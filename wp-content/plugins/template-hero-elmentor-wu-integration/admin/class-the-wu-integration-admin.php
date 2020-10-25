<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.waashero.com
 * @since      1.0.0
 *
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/admin
 * @author     J Hanlon <info@waashero.com>
 */
namespace The_WP_Ultimo\The_Wu_Integration;
use TemplateHero\Plugin_Client\Api\Tokens as library;
use \Firebase\JWT\JWT as Token;
class The_Wu_Integration_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in The_Wu_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The The_Wu_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		$is_elementor_screen = ( $screen && false !== strpos( $screen->id, 'the-wu-options' ) );
		if ( ! $is_elementor_screen && $screen->id != 'plans_page_wu-edit-plan-network' ) {
			
			return;
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/the-wu-integration-admin.css', array(), $this->version, 'all' );

	}

	public function add_ultimo_libraries_metaboxes() {
		$screen = get_current_screen();
		add_meta_box(
			'the-wu-libraries-data',
			__('Template Hero Libraries',  'the-wu-integration' ), array( $this, 'the_wu_add_libraries_to_ultimo'), $screen->id, 'advanced', 'high');
	}

	public function the_wu_add_libraries_to_ultimo() {
		?>
		<style>
		.card {
			max-width : 100% !important;
		}
		.th-table-token-row {
			padding-left : 45px !important;
			padding-top  : -10px !important;
		}
		.th-table-row-id {
			
			padding-top  : 15px !important;
		}

		.th-table-token-button {
			padding-left : 80px !important;
		}

		.btn-success {
			color: #fff !important;;
			background-color: #28a745 !important;;
			border-color: #28a745 !important;;
		}

		.btn {
			display: inline-block;
			font-weight: 400;
			color: #212529;
			text-align: center;
			vertical-align: middle;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			background-color: transparent;
			border: 1px solid transparent;
			padding: .375rem .75rem;
			font-size: 1rem;
			line-height: 1.5;
			border-radius: .25rem;
			transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
		}
		</style>
		
    	<?php
		
		include( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN. 'settings/templates/connect.php')
		?>
	
		<?php
	}

	/**
	 * Changes Context to override activate button
	 *
	 * @param [type] $context
	 * @return void
	 */
	public function the_wu_add_context( $context ) {
		return 'ultimo';
	}

	/**
	 * Adds activate button content
	 *
	 * @param [type] $content
	 * @return void
	 */
	public function the_wu_add_button_content( $content, $id, $status, $plan_id, $library_id  ) {
		if( $status == 'Deactivate' ) {
			$class  = 'btn-danger';
		} else {
			$class  = 'btn-success';
		}
		?>
		<style>
		.btn-danger {
			color: #fff;
			background-color: #dc3545;
			border-color: #dc3545;
		}
		</style>
		<?php
		ob_start();
		?>
			<button id=<?php echo $id;?> value = "<?php echo $status ?>" data-id="<?php echo  $library_id; ?>" data-plan="<?php echo $plan_id; ?>"  class="btn th-delete-client-btn <?php echo $class; ?>" onclick="the_wu_activateLibraryplan(event);" ><?php echo $status ?> </button>
		<?php
		$content  = ob_get_clean();
		ob_flush();
		return $content;
	}

	/**
	 * Sets Active Libraries Ids Access key
	 *
	 * @param [type] $key
	 * @return void
	 */
	public function the_wu_set_libraries_ids_key( $key ) {
		$current_plan = wu_get_current_site()->get_plan();
		return 'active_libraries_ids'.$current_plan->id;
	}

	/**
	 * Sets Active Libraries Nmes Access key
	 *
	 * @param [type] $key
	 * @return void
	 */
	public function the_wu_set_libraries_names_key( $key ) {
		$current_plan = wu_get_current_site()->get_plan();
		return 'active_libraries_ids'.$current_plan->id;
	}

	 /**
     * Activates Library viva ajax supports multiple libraries
     *
     * @return void
     */
    public static function the_wu_activateLibraryplan() {
        
        $nonce   = $_REQUEST['th_create_token_security'];
		$user_id = $_POST['user_id']; 
		$plan_id = $_POST['plan_id'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            
            echo json_encode([
                'success' => false,
                'message' => __( 'Security check',  'the-wu-integration' ),
            ]);
            exit;
		}
		
		if ( empty( $plan_id ) ) {
            
            echo json_encode([
                'success' => false,
                'message' => __( 'Empty Plan.',  'the-wu-integration' ),
            ]);
            exit;
        }
        if( isset( $_POST['template_hero_active_library'] ) ) {
            $context = $_POST['context'];
			
            global $wpdb;
            $active_library    = isset( $_POST['template_hero_active_library'] ) ? $_POST['template_hero_active_library'] : '';
            $templatehero_libs = $wpdb->prefix . "templatehero_libraries";
            $library = $wpdb->get_results( "SELECT id, library_name, library_url, client_id, client_secret FROM $templatehero_libs WHERE `id` = $active_library" );
            if ( $library ) {
        
                $remote_site_url  = $library[0]->library_url;
                $remote_site_name = $library[0]->library_name;

                $network_wide = get_site_option( 'template_hero_elementor_networkwide', 'on' );
            
                $public_key = $library[0]->client_id;
                if( $network_wide == 'on' ) {
                    update_site_option( 'template_hero_elementor_current_url', esc_url_raw( get_bloginfo( 'url' ) ) );
                    update_site_option( 'template_hero_elementor_remote_url'.$active_library, esc_url_raw( $remote_site_url ) );
                    update_site_option( 'template_hero_elementor_public_key'.$active_library, $public_key );
                }

                $private_key = $library[0]->client_secret;

				$library_ids_key = "active_libraries_ids".$plan_id;
				$library_ids_key = apply_filters( 'the_wu_libraries_ids_key', $library_ids_key );
				$library_names_key = "active_libraries_names".$plan_id;
				$library_names_key = apply_filters( 'the_wu_libraries_names_key', $library_names_key );
                if( $context == 'Deactivate' ) {
                    
					$libs     = get_site_option( $library_ids_key, array() );
					$libnames = get_site_option( $library_names_key, array() );
					$libnames = \array_diff( $libnames, [$remote_site_name] );
					$libs     = \array_diff( $libs, [$active_library] );
					update_site_option( $library_ids_key, $libs );
					update_site_option( $library_names_key , $libnames );
                    
                    echo json_encode([
                        'success' => true,
                        'message' => __( 'Library deactivated successfully.',  'the-wu-integration' )
                    ]);
                    exit;
                }
                if( !empty( $private_key ) && !empty( $public_key ) && !empty( $remote_site_url )  ) {

                    $user      = get_current_user();
                    $user_id   = get_current_user_id();
                    $issuedAt  = time();
                    $notBefore = apply_filters( 'template_hero_wu_auth_not_before', $issuedAt, $issuedAt );
                    $expire    = apply_filters( 'template_hero_wu_auth_expire', $issuedAt + ( DAY_IN_SECONDS * 7 ), $issuedAt );
                    
                
                    $tokenArgs = array(
                        'iss' => trim( esc_url_raw( get_home_url() ) ),
                        'iat' => $issuedAt,
                        'nbf' => $notBefore,
                        'exp' => $expire,
                        'data' => array(
                            'client' => array(
                                'public_id' => $public_key,
                            ),
                        )
                    );


                    try {

						// $token = library::encodeToken( $tokenArgs, trim( $private_key ) );
						$token = Token::encode( apply_filters( 'template_hero_auth_token_before_sign', $tokenArgs, $user ), trim( $private_key ) );
                                        
                        if ( empty( $token ) || !$token ) {
                            echo json_encode([
                                'success' => false,
                                'message' => __( 'Token not updated. Library is not activated.',  'the-wu-integration' ),
                            ]);
                            exit;

                        } else {

                            library::removeToken( $user_id, $active_library );
                            library::setTransient( $token, $user_id, $active_library );
                            $library_id   = $library[0]->id;
							$library_name = $library[0]->library_name;
							$libs     = get_site_option( $library_ids_key, array() );
							$libnames = get_site_option( $library_names_key, array() );
							$libs[]       = $library_id;
							$libnames[]   = $library_name;
							update_site_option( $library_ids_key, $libs );
							update_site_option( $library_names_key, $libnames );
                            
                            echo json_encode([
                                'success' => true,
                                'message' => __( 'Library activated successfully.',  'the-wu-integration' ),
                            ]);
                            exit;
                        }

                    } catch( \Exception $e ) {
                        echo json_encode([
                            'success' => false,
                            'message' => __( $e ,  'the-wu-integration' )
                        ]);
                        exit;
                    }
                    exit;
                }
                echo json_encode([
                    'success' => false,
                    'message' => __( 'Library is not activated.Empty Fields.',  'the-wu-integration'),
                ]);
                exit;
            }
            
        }
        echo json_encode([
            'success' => false,
            'message' => __( 'Library not activated. Empty library id.',  'the-wu-integration' ),
        ]);
        exit;
	}
	
	/**
	 * License Network Settongs
	 *
	 * @return void
	 */
	public function the_wu_update_license_options() {

		$user_id = get_current_user_id();
		$nonce   = $_REQUEST['th_create_token_security'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            die( __( 'Failed security check.', 'the-wu-integration' ) ); 
		}

		$license_key = $_REQUEST['th_form_license_input'];
        if ( empty($_REQUEST['th_form_license_input']) && !$_REQUEST['th_form_license_input'] ) {
			echo 'Empty license input.';
            die( __( 'Failed validation check.', 'the-wu-integration' ) ); 
		}

		$active_license = self::activateLicense( $license_key );

		// Successful api response. $license_data->license will be either "valid" or "invalid"
		if( isset( $active_license['license'] ) ) {

			update_option( '_the_wu_license_key_status', 'active' );

		} elseif( isset( $active_license['status'] )  == 'error') {

			delete_option( '_the_wu_license_key_status' );

		}

		update_option( '_the_wu_license_key', $license_key );
		wp_die( json_encode($active_license) );

	}
	

	/**
	 * Does Sanitization
	 *
	 * @param [type] $new
	 * @return void
	 */
	static private function sanitizeLicense( $new ) {

		$old = get_option( '_the_wu_license_key' );
		if( $old && $old != $new ) {
			delete_option( '_the_wu_license_key_status' ); 
		}
		
		return $new;
	}

	/**
	 * Initiates license activation process
	 *
	 * @return void
	 */
	private static function activateLicense( $license_key ) {
		
		// trim and sanitize the license 
		$license = trim( self::sanitizeLicense( $license_key ) );

		// set data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => THE_WU_INTEGRATION_ITEM_ID, // The ID of the item in EDD
			'url'        => home_url()
		);

		// Call the update API.
		$response = wp_remote_post( THE_WU_INTEGRATION_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// Check the response for errors
		if ( empty($response) || is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_site_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'the-wu-integration' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.', 'the-wu-integration' );
						break;
					case 'invalid' :
						$message = __( 'Your license is invalid.', 'the-wu-integration' );
						break;
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.', 'the-wu-integration' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'the-wu-integration' ), THE_WU_INTEGRATION_ITEM_NAME);
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'the-wu-integration' );
						break;
					default :
						$message = __( 'An error occurred, please try again.', 'the-wu-integration' );
						break;

				}
			}
	
			// Check for errror messages and return if true
			if ( ! empty( $message ) ) {
			
				return [
					'status' => 'error',
					'message' => $message
				];
				
			}

			return [
				'status' => 'updated',
				'license' => $license_data->license
			];
		}
	}


	/**
	 * Ads Custom activated libraries to default ones
	 *
	 * @param [array] $active_libraries
	 * @param [integer] $plan_id
	 * @return array
	 */
	public function the_wu_set_activated_libraries( $active_libraries, $plan_id ) {
		$active_libraries    =  get_site_option( "active_libraries_ids".$plan_id, array() );
		return $active_libraries;
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in The_Wu_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The The_Wu_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		$is_elementor_screen = ( $screen && false !== strpos( $screen->id, 'the-wu-options' ) );
		if ( ! $is_elementor_screen && $screen->id != 'plans_page_wu-edit-plan-network' ) {
			
			return;
		}
		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'js/the-wu-integration-admin.js', 
			array( 'jquery' ), 
			$this->version, 
			false 
		);

		wp_enqueue_script( 
			$this->plugin_name.'-license', 
			plugin_dir_url( __FILE__ ) . 'js/the-wu-integration-license.js', 
			array( 'jquery' ), 
			$this->version, 
			false 
		);
		
		if ( isset( $_GET['tab'] ) ) {
            $tab  = $_GET['tab'];
        } else {
            $tab  = '';
        }
        $user_id =  get_current_user_id();
        wp_localize_script( $this->plugin_name, 'templatehero_ajax_obj',
            array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'user_id' => $user_id,
                'blog_id' => get_current_blog_id(),
				'tab'     => $tab,
				'th_create_token_security' => wp_create_nonce( 'create-token-' . $user_id )
            )
		);

		wp_localize_script( $this->plugin_name.'-license', 'the_wu_ajax_obj',
			array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'user_id' => $user_id,
				'blog_id' => get_current_blog_id(),
				'tab'     => $tab,
				'the_wu_create_token_security' => wp_create_nonce( 'create-token-' . $user_id )
			)
		);
	}
}
