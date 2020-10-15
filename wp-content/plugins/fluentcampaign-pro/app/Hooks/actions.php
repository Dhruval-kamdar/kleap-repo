<?php
/**
 * @var $app \FluentCrm\Includes\Core\Application
 */

add_action('init', function () {
    (new \FluentCampaign\App\Hooks\Handlers\DynamicSegment())->init();
    (new \FluentCampaign\App\Hooks\Handlers\IntegrationHandler())->init();
}, 1);

/*
 * Cleanup actions
 */
$app->addAction('fluentcrm_sequence_email_deleted', 'FluentCampaign\App\Hooks\Handlers\Cleanup@deleteCampaignAssets', 10, 1);
$app->addAction('fluentcrm_sequence_deleted', 'FluentCampaign\App\Hooks\Handlers\Cleanup@deleteSequenceAssets', 10, 1);

// fluentcrm_scheduled_hourly_tasks
$app->addAction('fluentcrm_scheduled_hourly_tasks', 'FluentCampaign\App\Hooks\Handlers\EmailScheduleHandler@handle');


add_action('admin_init', function () {
    $licenseManager = new \FluentCampaign\App\Services\PluginManager\LicenseManager();
    $licenseManager->initUpdater();

    $licenseMessage = $licenseManager->getLicenseMessages();

    if ($licenseMessage) {
        add_action('admin_notices', function () use ($licenseMessage) {
            $class = 'notice notice-error fc_message';
            $message = $licenseMessage['message'];
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
        });
    }
}, 0);

