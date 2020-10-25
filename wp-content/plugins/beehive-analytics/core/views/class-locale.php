<?php
/**
 * The locale view class of the plugin.
 *
 * This class will handle all the strings required in Vue files.
 *
 * @link    http://premium.wpmudev.org
 * @since   3.2.4
 *
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Views
 */

namespace Beehive\Core\Views;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

use Beehive\Core\Helpers\Template;
use Beehive\Core\Utils\Abstracts\Base;

/**
 * Class Locale
 *
 * @package Beehive\Core\Views
 */
class Locale extends Base {

	/**
	 * Get the common vars available to all files.
	 *
	 * This data will be available in all scripts.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function common() {
		return array(
			'dialogs'    => array(
				'close'    => __( 'Close this dialog.', 'ga_trans' ),
				'go_back'  => __( 'Go back to previous slide.', 'ga_trans' ),
				'continue' => __( 'Continue', 'ga_trans' ),
				'cancel'   => __( 'Cancel', 'ga_trans' ),
			),
			'notices'    => array(
				'dismiss' => __( 'Dismiss this notice', 'ga_trans' ),
			),
			'accordions' => array(
				'open' => __( 'Open item', 'ga_trans' ),
			),
			'trees'      => array(
				'select'     => __( 'Select this item', 'ga_trans' ),
				'open_close' => __( 'Open or close this item', 'ga_trans' ),
			),
			'header'     => array(
				'doc' => __( 'View Documentation', 'ga_trans' ),
			),
			'footer'     => array(
				'hub'       => __( 'The Hub', 'ga_trans' ),
				'plugins'   => __( 'Plugins', 'ga_trans' ),
				'roadmap'   => __( 'Roadmap', 'ga_trans' ),
				'support'   => __( 'Support', 'ga_trans' ),
				'docs'      => __( 'Docs', 'ga_trans' ),
				'community' => __( 'Community', 'ga_trans' ),
				'academy'   => __( 'Academy', 'ga_trans' ),
				'tos'       => __( 'Terms of Service', 'ga_trans' ),
				'privacy'   => __( 'Privacy Policy', 'ga_trans' ),
				'facebook'  => __( 'Facebook', 'ga_trans' ),
				'twitter'   => __( 'Twitter', 'ga_trans' ),
				'instagram' => __( 'Instagram', 'ga_trans' ),
			),
			'labels'     => array(
				'dismiss' => __( 'Dismiss', 'ga_trans' ),
			),
			'buttons'    => array(
				'close'  => __( 'Dismiss', 'ga_trans' ),
				'add'    => __( 'Add', 'ga_trans' ),
				'adding' => __( 'Adding', 'ga_trans' ),
			),
		);
	}

	/**
	 * Get the common vars specific to settings.
	 *
	 * This data will be available in all scripts.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function settings() {
		return array(
			'titles'       => array(
				'settings'    => __( 'Settings', 'ga_trans' ),
				'permissions' => __( 'Permissions', 'ga_trans' ),
				'tracking'    => __( 'Tracking Settings', 'ga_trans' ),
				'add_user'    => __( 'Add user', 'ga_trans' ),
			),
			'menus'        => array(
				'general'     => __( 'General', 'ga_trans' ),
				'tracking'    => __( 'Tracking Settings', 'ga_trans' ),
				'permissions' => __( 'Permissions', 'ga_trans' ),
			),
			'notices'      => array(
				'changes_saved'              => __( 'Changes were saved successfully.', 'ga_trans' ),
				'changes_failed'             => __( 'Could not save the changes. Please try again.', 'ga_trans' ),
				/* translators: %s: Google analytics signup link. */
				'no_accounts'                => __( 'You don\'t have any Google Analytics profile connected to your account. To get going, just <a href="%s" target="_blank">sign up for Google Analytics</a>.', 'ga_trans' ),
				/* translators: %s: WPMUDEV support link. */
				'auth_failed'                => __( 'We couldn\'t authorize your Google account. Please fill in your API information again, or connect with Google using the button below in side tab. If you\'re still stuck, please <a href="%s">contact support</a> for assistance.', 'ga_trans' ),
				/* translators: %s: Google support doc link. */
				'privacy_policy'             => __( 'Note: Enabling this feature requires <a href="%s" target="_blank">updating your privacy policy</a>.', 'ga_trans' ),
				'google_account_success'     => __( 'Your Google Analytics account has been connected successfully. Choose which analytics profile you want to use for showing statistics.', 'ga_trans' ),
				/* translators: %1$s: Google support doc link. %2$s WPMUDEV support link. */
				'google_api_error'           => __( 'We couldn\'t authorize your Google account. Please fill in <a href="%1$s" target="_blank">your API information</a> again, or connect with Google using the button below in side tab. If you\'re still stuck, please <a href="%2$s" target="_blank">contact support</a> for assistance.', 'ga_trans' ),
				'google_connect_error'       => __( 'Your Google Analytics account has been connected successfully. Choose which analytics profile you want to use for showing statistics.', 'ga_trans' ),
				/* translators: %1$s: Google support doc link. %2$s WPMUDEV support link. */
				'google_connect_success'     => __( 'We couldn\'t connect your Google account. Please try reconnecting with the "Connect" button below. Alternately, you can set up a <a href="%1$s" target="_blank">new API project</a> with Google and use that instead. If you\'re still stuck you can <a href="%2$s" target="_blank">contact support</a> for assistance.', 'ga_trans' ),
				/* translators: %s: Link to All Statistics page. */
				'google_already_connected'   => __( 'Note: Your account is already configured at the network level. Optionally, you can set up a different account and profile by adding it below, otherwise, you can view your analytics via the <a href="%s">Statistics tab</a>.', 'ga_trans' ),
				'logged_out'                 => __( 'You have been successfully logged out.', 'ga_trans' ),
				/* translators: %s: Google support docs link. */
				'invalid_tracking_id'        => __( 'Whoops, looks like that\'s an invalid tracking ID. Double check you have your <a href="%s">Google tracking ID</a> and try again.', 'ga_trans' ),
				/* translators: %1$s: < and %2$s: >. */
				'automatic_tracking_enabled' => __( 'You\'ve selected to automatically detect the tracking ID in the Account settings. This tracking ID is being output in the %1$shead%2$s section of your pages.', 'ga_trans' ),
				/* translators: %s: Link to statistics page. */
				'account_setup'              => __( 'Your account has been set up successfully. You can view the statistics <a href="%s">here</a>.', 'ga_trans' ),
				/* translators: %s: Link to statistics page. */
				'account_setup_both'         => __( 'Your Google account is now connected and analytics are being tracked. You can view this site\'s analytics on the <a href="%s">Statistics page</a>.', 'ga_trans' ),
				/* translators: %s: Link to statistics page. */
				'account_setup_login'        => __( 'Your Google account is now connected and you can view this site\'s analytics on the <a href="%s">Statistics page</a>. If you want to track analytics using Beehive, add your Tracking ID below to set up tracking.', 'ga_trans' ),
				/* translators: %s: Link to statistics page. */
				'account_setup_tracking'     => __( 'Your Tracking ID is set up and being output in the %1$s section of your site\'s code. To view analytics in your <a href="%2$s">WordPress Dashboard</a>, connect the Google account you\'re tracking analytics for.', 'ga_trans' ),
			),
			'placeholders' => array(
				'no_website'        => __( 'No website information', 'ga_trans' ),
				'select_website'    => __( 'Select a website', 'ga_trans' ),
				'tracking_id'       => __( 'E.g: UA-XXXXXXXXX-X', 'ga_trans' ),
				'client_id'         => __( 'Paste Google Client ID here', 'ga_trans' ),
				'client_secret'     => __( 'Paste Google Client Secret here', 'ga_trans' ),
				'custom_capability' => __( 'Set custom capability', 'ga_trans' ),
				'access_code'       => __( 'Paste access code here', 'ga_trans' ),
			),
			'errors'       => array(
				'tracking_id'   => __( 'Please use a valid Google Analytics Tracking ID', 'ga_trans' ),
				'client_id'     => __( 'Please enter Client ID.', 'ga_trans' ),
				'client_secret' => __( 'Please enter Client Secret.', 'ga_trans' ),
				'access_code'   => __( 'Please enter access code.', 'ga_trans' ),
			),
			'labels'       => array(
				'tracking_id'                    => __( 'Tracking ID', 'ga_trans' ),
				'client_id'                      => __( 'Google Client ID', 'ga_trans' ),
				'client_secret'                  => __( 'Google Client Secret', 'ga_trans' ),
				'authorize'                      => __( 'Authorize', 'ga_trans' ),
				'connect_google'                 => __( 'Connect with Google', 'ga_trans' ),
				'google_api'                     => __( 'Set up API Project', 'ga_trans' ),
				'access_code'                    => __( 'Access Code', 'ga_trans' ),
				'roles'                          => __( 'Roles', 'ga_trans' ),
				'capabilities'                   => __( 'Capabilities', 'ga_trans' ),
				'user_role'                      => __( 'User Role', 'ga_trans' ),
				'custom_users'                   => __( 'Custom Users', 'ga_trans' ),
				'statistics'                     => __( 'Statistics', 'ga_trans' ),
				'settings'                       => __( 'Settings', 'ga_trans' ),
				'admin_tracking'                 => __( 'Admin pages tracking', 'ga_trans' ),
				'admin_tracking_enable'          => __( 'Enable Admin pages tracking', 'ga_trans' ),
				'display_ad'                     => __( 'Display Advertising', 'ga_trans' ),
				'display_ad_enable'              => __( 'Enable Display Advertising Support', 'ga_trans' ),
				'ip_anonymization'               => __( 'IP Anonymization', 'ga_trans' ),
				'ip_anonymization_enable'        => __( 'Enable IP Anonymization', 'ga_trans' ),
				'ip_anonymization_force'         => __( 'Force on sub-sites tracking', 'ga_trans' ),
				'ip_anonymization_force_network' => __( 'Whole network tracking', 'ga_trans' ),
				'ip_anonymization_force_subsite' => __( 'Force on sub-sites tracking', 'ga_trans' ),
				'prosites_settings'              => __( 'Pro Site Permissions', 'ga_trans' ),
				'analytics_settings'             => __( 'Google Analytics Settings', 'ga_trans' ),
				'dashboard_analytics'            => __( 'Dashboard Analytics', 'ga_trans' ),
				'custom_capability'              => __( 'Custom Capability', 'ga_trans' ),
				'administrator'                  => __( 'Administrator', 'ga_trans' ),
				'network_administrator'          => __( 'Network Administrator', 'ga_trans' ),
				'network_tracking'               => __( 'Network Tracking', 'ga_trans' ),
				'tracking_statistics'            => __( 'Tracking Statistics', 'ga_trans' ),
				'account'                        => __( 'Account', 'ga_trans' ),
				'logout'                         => __( 'Log out', 'ga_trans' ),
				'switch_profile'                 => __( 'Switch profile', 'ga_trans' ),
				'choose_account'                 => __( 'Choose your view (profile)', 'ga_trans' ),
				'auto_detect_id'                 => __( 'Automatically detect tracking ID', 'ga_trans' ),
				'connected_account'              => __( 'Connected Google Account', 'ga_trans' ),
				'no_account_info'                => __( 'No account information', 'ga_trans' ),
				'use_different_tracking'         => __( 'Use a different tracking ID', 'ga_trans' ),
				'override_permissions'           => __( 'Allow sub-site admins to override these permissions.', 'ga_trans' ),
				'excluded_users'                 => __( 'Users who don’t have access to settings', 'ga_trans' ),
				'include_users'                  => __( 'Users who have access to settings', 'ga_trans' ),
				'search_users'                   => __( 'Search users', 'ga_trans' ),
				'type_user_name'                 => __( 'Type Username', 'ga_trans' ),
			),
			'buttons'      => array(
				'authorize'      => __( 'Authorize', 'ga_trans' ),
				'authorizing'    => __( 'Authorizing', 'ga_trans' ),
				'processing'     => __( 'Processing', 'ga_trans' ),
				'save_changes'   => __( 'Save Changes', 'ga_trans' ),
				'saving_changes' => __( 'Saving Changes', 'ga_trans' ),
				'refresh'        => __( 'Refresh data', 'ga_trans' ),
				'refreshing'     => __( 'Refreshing data', 'ga_trans' ),
				'logging_out'    => __( 'Logging out', 'ga_trans' ),
				'open_options'   => __( 'Open options', 'ga_trans' ),
				'exclude'        => __( 'Exclude', 'ga_trans' ),
				'include'        => __( 'Include', 'ga_trans' ),
				'add_user'       => __( 'Add User', 'ga_trans' ),
			),
			'tooltips'     => array(
				'refresh'               => __( 'Clear Beehive\'s local analytics cache and grab the latest data from Google.', 'ga_trans' ),
				'administrator'         => __( 'Administrators have access to Beehive statistics by default.', 'ga_trans' ),
				'network_administrator' => __( 'Network administrators have access to Beehive statistics by default.', 'ga_trans' ),
				'tracking_id'           => __( 'A tracking ID is what connects your website to your Google Analytics account. Use \'Automatically detect tracking ID,\' and Beehive will find and set up your tracking code automatically.', 'ga_trans' ),
				'tracking_only'         => __( 'Note: This will only feed data to Google, to view analytics in your Dashboard you\'ll need to authenticate your account on the Settings tab.', 'ga_trans' ),
			),
			'descriptions' => array(
				/* translators: %s: Beehive docs link. */
				'google_setup'           => __( 'If you\'re a site admin and experienced with Google\'s tools, you might want to set up an API Project, instead. Need help setting this up? Check the docs <a href="%s" target="_blank">here</a>.', 'ga_trans' ),
				'connect_google_network' => __( 'Authenticate your Google Account by clicking Connect with Google below, then paste your access code into the input and authorize your account.', 'ga_trans' ),
				'connect_google_single'  => __( 'Easily connect with Google by clicking the “Connect with Google” button and pasting the access code below. ', 'ga_trans' ),
				'statistics'             => __( 'Choose which user roles or capabilities can view statistics in their WordPress Dashboard area. ', 'ga_trans' ),
				'settings'               => __( 'By default, all administrators have access to Beehive’s settings. You can configure and add permissions for other roles, as well as prevent or grant access to custom users to access Beehive’s settings.', 'ga_trans' ),
				'settings_network'       => __( 'By default, only network administrators and site admins have access to Beehive’s settings. You can configure and add permissions for other roles, as well as prevent or grant access to custom users to access Beehive’s settings.', 'ga_trans' ),
				'tracking_subsite'       => __( 'Log in to your Google Analytics account to to auto configure tracking code and improve the statistics accuracy. Alternatively if you don’t wan to log in, you can add the tracking ID below.', 'ga_trans' ),
				'tracking'               => __( 'To view analytics data in your Dashboard area, you need to connect and authenticate a Google Analytics account. Alternately, you can add a tracking ID to start tracking your Google Analytics, you just won\'t be able to view the data here.', 'ga_trans' ),
				'admin_tracking'         => __( 'When enabled, you will get statistics from all admin pages.', 'ga_trans' ),
				/* translators: %s: Google support docs link. */
				'display_ad'             => __( 'Enable support for Google\'s Display Advertising and get additional demographic and interest reports. You can read more about it <a href="%s" target="_blank">here</a>.', 'ga_trans' ),
				/* translators: %s: Google support docs link. */
				'ip_anonymization'       => __( 'When enabled, visitor IP address will be <a href="%s" target="_blank">anonymized</a>.', 'ga_trans' ),
				'prosites_settings'      => __( 'We see you have Pro Sites active. Choose which levels you want to access analytics.', 'ga_trans' ),
				'analytics_settings'     => __( 'Choose which Pro Site levels can configure analytics settings.', 'ga_trans' ),
				'dashboard_analytics'    => __( 'Choose which Pro Site levels can view analytics in their WP Admin Dashboard.', 'ga_trans' ),
				/* translators: %s: WordPress.org link. */
				'custom_capability'      => __( 'Specify a custom capability that, if a user role matches it, can see analytics. You can view default capabilities <a href="%s" target="_blank">here</a>.', 'ga_trans' ),
				'network_tracking'       => __( 'Copy and paste your Google Analytics tracking ID to add it to your website. Note: This tracking code will track your whole network. To track subsites, you need to add the tracking code separately for each one.', 'ga_trans' ),
				'tracking_statistics'    => __( 'Copy and paste your Google Analytics tracking ID to add it to your website.', 'ga_trans' ),
				'account_network'        => __( 'Connect your Google Analytics account to automatically add your tracking code network-wide. This will display analytics on all sub sites by default.', 'ga_trans' ),
				'account_subsite'        => __( 'Connect your Google analytics profile to auto configure tracking code and improve statistics accuracy.', 'ga_trans' ),
				'account_single'         => __( 'Authenticate with Google to connect your analytics profile and begin feeding analytics in your WordPress Dashboard.', 'ga_trans' ),
				'logout_first'           => __( 'Logging out will remove analytics from your Dashboard.', 'ga_trans' ),
				'logout_second'          => __( 'Are you sure you want to logout?', 'ga_trans' ),
				'switch_one'             => __( 'When you switch your profile, you will be logged out and analytics will be removed from Dashboard.', 'ga_trans' ),
				'switch_second'          => __( 'Are you sure you want to switch?', 'ga_trans' ),
				'account_not_here'       => __( 'Site not here? Try logging into another account above!', 'ga_trans' ),
				/* translators: %s: Google support docs link. */
				'tracking_id_help'       => __( 'Having trouble finding your tracking code? You can grab it <a href="%s" target="_blank">here</a>.', 'ga_trans' ),
				'tracking_id_inherited'  => __( 'Note: Currently your statistics are provided from network wide tracking code. You can increase stats accuracy by logging in and configuring your own profile.', 'ga_trans' ),
				'user_role'              => __( 'Choose which user roles can have access and configure Beehive’s settings.', 'ga_trans' ),
				'user_role_second'       => __( 'Note: By default, all Administrators have access to Beehive. You can exclude custom users with the Administrator role in the Custom Users / Exclude tab.', 'ga_trans' ),
				'custom_users'           => __( 'In addition to the enabled user roles you can include or exclude individual users.', 'ga_trans' ),
				'include_users'          => __( 'Include users who don’t match the user roles you specified in the User Role tab, but that you want to allow access.', 'ga_trans' ),
				'exclude_users'          => __( 'Exclude users who match the user roles you specified in the User Role tab, but don\'t want to allow access. Note: You can also exclude users with the Administrator role if necessary.', 'ga_trans' ),
				'add_user'               => __( 'Type the username in the searchbox to add. You can add as many users as you like.', 'ga_trans' ),
			),
		);
	}

	/**
	 * Get the localization vars for the onboarding screens.
	 *
	 * This data will be available only when onboarding is rendered.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function onboarding() {
		return array(
			'notices'      => array(
				/* translators: %1$s: Google API project link. %2$s: WPMUDEV support link. */
				'google_connect_failed' => __( 'We couldn\'t connect your Google account. Please try reconnecting with the “Connect” button below. Alternately, you can set up a <a href="%1$s" target="_blank">new API project</a> with Google and use that instead. If you\'re still stuck you can <a href="%2$s" target="_blank">contact support</a> for assistance.', 'ga_trans' ),
				/* translators: %1$s: Google API project link. %2$s: WPMUDEV support link. */
				'google_api_failed'     => __( 'We couldn\'t authorize your Google account. Please fill in <a href="%1$s" target="_blank">your API information</a> again, or connect with Google using the button below in side tab. If you\'re still stuck, please <a href="%2$s" target="_blank">contact support</a> for assistance.', 'ga_trans' ),
			),
			'labels'       => array(
				'finishing_setup'      => __( 'Finishing Setup...', 'ga_trans' ),
				'auth_form_alt'        => __( 'Setup Google Account', 'ga_trans' ),
				/* translators: %s: Beehive plugin name. */
				'welcome'              => __( 'Welcome to %s', 'ga_trans' ),
				'google_tracking_id'   => __( 'Add Google Analytics tracking ID', 'ga_trans' ),
				'google_account_setup' => __( 'Set up your account', 'ga_trans' ),
				'display_statistics'   => __( 'Display Analytics statistics to:', 'ga_trans' ),
				'add_tracking_id'      => __( 'Add Tracking ID', 'ga_trans' ),
				'network_tracking_id'  => __( 'Network-wide Tracking ID', 'ga_trans' ),
				'tracking_id'          => __( 'Tracking ID', 'ga_trans' ),
				'why_connect'          => __( 'Why do I need to connect with Google?', 'ga_trans' ),
				'give_settings_access' => __( 'New: Configure Access to Beehive Settings', 'ga_trans' ),
				'config_by_role'       => __( 'Configure by user and user role', 'ga_trans' ),
			),
			'buttons'      => array(
				'save_code' => __( 'Save Code', 'ga_trans' ),
				'got_it'    => __( 'Got it', 'ga_trans' ),
			),
			'descriptions' => array(
				'finishing_setup'        => __( 'Please wait a few moments while we set up your account. Note that data can take up to 24 hours to display.', 'ga_trans' ),
				'welcome_network'        => __( 'Let\'s get started by connecting your Google Analytics account to get your tracking ID. This will enable statistics for your whole network and all subsites. Alternately, you can choose to enable network-wide tracking by adding a tracking ID manually below.', 'ga_trans' ),
				'welcome_single'         => __( 'Let\'s get started by connecting your Google Analytics account which will auto-configure your tracking ID to improve your statistics accuracy. Alternately, if you don\'t want to log in, you can manually add the tracking ID below.', 'ga_trans' ),
				'google_connect_success' => __( 'We have successfully set up your API project. The next step is to choose your Analytics Profile to get data feeding.', 'ga_trans' ),
				/* translators: %s: Link to get tracking id. */
				'tracking_id'            => __( 'Paste your Google Analytics tracking ID in the field below to enable analytics tracking for the whole network. You can get your ID <a href="%s" target="_blank">here</a>.', 'ga_trans' ),
				'why_connect'            => __( 'We need to authenticate your account with Google to ensure you actually own the analytics data.', 'ga_trans' ),
				'give_settings_access'   => __( 'In Beehive 3.2.5 you can specify which roles or custom users have access to Beehive’s settings.', 'ga_trans' ),
				'config_by_role'         => sprintf(
					/* translators: %s: Link to permissions settings. */
					__( 'With Beehive, you can give access to the <a href="%s">Settings page</a> for anyone with a particular user role, and you can also include or exclude individual users.', 'ga_trans' ),
					wp_nonce_url( add_query_arg( 'beehive-admin-action', 'dismiss-welcome', Template::settings_page( 'permissions', $this->is_network() ) ), 'beehive_admin_action', 'beehive-admin-action-nonce' )
				),
			),
		);
	}

	/**
	 * Get the loclization vars for the dashboard page.
	 *
	 * This data will be only available in dashboard scripts.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function dashboard() {
		return array(
			'titles'       => array(
				'dashboard'  => __( 'Dashboard', 'ga_trans' ),
				'statistics' => __( 'Statistics', 'ga_trans' ),
				'gtm'        => __( 'Google Tag Manager', 'ga_trans' ),
			),
			'labels'       => array(
				'configure_account' => __( 'Configure account', 'ga_trans' ),
				'add_tracking_id'   => __( 'Add Tracking ID', 'ga_trans' ),
				'pageviews'         => __( 'Pageviews', 'ga_trans' ),
				'new_users'         => __( 'New Users', 'ga_trans' ),
				'top_page'          => __( 'Top Page', 'ga_trans' ),
				'top_search_engine' => __( 'Top Search Engine', 'ga_trans' ),
				'top_medium'        => __( 'Top Medium', 'ga_trans' ),
				'never'             => __( 'Never', 'ga_trans' ),
				'none'              => __( 'None', 'ga_trans' ),
				'coming_soon'       => __( 'Coming Soon', 'ga_trans' ),
				'sessions'          => __( 'Sessions', 'ga_trans' ),
				'users'             => __( 'Users', 'ga_trans' ),
				'page_sessions'     => __( 'Pages/Sessions', 'ga_trans' ),
				'average_sessions'  => __( 'Avg. time', 'ga_trans' ),
				'bounce_rates'      => __( 'Bounce Rate', 'ga_trans' ),
				'view_full_report'  => __( 'View Full Report', 'ga_trans' ),
				'fetching_data'     => __( 'Fetching latest data...', 'ga_trans' ),
			),
			'descriptions' => array(
				'statistics' => __( 'View your websites full analytics report with Sessions, Users Pageviews, Average time and Bounce Rate.', 'ga_trans' ),
				'gtm'        => __( 'Google Tag Manager is coming to Beehive. You\'ll soon be able to deliver reliable and easily integrated tag management solutions for better measurements and marketing optimization. Hold tight!', 'ga_trans' ),
			),
			'notices'      => array(
				'auth_required'         => __( 'You need to authenticate with your Google account to automatically get the tracking ID for this website and enable access to statistics, or you can add the tracking ID manually.', 'ga_trans' ),
				'auth_required_network' => __( 'Authenticate with Google Analytics account to automatically get the tracking code for this website and enable statistics for whole network. Alternatively, you can just enable network wide tracking by adding in the Settings.', 'ga_trans' ),
				'no_data'               => __( 'We haven\'t collected enough data. Please check back soon.', 'ga_trans' ),
				/* translators: %s: Link to statistics page. */
				'account_setup'         => __( 'Your account has been set up successfully. You can view the statistics <a href="%s">here</a>.', 'ga_trans' ),
			),
		);
	}
}
