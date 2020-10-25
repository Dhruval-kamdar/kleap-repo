<?php

namespace Erp_Inventory\API;

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
            '\Erp_Inventory\API\Inventory_Controller',
            '\Erp_Inventory\API\Inventory_Report_Controller',
        ] );

        return $controllers;
    }

    public function include_controllers() {
        include_once ERP_INVENTORY_API . '/class-rest-api-inventory.php';
        include_once ERP_INVENTORY_API . '/class-rest-api-inventory-report.php';
    }
}
