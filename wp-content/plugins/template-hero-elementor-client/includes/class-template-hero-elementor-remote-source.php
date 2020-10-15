<?php

/**
 * Where we extend and override Elemetors template library source. We set our URLs here ( from user settings like we discussed)
 * Elementor template library remote source.
 *
 * Elementor template library remote source handler class is responsible for
 * handling remote templates from Elementor.com servers.
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/remote_source
 * @author     J Hanlon | Waas Hero <info@waashero.com>
 * @since 1.0.0
 */
namespace Elementor\TemplateLibrary;

use Elementor\Plugin;
use \Firebase\JWT\JWT as Token;
use TemplateHero\Plugin_Client\Api\Tokens as Admin_Info;
use TemplateHero\Plugin_Client\Admin as Admin;
use Elementor\TemplateLibrary\Source_Local as Local; 
use Elementor\Core\Settings\Manager as SettingsManager;

class Template_Hero_Remote_Source extends Source_Base {

	/**
	 * New library option key.
	 */
	const LIBRARY_OPTION_KEY = 'custom_remote_info_library';

	/**
	 * Timestamp cache key to trigger library sync.
	 */
	const TIMESTAMP_CACHE_KEY = 'custom_remote_update_timestamp';
	public static $public_id='';
	
	/**
	 * API info URL.
	 *
	 * Holds the URL of the info API.
	 *
	 * @access public
	 * @static
	 *
	 * @var string API info URL.
	 */
	public static $api_info_url = '';

	/**
	 * API get template content URL.
	 *
	 * Holds the URL of the template content API.
	 *
	 * @access private
	 * @static
	 *
	 * @var string API get template content URL.
	 */
	private static $api_get_template_content_url = '';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		/**
		 * initialize urls with our custom urls
		 * 
		 */
	
