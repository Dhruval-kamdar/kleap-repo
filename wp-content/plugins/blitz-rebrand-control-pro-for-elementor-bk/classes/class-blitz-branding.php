<?php
/**
 * Handles logic for the plugin branding.
 *
 * @since 1.0
 */
class Elementor_blitzlabel {

    /**
	 * Holds the arguments for plugin branding.
	 *
	 * @since 1.0
	 * @var array
	 */
    static public $default_data = array();

    /**
     * Holds the plugin branding data.
     *
     * @since 1.0
     * @var array
     */
    static public $branding = array();

    /**
	 * Initializes the branding settings.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function init()
    {
        self::$default_data = array(
            'plugin_name'       	=> '',
            'plugin_desc'       	=> '',
            'plugin_author'     	=> '',
            'plugin_uri'        	=> '',
            'edit_with_text'    	=> '',
            'disable_pro'       	=> 'off',
            'primary_color'     	=> '',
            'secondary_color'   	=> '',
            'hide_logo'         	=> 'off',
            'hide_external_links'	=> 'off',
            'hide_descriptions'		=> 'off',
            'hide_settings'     	=> 'off',
            'hide_plugin'       	=> 'off',
			'hide_el_plugin'    	=> 'off',
			'hide_admin_menu'		=> 'off',
			'hide_wl_admin_menu'	=> 'off',
            'hide_my_templates'		=> 'off',
            'hide_settings_page'	=> 'off',
			'hide_custom_fonts'    	=> 'off',
			'hide_custom_icons'    	=> 'off',
			'hide_role_manager'		=> 'off',
			'hide_category_title'	=> 'off',
            'hide_tools'    		=> 'off',
            'hide_sys_info'    		=> 'off',
            'hide_knowledge_base'	=> 'off',
            'hide_license_page'    	=> 'off',
            'hide_getting_started' => 'off',
            'hide_library_pages'    => 'off',
            'hide_library_blocks'   => 'off',
            'hide_library_pro_templates'    	=> 'off',
            'hide_library_export_template'    	=> 'off',
            'hide_library_import_template'    	=> 'off',
            'hide_notices'    	=> 'off',
            'multisite_hide_settings' => 'off'
        );

		add_action( 'wp_head', 					__CLASS__ . '::frontend_scripts' );
        add_action( 'admin_head', 				__CLASS__ . '::branding_styles' );
		add_action( 'elementor/editor/before_enqueue_scripts', __CLASS__ . '::branding_styles' );
		add_action( 'elementor/frontend/after_enqueue_styles', __CLASS__ . '::branding_styles' );
		add_action( 'admin_menu',				__CLASS__ . '::admin_menu', 999 );
		add_filter( 'all_plugins', 				__CLASS__ . '::plugin_branding', 10, 1 );
		add_action( 'plugins_loaded',			__CLASS__ . '::plugin_meta' );
		add_filter( 'gettext', 					__CLASS__ . '::update_label', 20, 3 );
		//~ add_action( 'wp_enqueue_scripts',		__CLASS__ . '::my_admin_footer_function',100 );
		//~ add_action( 'admin_enqueue_scripts',		__CLASS__ . '::my_admin_footer_function',100 );
		add_filter( 'theme_elementor_library_templates', __CLASS__ .'::elem_add_page_templates', 1000,3 );
		add_filter( 'theme_page_templates', __CLASS__ .'::elem_add_page_templates_page', 1000,3 );

    }
	
	
	static public function elem_add_page_templates($post_templates, $post, $post_type) 	{
	
		$branding = self::get_branding();
		$templates = array_flip( wp_get_theme()->get_page_templates( $post, 'elementor_library' ) );
		if( isset($templates) && !empty($templates) ) {
			foreach($templates as $tempK => $tempV ) {
				 
				if( $tempV == 'elementor_canvas' ) {
					$tempK = $branding['plugin_name'].' Canvas';
				} else if ( $tempV == 'elementor_header_footer' ) {
					$tempK = $branding['plugin_name'].' Full Width';
				}
				//$page_templates[$tempK] = $tempV;
				$page_templates[$tempV] = $tempK;
			}
		}
		return $page_templates;
	
	}
	
	static public function elem_add_page_templates_page($post_templates, $post, $post_type) {
	
		$branding = self::get_branding();
		$templates = array_flip( wp_get_theme()->get_page_templates( $post, 'page' ) );
		if( isset($templates) && !empty($templates) ) {
			foreach($templates as $tempK => $tempV ) {
				 
				if( $tempV == 'elementor_canvas' ) {
					$tempK = $branding['plugin_name'].' Canvas';
				} else if ( $tempV == 'elementor_header_footer' ) {
					$tempK = $branding['plugin_name'].' Full Width';
				}
				//$page_templates[$tempK] = $tempV;
				$page_templates[$tempV] = $tempK;
			}
		}
		
		return $page_templates;
	
	}

	
	
	static public function frontend_scripts()
	{
		if ( ! is_user_logged_in() ) {
			return;
		}

		$branding = self::get_branding();

		if ( $branding['hide_logo'] == 'on' ) {
			?>
			<style>
			#wpadminbar #wp-admin-bar-elementor_edit_page > .ab-item::before {
				content: none;
			}
			</style>
			<?php
		} ?>
	
			
		<?php			
		//check if both pages/blocks are ticked
		if ( isset( $branding['hide_library_pages'] ) && 'on' == $branding['hide_library_pages'] &&  isset( $branding['hide_library_blocks'] ) && 'on' == $branding['hide_library_blocks'] ) { 	?>
		
				<script type="text/javascript">
				
				(function ($) {

				$(document).ready(function(){
				
					console.log('mytemp');

					$(document).on( 'click','.elementor-add-section-area-button.elementor-add-template-button', function( event ){	
					
						var interval = setInterval(function () {
						
							var activeSource = elementor.templates.getFilter('source');
							var activeType = elementor.templates.getFilter('type');

							if( activeSource == 'remote' ) {
																			
								var mytempSource = 'local';
								var mytempType = '';
								if (typeof elementor.templates.setTemplatesPage == 'function') { 
									elementor.templates.setTemplatesPage(mytempSource, mytempType); //change the Active type
								} else {
									elementor.templates.setScreen(mytempSource, mytempType); //change the Active type
								}
								clearInterval(interval);

						}   else {
							console.log('no');
						}
					}, 1000);
						
					});
				
				})
				})(jQuery); 
				
			   </script>
			   
			   <?php
			   //check if pages are checked
		 } else if ( isset( $branding['hide_library_pages'] ) && 'on' == $branding['hide_library_pages'] &&  isset( $branding['hide_library_blocks'] ) && 'off' == $branding['hide_library_blocks'] ) { ?>
			 
			 <script type="text/javascript">
				
				(function ($) {

				$(document).ready(function(){

					$(document).on( 'click','.elementor-add-section-area-button.elementor-add-template-button', function( event ){	
					
						var interval = setInterval(function () {
						
							var activeSource = elementor.templates.getFilter('source');
							var activeType = elementor.templates.getFilter('type');

							//~ console.log('pages');

							if( activeSource == 'remote' ) {
																			
								var mytempSource = 'remote';
								var mytempType = 'block';
								
								if (typeof elementor.templates.setTemplatesPage == 'function') { 
									elementor.templates.setTemplatesPage(mytempSource, mytempType); //change the Active type
								} else {
									elementor.templates.setScreen(mytempSource, mytempType); //change the Active type
								}
								clearInterval(interval);

						}   else {
							console.log('no');
						}
					}, 1000);
						
					});
				
				})
				})(jQuery); 
				
			   </script>
			 
		 <?php
		 
		 //check if blocks are checked
		 
		  }  else if ( isset( $branding['hide_library_pages'] ) && 'off' == $branding['hide_library_pages'] &&  isset( $branding['hide_library_blocks'] ) && 'on' == $branding['hide_library_blocks'] ) { ?>
			 
			 <script type="text/javascript">
				
				(function ($) {

				$(document).ready(function(){

					$(document).on( 'click','.elementor-add-section-area-button.elementor-add-template-button', function( event ){	
					
						var interval = setInterval(function () {
						
							var activeSource = elementor.templates.getFilter('source');
							var activeType = elementor.templates.getFilter('type');

							if( activeSource == 'remote' ) {
																			
								var mytempSource = 'remote';
								var mytempType = 'page';
								//elementor.templates.setTemplatesPage(mytempSource, mytempType);
								if (typeof elementor.templates.setTemplatesPage == 'function') { 
									elementor.templates.setTemplatesPage(mytempSource, mytempType); //change the Active type
								} else {
									elementor.templates.setScreen(mytempSource, mytempType); //change the Active type
								}
								clearInterval(interval);

						}   else {
							console.log('no');
						}
					}, 1000);
						
					});
				
				})
				})(jQuery); 
				
			   </script>
			   
			  <?php } 
	}


    /**
	 * Render branding styles.
	 *
	 * @since 1.0
     * @return void
	 */
    static public function branding_styles()
    {
		if ( ! is_user_logged_in() ) {
			return;
		}
        $branding = self::get_branding();
        echo '<style id="el-blitz-admin-style">';
		include el_blitz_DIR . 'includes/style.css.php';
		echo '</style>';
	}

