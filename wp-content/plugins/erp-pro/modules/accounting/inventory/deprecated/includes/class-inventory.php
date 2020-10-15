<?php

namespace WeDevs\ERP\Inventory;

use WeDevs\ERP\Framework\Traits\Hooker;

/**
 *  Inventory class HR
 *
 *  Inventory for employees
 *
 * @since 0.1
 *
 * @author weDevs <info@wedevs.com>
 */
class Inventory {

    use Hooker;

    private $post_type = 'erp_inv_product';
    private $assign_type = [ ];

    /**
     *  Load autometically all actions
     */
    function __construct() {

        $this->assign_type = array(
            ''                  => __( '-- Select --', 'erp-pro' ),
            'all_employee'      => __( 'All Employees', 'erp-pro' ),
            'selected_employee' => __( 'Selected Employee', 'erp-pro' )
        );

        $this->action( 'init', 'post_types' );
        $this->action( 'init', 'register_product_taxonomy' );
        $this->action( 'do_meta_boxes', 'do_metaboxes_inventory' );
        $this->action( 'save_post', 'save_inventory_product_meta', 10, 2 );
        $this->action( 'manage_erp_inv_product_posts_custom_column', 'product_table_data', 10, 2 );

        $this->filter( 'manage_erp_inv_product_posts_columns', 'product_table_head' );

    }

    /**
     * Register product post type
     *
     * @since 0.1
     *
     * @return void
     */
    function post_types() {
        $capability = 'erp_hr_manager';
        register_post_type( $this->post_type,
            array(
                'label'           => __( 'Inventory product', 'erp-pro' ),
                'description'     => '',
                'public'          => false,
                'show_ui'         => true,
                'show_in_menu'    => false,
                'capability_type' => 'post',
                'hierarchical'    => false,
                'rewrite'         => array( 'slug' => 'erp_inventory_product' ),
                'query_var'       => false,
                'supports'        => array( 'title', 'thumbnail' ),
                'capabilities'    => array(
                    'edit_post'          => $capability,
                    'read_post'          => $capability,
                    'delete_posts'       => $capability,
                    'edit_posts'         => $capability,
                    'edit_others_posts'  => $capability,
                    'publish_posts'      => $capability,
                    'read_private_posts' => $capability,
                    'create_posts'       => $capability,
                    'delete_post'        => $capability
                ),
                'labels'          => array(
                    'name'               => __( 'Product', 'erp-pro' ),
                    'singular_name'      => __( 'Product', 'erp-pro' ),
                    'menu_name'          => __( 'Product', 'erp-pro' ),
                    'add_new'            => __( 'Add Product', 'erp-pro' ),
                    'add_new_item'       => __( 'Add New Product', 'erp-pro' ),
                    'edit'               => __( 'Edit', 'erp-pro' ),
                    'edit_item'          => __( 'Edit Product', 'erp-pro' ),
                    'new_item'           => __( 'New Product', 'erp-pro' ),
                    'view'               => __( 'View Product', 'erp-pro' ),
                    'view_item'          => __( 'View Product', 'erp-pro' ),
                    'search_items'       => __( 'Search Product', 'erp-pro' ),
                    'not_found'          => __( 'No Product Found', 'erp-pro' ),
                    'not_found_in_trash' => __( 'No Product found in trash', 'erp-pro' ),
                    'parent'             => __( 'Parent Product', 'erp-pro' )
                )
            )
        );
    }

    /**
     * Register product taxonomy
     *
     * @since 0.1
     *
     * @return void
     */
    public function register_product_taxonomy() {
        $labels = array(
            'name'              => _x( 'Product category', 'erp-pro' ),
            'singular_name'     => _x( 'Product category', 'erp-pro' ),
            'search_items'      => __( 'Search category' ),
            'all_items'         => __( 'All category' ),
            'parent_item'       => __( 'Parent category' ),
            'parent_item_colon' => __( 'Parent category:' ),
            'edit_item'         => __( 'Edit category' ),
            'update_item'       => __( 'Update category' ),
            'add_new_item'      => __( 'Add New category' ),
            'new_item_name'     => __( 'New category Name' ),
            'menu_name'         => __( 'Product category' )
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menus'     => true,
            'show_admin_column' => false,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'product-category' )
        );

