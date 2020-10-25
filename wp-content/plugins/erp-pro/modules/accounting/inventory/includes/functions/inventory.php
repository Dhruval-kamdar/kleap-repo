<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get all products
 *
 * @return mixed
 */

function erp_acct_get_all_inventory_items( $args = [] ) {
    global $wpdb;

    $defaults = [
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'DESC',
        'count'   => false,
        's'       => '',
    ];

    $args = wp_parse_args( $args, $defaults );

    $where = '';
    $limit = '';

    if ( $args['number'] != '-1' ) {
        $limit = "LIMIT {$args['number']} OFFSET {$args['offset']}";
    }

    $sql = "SELECT";

    if ( $args['count'] ) {
        $sql .= " COUNT( product.id ) as total_number";
    } else {
        $sql .= " product.id,
            product.name,
            product.product_type_id,
            product.cost_price,
            product.sale_price,
            (SUM(product_detail.stock_in) - SUM(product_detail.stock_out)) as stock,
            product.tax_cat_id,
            tax_cat.name as tax_cat_name,
            
            people.id AS vendor,
            CONCAT(people.first_name, ' ',  people.last_name) AS vendor_name,
            cat.id AS category_id,
            cat.name AS cat_name";
    }

    $sql .= " FROM {$wpdb->prefix}erp_acct_products AS product
        LEFT JOIN {$wpdb->prefix}erp_peoples AS people ON product.vendor = people.id
        LEFT JOIN {$wpdb->prefix}erp_acct_product_categories AS cat ON product.category_id = cat.id
        LEFT JOIN {$wpdb->prefix}erp_acct_product_details AS product_detail ON product.id = product_detail.product_id
        LEFT JOIN {$wpdb->prefix}erp_acct_tax_categories AS tax_cat ON tax_cat.id = product.tax_cat_id
        WHERE product.product_type_id=1 GROUP BY product_detail.product_id ORDER BY product.{$args['orderby']} {$args['order']} {$limit}";

    if ( $args['count'] ) {
        return $wpdb->get_var( $sql );
    }

    return $wpdb->get_results( $sql, ARRAY_A );
}


/**
 * Insert inventory data
 *
 * @param $data
 * @return mixed
 */
add_action( 'erp_acct_after_purchase_create', 'erp_acct_inventory_purchase_create', 10, 2 );

function erp_acct_inventory_purchase_create( $data, $voucher_no ) {
    global $wpdb;

    $user_id = get_current_user_id();

    $data['created_at'] = date( 'Y-m-d H:i:s' );
    $data['created_by'] = $user_id;
    $data['updated_at'] = date( 'Y-m-d H:i:s' );
    $data['updated_by'] = $user_id;

    try {
        $wpdb->query( 'START TRANSACTION' );

        $items = $data['line_items'];

        foreach ( $items as $key => $item ) {

            $data['product_type_id'] = erp_acct_get_product_type_id_by_product_id( $item['product_id'] );

            if ( $data['product_type_id'] != 1 ) {
                continue;
            }

            $wpdb->insert( $wpdb->prefix . 'erp_acct_product_details', array(
                'trn_no'     => $voucher_no,
                'product_id' => $item['product_id'],
                'stock_in'   => $item['qty'],
                'stock_out'  => 0,
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by']
            ) );

            $wpdb->insert( $wpdb->prefix . 'erp_acct_product_price', array(
                'trn_no'     => $voucher_no,
                'product_id' => $item['product_id'],
                'price'      => $item['item_total'],
                'trn_date'   => $data['trn_date'],
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by'],
            ) );
        }

        $wpdb->query( 'COMMIT' );

    } catch ( Exception $e ) {
        $wpdb->query( 'ROLLBACK' );
        return new WP_error( 'inventory-exception', $e->getMessage() );
    }

}

/**
 * Update inventory data
 *
 * @param $data
 * @return mixed
 */
add_action( 'erp_acct_after_purchase_update', 'erp_acct_inventory_purchase_update', 10, 2 );

function erp_acct_inventory_purchase_update( $data, $voucher_no ) {
    global $wpdb;

    $user_id = get_current_user_id();

    $data['updated_at'] = date( 'Y-m-d H:i:s' );
    $data['updated_by'] = $user_id;

    try {
        $wpdb->query( 'START TRANSACTION' );

        $items = $data['line_items'];

        foreach ( $items as $key => $item ) {

            $data['product_type_id'] = erp_acct_get_product_type_id_by_product_id( $item['product_id'] );

            if ( $data['product_type_id'] != 1 ) {
                continue;
            }

            $wpdb->update( $wpdb->prefix . 'erp_acct_product_details', array(
                'product_id' => $item['product_id'],
                'stock_in'   => $item['qty'],
                'stock_out'  => 0,
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by']
            ), array(
                'trn_no' => $voucher_no,
            ) );

            $wpdb->update( $wpdb->prefix . 'erp_acct_product_price', array(
                'product_id' => $item['product_id'],
                'price'      => $item['item_total'],
                'trn_date'   => $data['trn_date'],
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by'],
            ), array(
                'trn_no' => $voucher_no,
            ) );
        }

        $wpdb->query( 'COMMIT' );

    } catch ( Exception $e ) {
        $wpdb->query( 'ROLLBACK' );
        return new WP_error( 'inventory-exception', $e->getMessage() );
    }

}


