<?php

/**
 * Settings page settings
 *
 * @package     Project Huddle
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Function that holds the array of fields for the settings page
 *
 * @since 1.0.0
 * @return array Fields to be displayed on settings page
 */
function ph_settings_fields()
{

	/**
	 * Filters are provided for each settings section to allow plugins
	 * to add their own settings to an already created section.
	 */
	$settings['customize'] = apply_filters('ph_settings_customize', array(
		'title'  => __('Customize', 'project-huddle'),
		'fields' => array(
			'logo_divider' => array(
				'id'          => 'logo_divider',
				'label'       => __('Logos', 'project-huddle'),
				'description' => '',
				'type'        => 'divider',
			),
			'login_logo' => array(
				'id'          => 'login_logo',
				'label'       => __('Light Logo', 'project-huddle'),
				'description' => __('Logo used on light backgrounds. Appears on login forms and at the top of emails.', 'project-huddle'),
				'type'        => 'image',
				'default'     => '',
				'placeholder' => ''
			),
			'login_logo_retina' => array(
				'id'          => 'login_logo_retina',
				'label'       => __('Retina', 'project-huddle'),
				'description' => __('This will display your logo at half it\'s size.', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			),

			'control_logo' => array(
				'id'          => 'control_logo',
				'label'       => __('Dark Logo', 'project-huddle'),
				'description' => __('Logo used on dark backgrounds. Appears on control bars.', 'project-huddle'),
				'type'        => 'image',
				'default'     => '',
				'placeholder' => ''
			),
			'control_logo_retina' => array(
				'id'          => 'control_logo_retina',
				'label'       => __('Retina', 'project-huddle'),
				'description' => __('This will display your logo at half it\'s size.', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			),

			/* Highlight Color */
			'highlight_divider' => array(
				'id'          => 'highlight_divider',
				'label'       => __('Highlights', 'project-huddle'),
				'description' => '',
				'type'        => 'divider',
			),
			'highlight_color' => array(
				'id'          => 'highlight_color',
				'label'       => __('Highlight Color', 'project-huddle'),
				'description' => __('Choose a highlight color to match your brand.', 'project-huddle'),
				'type'        => 'color',
				'default'     => '#4353ff'
			),

			/* Comments */
			'permissions_divider' => array(
				'id'          => 'permissions_divider',
				'label'       => __('Permissions', 'project-huddle'),
				'description' => '',
				'type'        => 'divider',
			),
			'un_silo' => array(
				'id'          => 'un_silo',
				'label'       => __('Universal Project Access', 'project-huddle'),
				'description' => __('Allow Project Client and Project Collaborators to view and access projects they aren\'t subscribed to.', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => 'on',
			)
		)
	));

	$settings['approvals'] = apply_filters('ph_settings_approvals', [
		'title'  => __('Approvals', 'project-huddle'),
		'fields' => [
			array(
				'id'          => 'require_terms',
				'label'       => __('Approval Terms &amp; Conditions Checkbox', 'project-huddle'),
				'description' => __('Option to require terms and conditions checkbox on approvals.', 'project-huddle'),
				'type'        => 'radio',
				'options'     => array(
					0 => __('Don\'t require terms agreement', 'project-huddle'),
					1 => __('Require terms agreement', 'project-huddle')
				),
				'default'     => 0
			),
			array(
				'id'          => 'approve_terms_checkbox_text',
				'label'       => __('Approval Terms Checkbox Text', 'project-huddle'),
				'description' => __('Approval Terms Checkbox Text. Use {{terms}} to place the terms link and {{user_name}} to display the current identified user.', 'project-huddle'),
				'type'        => 'text',
				'default'     => sprintf(__('I, %2$s, read and agree with the %1$s.', 'project-huddle'), '{{terms}}', '{{user_name}}'),
				'required'    => array(
					'require_terms' => 1
				)
			),
			array(
				'id'          => 'approve_terms_link_text',
				'label'       => __('Approval Terms Link Text', 'project-huddle'),
				'description' => __('Clickable text to show the terms.', 'project-huddle'),
				'type'        => 'text',
				'default'     => __('Terms', 'project-huddle'),
				'required'    => array(
					'require_terms' => 1
				)
			),
			array(
				'id'          => 'approve_terms',
				'label'       => __('Approval Terms', 'project-huddle'),
				'description' => __('Full Terms and Conditions. HTML Allowed.', 'project-huddle'),
				'type'        => 'textarea',
				'default'     => '',
				'required'    => array(
					'require_terms' => 1
				)
			),
		]
	]);

	// $settings['mockups'] = apply_filters('ph_settings_mockups', array(
	// 	'title'  => __('Mockups', 'project-huddle'),
	// 	'fields' => array(
	// 		/* Project Background */
	// 		'project_image_divider' => array(
	// 			'id'          => 'project_image_divider',
	// 			'label'       => __('Project Image', 'project-huddle'),
	// 			'description' => '',
	// 			'type'        => 'divider',
	// 		),
	// 		'image_bg'  => array(
	// 			'id'          => 'image_bg',
	// 			'label'       => __('Default Project Background Color', 'project-huddle'),
	// 			'description' => __('The default project image background color. You can overwrite this per image in your individual projects.', 'project-huddle'),
	// 			'type'        => 'color',
	// 			'default'     => '#191d21'
	// 		),
	// 	)
	// ));

	$settings['email'] = apply_filters('ph_settings_email', array(
		'title'  => __('Emails', 'project-huddle'),
		'fields' => array(
			// 'admin_emails' => array(
			// 	'id'          => 'admin_emails',
			// 	'label'       => __('Admin Emails', 'project-huddle'),
			// 	'description' => __('Additionally send an email to the site admin for new project actions.', 'project-huddle'),
			// 	'type'        => 'checkbox',
			// 	'default'     => '',
			// ),
			/* Project Background */
			// 'email_sender_options' => array(
			// 	'id'          => 'email_sender_options',
			// 	'label'       => __('Email Sender Options', 'project-huddle'),
			// 	'description' => '',
			// 	'type'        => 'divider',
			// ),
			'email_from_name' => array(
				'id'          => 'email_from_name',
				'label'       => __('"From" Name', 'project-huddle'),
				'description' => __('This is the name of the email sender.', 'project-huddle'),
				'type'        => 'text',
				'default'     => get_bloginfo('name'),
			),
			'email_from_address' => array(
				'id'          => 'email_from_address',
				'label'       => __('"From" Address', 'project-huddle'),
				'description' => __('This is the email address of the sender.', 'project-huddle'),
				'type'        => 'text',
				'default'     => get_option('admin_email'),
			),
			// 'background_email' => array(
			// 	'id'          => 'background_emails',
			// 	'label'       => __('Background Email Processing', 'project-huddle'),
			// 	'description' => __('Check this box to enable sending emails in the background. Uncheck if you\'re having trouble with emails sending.', 'project-huddle'),
			// 	'type'        => 'checkbox',
			// 	'default'     => '',
			// )
		)
	));

	$settings['advanced'] = apply_filters('ph_settings_advanced', array(
		'title'  => __('Advanced', 'project-huddle'),
		'fields' => array(
			'error_reporting' => array(
				'id'          => 'error_reporting',
				'label'       => __('Send Error Reports', 'project-huddle'),
				'description' => __('Check this box to turn on sending error and reports to ProjectHuddle support.', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			),
			'script_debug' => array(
				'id'          => 'script_debug',
				'label'       => __('Turn on script debugging', 'project-huddle'),
				'description' => __('Check this box to turn on helpful script debugging messages.', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			),
			'rerun_setup' => array(
				'id'          => 'rerun_setup',
				'label'       => __('Setup Wizard', 'project-huddle'),
				'description' => __('Run the ProjectHuddle setup wizard', 'project-huddle'),
				'type'        => 'button',
				'default'     => admin_url('admin.php?page=ph-setup'),
			),
			'images_trash_bin' => array(
				'id'          => 'images_trash_bin',
				'label'       => __('Restore Trashed Mockup Images', 'project-huddle'),
				'description' => __('Restore trashed images to their original projects.', 'project-huddle'),
				'type'        => 'button',
				'default'     => admin_url('edit.php?post_status=trash&post_type=project_image'),
			),
			'comment_locations_trash_bin' => array(
				'id'          => 'comment_locations_trash_bin',
				'label'       => __('Restore Trashed Mockup Threads', 'project-huddle'),
				'description' => __('Restore trashed comment threads to their original images.', 'project-huddle'),
				'type'        => 'button',
				'default'     => admin_url('edit.php?post_status=trash&post_type=ph_comment_location'),
			),
			'website_comments_trash_bin' => array(
				'id'          => 'website_comments_trash_bin',
				'label'       => __('Restore Trashed Website Threads', 'project-huddle'),
				'description' => __('Restore trashed website comment threads to their original pages.', 'project-huddle'),
				'type'        => 'button',
				'default'     => admin_url('edit.php?post_status=trash&post_type=phw_comment_loc'),
			),
			'website_pages_trash_bin' => array(
				'id'          => 'website_pages_trash_bin',
				'label'       => __('Restore Trashed Website Pages', 'project-huddle'),
				'description' => __('Restore trashed website pages to their original state.', 'project-huddle'),
				'type'        => 'button',
				'default'     => admin_url('edit.php?post_status=trash&post_type=ph-webpage'),
			),
			'script_shielding' => array(
				'id'          => 'script_shielding',
				'label'       => __('Disable Script Shielding', 'project-huddle'),
				'description' => __('Check this box to disable auto-dequeuing of theme styles and scripts on Project and Website pages.', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			),
			'use_php_sessions' => array(
				'id'          => 'use_php_sessions',
				'label'       => __('Use Native PHP Sessions', 'project-huddle'),
				'description' => __('Check this box to enable native PHP Sessions (not supported on all servers).', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			),
			'uninstall_data_on_delete' => array(
				'id'          => 'uninstall_data_on_delete',
				'label'       => __('Remove All ProjectHuddle Data on Delete?', 'project-huddle'),
				'description' => __('This will remove all data when ProjectHuddle is deleted. This is irreversible!', 'project-huddle'),
				'type'        => 'checkbox',
				'default'     => '',
			)
		)
	));

	// allow filter of fields
	$settings = apply_filters('project_huddle_settings_fields', $settings);

	return $settings;
}

/**
 * Hide updates field for subsites
 *
 * @param $settings
 *
 * @return mixed
 */
function ph_hide_settings_fields_for_subsites($settings)
{
	if (is_multisite() && !is_main_site()) {
		unset($settings['updates']);
	}
	return $settings;
}
add_filter('project_huddle_settings_fields', 'ph_hide_settings_fields_for_subsites', 20);

/**
 * Empty extensions tab
 * @param $extensions
 *
 * @return mixed
 */
function ph_empty_extensions($extensions)
{
	if (empty($extensions['fields'])) {
		$extensions['fields'] = array(
			/* Comments */
			'no_extensions' => array(
				'id'          => 'no_extensions',
				'label'       => __('No extensions installed.', 'project-huddle'),
				'description' => '',
				'html'        => __('Please stay tuned for available extensions!', 'project-huddle'),
				'type'        => 'custom',
			),
		);
	}

	return $extensions;
}

add_filter('ph_settings_extensions', 'ph_empty_extensions', 9999);
