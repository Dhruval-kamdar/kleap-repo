<?php
/**
 * get product dropdown
 *
 * @return string
 */
function erp_inv_render_product_dropdown_html($head, $header_slug, $item) {

    $product_name = "";
    if ( isset($item['product_id']) && $item['product_id'] != '' ) {
        $gp = get_post($item['product_id']);
        $product_name = $gp->post_title;
    }

    $defaults = [
        'select_text' => __( '&#8212; Select &#8212;', 'erp-pro' ),
        'selected'    => '0',
        'name'        => 'inv-product-id[]',
        'class'       => 'erp-select2 inv-product-selection',
        'required'    => false
    ];

    $query = new WP_Query( array( 'post_type' => 'erp_inv_product', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'title' ) );

    $dropdown = sprintf( '<select name="%1$s" id="%1$s" class="%2$s"%3$s>', $defaults['name'], $defaults['class'], $defaults['required'] == true ? ' required="required"' : '' );

    $dropdown .= '<option value="">' . $defaults['select_text'] . '</option>';

    if ( $query->have_posts() ) {
        while ($query->have_posts()) : $query->the_post();
            if ( get_the_title( get_the_ID() ) == $product_name ) {
                $dropdown .= '<option value="' . get_the_ID() . '" selected="selected">' . get_the_title( get_the_ID() ) . '</option>';
            } else {
                $dropdown .= '<option value="' . get_the_ID() . '">' . get_the_title( get_the_ID() ) . '</option>';
            }
        endwhile;
        $query->reset_postdata();
    }

    $dropdown .= '</select>';

    return $dropdown;
}

/*
 * get product stock
 * para
 * return int
 */
function erp_inv_get_this_product_stock( $product_id ) {
    global $wpdb;

    $purchase_quantity = $wpdb->get_var( $wpdb->prepare(
        "SELECT sum(qty)
		FROM {$wpdb->prefix}erp_ac_transaction_items as items
		INNER JOIN {$wpdb->prefix}erp_ac_transactions as tran
		ON tran.id=items.transaction_id
		WHERE items.product_id = %d AND tran.type=%s AND tran.form_type=%s",
        $product_id, 'expense', 'vendor_credit'
    ) );

    $sales_quantity = $wpdb->get_var( $wpdb->prepare(
        "SELECT sum(qty)
		FROM {$wpdb->prefix}erp_ac_transaction_items as items
		INNER JOIN {$wpdb->prefix}erp_ac_transactions as tran
		ON tran.id=items.transaction_id
		WHERE items.product_id = %d AND tran.type=%s AND tran.form_type=%s",
        $product_id, 'sales', 'invoice'
    ) );

    return $purchase_quantity - $sales_quantity;
}

/*
 * get recent 10 transactions of product purchase and sales
 *
 * return array
 */
function erp_inv_get_last_ten_transactions( $postid ) {
    global $wpdb;

    $purchase_query = $wpdb->get_results(
        "SELECT tran.issue_date as podate,
                'bill' as type,
                tran.id as tranid,
                tran.ref as ref_no,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.qty * items.unit_price as total
        FROM {$wpdb->prefix}erp_ac_transactions as tran
        INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
        ON items.transaction_id=tran.id
        WHERE items.product_id = $postid AND tran.type='expense' AND tran.form_type='vendor_credit' OR items.product_id = $postid AND tran.type='expense' AND tran.form_type='payment_voucher'
        ORDER BY tran.issue_date DESC
        LIMIT 5"
    );

    $sales_query = $wpdb->get_results(
        "SELECT tran.issue_date as podate,
                'invoice' as type,
                tran.id as tranid,
                tran.ref as ref_no,
                items.qty as quantity,
                items.unit_price as unit_price,
                items.qty * items.unit_price as total
        FROM {$wpdb->prefix}erp_ac_transactions as tran
        INNER JOIN {$wpdb->prefix}erp_ac_transaction_items as items
        ON items.transaction_id=tran.id
        WHERE items.product_id = $postid AND tran.type='sales' AND tran.form_type='invoice' OR items.product_id = $postid AND tran.type='sales' AND tran.form_type='payment'
        ORDER BY tran.issue_date DESC
        LIMIT 5"
    );

    $purchase_and_sales = array_merge( $purchase_query, $sales_query );

    if ( count($purchase_and_sales) > 0 ) {
        foreach ( $purchase_and_sales as $key => $part ) {
            $sort[$key] = strtotime( $part->podate );
        }
        array_multisort( $sort, SORT_DESC, $purchase_and_sales );
    }

    return $purchase_and_sales;
}

/*
 * get tax
 *
 * return array
 */
function erp_inv_get_tax_list() {
    global $wpdb;
    $query = "SELECT tax.name as tax_name,tax_item.tax_id as tax_id
        FROM {$wpdb->prefix}erp_ac_tax as tax
        INNER JOIN {$wpdb->prefix}erp_ac_tax_items as tax_item
        ON tax.id=tax_item.tax_id
        ORDER BY tax.id DESC";
    return $wpdb->get_results($query);
}

function erp_inv_product_page_redirect() {
    if ( isset( $_GET['section'] ) && $_GET['section'] == 'erp_inv_product' ) {
        wp_redirect( admin_url( 'edit.php?post_type=erp_inv_product' ), 301 );
        exit;
    }
}

function erp_inv_product_cat_page_redirect() {
    if ( isset( $_GET['sub-section'] ) && $_GET['sub-section'] == 'erp_inv_product_category' ) {
        wp_redirect( admin_url( 'edit-tags.php?taxonomy=product_category' ), 301 );
        exit;
    }
}

function erp_inv_product_cat_redirect() {
    if ( ! \weDevs\ERP_PRO\ACC\Inventory\Module::need_backward_compatible() ) {
        add_action( 'admin_init', 'erp_inv_product_cat_page_redirect' );
        add_action( 'admin_init', 'erp_inv_product_page_redirect' );
    }
}
erp_inv_product_cat_redirect();
