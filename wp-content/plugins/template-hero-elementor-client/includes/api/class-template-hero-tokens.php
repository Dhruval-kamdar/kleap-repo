<?php
/**
 * The api token functionality of the plugin.
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 * @since 1.0.0
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
use TemplateHero\Plugin_Client\Api\Client as library;
use \Firebase\JWT\JWT as Token;

class Tokens {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
        $this->namespace = $this->plugin_name . '/v' . intval($this->version);
        $activation_lib_added = get_site_option( 'th_elemntor_activation_library', '' );
        
        if ( $activation_lib_added != 'added' ) {
            if ( is_multisite() ) {

                // get ids of all sites
            
                global $wpdb;
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        
                foreach ( $blogids as $blog_id ) {
        
                    switch_to_blog( $blog_id );
                    self::template_hero_elementor_create_activation_lib();
                    restore_current_blog();
                }
            } else {
                self::template_hero_elementor_create_activation_lib();
            }
            update_site_option( 'th_elemntor_activation_library', 'added' );
        }
        
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, TEMPLATE_HERO_ELEMENTOR_ASSETS_URL_PUBLIC . 'css/template-hero-elementor-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_token_scripts() {
        $screen   = get_current_screen();
        $is_elementor_screen = ( $screen && false !== strpos( $screen->id, 'the' ) );
        if( !$is_elementor_screen && $screen->id != 'plans_page_wu-edit-plan-network' && $screen->base != 'settings_page_template-hero-elementor-options' && $screen->base != 'settings_page_template-hero-elementor-options-network' && $screen->base != 'toplevel_page_template-hero-elementor-options-network' ) {
            
            return;
        }
        /**
         * frontend ajax requests.
         */
        wp_enqueue_script( 
            $this->plugin_name.'-ajax', 
            TEMPLATE_HERO_ELEMENTOR_ASSETS_URL. 'js/create-token-ajax.js', 
            array('jquery'), 
            null, 
            true 
        );
        if ( isset( $_GET['tab'] ) ) {
            $tab  = $_GET['tab'];
        } else {
            $tab  = '';
        }
        $user_id =  get_current_user_id();
        wp_localize_script( $this->plugin_name.'-ajax', 'templatehero_ajax_obj',
            array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'user_id' => $user_id,
                'blog_id' => get_current_blog_id(),
                'tab'     => $tab,
                'th_create_token_security' => wp_create_nonce( 'create-token-' . $user_id ),
            )
        );
    }

    /**
     * Delets library id and name from option
     * @since 1.2.0
     * @param [integer] $library_id
     * @param [string] $library_name
     * @return void
     */
    public static function the_delete_library_meta( $active_library, $library_ids_key, $library_names_key ) {
        $network_wide   = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
    
        if ( $network_wide == 'on' ) {
            $libs     = get_site_option( $library_ids_key, array() );
            $libs     = \array_diff( $libs , [$active_library] );
            update_site_option( $library_ids_key, $libs );
            delete_site_option( "template_hero_elementor_public_key".$active_library );
            delete_site_option( "template_hero_elementor_private_key".$active_library );
            delete_site_option( "template_hero_elementor_remote_url".$active_library );
        } else {
            $libs         = get_option( $library_ids_key, array() );
            $libs         = \array_diff( $libs , [$active_library] );
            update_option( $library_ids_key, $libs );
            delete_option( "template_hero_elementor_public_key".$active_library );
            delete_option( "template_hero_elementor_private_key".$active_library );
            delete_option( "template_hero_elementor_remote_url".$active_library );
        }
        $transient = delete_transient( "token_".get_current_user_id().$active_library );
    }

    /**
     * Creates new Token
     * @since 1.0.0
     * @return void
     */
    public function refreshJwtToken() {
       
        $user_id    = $_REQUEST['user_id'];
        $library_id = $_REQUEST['library_id'];
        if( empty( $user_id ) || !$user_id == get_current_user_id() ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }

        if( empty( $library_id )  ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }
 
        $nonce = $_REQUEST['th_create_token_security'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            die( __( 'Security check', 'template-hero-elementor' ) ); 
        }

        $token = get_transient( "token_".$user_id.$library_id );
        if( !$token ) {
            $admin_ids = self::get_admin_user_ids();
			foreach( $admin_ids as $id  ) {
				$token = get_transient( "token_".$id.$library_id  );
				if( $token ) {
					break;
				}
			}
        }
        $network_wide         = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
		if ( $network_wide == 'on' ) {
			switch_to_blog( 1 );
			$token = get_transient( "token_network_wide".$library_id );
			restore_current_blog();
		}

        if ( !$token ) {
            // echo json_encode([
            //     'success' => false,
            //     'message' => __( 'No valid token.', 'template-hero-elementor' ),
            // ]);
            // exit;
        }

        $user      = get_current_user();
        $issuedAt  = time();
        $notBefore = apply_filters( 'template_hero_auth_not_before', $issuedAt, $issuedAt );
        $expire    = apply_filters( 'template_hero_auth_expire', $issuedAt + ( DAY_IN_SECONDS * 7 ), $issuedAt );
       
        if( $network_wide == 'on' ) {
            $public_key  = get_site_option( 'template_hero_elementor_public_key'.$library_id, "" );
        } else {
            $public_key  = get_option( 'template_hero_elementor_public_key'.$library_id, "" );
        }
        if ( $public_key ) {
            global $wpdb;
            $active_library    = $library_id;
            $templatehero_libs = $wpdb->prefix . "templatehero_libraries";
            $library            = $wpdb->get_results( "SELECT id, library_name, library_url, client_id, client_secret FROM $templatehero_libs WHERE `id` = $active_library" );
            if ( $library ) {
                $private_key = $library[0]->client_secret;
            }
        }

        if( self::checkForCharacterCondition( $private_key ) ):

            /** Let the user modify the token data before the sign. */
            $token = array(
                'iss' => trim( esc_url_raw( get_home_url() ) ),
                'iat' => $issuedAt,
                'nbf' => $notBefore,
                'exp' => $expire,
                'data' => array(
                    'client' => array(
                        'public_id' => $public_key, 
                    ),
                    'user' => $user,
                ),
            );

        $token = self::encodeToken( $token, $private_key ); 
        $isSet = self::setTransient( $token, $user_id, $library_id );

    
        else: 
                
            // api private key is invalid
            echo json_encode([
                'success' => false,
                'message' => __( 'Incorrect Api Key format.', 'template-hero-elementor' ),
            ]);
            wp_die();
      
        endif;

            //send message if token fails
            if ( empty( $token ) || !$token ) {
                echo json_encode([
                    'success' => false,
                    'message' => __( 'A valid api key is required.', 'template-hero-elementor' )
                ]); 
            }

            //send message if token refreshes and saves
            if( $token && $isSet ):
                echo json_encode([
                    'success' => $isSet,
                    'message' => __( 'Token created successfully.', 'template-hero-elementor' ),
                ]);
            endif;

        wp_die();
    
    }

    /**
     * Activates Library
     *
     * @param [type] $library_id
     * @param string $context
     * @return void
     */
    public static function template_hero_elementor_admin_activate_library( $library_id, $context = 'local' ) {
        if( !empty( $library_id ) ) {

            global $wpdb;
            $active_library    = $library_id;
            $templatehero_libs = $wpdb->prefix . "templatehero_libraries";
            $library            = $wpdb->get_results( "SELECT id, library_name, library_url, client_id, client_secret FROM $templatehero_libs WHERE `id` = $active_library" );
            if ( $library ) {
           
                $remote_site_url  = $library[0]->library_url;
                $remote_site_name = $library[0]->library_name;
                

                $network_wide = get_site_option( 'template_hero_elementor_networkwide', '' );
              
                $public_key = $library[0]->client_id;
                if( $network_wide == 'on' ) {
                    update_site_option( 'template_hero_elementor_remote_url'.$library_id, esc_url_raw( $remote_site_url ) );
                    update_site_option( 'template_hero_elementor_public_key'.$library_id, $public_key );
                } else {
                    delete_transient( "token_network_wide".$library_id );
                    update_option( 'template_hero_elementor_remote_url'.$library_id, esc_url_raw( $remote_site_url ) );
                    update_option( 'template_hero_elementor_public_key'.$library_id, $public_key );
                }
                    

                $private_key = $library[0]->client_secret;

                if( !empty( $private_key ) && !empty( $public_key ) && !empty( $remote_site_url ) ) {

                    $user      = get_current_user();
                    $user_id   = get_current_user_id();
                    $issuedAt  = time();
                    $notBefore = apply_filters( 'template_hero_auth_not_before', $issuedAt, $issuedAt );
                    $expire    = apply_filters( 'template_hero_auth_expire', $issuedAt + ( DAY_IN_SECONDS * 7 ), $issuedAt );
                    
                   
                    $tokenArgs = array(
                        'iss' => trim( esc_url_raw( get_home_url() ) ),
                        'iat' => $issuedAt,
                        'nbf' => $notBefore,
                        'exp' => $expire,
                        'data' => array(
                            'client' => array(
                                'public_id' => $public_key,
                            ),
                        ),
                    );


                    try {

                        $token = Token::encode( apply_filters( 'template_hero_auth_token_before_sign', $tokenArgs, $user ), trim( $private_key ) );
                                        
                        if ( empty( $token ) || !$token ) {
                            if( $context == 'local' ) {
                                wp_safe_redirect( add_query_arg( 'token-updated', 'false', $_POST['_wp_http_referer'] ) );
                                exit;
                            } else {
                                return;
                            }

                        } else {

                            self::removeToken( $user_id, $library_id );
                            self::setTransient( $token, $user_id, $library_id );
                            $library_id   = $library[0]->id;
                            $library_name = $library[0]->library_name;
                            
                            if ( $network_wide == 'on' ) {
                                $libs     = get_site_option( "active_libraries_ids", array() );
                                //$libnames = get_site_option( "active_libraries_names", array() );
                                if( !in_array( $library_id, $libs ) ) {
                                    $libs[]       = $library_id;
                                }
                               // $libnames[]   = $library_name;
                                update_site_option( "active_libraries_ids", $libs );
                                //update_site_option( "active_libraries_names", $libnames );
                            } else {
                                $libs         = get_option( "active_libraries_ids", array() );
                               /// $libnames     = get_option( "active_libraries_names", array() );
                                if( !in_array( $library_id, $libs ) ) {
                                    $libs[]       = $library_id;
                                }
                                //$libnames[]   = $library_name;
                                update_option( "active_libraries_ids", $libs );
                               // update_option( "active_libraries_names", $libnames );
                            }
                            if( $context == 'local' ) {
                                wp_safe_redirect( add_query_arg( 'token-updated', 'true', $_POST['_wp_http_referer'] ) );
                                exit;
                            } else {
                                return;
                            }

                        }

                    } catch( \Exception $e ) {
                        if( $context == 'local' ) {
                            wp_safe_redirect( add_query_arg( 'token-updated', 'false', $_POST['_wp_http_referer'] ) );
                            exit;
                        } else {
                            return;
                        }
                    }

                    exit;
                }
                if( $context == 'local' ) {
                    wp_safe_redirect( add_query_arg( 'settings-updated', 'true', $_POST['_wp_http_referer'] ) );
                    exit;
                } else {
                    return;
                }
            }
        }
        if( $context == 'local' ) {
            wp_safe_redirect( add_query_arg( 'token-updated', 'false', $_POST['_wp_http_referer'] ) );
            exit;
        }
    }

    /**
     * Activates Library viva ajax supports multiple libraries
     * @since 1.1.4
     * @return void
     */
    public function the_activate_library() {
        
        $nonce   = $_REQUEST['th_create_token_security'];
        $user_id = $_POST['user_id']; 
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            
            echo json_encode([
                'success' => false,
                'message' => __( 'Security check', 'template-hero-elementor' ),
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
                    update_site_option( 'template_hero_elementor_remote_url'.$active_library, esc_url_raw( $remote_site_url ) );
                    update_site_option( 'template_hero_elementor_public_key'.$active_library, $public_key );
                } else {
                    delete_transient( "token_network_wide".$active_library );
                    update_option( 'template_hero_elementor_remote_url'.$active_library, esc_url_raw( $remote_site_url ) );
                    update_option( 'template_hero_elementor_public_key'.$active_library, $public_key );
                }
                    

                $private_key = $library[0]->client_secret;

             
                $library_ids_key = "active_libraries_ids";
                // $library_ids_key = apply_filters( 'the_libraries_ids_key', $library_ids_key );
                $library_names_key = "active_libraries_names";
                // $library_names_key = apply_filters( 'the_libraries_names_key', $library_names_key );
                if( $context == 'Deactivate' ) {
                    if ( $network_wide == 'on' ) {
                        $libs     = get_site_option( $library_ids_key, array() );
                        //$libnames = get_site_option( $library_names_key, array() );
                        //$libnames = \array_diff( $libnames , [$remote_site_name] );
                        $libs     = \array_diff( $libs , [$active_library] );
                        update_site_option( $library_ids_key, $libs );
                        //update_site_option( $library_names_key, $libnames );
                    } else {
                        $libs         = get_option( $library_ids_key, array() );
                        // $libnames     = get_option( $library_names_key, array() );
                        // $libnames = \array_diff( $libnames , [$remote_site_name] );
                        $libs     = \array_diff( $libs , [$active_library] );
                        update_option( $library_ids_key, $libs  );
                        //update_option( $library_names_key, $libnames  );
                    }
    
                    echo json_encode([
                        'success' => true,
                        'message' => __( 'Library deactivated successfully.', 'template-hero-elementor' )
                    ]);
                    exit;
                }
                if( !empty( $private_key ) && !empty( $public_key ) && !empty( $remote_site_url )  ) {

                    $user      = get_current_user();
                    $user_id   = get_current_user_id();
                    $issuedAt  = time();
                    $notBefore = apply_filters( 'template_hero_auth_not_before', $issuedAt, $issuedAt );
                    $expire    = apply_filters( 'template_hero_auth_expire', $issuedAt + ( DAY_IN_SECONDS * 7 ), $issuedAt );
                    $template_hero_elementor_options = get_option( 'template_hero_elementor_options', $template_hero_elementor_options );
                    
                
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

                        $token = Token::encode( apply_filters( 'template_hero_auth_token_before_sign', $tokenArgs, $user ), trim( $private_key ) );
                                        
                        if ( empty( $token ) || !$token ) {
                            echo json_encode([
                                'success' => false,
                                'message' => __( 'Token not updated. Library is not activated', 'template-hero-elementor' ),
                            ]);
                            exit;

                        } else {

                            self::removeToken( $user_id, $active_library );
                            self::setTransient( $token, $user_id, $active_library );
                            update_option( "active_library_id", $library[0]->id );
                            //update_option( "actived_library_name", $library[0]->library_name );
                            $library_id   = $library[0]->id;
                            $library_name = $library[0]->library_name;
                            if ( $network_wide == 'on' ) {
                                $libs     = get_site_option( "active_libraries_ids", array() );
                                //$libnames = get_site_option( "active_libraries_names", array() );
                                if( !in_array( $library_id, $libs ) ) {
                                    $libs[]       = $library_id;
                                }
                                //$libnames[]   = $library_name;
                                update_site_option( "active_libraries_ids", $libs );
                                //update_site_option( "active_libraries_names", $libnames );
                            } else {
                                $libs         = get_option( "active_libraries_ids", array() );
                                //$libnames     = get_option( "active_libraries_names", array() );
                                if( !in_array( $library_id, $libs ) ) {
                                    $libs[]       = $library_id;
                                }
                                //$libnames[]   = $library_name;
                                update_option( "active_libraries_ids", $libs );
                                //update_option( "active_libraries_names", $libnames );
                            }
                            echo json_encode([
                                'success' => true,
                                'message' => __( 'Library activated successfully.', 'template-hero-elementor' ),
                            ]);
                            exit;
                        }

                    } catch( \Exception $e ) {
                        echo json_encode([
                            'success' => false,
                            'message' => __( $e , 'template-hero-elementor' )
                        ]);
                        exit;
                    }
                    exit;
                }
                echo json_encode([
                    'success' => false,
                    'message' => __( 'Library is not activated.Empty Fields.', 'template-hero-elementor' ),
                ]);
                exit;
            }
            
        }
        echo json_encode([
            'success' => false,
            'message' => __( 'Library not activated. Empty library id.', 'template-hero-elementor' ),
        ]);
        exit;
    }

    /**
     * Save Plugin Settings Admin ajax formsa
     * @since 1.0.0
     * @return void
     */
    public function template_hero_elementor_admin_library_settings_save() {
        if( isset( $_POST['template_hero_active_library'] ) ) {
           
            $template_hero_elementor_options    = array();
            global $wpdb;
            $active_library    = isset( $_POST['template_hero_library_select'] ) ? $_POST['template_hero_library_select'] : '';
            $templatehero_libs = $wpdb->prefix . "templatehero_libraries";
            $library = $wpdb->get_results( "SELECT id, library_name, library_url, client_id, client_secret FROM $templatehero_libs WHERE `id` = $active_library" );
            if ( $library ) {
        
                $remote_site_url  = $library[0]->library_url;
                $remote_site_name = $library[0]->library_name;
                

                $network_wide = get_site_option( 'template_hero_elementor_networkwide', '' );
            
                $public_key = $library[0]->client_id;
                if( $network_wide == 'on' ) {
                    update_site_option( 'template_hero_elementor_current_url', esc_url_raw( get_bloginfo( 'url' ) ) );
                    update_site_option( 'template_hero_elementor_remote_url'.$active_library, esc_url_raw( $remote_site_url ) );
                    update_site_option( 'template_hero_elementor_public_key'.$active_library, $public_key );
                } else {
                    delete_transient( "token_network_wide".$active_library );
                    update_option( 'template_hero_elementor_remote_url'.$active_library, esc_url_raw( $remote_site_url ) );
                    update_option( 'template_hero_elementor_public_key'.$active_library, $public_key );
                }
                    
                

                $private_key = $library[0]->client_secret;

                if( !empty( $private_key ) && !empty( $public_key ) && !empty( $remote_site_url ) ) {

                    $user      = get_current_user();
                    $user_id   = get_current_user_id();
                    $issuedAt  = time();
                    $notBefore = apply_filters( 'template_hero_auth_not_before', $issuedAt, $issuedAt );
                    $expire    = apply_filters( 'template_hero_auth_expire', $issuedAt + ( DAY_IN_SECONDS * 7 ), $issuedAt );
                    
                    $tokenArgs = array(
                        'iss' => trim( esc_url_raw( get_home_url() ) ),
                        'iat' => $issuedAt,
                        'nbf' => $notBefore,
                        'exp' => $expire,
                        'data' => array(
                            'client' => array(
                                'public_id' => $public_key,
                            ),
                        ),
                    );


                    try{

                        $token = Token::encode( apply_filters( 'template_hero_auth_token_before_sign', $tokenArgs, $user ), trim( $private_key ) );
                                        
                        if ( empty( $token ) || !$token ) {

                            wp_safe_redirect( add_query_arg( 'token-updated', 'false', $_POST['_wp_http_referer'] ) );
                            exit;

                        } else {

                            self::removeToken( $user_id, $active_library );
                            self::setTransient( $token, $user_id, $active_library );
                         
                            $library_ids[]   = $library[0]->id;
                            $library_names[] = $library[0]->library_name;
                            if ( $network_wide == 'on' ) {
                                update_site_option( "actived_library_name", $library[0]->library_name );
                            }
                            wp_safe_redirect( add_query_arg( 'token-updated', 'true', $_POST['_wp_http_referer'] ) );
                            exit;

                        }

                    } catch( \Exception $e ) {

                        wp_safe_redirect( add_query_arg( 'token-updated', 'false', $_POST['_wp_http_referer'] ) );
                        exit;
                    }
                    exit;
                }

                wp_safe_redirect( add_query_arg( 'settings-updated', 'true', $_POST['_wp_http_referer'] ) );
                exit;
            }
            
            if ( $network_wide == 'on' ) {
                update_site_option( "active_libraries_names", $library_names );
            }
        }

        wp_safe_redirect( add_query_arg( 'token-updated', 'false', $_POST['_wp_http_referer'] ) );
        exit;
    }

    /**
     * Create Library on plugin activation
     * @since 1.0.0
     * @return void
     */
    public static function template_hero_elementor_create_activation_lib() {
        
        $template_hero_elementor_options    = array();

            
        $remote_site_name = 'Activation Library';
        if( th_elementor_pk == '' || th_elementor_token == '' ) {
            return;
        }
        $client_token= th_elementor_token;
        $private_key = th_elementor_pk;
        if( empty( $private_key ) || empty( $client_token ) ) {
            return;
        }
        try {
            $token = self::decodeToken( $client_token, $private_key );
        } catch ( Exception $e ) {
            return;
        }
        if ( !$token ) {
           return;
        }
        $public_key      = $token->data->client->public_id;
        $remote_site_url = $token->iss; 
        if( empty( $private_key ) || empty( $public_key ) || empty( $remote_site_url ) || empty( $remote_site_name ) ) {
            
            $result = 0;
        } else {
            $result = library::th_create_library(
                get_current_user_id(), 
                get_current_blog_id(), 
                sanitize_text_field( $remote_site_name ), 
                $remote_site_url, 
                $public_key, 
                sanitize_text_field( $private_key ) 
            ); 
        }
        
        if( $result != 0 && $result != 2 && !is_wp_error( $result ) && !class_exists('\\The_WP_Ultimo\\The_Wu_Integration') ) {
            self::template_hero_elementor_admin_activate_library(  $result, 'remote' );
        }
    }

    /**
     * Save Plugin Settings Admin ajax formsa
     * @since 1.0.0
     * @return void
     */
    public function template_hero_elementor_admin_settings_save() {
        if( isset( $_POST['template_hero_elementor_settings_submit'] ) ) {

            $template_hero_elementor_options    = array();

            
            $remote_site_name = isset( $_POST['template_hero_elementor_remote_name'] ) ? $_POST['template_hero_elementor_remote_name'] : '';
            
            $make_live = isset( $_POST['template_hero_elementor_make_library'] ) ? $_POST['template_hero_elementor_make_library'] : '';

            $network_wide = isset( $_POST['template_hero_elementor_networkwide'] ) ? $_POST['template_hero_elementor_networkwide'] : '';
            update_site_option('template_hero_elementor_networkwide', sanitize_text_field( $network_wide ) );
            
            $client_token= isset( $_POST['template_hero_elementor_remote_token'] ) ? $_POST['template_hero_elementor_remote_token'] : '';
            $private_key = isset( $_POST['template_hero_elementor_private_key'] ) ? $_POST['template_hero_elementor_private_key'] : '';
            if( empty( $private_key ) || empty( $client_token ) ) {
                wp_safe_redirect( add_query_arg( 'library-created', 'false', $_POST['_wp_http_referer'] ) );
                exit;
            }
            try {
                $token = self::decodeToken( $client_token, $private_key );
            } catch ( Exception $e ) {
                wp_safe_redirect( add_query_arg( 'library-created', 'false', $_POST['_wp_http_referer'] ) );
                exit;
            }
            if ( !$token ) {
                wp_safe_redirect( add_query_arg( 'library-created', 'false', $_POST['_wp_http_referer'] ) );
                exit;
            }
            $public_key      = $token->data->client->public_id;
            $remote_site_url = $token->iss; 
            if( empty( $private_key ) || empty( $public_key ) || empty( $remote_site_url ) || empty( $remote_site_name ) ) {
                
                $result = 0;
            } else {
                $result = library::th_create_library(
                    get_current_user_id(), 
                    get_current_blog_id(), 
                    sanitize_text_field( $remote_site_name ), 
                    $remote_site_url, 
                    $public_key, 
                    sanitize_text_field( $private_key ) 
                ); 
            }
           
            if( $result != 0 && $result != 2 && !is_wp_error( $result ) && !class_exists('\\The_WP_Ultimo\\The_Wu_Integration') ) {
                self::template_hero_elementor_admin_activate_library(  $result, 'local' );
            }
            if ( $result == 1 ) {
                wp_safe_redirect( add_query_arg( 'library-created', 'true', $_POST['_wp_http_referer'] ) );
                exit;
            } elseif( $result == 2 ) {
                wp_safe_redirect( add_query_arg( 'library-created', 'exits', $_POST['_wp_http_referer'] ) );
                exit;
            } else {
                wp_safe_redirect( add_query_arg( 'library-created', 'false', $_POST['_wp_http_referer'] ) );
                exit;
            }
        }
    }

    /**
     * Sets Transient for Token
     * @since 1.0.0
     * @param [string] $token
     * @param [integer] $user_id
     * @return void
     */
    public static function setTransient( $token, $user_id, $active_library ) {
        self::removeToken( $user_id,  $active_library );
        $network_wide         = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
        if( $network_wide !== 'on' ) {
            $transient = set_transient( "token_".$user_id.$active_library,  $token, time() + ( DAY_IN_SECONDS * 7 ) );
        } else {
            $transient = set_transient( "token_network_wide".$active_library,  $token, time() + ( DAY_IN_SECONDS * 7 ) );
        }

        return $transient;
    }


    /**
     * Encodes Token
     * @since 1.0.0
     * @param [type] $token
     * @param [type] $secret
     * @return void
     */
    public static function encodeToken( $token, $secret ) {
        $user  = get_current_user();

        $token = Token::encode( apply_filters( 'template_hero_auth_token_before_sign', $token, $user ), $secret );
        
        return $token;
    }

    /**
     * Decodes Token
     * @since 1.0.0
     * @param [type] $token
     * @param [type] $secret
     * @return void
     */
    public static function decodeToken( $token, $secret ) {

        //Token::$leeway = 60;
        $decoded = Token::decode( $token, $secret, array('HS256') );

        return $decoded;
    }

    /**
     * Removes Token
     * @since 1.0.0
     * @param string $user_id
     * @return void
     */
    public static function removeToken( $user_id = '', $active_library ) {
        $network_wide         = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
        if( $network_wide !== 'on' ) {
            if( $user_id == '' ) { $user_id = get_current_user_id(); }
            $transient = delete_transient( "token_".$user_id.$active_library );
            $admin_ids = self::get_admin_user_ids();
			foreach( $admin_ids as $id  ) {
				$transient = delete_transient( "token_".$id.$active_library  );
            }
            $transient = true;

        } else {
            $transient = delete_transient( "token_network_wide".$active_library );
        }

        return $transient;
    }

    /**
	 * Get Admin User id's
	 * @since 1.1.3
	 * @return void
	 */
	public static function get_admin_user_ids() {
		//Grab wp DB
		global $wpdb;
		//Get all users in the DB
		$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");
	
		//Blank array
		$adminArray = array();
		//Loop through all users
		foreach ( $wp_user_search as $userid ) {
			//Current user ID we are looping through
			$curID = $userid->ID;
			//Grab the user info of current ID
			$curuser = get_userdata($curID);
			//Current user level
			$user_level = $curuser->user_level;
			//Only look for admins
			if($user_level >= 8) {//levels 8, 9 and 10 are admin
				//Push user ID into array
				$adminArray[] = $curID;
			}
		}

		return $adminArray;
	}


    /**
     * Removes Transient For current user token
     * @since 1.0.0
     */
    public function removeTokenTransient() {

        $user_id    = $_REQUEST['user_id'];
        $library_id = $_REQUEST['library_id'];
        if( empty( $user_id ) || !$user_id == get_current_user_id() ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }

        if( empty( $library_id )  ) {
            die( __( 'Security check', 'template-hero-elementor' ) );
        }
 
        $nonce = $_REQUEST['th_create_token_security'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            die( __( 'Security check', 'template-hero-elementor' ) ); 
        }

        $network_wide         = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
        if( $network_wide !== 'on' ) {
            if( $user_id == '' ) { $user_id = get_current_user_id(); }
            self::removeToken( $user_id, $library_id );
            $transient = true;

        } else {
            $transient = delete_transient( "token_network_wide".$library_id );
        }

        //send message if token refreshes and saves
        if( $transient ):
            echo json_encode([
                'success' => true,
                'message' => __( 'Token removed successfully.', 'template-hero-elementor' ),
            ]);

            else:
                echo json_encode([
                    'success' => false,
                    'message' => __( 'No token to remove.', 'template-hero-elementor' ),
                ]);
        endif;
        
        wp_die();
    }

    /**
     * Checks for characters conditions
     *
     * @param [string] $string
     * @return void
     */
    private static function checkForCharacterCondition( $string ) {
        return ( bool ) preg_match('/(?=.*([A-Z]))(?=.*([a-z]))(?=.*([0-9]))(?=.*([$\!@#\$%\^&\*]))/', $string );
    }

    /**
     * Creates Token
     *
     * @param [string] $private_key
     * @param [string] $public_key
     * @param [WP_USER] $user
     * @return string token/exception
     */
    public function th_create_token( $private_key, $public_key, $user ) {
        $template_hero_elementor_options = get_option( 'template_hero_elementor_advance_options', array() );
        $allow_dev         = !empty( $template_hero_elementor_options['template_hero_elementor_allowed_extensions'] ) ? $template_hero_elementor_options['template_hero_elementor_allowed_extensions'] : 'no';
        if( $allow_dev != 'on' ) {
            return;
        }

        if ( empty( $user ) && !is_object( 'WP_User' ) ) {
            $user = get_current_user();
        }

        if ( empty( $public_key ) ) {
            return;
        }

        if ( empty( $private_key ) ) {
            return;
        }
        $user      = get_current_user();
        $user_id   = get_current_user_id();
        $issuedAt  = time();
        $notBefore = apply_filters( 'template_hero_auth_not_before', $issuedAt, $issuedAt );
        $expire    = apply_filters( 'template_hero_auth_expire', $issuedAt + ( DAY_IN_SECONDS * 7 ), $issuedAt );
        $tokenArgs = array(
            'iss' => trim( esc_url_raw( get_bloginfo( 'url' ) ) ),
            'iat' => $issuedAt,
            'nbf' => $notBefore,
            'exp' => $expire,
            'data' => array(
                'client' => array(
                    'public_id' => $public_key,
                ),
            ),
        );
        try{
            $token = Token::encode( apply_filters( 'template_hero_auth_token_before_sign', $tokenArgs, $user ), $private_key );
            
            return $token;
        } catch( \Exception $e ) {
            
            return $e;
        } 
    }
}
