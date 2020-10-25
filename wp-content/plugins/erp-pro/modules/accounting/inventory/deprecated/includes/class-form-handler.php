<?php
namespace WeDevs\ERP\Inventory;

use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * Handle the form submissions
 *
 * Although our most of the forms uses ajax and popup, some
 * are needed to submit via regular form submits. This class
 * Handles those form submission in this module
 *
 * @package WP ERP
 * @subpackage HRM
 */
class Form_Handler {

    use Hooker;

    /**
     * Hook 'em all
     */
    public function __construct() {
        $this->action( 'admin_init', 'export_purchase_report_csv' );
        $this->action( 'admin_init', 'export_sales_report_csv' );
        $this->action( 'load-erp-settings_page_erp-tools', 'download_csv_file' );
        //$this->action( 'load-accounting_page_erp-accounting-reports', 'export_inv_report_csv' );
        $this->action('erp_tool_import_csv_action', 'import_csv_file');
    }

    /*
     * export purchase report
     */
    public function export_purchase_report_csv() {
        if ( isset( $_REQUEST['func'] ) && $_REQUEST['func'] == 'purchase-report-csv' ) {
            global $wpdb;
            $from_date = isset( $_REQUEST['from_date'] ) ? $_REQUEST['from_date'] : '';
            $to_date   = isset( $_REQUEST['to_date'] ) ? $_REQUEST['to_date'] : '';

            if ( $from_date == "" && $to_date == "" ) {
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
            } else if ( $from_date != "" && $to_date != "" ) {
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
                WHERE people_type_relations.people_types_id=4 AND tran.issue_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND tran.type='expense' AND tran.form_type='vendor_credit' OR people_type_relations.people_types_id=4 AND tran.issue_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND tran.type='expense' AND tran.form_type='payment_voucher'
                ORDER BY purchase_date DESC";
            } else {
                $query = "";
            }

            $qdata = $wpdb->get_results( $query, ARRAY_A );

            // create a file pointer connected to the output stream
            //BUILD CSV CONTENT
            $csv = 'Purchase Date, Vendor Name, Reference Number, Tax Rate, Quantity, Unit Price, Line Total' . "\n";

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
                    'line_total'    => $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total']
                );
                $csv .= date( "d M Y", strtotime( $ud['purchase_date'] ) ) . "," . $ud['first_name'] . ' ' . $ud['last_name'] . "," . $ud['ref_no'] . "," . $ud['tax_rate'] . "," . $ud['quantity'] . "," . $ud['unit_price'] . "," . ( $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total'] ) . "\n";
            }

            //NAME THE FILE
            $table = "purchase-report";

            //OUTPUT HEADERS
            header( "Pragma: public" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Cache-Control: private", false );
            header( "Content-Type: application/octet-stream" );
            header( "Content-Disposition: attachment; filename=\"$table.csv\";" );
            header( "Content-Transfer-Encoding: binary" );
            echo( $csv );
            exit;
        }
    }

