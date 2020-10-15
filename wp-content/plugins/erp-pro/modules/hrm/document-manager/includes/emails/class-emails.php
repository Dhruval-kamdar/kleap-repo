<?php
namespace WeDevs\ERP\ERP_Document\Emails;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\ERP_Document\Emails\DM_File_Share_Notification as DM_File_Share_Notification;

/**
 * HR Email handler class
 */
class Emailer {

    use Hooker;

    function __construct() {
        $this->filter( 'erp_email_classes', 'register_emails' );
    }

    function register_emails( $emails ) {

        $emails['DM_File_Share_Notification']   = new DM_File_Share_Notification();

        return $emails;
    }
}
