<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Application\Services\Placeholder;

use AmeliaBooking\Application\Services\Helper\HelperService;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Location\LocationRepository;
use DateTime;

/**
 * Class EventPlaceholderService
 *
 * @package AmeliaBooking\Application\Services\Notification
 */
class EventPlaceholderService extends PlaceholderService
{
    /**
     *
     * @return array
     *
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function getEntityPlaceholdersDummyData()
    {
        /** @var SettingsService $settingsService */
        $settingsService = $this->container->get('domain.settings.service');

        /** @var HelperService $helperService */
        $helperService = $this->container->get('application.helper.service');

        $companySettings = $settingsService->getCategorySettings('company');

        $dateFormat = $settingsService->getSetting('wordpress', 'dateFormat');
        $timeFormat = $settingsService->getSetting('wordpress', 'timeFormat');

        $timestamp = date_create()->getTimestamp();

        return [
            'event_name'              => 'Event Name',
            'reservation_name'        => 'Event Name',
            'event_location'          => $companySettings['address'],
            'event_periods'           =>
                '<ul>' .
                '<li>' . date_i18n($dateFormat, strtotime($timestamp)) . '</li>' .
                '<li>' . date_i18n($dateFormat, strtotime($timestamp . ' +1 day')) . '</li>' .
                '</ul>',
            'event_start_date'        => date_i18n($dateFormat, strtotime($timestamp)),
            'event_start_time'        => date_i18n($timeFormat, $timestamp),
            'event_start_date_time'   => date_i18n($dateFormat . ' ' . $timeFormat, strtotime($timestamp)),
            'event_end_date'          => date_i18n($dateFormat, strtotime($timestamp . ' +1 day')),
            'event_end_time'          => date_i18n($timeFormat, $timestamp),
            'event_end_date_time'     => date_i18n($dateFormat . ' ' . $timeFormat, strtotime($timestamp . ' +1 day')),
            'event_price'             => $helperService->getFormattedPrice(100),
            'event_description'       => 'Event Description',
            'reservation_description' => 'Event Description',
        ];
    }

    /**
     * @param array  $event
     * @param int    $bookingKey
     * @param string $token
     * @param string $type
     *
     * @return array
     *
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws NotFoundException
     * @throws QueryExecutionException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Exception
     */
    public function getEntityPlaceholdersData($event, $bookingKey = null, $token = null, $type = null)
    {
        $data = [];

        $data = array_merge($data, $this->getEventData($event, $bookingKey, $token, $type));

        return $data;
    }

    /**
     * @param array  $event
     * @param int    $bookingKey
     * @param string $token
     * @param string $type
     *
     * @return array
     *
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Exception
     */
    private function getEventData($event, $bookingKey = null, $token = null, $type = null)
    {
        /** @var HelperService $helperService */
        $helperService = $this->container->get('application.helper.service');

        /** @var SettingsService $settingsService */
        $settingsService = $this->container->get('domain.settings.service');

        $dateFormat = $settingsService->getSetting('wordpress', 'dateFormat');
        $timeFormat = $settingsService->getSetting('wordpress', 'timeFormat');

        $dateTimes = [];

        $locationName = '';
        $locationAddress = '';
        $locationDescription = '';
        $locationPhone = '';

        if ($event['locationId']) {
            /** @var LocationRepository $locationRepository */
            $locationRepository = $this->container->get('domain.locations.repository');

            $location = $locationRepository->getById($event['locationId']);

            $locationName = $location->getName()->getValue();
            $locationDescription = $location->getDescription()->getValue();
            $locationAddress = $location->getAddress()->getValue();
            $locationPhone = $location->getPhone()->getValue();
        } elseif ($event['customLocation']) {
            $locationName = $event['customLocation'];
        }

        $staff = [];

        /** @var string $liStartTag */
        $liStartTag = $type === 'email' ? '<li>' : '';

        /** @var string $liEndTag */
        $liEndTag = $type === 'email' ? '</li>' : PHP_EOL;

        /** @var string $ulStartTag */
        $ulStartTag = $type === 'email' ? '<ul>' : '';

        /** @var string $ulEndTag */
        $ulEndTag = $type === 'email' ? '</ul>' : '';

        foreach ((array)$event['providers'] as $provider) {
            $staff[] = [
                'employee_first_name'       => $provider['firstName'],
                'employee_last_name'        => $provider['lastName'],
                'employee_full_name'        => $provider['firstName'] . ' ' . $provider['lastName'],
                'employee_note'             => $provider['note'],
                'employee_phone'            => $provider['phone'],
                'employee_email'            => $provider['email'],
                'employee_name_email_phone' =>
                    (sizeof($event['providers']) > 1 ? $liStartTag : '') .
                    $provider['firstName'] . ' ' . $provider['lastName'] .
                    ($provider['phone'] ? ', ' . $provider['phone'] : '') .
                    (sizeof($event['providers']) > 1 ? $liEndTag : ''),
            ];
        }

        $staff = [
            'employee_first_name'       =>
                implode(', ', array_column($staff, 'employee_first_name')),
            'employee_last_name'        =>
                implode(', ', array_column($staff, 'employee_last_name')),
            'employee_full_name'        =>
                implode(', ', array_column($staff, 'employee_full_name')),
            'employee_note'             =>
                implode(', ', array_column($staff, 'employee_note')),
            'employee_phone'            =>
                implode(', ', array_column($staff, 'employee_phone')),
            'employee_email'            =>
                implode(', ', array_column($staff, 'employee_email')),
            'employee_name_email_phone' =>
                $ulStartTag . implode('', array_column($staff, 'employee_name_email_phone')) . $ulEndTag,
        ];

        foreach ((array)$event['periods'] as $period) {
            if ($bookingKey !== null &&
                $event['bookings'][$bookingKey]['utcOffset'] !== null &&
                $settingsService->getSetting('general', 'showClientTimeZone')
            ) {
                $dateTimes[] = [
                    'start' => DateTimeService::getClientUtcCustomDateTimeObject(
                        DateTimeService::getCustomDateTimeInUtc($period['periodStart']),
                        $event['bookings'][$bookingKey]['utcOffset']
                    ),
                    'end'   => DateTimeService::getClientUtcCustomDateTimeObject(
                        DateTimeService::getCustomDateTimeInUtc($period['periodEnd']),
                        $event['bookings'][$bookingKey]['utcOffset']
                    )
                ];
            } else {
                $dateTimes[] = [
                    'start' => DateTime::createFromFormat('Y-m-d H:i:s', $period['periodStart']),
                    'end'   => DateTime::createFromFormat('Y-m-d H:i:s', $period['periodEnd'])
                ];
            }
        }

        $eventDateList = [];
        $eventDateTimeList = [];
        $eventZoomStartDateList = [];
        $eventZoomStartDateTimeList = [];
        $eventZoomJoinDateList = [];
        $eventZoomJoinDateTimeList = [];

        foreach ($dateTimes as $key => $dateTime) {
            /** @var \DateTime $startDateTime */
            $startDateTime = $dateTime['start'];

            /** @var \DateTime $endDateTime */
            $endDateTime = $dateTime['end'];

            $startDateString = $startDateTime->format('Y-m-d');
            $endDateString = $endDateTime->format('Y-m-d');

            $periodStartDate = date_i18n($dateFormat, $startDateTime->getTimestamp());
            $periodEndDate = date_i18n($dateFormat, $endDateTime->getTimestamp());

            $periodStartTime = date_i18n($timeFormat, $startDateTime->getTimestamp());
            $periodEndTime = date_i18n($timeFormat, $endDateTime->getTimestamp());

            $dateString = $startDateString === $endDateString ?
                $periodStartDate :
                $periodStartDate . ' - ' . $periodEndDate;

            $dateTimeString = $startDateString === $endDateString ?
                $periodStartDate . ' (' . $periodStartTime . ' - ' . $periodEndTime . ')' :
                $periodStartDate . ' - ' . $periodEndDate . ' (' . $periodStartTime . ' - ' . $periodEndTime . ')';

            $eventDateList[] = "$liStartTag{$dateString}$liEndTag";
            $eventDateTimeList[] = "$liStartTag{$dateTimeString}$liEndTag";

            if ($event['zoomUserId']) {
                $startUrl = $event['periods'][$key]['zoomMeeting']['startUrl'];
                $joinUrl = $event['periods'][$key]['zoomMeeting']['joinUrl'];

                $eventZoomStartDateList[] =  $type === 'email' ? '<li><a href="' . $startUrl . '">' . $dateString . '</a></li>' : $dateString . ': ' . $startUrl;
                $eventZoomStartDateTimeList[] = $type === 'email' ? '<li><a href="' . $startUrl . '">' . $dateTimeString . '</a></li>' : $dateTimeString . ': ' . $startUrl;
                $eventZoomJoinDateList[] = $type === 'email' ? '<li><a href="' . $joinUrl . '">' . $dateString . '</a></li>' : $dateString . ': ' . $joinUrl;
                $eventZoomJoinDateTimeList[] = $type === 'email' ? '<li><a href="' . $joinUrl . '">' . $dateTimeString . '</a></li>' : $dateTimeString . ': ' . $joinUrl;
            }
        }

        /** @var \DateTime $eventStartDateTime */
        $eventStartDateTime = $dateTimes[0]['start'];

        /** @var \DateTime $eventEndDateTime */
        $eventEndDateTime = $dateTimes[sizeof($dateTimes) - 1]['end'];

        $attendeeCode = $bookingKey !== null && $token ? $token : '';

        return array_merge([
            'attendee_code'            => substr($attendeeCode, 0, 5),
            'reservation_name'         => $event['name'],
            'event_name'               => $event['name'],
            'event_price'              => $helperService->getFormattedPrice($event['price']),
            'event_description'        => $event['description'],
            'reservation_description'  => $event['description'],
            'event_location'           => $locationName,
            'location_name'            => $locationName,
            'location_address'         => $locationAddress,
            'location_description'     => $locationDescription,
            'location_phone'           => $locationPhone,
            'event_period_date'        => $ulStartTag . implode('', $eventDateList) . $ulEndTag,
            'event_period_date_time'   => $ulStartTag . implode('', $eventDateTimeList) . $ulEndTag,
            'zoom_host_url_date'      => count($eventZoomStartDateList) === 0 ?
                '' : $ulStartTag . implode('', $eventZoomStartDateList) . $ulEndTag,
            'zoom_host_url_date_time' => count($eventZoomStartDateTimeList) === 0 ?
                '' : $ulStartTag . implode('', $eventZoomStartDateTimeList) . $ulEndTag,
            'zoom_join_url_date'       => count($eventZoomJoinDateList) === 0 ?
                '' : $ulStartTag . implode('', $eventZoomJoinDateList) . $ulEndTag,
            'zoom_join_url_date_time'  => count($eventZoomJoinDateTimeList) === 0 ?
                '' : $ulStartTag . implode('', $eventZoomJoinDateTimeList) . $ulEndTag,
            'event_start_date'         => date_i18n($dateFormat, $eventStartDateTime->getTimestamp()),
            'event_end_date'           => date_i18n($dateFormat, $eventEndDateTime->getTimestamp()),
            'event_start_date_time'    => date_i18n(
                $dateFormat . ' ' . $timeFormat,
                $eventStartDateTime->getTimestamp()
            ),
            'event_end_date_time'      => date_i18n(
                $dateFormat . ' ' . $timeFormat,
                $eventEndDateTime->getTimestamp()
            ),
            'event_start_time'         => date_i18n(
                $timeFormat,
                $eventStartDateTime->getTimestamp()
            ),
            'event_end_time'           => date_i18n(
                $timeFormat,
                $eventEndDateTime->getTimestamp()
            ),
        ], $staff);
    }
}