<?php
namespace WeDevs\ERP\Inventory;

use WeDevs\ERP\Framework\Traits\Ajax;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * Ajax handler
 *
 * @package WP-ERP
 */
class Ajax_Handler {

    use Ajax;
    use Hooker;

    /**
     * Bind all the ajax event for HRM
     *
     * @since 0.1
     *
     * @return void
     */
    public function __construct() {
        //purchase
        $this->action( 'wp_ajax_erp-inv-get-product-cost-price', 'get_inv_product_cost_price' );
        //sales
        $this->action( 'wp_ajax_erp-inv-get-product-sale-price', 'get_inv_product_sale_price' );
        // report
        $this->action( 'wp_ajax_erp-inv-get-purchase-report', 'get_purchase_report' );
        $this->action( 'wp_ajax_erp-inv-get-item-detail-report', 'get_item_detail_report' );
        $this->action( 'wp_ajax_erp-inv-get-item-list-report', 'get_item_list_report' );
        $this->action( 'wp_ajax_erp-inv-get-item-summary-report', 'get_item_summary_report' );
        $this->action( 'wp_ajax_erp-inv-get-sales-report', 'get_sales_report' );
        // import product
//        $this->action( 'wp_ajax_erp-inv-import-product', 'inv_import_product' );
    }

    /*
     * get inventory product cost price
     * para
     * return void
     */
    public function get_inv_product_cost_price() {
        $product_id       = $_REQUEST['post_id'];
        $cost_price       = ( get_post_meta( $product_id, '_cost_price', true ) == "" ? 0 : get_post_meta( $product_id, '_cost_price', true ) );
        $purchase_account = ( get_post_meta( $product_id, '_purchase_account', true ) == "" ? 0 : get_post_meta( $product_id, '_purchase_account', true ) );
        $purchase_tax     = ( get_post_meta( $product_id, '_tax_on_purchase', true ) == "" ? 0 : get_post_meta( $product_id, '_tax_on_purchase', true ) );
        $product_content  = ( get_post_meta( $product_id, '_purchase_description', true ) == "" ? "" : get_post_meta( $product_id, '_purchase_description', true ) );
        $this->send_success( [ 'purchase_account' => $purchase_account, 'product_content' => $product_content, 'tax_on_purchase' => $purchase_tax, 'cost_price' => $cost_price ] );
    }

    /*
     * get inventory product sale price
     * para
     * return void
     */
    public function get_inv_product_sale_price() {
        $product_id      = $_REQUEST['post_id'];
        $sale_price      = ( get_post_meta( $product_id, '_sale_price', true ) == "" ? 0 : get_post_meta( $product_id, '_sale_price', true ) );
        $sales_account   = ( get_post_meta( $product_id, '_sales_account', true ) == "" ? 0 : get_post_meta( $product_id, '_sales_account', true ) );
        $sales_tax       = ( get_post_meta( $product_id, '_tax_on_sales', true ) == "" ? 0 : get_post_meta( $product_id, '_tax_on_sales', true ) );
        $product_content = ( get_post_meta( $product_id, '_sales_description', true ) == "" ? "" : get_post_meta( $product_id, '_sales_description', true ) );
        $this->send_success( [ 'sales_account' => $sales_account, 'product_content' => $product_content, 'tax_on_sales' => $sales_tax, 'sale_price' => $sale_price ] );
    }

