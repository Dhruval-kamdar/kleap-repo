<?php

namespace PH\Listeners\Mail;

use PH\Models\Thread;
use PH\Support\Mail\ImmediateMail;
use PH\Controllers\Mail\Mailers\Mailer;

if (!defined('ABSPATH')) exit;

/**
 * Send activity summary mail
 */
class SendCommentMail extends ImmediateMail
{
    /**
     * Handle
     *
     * @param integer $id
     * @param WP_Comment $comment
     * @param array $mentioned_user_ids
     * @return void
     */
    public function handle($id, $comment, $mentioned_user_ids)
    {
        // must have a thread
        if (!isset($comment->comment_post_ID)) {
            return;
        }

        // get thread
        if (!$thread = new Thread($comment->comment_post_ID)) {
            return;
        }

        // get members
        if (!$members = $thread->subscribedUsers()) {
            return;
        }

        // send each email individually
        foreach ($members as $member) {
            // exclude mentioned users
            if (in_array($member->ID, $mentioned_user_ids)) {
                continue;
            }

            // exclude user who commented
            if (get_current_user_id() === $member->ID) {
                continue;
            }

            // send email
            try {
                (new Mailer('comments', $thread->projectId()))
                    ->to($member)
                    ->template(
                        ph_locate_template('email/new-comment-email.php'),
                        [
                            'commenter'    => sanitize_text_field($comment->comment_author),
                            'avatar'       => $this->avatar(get_current_user_id()),
                            'project_name' => ph_get_the_title($thread->parentsIds()['project']),
                            'item_name'    => ph_get_the_title($thread->parentsIds()['item']),
                            'content'      => wpautop($comment->comment_content),
                            'link'         => ph_email_link($thread->getAccessLink(), __('View Comment', 'project-huddle')),
                        ]
                    )
                    ->subject(apply_filters('ph_mockup_new_comment_email_subject', sprintf(__('%1$1s made a new comment on %2$2s.', 'project-huddle'), '{{commenter}}', '{{project_name}}'), $id, $comment, $member->user_email))
                    ->send();
            } catch (Exception $e) {
                // log error but don't crash anything
                error_log($e);
            }
        }
    }
}
