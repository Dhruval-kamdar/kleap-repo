<?php

namespace FluentCampaign\App\Http\Controllers;

use FluentCampaign\App\Services\DynamicSegments\CustomSegment;
use FluentCrm\App\Http\Controllers\Controller;
use FluentCrm\App\Models\Lists;
use FluentCrm\App\Models\Meta;
use FluentCrm\App\Models\Tag;
use FluentCrm\Includes\Request\Request;

class DynamicSegmentController extends Controller
{
    public function index()
    {
        $segments = apply_filters('fluentcrm_dynamic_segments', []);

        return $this->sendSuccess([
            'dynamic_segments' => $segments
        ]);
    }

    public function getSegment(Request $request, $slug, $id)
    {
        $segment = apply_filters('fluentcrm_dynamic_segment_' . $slug, null, $id, [
            'subscribers' => true,
            'paginate'    => true
        ]);

        return $this->sendSuccess([
            'segment' => $segment
        ]);
    }

    public function getCustomFields(Request $request)
    {
        $textOperators = [
            '='        => 'Equal',
            '!='       => 'Not Equal',
            'LIKE'     => 'Contains',
            'NOT LIKE' => 'Not Contains'
        ];
        $selectOptions = [
            'whereIn'    => 'In',
            'whereNotIn' => 'Not In'
        ];
        $dateOperators = [
            '>=' => 'Within',
            '<=' => 'Before',
        ];
        $subscriptionOptions = [];
        foreach (fluentcrm_subscriber_statuses() as $option) {
            $subscriptionOptions[$option] = ucfirst($option);
        }

        $fields = [
            [
                'type'    => 'condition_blocks',
                'key'     => 'conditions',
                'heading' => 'Conditions',
                'label'   => 'Select conditions which will define this segment.',
                'fields'  => [
                    'email'         => [
                        'type'      => 'text',
                        'label'     => 'Contact Email',
                        'operators' => $textOperators,
                        'value'     => ''
                    ],
                    'first_name'    => [
                        'type'      => 'text',
                        'label'     => 'First Name',
                        'operators' => $textOperators,
                        'value'     => ''
                    ],
                    'last_name'     => [
                        'type'      => 'text',
                        'label'     => 'Last Name',
                        'operators' => $textOperators,
                        'value'     => ''
                    ],
                    'city'          => [
                        'type'      => 'text',
                        'label'     => 'City',
                        'operators' => $textOperators,
                        'value'     => ''
                    ],
                    'state'         => [
                        'type'      => 'text',
                        'label'     => 'State',
                        'operators' => $textOperators,
                        'value'     => ''
                    ],
                    'country'       => [
                        'type'        => 'option-selector',
                        'option_key'  => 'countries',
                        'is_multiple' => true,
                        'label'       => 'Country',
                        'operators'   => $selectOptions,
                        'value'       => []
                    ],
                    'status'        => [
                        'type'        => 'select',
                        'is_multiple' => true,
                        'label'       => 'Subscription Status',
                        'operators'   => $selectOptions,
                        'value'       => [],
                        'options'     => $subscriptionOptions
                    ],
                    'source'        => [
                        'type'      => 'text',
                        'label'     => 'Source',
                        'operators' => $textOperators,
                        'value'     => ''
                    ],
                    'created_at'    => [
                        'type'      => 'days_ago',
                        'label'     => 'Created at',
                        'operators' => $dateOperators
                    ],
                    'last_activity' => [
                        'type'        => 'days_ago',
                        'label'       => 'Last Contact Activity',
                        'description' => 'Activity on your site login, email link click or various other activities',
                        'operators'   => $dateOperators
                    ],
                    'tags'          => [
                        'type'        => 'select',
                        'is_multiple' => true,
                        'label'       => 'Tags',
                        'operators'   => [
                            'whereIn' => 'In',
                            'whereNotIn' => 'Not In'
                        ],
                        'value'       => [],
                        'options'     => $this->getTagOptions()
                    ],
                    'lists'         => [
                        'type'        => 'select',
                        'is_multiple' => true,
                        'label'       => 'Lists',
                        'operators'   => [
                            'whereIn' => 'In',
                            'whereNotIn' => 'Not In'
                        ],
                        'value'       => [],
                        'options'     => $this->getListOptions()
                    ]
                ]
            ],
            [
                'type'    => 'radio_section',
                'key'     => 'condition_match',
                'heading' => 'Match Type',
                'label'   => 'Should contacts in this segment should match any or all the above conditions?',
                'options' => [
                    'match_all' => 'Match All Conditions',
                    'match_any' => 'Match Any One Condition'
                ]
            ],
            [
                'type'    => 'activities_blocks',
                'key'     => 'email_activities',
                'heading' => 'Filter By Email Activities',
                'label'   => 'Filter your contacts by from email open or email link click metrics. Leave the values blank for not applying',
                'fields'  => [
                    'status'                    => [
                        'type'  => 'yes_no_check',
                        'label' => 'Enable Last Email Activity Filter'
                    ],
                    'last_email_open'           => [
                        'type'        => 'days_ago_with_operator',
                        'label'       => 'Last Email Open',
                        'options'     => $dateOperators,
                        'inline_help' => 'Keep days 0/Blank for disable'
                    ],
                    'last_email_link_click'     => [
                        'type'        => 'days_ago_with_operator',
                        'label'       => 'Last Email Link Clicked',
                        'options'     => $dateOperators,
                        'inline_help' => 'Keep days 0/Blank for disable'
                    ],
                    'last_email_activity_match' => [
                        'heading' => 'Match Type',
                        'label'   => 'Should Match Both Open & Click Condition?',
                        'options' => [
                            'match_all' => 'Match Both Open and Click Condition',
                            'match_any' => 'Match Any One Condition'
                        ]
                    ]
                ]
            ]
        ];

        $settingsDefaults = [
            'conditions'       => [
                [
                    'field'    => '',
                    'operator' => '',
                    'value'    => ''
                ]
            ],
            'condition_match'  => 'match_all',
            'email_activities' => [
                'status'                    => 'no',
                'last_email_open'           => [
                    'value'    => 0,
                    'operator' => '>='
                ],
                'last_email_link_click'     => [
                    'value'    => 0,
                    'operator' => '>='
                ],
                'last_email_activity_match' => 'match_any'
            ]
        ];

        return $this->sendSuccess([
            'fields'            => $fields,
            'settings_defaults' => $settingsDefaults
        ]);
    }