    /*
     * get candidate report
     * para
     * return
     */
    public function get_purchase_report() {
        global $wpdb;

        $f_date = $_REQUEST['f_date'];
        $t_date = $_REQUEST['t_date'];
        $query  = "";

        if ( $f_date == "" || $t_date == "" ) {
            $query = "SELECT tran.issue_date as purchase_date,
                tran.ref as ref_no,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                WHERE people_type_relations.people_types_id=4 AND tran.type='expense' AND tran.form_type='vendor_credit' OR people_type_relations.people_types_id=4 AND tran.type='expense' AND tran.form_type='payment_voucher'
                ORDER BY purchase_date DESC";
        } elseif ( $f_date != "" && $t_date != "" ) {
            $query = "SELECT tran.issue_date as purchase_date,
                tran.ref as ref_no,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                WHERE people_type_relations.people_types_id=4 AND tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='expense' AND tran.form_type='vendor_credit' OR people_type_relations.people_types_id=4 AND tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='expense' AND tran.form_type='payment_voucher'
                ORDER BY purchase_date DESC";
        }

        $qdata = $wpdb->get_results( $query, ARRAY_A );

        $report_data = [ ];
        foreach ( $qdata as $ud ) {
            $report_data[] = array(
                'purchase_date' => date( "d M Y", strtotime( $ud['purchase_date'] ) ),
                'ref_no'        => $ud['ref_no'],
                'vendor_name'   => $ud['first_name'] . ' ' . $ud['last_name'],
                'tax'           => $ud['tax'],
                'tax_rate'      => $ud['tax_rate'],
                'tax_amount'    => $ud['tax_rate'] * $ud['line_total'] / 100,
                'discount'      => $ud['discount'],
                'quantity'      => $ud['quantity'],
                'unit_price'    => $ud['unit_price'],
                'line_total'    => number_format( $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total'], 2 )
            );
        }

        $this->send_success( $report_data );
    }

    /*
     * get item detail report data
     * para
     * return void
     */
    public function get_item_detail_report() {
        global $wpdb;

        $f_date = $_REQUEST['f_date'];
        $t_date = $_REQUEST['t_date'];
        $query  = "";

        if ( $f_date == "" || $t_date == "" ) {
            $query = "(SELECT tran.issue_date as issue_date,
                tran.ref as ref_no,
                tran.type as tran_type,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name,
                posts.post_title as product_name,
                posts.ID as product_id,
                tran.type as transaction_type
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                INNER JOIN {$wpdb->prefix}postmeta as postmeta
                ON items.product_id=postmeta.post_id
                WHERE
                people_type_relations.people_types_id=4 AND tran.type='expense' AND tran.form_type='vendor_credit' OR
                people_type_relations.people_types_id=4 AND tran.type='expense' AND tran.form_type='payment_voucher')
                UNION
                (SELECT tran.issue_date as issue_date,
                tran.ref as ref_no,
                tran.type as tran_type,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name,
                posts.post_title as product_name,
                posts.ID as product_id,
                tran.type as transaction_type
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                INNER JOIN {$wpdb->prefix}postmeta as postmeta
                ON items.product_id=postmeta.post_id
                WHERE
                people_type_relations.people_types_id=3 AND tran.type='sales' AND tran.form_type='invoice' OR
                people_type_relations.people_types_id=3 AND tran.type='sales' AND tran.form_type='payment')
                ORDER BY issue_date DESC";
        } elseif ( $f_date != "" && $t_date != "" ) {
            $query = "(SELECT tran.issue_date as issue_date,
                tran.ref as ref_no,
                tran.type as tran_type,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name,
                posts.post_title as product_name,
                posts.ID as product_id,
                tran.type as transaction_type
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                INNER JOIN {$wpdb->prefix}postmeta as postmeta
                ON items.product_id=postmeta.post_id
                WHERE tran.issue_date BETWEEN '$f_date' AND '$t_date' AND people_type_relations.people_types_id=4 AND tran.type='expense' AND tran.form_type='vendor_credit' OR tran.issue_date BETWEEN '$f_date' AND '$t_date' AND people_type_relations.people_types_id=4 AND tran.type='expense' AND tran.form_type='payment_voucher')
                UNION
                (SELECT tran.issue_date as issue_date,
                tran.ref as ref_no,
                tran.type as tran_type,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name,
                posts.post_title as product_name,
                posts.ID as product_id,
                tran.type as transaction_type
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                INNER JOIN {$wpdb->prefix}postmeta as postmeta
                ON items.product_id=postmeta.post_id
                WHERE tran.issue_date BETWEEN '$f_date' AND '$t_date' AND people_type_relations.people_types_id=3 AND tran.type='sales' AND tran.form_type='invoice' OR tran.issue_date BETWEEN '$f_date' AND '$t_date' AND people_type_relations.people_types_id=3 AND tran.type='sales' AND tran.form_type='payment')
                ORDER BY issue_date DESC";
        }

