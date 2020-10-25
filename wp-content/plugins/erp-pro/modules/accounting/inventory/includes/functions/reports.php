<?php
/**
 * Inventory sales report
 *
 * @param array $args
 * @return array|object|null
 */
function erp_acct_get_inventory_sales_report( $args = [] ) {
    global $wpdb;

    if ( empty( $args['start_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
    } else {
        $closest_fy_date    = erp_acct_get_closest_fn_year_date( $args['start_date'] );
        $args['start_date'] = $closest_fy_date['start_date'];
    }

    if ( empty( $args['end_date'] ) ) {
        $args['end_date'] = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    if ( empty( $args['start_date'] ) && empty( $args['end_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
        $args['end_date']   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    $sql = "SELECT 
        invoice.voucher_no,
        invoice.trn_date,
        invoice.customer_name,
        invoice.tax,
        invoice.discount,
        invoice.amount as price,
        product.name as product,
        details.stock_out as qty
        
        FROM {$wpdb->prefix}erp_acct_invoices as invoice
        LEFT JOIN {$wpdb->prefix}erp_acct_product_details as details ON invoice.voucher_no = details.trn_no 
        LEFT JOIN {$wpdb->prefix}erp_acct_products as product ON details.product_id = product.id
        WHERE invoice.trn_date BETWEEN '{$args['start_date']}' AND '{$args['end_date']}'";

    $results = $wpdb->get_results( $sql, ARRAY_A );

    return $results;

}

/**
 * Inventory purchase report
 *
 * @param array $args
 * @return array|object|null
 */
function erp_acct_get_inventory_purchase_report( $args = [] ) {
    global $wpdb;

    if ( empty( $args['start_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
    } else {
        $closest_fy_date    = erp_acct_get_closest_fn_year_date( $args['start_date'] );
        $args['start_date'] = $closest_fy_date['start_date'];
    }

    if ( empty( $args['end_date'] ) ) {
        $args['end_date'] = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    if ( empty( $args['start_date'] ) && empty( $args['end_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
        $args['end_date']   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    $sql = "SELECT 
        purchase.voucher_no,
        purchase.trn_date,
        purchase.vendor_name,
        purchase.amount as price,
        product.name as product,
        details.stock_in as qty
        
        FROM {$wpdb->prefix}erp_acct_purchase as purchase
        LEFT JOIN {$wpdb->prefix}erp_acct_product_details as details ON purchase.voucher_no = details.trn_no 
        LEFT JOIN {$wpdb->prefix}erp_acct_products as product ON details.product_id = product.id
        WHERE purchase.trn_date BETWEEN '{$args['start_date']}' AND '{$args['end_date']}'";

    $results = $wpdb->get_results( $sql, ARRAY_A );

    return $results;

}

/**
 * Get inventory list report
 *
 * @param array $args
 * @return array|object|null
 */
function erp_acct_get_inventory_list_report( $args = [] ) {
    global $wpdb;

    if ( empty( $args['start_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
    } else {
        $closest_fy_date    = erp_acct_get_closest_fn_year_date( $args['start_date'] );
        $args['start_date'] = $closest_fy_date['start_date'];
    }

    if ( empty( $args['end_date'] ) ) {
        $args['end_date'] = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    if ( empty( $args['start_date'] ) && empty( $args['end_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
        $args['end_date']   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    $sql = "SELECT 
        product.name,
        product.cost_price,
        product.sale_price,
        sum(details.stock_in) as qty
        
        FROM {$wpdb->prefix}erp_acct_products as product
        LEFT JOIN {$wpdb->prefix}erp_acct_product_details as details ON product.id = details.product_id 
        LEFT JOIN {$wpdb->prefix}erp_acct_product_price as product_price ON details.trn_no = product_price.trn_no
        WHERE product_price.trn_date BETWEEN '{$args['start_date']}' AND '{$args['end_date']}' GROUP BY product.id";

    $results = $wpdb->get_results( $sql, ARRAY_A );

    return $results;
}

/**
 *
 * Get inventory summary report
 * @param array $args
 * @return array|object|null
 */
function erp_acct_get_inventory_summary_report( $args = [] ) {
    global $wpdb;

    if ( empty( $args['start_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
    } else {
        $closest_fy_date    = erp_acct_get_closest_fn_year_date( $args['start_date'] );
        $args['start_date'] = $closest_fy_date['start_date'];
    }

    if ( empty( $args['end_date'] ) ) {
        $args['end_date'] = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    if ( empty( $args['start_date'] ) && empty( $args['end_date'] ) ) {
        $args['start_date'] = date( 'Y-m-d', strtotime( 'first day of january' ) );
        $args['end_date']   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
    }

    $sql = "SELECT 
        product.name,
        product.cost_price,
        product.sale_price,
        product_price.price as cogs,
        sum(details.stock_in) as no_purchase,
        sum(details.stock_in) as no_sale
        
        FROM {$wpdb->prefix}erp_acct_products as product
        LEFT JOIN {$wpdb->prefix}erp_acct_product_details as details ON product.id = details.product_id 
        LEFT JOIN {$wpdb->prefix}erp_acct_product_price as product_price ON details.trn_no = product_price.trn_no
        WHERE product_price.trn_date BETWEEN '{$args['start_date']}' AND '{$args['end_date']}' GROUP BY product.id";

    $results = $wpdb->get_results( $sql, ARRAY_A );

    return $results;
}