    public function createCustomSegment(Request $request)
    {
        $segment = \json_decode($request->get('segment'), true);

        if (empty($segment['title'])) {
            return $this->sendError([
                'message' => 'Please provide segment title'
            ]);
        }

        $segmentData = [
            'object_type' => 'custom_segment',
            'key'         => 'custom_segment',
            'value'       => maybe_serialize($segment),
            'updated_at'  => fluentCrmTimestamp()
        ];

        $segmentData['created_at'] = fluentCrmTimestamp();
        $inserted = Meta::insert($segmentData);
        $segment['id'] = $inserted;
        $segment['slug'] = 'custom_segment';

        return $this->sendSuccess([
            'message' => 'Segment has been created',
            'segment' => $segment
        ]);
    }

    public function updateCustomSegment(Request $request, $segmentId)
    {
        $segment = \json_decode($request->get('segment'), true);

        if (empty($segment['title'])) {
            return $this->sendError([
                'message' => 'Please provide segment title'
            ]);
        }

        unset($segment['id']);

        $segmentData = [
            'object_type' => 'custom_segment',
            'key'         => 'custom_segment',
            'value'       => maybe_serialize($segment),
            'updated_at'  => fluentCrmTimestamp()
        ];

        Meta::where('id', $segmentId)
            ->where('object_type', 'custom_segment')
            ->update($segmentData);
        $segment['id'] = $segmentId;

        return $this->sendSuccess([
            'message' => 'Segment has been updated',
            'segment' => $segment
        ]);
    }

    public function deleteCustomSegment(Request $request, $segmentId)
    {
        if (!$segmentId) {
            return $this->sendError([
                'message' => 'Sorry! No segment found'
            ]);
        }

        Meta::where('object_type', 'custom_segment')
            ->where('id', $segmentId)
            ->delete();

        return $this->sendSuccess([
            'message' => 'Selected segment has been deleted'
        ]);
    }

    public function getTagOptions()
    {
        $tags = Tag::get();
        $formattedTags = [];
        foreach ($tags as $tag) {
            $formattedTags[strval($tag->id)] = $tag->title;
        }

        return $formattedTags;
    }

    public function getListOptions()
    {
        $lists = Lists::get();
        $formattedLists = [];
        foreach ($lists as $list) {
            $formattedLists[strval($list->id)] = $list->title;
        }

        return $formattedLists;
    }

    public function getEstimatedContacts(Request $request)
    {
        $settings = $request->get('settings');
        $customSegmentModel = (new CustomSegment())->getModel(['settings' => $settings]);
        return [
            'count' => $customSegmentModel->count()
        ];
    }
}
