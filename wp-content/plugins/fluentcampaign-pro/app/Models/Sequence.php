<?php

namespace FluentCampaign\App\Models;

use FluentCrm\App\Models\CampaignEmail;
use FluentCrm\App\Models\Model;
use FluentCrm\App\Services\BlockParser;
use FluentCrm\App\Services\Helper;
use FluentCrm\Includes\Helpers\Arr;

class Sequence extends Model
{
    protected $table = 'fc_campaigns';

    protected $guarded = ['id'];

    protected static $type = 'email_sequence';

    public static function boot()
    {
        static::creating(function ($model) {
            $model->email_body = $model->email_body ? $model->email_body : '';
            $model->status = $model->status ?: 'draft';
            $model->type = self::$type;
            $model->design_template = $model->design_template ? $model->design_template : 'simple';
            $model->slug = $model->slug ?: sanitize_title($model->title, '', 'preview');
            $model->created_by = $model->created_by ?: get_current_user_id();
            $model->settings = $model->settings ? $model->settings : [
                'mailer_settings'     => [
                    'from_name'      => '',
                    'from_email'     => '',
                    'reply_to_name'  => '',
                    'reply_to_email' => '',
                    'is_custom'      => 'no'
                ]
            ];
        });

        static::addGlobalScope('type', function ($builder) {
            $builder->where('type', '=', self::$type);
        });
    }

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = \sanitize_title($slug, '', 'preview');
    }

    public function setSettingsAttribute($settings)
    {
        $this->attributes['settings'] = \maybe_serialize($settings);
    }

    public function getSettingsAttribute($settings)
    {
        return \maybe_unserialize($settings);
    }

    public function getRecipientsCountAttribute($recipientsCount)
    {
        return (int)$recipientsCount;
    }

    public function scopeOfType($query, $status)
    {
        return $query->where('status', $status);
    }

    public function subscribe($subscribers)
    {
        $sequenceEmails = SequenceMail::where('parent_id', $this->id)
            ->orderBy('delay', 'ASC')
            ->get();

        if ($sequenceEmails->empty()) {
            return [];
        }

        $firstSequence = $sequenceEmails[0];
        $nextSequence = null;
        $immediateSequences = [];

        foreach ($sequenceEmails as $sequence) {
            if ($sequence->delay == $firstSequence->delay) {
                $immediateSequences[] = $sequence;
            } else {
                if (!$nextSequence) {
                    $nextSequence = $sequence;
                }
                if ($sequence->delay < $nextSequence->delay) {
                    $nextSequence = $sequence;
                }
            }
        }

        return $this->attachEmails($subscribers, $immediateSequences, $nextSequence);
    }

    public function attachEmails($subscribers, $immediateSequences, $nextSequence, $tracker = false)
    {
        $scheduledEmails = [];
        $campaignUrl = [];
        $time = current_time('mysql');

        $parentCampaignId = false;

        foreach ($immediateSequences as $sequenceEmail) {
            $emailBody = $this->getParsedEmailBody($sequenceEmail);
            if (fluentcrmTrackClicking()) {
                $campaignUrl[$sequenceEmail->id] = Helper::urlReplaces($emailBody);
            }
            $parentCampaignId = $sequenceEmail->parent_id;
            $scheduledTime = $this->guessScheduledTime($sequenceEmail);
            $scheduledEmails[] = [
                'campaign_id'   => $sequenceEmail->id,
                'status'        => 'scheduled',
                'email_type'    => 'sequence_mail',
                'created_at'    => $time,
                'email_headers' => Helper::getMailHeadersFromSettings($sequenceEmail->settings['mailer_settings']),
                'email_subject' => $sequenceEmail->email_subject,
                'email_body'    => $emailBody,
                'scheduled_at'  => $scheduledTime,
                'updated_at'    => $time,
                'delay'         => $sequenceEmail->delay,
                'is_parsed' => 1
            ];
        }

        $insertIds = [];

        foreach ($subscribers as $subscriber) {
            if (!$subscriber) {
                continue;
            }
            foreach ($scheduledEmails as $scheduledEmail) {
                $sequenceEmailId = $scheduledEmail['campaign_id'];
                $urls = [];
                if (!empty($campaignUrl[$sequenceEmailId])) {
                    $urls = $campaignUrl[$sequenceEmailId];
                }

                $emailBody = apply_filters('fluentcrm-parse_campaign_email_text', $scheduledEmail['email_body'], $subscriber);

                $scheduledEmail['subscriber_id'] = $subscriber->id;
                $scheduledEmail['email_address'] = $subscriber->email;
                $scheduledEmail['email_subject'] = apply_filters('fluentcrm-parse_campaign_email_text', $scheduledEmail['email_subject'], $subscriber);

                if (!$urls) {
                    $scheduledEmail['email_body'] = $emailBody;
                }

                $delay = ($scheduledEmail['delay']) ? $scheduledEmail['delay'] : 0;
                unset($scheduledEmail['delay']);

                $inserted = CampaignEmail::create($scheduledEmail);
                $insertIds[] = $inserted->id;

                $updateData = [
                    'email_hash' => Helper::generateEmailHash($inserted->id)
                ];

                if ($urls) {
                    $updateData['email_body'] = Helper::attachUrls($emailBody, $urls, $inserted->id);
                }

                $inserted->update($updateData);


                $trackerData = [
                    'subscriber_id'       => $subscriber->id,
                    'campaign_id'         => $parentCampaignId,
                    'last_sequence_id'    => $scheduledEmail['campaign_id'],
                    'next_sequence_id'    => ($nextSequence) ? $nextSequence->id : NULL,
                    'status'              => ($nextSequence) ? 'active' : 'completed',
                    'last_executed_time'  => current_time('mysql'),
                    'next_execution_time' => ($nextSequence) ? date('Y-m-d H:i:s', current_time('timestamp') + $nextSequence->delay - $delay - HOUR_IN_SECONDS) : NULL
                ];

                if (!$tracker) {
                    SequenceTracker::updateOrCreate([
                        'subscriber_id' => $subscriber->id,
                        'campaign_id'   => $parentCampaignId
                    ], $trackerData);
                } else {
                    $tracker->update($trackerData);
                }
            }
        }

        return $insertIds;
    }

    private function getParsedEmailBody($sequenceEmail)
    {
        static $parsedEmailBody = [];
        if (isset($parsedEmailBody[$sequenceEmail->id])) {
            return $parsedEmailBody[$sequenceEmail->id];
        }
        $parsedEmailBody[$sequenceEmail->id] = (new BlockParser())->parse($sequenceEmail->email_body);
        return $parsedEmailBody[$sequenceEmail->id];
    }

    public function unsubscribe($subscriberIds, $note = '')
    {
        $sequenceEmails = SequenceMail::where('parent_id', $this->id)
            ->orderBy('delay', 'ASC')
            ->get();

        if (!$sequenceEmails) {
            return;
        }

        $sequenceEmailIds = $sequenceEmails->pluck('id');

        return CampaignEmail::whereIn('campaign_id', $sequenceEmailIds)
            ->where('status', 'scheduled')
            ->where('email_type', 'sequence_mail')
            ->whereIn('subscriber_id', $subscriberIds)
            ->update([
                'status' => 'cancelled',
                'note'   => $note
            ]);
    }

    private function guessScheduledTime($sequenceEmail)
    {
        $sendingTimes = Arr::get($sequenceEmail->settings, 'timings.sending_time', []);

        if (!is_array($sendingTimes)) {
            return current_time('mysql');
        }

        $sendingTimes = array_filter($sendingTimes);

        if (!$sendingTimes || count($sendingTimes) != 2) {
            return current_time('mysql');
        }

        $diff = absint(strtotime($sendingTimes[1]) - strtotime($sendingTimes[0]));

        $baseDate = current_time('Y-m-d ' . $sendingTimes[0]);

        return date('Y-m-d H:i:s', strtotime($baseDate) + mt_rand(0, $diff));

    }

    public function stat()
    {
        $campaignMails = SequenceMail::select('id')
            ->where('parent_id', $this->id)
            ->count();

        $subscribers = SequenceTracker::where('campaign_id', $this->id)->count();

        return [
            'emails'      => $campaignMails,
            'subscribers' => $subscribers
        ];
    }

}
