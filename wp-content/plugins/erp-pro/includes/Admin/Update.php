<?php

namespace weDevs\ERP_PRO\ADMIN;

// don't call the file directly
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * ERP Pro Update class
 *
 * Performas license validation and update checking
 *
 * @package License
 */
class Update {

    use \WeDevs\ERP_PRO\Traits\Singleton;

    /**
     * The license product ID
     *
     * @var string
     */
    private $product_id = 'erp-pro';

    /**
     * The license plan ID
     *
     * @var string
     */
    private $plan_id = 'erp-pro';

    /**
     * Monthly subscription product id
     *
     * @var int
     */
    private $monthly_plan_id = 80501;

    /**
     * Yearly Subscription product idd
     *
     * @var int
     */
    private $yearly_plan_id = 80505;

    /**
     * Base url for api call
     *
     * @var string
     */
    private $base_url = 'https://wperp.com/';

    const option       = 'erp_pro_license';
    const slug         = 'erp-pro';

    private function __construct() {

        // bail out if it's a local server
        if ( $this->is_local_server() ) {
            //return;
        }

        // local dev base url
        if ( defined( 'ERP_PRO_BASE_URL' ) ) {
            $this->base_url = ERP_PRO_BASE_URL;
        }

        // local dev monthly plan id
        if ( defined( 'ERP_PRO_MONTHLY_PLAN') ) {
            $this->monthly_plan_id = ERP_PRO_MONTHLY_PLAN;
        }

        // local dev yearly plan id
        if ( defined( 'ERP_PRO_YEARLY_PLAN' ) ) {
            $this->yearly_plan_id = ERP_PRO_YEARLY_PLAN;
        }

        add_action( 'admin_menu', array($this, 'admin_menu'), 9999 );

        if ( is_multisite() ) {
            if ( is_main_site() ) {
                add_action( 'admin_notices', array($this, 'license_enter_notice'), 1 );
                add_action( 'admin_notices', array($this, 'license_check_notice'), 2 );
                add_action( 'admin_notices', array($this, 'license_upgrade_notice'), 9999 );
            }
        } else {
            add_action( 'admin_notices', array($this, 'license_enter_notice'), 1 );
            add_action( 'admin_notices', array($this, 'license_check_notice'), 2 );
            add_action( 'admin_notices', array($this, 'license_upgrade_notice'), 9999 );
        }

        add_filter( 'pre_set_site_transient_update_plugins', array($this, 'check_update') );
        add_filter( 'pre_set_transient_update_plugins', array($this, 'check_update') );

        add_action( 'in_plugin_update_message-' . plugin_basename( ERP_PRO_FILE ), array( $this, 'plugin_update_message' ) );
        add_filter( 'plugins_api', array($this, 'plugins_api_filter'), 10, 3 );
    }

    /**
     * Check if the current server is localhost
     *
     * @return boolean
     */
    private function is_local_server() {
        // we are from cli
        if ( ! isset( $_SERVER['HTTP_HOST'] ) ) {
            return;
        }

        if ( $_SERVER['HTTP_HOST'] === 'localhost'
            || substr( $_SERVER['REMOTE_ADDR'], 0, 3 ) === '10.'
            || substr( $_SERVER['REMOTE_ADDR'], 0, 7 ) === '192.168' ) {

            return true;
        }

        $live_sites = [
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
        ];

        foreach ( $live_sites as $ip ) {
            if ( ! empty( $_SERVER[$ip] ) ) {
                return false;
            }
        }

        if ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) {
            return true;
        }

        $fragments = explode( '.',  site_url() );