        $qdata = $wpdb->get_results( $query, ARRAY_A );

        $profit_per_item = 0;
        $margin          = 0;
        $report_data     = [ ];
        foreach ( $qdata as $ud ) {

            if ( $ud['transaction_type'] == 'expense' ) {

            }
            // find out profit per item and margin because here sales made
            if ( $ud['transaction_type'] == 'sales' ) {
                $cost_price      = get_post_meta( $ud['product_id'], '_cost_price', true );
                $sale_price      = get_post_meta( $ud['product_id'], '_sale_price', true );
                $profit_per_item = $sale_price - $cost_price;
                $margin          = ( $profit_per_item / $sale_price ) * 100;
            } else {
                $profit_per_item = 0;
                $margin          = 0;
            }

            $report_data[] = array(
                'issue_date'      => date( "d M Y", strtotime( $ud['issue_date'] ) ),
                'ref_no'          => $ud['ref_no'],
                'tran_type'       => $ud['tran_type'],
                'product_name'    => $ud['product_name'],
                'vendor_name'     => $ud['first_name'] . ' ' . $ud['last_name'],
                'tax'             => $ud['tax'],
                'tax_rate'        => $ud['tax_rate'],
                'tax_amount'      => $ud['tax_rate'] * $ud['line_total'] / 100,
                'discount'        => $ud['discount'],
                'quantity'        => $ud['quantity'],
                'unit_price'      => $ud['unit_price'],
                'profit_per_item' => $profit_per_item,
                'margin'          => number_format( $margin, 2 ),
                'line_total'      => number_format( $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total'], 2 )
            );
        }

        // sort the report data array by product name
        $final_report_data_after_sorting = [ ];
        if ( count( $report_data ) > 0 ) {
            $sort = [ ];
            foreach ( $report_data as $key => $part ) {
                $sort[$key] = $part['product_name'];
            }
            array_multisort( $sort, SORT_ASC, $report_data );

            // after sorting find out sub total of vaule movement and profit per item summation
            $final_report_data_after_sorting = [ ];
            $previous_product_name           = false;
            $subtotal_value_movement         = 0;
            $subtotal_qoh_movement           = 0;
            $subtotal_profit_per_item        = 0;
            $gross_value_movement            = 0;
            $gross_qoh_movement              = 0;
            $gross_profit_per_item           = 0;
            $last_key                        = end( array_keys( $report_data ) );
            foreach ( $report_data as $key => $rdata ) {
                if ( $key == 0 ) { // fisrt one so show only title row
                    $final_report_data_after_sorting[] = array(
                        'issue_date'      => '',
                        'ref_no'          => '',
                        'product_name'    => $rdata['product_name'],
                        'vendor_name'     => '',
                        'tax'             => '',
                        'tax_rate'        => '',
                        'tax_amount'      => '',
                        'discount'        => '',
                        'quantity'        => '',
                        'unit_price'      => '',
                        'profit_per_item' => '',
                        'margin'          => '',
                        'line_total'      => ''
                    );
                }
                if ( $previous_product_name != false && $previous_product_name != $rdata['product_name'] ) {
                    // show sub total here and go to loop again
                    $final_report_data_after_sorting[] = array(
                        'issue_date'      => '',
                        'ref_no'          => '',
                        'product_name'    => '',
                        'vendor_name'     => 'Sub Total:',
                        'tax'             => '',
                        'tax_rate'        => '',
                        'tax_amount'      => '',
                        'discount'        => '',
                        'quantity'        => $subtotal_qoh_movement,
                        'unit_price'      => '',
                        'profit_per_item' => $subtotal_profit_per_item,
                        'margin'          => '',
                        'line_total'      => number_format( $subtotal_value_movement, 2 )
                    );
                    $subtotal_value_movement           = 0;
                    $subtotal_qoh_movement             = 0;
                    $subtotal_profit_per_item          = 0;
                    // show only title row
                    $final_report_data_after_sorting[] = array(
                        'issue_date'      => '',
                        'ref_no'          => '',
                        'product_name'    => $rdata['product_name'],
                        'vendor_name'     => '',
                        'tax'             => '',
                        'tax_rate'        => '',
                        'tax_amount'      => '',
                        'discount'        => '',
                        'quantity'        => '',
                        'unit_price'      => '',
                        'profit_per_item' => '',
                        'margin'          => '',
                        'line_total'      => ''
                    );
                }

                $previous_product_name = $rdata['product_name'];

                $final_report_data_after_sorting[] = array(
                    'issue_date'      => date( "d M Y", strtotime( $rdata['issue_date'] ) ),
                    'ref_no'          => $rdata['ref_no'],
                    'product_name'    => '',
                    'vendor_name'     => $rdata['vendor_name'],
                    'tax'             => $rdata['tax'],
                    'tax_rate'        => $rdata['tax_rate'],
                    'tax_amount'      => $rdata['tax_rate'] * $rdata['line_total'] / 100,
                    'discount'        => $rdata['discount'],
                    'quantity'        => $rdata['quantity'],
                    'unit_price'      => $rdata['unit_price'],
                    'profit_per_item' => $rdata['profit_per_item'],
                    'margin'          => number_format( $rdata['margin'], 2 ) . '%',
                    'line_total'      => number_format( $rdata['tax_rate'] * $rdata['line_total'] / 100 + $rdata['line_total'], 2 )
                );
                $subtotal_value_movement           = $subtotal_value_movement + $rdata['tax_rate'] * $rdata['line_total'] / 100 + $rdata['line_total'];
                if ( $rdata['tran_type'] == 'expense' ) {
                    $subtotal_qoh_movement = $subtotal_qoh_movement + $rdata['quantity'];
                    $gross_qoh_movement    = $gross_qoh_movement + $rdata['quantity'];
                } elseif ( $rdata['tran_type'] == 'sales' ) {
                    $subtotal_qoh_movement = $subtotal_qoh_movement - $rdata['quantity'];
                    $gross_qoh_movement    = $gross_qoh_movement - $rdata['quantity'];
                }
                $subtotal_profit_per_item = $subtotal_profit_per_item + $rdata['profit_per_item'];

                if ( $last_key == $key ) {
                    // show sub total here for the last product
                    $final_report_data_after_sorting[] = array(
                        'issue_date'      => '',
                        'ref_no'          => '',
                        'product_name'    => '',
                        'vendor_name'     => 'Sub Total:',
                        'tax'             => '',
                        'tax_rate'        => '',
                        'tax_amount'      => '',
                        'discount'        => '',
                        'quantity'        => $subtotal_qoh_movement,
                        'unit_price'      => '',
                        'profit_per_item' => $subtotal_profit_per_item,
                        'margin'          => '',
                        'line_total'      => number_format( $subtotal_value_movement, 2 )
                    );
                    $subtotal_value_movement           = 0;
                    $subtotal_qoh_movement             = 0;
                    $subtotal_profit_per_item          = 0;
                }

                $gross_value_movement = $gross_value_movement + $rdata['tax_rate'] * $rdata['line_total'] / 100 + $rdata['line_total'];
                //$gross_qoh_movement    = $gross_qoh_movement + $subtotal_qoh_movement;
                $gross_profit_per_item = $gross_profit_per_item + $rdata['profit_per_item'];
            }

            $final_report_data_after_sorting[] = array(
                'issue_date'      => '',
                'ref_no'          => '',
                'product_name'    => '',
                'vendor_name'     => 'Gross Total:',
                'tax'             => '',
                'tax_rate'        => '',
                'tax_amount'      => '',
                'discount'        => '',
                'quantity'        => $gross_qoh_movement,
                'unit_price'      => '',
                'profit_per_item' => $gross_profit_per_item,
                'margin'          => '',
                'line_total'      => number_format( $gross_value_movement, 2 )
            );
        }

        $this->send_success( $final_report_data_after_sorting );
    }

