<?php

namespace Erp_Inventory\API;

use WP_REST_Server;
use WP_REST_Response;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Inventory_Report_Controller extends \WeDevs\ERP\API\REST_Controller {
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'erp/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'accounting/v1/inventory/reports';

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/sales', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_sales_report' ],
                'args'                => [],
                'permission_callback' => function( $request ) {
                    return current_user_can( 'erp_ac_view_sales_summary' );
                },
            ]
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/purchase', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_purchase_report' ],
                'args'                => [],
                'permission_callback' => function( $request ) {
                    return current_user_can( 'erp_ac_view_sales_summary' );
                },
            ]
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/item-list', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_inventory_item_list' ],
                'args'                => [],
                'permission_callback' => function( $request ) {
                    return current_user_can( 'erp_ac_view_sales_summary' );
                },
            ]
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/item-summary', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_inventory_item_summary' ],
                'args'                => [],
                'permission_callback' => function( $request ) {
                    return current_user_can( 'erp_ac_view_sales_summary' );
                },
            ]
        ] );

    }

    /**
     * Get inventory sales report
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_sales_report( $request ) {
        $args = [
            'start_date' => ! empty( $request['start_date'] ) ? $request['start_date'] : null,
            'end_date'   => ! empty( $request['end_date'] ) ? $request['end_date'] : null
        ];

        $data = erp_acct_get_inventory_sales_report( $args );

        $response = rest_ensure_response( $data );

        $response->set_status( 200 );

        return $response;
    }

    /**
     * Get inventory purchase report
     *
     * @param $request
     * @return mixed|WP_REST_Response
     */
    public function get_purchase_report( $request ) {
        $args = [
            'start_date' => empty( $request['start_date'] ) ? '' : $request['start_date'],
            'end_date'   => empty( $request['end_date'] ) ? date( 'Y-m-d' ) : $request['end_date']
        ];

        $data = erp_acct_get_inventory_purchase_report( $args );

        $response = rest_ensure_response( $data );

        $response->set_status( 200 );

        return $response;
    }

    /**
     * Get inventory item list report
     *
     * @param $request
     * @return mixed|WP_REST_Response
     */
    public function get_inventory_item_list( $request ) {
        $args = [
            'start_date' => empty( $request['start_date'] ) ? '' : $request['start_date'],
            'end_date'   => empty( $request['end_date'] ) ? date( 'Y-m-d' ) : $request['end_date']
        ];

        $data = erp_acct_get_inventory_list_report( $args );

        $response = rest_ensure_response( $data );

        $response->set_status( 200 );

        return $response;
    }

    /**
     * Get inventory item summary report
     *
     * @param $request
     * @return mixed|WP_REST_Response
     */
    public function get_inventory_item_summary( $request ) {
        $args = [
            'start_date' => empty( $request['start_date'] ) ? '' : $request['start_date'],
            'end_date'   => empty( $request['end_date'] ) ? date( 'Y-m-d' ) : $request['end_date']
        ];

        $data = erp_acct_get_inventory_summary_report( $args );

        $response = rest_ensure_response( $data );

        $response->set_status( 200 );

        return $response;
    }

    /**
     * Prepare a single user output for response
     *
     * @param object|array $item
     * @param WP_REST_Request $request Request object.
     * @param array $additional_fields (optional)
     *
     * @return WP_REST_Response $response Response data.
     */
    public function prepare_item_for_response( $item, $request, $additional_fields = [] ) {

        $data = array_merge( $item, $additional_fields );

        // Wrap the data in a response object
        $response = rest_ensure_response( $data );

        $response = $this->add_links( $response, $item, $additional_fields );

        return $response;
    }

}
