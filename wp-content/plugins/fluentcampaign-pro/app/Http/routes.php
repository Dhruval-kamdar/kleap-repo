<?php

/**
 * @var $app \FluentCrm\Includes\Core\Application
 */

/*
 * Email Sequences Route
 */
$app->group(function ($app) {

    $app->get('/', '\FluentCampaign\App\Http\Controllers\SequenceController@sequences');
    $app->post('/', 'FluentCampaign\App\Http\Controllers\SequenceController@create');

    $app->get('{id}', 'FluentCampaign\App\Http\Controllers\SequenceController@sequence')->int('id');
    $app->put('{id}', 'FluentCampaign\App\Http\Controllers\SequenceController@update')->int('id');
    $app->delete('{id}', 'FluentCampaign\App\Http\Controllers\SequenceController@delete')->int('id');

    $app->get('{id}/email/{email_id}', 'FluentCampaign\App\Http\Controllers\SequenceMailController@get')->int('id')->int('email_id');
    $app->post('{id}/email', 'FluentCampaign\App\Http\Controllers\SequenceMailController@create')->int('id');
    $app->put('{id}/email/{email_id}', 'FluentCampaign\App\Http\Controllers\SequenceMailController@update')->int('id')->int('sequence_id');
    $app->delete('{id}/email/{email_id}', 'FluentCampaign\App\Http\Controllers\SequenceMailController@delete')->int('id')->int('sequence_id');

    $app->get('{id}/subscribers', 'FluentCampaign\App\Http\Controllers\SequenceController@getSubscribers')->int('id');
    $app->post('{id}/subscribers', 'FluentCampaign\App\Http\Controllers\SequenceController@subscribe')->int('id');

})->prefix('sequences')->withPolicy('FluentCampaign\App\Http\Policies\SequencePolicy');

/*
 * Dynamic Segments
 */
$app->group(function ($app) {

    $app->get('/', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@index');
    $app->post('/', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@createCustomSegment');
    $app->post('estimated-contacts', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@getEstimatedContacts');
    $app->put('{id}', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@updateCustomSegment')->int('id');

    $app->delete('{id}', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@deleteCustomSegment');

    $app->get('{slug}/subscribers/{id}', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@getSegment')->alphaNumDash('slug')->int('id');
    $app->get('custom-fields', '\FluentCampaign\App\Http\Controllers\DynamicSegmentController@getCustomFields');

})->prefix('dynamic-segments')->withPolicy('FluentCampaign\App\Http\Policies\DynamicSegmentPolicy');


/*
 * Dynamic Segments
 */
$app->group(function ($app) {

    $app->get('license', '\FluentCampaign\App\Http\Controllers\LicenseController@getStatus');
    $app->post('license', '\FluentCampaign\App\Http\Controllers\LicenseController@saveLicense');
    $app->delete('license', '\FluentCampaign\App\Http\Controllers\LicenseController@deactivateLicense');

})->prefix('campaign-pro-settings')->withPolicy('FluentCampaign\App\Http\Policies\DynamicSegmentPolicy');