    /*
     * get item list report data
     * para
     * return void
     */
    public function get_item_list_report() {
        global $wpdb;

        $f_date = $_REQUEST['f_date'];
        $query  = "";

        if ( $f_date == "" ) {
            $query = "SELECT
                posts.post_title as product_name,
                posts.post_content as description,
                posts.ID as product_id,
                (SELECT SUM(qty)
                    FROM {$wpdb->prefix}erp_ac_transaction_items as itemss
                    INNER JOIN {$wpdb->prefix}erp_ac_transactions as transs
                    ON transs.id=itemss.transaction_id
                    WHERE itemss.product_id=items.product_id AND transs.type='expense' AND transs.form_type='vendor_credit' OR itemss.product_id=items.product_id AND transs.type='expense' AND transs.form_type='payment_voucher'
                    GROUP BY product_id) as total_purchase,
                (SELECT SUM(qty)
                    FROM {$wpdb->prefix}erp_ac_transaction_items as itemss
                    INNER JOIN {$wpdb->prefix}erp_ac_transactions as transs
                    ON transs.id=itemss.transaction_id
                    WHERE itemss.product_id=items.product_id AND transs.type='sales' AND transs.form_type='invoice' OR itemss.product_id=items.product_id AND transs.type='sales' AND transs.form_type='payment'
                    GROUP BY product_id) as total_sale
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                GROUP BY product_id
                ORDER BY product_name DESC";
        } elseif ( $f_date != "" ) {
            $query = "SELECT
                posts.post_title as product_name,
                posts.post_content as description,
                posts.ID as product_id,
                (SELECT SUM(qty)
                    FROM {$wpdb->prefix}erp_ac_transaction_items as itemss
                    INNER JOIN {$wpdb->prefix}erp_ac_transactions as transs
                    ON transs.id=itemss.transaction_id
                    WHERE itemss.product_id=items.product_id AND transs.type='expense' AND transs.form_type='vendor_credit' OR itemss.product_id=items.product_id AND transs.type='expense' AND transs.form_type='payment_voucher'
                    AND transs.issue_date <= '$f_date'
                    GROUP BY product_id) as total_purchase,
                (SELECT SUM(qty)
                    FROM {$wpdb->prefix}erp_ac_transaction_items as itemss
                    INNER JOIN {$wpdb->prefix}erp_ac_transactions as transs
                    ON transs.id=itemss.transaction_id
                    WHERE itemss.product_id=items.product_id AND transs.type='sales' AND transs.form_type='invoice' OR itemss.product_id=items.product_id AND transs.type='sales' AND transs.form_type='payment'
                    AND transs.issue_date <= '$f_date'
                    GROUP BY product_id) as total_sale
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                GROUP BY product_id
                ORDER BY product_name DESC";
        }

        $qdata = $wpdb->get_results( $query, ARRAY_A );

        $report_data = [ ];
        foreach ( $qdata as $ud ) {
            $cost_price = get_post_meta( $ud['product_id'], '_cost_price', true );
            $sale_price = get_post_meta( $ud['product_id'], '_sale_price', true );

            $report_data[] = array(
                'product_name'     => $ud['product_name'],
                'description'      => get_post_meta( $ud['product_id'], '_short_description', true ),
                'unit_cost_price'  => $cost_price,
                'unit_sale_price'  => $sale_price,
                'total_value'      => number_format( $cost_price * $ud['total_purchase'], 2 ),
                'quantity_on_hand' => $ud['total_purchase'] - $ud['total_sale']
            );
        }

        $this->send_success( $report_data );
    }

