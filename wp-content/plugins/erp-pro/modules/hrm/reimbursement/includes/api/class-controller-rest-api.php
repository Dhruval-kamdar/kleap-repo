<?php

namespace Erp_People_Trn\API;

/**
 * REST_API Handler
 */
class REST_API {

    public function __construct() {
        add_filter( 'erp_rest_api_controllers', array( $this, 'register_erp_people_trn_controllers' ) );
    }

    public function register_erp_people_trn_controllers( $controllers ) {
        $this->include_controllers();

        $controllers = array_merge( $controllers, [
            '\Erp_People_Trn\API\People_Trn_Controller',
            '\Erp_People_Trn\API\Employee_Requests_Controller',
        ] );

        return $controllers;
    }

    public function include_controllers() {
        include_once ERP_REIMBURSEMENT_API . '/class-rest-api-people-trn.php';
        include_once ERP_REIMBURSEMENT_API . '/class-rest-api-empl-requests.php';
    }
}