        register_taxonomy( 'product_category', $this->post_type, $args );
    }

    /**
     * initialize meta boxes for inventory product post type
     *
     * @return void
     */
    public function do_metaboxes_inventory() {
        add_meta_box( 'erp-hr-inventory-meta-box', __( 'Product Settings', 'erp-pro' ),
            array( $this, 'meta_boxes_cb' ), $this->post_type, 'advanced', 'high' );

        if ( $this->post_type == 'erp_inv_product' ) {
            if ( get_the_ID() != false ) {
                $get_purchase_tran_catpion = count( erp_inv_get_last_ten_transactions( get_the_ID() ) ) > 9 ? 'Last 10 transaction' : 'Recent transaction';
                add_meta_box( 'erp-hr-product-history-meta-box', $get_purchase_tran_catpion,
                    array( $this, 'meta_boxes_product_history_cb' ), $this->post_type, 'advanced', 'high' );
            }
        }

        add_meta_box( 'erp-hr-inventory-stock-meta-box', __( 'Stock available', 'erp-pro' ),
            array( $this, 'meta_boxes_product_stock_cb' ), $this->post_type, 'side', 'low' );
    }

    /**
     * inventory metabox callback function
     *
     * @return void
     */
    public function meta_boxes_cb( $post_id ) {
        global $post;
        $get_inventory_account = ( get_post_meta( $post->ID, '_inventory_asset_account', true ) == "" ? false : get_post_meta( $post->ID, '_inventory_asset_account', true ) );
        $get_sku               = ( get_post_meta( $post->ID, '_sku', true ) == "" ? '' : get_post_meta( $post->ID, '_sku', true ) );
        $get_purchase_account  = ( get_post_meta( $post->ID, '_purchase_account', true ) == "" ? false : get_post_meta( $post->ID, '_purchase_account', true ) );
        $get_sales_account     = ( get_post_meta( $post->ID, '_sales_account', true ) == "" ? false : get_post_meta( $post->ID, '_sales_account', true ) );
        $get_cost_price        = ( get_post_meta( $post->ID, '_cost_price', true ) == "" ? 0 : get_post_meta( $post->ID, '_cost_price', true ) );
        $get_sale_price        = ( get_post_meta( $post->ID, '_sale_price', true ) == "" ? 0 : get_post_meta( $post->ID, '_sale_price', true ) );
        $get_tax_on_purchase   = ( get_post_meta( $post->ID, '_tax_on_purchase', true ) == "" ? - 1 : get_post_meta( $post->ID, '_tax_on_purchase', true ) );
        $get_tax_on_sales      = ( get_post_meta( $post->ID, '_tax_on_sales', true ) == "" ? - 1 : get_post_meta( $post->ID, '_tax_on_sales', true ) );
        $get_purchase_desc     = ( get_post_meta( $post->ID, '_purchase_description', true ) == "" ? '' : get_post_meta( $post->ID, '_purchase_description', true ) );
        $get_sales_desc        = ( get_post_meta( $post->ID, '_sales_description', true ) == "" ? '' : get_post_meta( $post->ID, '_sales_description', true ) );
        $get_short_desc        = ( get_post_meta( $post->ID, '_short_description', true ) == "" ? '' : get_post_meta( $post->ID, '_short_description', true ) );

        ?>
        <table class="form-table erp-hr-inventory-meta-wrap-table" xmlns:v-on="http://www.w3.org/1999/xhtml">
        <tr>
            <td>
                <div class="block-box">
                    <label style="border-bottom: 1px solid #ddd"><?php _e( 'Inventory Item', 'erp-pro' ); ?></label>
                    <span class="product-setting-left-caption"><?php _e( 'Inventory asset account', 'erp-pro' ); ?></span><?php
                    $dropdown      = erp_ac_get_chart_dropdown( [
                        'exclude' => [ 2, 3, 4, 5 ]
                    ] );
                    $dropdown_html = erp_ac_render_account_dropdown_html( $dropdown, array(
                        'name'     => 'inventory_asset_account',
                        'selected' => $get_inventory_account,
                        'class'    => 'erp-select2 erp-ac-account-dropdown product-setting-right-things'
                    ) );
                    echo $dropdown_html;
                    ?>
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Item code', 'erp-pro' ); ?></span>
                    <input type="text" name="sku" value="<?php echo $get_sku; ?>">
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="block-box">
                    <label style="border-bottom: 1px solid #ddd"><?php _e( 'Purchase', 'erp-pro' ); ?></label>
                    <span class="product-setting-left-caption"><?php _e( 'Cost Price', 'erp-pro' ); ?></span>
                    <input class="product-setting-right-things" type="text" name="cost_price"
                           value="<?php echo $get_cost_price; ?>" maxlength="6"
                           onfocus=" if ( this.value == 0 ) { this.value = ''; } "
                           onblur=" if ( this.value == '' ) { this.value = 0; } ">
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Purchase account', 'erp-pro' ); ?></span>
                    <?php
                    $dropdown      = erp_ac_get_chart_dropdown( [
                        'exclude' => [ 1, 2, 4, 5 ]
                    ] );
                    $dropdown_html = erp_ac_render_account_dropdown_html( $dropdown, array(
                        'name'     => 'purchase_account',
                        'selected' => $get_purchase_account,
                        'class'    => 'erp-select2 erp-ac-account-dropdown product-setting-right-things'
                    ) );
                    echo $dropdown_html;
                    ?>
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Tax Rate', 'erp-pro' ); ?></span>
                    <?php $taxlabels = erp_inv_get_tax_list(); ?>
                    <select name="tax_on_purchase" class="product-setting-right-things">
                        <option value="-1" <?php if ( $get_tax_on_purchase == '-1' ) {
                            echo 'selected="selected"';
                        } ?>><?php _e( '- Select -', 'erp-pro' ); ?></option>
                        <?php foreach ( $taxlabels as $key => $value ) { ?>
                            <option value="<?php echo $value->tax_id; ?>" <?php if ( $get_tax_on_purchase == $value->tax_id ) {
                                echo 'selected="selected"';
                            } ?>><?php echo $value->tax_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Purchase Description', 'erp-pro' ); ?></span>
                    <textarea name="purchase_description"
                              class="purchase-sales-description"><?php echo $get_purchase_desc; ?></textarea>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="block-box">
                    <label style="border-bottom: 1px solid #ddd"><?php _e( 'Sales', 'erp-pro' ); ?></label>
                    <span class="product-setting-left-caption"><?php _e( 'Sale Price', 'erp-pro' ); ?></span>
                    <input class="product-setting-right-things" type="text" name="sale_price"
                           value="<?php echo $get_sale_price; ?>" maxlength="6"
                           onfocus=" if ( this.value == 0 ) { this.value = ''; } "
                           onblur=" if ( this.value == '' ) { this.value = 0; } ">
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Sales account', 'erp-pro' ); ?></span>
                    <?php
                    $dropdown      = erp_ac_get_chart_dropdown( [
                        'exclude' => [ 1, 2, 3, 5 ]
                    ] );
                    $dropdown_html = erp_ac_render_account_dropdown_html( $dropdown, array(
                        'name'     => 'sales_account',
                        'selected' => $get_sales_account,
                        'class'    => 'erp-select2 erp-ac-account-dropdown'
                    ) );
                    echo $dropdown_html;
                    ?>
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Tax Rate', 'erp-pro' ); ?></span>
                    <select name="tax_on_sales">
                        <option value="-1" <?php if ( $get_tax_on_sales == '-1' ) {
                            echo 'selected="selected"';
                        } ?>><?php _e( '- Select -', 'erp-pro' ); ?></option>
                        <?php foreach ( $taxlabels as $key => $value ) { ?>
                            <option value="<?php echo $value->tax_id; ?>" <?php if ( $get_tax_on_sales == $value->tax_id ) {
                                echo 'selected="selected"';
                            } ?>><?php echo $value->tax_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="block-box">
                    <span class="product-setting-left-caption"><?php _e( 'Sales Description', 'erp-pro' ); ?></span>
                    <textarea name="sales_description"
                              class="purchase-sales-description"><?php echo $get_sales_desc; ?></textarea>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="block-box">
                    <label style="border-bottom: 1px solid #ddd"><?php _e( 'Miscellaneous', 'erp-pro' ); ?></label>
                    <span class="product-setting-left-caption"><?php _e( 'Short Description', 'erp-pro' ); ?></span>
                    <textarea name="short_description"
                              class="purchase-sales-description"><?php echo $get_short_desc; ?></textarea>
                </div>
            </td>
        </tr>

        </table><?php wp_nonce_field( 'inventory_meta_action', 'inventory_meta_action_nonce' );
    }

    /**
     * product history metabox callback function
     *
     * @return void
     */
    public function meta_boxes_product_history_cb( $post_id ) {
        global $post;
        $get_purchase_tran = erp_inv_get_last_ten_transactions( $post_id->ID );
        ?>
        <table class="wp-list-table wp-list-table-ten-transactions widefat fixed striped posts"
               xmlns:v-on="http://www.w3.org/1999/xhtml">
        <thead>
        <tr>
            <th><?php _e( 'Date', 'erp-pro' ); ?></th>
            <th><?php _e( 'Type', 'erp-pro' ); ?></th>
            <th><?php _e( 'Reference', 'erp-pro' ); ?></th>
            <th><?php _e( 'Quantity', 'erp-pro' ); ?></th>
            <th><?php _e( 'Unit Price', 'erp-pro' ); ?></th>
            <th><?php _e( 'Total', 'erp-pro' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ( is_array( $get_purchase_tran ) && count( $get_purchase_tran ) > 0 ) : ?>
            <?php foreach ( $get_purchase_tran as $podata ) : ?>
                <tr>
                    <td>
                        <?php
                        $location = '#';
                        if ( $podata->type == 'bill' ) {
                            $location = add_query_arg( array(
                                'action' => 'view',
                                'id'     => $podata->tranid
                            ), admin_url( 'admin.php?page=erp-accounting&section=expense' ) );
                        } elseif ( $podata->type == 'invoice' ) {
                            $location = add_query_arg( array(
                                'action' => 'view',
                                'id'     => $podata->tranid
                            ), admin_url( 'admin.php?page=erp-accounting&section=sales' ) );
                        }
                        ?>
                        <a href="<?php echo $location; ?>"><?php echo $podata->podate; ?></a>
                    </td>
                    <td><?php echo $podata->type; ?></td>
                    <td><?php echo $podata->ref_no; ?></td>
                    <td><?php echo $podata->quantity; ?></td>
                    <td><?php echo $podata->unit_price; ?></td>
                    <td><?php echo $podata->total; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6"><h3 class="not-found"><?php _e( 'No transaction found', 'erp-pro' ); ?></h3></td>
            </tr>
        <?php endif; ?>
        </tbody>

        </table><?php
    }

    /**
     * product history metabox callback function
     *
     * @return void
     */
    public function meta_boxes_product_stock_cb( $post_id ) {
        ?>
        <div id="stock-detail-page">
            <span>
                <?php
                echo $product_stock = erp_inv_get_this_product_stock( $post_id->ID );
                ?>
            </span>
        </div>
        <?php
    }

    /**
     * Save inventory product post meta
     *
     * @since  0.1
     *
     * @param  integer $post_id
     * @param  object $post
     *
     * @return void
     */
    function save_inventory_product_meta( $post_id, $post ) {
        global $post, $wpdb;

        if ( ! isset( $_POST['inventory_meta_action_nonce'] ) ) {
            return $post_id;
        }

        if ( ! wp_verify_nonce( $_POST['inventory_meta_action_nonce'], 'inventory_meta_action' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        $post_type = get_post_type_object( $post->post_type );

        if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
            return $post_id;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return $post_id;
        }

        if ( $post->post_type != 'erp_inv_product' ) {
            return;
        }

        $inventory_asset_account = ( isset( $_POST['inventory_asset_account'] ) ) ? $_POST['inventory_asset_account'] : '';
        $sku                     = ( isset( $_POST['sku'] ) ) ? $_POST['sku'] : '';
        $purchase_account        = ( isset( $_POST['purchase_account'] ) ) ? $_POST['purchase_account'] : '';
        $sales_account           = ( isset( $_POST['sales_account'] ) ) ? $_POST['sales_account'] : '';
        $cost_price              = ( isset( $_POST['cost_price'] ) ) ? $_POST['cost_price'] : 0;
        $sale_price              = ( isset( $_POST['sale_price'] ) ) ? $_POST['sale_price'] : 0;
        $purchase_description    = ( isset( $_POST['purchase_description'] ) ) ? $_POST['purchase_description'] : '';
        $sales_description       = ( isset( $_POST['sales_description'] ) ) ? $_POST['sales_description'] : '';
        $short_description       = ( isset( $_POST['short_description'] ) ) ? $_POST['short_description'] : '';
        $tax_on_purchase         = $_POST['tax_on_purchase'];
        $tax_on_sales            = $_POST['tax_on_sales'];

        update_post_meta( $post_id, '_inventory_asset_account', $inventory_asset_account );
        update_post_meta( $post_id, '_purchase_account', $purchase_account );
        update_post_meta( $post_id, '_sales_account', $sales_account );
        update_post_meta( $post_id, '_tax_on_purchase', $tax_on_purchase );
        update_post_meta( $post_id, '_tax_on_sales', $tax_on_sales );
        update_post_meta( $post_id, '_purchase_description', sanitize_text_field( $purchase_description ) );
        update_post_meta( $post_id, '_sales_description', sanitize_text_field( $sales_description ) );
        update_post_meta( $post_id, '_short_description', sanitize_text_field( $short_description ) );

        /*validating sku then save sku*/
        $query   = new \WP_Query( array(
            'post_type'      => $this->post_type,
            'post__not_in'   => array( $post_id ),
            'posts_per_page' => - 1
        ) );
        $all_sku = [];
        while ( $query->have_posts() ) {
            $query->the_post();
            array_push( $all_sku, get_post_meta( get_the_ID(), '_sku', true ) );
        }

        if ( in_array( $sku, $all_sku ) && $sku != "" ) {
            $location = add_query_arg( array( 'invprod-msg' => '2' ), admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
            wp_safe_redirect( $location );
            exit;
        } else {
            update_post_meta( $post_id, '_sku', $sku );
        }

        /*cost price should be less than sale price*/
        if ( ! is_numeric( $cost_price ) || ! is_numeric( $sale_price ) ) {
            update_post_meta( $post_id, '_cost_price', 0 );
            update_post_meta( $post_id, '_sale_price', 0 );
            $location = add_query_arg( array( 'invprod-msg' => '3' ), admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
            wp_safe_redirect( $location );
            exit;
        } elseif ( $cost_price > $sale_price ) {
            update_post_meta( $post_id, '_cost_price', 0 );
            update_post_meta( $post_id, '_sale_price', 0 );
            $location = add_query_arg( array( 'invprod-msg' => '4' ), admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
            wp_safe_redirect( $location );
            exit;
        } else {
            update_post_meta( $post_id, '_cost_price', sanitize_text_field( $cost_price ) );
            update_post_meta( $post_id, '_sale_price', sanitize_text_field( $sale_price ) );
        }

    }

    /**
     * custom post product table head
     *
     * @return array
     */
    public function product_table_head( $defaults ) {
        $defaults['image']          = __( 'Image', 'erp-pro' );
        $defaults['jtitle']         = __( 'Name', 'erp-pro' );
        $defaults['sku']            = __( 'Item code', 'erp-pro' );
        $defaults['cost_price']     = __( 'Cost price', 'erp-pro' );
        $defaults['sale_price']     = __( 'Sale price', 'erp-pro' );
        $defaults['stock_quantity'] = __( 'Stock', 'erp-pro' );
        $defaults['created']        = __( 'Created On', 'erp-pro' );
        $defaults['modified']       = __( 'Modified', 'erp-pro' );

        unset( $defaults['title'] );
        unset( $defaults['date'] );

        return $defaults;
    }

    /*
     * custom post product table row value
     *
     * return void
     */
    public function product_table_data( $column_name, $post_id ) {

        if ( $column_name == 'jtitle' ) { ?>
            <?php if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, [ 40, 40 ] );
            } else { ?>
                <img src="<?php echo WPERP_INV_ASSETS . '/images/nimage.png'; ?>" alt="noimage" width="40">
            <?php } ?>
            <a class="jtitle"
               href="<?php echo admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ); ?>"><?php echo get_the_title( $post_id ); ?></a>
        <?php }

        if ( $column_name == 'sku' ) {
            echo get_post_meta( $post_id, '_sku', true ) ? get_post_meta( $post_id, '_sku', true ) : '';
        }

        if ( $column_name == 'cost_price' ) {
            echo get_post_meta( $post_id, '_cost_price', true ) ? erp_ac_get_price( get_post_meta( $post_id, '_cost_price', true ) ) : erp_ac_get_price( 0 );
        }

        if ( $column_name == 'sale_price' ) {
            echo get_post_meta( $post_id, '_sale_price', true ) ? erp_ac_get_price( get_post_meta( $post_id, '_sale_price', true ) ) : erp_ac_get_price( 0 );
        }

        if ( $column_name == 'stock_quantity' ) {
            $product_stock = erp_inv_get_this_product_stock( $post_id );
            if ( $product_stock < 0 ) {
                echo "<p style='color: #ff0000;'>" . $product_stock . "</p>";
            } else {
                echo $product_stock;
            }
        }

        if ( $column_name == 'created' ) {
            echo get_the_date();
        }

        if ( $column_name == 'modified' ) {
            echo the_modified_date();
        }

    }

}
