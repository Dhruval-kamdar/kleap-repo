<?php
namespace WeDevs\ERP\ERP_Recruitment\Emails;

use WeDevs\ERP\Email;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * New Job Application
 */
class New_Job_Appication_Submitted extends Email {

    use Hooker;

    function __construct() {
        $this->id          = 'new-job-application-submitted';
        $this->title       = __( 'New Job Application', 'erp-pro' );
        $this->description = __( 'New job application submitted.', 'erp-pro' );

        $this->subject     = __( 'New job application submitted', 'erp-pro');
        $this->heading     = __( 'New Job Application', 'erp-pro');

        $this->find = [
            'applicant-name'    => '{applicant_name}',
            'date'              => '{date}',
            'position'          => '{position}'
        ];

        $this->action( 'erp_admin_field_' . $this->id . '_help_texts', 'replace_keys' );

        parent::__construct();
    }

    function get_args() {
        return [
            'email_heading' => $this->heading,
            'email_body'    => wpautop( $this->get_option( 'body' ) ),
        ];
    }

    public function trigger( $data = [] ) {

        if ( empty( $data ) ) {
            return;
        }

        $this->recipient   = $data['recipient'];
        $this->heading     = $this->get_option( 'heading', $this->heading );
        $this->subject     = $this->get_option( 'subject', $this->subject );

        $this->replace = [
            'applicant-name' => $data['applicant_name'],
            'date'           => $data['date'],
            'position'       => $data['position']
        ];

        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }
}