    /*
     * get item summary report data
     * para
     * return void
     */
    public function get_item_summary_report() {
        global $wpdb;

        $f_date = $_REQUEST['f_date'];
        $t_date = $_REQUEST['t_date'];
        $query  = "";

        $query = "SELECT
                posts.post_title as product_name,
                ( SELECT SUM(total)
                    FROM {$wpdb->prefix}erp_ac_transactions as tran
                    INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as itemss
                    ON itemss.transaction_id=tran.id
                    WHERE tran.issue_date < '" . $f_date . "' AND tran.type='expense' AND tran.form_type='vendor_credit'
                    AND itemss.product_id=items.product_id ) as opening_balance,
                ( SELECT SUM(total)
                    FROM {$wpdb->prefix}erp_ac_transactions as tran
                    INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as itemss
                    ON itemss.transaction_id=tran.id
                    WHERE tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='expense' AND tran.form_type='vendor_credit' AND itemss.product_id=items.product_id OR tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='expense' AND tran.form_type='payment_voucher' AND itemss.product_id=items.product_id ) as purchases,
                ( SELECT SUM(qty)
                    FROM {$wpdb->prefix}erp_ac_transactions as tran
                    INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as itemss
                    ON itemss.transaction_id=tran.id
                    WHERE tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='sales' AND tran.form_type='invoice' AND itemss.product_id=items.product_id OR tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='sales' AND tran.form_type='payment' AND itemss.product_id=items.product_id ) as total_sales_quantity,
                posts.ID as product_id
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}posts as posts
                ON items.product_id=posts.ID
                GROUP BY product_id
                ORDER BY product_name DESC";

