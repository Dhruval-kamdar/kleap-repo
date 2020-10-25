<?php
namespace WeDevs\ERP\HRM\Asset;

class Ajax_Handler {

    // Constructor for Ajax Handler Class
    public function __construct() {

        add_action( 'wp_ajax_erp-hr-assets-new', [ $this, 'asset_insert' ] );                        // New asset Entry
        add_action( 'wp_ajax_erp-hr-asset-get', [ $this, 'asset_get' ] );                             // Get asset info for edit
        add_action( 'wp_ajax_erp-hr-asset-delete', [ $this, 'asset_record_remove' ] );                // Asset delete
        add_action( 'wp_ajax_erp-hr-emp-asset-new', [ $this, 'emp_assets_insert' ] );                 // New asset from employee page
        add_action( 'wp_ajax_erp-hr-emp-delete-asset', [ $this, 'emp_asset_remove' ] );               // Asset remove from employee page
        add_action( 'wp_ajax_erp-hr-assets-new-category', [ $this, 'asset_category_add' ] );
        add_action( 'wp_ajax_erp-hr-assets-edit-category', [ $this, 'asset_category_edit' ] );
        add_action( 'wp_ajax_erp-hr-assets-is-category-used', [ $this, 'asset_is_category_used' ] );
        add_action( 'wp_ajax_erp-hr-assets-category-delete', [ $this, 'asset_category_delete' ] );

        // Allotment
        add_action( 'wp_ajax_erp-hr-get-item-by-category', [ $this, 'asset_get_item_by_category' ] );
        add_action( 'wp_ajax_erp-hr-get-item-by-group', [ $this, 'asset_get_item_by_group' ] );
        add_action( 'wp_ajax_erp-hr-allottment-get-item-by-group', [ $this, 'asset_allottment_get_item_by_group' ] );
        add_action( 'wp_ajax_erp-hr-allottment-new', [ $this, 'asset_allottment_insert' ] );
        add_action( 'wp_ajax_erp-assets-allottment-get', [ $this, 'asset_allotment_get' ] );
        add_action( 'wp_ajax_erp-hr-allott-remove', [ $this, 'asset_allottment_remove' ] );
        add_action( 'wp_ajax_erp-asset-item-return', [ $this, 'asset_item_return' ] );
        add_action( 'wp_ajax_erp-assets-emp-request-return', [ $this, 'asset_request_return' ] );
        add_action( 'wp_ajax_erp-assets-emp-reject-return-request', [ $this, 'reject_return_request' ] );

        //Asset Request
        add_action( 'wp_ajax_erp-hr-asset-request-new', [ $this, 'asset_request_insert' ] );
        add_action( 'wp_ajax_erp-assets-request-get', [ $this, 'asset_request_get' ] );
        add_action( 'wp_ajax_erp-asset-request-approve', [ $this, 'asset_request_approve' ] );
        add_action( 'wp_ajax_erp-asset-request-reject', [ $this, 'asset_request_reject' ] );
        add_action( 'wp_ajax_erp-assets-request-undo', [ $this, 'asset_request_undo' ] );
        add_action( 'wp_ajax_erp-assets-request-disapprove', [ $this, 'asset_request_disapprove' ] );
        add_action( 'wp_ajax_erp-assets-request-delete', [ $this, 'asset_request_delete' ] );

        // Single Item
        add_action( 'wp_ajax_erp-assets-single-item-delete', [ $this, 'asset_single_item_delete'] );
        add_action( 'wp_ajax_erp-assets-single-item-dissmiss', [ $this, 'asset_single_item_dissmiss'] );

        //Script reload
        add_action( 'wp_ajax_erp_asset_edit_category_reload', [ $this, 'reload_edit_category_template'] );
    }

    /**
     * Popup Script Reload
     *
     * @return json
     */
    public function reload_edit_category_template() {
        ob_start();
        include WPERP_ASSET_JS_TMPL . '/category-edit.php';
        wp_send_json_success( [ 'content' => ob_get_clean() ] );
    }