/**
 * Insert inventory sales data
 *
 * @param $data
 * @return mixed
 */
add_action( 'erp_acct_after_sales_create', 'erp_acct_inventory_items_sell_create', 10, 2 );

function erp_acct_inventory_items_sell_create( $data, $voucher_no ) {
    global $wpdb;

    $user_id = get_current_user_id();

    $data['created_at'] = date( 'Y-m-d H:i:s' );
    $data['created_by'] = $user_id;
    $data['updated_at'] = date( 'Y-m-d H:i:s' );
    $data['updated_by'] = $user_id;

    try {
        $wpdb->query( 'START TRANSACTION' );

        $items = $data['line_items'];

        foreach ( $items as $key => $item ) {

            $data['product_type_id'] = erp_acct_get_product_type_id_by_product_id( $item['product_id'] );

            if ( $data['product_type_id'] != 1 ) {
                continue;
            }

            $wpdb->insert( $wpdb->prefix . 'erp_acct_product_details', array(
                'trn_no'     => $voucher_no,
                'product_id' => $item['product_id'],
                'stock_in'   => 0,
                'stock_out'  => $item['qty'],
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by']
            ) );

            $wpdb->insert( $wpdb->prefix . 'erp_acct_product_price', array(
                'trn_no'     => $voucher_no,
                'product_id' => $item['product_id'],
                'price'      => $item['item_total'],
                'trn_date'   => $data['date'],
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by'],
            ) );
        }

        $wpdb->query( 'COMMIT' );

    } catch ( Exception $e ) {
        $wpdb->query( 'ROLLBACK' );
        return new WP_error( 'inventory-exception', $e->getMessage() );
    }

}

/**
 * Update inventory sales data
 *
 * @param $data
 * @return mixed
 */
add_action( 'erp_acct_after_sales_update', 'erp_acct_inventory_sales_updates', 10, 2 );

function erp_acct_inventory_sales_updates( $data, $voucher_no ) {
    global $wpdb;

    $user_id = get_current_user_id();

    $data['updated_at'] = date( 'Y-m-d H:i:s' );
    $data['updated_by'] = $user_id;

    try {
        $wpdb->query( 'START TRANSACTION' );

        $items = $data['line_items'];

        foreach ( $items as $key => $item ) {

            $data['product_type_id'] = erp_acct_get_product_type_id_by_product_id( $item['product_id'] );

            if ( $data['product_type_id'] != 1 ) {
                continue;
            }

            $wpdb->update( $wpdb->prefix . 'erp_acct_product_details', array(
                'product_id' => $item['product_id'],
                'stock_in'   => 0,
                'stock_out'  => $item['qty'],
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by']
            ), array(
                'trn_no' => $voucher_no,
            ) );

            $wpdb->update( $wpdb->prefix . 'erp_acct_product_price', array(
                'product_id' => $item['product_id'],
                'price'      => $item['item_total'],
                'trn_date'   => $data['date'],
                'created_at' => $data['created_at'],
                'created_by' => $data['created_by'],
                'updated_at' => $data['updated_at'],
                'updated_by' => $data['updated_by'],
            ), array(
                'trn_no' => $voucher_no,
            ) );
        }

        $wpdb->query( 'COMMIT' );

    } catch ( Exception $e ) {
        $wpdb->query( 'ROLLBACK' );
        return new WP_error( 'inventory-exception', $e->getMessage() );
    }

}

/**
 * Get Inventory stock overview
 */
function erp_acct_get_inventory_stock_overview( $args = [] ) {
    global $wpdb;

    $where = '';

    if ( ! empty( $args['start_date'] ) ) {
        $where .= " AND invoice.trn_date BETWEEN '{$args['start_date']}' AND '{$args['end_date']}'";
    }

    $sql = "SELECT SUM(stock_in) as stock_in, SUM(stock_out) as stock_out
        FROM {$wpdb->prefix}erp_acct_product_details";

    return $wpdb->get_row( $sql, ARRAY_A );
}

/**
 * Get Inventory transactions overview
 */
function erp_acct_get_inventory_transactions_overview( $args = [] ) {
    global $wpdb;

    $where = '';

    if ( ! empty( $args['start_date'] ) ) {
        $where .= " AND invoice.trn_date BETWEEN '{$args['start_date']}' AND '{$args['end_date']}'";
    }

    $sql1 = "SELECT SUM(price) as sales
        FROM {$wpdb->prefix}erp_acct_product_price AS poduct_price
        LEFT JOIN {$wpdb->prefix}erp_acct_voucher_no AS voucher ON voucher.id = poduct_price.trn_no WHERE voucher.type='invoice' {$where}";

    $trn_data['sales'] = $wpdb->get_var( $sql1 );

    $sql2 = "SELECT SUM(price) as sales
        FROM {$wpdb->prefix}erp_acct_product_price AS poduct_price
        LEFT JOIN {$wpdb->prefix}erp_acct_voucher_no AS voucher ON voucher.id = poduct_price.trn_no WHERE voucher.type='purchase' {$where}";

    $trn_data['purchase'] = $wpdb->get_var( $sql2 );

    return $trn_data;
}
