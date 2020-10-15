<?php
namespace WeDevs\ERP\SMS;
/**
 * SMS Handler class for twilio
 *
 * @since 1.0
 */
class Twilio implements Gateway_Interface{

    /**
     * The number SMS will be sent from
     *
     * @since 1.0
     */
    private $number_from;

    /**
     * Twilio account SID
     *
     * @since 1.0
     */
    private $account_sid;

    /**
     * Twilio Auth Token
     *
     * @since 1.0
     */
    private $auth_token;

    /**
     * Twilio instance class
     *
     * @since 1.0
     */
    private $instance;

    /**
     * The constructor function
     *
     * @since 1.0
     */
    public function __construct() {
        $this->get_credentials();
        $this->prepare();
    }

    /**
     * Setup Credentials
     *
     * @since 1.0
     */
    public function get_credentials() {
        $this->number_from = erp_get_option( 'erp_sms_twilio_number_from' );
        $this->account_sid = erp_get_option( 'erp_sms_twilio_account_sid' );
        $this->auth_token  = erp_get_option( 'erp_sms_twilio_auth_token' );
    }

    /**
     * Prepare SMS
     *
     * @since 1.0
     */
    public function prepare() {
        $this->instance = new \Services_Twilio( $this->account_sid, $this->auth_token );
    }

    /**
     * Send SMS
     *
     * @since 1.0
     */
    public function send( array $cell_no, $message ) {
        foreach ( $cell_no as $cell_no_single ) {
            $result = $this->instance->account->messages->sendMessage( $this->number_from, '+'.$cell_no_single, $message );
        }

    }
}