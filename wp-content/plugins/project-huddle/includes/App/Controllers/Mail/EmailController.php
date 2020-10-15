<?php

namespace PH\Controllers\Mail;

use PH\Models\User;

/**
 * Batch Emails Class
 * This class scehdules and sends periodic emails for latest activity between two dates
 *
 * @package     ProjectHuddle
 * @copyright   Copyright (c) 2015, Andre Gagnon
 * @since       1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

// include actions scheduler
require_once PH_PLUGIN_DIR . 'includes/libraries/action-scheduler/action-scheduler.php';

class EmailController
{
	/**
	 * Store the email interval
	 *
	 * @var String
	 */
	protected $interval;

	/**
	 * Hook into WP
	 */
	public function __construct()
	{
		add_action('ph_settings_email', array($this, 'admin_settings'));
		add_action('admin_init', array($this, 'start_schedule'));

		add_action('update_option_ph_weekly_email', array($this, 'weekly_email_schedule'), 10, 2);
		add_action('add_option_ph_weekly_email', array($this, 'weekly_email_schedule'), 10, 2);

		add_action('update_option_ph_daily_email', array($this, 'daily_email_schedule'), 10, 2);
		add_action('add_option_ph_daily_email', array($this, 'daily_email_schedule'), 10, 2);

		add_action('update_option_ph_email_throttle', array($this, 'activity_email_schedule'), 10, 2);
		add_action('add_option_ph_email_throttle', array($this, 'activity_email_schedule'), 10, 2);

		// failed action handling
		add_action('action_scheduler_failed_action', array($this, 'reschedule'), 10);
		add_action('action_scheduler_failed_execution', array($this, 'reschedule'), 10);
		add_action('action_scheduler_unexpected_shutdown', array($this, 'reschedule'), 10);
		add_action('action_scheduler_failed_fetch_action', array($this, 'reschedule'), 10);
	}

	/**
	 * Start all email schedule
	 *
	 * @return void
	 */
	public function start_schedule()
	{
		if (\get_option('ph_emails_scheduled', false)) {
			return;
		}
		$this->reschedule();
		\update_option('ph_emails_scheduled', true);
	}

	/**
	 * Get the last scheduled action date
	 *
	 * @param String $hook
	 * @param Array|null $args
	 * @param String $group
	 * @return String|Boolean
	 */
	public function get_last_scheduled_action_date($hook, $args = NULL, $group = '')
	{
		$params = array(
			'status' => \ActionScheduler_Store::STATUS_COMPLETE,
		);
		if (is_array($args)) {
			$params['args'] = $args;
		}
		if (!empty($group)) {
			$params['group'] = $group;
		}
		$job_id = \ActionScheduler::store()->find_action($hook, $params);
		if (empty($job_id)) {
			return false;
		}
		$completed_comment = get_comments(array(
			'post_id' => $job_id,
			'type' => 'action_log',
			'number' => 1
		));

		if (!empty($completed_comment)) {
			return $completed_comment[0]->comment_date;
		}
		return false;
	}

	/**
	 * Reschedule failed action
	 */
	public function reschedule()
	{
		// reschedule all on failure
		$this->weekly_email_schedule(false, \get_option('ph_weekly_email', 'on'));
		$this->daily_email_schedule(false, \get_option('ph_daily_email', 'on'));
		$this->activity_email_schedule(false, $this->getSavedInterval());
	}

	/**
	 * Add admin setting
	 *
	 * @param [type] $settings
	 * @return void
	 */
	public function admin_settings($settings)
	{
		$settings['fields']['email_behavior_options']  = array(
			'id'          => 'email_behavior_options',
			'label'       => __('Email Options', 'project-huddle'),
			'description' => '',
			'type'        => 'divider',
		);

		$settings['fields']['email_throttle'] = array(
			'id'          => 'email_throttle',
			'label'       => __('Email Frequency', 'project-huddle'),
			'description' => 'Choose to send immediate emails, or get a single email with all activity included within that time period.',
			'type'        => 'radio',
			'options'     => array(
				'off'         => __('Don\'t send any activity emails automatically.', 'project-huddle'),
				'immediate'   => __('Immediately email subscribed users about each item right away.', 'project-huddle'),
				5   => __('Email a summary every 5 minutes at most.', 'project-huddle'),
				30  => __('Email a summary every 30 minutes at most.', 'project-huddle'),
				180 => __('Email a summary every 3 hours at most.', 'project-huddle'),
			),
			'default'     => 'immediate',
		);

		$settings['fields']['daily_email'] = array(
			'id'          => 'daily_email',
			'label'       => __('Daily Email Summary', 'project-huddle'),
			'description' => __('Send a daily summary email to members of a project.', 'project-huddle'),
			'type'        => 'checkbox',
			'default'     => 'on',
		);

		$settings['fields']['weekly_email'] = array(
			'id'          => 'weekly_email',
			'label'       => __('Weekly Email Summary', 'project-huddle'),
			'description' => __('Send a weekly summary email to members of a project.', 'project-huddle'),
			'type'        => 'checkbox',
			'default'     => 'on',
		);
		return $settings;
	}

	/**
	 * Schedules or unschedules a daily email if value is updated
	 *
	 * @param boolean $old_value
	 * @param boolean $value
	 * @return void
	 */
	public function activity_email_schedule($old_value, $value)
	{
		// always change
		\as_unschedule_action('ph_activity_summary_email');

		if ('immediate' === $value || 'off' === $value) {
			return;
		}

		if (false === \as_next_scheduled_action('ph_activity_summary_email') && $value) {
			\as_schedule_recurring_action(strtotime("now"), strtotime($value . ' minutes', 0), 'ph_activity_summary_email', array(), 'email');
		}
	}

	/**
	 * Schedules or unschedules a daily email if value is updated
	 *
	 * @param boolean $old_value
	 * @param boolean $value
	 * @return void
	 */
	public function daily_email_schedule($old_value, $value)
	{
		// unschedule if unchecked
		if (!filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
			as_unschedule_action('ph_daily_summary_email');
			return;
		}

		if (false === as_next_scheduled_action('ph_daily_summary_email')) {
			// allow filtering of day and time
			$time = apply_filters('ph_daily_email_time', '6pm');

			as_schedule_recurring_action(strtotime("$time"), strtotime('1 day', 0), 'ph_daily_summary_email', array(), 'email');
		}
	}

	/**
	 * Schedules or unschedules weekly email if value is updated
	 *
	 * @param boolean $old_value
	 * @param boolean $value
	 * @return void
	 */
	public function weekly_email_schedule($old_value, $value)
	{
		// unschedule if unchecked
		if (!filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
			\as_unschedule_action('ph_weekly_summary_email');
			return;
		}

		if (false === \as_next_scheduled_action('ph_weekly_summary_email')) {
			// allow filtering of day and time
			$day  = apply_filters('ph_weekly_email_day', 'Friday');
			$time = apply_filters('ph_weekly_email_time', '6am');

			as_schedule_recurring_action(strtotime("this $day $time"), strtotime('1 week', 0), 'ph_weekly_summary_email', array(), 'email');
		}
	}

	public function getSavedInterval()
	{
		return get_option('ph_email_throttle', 'immediate');
	}

	/**
	 * Get activity interval
	 *
	 * @return void
	 */
	public function get_interval()
	{
		if (!$this->interval) {
			$this->interval = $this->getSavedInterval();
		}
		if ('immediate' === $this->interval || 'off' === $this->interval) {
			return 0;
		}
		return (int) $this->interval;
	}

	/**
	 * Are emails throttled
	 */
	public function is_throttled()
	{
		return $this->get_interval() > 0;
	}

	public function emailsEnabled()
	{
		$this->get_interval();
		return $this->interval !== 'off';
	}
}
