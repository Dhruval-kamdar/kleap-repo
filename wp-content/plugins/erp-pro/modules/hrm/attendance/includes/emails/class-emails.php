<?php
namespace WeDevs\ERP\HRM\Attendance;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\HRM\Attendance\Emails\Attendance_Reminder;

/**
 * HR Email handler class
 */
class Emailer {

    use Hooker;

    function __construct() {
        $this->filter( 'erp_email_classes', 'register_emails' );
    }

    function register_emails( $emails ) {

        $emails['Attendance_Reminder']   = new Attendance_Reminder();

        return $emails;
    }
}