		$this->hooks();
	}

	public function hooks() {
		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.2.8', '>' ) ) {
			add_action( 
				'elementor/ajax/register_actions', 
				array( $this, 'th_hero_elementor_register_ajax_actions' ), 
				20 
			);

		} else {
			add_action( 
				'wp_ajax_elementor_get_template_data', 
				array( $this, 'force_wh_template_source' ), 
				0
			);
		}
		
	}

	
	
	/**
	 * Return template data insted of elementor template.
	 * @since  1.0.0
	 */
	public function force_wh_template_source() {

		if ( empty( $_REQUEST['template_id'] ) ) {
			
			return;
		}

		if ( false === strpos( $_REQUEST['template_id'], 'wh' ) ) {

			return;
		}

		$_REQUEST['source'] = 'remote';
	}
	/**
	 * Register AJAX actions
	 * @since  1.0.1
	 */
	public function th_hero_elementor_register_ajax_actions( $ajax ) {
		if ( ! isset( $_REQUEST['actions'] ) ) {
			return;
		}
		$ajax->register_ajax_action( 'get_library_data', array( $this, 'get_library_data_with_categories' ) );
		$actions = json_decode( stripslashes( $_REQUEST['actions'] ), true );
		$data    = false;

		foreach ( $actions as $id => $action_data ) {
			if ( ! isset( $action_data['get_template_data'] ) ) {
				$data = $action_data;
			}
		}

		if ( ! $data ) {
			return;
		}

		if ( ! isset( $data['data'] ) ) {
			return;
		}

		$data = $data['data'];

		if ( empty( $data['template_id'] ) ) {
			return;
		}

		if ( false === strpos( $data['template_id'], 'template-hero-elementor' ) ) {
			return;
		}

		$ajax->register_ajax_action( 'get_template_data', array( $this, 'get_data' ) );
	}

	/**
	 * Get template export link.
	 *
	 * Retrieve the link used to export a single template based on the template
	 * ID.
	 *
	 * @since 1.2.1
	 * @access private
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return string Template export URL.
	 */
	private static function get_export_link( $template_id ) {
		// TODO: BC since 2.3.0 - Use `$ajax->create_nonce()`
		/** @var \Elementor\Core\Common\Modules\Ajax\Module $ajax */
		// $ajax = Plugin::$instance->common->get_component( 'ajax' );

		return add_query_arg(
			[
				'action'         => 'elementor_library_direct_actions',
				'library_action' => 'export_template',
				'source'         => 'local',
				'_nonce'         => wp_create_nonce( 'elementor_ajax' ),
				'template_id'    => $template_id,
			],
			admin_url( 'admin-ajax.php' )
		);
	}

	
	/**
	 * Get library data.
	 *
	 * Retrieve the library data.
	 *
	 * @since  1.1.4 
	 * @access public
	 *
	 * @param array $args Library arguments.
	 *
	 * @return array Library data.
	 */
	public function get_library_data_with_categories( array $args ) {
		$library_data = self::get_library_data();
		
		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();
		
		return [
			'templates' => self::get_items(),
			'config'    => $library_data['types_data'],
		];
	}

	/**
	 * Get remote template ID.
	 *
	 * Retrieve the remote template ID.
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string The remote template ID.
	 */
	public function get_id() {
		
		return 'remote';
	}

	/**
	 * Get remote template title.
	 *
	 * Retrieve the remote template title.
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string The remote template title.
	 */
	public function get_title() {

		return __( 'Waas Hero Templates', 'template-hero-elementor' );
	}

	/**
	 * Register remote template data.
	 * @since 1.0.0
	 * Used to register custom template data like a post type, a taxonomy or any
	 * other data.
	 *
	 * @access public
	 */
	public function register_data() {
	}

	/**
	 * Get remote templates.
	 *
	 * Retrieve remote templates from Elementor.com servers.
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Optional. Nou used in remote source.
	 *
	 * @return array Remote templates.
	 */
	public function get_items( $args = [] ) {

		$library_data = self::get_library_data();
		$templates = [];

		if ( ! empty( $library_data['templates'] ) ) {
			foreach ( $library_data['templates']  as $template_data ) {
				$templates[] = $this->prepare_template( $template_data );
			}
		}

		return $templates;
	}

	/**
	 * Get templates data.
	 *
	 * Retrieve the templates data from a remote server.
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data update or
	 *                                     not. Default is false.
	 *
	 * @return array The templates data.
	 */
	public static function get_library_data( $force_update = false ) {
		self::get_info_data( $force_update );

		$library_data = get_option( self::LIBRARY_OPTION_KEY );

		if ( empty( $library_data ) ) {
			return [];
		}

		return $library_data;
	}

	/**
	 * Get info data.
	 *
	 * This function notifies the user of upgrade notices, new templates and contributors.
	 * @since  1.0.0
	 * @access private
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data retrieval or
	 *                                     not. Default is false.
	 *
	 * @return array|false Info data, or false.
	 */
	private static function get_info_data( $force_update = false ) {

		$elementor_update_timestamp = get_option( '_transient_timeout_elementor_remote_info_api_data_' . ELEMENTOR_VERSION );
		$elementor_update_timestamp = apply_filters( "elementor_library_update_timestamp", $elementor_update_timestamp );
		$update_timestamp           = get_transient( self::TIMESTAMP_CACHE_KEY );
		$update_timestamp           = apply_filters( "elementor_update_library_cache_key", $update_timestamp );
		$info_data_libraries = [];
		$lib_templates       = [];
		
		if ( $force_update || ! $update_timestamp || $update_timestamp != $elementor_update_timestamp ) {
			$info_data_libraries = array_merge_recursive( $info_data_libraries, self::get_all_local_templates() );
			$timeout   = ( $force_update ) ? 25 : 8;
			$library_ids_key = "active_libraries_ids";
			$library_ids_key = apply_filters( 'the_libraries_ids_key', $library_ids_key );
			$library_names_key = "active_libraries_names";
			$library_names_key = apply_filters( 'the_libraries_names_key', $library_names_key );
			$id_token  = '';
			$network_wide   = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
			if ( $network_wide == 'on' ) {
								
				$active_libraries    =  get_site_option( $library_ids_key, array() );
				global $wpdb;

				switch_to_blog( 1 );
				$templatehero_libs = $wpdb->prefix . "templatehero_libraries";

				$libraries         = $wpdb->get_results( "SELECT id, library_name, library_url, client_id FROM $templatehero_libs" );
				foreach( $libraries as $library ) {
					$libs_array[] = $library->id;
				}
				
				foreach( $active_libraries as $library ) {
					if( !in_array( $library, $libs_array ) ) {
						Admin_Info::the_delete_library_meta( $library, $library_ids_key, $library_names_key );
					}
				
				}
				restore_current_blog();
				$active_libraries    =  get_site_option( $library_ids_key, array() );
			} else {
				global $wpdb;
				$active_libraries    =  get_option( $library_ids_key, array() );
				$templatehero_libs = $wpdb->prefix . "templatehero_libraries";

				$libraries         = $wpdb->get_results( "SELECT id, library_name, library_url, client_id FROM $templatehero_libs" );
				foreach( $libraries as $library ) {
					$libs_array[] = $library->id ;
				}
				foreach( $active_libraries as $library ) {
					if( !in_array( $library, $libs_array ) ) {
						Admin_Info::the_delete_library_meta( $library, $library_ids_key, $library_names_key );
					}
				}
				$active_libraries    =  get_option( $library_ids_key, array() );
			}
			
			foreach( $active_libraries as $active_library ) {
				if( $network_wide == 'on' ) {
                   
                    $api_info_url = get_site_option( 'template_hero_elementor_remote_url'.$active_library, '');
					$public_id    = get_site_option( 'template_hero_elementor_public_key'.$active_library, '' );
                } else {
            
                    $api_info_url = get_option( 'template_hero_elementor_remote_url'.$active_library, '' );
                    $public_id    = get_option( 'template_hero_elementor_public_key'.$active_library, '' );
                }
				if( $api_info_url ) {
					
					$api_info_url              = $api_info_url.'/index.php?rest_route=/waashero/v0/templates';
				
					$remote_site_content_url   = $api_info_url.'/%d';
				}
				$response  = wp_remote_get( $api_info_url, [
					'timeout' => $timeout,
					'body' => [
						// Which API version is used.
						'api_version' => ELEMENTOR_VERSION,
						// Which language to return.
						'site_lang'   => get_bloginfo( 'language' ),
						'_token'      => $id_token,
						'public_id'   => $public_id
					],
				] );

				$response_code = (int) wp_remote_retrieve_response_code( $response );
				if ( 403 == $response_code || 402 == $response_code || 404 == $response_code ) {
					return false;
				}
		

				if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
					set_transient( self::TIMESTAMP_CACHE_KEY, [], HOUR_IN_SECONDS );

					return false;
				}

				$info_data = json_decode( wp_remote_retrieve_body( $response ), true );
				$info_data = apply_filters( "template_hero_library_info_data", $info_data  );

				if ( empty( $info_data ) || ! is_array( $info_data ) ) {
					set_transient( self::TIMESTAMP_CACHE_KEY, [],  HOUR_IN_SECONDS );

					return false;
				}
				$lib_templates[$active_library] = self::get_template_ids( $info_data['library'] );
				if ( empty( $info_data_libraries ) ) {
					$info_data_libraries = $info_data;
				} else {
					$info_data_libraries = array_merge_recursive( $info_data_libraries, $info_data );
				}
			}

			if ( isset( $info_data_libraries['library'] ) ) {
				update_option( self::LIBRARY_OPTION_KEY, $info_data_libraries['library'] );
			} 
			delete_transient( 'library_templates' );
			set_transient( 'library_templates', $lib_templates, HOUR_IN_SECONDS  );
			set_transient( self::TIMESTAMP_CACHE_KEY, $elementor_update_timestamp,  HOUR_IN_SECONDS );
		}
		
		return $info_data_libraries;
	}

	/**
	 * Returns Temmplate ids
	 * @since  1.1.5 
	 * @param [array] $library
	 * @return array
	 */
	private static function get_template_ids( $library ) {
		$ids = [];
		foreach( $library['templates'] as $data ) {
			$ids[] = $data['id'];
		}

		return $ids;
	}

	/**
	 * Get remote template.
	 *
	 * Retrieve a single remote template from Elementor.com servers.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param int $template_id The template ID.
	 *
	 * @return array Remote template.
	 */
	public function get_item( $template_id ) {

		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	/**
	 * Save remote template.
	 *
	 * Remote template from Elementor.com servers cannot be saved on the
	 * database as they are retrieved from remote servers.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $template_data Remote template data.
	 *
	 * @return \WP_Error
	 */
	public function save_item( $template_data ) {

		return new \WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	/**
	 * Update remote template.
	 *
	 * Remote template from Elementor.com servers cannot be updated on the
	 * database as they are retrieved from remote servers.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $new_data New template data.
	 *
	 * @return \WP_Error
	 */
	public function update_item( $new_data ) {

		return new \WP_Error( 'invalid_request', 'Cannot update template to a remote source.' );
	}

	/**
	 * Delete remote template.
	 *
	 * Remote template from Elementor.com servers cannot be deleted from the
	 * database as they are retrieved from remote servers.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error
	 */
	public function delete_template( $template_id ) {

		return new \WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}

	/**
	 * Export remote template.
	 *
	 * Remote template from Elementor.com servers cannot be exported from the
	 * database as they are retrieved from remote servers.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error
	 */
	public function export_template( $template_id ) {

		return new \WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from Elementor.com servers.
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $args    Custom template arguments.
	 * @param string $context Optional. The context. Default is `display`.
	 *
	 * @return array Remote Template data.
	 */
	public function get_data( array $args, $context = 'display' ) {

		$data = self::get_template_content( $args['template_id'] );
	
		
		if ( is_wp_error( $data ) ) {

			return $data;
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );
		
		$post_id  = $args['editor_post_id'];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}
		
		return $data;
	}

	/**
     * Returns all templates from local site
     *
     * 
     * @return void
     */
    public static function get_all_local_templates() {

        $idsElementorArray = [];
        if ( class_exists( '\\Elementor\\Plugin' ) ) {

            $args = array(
                'post_type'=> 'elementor_library',
                'order'    => 'ASC',
                'numberposts' => -1,
                'post_status' => 'publish'
            );              
            
            $templates = get_posts( $args );
            // make array of ids and titles
            $idsElementor = array();
            $new_cat    = [];
            $categories_default = [];
            foreach ( $templates as $template ) {
    
                $meta     = get_post_meta( $template->ID );
                $type     = $meta['_elementor_template_type'][0];
                $sub_type = $type;
                $categories = get_the_terms( $template->ID, 'elementor_library_category' );
        
                if( $type != "kit" || $template->post_title != "Default Kit" ) {
                    //TODO: Add a way to select template order, and add our own categories.
                    if( $type == 'th_elementor_custom' ) {
                        $sub_type = $type;
                        if ( isset( $categories[0] ) ) {
                            $sub_type   = $categories[0]->name;
                            $new_cat[]  = $categories[0]->name;
                        }
                    } elseif( $type != 'page'  ) {
                        if ( isset( $categories[0] ) ) {
                            $sub_type   = $categories[0]->name;
                            $new_cat[]  = $categories[0]->name;
                        } else {
                            $sub_type = 'category';
                        }

                        $type     = 'block';
                    } 
					$page = SettingsManager::get_settings_managers( 'page' )->get_model( $template->ID );

					$page_settings = $page->get_data( 'settings' );
                    $time_stamp = strtotime( $template->post_modified );
                    $thumbnail  = '';
                    if( has_post_thumbnail( $template ) ) {
                        $thumbnail = get_the_post_thumbnail_url( $template->ID );
                    } else {
                        $wp_filetype = wp_check_filetype( TEMPLATE_HERO_ELEMENTOR_ASSETS_URL ."images/no-preview.jpg", null );
                        if( $wp_filetype['ext'] == 'jpg' ) {
                            $thumbnail = TEMPLATE_HERO_ELEMENTOR_ASSETS_URL ."images/no-preview.jpg";
                        }
                    }
                    $time = ''.strtotime( $template->post_modified ).'';
					$url  = get_bloginfo( 'url' );
					$date = strtotime( $template->post_date );
                    $idsElementor[] = array(
                        "id"               => ''.$template->ID.'',
                        "title"            => $template->post_title,
						"thumbnail"        => $thumbnail,
						"date"             => $date,
						"tmpl_created"     => $time,
						'human_date'       => date_i18n( get_option( 'date_format' ), $date ),
                        "author"           => get_the_author_meta( 'nicename', $template->post_author ),
						"export_link"      => self::get_export_link( $template->ID ),
						"type"             => $type,
						'url'              => get_permalink( $template->ID ),
                        "tags"             => [],
                        'hasPageSettings'  => ! empty( $page_settings ),
						"source"           => "local"
                    );
                }
            }
            wp_reset_postdata(); // just reset since we made new query
            $template_hero_elementor_options = get_option( 'template_hero_elementor_advance_options', array() );
            $default_cat                     = !empty( $template_hero_elementor_options['template_hero_elementor_default_cat'] ) ? $template_hero_elementor_options['template_hero_elementor_default_cat'] : '';
            $time_stamp  = get_option( '_waashero_elementor_installed_time', time() );
            if ( $default_cat == 'on' ) {
                $categories     = array_merge( $categories_default,  array_unique( $new_cat ) );
            } else {
                $categories = array_unique( $new_cat );
            }
            $idsElementorArray = array(
                'timestamp'       => $time_stamp,
                'upgrade_notice'  => array(
                    'version'     => TEMPLATE_HERO_ELEMENTOR_LIBRARY_VERSION,
                    'message'     => __( 'Template Library Update Available!', 'template-hero-elementor' ),
                    'update_link' => 'link to our update button'
                ),
                'library' => array(
                    'types_data'=> array(
                        'block' => array(
                            'categories'=> []
                        ),
                        'th_elementor_custom' => array(
                            'categories'=> []
                        ),
                        'popup' => array(
                            'categories'=> []
                        )
                    ),
                    'templates'=> $idsElementor,
                )
            );
        }
        do_action( 'template_hero_elementor_before_sending_local_templates_array', $idsElementorArray ); 
        return $idsElementorArray;
    } 

	/**
	 * Gives library id of a template
	 * @since  1.1.5 
	 * @param [integer] $temp_id
	 * @return void
	 */
	private static function get_library_for_template( $temp_id ) {
		$data = get_transient( 'library_templates' );
		foreach( $data as $lib_id => $templates ) {
			if( in_array( $temp_id, $templates ) ) {
				return $lib_id; 
			}
		}
		
		return 0;
	}
	/**
	 * Get template content.
	 *
	 * Retrieve the templates content received from a remote server.
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 * @param int $template_id The template ID.
	 *
	 * @return array The template content.
	 */
	public static function get_template_content( $template_id ) {
		$active_library    = self::get_library_for_template( $template_id  );
		$id_token          = self::get_token( $active_library );
		$license_key       = trim( get_option( '_template_hero_license_key' ) );
		$license_check     = get_site_transient( 'hero_client_elementor_license_active' );
		
		if (  $license_check  != 1 ) {
			if( is_multisite() && ( $license_key == '' || empty( $license_key ) ) ) {
				switch_to_blog( 1 );
				$license_key       = trim( get_option( '_template_hero_license_key' ) );
				restore_current_blog();
			}
			$active_license    = Admin::check_for_license ( $license_key ); 
			
			if( $active_license['status'] == 'error' )  {

				return new \WP_Error( 'license_error', sprintf( '%s', 'Please activate your license to get feature updates, premium support and unlimited access to the template library.'.$license_key ) );	
				
			} else {
				set_site_transient( 'hero_client_elementor_license_active', 1, 7 * DAY_IN_SECONDS  );
			}
		}
		
		$network_wide   = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
		if( $network_wide == 'on' ) {
                   
			$api_info_url = get_site_option( 'template_hero_elementor_remote_url'.$active_library, '' );
			$public_id    = get_site_option( 'template_hero_elementor_public_key'.$active_library, '' );
		} else {
	
			$api_info_url = get_option( 'template_hero_elementor_remote_url'.$active_library, '' );
			$public_id    = get_option( 'template_hero_elementor_public_key'.$active_library, '' );
		}
		
		if( $api_info_url ) {
			
			$api_info_url          = $api_info_url.'/index.php?rest_route=/waashero/v0/templates';
			
			$api_get_template_content_url  = $api_info_url.'/%d';
		}
		
		if ( $id_token && $api_get_template_content_url ) {

			$url = sprintf( $api_get_template_content_url, $template_id );
			$url = apply_filters( 'elementor/api/get_templates/url', $url, $template_id );


			/**
			 * API: Template body args.
			 *
			 * Filters the body arguments send with the GET request when fetching the content.
			 *
			 * @param array $body_args Body arguments.
			 */

			$body_args = apply_filters( 'elementor/api/get_templates/body_args', [
				'api_version' => ELEMENTOR_VERSION,
				'site_lang'   => get_bloginfo( 'language' ),
				'_token'      => $id_token,
				'public_id'   => $public_id
			] );

			$response  = wp_remote_post( $url, [
				'timeout' => 70,
				'body'    => $body_args,
			] );
		
			$response_code    = (int) wp_remote_retrieve_response_code( $response );
			$response_code    = apply_filters( "template_hero_get_temp_data_response_code", $response_code );
			
			$template_content = json_decode( wp_remote_retrieve_body( $response ), true );
			$template_content = apply_filters( "template_hero_get_template_content", $template_content );
		
			if ( 200 !== $response_code ) {

				return new \WP_Error( 'template_data_error', sprintf( '%s', $template_content['message'].$response_code  ) );	

			}
			
			if ( isset( $template_content['error'] ) ) {
				return new \WP_Error( 'response_error', $template_content['error'] );
			}
	
			if ( empty( $template_content['data'] ) && empty( $template_content['content'] ) ) {
				return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
			}

			return $template_content;

		} else {

			return new \WP_Error( 'response_code_error', 'Api Error: No valid tokens found.' );
		}
	}
	
	/**
	 * Get saved token or create a new one 
	 * @since  1.0.0 
	 * @param [string] $id_token
	 * @return void
	 */
	private static function get_token( $lib_id ) {
		$network_wide         = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
		if ( $network_wide == 'on' ) {
			switch_to_blog( 1 );
			$token = get_transient( "token_network_wide".$lib_id );
			restore_current_blog();
			return $token;
		}
		
		$token = get_transient( "token_".get_current_user_id().$lib_id );
		if ( ! $token ) {
			$admin_ids = Admin_Info::get_admin_user_ids();
			foreach( $admin_ids as $id  ) {
				$token = get_transient( "token_".$id.$lib_id  );
				if( $token ) {
					break;
				}
			}
		}

		return $token;
	}

	/**
	 * @access private
	 * @since 1.0.0
	 */
	private function prepare_template( array $template_data ) {
		$favorite_templates = $this->get_user_meta( 'favorites' );
		if( isset( $template_data['source'] ) ) {
			$source = 'local';
		} else {
			$source = $this->get_id();
		}
		return [
			'template_id' => $template_data['id'],
			'source'      => $source,
			'type'        => $template_data['type'],
			'subtype'     => isset( $template_data['subtype'] ) ? $template_data['subtype'] : '',
			'human_date'  => isset( $template_data['human_date']  ) ? $template_data['human_date']  : '',
			'title'       => $template_data['title'],
			'thumbnail'   => $template_data['thumbnail'],
			'date'        => $template_data['tmpl_created'],
			'author'      => $template_data['author'],
			'tags'        => json_decode( $template_data['tags'] ),
			'export_link'  => isset( $template_data['export_link']  ) ? $template_data['export_link']  : '',
			'url'         => $template_data['url'],
			'favorite'    => ! empty( $favorite_templates[ $template_data['id'] ] ),
			'isPro'       => ( '1' === $template_data['is_pro'] ),
			'popularityIndex' => (int) $template_data['popularity_index'],
			'trendIndex'      => (int) $template_data['trend_index'],
			'hasPageSettings' => ( '1' === $template_data['has_page_settings'] ),
		];
	}
}
