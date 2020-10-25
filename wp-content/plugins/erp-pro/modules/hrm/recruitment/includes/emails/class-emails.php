<?php
namespace WeDevs\ERP\HRM\ERP_Recruitment;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\ERP_Recruitment\Emails\New_Job_Appication_Submitted;
use WeDevs\ERP\ERP_Recruitment\Emails\Confirmation_Of_Successful_Submission;

/**
 * HR Email handler class
 */
class Emailer {

    use Hooker;

    function __construct() {
        $this->filter( 'erp_email_classes', 'register_emails' );
    }

    function register_emails( $emails ) {

        $emails['New_Job_Appication_Submitted']             = new New_Job_Appication_Submitted();
        $emails['Confirmation_Of_Successful_Submission']    = new Confirmation_Of_Successful_Submission();

        return $emails;
    }
}
