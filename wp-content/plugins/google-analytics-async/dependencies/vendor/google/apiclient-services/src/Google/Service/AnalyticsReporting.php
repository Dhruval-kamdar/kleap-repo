<?php

namespace Beehive;

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
/**
 * Service definition for AnalyticsReporting (v4).
 *
 * <p>
 * Accesses Analytics report data.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/analytics/devguides/reporting/core/v4/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_Service_AnalyticsReporting extends \Beehive\Google_Service
{
    /** View and manage your Google Analytics data. */
    const ANALYTICS = "https://www.googleapis.com/auth/analytics";
    /** View your Google Analytics data. */
    const ANALYTICS_READONLY = "https://www.googleapis.com/auth/analytics.readonly";
    public $reports;
    public $userActivity;
    /**
     * Constructs the internal representation of the AnalyticsReporting service.
     *
     * @param Google_Client $client The client used to deliver requests.
     * @param string $rootUrl The root URL used for requests to the service.
     */
    public function __construct(\Beehive\Google_Client $client, $rootUrl = null)
    {
        parent::__construct($client);
        $this->rootUrl = $rootUrl ?: 'https://analyticsreporting.googleapis.com/';
        $this->servicePath = '';
        $this->batchPath = 'batch';
        $this->version = 'v4';
        $this->serviceName = 'analyticsreporting';
        $this->reports = new \Beehive\Google_Service_AnalyticsReporting_Resource_Reports($this, $this->serviceName, 'reports', array('methods' => array('batchGet' => array('path' => 'v4/reports:batchGet', 'httpMethod' => 'POST', 'parameters' => array()))));
        $this->userActivity = new \Beehive\Google_Service_AnalyticsReporting_Resource_UserActivity($this, $this->serviceName, 'userActivity', array('methods' => array('search' => array('path' => 'v4/userActivity:search', 'httpMethod' => 'POST', 'parameters' => array()))));
    }
}
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
/**
 * Service definition for AnalyticsReporting (v4).
 *
 * <p>
 * Accesses Analytics report data.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/analytics/devguides/reporting/core/v4/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
\class_alias('Beehive\\Google_Service_AnalyticsReporting', 'Google_Service_AnalyticsReporting', \false);