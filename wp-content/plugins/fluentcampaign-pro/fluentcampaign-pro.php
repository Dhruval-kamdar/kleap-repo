<?php
/*
Plugin Name:  FluentCRM Pro - Email Marketing Addon
Plugin URI:   https://fluentcrm.io
Description:  Pro Email Campaign Addon for FluentCRM
Version:      1.0.5
Author:       Fluent CRM
Author URI:   https://fluentcrm.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  fluentcampaign
Domain Path:  /language
*/

if(defined('FLUENTCAMPAIGN_DIR_FILE')) {
    return;
}

define('FLUENTCAMPAIGN_DIR_FILE', __FILE__);

require_once("fluentcampaign_boot.php");

add_action('fluentcrm_loaded', function ($app) {
    (new \FluentCampaign\App\Application($app));
    do_action('fluentcampaign_loaded', $app);
});

register_activation_hook(
    __FILE__, array('FluentCampaign\App\Migration\Migrate', 'run')
);

// Handle Newtwork new Site Activation
add_action('wpmu_new_blog', function ($blogId) {
    switch_to_blog($blogId);
    \FluentCampaign\App\Migration\Migrate::run(false);
    restore_current_blog();
});

