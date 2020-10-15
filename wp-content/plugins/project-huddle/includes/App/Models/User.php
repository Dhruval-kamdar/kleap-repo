<?php

namespace PH\Models;

use PH\Models\Post;
use PH\Models\Comment;
use PH\Models\Activity;
use PH\Traits\CanBeSubscribed;
use PH\Traits\HasRestRequests;
use PH\Traits\HasSuppressions;

/**
 *  PH user class
 */
class User extends \WP_User
{
    use HasSuppressions, CanBeSubscribed, HasRestRequests;

    protected $rest_endpoint = 'users';

    public function __construct($id = 0)
    {
        parent::__construct($id);
    }

    public static function current()
    {
        $user = wp_get_current_user();
        return new self($user->ID);
    }

    public function can($action = 'read_post', Post $model)
    {
        if (!isset($model->ID)) {
            return new \WP_Error('not_found', 'Model needs an ID');
        }
        $cap = is_a($model, Comment::class) ? 'comment' : $model->getPostTypeObject()->cap->{$action};
        return user_can($this->ID, $cap, $model->ID);
    }

    /**
     * A user has project ids
     *
     * @return void
     */
    public function subscribedProjectIds()
    {
        return ph_get_users_project_ids($this->ID);
    }

    /**
     * A user has projects
     *
     * @param array $args
     * @return void
     */
    public function subscribedProjects($args = array())
    {
        $args['user_id'] = $this->ID; // force this user id
        return ph_get_users_projects($args);
    }

    /**
     * A user has subscribed threads
     *
     * @return void
     */
    public function subscribedTasks($args = array())
    {
        $args['user_id'] = $this->ID;
        return ph_get_users_threads($this->ID, $args);
    }

    /**
     * Alias
     */
    public function subscribedThreads($args)
    {
        return $this->subscribedTasks($args);
    }

    /**
     * A user has subscribed threads
     *
     * @return array
     */
    public function subscribedTaskIds()
    {
        return (array) ph_get_users_thread_ids($this->ID);
    }

    /**
     * Alias
     */
    public function subscribedThreadIds()
    {
        return $this->subscribedTaskIds();
    }

    /**
     * Get a users activity comments for a specified period
     *
     * @param array $args
     * @return PH\Models\Activity
     */
    public function projectsActivity()
    {
        return new Activity($this);
    }

    public function can_read($model)
    {
        if (!method_exists($model, 'projectId')) {
            return false;
        }
        // make sure visitor can access
        $validator = new \PH_Permissions_Controller($model->projectId());
        // check if visitor can access
        return $validator->visitor_can_access();
    }
}
