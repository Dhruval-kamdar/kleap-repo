<?php

namespace PH\Controllers;

use PH\Models\Item;
use PH\Models\Project;
use PH\Contracts\Model;

class ApprovalController
{
    public function __construct()
    {
        // set history
        add_action('ph_item_approval', [$this, 'saveHistory'], 10, 3);
        add_action('ph_project_approval', [$this, 'saveHistory'], 10, 3);

        // do triggers
        add_action('ph_set_approval', [$this, 'triggers'], 10, 3);

        // clear transients
        add_action('untrashed_post', [$this, 'clearProjectTransient']);
    }

    /**
     * Get approval status
     *
     * @param int $id
     * @return void
     */
    public function getStatus($id)
    {
        // backwards compat
        if ($approved = get_post_meta($id, 'approval', true)) {
            return (bool) $approved;
        }
        return (bool) get_post_meta($id, 'approved', true);
    }

    /**
     * Save Approval
     *
     * @param \App\Model $model
     * @param bool $approved
     * @return void
     */
    public function save($model, $approved)
    {
        $isNew = true;
        if (metadata_exists('post', $model->ID, 'approved') || metadata_exists('post', $model->ID, 'approval')) {
            $isNew = false;
        }

        $meta = update_post_meta($model->ID, 'approved', (bool) $approved);
        // $meta = update_post_meta($model->ID, 'approval', (bool) $approved); // legacy
        do_action('ph_set_approval', $model, (bool) $approved, $isNew);
        return $meta;
    }

    /**
     * Get approval history
     */
    public function getHistory($id, $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'post_id'  => $id,
                'type__in' => array(
                    'ph_approval',
                )
            ]
        );
        return ph_get_comments($args);
    }

    /**
     * Save approval in history
     *
     * @param \PH\Model $model
     * @param bool $approved
     * @return void
     */
    public function saveHistory(Model $model, $approved, $isNew = false)
    {
        if ($isNew && !$approved) {
            return;
        }

        // needs a current user
        if (!$user = wp_get_current_user()) {
            return false;
        }

        // unsert approval comment
        return wp_insert_comment(
            array(
                'comment_post_ID'      => $model->ID,
                'comment_author'       => $user->display_name,
                'comment_author_email' => $user->user_email,
                'user_id'              => $user->ID,
                'comment_content'      => $approved ? 'approved' : 'unapproved',
                'comment_type'         => 'ph_approval',
                'comment_approved'     => 1, // force approval.
                'comment_meta'         => [
                    'approval' => (bool) $approved,
                ],
            )
        );
    }

    /**
     * Send correct triggers for mail, etc.
     *
     * @param bool $approved
     * @param int $id
     * @param bool $isNew Is this a new project
     * @param PH\Contracts\Model $model
     * @return void
     */
    public function triggers(Model $model, $approved, $isNew = false)
    {
        global $is_ph_batch;

        // if we're approving a project, do that!
        if (is_a($model, Project::class)) {
            do_action('ph_project_approval', $model, $approved, $isNew);
            return;
        }

        // make sure we're not running other batches
        static $batch_running;
        if ($batch_running) {
            return;
        }

        // if we're batch approving images, do a project approval action
        if ($is_ph_batch) {
            $batch_running = true;
            do_action('ph_project_approval', $model->project(), $approved, $isNew);
        } else {
            // if all siblings are approved, trigger project approval
            if ($model->siblingsApproved()) {
                do_action('ph_project_approval', $model->project(), $approved, $isNew);
                // trigger item approval
            } else {
                do_action('ph_item_approval', $model, $approved, $isNew);
            }
        }
    }

    /**
     * Are it's siblings approved too?
     *
     * @param int $parent_id
     * @param string $project_type
     * @return void
     */
    public function siblingsApproved($parent_id, $project_type)
    {
        $all_approved = false;
        $approval_status = ph_get_items_approval_status($parent_id, $project_type);

        if (!empty($approval_status)) {
            $all_approved = $approval_status['total'] == $approval_status['approved'];
        }

        return $all_approved;
    }

    public function clearProjectTransient($model)
    {
        // maybe get item
        if (is_int($model)) {
            $model = Item::get($model);
        }

        // only for items
        if (!is_a($model, Item::class)) {
            return;
        }

        // delete project transient
        if ($model->projectId()) {
            delete_transient("ph_approved_status_" . $model->projectId());
        }
    }
}
