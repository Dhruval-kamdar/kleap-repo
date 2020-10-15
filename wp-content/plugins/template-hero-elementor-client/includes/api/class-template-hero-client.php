<?php
/**
 * The api token functionality of the plugin.
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 */

/**
 * The api token functionality of the plugin.
 *
 *
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 * @author     J Hanlon | Waas Hero <info@waashero.com>
 */

namespace TemplateHero\Plugin_Client\Api;
use \Firebase\JWT\JWT as Token;
class Client {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $version;
    
    public $allow_devs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
        $this->version     = $version;
        $template_hero_elementor_options = get_option( 'template_hero_elementor_advance_options', array() );
        $allow_dev         = !empty( $template_hero_elementor_options['template_hero_elementor_allowed_extensions'] ) ? $template_hero_elementor_options['template_hero_elementor_allowed_extensions'] : 'no';
        $this->allow_devs  = $allow_dev;
	}

    /**
     * Creates the clients library ( Developers can use this function )
     * @since 1.0.0
     * @return void
     */
    public static function th_create_library( $user_id, $blog_id, $library_name, $library_url, $public_id, $private_id ) {


        if ( !is_user_logged_in() ) {
            return;
        }

        if( empty( $library_name ) ) {
            return;
        }

        if( empty( $library_url ) ) {
            return;
        }

        if ( empty( $user_id ) ) {
            $user_id = get_current_user_id();
        } 

        if ( empty( $blog_id ) ) {
            $blog_id = get_current_blog_id();
        }

        if( empty( $user_id ) || $user_id != get_current_user_id() ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }

        if( empty( $blog_id ) || $blog_id != get_current_blog_id() ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }

        $client_id = $public_id;
        $secret    = $private_id; 
       
        global $wpdb;
        $templatehero_clients = $wpdb->prefix . "templatehero_libraries";
        $result = $wpdb->get_results ( "
            SELECT * 
            FROM  $templatehero_clients
            WHERE client_id = '$client_id' AND client_secret = '$secret' AND library_url = '$library_url'" 
        );

        if ( $result ) {

            return 2;
        }
        
        $token_data = array(
            'blog_id'       =>  $blog_id,
            'user_id'       =>  $user_id,
            'library_name'  =>  $library_name,
            'library_url'   =>  $library_url,
            'client_id'     =>  $client_id,
            'client_secret' =>  $secret
        );
        $format = array('%d','%d','%s','%s','%s','%s','%s');
        $templatehero_clients = apply_filters( "template_hero_insert_client_to_table", $templatehero_clients );
        $token_data           = apply_filters( "template_hero_insert_client_token_data", $token_data );
        $format               = apply_filters( "template_hero_insert_client_table_formate", $format );
        do_action( "template_hero_before_client_library_creation", $templatehero_clients, $token_data, $format );
        try {

            $wp_db_resut = $wpdb->insert( $templatehero_clients, $token_data, $format );
        } catch( exception $e) {

            return $e;
        }
        do_action( "template_hero_after_client_library_creation", $wp_db_resut, $templatehero_clients, $token_data );
        if ( $wpdb->insert_id && $wpdb->insert_id != 0 ) {

            return  $wpdb->insert_id;
        } else {
            return 0;
        }

        wp_die();
    }
    
    /**
     * Deletes the Library
     * @since 1.0.0
     * @return void
     */
    public function thDeleteClientLibrary() {

        $user_id = $_REQUEST['user_id'];
        if( empty( $user_id ) || !$user_id == get_current_user_id() ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }
        $nonce = $_REQUEST['th_create_token_security'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            die( __( 'Security check', 'template-hero-elementor' ) ); 
        }

        $row_id = $_REQUEST['row_id'];
        if ( empty( $row_id ) ) {
            die( __( 'Security check. Must include row id.', 'template-hero-elementor' ) ); 
        }
        global $wpdb;
        $templatehero_libraries = $wpdb->prefix . "templatehero_libraries";
        $templatehero_libraries = apply_filters( "template_hero_delete_client_library_from_table", $templatehero_libraries );
        if( is_array( $row_id ) ):
            foreach( $row_id as $id ):
                do_action( "template_hero_before_delete_library", $templatehero_libraries, $id  );
                self::delete_library( $id );
                $result = $wpdb->delete( $templatehero_libraries, array( 'id' => $id ), array( '%d' ) );
                do_action( "template_hero_after_delete_library", $result, $templatehero_libraries, $id  );
            endforeach;
        else:
            do_action( "template_hero_before_delete_library", $templatehero_libraries, $row_id );
            self::delete_library( $row_id );
            $result = $wpdb->delete( $templatehero_libraries, array( 'id' => $row_id ), array( '%d' ) );
            do_action( "template_hero_after_delete_library", $result, $templatehero_libraries, $row_id  );
            
        endif;

        echo $row_id;
        wp_die();
        
    }

    /**
     * Deletes  library options
     * @since 1.1.4
     * @param [type] $id
     * @return void
     */
    public static function delete_library( $active_library ) {
        do_action( 'template_hero_elementor_before_delete_library_meta', $active_library  );
        $network_wide = get_site_option( 'template_hero_elementor_networkwide', '' );
        global $wpdb;
        $active_library    = $active_library ;
        $templatehero_libs = $wpdb->prefix . "templatehero_libraries";
        $library           = $wpdb->get_results( "SELECT id, library_name, library_url, client_id, client_secret FROM $templatehero_libs WHERE `id` = $active_library" );
        if (  $library ) {
            $remote_site_name = $library[0]->library_name;
        } else {
            $remote_site_name = '';
        }
        $library_ids_key = "active_libraries_ids";
        $library_ids_key = apply_filters( 'the_libraries_ids_key', $library_ids_key );
        $library_names_key = "active_libraries_names";
        $library_names_key = apply_filters( 'the_libraries_names_key', $library_names_key );
        $network_wide      = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
        if ( $network_wide == 'on' ) {
            $libs     = get_site_option( $library_ids_key, array() );
            // $libnames = get_site_option( $library_names_key, array() );
            // $libnames = \array_diff( $libnames , [$remote_site_name] );
            $libs     = \array_diff( $libs , [$active_library] );
            update_site_option( $library_ids_key, $libs );
           // update_site_option( $library_names_key, $libnames );
            delete_site_option( 'template_hero_elementor_remote_url'.$active_library );
            delete_site_option( 'template_hero_elementor_public_key'.$active_library );
        } else {
            $libs         = get_option( $library_ids_key, array() );
            // $libnames     = get_option( $library_names_key, array() );
            // $libnames = \array_diff( $libnames , [$remote_site_name] );
            $libs     = \array_diff( $libs , [$active_library] );
            update_option( $library_ids_key, $libs  );
            //update_option( $library_names_key, $libnames  );
            delete_option( 'template_hero_elementor_remote_url'.$active_library );
            delete_option( 'template_hero_elementor_public_key'.$active_library );
        }
        do_action( 'template_hero_elementor_after_delete_library_meta', $active_library  );
    }

    /**
     * Gets the clients secret
     * @since 1.0.0
     * @return void
     */
    public function thGetClientLibrarySecret() {
        do_action( 'template_hero_elementor_before_get_library_secret', $_REQUEST['row_id'] );
        $user_id = $_REQUEST['user_id'];
        if( empty( $user_id ) || !$user_id == get_current_user_id() ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }
 
        $nonce = $_REQUEST['th_create_token_security'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            die( __( 'Security check', 'template-hero-elementor' ) ); 
        }

        $row_id = $_REQUEST['row_id'];
        if ( empty( $row_id ) ) {
            die( __( 'Security check. Must include row id.', 'template-hero-elementor' ) ); 
        }

        global $wpdb;

        $templatehero_libraries   = $wpdb->prefix . "templatehero_libraries";
        $templatehero_libraries   = apply_filters( "template_hero_select_secret_get_library_table", $templatehero_libraries );
        $query = $wpdb->prepare(
            "SELECT client_secret FROM $templatehero_libraries WHERE `id` = %d",
            $row_id
        );
        $client_secret = '';
        $client_secret = apply_filters( 'template_hero_elementor_before_get_library_secret', $client_secret );
        $client_secret = $wpdb->get_results( $query );
        $client_secret = apply_filters( 'template_hero_elementor_after_get_library_secret', $client_secret );
        do_action( 'template_hero_elementor_after_get_library_secret', $_REQUEST['row_id'], $client_secret );

        echo json_encode( [
            'client_secret' => $client_secret[0]->client_secret,
            'row_id'        =>  $row_id
        ] );

        wp_die();
    }
}