    /**
     * Assets Insert Function
     *
     * @since 1.0
     *
     * @return bool
     */
    public function asset_insert() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-hr-asset-new' ) ) {
            die( 'You are not allowed!' );
        }

        if ( '-1' == $_REQUEST['category_id'] ) {
            die( __( 'You must select a category', 'erp-pro' ) );
        }

        global $wpdb;

        $category_id   = isset( $_REQUEST['category_id'] ) ? intval( $_REQUEST['category_id'] ) : '';
        $item_group    = isset( $_REQUEST['item_group'] ) ? sanitize_text_field( $_REQUEST['item_group'] ) : '';
        $asset_type    = isset( $_REQUEST['asset_type'] ) ? sanitize_text_field( $_REQUEST['asset_type'] ) : '';
        $items         = isset( $_REQUEST['items'] ) ? strip_tags_deep( $_REQUEST['items'] ) : '';
        $parent_row_id = isset( $_REQUEST['parent_row_id'] ) ? intval( $_REQUEST['parent_row_id'] ) : '';
        $data          = [];
        $row_id        = 0;

        foreach ( $items as $item ) {

            $data[] = [
                'category_id'   => $category_id,
                'item_group'    => $item_group,
                'asset_type'    => $asset_type,
                'item_code'     => isset( $item['item_code'] ) ? $item['item_code'] : '',
                'model_no'      => isset( $item['model_no'] ) ? $item['model_no'] : '',
                'manufacturer'  => isset( $item['manufacturer'] ) ? $item['manufacturer'] : '',
                'price'         => isset( $item['price'] ) ? $item['price'] : '',
                'date_reg'      => current_time( 'Y-m-d' ),
                'date_expiry'   => isset( $item['date_exp'] ) ? $item['date_exp'] : '',
                'date_warranty' => isset( $item['date_warr'] ) ? $item['date_warr'] : '',
                'allottable'    => isset( $item['allottable'] ) ? $item['allottable'] : '',
                'item_serial'   => isset( $item['item_serial'] ) ? $item['item_serial'] : '',
                'item_desc'     => isset( $item['item_desc'] ) ? $item['item_desc'] : '',
                'status'        => 'stock',
                'row_id'        => isset( $item['id'] ) ? $item['id'] : 0
            ];

        }

        if ( 'single' == $asset_type ) {

            $data[0]['parent'] = 0;

            if ( $data[0]['row_id'] ) {

                $row_id = $data[0]['row_id'];

                unset( $data[0]['row_id'] );
                unset( $data[0]['date_reg'] );

                $wpdb->update( $wpdb->prefix . 'erp_hr_assets', $data[0], [ 'ID' => $row_id ] );
            } else {

                unset( $data[0]['row_id'] );

                $wpdb->insert( $wpdb->prefix . 'erp_hr_assets', $data[0] );

                do_action( 'erp_register_new_asset', [
                    'asset_info' => [
                        'model_no'   => $data[0]['model_no'],
                        'item_group' => $data[0]['item_group'],
                        'item_code'  => $data[0]['item_code']
                    ],
                    'added_by' => get_current_user_id()
                ] );
            }
        }

        if ( 'variable' == $asset_type ) {

            $parent = [
                'category_id' => $category_id,
                'item_group'  => $item_group,
                'asset_type'  => $asset_type
            ];

            if ( $parent_row_id ) {

                foreach ( $data as $single_data ) {

                    if ( $single_data['row_id'] ) {

                        $row_id = $single_data['row_id'];

                        unset( $single_data['row_id'] );
                        unset( $single_data['date_reg'] );

                        $wpdb->update( $wpdb->prefix . 'erp_hr_assets', $single_data, [ 'ID' => $row_id ] );
                    } else {

                        $single_data['parent'] = $parent_row_id;

                        unset( $single_data['row_id'] );
                        unset( $single_data['date_reg'] );

                        $wpdb->insert( $wpdb->prefix . 'erp_hr_assets', $single_data );

                        do_action( 'erp_register_new_asset', [
                            'asset_info' => [
                                'model_no'   => $single_data['model_no'],
                                'item_group' => $single_data['item_group'],
                                'item_code'  => $single_data['item_code']
                            ],
                            'added_by' => get_current_user_id()
                        ] );

                    }

                }


            // Insert Items
            } else {

                $parent['date_reg'] = current_time( 'Y-m-d' );

                $wpdb->insert( $wpdb->prefix . 'erp_hr_assets', $parent );

                if ( $insert_id = intval( $wpdb->insert_id ) ) {

                    foreach ( $data as $single ) {

                        unset( $single['row_id'] );

                        $single['parent'] = $insert_id;

                        $wpdb->insert( $wpdb->prefix . 'erp_hr_assets', $single );
                    }
                }
            }
        }

        wp_send_json_success();
    }

    /**
     * Get Assets for all employee
     *
     * @since 1.0
     *
     * @return array
     */
    public function asset_get() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( 'You are not allowed!' );
        }

        global $wpdb;

        $id     = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

        $sql_parent = "SELECT *
                        FROM {$wpdb->prefix}erp_hr_assets
                        WHERE id = %d
                        OR parent = %d";
        $parent     = $wpdb->get_results( $wpdb->prepare( $sql_parent, $id, $id ), ARRAY_A );

        if ( !is_wp_error( $parent ) ) {

            foreach ( $parent as &$single ) {
                $single['date_expiry'] = '0000-00-00' == $single['date_expiry'] ? '' : $single['date_expiry'];
                $single['date_warranty'] = '0000-00-00' == $single['date_warranty'] ? '' : $single['date_warranty'];
            }

            wp_send_json_success( $parent );
        } else {
            wp_send_json_error();
        }

    }

    /**
     * Remove Asset for a single employee
     *
     * @since 1.0
     *
     * @return bool
     */
    public function asset_record_remove() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( 'You are not allowed!' );
        }

        $id     = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;
        $result = erp_hr_asset_remove( $id );

        wp_send_json_success( $result );
    }

    /**
     * Add New Category
     *
     * @since 1.0
     *
     * @return bool
     */
    public function asset_category_add() {

         if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'erp-hr-asset-new-category' ) ) {
             die( 'You are not allowed!' );
         }

        $cat_name = isset( $_REQUEST['cat_name'] ) ? $_REQUEST['cat_name'] : '';
        $result   = wp_erp_hr_asset_category_insert( $cat_name );

        wp_send_json_success( $result );

    }

    /**
     * Asset Category Edit
     *
     * @since 1.0
     *
     * @return bool
     */
    public function asset_category_edit() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'erp-hr-asset-edit-category' ) ) {
            die( 'You are not allowed!' );
        }

        $cat_name = isset( $_REQUEST['cat_name'] ) ? $_REQUEST['cat_name'] : '';
        $row_id   = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : '';

        if ( $row_id ) {
            $result = wp_erp_hr_asset_category_edit( $row_id, $cat_name );
        } else {
            $result = wp_erp_hr_asset_category_insert( $cat_name );
        }

        wp_send_json_success( $result );
    }

    /**
     * Checks if a category already in use
     *
     * @return array
     */
    public function asset_is_category_used() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( 'You are not allowed!' );
        }

        global $wpdb;

        $id  = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
        $ids = $wpdb->get_col( "SELECT category_id FROM {$wpdb->prefix}erp_hr_assets" );

        if ( in_array( $id, $ids ) ) {
            wp_send_json_success( [ 'exist' => 1 ] );
        }

        wp_send_json_success( [ 'exist' => 0 ] );
    }

    /**
     * Delete Category
     *
     * @return bool
     */
    public function asset_category_delete() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( 'You are not allowed!' );
        }

        global $wpdb;

        $id     = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
        $result = $wpdb->delete( $wpdb->prefix . erp_hr_assets_category, [ ID => $id ], [ '%d' ] );

        if ( $result ) {
            wp_send_json_success( [ 'deleted' => 1 ] );
        }

        wp_send_json_error();
    }


    /**
     * Get Items by Category
     *
     * @return array
     */
    public function asset_get_item_by_category() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $cat_id = isset( $_REQUEST['cat_id'] ) ? $_REQUEST['cat_id'] : '';
        $result = $wpdb->get_results( "SELECT id, item_group FROM {$wpdb->prefix}erp_hr_assets WHERE category_id = $cat_id AND parent = 0", ARRAY_A );

        wp_send_json_success($result);
    }

    /**
     * Get Items by Item Group
     *
     * @return array
     */
    public function asset_get_item_by_group() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $group_id     = isset( $_REQUEST['item_group_id'] ) ? $_REQUEST['item_group_id'] : '';
        $result_child = $wpdb->get_results( "SELECT id, item_code, model_no FROM {$wpdb->prefix}erp_hr_assets WHERE parent = $group_id AND allottable = 'on' AND status='stock'", ARRAY_A );

        if ( $result_child  ) {
            wp_send_json_success( $result_child );
        } else {
            $result_parent = $wpdb->get_results( "SELECT id, item_code, model_no FROM {$wpdb->prefix}erp_hr_assets WHERE id = $group_id AND allottable = 'on' AND status='stock'", ARRAY_A );
            wp_send_json_success( $result_parent );
        }
    }

    /**
     * Get Items by Item Group
     *
     * @return array
     */
    public function asset_allottment_get_item_by_group() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $group_id     = isset( $_REQUEST['item_group_id'] ) ? $_REQUEST['item_group_id'] : '';
        $result_child = $wpdb->get_results( "SELECT id, item_code, model_no FROM {$wpdb->prefix}erp_hr_assets WHERE parent = $group_id AND allottable = 'on'", ARRAY_A );

        if ( $result_child  ) {
            wp_send_json_success( $result_child );
        } else {
            $result_parent = $wpdb->get_results( "SELECT id, item_code, model_no FROM {$wpdb->prefix}erp_hr_assets WHERE id = $group_id AND allottable = 'on'", ARRAY_A );
            wp_send_json_success( $result_parent );
        }
    }

    /**
     * Create Allotment Record
     *
     * @return bool
     */
    public function asset_allottment_insert() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-hr-allot-new' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        if ( '-1' == $_REQUEST['category_id'] ) {
            die( __( 'You must select a category', 'erp-pro' ) );
        }

        if ( '-1' == $_REQUEST['item_group'] ) {
            die( __( 'You must select a group', 'erp-pro' ) );
        }

        if ( '-1' == $_REQUEST['item'] ) {
            die( __( 'You must select an item name', 'erp-pro' ) );
        }

        if ( 0 == $_REQUEST['allotted_to'] ) {
            die( __( 'You must select an employee', 'erp-pro' ) );
        }

        global $wpdb;

        $item_cat      = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : '';
        $item_group    = isset( $_REQUEST['item_group'] ) ? $_REQUEST['item_group'] : '';
        $item          = isset( $_REQUEST['item'] ) ? $_REQUEST['item'] : '';
        $allotted_to   = isset( $_REQUEST['allotted_to'] ) ? $_REQUEST['allotted_to'] : '';
        $date_given    = isset( $_REQUEST['given_date'] ) ? $_REQUEST['given_date'] : '';
        $is_returnable = isset( $_REQUEST['is_returnable'] ) ? $_REQUEST['is_returnable'] : '';
        $date_return   = isset( $_REQUEST['return_date'] ) ? $_REQUEST['return_date'] : '';
        $row_id        = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : '';

        $data = [
            'category_id'          => $item_cat,
            'item_group'           => $item_group,
            'item_id'              => $item,
            'allotted_to'          => $allotted_to,
            'is_returnable'        => 'on' == $is_returnable ? 'yes' : 'no',
            'date_given'           => $date_given,
            'status'               => 'allotted'
        ];

        if ( 'on' == $is_returnable ) {
            $data['date_return_proposed'] = $date_return;
        } else {
            $data['date_return_proposed'] = '';
        }

        if ( $row_id ) {
            $old_item_id = $wpdb->get_var( $wpdb->prepare( "SELECT item_id FROM {$wpdb->prefix}erp_hr_assets_history WHERE id = %d", $row_id ) );

            if ( $old_item_id != $data['item_id'] ) {

                $new_item_status = $wpdb->get_var( $wpdb->prepare( "SELECT status FROM {$wpdb->prefix}erp_hr_assets WHERE id = %d", $data['item_id'] ) );

                if ( 'stock' != $new_item_status ) {
                    die( __( 'This item is allotted or dissmised.', 'erp-pro') );
                }
                $wpdb->update( $wpdb->prefix . 'erp_hr_assets', ['status' => 'allotted'], [ID => $data['item_id']]);
                $wpdb->update( $wpdb->prefix . 'erp_hr_assets', ['status' => 'stock'], [ID => $old_item_id] );
            }

            $result = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_history', $data, [ 'ID' => $row_id ] );

            if ( false !== $result ) {
                wp_send_json_success();
            }

        } else {

            $result = $wpdb->insert( $wpdb->prefix . 'erp_hr_assets_history', $data );

            if ( $result ) {
                $update = $wpdb->update( $wpdb->prefix . 'erp_hr_assets', ['status' => 'allotted'], [ID => $item] );

                do_action( 'erp_assets_alloted', $allotted_to );

                if ( false !== $update ) {
                    wp_send_json_success();
                }
            }
        }

        return false;
    }

    /**
     * Handles Asset Reqest Creation
     *
     * @return bool
     */
    public function asset_request_insert() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset-request-new' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id       = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : '';
        $item_group   = isset( $_REQUEST['item_group'] ) ? $_REQUEST['item_group'] : '';
        $not_in_list  = isset( $_REQUEST['not_in_list'] ) ? $_REQUEST['not_in_list'] : '';
        $request_desc = isset( $_REQUEST['request_desc'] ) ? $_REQUEST['request_desc'] : '';

        $request_data = [
            'user_id'        => get_current_user_id(),
            'item_group'     => 'on' == $not_in_list ? '' : $item_group,
            'request_desc'   => 'on' == $not_in_list ? $request_desc : '',
            'not_in_list'    => $not_in_list,
            'date_requested' => current_time( 'Y-m-d' ),
            'status'         => 'pending'
        ];

        if ( $row_id ) {
            unset( $request_data['user_id'] );
            unset( $request_data['date_requested'] );
            $wpdb->update( $wpdb->prefix . 'erp_hr_assets_request', $request_data, ['ID' => $row_id]);

            wp_send_json_success();

        } else {
            $wpdb->insert( $wpdb->prefix . 'erp_hr_assets_request', $request_data );
        }

        if ( $wpdb->insert_id ) {

            $sql = "SELECT req.request_desc, req.date_requested, u.user_email, u.display_name, ass.item_group, ass.item_code, ass.model_no
                    FROM {$wpdb->prefix}erp_hr_assets_request AS req
                    LEFT JOIN $wpdb->users AS u
                    ON req.user_id = u.id
                    LEFT JOIN {$wpdb->prefix}erp_hr_assets AS ass
                    ON req.item_id = ass.id
                    WHERE req.id = %d";

            $data = $wpdb->get_row( $wpdb->prepare( $sql, $wpdb->insert_id ) );

            $emailer = wperp()->emailer->get_email( 'New_Asset_Request' );

            if ( is_a( $emailer, '\WeDevs\ERP\Email') ) {
                $emailer->trigger( $data );

                wp_send_json_success();
            }
        }
    }

    /**
     * Handles asset return
     *
     * @return bool
     */
    public function asset_item_return() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'erp_asset_return' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $allott_id     = isset( $_REQUEST['allott_id'] ) ? $_REQUEST['allott_id'] : '';
        $item_id       = isset( $_REQUEST['item_id'] ) ? $_REQUEST['item_id'] : '';
        $return_date   = isset( $_REQUEST['date_return'] ) ? $_REQUEST['date_return'] : current_time( 'Y-m-d' );
        $return_note   = isset( $_REQUEST['return_note'] ) ? $_REQUEST['return_note'] : '';
        $is_dissmissed = isset( $_REQUEST['is_dissmissed'] ) && 'on' == $_REQUEST['is_dissmissed'] ? 'on' : '';

        if ( 'on' == $is_dissmissed ) {
            $damaged     = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_history', [ 'date_return_real' => $return_date, 'return_note' => $return_note, 'status' => 'dissmissed' ], [ 'ID' => $allott_id ] );
            $dissmissed  = $wpdb->update( $wpdb->prefix . 'erp_hr_assets', [ 'status' => 'dissmissed', 'allottable' => '' ], [ 'ID' => $item_id ] );

            if ( false !== $damaged && false !== $dissmissed ) {
                wp_send_json_success();
            }

        } else {
            $returned = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_history', [ 'date_return_real' => $return_date, 'return_note' => $return_note, 'status' => 'returned' ], [ 'ID' => $allott_id ] );
            $stocked  = $wpdb->update( $wpdb->prefix . 'erp_hr_assets', [ 'status' => 'stock' ], [ 'ID' => $item_id ] );

                if ( false !== $returned && false !== $stocked ) {
                    wp_send_json_success();
                }
        }

        wp_send_json_error();
    }

    /**
     * Handles request for an item return from Employee
     *
     * @return bool
     */
    public function asset_request_return() {

        global $wpdb;

        $allot_id     = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;
        $request_date = current_time( 'Y-m-d' );

        if ( $allot_id ) {
            $requested_return = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_history', [ 'date_request_return' => $request_date, 'status' => 'requested_return' ], [ 'ID' => $allot_id ] );

            if ( false !== $requested_return ) {
                wp_send_json_success();
            }
        }
    }

    /**
     * Get Allottment Data
     *
     * @return object
     */
    public function asset_allotment_get() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

        if ( $row_id ) {
            $result = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}erp_hr_assets_history WHERE id = $row_id" );

            if ( !is_wp_error( $result ) ) {

                $result->date_given = '0000-00-00' == $result->date_given ? '' : $result->date_given;
                $result->date_return_proposed = '0000-00-00' == $result->date_return_proposed ? '' : $result->date_return_proposed;
            }

            wp_send_json_success( $result );
        }

        wp_send_json_error();
    }

    /**
     * Handles approvement of asset request
     *
     * @return bool
     */
    public function asset_request_approve() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'erp-asset-request-approve' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id               = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : 0;
        $category_id          = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : 0;
        $item_id              = isset( $_REQUEST['item_id'] ) ? $_REQUEST['item_id'] : 0;
        $item                 = isset( $_REQUEST['item'] ) ? $_REQUEST['item'] : 0;
        $item_group           = isset( $_REQUEST['item_group'] ) ? $_REQUEST['item_group'] : 0;
        $reply_msg            = isset( $_REQUEST['reply_msg'] ) ? $_REQUEST['reply_msg'] : '';
        $is_returnable        = isset( $_REQUEST['is_returnable'] ) ? $_REQUEST['is_returnable'] : '';
        $date_given           = isset( $_REQUEST['given_date'] ) ? $_REQUEST['given_date'] : '';
        $date_return_proposed = isset( $_REQUEST['return_date'] ) ? $_REQUEST['return_date'] : '';
        $date_replied         = current_time( 'Y-m-d' );

        if ( $row_id ) {

            $request_sql     = "SELECT user_id, item_group, item_id FROM {$wpdb->prefix}erp_hr_assets_request WHERE id = %d";
            $request_details = $wpdb->get_row( $wpdb->prepare( $request_sql, $row_id ) );

            if ( $item_id ) {

                if ( $request_details->item_id ) {
                    $item_sql     = "SELECT category_id FROM {$wpdb->prefix}erp_hr_assets WHERE id = %d";
                    $item_details = $wpdb->get_row( $wpdb->prepare( $item_sql, $request_details->item_id ) );
                }

                $allott_data = [
                    'category_id'          => $item_details->category_id ? $item_details->category_id : 0,
                    'item_group'           => $request_details->item_group ? $request_details->item_group : 0,
                    'item_id'              => $request_details->item_id ? $request_details->item_id : 0,
                    'allotted_to'          => $request_details->user_id ? $request_details->user_id : 0,
                    'is_returnable'        => $is_returnable,
                    'date_given'           => $date_given,
                    'date_return_proposed' => 'on' == $is_returnable ? $date_return_proposed : '',
                    'status'               => 'allotted'
                ];

            } else {

                if ( '-1' == $_REQUEST['category_id'] ) {
                    die( __( 'You must select a category', 'erp-pro' ) );
                }

                if ( '-1' == $_REQUEST['item_group'] ) {
                    die( __( 'You must select a group', 'erp-pro' ) );
                }

                if ( '-1' == $_REQUEST['item'] ) {
                    die( __( 'You must select an item name', 'erp-pro' ) );
                }

                $allott_data = [
                    'category_id'          => $category_id,
                    'item_group'           => $item_group,
                    'item_id'              => $item,
                    'allotted_to'          => $request_details->user_id ? $request_details->user_id : 0,
                    'is_returnable'        => $is_returnable,
                    'date_given'           => $date_given,
                    'date_return_proposed' => 'on' == $is_returnable ? $date_return_proposed : '',
                    'status'               => 'allotted'
                ];
            }

            if ( $allott_data['item_id'] && $allott_data['allotted_to'] ) {
                $new_allot = $wpdb->insert( $wpdb->prefix . 'erp_hr_assets_history', $allott_data );
            }

            if ( !is_wp_error( $new_allot ) ) {
                $allott_id = $wpdb->insert_id;

                $request_update_data = [
                    'allott_id'     => $allott_id,
                    'date_replied'  => $date_replied,
                    'reply_msg'     => $reply_msg,
                    'item_group'    => $allott_data['item_group'],
                    'given_item_id' => $allott_data['item_id'],
                    'status'        => 'approved'
                ];

                $allott_update = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_request', $request_update_data, ['ID' => $row_id] );
            }

            if ( !is_wp_error( $allott_update ) ) {
                $item_to_update = $request_details->item_id && '-1' == $category_id ? $request_details->item_id : $item;
                $asset_update   = $wpdb->update( $wpdb->prefix . 'erp_hr_assets', ['status' => 'allotted'], ['ID' => $item_to_update] );
            }

            if ( !is_wp_error( $asset_update ) ) {

                $sql = "SELECT req.request_desc, req.date_requested, u.user_email, u.display_name, ass.item_group, ass.item_code, ass.model_no
                    FROM {$wpdb->prefix}erp_hr_assets_request AS req
                    LEFT JOIN $wpdb->users AS u
                    ON req.user_id = u.id
                    LEFT JOIN {$wpdb->prefix}erp_hr_assets AS ass
                    ON req.item_id = ass.id
                    WHERE req.id = %d";

                $data = $wpdb->get_row( $wpdb->prepare( $sql, $row_id ) );

                $emailer = wperp()->emailer->get_email( 'New_Asset_Approve' );

                if ( is_a( $emailer, '\WeDevs\ERP\Email') ) {
                    $emailer->trigger( $data );

                    wp_send_json_success();
                }
            }

            wp_send_json_error();
        }
    }

    /**
     * Delete Single Item
     * @return bool
     */
    public function asset_single_item_delete() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $id  = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;

        if ( $id ) {

            $wpdb->delete( $wpdb->prefix . 'erp_hr_assets', [ 'ID' => $id ] );
            $wpdb->delete( $wpdb->prefix . 'erp_hr_assets_history', [ 'item_id' => $id ] );
            $wpdb->delete( $wpdb->prefix . 'erp_hr_assets_request', [ 'item_id' => $id ] );

            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /**
     * Dissmiss Single Item
     * @return bool
     */
    public function asset_single_item_dissmiss() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;

        $data = [
            'status'          => 'dissmissed',
            'date_dissmissed' => current_time( 'Y-m-d' )
        ];

        if ( $id ) {
            $wpdb->update( $wpdb->prefix . 'erp_hr_assets', $data, [ 'ID' => $id ] );
        }
        wp_send_json_success( $id );
    }

    /**
     * Allot Asset from Employee tab
     *
     * @return bool
     */
    public function emp_assets_insert() {
        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'erp-hr-emp-asset-new' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        if ( '-1' == $_REQUEST['category_id'] ) {
            die( __( 'You must select a category', 'erp-pro' ) );
        }

        if ( '-1' == $_REQUEST['item_group'] ) {
            die( __( 'You must select a group', 'erp-pro' ) );
        }

        if ( '-1' == $_REQUEST['item'] ) {
            die( __( 'You must select an item name', 'erp-pro' ) );
        }

        if ( 0 == $_REQUEST['emp_id'] ) {
            die( __( 'You must select an employee', 'erp-pro' ) );
        }

        global $wpdb;

        $item_cat      = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : '';
        $item_group    = isset( $_REQUEST['item_group'] ) ? $_REQUEST['item_group'] : '';
        $item          = isset( $_REQUEST['item'] ) ? $_REQUEST['item'] : '';
        $allotted_to   = isset( $_REQUEST['emp_id'] ) ? $_REQUEST['emp_id'] : '';
        $date_given    = isset( $_REQUEST['given_date'] ) ? $_REQUEST['given_date'] : '';
        $is_returnable = isset( $_REQUEST['is_returnable'] ) ? $_REQUEST['is_returnable'] : '';
        $date_return   = isset( $_REQUEST['return_date'] ) ? $_REQUEST['return_date'] : '';
        $row_id        = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : '';

        $data = [
            'category_id'          => $item_cat,
            'item_group'           => $item_group,
            'item_id'              => $item,
            'allotted_to'          => $allotted_to,
            'is_returnable'        => 'on' == $is_returnable ? 'yes' : 'no',
            'date_given'           => $date_given,
            'status'               => 'allotted'
        ];

        if ( 'on' == $is_returnable ) {
            $data['date_return_proposed'] = $date_return;
        } else {
            $data['date_return_proposed'] = '';
        }

        if ( $row_id ) {

            $result = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_history', $data, [ 'ID' => $row_id ]);

            if ( false !== $result ) {
                wp_send_json_success();
            }

        } else {

            $result = $wpdb->insert( $wpdb->prefix . 'erp_hr_assets_history', $data );

            if ( $result ) {
                $update = $wpdb->update( $wpdb->prefix . 'erp_hr_assets', ['status' => 'allotted'], [ 'ID' => $item ]);

                if ( false !== $update ) {
                    wp_send_json_success();
                }
            }
        }

        return false;
    }

    /**
     * Handles rejection of asset request
     *
     * @return bool
     */
    public function asset_request_reject() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'erp-asset-request-reject' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        $row_id        = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : 0;
        $reject_reason = isset( $_REQUEST['reject_reason'] ) ? $_REQUEST['reject_reason'] : '';

        if ( !$row_id ) {
            die( __( 'There is an error', 'erp-pro' ) );
        }

        if ( $row_id ) {
            erp_asset_request_reject( $row_id, $reject_reason );
            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /**
     * Remove Allottment
     *
     * @return bool
     */
    public function asset_allottment_remove() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id  = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

        if ( $row_id ) {
            $return  = erp_hr_asset_allottment_remove( $row_id );
        }

        if ( $return ) {
            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /**
     * Request Undo
     *
     * @return bool
     */
    public function asset_request_undo() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id  = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

        $undo_data = [
            'status'       => 'pending',
            'date_replied' => '',
            'reply_msg'    => ''
        ];

        if ( $row_id ) {
            $undone = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_request', $undo_data, [ 'ID' => $row_id ] );
        }

        if ( $undone ) {
            wp_send_json_success();
        } else {
           wp_send_json_error();
        }
    }

    /**
     * Request Disapprove
     *
     * @return bool
     */
    public function asset_request_disapprove() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id  = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

        if ( $row_id ) {
            $request_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}erp_hr_assets_request WHERE id = %d", $row_id ) );
        }

        if ( !is_wp_error( $request_details ) ) {
            $latest_allott = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}erp_hr_assets_history WHERE item_id = %d ORDER BY id DESC", $request_details->given_item_id ) );
        }

        if ( !is_wp_error( $latest_allott ) ) {
            if ( $latest_allott->id == $request_details->allott_id ) {
                $history_deleted = $wpdb->delete( $wpdb->prefix . 'erp_hr_assets_history', [ 'ID' => $request_details->allott_id ]);
                $assets_updated  = $wpdb->update( $wpdb->prefix . 'erp_hr_assets', [ 'status' => 'stock' ], [ 'id' => $request_details->given_item_id, 'status' => 'allotted' ] );
                $request_updated = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_request', [
                    'status'        => 'pending',
                    'date_replied'  => '',
                    'reply_msg'     => '',
                    'item_group'    => '',
                    'given_item_id' => '',
                    'allott_id'     => '',
                ], [ 'ID' => $row_id ] );
            }
        }

        if ( !is_wp_error( $history_deleted ) && !is_wp_error( $assets_updated ) && !is_wp_error( $request_updated ) ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }

    }

    /**
     * Handles Asset Request Delete
     *
     * @return bool
     */
    public function asset_request_delete() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id  = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

        $deleted = $wpdb->delete( $wpdb->prefix . 'erp_hr_assets_request', [ 'ID' => $row_id ] );

        if ( !is_wp_error( $deleted ) ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }

    }

    /**
     * Get Request Data
     *
     * @return object
     */
    public function asset_request_get() {

        if ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-erp-asset' ) ) {
            die( __( 'You are not allowed!', 'erp-pro' ) );
        }

        global $wpdb;

        $row_id = isset( $_REQUEST['row_id'] ) ? $_REQUEST['row_id'] : '';

        if ( $row_id ) {

            $sql = "SELECT req.*, ass.category_id
                    FROM {$wpdb->prefix}erp_hr_assets_request AS req
                    LEFT JOIN {$wpdb->prefix}erp_hr_assets AS ass
                    ON req.item_id = ass.id
                    WHERE req.id = %d";
            $result = $wpdb->get_row( $wpdb->prepare( $sql, $row_id ) );
        }

        if ( !is_wp_error( $result ) ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error();
        }
    }

    /**
     * Handles reject for return request
     *
     * @since 1.0
     * @return bool
     */
    public function reject_return_request() {
        global $wpdb;

        $allot_id     = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;

        if ( $allot_id ) {
            $reject_request = $wpdb->update( $wpdb->prefix . 'erp_hr_assets_history', [ 'date_request_return' => '', 'status' => 'allotted' ], [ 'ID' => $allot_id ] );

            if ( false !== $reject_request ) {
                wp_send_json_success();
            }
        }
    }
}