        if ( in_array( end( $fragments ), array( 'dev', 'local', 'localhost', 'test' ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * This method will return base url of the erp pro site.
     *
     * @since 0.0.1
     * @return string
     */
    public function get_base_url() {
        return $this->base_url;
    }

    /**
     * Add admin menu to User Frontend option
     *
     * @return void
     */
    public function admin_menu() {
        $menu_slug = add_submenu_page( 'erp', __( 'License', 'erp-pro' ), __( 'License', 'erp-pro' ), 'manage_options', 'erp-license', array( $this, 'license_menu' ) );
        add_action( 'load-' . $menu_slug , array( $this,'remove_license_enter_notice' ) );
        add_action( 'load-wp-erp_page_erp-settings' , array( $this,'remove_license_enter_notice' ) );
        add_action( 'load-wp-erp_page_erp-tools' , array( $this,'remove_license_enter_notice' ) );
        add_action( 'load-update-core.php', array( $this,'remove_license_enter_notice' ) );
    }

    /**
     * This method will remove license enter notice on license page
     *
     * @since 0.0.1
     */
    public function remove_license_enter_notice() {
        remove_action( 'admin_notices', array($this, 'license_enter_notice'), 1 );
        remove_action( 'admin_notices', array($this, 'license_upgrade_notice'), 9999 );
        remove_action( 'admin_notices', array($this, 'license_check_notice'), 2 );
    }

    /**
     * Get license key
     *
     * @return array
     */
    public function get_license_key() {
        return get_option( self::option, array() );
    }

    /**
     * Check if this is a valid license
     *
     * @since 0.0.1
     *
     * @return boolean
     */
    public function is_valid_license() {
        $license_status = get_option( 'erp_pro_license_status' );

        if ( is_object( $license_status ) && $license_status->success && $license_status->license === 'valid' ) {
            return true;
        }

        return false;
    }

    /**
     * This method will return number of users selected during checkout.
     *
     * @since 0.0.1
     *
     * @return int|boolean
     */
    public function get_licensed_user() {
        // check if license is valid
        if ( $this->is_valid_license() ) {
            $license_status = get_option( 'erp_pro_license_status' );
            return isset( $license_status->users ) ? intval( $license_status->users ) : false;
        }

        return false;
    }

    /**
     * This method will return purchased extensions selected during checkout.
     *
     * @since 0.0.1
     *
     * @return array
     */
    public function get_licensed_extensions() {
        // check if license is valid
        if ( $this->is_valid_license() ) {
            $license_status = get_option( 'erp_pro_license_status' );
            return isset( $license_status->extensions ) && is_array( $license_status->extensions ) ? $license_status->extensions : [];
        }

        return [];
    }

    /**
     * This method will return subscription renew date.
     *
     * @since 0.0.1
     *
     * @return array
     */
    public function get_subscription_expire_date() {
        // check if license is valid
        if ( $this->is_valid_license() ) {
            $license_status = get_option( 'erp_pro_license_status' );
            return isset( $license_status->subscription_expire_date ) ? $license_status->subscription_expire_date : '';
        }

        return [];
    }

    /**
     * This method will return subscription status.
     *
     * @since 0.0.1
     *
     * @return array
     */
    public function get_subscription_status() {
        // check if license is valid
        if ( $this->is_valid_license() ) {
            $license_status = get_option( 'erp_pro_license_status' );
            return isset( $license_status->subscription_status ) ? $license_status->subscription_status : '';
        }

        return [];
    }

    /**
     * This method will return purchased license id
     * @since 0.0.1
     * @return int
     */
    public function get_license_id() {
        // check if license is valid
        if ( $this->is_valid_license() ) {
            $license_status = get_option( 'erp_pro_license_status' );
            return isset( $license_status->license_id ) ? $license_status->license_id : 0;
        }

        return 0;
    }

    /**
     * This method will return number of available erp users.
     *
     * @since 0.0.1
     *
     * @return int
     */
    public function count_users() {
        // include required files
        if ( ! function_exists( 'erp_crm_get_manager_role' ) ) {
            include_once WPERP_MODULES . '/crm/includes/functions-capabilities.php';
        }

        if ( ! function_exists( 'erp_hr_get_manager_role' ) ) {
            include_once WPERP_MODULES . '/hrm/includes/functions-capabilities.php';
        }

        if ( ! function_exists( 'erp_ac_get_manager_role' ) ) {
            include_once WPERP_MODULES . '/accounting/includes/functions/capabilities.php';
        }

        $roles = [];

        if ( wperp()->modules->is_module_active('crm') ) {
            $roles[] = erp_crm_get_manager_role();
            $roles[] = erp_crm_get_agent_role();
        }

        if ( wperp()->modules->is_module_active('accounting') ) {
            $roles[] = erp_ac_get_manager_role();
        }

        if ( wperp()->modules->is_module_active('hrm') ) {
            $roles[] = erp_hr_get_manager_role();
            $roles[] = erp_hr_get_employee_role();
        }

        $user_count = get_users( [
            'role__in' => $roles,
            'role__not_in' => 'administrator',
            'fields' => 'ID'
        ] );

        // count inactive employees
        if ( wperp()->modules->is_module_active('hrm') ) {
            $employees = $this->erp_hr_get_employees();
            $user_count = array_diff( $user_count, $employees );
        }

        return count( $user_count );
    }

    /**
     * This method will count active users of hrm
     *
     * @since 0.0.1
     * @return int
     */
    public function erp_hr_get_employees() {
        global $wpdb;

        $employee_tbl = $wpdb->prefix . 'erp_hr_employees';

        $query = $wpdb->prepare(
            "select $employee_tbl.user_id from $employee_tbl
            left join {$wpdb->users} on $employee_tbl.user_id = {$wpdb->users}.ID
            where $employee_tbl.status != %s or $employee_tbl.deleted_at is not null",
            array( 'active' ) );

        $cache_key = 'erp-pro-get-employees-count';
        $results   = wp_cache_get( $cache_key, 'erp' );

        if ( false === $results ) {
            $results = $wpdb->get_col( $query );

            wp_cache_set( $cache_key, $results, 'erp', HOUR_IN_SECONDS );
        }

        return $results;
    }

    /**
     * Prompts the user to add license key if it's not already filled out
     *
     * @return void
     */
    function license_enter_notice() {
        if ( $key = $this->get_license_key() && $this->is_valid_license() ) {
            return;
        }

        // get license object
        $status = get_option( 'erp_pro_license_status' );

        $error_notice = sprintf( __( '<p>Please <a href="%s">enter</a> your license key to activate your purchase.</p>', 'erp-pro' ), admin_url( 'admin.php?page=erp-license' ) );

        if ( $status && $status->success && $status->license !== 'valid' ) {
            $message = $this->license_check_error( $status );
            if ( ! empty( $message ) ) {
                $error_notice = '<p><strong>' . $message . '</strong></p>' . $error_notice;
            }
        }
        ?>
        <div class="error">
            <?php echo '<p><strong>' . __( 'WP ERP PRO', 'erp-pro' ) . '</strong></p>' . $error_notice; ?>
        </div>
        <?php
    }

    public function license_upgrade_notice() {
        if ( ! $this->is_valid_license() ) {
            return;
        }

        $status = get_option( 'erp_pro_license_status' );

        if ( $this->get_licensed_user() < $this->count_users() ) {
            // update status
            //update_option( 'erp_pro_license_status', array() );
            $license_id     = intval( $this->get_license_id() );
            $purchase_url   = trailingslashit( $this->base_url ) . 'pricing?utm_source=wp-admin&utm_medium=link&utm_campaign=user-capping';
            if ( ! empty( $license_id ) ) {
                $purchase_url  .= "&license_id=$license_id&action=upgrade" ;
            }
            ?>
            <div class="error">
                <p><?php printf( __( 'Current <strong>WP ERP PRO</strong> user limit has been exceeded. Purchased Users: %d, Current Site Users: %d Please <a target="_blank" href="%s">upgrade</a> the number of users as per your business needs or <strong>delete</strong> existing users to match the user limit.', 'erp-pro' ), $this->get_licensed_user(), $this->count_users(), $purchase_url ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Check activation every 12 hours to the server
     *
     * @return void
     */
    function license_check_notice() {

        if ( ! $key = $this->get_license_key() ) {
            return;
        }

        $status = get_transient( self::option );

        if ( empty( $status ) ) {
            $status   = $this->activation();
            // save transient
            $duration = 60 * 60 * 2; // 2 hours
            set_transient( self::option, $status, $duration );

            if ( false !== $status && $status->success ) {
                // update self option
                update_option( 'erp_pro_license_status', $status );
            }
            else {
                // update status
                update_option( 'erp_pro_license_status', array() );
            }
        }

        // may be the request didn't completed
        if ( isset( $status->error ) ) {
            $message = $this->license_check_error( $status );
            ?>
            <div class="error">
                <p><strong><?php _e( 'ERP Pro Error:', 'erp-pro' ); ?></strong> <?php echo $message; ?></p>
            </div>
            <?php
            return;
        }
    }

    /**
     * Activation request to the plugin server
     *
     * @return object
     */
    function activation( $request = 'check_license' ) {
        global $wp_version;

        if ( ! $option = $this->get_license_key() ) {
            return;
        }

        $params = array(
            'timeout'    => ( ( defined( 'DOING_CRON' ) && DOING_CRON ) ? 60 : 30 ),
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
            //'sslverify' => false,
            'body'       => array(
                'edd_action' => $request,
                'license'    => $option['key'],
                'item_id'    => $option['subscription_type'] === 'monthly' ? $this->monthly_plan_id : $this->yearly_plan_id,
                'url'        => home_url()
            )
        );

        $response = wp_remote_post( $this->base_url, $params );
        $update   = wp_remote_retrieve_body( $response );

        if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
            if ( is_wp_error( $response ) ) {
                echo '<div class="error"><p><strong>WP ERP Pro Activation Error:</strong> ' . $response->get_error_message() . '</p></div>';
                return false;
            }

            if ( $response['response']['code'] != 200 ) {
                echo '<div class="error"><p><strong>WP ERP Pro Activation Error:</strong> ' . $response['response']['code'] .' - ' . $response['response']['message'] . '</p></div>';
                return false;
            }

            printf('<pre>%s</pre>', print_r( $response, true ) );

            return false;
        }

        return json_decode( $update );
    }

    /**
     * Integrates into plugin update api check
     *
     * @param object $transient
     * @return object
     */
    public function check_update( $_transient_data ) {

        global $pagenow;

        if ( ! is_object( $_transient_data ) ) {
            $_transient_data = new stdClass;
        }

        if ( 'plugins.php' == $pagenow && is_multisite() ) {
            return $_transient_data;
        }

        $version_info = $this->get_info();

        if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

            list( $plugin_name, $plugin_version) = $this->get_current_plugin_info();

            // get paid extension versions
            $module_version = wp_erp_pro()->module->get_purchased_paid_modules_version();
            $module_hash    = md5( serialize( $module_version ) . $plugin_version );
            $basefile       = plugin_basename( ERP_PRO_FILE );

            if ( version_compare( $plugin_version, $version_info->new_version, '<' )
                || ( isset( $version_info->extension_version ) && $module_hash !== $version_info->extension_version ) ) {

                if ( isset( $version_info->extension_version ) && $module_hash !== $version_info->extension_version ) {
                    $version_info->new_version .= '.1';
                }

                if ( $version_info->icons ) {
                    $version_info->icons = maybe_unserialize( $version_info->icons );
                }

                $_transient_data->response[ $basefile ] = $version_info;

                // Make sure the plugin property is set to the plugin's name/location. See issue 1463 on Software Licensing's GitHub repo.
                $_transient_data->response[ $basefile ]->plugin = $basefile;
            }

            $_transient_data->last_checked         = time();
            $_transient_data->checked[ $basefile ] = $plugin_version;
        }

        // update user purchased license information
        delete_transient( self::option );

        $this->license_check_notice();

        return $_transient_data;
    }

    /**
     * Updates information on the "View version x.x details" page with custom data.
     *
     * @since 0.0.1
     *
     * @param mixed   $_data
     * @param string  $_action
     * @param object  $_args
     * @return object $_data
     */
    public function plugins_api_filter( $_data, $_action = '', $_args = null ) {
        /*
         * (
                [new_version] => 0.0.1
                [stable_version] => 0.0.1
                [name] => ERP Pro - Yearly Subscription
                [slug] => erp-pro
                [url] => http://localhost:10000/downloads/erp-pro/?changelog=1
                [last_updated] => 2020-07-22 17:59:09
                [homepage] => http://localhost:10000/downloads/erp-pro/
                [package] => http://localhost:10000/edd-sl/package_download/MTU5NTUyNzI5OTpmOWMxM2UxMzE0YTFlMGUxZGViOWIzZTc2ZGQ1Mjg2Mjo1NjplMmFmZTNhMmMzMzkyZDEwMjM0ZWM0MDY4NzY5NDAzMTpodHRwQC8vbG9jYWxob3N0QDg4ODgvZXJwX3Byby86MA==
                [download_link] => http://localhost:10000/edd-sl/package_download/MTU5NTUyNzI5OTpmOWMxM2UxMzE0YTFlMGUxZGViOWIzZTc2ZGQ1Mjg2Mjo1NjplMmFmZTNhMmMzMzkyZDEwMjM0ZWM0MDY4NzY5NDAzMTpodHRwQC8vbG9jYWxob3N0QDg4ODgvZXJwX3Byby86MA==
                [sections] => a:2:{s:11:"description";s:45:"<p>Testing this as yearly subscriptions.</p>
            ";s:9:"changelog";s:0:"";}
                [banners] => a:2:{s:4:"high";s:0:"";s:3:"low";s:0:"";}
                [icons] => a:2:{s:2:"1x";s:57:"http://localhost:10000/app/uploads/edd/2020/04/feeder.svg";s:2:"2x";s:57:"http://localhost:10000/app/uploads/edd/2020/04/feeder.svg";}
                [extension_version] => 75acc20c13116f99e34c4bd3c3ae2587
            )
         */

        if ( $_action != 'plugin_information' ) {
            return $_data;
        }

        if ( ! isset( $_args->slug ) || ( $_args->slug != self::slug ) ) {
            return $_data;
        }

        // get remote data, todo: probably add this to transient
        $_data = $this->get_info();

        // Convert sections into an associative array, since we're getting an object, but Core expects an array.
        if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
            $_data->sections = maybe_unserialize( $_data->sections );
        }

        if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
            $_data->banners = maybe_unserialize( $_data->banners );
        }

        if ( isset( $_data->icons ) && ! is_array( $_data->icons ) ) {
            $_data->icons = maybe_unserialize( $_data->icons );
        }

        // Convert icons into an associative array, since we're getting an object, but Core expects an array.
        if ( isset( $_data->contributors ) ) {
            $_data->contributors = $this->convert_object_to_array( $_data->contributors );
        }

        if( ! isset( $_data->plugin ) ) {
            $_data->plugin = self::slug;
        }

        return $_data;
    }

    /**
     * Convert some objects to arrays when injecting data into the update API
     *
     * Some data like sections, banners, and icons are expected to be an associative array, however due to the JSON
     * decoding, they are objects. This method allows us to pass in the object and return an associative array.
     *
     * @since 0.0.1
     *
     * @param stdClass|array $data
     *
     * @return array
     */
    private function convert_object_to_array( $data ) {
        $new_data = array();
        foreach ( $data as $key => $value ) {
            $new_data[ $key ] = is_object( $value ) ? (array) $value : $value;
        }

        return $new_data;
    }

    /**
     * Collects current plugin information
     *
     * @return array
     */
    function get_current_plugin_info() {
        require_once ABSPATH . '/wp-admin/includes/plugin.php';

        $plugin_data    = get_plugin_data( ERP_PRO_DIR . '/erp-pro.php' );
        $plugin_name    = $plugin_data['Name'];
        $plugin_version = $plugin_data['Version'];

        return array($plugin_name, $plugin_version);
    }

    /**
     * Get plugin update information from server
     *
     * @global string $wp_version
     * @global object $wpdb
     * @return boolean
     */
    function get_info() {
        global $wp_version, $wpdb;

        list( $plugin_name, $plugin_version) = $this->get_current_plugin_info();

        if ( is_multisite() ) {
            $wp_install = network_site_url();
        } else {
            $wp_install = home_url( '/' );
        }

        $license = $this->get_license_key();

        // get available modules
        $module_version = wp_erp_pro()->module->get_purchased_paid_modules_version();

        $params = array(
            'timeout'    => ( ( defined( 'DOING_CRON' ) && DOING_CRON ) ? 180 : 120 ),
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
            'body' => array(
                'name'              => $plugin_name,
                'slug'              => $this->plan_id,
                'type'              => 'plugin',
                'version'           => $plugin_version,
                'wp_version'        => $wp_version,
                'php_version'       => phpversion(),
                'url'               => $wp_install,
                'license'           => isset( $license['key'] ) ? $license['key'] : '',
                'license_email'     => isset( $license['email'] ) ? $license['email'] : '',
                'item_id'           => $license['subscription_type'] === 'monthly' ? $this->monthly_plan_id : $this->yearly_plan_id,
                'module_version'    => $module_version,
                'edd_action'        => 'get_version',
                'users'             => $this->count_users(),
            )
        );

        $response = wp_remote_post( $this->base_url, $params );
        $update   = wp_remote_retrieve_body( $response );

        if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
            return false;
        }

        return json_decode( $update );
    }

    public function license_check_error( $response ) {
        $message = '';

        $error = isset( $response->error ) ? $response->error : $response->license;

        switch( $error ) {

            case 'expired' :

                $message = sprintf(
                    __( 'Your license key expired on %s.', 'erp-pro' ),
                    date_i18n( get_option( 'date_format' ), strtotime( $response->expires, current_time( 'timestamp' ) ) )
                );
                break;

            case 'disabled' :
            case 'revoked' :

                $message = __( 'Your license key has been disabled.', 'erp-pro' );
                break;

            case 'missing' :

                $message = __( 'Invalid license. License doesn\'t exist.', 'erp-pro' );
                break;

            case 'invalid' :
            case 'site_inactive' :

                $message = __( 'Your license is not active for this URL.', 'erp-pro' );
                break;

            case 'item_name_mismatch' :
            case 'invalid_item_id' :

                $message = sprintf( __( 'This appears to be an invalid license key for %s subscription.', 'erp-pro' ), $_POST['subscription_type'] );
                break;

            case 'no_activations_left':

                $message = __( 'Your license key has reached its activation limit.', 'erp-pro' );
                break;

            case 'missing_url':

                $message = __('URL not provided', 'erp-pro' );
                break;

            case 'license_not_activable':

                $message = __( 'Attempting to activate a bundle\'s parent license.', 'erp-pro' );
                break;

            case 'key_mismatch':

                $message = __( 'License is not valid for this product.', 'erp-pro' );
                break;

            case 'invalid_subscription':
                $purchase_url   = trailingslashit( wp_erp_pro()->update->get_base_url() ) . 'my-account/subscriptions/?utm_source=wp-admin&utm_medium=link&utm_campaign=erp-pro-expired-subscription';
                $message = sprintf( __( 'No active subscription found with given license key. Please <a target="_blank" href="%s">activate</a> your subscription to use <strong>WP ERP PRO</strong>.', 'erp-pro' ), $purchase_url );
                break;

            default :
                $message = __( 'An error occurred, please try again.', 'erp-pro' );
                break;
        }

        return $message;
    }

    /**
     * Plugin license enter admin UI
     *
     * @return void
     */
    function license_menu() {
        echo '<div class="wrap">';
        $errors = array();
        if ( isset( $_POST['submit'] ) ) {
            if ( empty( $_POST['email'] ) ) {
                $errors[] = __( 'Empty email address', 'erp-pro' );
            }

            if ( empty( $_POST['license_key'] ) ) {
                $errors[] = __( 'Empty license key', 'erp-pro' );
            }

            if ( ! $errors ) {
                update_option( self::option, array('email' => $_POST['email'], 'key' => $_POST['license_key'], 'subscription_type' => $_POST['subscription_type']) );
                delete_transient( self::option );

                $response = $this->activation( 'activate_license' );

                if ( $response && isset( $response->success ) ) {
                    update_option( 'erp_pro_license_status', $response );
                }

                if ( $response->license === 'valid' ) {
                    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'License activated successfully.', 'erp-pro' ) . '</p></div>';
                }
                else {
                    $message = $this->license_check_error( $response );
                    echo '<div class="notice notice-error is-dismissible"><p>' . $message . '</p></div>';
                }
            }
        }

        if ( isset( $_POST['deactivate_license'] ) ) {
            $response = $this->activation( 'deactivate_license' );

            if ( $response && isset( $response->success ) && $response->success ) {
                // delete license data
                delete_option( self::option );
                delete_transient( self::option );
                delete_option( 'erp_pro_license_status' );

                echo '<div class="notice notice-success is-dismissible"><p>' . __( 'License deactivated successfully.', 'erp-pro' ) . '</p></div>';
            }
            else {
                $message = $this->license_check_error( $response );
                if ( ! empty( $message ) ) {
                    echo '<div class="notice notice-error is-dismissible"><p>' . $message . '</p></div>';
                }
            }
        }

        $license = $this->get_license_key();
        $email   = isset( $license['email'] )   ? $license['email'] : get_option( 'admin_email' );
        $key     = isset( $license['key'] )     ? $license['key']   : '';
        $subscription_type = isset( $license['subscription_type'] ) ? $license['subscription_type'] : '';
        ?>
            <?php
            if ( $this->is_valid_license() ) {
                $message = [];
                $message[] = __( '<strong>Subscription Status:</strong> ', 'erp-pro' ) . ucfirst( $this->get_subscription_status() );
                $message[] = __( '<strong>Subscription Renewal Date:</strong> ', 'erp-pro' ) . $this->get_subscription_expire_date();
                $message[] = __( '<strong>Number of user:</strong> ', 'erp-pro') . $this->get_licensed_user();

                if ( ! empty( $this->get_licensed_extensions() ) ) {
                    $extension_purchased = [];
                    $paid_module_info = wp_erp_pro()->module->get_paid_modules_info();

                    foreach ( $this->get_licensed_extensions() as $extension_path ) {
                        if ( array_key_exists( $extension_path, $paid_module_info ) ) {
                            $extension_purchased[] = $paid_module_info[ $extension_path ]['name'];
                        }
                    }

                    if ( ! empty( $extension_purchased ) ) {
                        $message[] = __( '<strong>Extra Extension Purchased:</strong> ', 'erp-pro' ) . implode( ', ', $extension_purchased );
                    }
                }
                ?>

                <div class="notice notice-success">
                    <?php
                    foreach ( $message as $msg ) {
                        echo '<p>' . $msg . '</p>';
                    }
                    ?>
                </div>

            <?php } ?>
            <h3><?php _e( 'Plugin Activation', 'erp-pro' ); ?></h3>

            <p class="description">
                <?php _e( 'Enter the E-mail address that was used for purchasing the plugin and the license key.', 'erp-pro' ); ?>
                <?php _e( 'We recommend you to enter those details to get regular <strong>plugin update and support</strong>.', 'erp-pro' ); ?>
            </p>

            <h3><?php _e( 'WP ERP Pro', 'erp-pro' ); ?></h3>
            <hr>
            <div class="erp-pro-license-wrap">
                <?php
                if ( $errors ) {
                    foreach ($errors as $error) {
                        ?>
                        <div class="error"><p><?php echo $error; ?></p></div>
                        <?php
                    }
                }

                $license_status = get_option( 'erp_pro_license_status' );
                if ( ! isset( $license_status->activated ) || $license_status->activated != true ) {
                    ?>

                    <form method="post" action="">
                        <table class="form-table">
                            <tr>
                                <th><?php _e( 'E-mail Address', 'erp-pro' ); ?></th>
                                <td>
                                    <input type="email" name="email" class="regular-text" value="<?php echo esc_attr( $email ); ?>" required>
                                    <span class="description"><?php _e( 'Enter your purchase Email address', 'erp-pro' ); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e( 'License Key', 'erp-pro' ); ?></th>
                                <td>
                                    <input type="text" name="license_key" class="regular-text" value="<?php echo esc_attr( $key ); ?>">
                                    <span class="description"><?php _e( 'Enter your license key', 'erp-pro' ); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Subscription Type', 'erp-pro' ); ?></th>
                                <td>
                                    <select name="subscription_type" class="regular-text">
                                        <option value="monthly" <?php selected( $subscription_type, 'monthly') ?>><?php _e( 'Monthly', 'erp-pro' ); ?></option>
                                        <option value="yearly" <?php selected( $subscription_type, 'yearly') ?>><?php _e( 'Yearly', 'erp-pro'); ?></option>
                                    </select>
                                    <span class="description"><?php _e( 'Select your subscription type', 'erp-pro' ); ?></span>
                                </td>
                            </tr>
                        </table>
                        <?php if ( $this->is_valid_license() ): ?>

                            <p class="submit">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="Sync License">
                                <input type="submit" name="deactivate_license" id="deactivate_license" class="button button-secondary" value="Deactivate License">
                            </p>

                        <?php else: submit_button( __( 'Save & Activate', 'erp-pro' ) ); ?>
                        <?php endif; ?>
                    </form>
                <?php } else {

                    if ( isset( $license_status->update ) ) {
                        $update = strtotime( $license_status->update );
                        $expired = false;

                        if ( time() > $update ) {
                            $string = __( 'has been expired %s ago', 'erp-pro' );
                            $expired = true;
                        } else {
                            $string = __( 'will expire in %s', 'erp-pro' );
                        }
                        // $expired = true;
                        ?>
                        <div class="updated <?php echo $expired ? 'error' : ''; ?>">
                            <p>
                                <strong><?php _e( 'Validity:', 'erp-pro' ); ?></strong>
                                <?php printf( 'Your license %s.', sprintf( $string, human_time_diff( $update, time() ) ) ); ?>
                            </p>

                            <?php if ( $expired ) { ?>
                                <p><a href="https://www.wperp.com/account/" target="_blank" class="button-primary"><?php _e( 'Renew License', 'erp-pro' ); ?></a></p>
                            <?php } ?>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="updated">
                        <p><?php _e( 'Plugin is activated', 'erp-pro' ); ?></p>
                    </div>

                    <form method="post" action="">
                        <?php submit_button( __( 'Delete License', 'erp-pro' ), 'delete', 'delete_license' ); ?>
                    </form>

                <?php } ?>

            </div>


            <?php do_action( 'erp_pro_update_license_wrap' ); ?>
        </div>
        <?php
    }

    /**
     * Show plugin udpate message
     *
     * @since 0.0.1
     *
     * @param  array $args
     *
     * @return void
     */
    public function plugin_update_message( $args ) {

        if ( $this->is_valid_license() ) {
            return;
        }

        $upgrade_notice = sprintf(
            '</p><p class="%s-plugin-upgrade-notice" style="background: #dc4b02;color: #fff;padding: 10px;">Please <a href="%s" target="_blank">activate</a> your license key for getting regular updates and support',
            self::slug,
            admin_url( 'admin.php?page=erp-license' )
        );

        echo apply_filters( $this->product_id . '_in_plugin_update_message', wp_kses_post( $upgrade_notice ) );
    }

}