	static public function admin_menu()
	{
		$branding = self::get_branding();
		
		if ( isset( $branding['hide_admin_menu'] ) && 'on' == $branding['hide_admin_menu'] ) {
			remove_menu_page( 'elementor' );
		}
	}

    /**
	 * Render branding fields.
	 *
	 * @since 1.0
     * @return void
	 */
    static public function render_fields()
    {
        $branding = get_option( '_el_blitzlabel');
        include el_blitz_DIR . 'includes/admin-settings-branding.php';
    }

    /**
	 * Get the branding data from options.
	 *
	 * @since 1.0
	 * @return array
	 */
   
    static public function get_branding( $cache = true )
    {
		if ( ! is_array( self::$branding ) || empty( self::$branding ) ) {
			if ( is_multisite() ) {
				self::$branding = get_blog_option( 1, '_el_blitzlabel');
			} else {
				self::$branding = get_option( '_el_blitzlabel');
			}
		}

        return self::$branding;
    }

    /**
	 * Add/Update the branding data to options.
	 *
	 * @since 1.0
	 * @return mixed
	 */
    static public function update_branding()
    {
        if ( ! isset($_POST['el_blitz_nonce']) ) {
            return;
        }

        $data = array(
            'plugin_name'       => isset( $_POST['el_blitz_plugin_name'] ) ? sanitize_text_field( $_POST['el_blitz_plugin_name'] ) : '',
            'plugin_desc'       => isset( $_POST['el_blitz_plugin_desc'] ) ? sanitize_text_field( $_POST['el_blitz_plugin_desc'] ) : '',
            'plugin_author'     => isset( $_POST['el_blitz_plugin_author'] ) ? sanitize_text_field( $_POST['el_blitz_plugin_author'] ) : '',
            'plugin_uri'        => isset( $_POST['el_blitz_plugin_uri'] ) ? esc_url( $_POST['el_blitz_plugin_uri'] ) : '',
            'edit_with_text'    => isset( $_POST['el_blitz_edit_with_text'] ) ? sanitize_text_field( $_POST['el_blitz_edit_with_text'] ) : self::$default_data['edit_with_text'],
            'disable_pro'    	=> isset( $_POST['el_blitz_disable_pro'] ) ? sanitize_text_field( $_POST['el_blitz_disable_pro'] ) : self::$default_data['disable_pro'],
            'primary_color'   	=> isset( $_POST['el_blitz_primary_color'] ) ? sanitize_hex_color( $_POST['el_blitz_primary_color'] ) : self::$default_data['primary_color'],
            'secondary_color'   => isset( $_POST['el_blitz_secondary_color'] ) ? sanitize_hex_color( $_POST['el_blitz_secondary_color'] ) : self::$default_data['secondary_color'],
            'hide_logo'         => isset( $_POST['el_blitz_hide_logo'] ) ? sanitize_text_field( $_POST['el_blitz_hide_logo'] ) : self::$default_data['hide_logo'],
			'hide_external_links'	=> isset( $_POST['el_blitz_hide_external_links'] ) ? sanitize_text_field( $_POST['el_blitz_hide_external_links'] ) : self::$default_data['hide_external_links'],
			'hide_descriptions'     => isset( $_POST['el_blitz_hide_descriptions'] ) ? sanitize_text_field( $_POST['el_blitz_hide_descriptions'] ) : self::$default_data['hide_descriptions'],
            'hide_settings'     	=> isset( $_POST['el_blitz_hide_settings'] ) ? sanitize_text_field( $_POST['el_blitz_hide_settings'] ) : self::$default_data['hide_settings'],
            'hide_plugin'       	=> isset( $_POST['el_blitz_hide_plugin'] ) ? sanitize_text_field( $_POST['el_blitz_hide_plugin'] ) : self::$default_data['hide_plugin'],
			'hide_el_plugin'    	=> isset( $_POST['el_blitz_hide_el_plugin'] ) ? sanitize_text_field( $_POST['el_blitz_hide_el_plugin'] ) : self::$default_data['hide_el_plugin'],
			'hide_admin_menu'		=> isset( $_POST['el_blitz_hide_admin_menu'] ) ? sanitize_text_field( $_POST['el_blitz_hide_admin_menu'] ) : self::$default_data['hide_admin_menu'],
			'hide_wl_admin_menu'	=> isset( $_POST['el_blitz_hide_wl_admin_menu'] ) ? sanitize_text_field( $_POST['el_blitz_hide_wl_admin_menu'] ) : self::$default_data['hide_wl_admin_menu'],
			'hide_my_templates'		=> isset( $_POST['el_blitz_hide_my_templates'] ) ? sanitize_text_field( $_POST['el_blitz_hide_my_templates'] ) : self::$default_data['hide_my_templates'],
			'hide_settings_page'	=> isset( $_POST['el_blitz_hide_settings_page'] ) ? sanitize_text_field( $_POST['el_blitz_hide_settings_page'] ) : self::$default_data['hide_settings_page'],
            'hide_custom_fonts'    	=> isset( $_POST['el_blitz_hide_custom_fonts'] ) ? sanitize_text_field( $_POST['el_blitz_hide_custom_fonts'] ) : self::$default_data['hide_custom_fonts'],
            'hide_custom_icons'    	=> isset( $_POST['el_blitz_hide_custom_icons'] ) ? sanitize_text_field( $_POST['el_blitz_hide_custom_icons'] ) : self::$default_data['hide_custom_icons'],
            'hide_role_manager'    	=> isset( $_POST['el_blitz_hide_role_manager'] ) ? sanitize_text_field( $_POST['el_blitz_hide_role_manager'] ) : self::$default_data['hide_role_manager'],
             'hide_category_title'    	=> isset( $_POST['el_blitz_hide_category_title'] ) ? sanitize_text_field( $_POST['el_blitz_hide_category_title'] ) : self::$default_data['hide_category_title'],
            'hide_tools'    		=> isset( $_POST['el_blitz_hide_tools'] ) ? sanitize_text_field( $_POST['el_blitz_hide_tools'] ) : self::$default_data['hide_tools'],
            'hide_sys_info'    		=> isset( $_POST['el_blitz_hide_system_info'] ) ? sanitize_text_field( $_POST['el_blitz_hide_system_info'] ) : self::$default_data['hide_sys_info'],
            'hide_knowledge_base'	=> isset( $_POST['el_blitz_hide_knowledge_base'] ) ? sanitize_text_field( $_POST['el_blitz_hide_knowledge_base'] ) : self::$default_data['hide_knowledge_base'],
            
            'hide_license_page'    	=> isset( $_POST['el_blitz_hide_license_page'] ) ? sanitize_text_field( $_POST['el_blitz_hide_license_page'] ) : self::$default_data['hide_license_page'],
            
            'hide_getting_started'    	=> isset( $_POST['el_blitz_hide_getting_started'] ) ? sanitize_text_field( $_POST['el_blitz_hide_getting_started'] ) : self::$default_data['hide_getting_started'],
            
            'hide_library_blocks'    	=> isset( $_POST['el_blitz_hide_library_blocks'] ) ? sanitize_text_field( $_POST['el_blitz_hide_library_blocks'] ) : self::$default_data['hide_library_blocks'],
            
            'hide_library_pages'    	=> isset( $_POST['el_blitz_hide_library_pages'] ) ? sanitize_text_field( $_POST['el_blitz_hide_library_pages'] ) : self::$default_data['hide_library_pages'],
            
            'hide_library_pro_templates'    	=> isset( $_POST['el_blitz_hide_library_pro_templates'] ) ? sanitize_text_field( $_POST['el_blitz_hide_library_pro_templates'] ) : self::$default_data['hide_library_pro_templates'],     
            
            'hide_library_export_template'    	=> isset( $_POST['el_blitz_hide_export_template'] ) ? sanitize_text_field( $_POST['el_blitz_hide_export_template'] ) : self::$default_data['hide_library_export_template'], 
            
            'hide_library_import_template'    	=> isset( $_POST['el_blitz_hide_import_template'] ) ? sanitize_text_field( $_POST['el_blitz_hide_import_template'] ) : self::$default_data['hide_library_import_template'],         
            
            'hide_notices'    	=> isset( $_POST['el_blitz_hide_notices'] ) ? sanitize_text_field( $_POST['el_blitz_hide_notices'] ) : self::$default_data['hide_notices'],
            
            'multisite_hide_settings' => isset( $_POST['el_blitz_multisite_hide_settings'] ) ? sanitize_text_field( $_POST['el_blitz_multisite_hide_settings'] ) : self::$default_data['multisite_hide_settings'],
        );

        update_option( '_el_blitzlabel', $data );
        self::$branding = $data;
    }