    /*
     * export sales report
     */
    public function export_sales_report_csv() {
        if ( isset( $_REQUEST['func'] ) && $_REQUEST['func'] == 'sales-report-csv' ) {
            global $wpdb;
            $from_date = isset( $_REQUEST['from_date'] ) ? $_REQUEST['from_date'] : '';
            $to_date   = isset( $_REQUEST['to_date'] ) ? $_REQUEST['to_date'] : '';

            if ( $from_date == "" && $to_date == "" ) {
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
            } elseif ( $from_date != "" && $to_date != "" ) {
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
                WHERE people_type_relations.people_types_id=3 AND tran.issue_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND tran.type='sales' AND tran.form_type='invoice' OR people_type_relations.people_types_id=3 AND tran.issue_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND tran.type='sales' AND tran.form_type='payment'
                ORDER BY sales_date DESC";
            } else {
                $query = "";
            }

            $qdata = $wpdb->get_results( $query, ARRAY_A );

            // create a file pointer connected to the output stream
            //BUILD CSV CONTENT
            $csv         = 'Sales Date, Customer Name, Reference Number, Tax Rate, Quantity, Unit Price, Line Total' . "\n";
            $report_data = [ ];
            foreach ( $qdata as $ud ) {
                $report_data[] = array(
                    'sales_date'    => date( "d M Y", strtotime( $ud['sales_date'] ) ),
                    'ref_no'        => $ud['ref_no'],
                    'customer_name' => $ud['first_name'] . ' ' . $ud['last_name'],
                    'tax'           => $ud['tax'],
                    'tax_rate'      => $ud['tax_rate'],
                    'tax_amount'    => $ud['tax_rate'] * $ud['line_total'] / 100,
                    'discount'      => $ud['discount'],
                    'quantity'      => $ud['quantity'],
                    'unit_price'    => $ud['unit_price'],
                    'line_total'    => $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total']
                );
                $csv .= date( "d M Y", strtotime( $ud['sales_date'] ) ) . "," . $ud['first_name'] . ' ' . $ud['last_name'] . "," . $ud['ref_no'] . "," . $ud['tax_rate'] . "," . $ud['quantity'] . "," . $ud['unit_price'] . "," . ( $ud['tax_rate'] * $ud['line_total'] / 100 + $ud['line_total'] ) . "\n";
            }

            //NAME THE FILE
            $table = "sales-report";

            //OUTPUT HEADERS
            header( "Pragma: public" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Cache-Control: private", false );
            header( "Content-Type: application/octet-stream" );
            header( "Content-Disposition: attachment; filename=\"$table.csv\";" );
            header( "Content-Transfer-Encoding: binary" );
            echo( $csv );
            exit;
        }
    }

    /*
     * download csv file
     * @return void
     */
    public function download_csv_file() {
        if ( isset( $_REQUEST['func'] ) && $_REQUEST['func'] == 'dl-csv-template-file' ) {

            //BUILD CSV CONTENT
            $csv = 'Item code, product name, purchase description, sale description, short description, cost price, sale price, Tax Rate on purchase, Tax Rate on sale' . "\n";

            //NAME THE FILE
            $table = "product";

            //OUTPUT HEADERS
            header( "Pragma: public" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Cache-Control: private", false );
            header( "Content-Type: application/octet-stream" );
            header( "Content-Disposition: attachment; filename=\"$table.csv\";" );
            header( "Content-Transfer-Encoding: binary" );
            echo( $csv );
            exit;
        }
    }

    /**
     * Import inventory
     * @since 1.0.2
     *
     * @param $data
     */
    public function import_csv_file( $data ) {
        $type = $data['type'];
        if ( $type !== 'product' ) {
            return;
        }

        require_once WPERP_INCLUDES . '/lib/parsecsv.lib.php';

        $csv = new \parseCSV( $_FILES['csv_file']['tmp_name'] );

        if ( empty( $csv->data ) ) {
            $location = add_query_arg( array( 'tab'         => 'import',
                                              'invprod-msg' => 5
            ), admin_url( 'admin.php?page=erp-tools' ) );
            wp_redirect( $location );
            exit;
        }

        global $wpdb;
        $counter = 0;
        foreach ( $csv->data as $data_item ) {

            $data_arr          = array_values( $data_item );
            $product_name      = $data_arr[0];
            $asset_acc         = $data_arr[1];
            $sku               = $data_arr[2];
            $cost_price        = $data_arr[3];
            $purchase_acc      = $data_arr[4];
            $purchase_tax      = $data_arr[5];
            $p_description     = $data_arr[6];
            $sale_price        = $data_arr[7];
            $sale_acc          = $data_arr[8];
            $sale_tax          = $data_arr[9];
            $s_description     = $data_arr[10];
            $short_description = $data_arr[11];

            $sql    = "select * from wp_posts right join wp_postmeta on wp_posts.id=wp_postmeta.post_id where wp_postmeta.`meta_key` = '_sku' AND  wp_postmeta.meta_value = '{$sku}'";
            $result = $wpdb->get_results( $sql );
            if ( ! empty( $result ) ) {
                // already have one skip
                continue;
            }

            $post_array = [
                'post_type'      => 'erp_inv_product', //WooCommerce issue
                'post_title'     => $product_name,
                'post_status'    => 'publish',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
            ];

            $postid = wp_insert_post( $post_array );
            if ( is_wp_error( $postid ) ) {
                continue;
            }
            update_post_meta( $postid, '_inventory_asset_account', $asset_acc );
            update_post_meta( $postid, '_purchase_account', $purchase_acc );
            update_post_meta( $postid, '_sales_account', $sale_acc );
            update_post_meta( $postid, '_sku', $sku );
            update_post_meta( $postid, '_cost_price', $cost_price );
            update_post_meta( $postid, '_sale_price', $sale_price );
            update_post_meta( $postid, '_tax_on_purchase', $purchase_tax );
            update_post_meta( $postid, '_tax_on_sales', $sale_tax );
            update_post_meta( $postid, '_purchase_description', $p_description );
            update_post_meta( $postid, '_sales_description', $s_description );
            update_post_meta( $postid, '_short_description', $short_description );

            $counter ++;
        }

        if ( $counter > 0 ) {
            $location = add_query_arg( array( 'tab'         => 'import',
                                              'invprod-msg' => 6
            ), admin_url( 'admin.php?page=erp-tools' ) );
        } else {
            $location = add_query_arg( array( 'tab'         => 'import',
                                              'invprod-msg' => 11
            ), admin_url( 'admin.php?page=erp-tools' ) );
        }
        wp_redirect( $location );
    }

}

new Form_Handler();