        $qdata = $wpdb->get_results( $query, ARRAY_A );

        $report_data = [ ];
        foreach ( $qdata as $ud ) {
            $cost_price = get_post_meta( $ud['product_id'], '_cost_price', true );
            $sale_price = get_post_meta( $ud['product_id'], '_sale_price', true );

            $report_data[] = array(
                'product_name'    => $ud['product_name'],
                'opening_balance' => is_null( $ud['opening_balance'] ) ? 0 : $ud['opening_balance'],
                'purchases'       => is_null( $ud['purchases'] ) ? 0 : $ud['purchases'],
                'COGS'            => number_format( $cost_price * $ud['total_sales_quantity'], 2 ),
                'closing_balance' => $ud['purchases'] - ( $cost_price * $ud['total_sales_quantity'] ),
                'sales'           => number_format( $sale_price * $ud['total_sales_quantity'], 2 )
            );
        }

        $this->send_success( $report_data );
    }

    /*
     * get candidate report
     * para
     * return
     */
    public function get_sales_report() {
        global $wpdb;

        $f_date = $_REQUEST['f_date'];
        $t_date = $_REQUEST['t_date'];
        $query  = "";

        if ( $f_date == "" || $t_date == "" ) {
            $query = "SELECT tran.issue_date as sales_date,
                tran.ref as ref_no,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                WHERE people_type_relations.people_types_id=3 AND tran.type='sales' AND tran.form_type='invoice' OR people_type_relations.people_types_id=3 AND tran.type='sales' AND tran.form_type='payment'
                ORDER BY sales_date DESC";
        } elseif ( $f_date != "" && $t_date != "" ) {
            $query = "SELECT tran.issue_date as sales_date,
                tran.ref as ref_no,
                items.tax as tax,
                items.tax_rate as tax_rate,
                items.discount as discount,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.line_total as line_total,
                people.first_name as first_name,
                people.last_name as last_name
                FROM {$wpdb->prefix}erp_ac_transactions as tran
                INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
                ON items.transaction_id=tran.id
                INNER JOIN {$wpdb->prefix}erp_people_type_relations as people_type_relations
                ON tran.user_id=people_type_relations.people_id
                INNER JOIN {$wpdb->prefix}erp_peoples as people
                ON people.id=tran.user_id
                WHERE people_type_relations.people_types_id=3 AND tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='sales' AND tran.form_type='invoice' OR people_type_relations.people_types_id=3 AND tran.issue_date BETWEEN '" . $f_date . "' AND '" . $t_date . "' AND tran.type='sales' AND tran.form_type='payment'
                ORDER BY sales_date DESC";
        }

        $qdata = $wpdb->get_results( $query, ARRAY_A );

        $report_data = [ ];
        foreach ( $qdata as $ud ) {
            $report_data[] = array(
                'sales_date'  => date( "d M Y", strtotime( $ud['sales_date'] ) ),
                'ref_no'      => $ud['ref_no'],
                'vendor_name' => $ud['first_name'] . ' ' . $ud['last_name'],
                'tax'         => $ud['tax'],
                'tax_rate'    => $ud['tax_rate'],
                'tax_amount'  => $ud['tax_rate'] * $ud['line_total'] / 100,
                'discount'    => $ud['discount'],
                'quantity'    => $ud['quantity'],
                'unit_price'  => $ud['unit_price'],
                'line_total'  => number_format( $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total'], 2 )
            );
        }

        $this->send_success( $report_data );
    }

//    /*
//     * import product
//     *
//     * return void
//     */
//    public function inv_import_product() {
//        $this->verify_nonce( 'inventory_nonce' );
//
//        if ( empty( $_FILES['imp']['tmp_name'] ) ) {
//            $this->send_error( __( 'File upload error!', 'erp-pro' ) );
//        }
//
//        $filename       = $_FILES['imp']['tmp_name'];
//        $file_real_name = $_FILES['imp']['name'];
//        $file_explode   = explode( '.', $file_real_name );
//        $file_extension = end( $file_explode );
//
//        if ( $file_extension == "csv" ) {
//            $flag_for_title      = false;
//            $total_product       = 0;
//            $product_sku_array   = [ ];
//            $product_title_array = [ ];
//            $handle              = fopen( $filename, 'r' );
//            while ( ( $fileop = fgetcsv( $handle, 1000, "," ) ) !== false ) {
//                if ( $flag_for_title == true ) {
//                    $total_product++;
//                    array_push( $product_sku_array, $fileop[0] );
//                    array_push( $product_title_array, $fileop[1] );
//                } else {
//                    $flag_for_title = true;
//                }
//            }
//
//            if ( $total_product == 2 ) {
//                $flag_for_title = false;
//                $handle         = fopen( $filename, 'r' );
//                while ( ( $fileop = fgetcsv( $handle, 1000, "," ) ) !== false ) {
//                    $sku               = $fileop[0];
//                    $product_name      = $fileop[1];
//                    $pdescription      = $fileop[2];
//                    $sdescription      = $fileop[3];
//                    $short_description = $fileop[4];
//                    $cost_price        = $fileop[5];
//                    $sale_price        = $fileop[6];
//                    $tax_on_purchase   = $fileop[7];
//                    $tax_on_sales      = $fileop[8];
//                    if ( $flag_for_title == true ) {
//                        $post_array = [
//                            'post_type'      => 'erp_inv_product', //WooCommerce issue
//                            'post_title'     => $product_name,
//                            'post_status'    => 'publish',
//                            'comment_status' => 'closed',
//                            'ping_status'    => 'closed',
//                        ];
//                        $postid     = wp_insert_post( $post_array );
//                        update_post_meta( $postid, '_sku', $sku );
//                        update_post_meta( $postid, '_cost_price', $cost_price );
//                        update_post_meta( $postid, '_sale_price', $sale_price );
//                        update_post_meta( $postid, '_tax_on_purchase', $tax_on_purchase );
//                        update_post_meta( $postid, '_tax_on_sales', $tax_on_sales );
//                        update_post_meta( $postid, '_purchase_description', $pdescription );
//                        update_post_meta( $postid, '_sales_description', $sdescription );
//                        update_post_meta( $postid, '_short_description', $short_description );
//                    } else {
//                        $flag_for_title = true;
//                    }
//                }
//            } elseif ( $total_product > 2 ) {
//                if ( $this->array_has_duplicate_values( $product_sku_array ) ) {
//                    $this->send_error( __( "Item code should be unique. Please give unique item code value in your csv file then import again.", "erp-inventory" ) );
//                } elseif ( $this->array_has_duplicate_values( $product_title_array ) ) {
//                    $this->send_error( __( "Please give an unique product name in your csv file then import again.", "erp-inventory" ) );
//                } elseif ( in_array( "", $product_sku_array ) ) {
//                    $this->send_error( __( "Item code should not be empty. Please enter unique item code value in your csv file then import again.", "erp-inventory" ) );
//                } elseif ( $this->check_duplicate_sku_to_db( $product_sku_array ) ) {
//                    $this->send_error( __( "Item code should be unique. One of your item code value is matching to your published product's item code. Please enter unique item code value in your csv file then try again.", "erp-inventory" ) );
//                } elseif ( in_array( "", $product_title_array ) ) {
//                    $this->send_error( __( "Product name should not be empty. Please enter product name in your csv file then import again.", "erp-inventory" ) );
//                } else {
//                    $flag_for_title = false;
//                    $handle         = fopen( $filename, 'r' );
//                    while ( ( $fileop = fgetcsv( $handle, 1000, "," ) ) !== false ) {
//                        $sku               = $fileop[0];
//                        $product_name      = $fileop[1];
//                        $pdescription      = $fileop[2];
//                        $sdescription      = $fileop[3];
//                        $short_description = $fileop[4];
//                        $cost_price        = $fileop[5];
//                        $sale_price        = $fileop[6];
//                        $tax_on_purchase   = $fileop[7];
//                        $tax_on_sales      = $fileop[8];
//                        if ( $flag_for_title == true ) {
//                            $post_array = [
//                                'post_type'      => 'erp_inv_product', //WooCommerce issue
//                                'post_title'     => $product_name,
//                                'post_status'    => 'publish',
//                                'comment_status' => 'closed',
//                                'ping_status'    => 'closed',
//                            ];
//                            $postid     = wp_insert_post( $post_array );
//                            update_post_meta( $postid, '_sku', $sku );
//                            update_post_meta( $postid, '_cost_price', $cost_price );
//                            update_post_meta( $postid, '_sale_price', $sale_price );
//                            update_post_meta( $postid, '_tax_on_purchase', $tax_on_purchase );
//                            update_post_meta( $postid, '_tax_on_sales', $tax_on_sales );
//                            update_post_meta( $postid, '_purchase_description', $pdescription );
//                            update_post_meta( $postid, '_sales_description', $sdescription );
//                            update_post_meta( $postid, '_short_description', $short_description );
//                        } else {
//                            $flag_for_title = true;
//                        }
//                    }
//                }
//            }
//
//            $this->send_success( __( "Products imported successfully.", "erp-inventory" ) );
//        } else {
//            $this->send_error( __( "Given file is not csv. Please give a csv file.", "erp-inventory" ) );
//        }
//    }
//
//    /*
//     * product duplicate in csv file
//     *
//     * return bool
//     */
//    public function array_has_duplicate_values( $array ) {
//        if ( count( $array ) !== count( array_unique( $array ) ) ) {
//            return true;
//        } else {
//            return false;
//        }
//    }

//    /*
//     * detecting duplicate sku to previous products
//     * array sku_list
//     * return bool
//     */
//    public function check_duplicate_sku_to_db( $sku_list ) {
//        /*validating sku then save sku*/
//        //$inv_obj = new \WeDevs\ERP\Inventory\Inventory();
//        $query   = new \WP_Query( array( 'post_type' => 'erp_inv_product', 'posts_per_page' => -1 ) ); //WooCommerce issue
//        $all_sku = [ ];
//        while ( $query->have_posts() ) {
//            $query->the_post();
//            array_push( $all_sku, get_post_meta( get_the_ID(), '_sku', true ) );
//        }
//
//        foreach ( $sku_list as $skulist ) {
//            foreach ( $all_sku as $allsku ) {
//                if ( $skulist == $allsku ) {
//                    return true;
//                }
//            }
//        }
//
//        return false;
//    }

}