    /**
	 * Set the branding data to plugin.
	 *
	 * @since 1.0
	 * @return array
	 */
    static public function plugin_branding( $all_plugins )
    {
		if ( ! defined( 'ELEMENTOR_PLUGIN_BASE' ) || ! isset( $all_plugins[ELEMENTOR_PLUGIN_BASE] ) ) {
			return $all_plugins;
		}

		$branding = self::get_branding();
        
    	$all_plugins[ELEMENTOR_PLUGIN_BASE]['Name']           = ! empty( $branding['plugin_name'] )     ? $branding['plugin_name']      : $all_plugins[ELEMENTOR_PLUGIN_BASE]['Name'];
    	$all_plugins[ELEMENTOR_PLUGIN_BASE]['PluginURI']      = ! empty( $branding['plugin_uri'] )      ? $branding['plugin_uri']       : $all_plugins[ELEMENTOR_PLUGIN_BASE]['PluginURI'];
    	$all_plugins[ELEMENTOR_PLUGIN_BASE]['Description']    = ! empty( $branding['plugin_desc'] )     ? $branding['plugin_desc']      : $all_plugins[ELEMENTOR_PLUGIN_BASE]['Description'];
    	$all_plugins[ELEMENTOR_PLUGIN_BASE]['Author']         = ! empty( $branding['plugin_author'] )   ? $branding['plugin_author']    : $all_plugins[ELEMENTOR_PLUGIN_BASE]['Author'];
    	$all_plugins[ELEMENTOR_PLUGIN_BASE]['AuthorURI']      = ! empty( $branding['plugin_uri'] )      ? $branding['plugin_uri']       : $all_plugins[ELEMENTOR_PLUGIN_BASE]['AuthorURI'];
    	$all_plugins[ELEMENTOR_PLUGIN_BASE]['Title']          = ! empty( $branding['plugin_name'] )     ? $branding['plugin_name']      : $all_plugins[ELEMENTOR_PLUGIN_BASE]['Title'];
		$all_plugins[ELEMENTOR_PLUGIN_BASE]['AuthorName']     = ! empty( $branding['plugin_author'] )   ? $branding['plugin_author']    : $all_plugins[ELEMENTOR_PLUGIN_BASE]['AuthorName'];
		
		if ( defined( 'ELEMENTOR_PRO_PLUGIN_BASE' ) ) {
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Name']           = ! empty( $branding['plugin_name'] )     ? $branding['plugin_name'] . __( ' Pro', 'el-blitzlabel' ) : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Name'];
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['PluginURI']      = ! empty( $branding['plugin_uri'] )      ? $branding['plugin_uri']       : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['PluginURI'];
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Description']    = ! empty( $branding['plugin_desc'] )     ? $branding['plugin_desc']      : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Description'];
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Author']         = ! empty( $branding['plugin_author'] )   ? $branding['plugin_author']    : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Author'];
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['AuthorURI']      = ! empty( $branding['plugin_uri'] )      ? $branding['plugin_uri']       : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['AuthorURI'];
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Title']          = ! empty( $branding['plugin_name'] )     ? $branding['plugin_name']      : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['Title'];
			$all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['AuthorName']     = ! empty( $branding['plugin_author'] )   ? $branding['plugin_author']    : $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE]['AuthorName'];
		}

    	if ( $branding['hide_el_plugin'] == 'on' ) {
			unset( $all_plugins[ELEMENTOR_PLUGIN_BASE] );
			if ( defined( 'ELEMENTOR_PRO_PLUGIN_BASE' ) ) {
				unset( $all_plugins[ELEMENTOR_PRO_PLUGIN_BASE] );
			}
		}
		
		if ( $branding['hide_plugin'] == 'on' ) {
			unset( $all_plugins[el_blitz_PATH] );
		}

    	return $all_plugins;
	}

	static public function plugin_meta()
	{
		add_filter( 'plugin_action_links', 		__CLASS__ . '::plugin_action_links', 1, 4 );
		add_filter( 'plugin_row_meta', 			__CLASS__ . '::plugin_row_meta', 20, 2);
	}

	static public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context )
	{
		$branding = self::get_branding();

		if ( ! isset( $branding['hide_external_links'] ) || 'on' != $branding['hide_external_links'] ) {
			return $actions;
		}

		if ( defined( 'ELEMENTOR_PLUGIN_BASE' ) && ELEMENTOR_PLUGIN_BASE === $plugin_file ) {
			if ( isset( $actions['go_pro'] ) ) {
				unset( $actions['go_pro'] );
			}
		}

		return $actions;
	}

	static public function plugin_row_meta($plugin_meta, $plugin_file)
	{
		$branding = self::get_branding();

		if ( ! isset( $branding['hide_external_links'] ) || 'on' != $branding['hide_external_links'] ) {
			return $plugin_meta;
		}

		if ( defined( 'ELEMENTOR_PLUGIN_BASE' ) && ELEMENTOR_PLUGIN_BASE === $plugin_file ) {
			if ( isset( $plugin_meta['docs'] ) ) {
				unset( $plugin_meta['docs'] );
			}
			if ( isset( $plugin_meta['ideo'] ) ) {
				unset( $plugin_meta['ideo'] );
			}
			if ( isset( $plugin_meta['video'] ) ) {
				unset( $plugin_meta['video'] );
			}
		}

		if ( defined( 'ELEMENTOR_PRO_PLUGIN_BASE' ) ) {
			if ( ELEMENTOR_PRO_PLUGIN_BASE === $plugin_file ) {
				if ( isset( $plugin_meta['docs'] ) ) {
					unset( $plugin_meta['docs'] );
				}
				if ( isset( $plugin_meta['ideo'] ) ) {
					unset( $plugin_meta['ideo'] );
				}
				if ( isset( $plugin_meta['video'] ) ) {
					unset( $plugin_meta['video'] );
				}
				if ( isset( $plugin_meta['changelog'] ) ) {
					unset( $plugin_meta['changelog'] );
				}
			}
		}

		return $plugin_meta;
	}
	
	static public function update_label( $translated_text, $text, $domain )
	{
		$branding = self::get_branding();
		$new_text = $translated_text;
		$name = isset( $branding['plugin_name'] ) && ! empty( $branding['plugin_name'] ) ? $branding['plugin_name'] : '';

		if ( ! empty( $name ) ) {
			$new_text = str_replace( 'Elementor', $name, $new_text );
		}
		
		return $new_text;
	}
}

// Initializes Elementor_blitzlabel class.
Elementor_blitzlabel::init();
