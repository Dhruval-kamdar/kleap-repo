<?php
namespace WeDevs\ERP\HRM\Training;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\HRM\Training\After_Assign_Training;

/**
 * HR Email handler class
 */
class Emailer {

    use Hooker;

    function __construct() {
        $this->filter( 'erp_email_classes', 'register_emails' );
    }

    function register_emails( $emails ) {

        $emails['After_Assign_Training'] = new After_Assign_Training();

        return $emails;
    }
}
