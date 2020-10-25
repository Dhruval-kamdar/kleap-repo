<?php
namespace ElementorControls;

use Elementor;
/**
 * WordPress settings API: Granular Controls For Elementor
 *
 * @author Zulfikar Nore
 */
if ( !class_exists('Granular_Controls_Settings_API' ) ) {
	class Granular_Controls_Settings_API {

		private $settings_api;

		function __construct() {
			$this->settings_api = new Granular_Settings_API;

			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_menu', array($this, 'add_admin_menu'), 100 );
			add_action( 'admin_footer', array($this, 'add_custom_js'), 100 );   //Add custom js to work with custom editor theme
		}

		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		function add_admin_menu() {
			if(is_multisite() && is_main_site()){
			add_submenu_page( 
				Elementor\Settings::PAGE_ID, 
				__( 'Control', 'blitz-controls-for-elementor' ), 
				__( 'Control', 'blitz-controls-for-elementor' ), 
				'delete_posts', 
				'blitz_controls', 
				array($this, 'granular_settings_page' ) );
			}elseif(!is_multisite()){
				add_submenu_page( 
				Elementor\Settings::PAGE_ID, 
				__( 'Control', 'blitz-controls-for-elementor' ), 
				__( 'Control', 'blitz-controls-for-elementor' ), 
				'delete_posts', 
				'blitz_controls', 
				array($this, 'granular_settings_page' ) );
				}
		}
		
		
		function add_custom_js() {
			
			$script ='';
			$script .='<script type="text/javascript">';
			$script .= 'jQuery(document).ready(function(){
							
				var themeSelected=jQuery("tr.granular_editor_skin select").val();
  			    if(themeSelected == "custom") {
  			    	jQuery("tr.granular_editor_bg_color, tr.granular_editor_module_bg_color, tr.granular_editor_module_text_color, tr.granular_editor_module_text_hcolor").show();
				} else {
					jQuery("tr.granular_editor_bg_color, tr.granular_editor_module_bg_color, tr.granular_editor_module_text_color, tr.granular_editor_module_text_hcolor").hide();
					jQuery("tr.granular_editor_bg_color input, tr.granular_editor_module_bg_color input, tr.granular_editor_module_text_color input, tr.granular_editor_module_text_hcolor input").attr("value","");
				}
				
				jQuery("tr.granular_editor_skin select").on("change", function() {
					  var theme = jQuery(this).val();
					  if(theme == "custom") {
							jQuery("tr.granular_editor_bg_color, tr.granular_editor_module_bg_color, tr.granular_editor_module_text_color, tr.granular_editor_module_text_hcolor").show();
					  } else {
							jQuery("tr.granular_editor_bg_color, tr.granular_editor_module_bg_color, tr.granular_editor_module_text_color, tr.granular_editor_module_text_hcolor").hide();
							jQuery("tr.granular_editor_bg_color input, tr.granular_editor_module_bg_color input, tr.granular_editor_module_text_color input, tr.granular_editor_module_text_hcolor input").attr("value","");
					  }
				});
			
			});
			';
			$script .='</script>';
			
			echo $script;
		}
		
		
		function get_settings_sections() {
			
			if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {

				$sections = array(
					array(
						'id'    => 'granular_general_settings',
						'title' => __( 'Global Settings', 'blitz-controls-for-elementor' )
					),
					array(
						'id'    => 'granular_editor_settings',
						'title' => __( 'Elementor Editor Settings', 'blitz-controls-for-elementor' )
					),
					array(
						'id'    => 'granular_editor_elements_settings',
						'title' => __( 'Elementor - Elements Settings', 'blitz-controls-for-elementor' )
					),
					array(
						'id'    => 'granular_editor_elements_pro_settings',
						'title' => __( 'Elementor PRO - Elements Settings', 'blitz-controls-for-elementor' )
					),
					//~ array(
						//~ 'id'    => 'granular_advanced_settings',
						//~ 'title' => __( 'Advanced Settings', 'blitz-controls-for-elementor' )
					//~ )
				);
			
			} else {
				
				$sections = array(
					array(
						'id'    => 'granular_general_settings',
						'title' => __( 'Global Settings', 'blitz-controls-for-elementor' )
					),
					array(
						'id'    => 'granular_editor_settings',
						'title' => __( 'Elementor Editor Settings', 'blitz-controls-for-elementor' )
					),
					array(
						'id'    => 'granular_editor_elements_settings',
						'title' => __( 'Elementor - Elements Settings', 'blitz-controls-for-elementor' )
					),
					//~ array(
						//~ 'id'    => 'granular_advanced_settings',
						//~ 'title' => __( 'Advanced Settings', 'blitz-controls-for-elementor' )
					//~ )
				);
				
				
			}
			return $sections;
			
			
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {

			$templates = $this->get_templates();
			$options = [
				'' => '— ' . __( 'Select', 'blitz-controls-for-elementor' ) . ' —',
			];
			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
			$settings_fields = array(
				'granular_general_settings' => array(
					array(
						'name'    => 'granular_accordion_off',
						'label'   => __( 'Accordions Closed?', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Set all accordions\' first tab to be closed on page load.', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_dashboard_widget_off',
						'label'   => __( 'Remove Dashboard Widget', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Remove the Elementor\'s dashboard widget.', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					)
				),
				'granular_editor_elements_settings' => array(
				
					array(
						'name'    => 'granular_main_elmenent_heading',
						'desc'    => __( 'Element Sections', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),
					array(
						'name'    => 'granular_main_elmenent_basic',
						'desc'    => __( 'Basic', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_main_elmenent_general',
						'desc'    => __( 'General', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_main_elmenent_wordpress',
						'desc'    => __( 'Wordpress', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_main_heading',
						'desc'    => __( 'Basic', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),
					//~ array(
						//~ 'name'    => 'granular_basic_common',
						//~ 'desc'    => __( 'Inner Section', 'blitz-controls-for-elementor' ),
						//~ 'type'    => 'checkboxui',
						//~ 'default' => '1'
					//~ ),
					array(
						'name'    => 'granular_basic_heading',
						'desc'    => __( 'Heading', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_image',
						'desc'    => __( 'Image', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_text-editor',
						'desc'    => __( 'Text Editor', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_video',
						'desc'    => __( 'Video', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_button',
						'desc'    => __( 'Button', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_divider',
						'desc'    => __( 'Divider', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_spacer',
						'desc'    => __( 'Spacer', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_google-maps',
						'desc'    => __( 'Google Maps', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_basic_icon',
						'desc'    => __( 'Icon', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					//general
					array(
						'name'    => 'granular_general_main_heading',
						'desc'    => __( 'General', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),
					
					array(
						'name'    => 'granular_general_image-box',
						'desc'    => __( 'Image Box', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_icon-box',
						'desc'    => __( 'Icon Box', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_star-rating',
						'desc'    => __( 'Star Rating', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_image-gallery',
						'desc'    => __( 'Image Gallery', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_image-carousel',
						'desc'    => __( 'Image Carousel', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_icon-list',
						'desc'    => __( 'Icon List', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_counter',
						'desc'    => __( 'Counter', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_progress',
						'desc'    => __( 'Progress Bar', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_testimonial',
						'desc'    => __( 'Testimonial', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_tabs',
						'desc'    => __( 'Tabs', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_accordion',
						'desc'    => __( 'Accordion', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_toggle',
						'desc'    => __( 'Toggle', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_social-icons',
						'desc'    => __( 'Social Icons', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_alert',
						'desc'    => __( 'Alert', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_audio',
						'desc'    => __( 'Sound Cloud', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_shortcode',
						'desc'    => __( 'Shortcode', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_html',
						'desc'    => __( 'HTML', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_menu-anchor',
						'desc'    => __( 'Menu Anchor', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_sidebar',
						'desc'    => __( 'Sidebar', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_general_readmore',
						'desc'    => __( 'Read More', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_main_heading',
						'desc'    => __( 'Wordpress', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),
					array(
						'name'    => 'granular_wordpress_pages',
						'desc'    => __( 'Pages', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_calendar',
						'desc'    => __( 'Calender', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_archives',
						'desc'    => __( 'Archives', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_media_audio',
						'desc'    => __( 'Audio', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_media_image',
						'desc'    => __( 'Image', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_media_gallery',
						'desc'    => __( 'Gallery', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_media_video',
						'desc'    => __( 'Video', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_meta',
						'desc'    => __( 'Meta', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_search',
						'desc'    => __( 'Search', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_text',
						'desc'    => __( 'Text', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_categories',
						'desc'    => __( 'Categories', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_recent-posts',
						'desc'    => __( 'Recent Posts', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_recent-comments',
						'desc'    => __( 'Recent Comments', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_rss',
						'desc'    => __( 'RSS', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_tag_cloud',
						'desc'    => __( 'Tag Cloud', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_nav_menu',
						'desc'    => __( 'Navigation Menu', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_wordpress_custom_html',
						'desc'    => __( 'Custom HTML', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					
				),
				'granular_editor_elements_pro_settings' => array(
					
					array(
						'name'    => 'granular_main_elmenent_pro_heading',
						'desc'    => __( 'Element Sections', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),
					array(
						'name'    => 'granular_main_elmenent_pro-elements',
						'desc'    => __( 'PRO', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_main_elmenent_pro_theme-elements',
						'desc'    => __( 'Site', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_main_elmenent_pro_theme-elements-single',
						'desc'    => __( 'Single', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					
					array(
						'name'    => 'granular_pro_main_heading',
						'desc'    => __( 'PRO', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),
					array(
						'name'    => 'granular_pro_posts',
						'desc'    => __( 'Posts', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_portfolio',
						'desc'    => __( 'Portfolio', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_slides',
						'desc'    => __( 'Slides', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_form',
						'desc'    => __( 'Form', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_login',
						'desc'    => __( 'Login', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_media-carousel',
						'desc'    => __( 'Media Carousel', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_testimonial-carousel',
						'desc'    => __( 'Testimonial Carousel', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_nav-menu',
						'desc'    => __( 'Nav Menu', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_nav-menu',
						'desc'    => __( 'Nav Menu', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_pricing',
						'desc'    => __( 'Pricing', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_facebook-comment',
						'desc'    => __( 'Facebook Comments', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_reviews',
						'desc'    => __( 'Reviews', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_animated-headline',
						'desc'    => __( 'Animated Headline', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_price-list',
						'desc'    => __( 'Price List', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_price-table',
						'desc'    => __( 'Price Table', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_facebook-button',
						'desc'    => __( 'Facebook Button', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_facebook-button',
						'desc'    => __( 'Facebook Button', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_blockquote',
						'desc'    => __( 'Blockquote', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_flip-box',
						'desc'    => __( 'Flip Box', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_call-to-action',
						'desc'    => __( 'Call to Action', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_countdown',
						'desc'    => __( 'Countdown', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_share-buttons',
						'desc'    => __( 'Share Buttons', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_facebook-embed',
						'desc'    => __( 'Facebook Embed', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_facebook-page',
						'desc'    => __( 'Facebook Page', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_pro_template',
						'desc'    => __( 'Template', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_site_main_heading',
						'desc'    => __( 'Site', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),		
					array(
						'name'    => 'granular_site_theme-site-logo',
						'desc'    => __( 'Site Logo', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_site_theme-site-title',
						'desc'    => __( 'Site Title', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_site_theme-page-title',
						'desc'    => __( 'Page Title', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_site_search-form',
						'desc'    => __( 'Search Form', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_site_nav-menu',
						'desc'    => __( 'Nav Menu', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_site_sitemap',
						'desc'    => __( 'Sitemap', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_main_heading',
						'desc'    => __( 'Single', 'blitz-controls-for-elementor' ),
						'type'    => 'html'
					),		
					array(
						'name'    => 'granular_single_theme-post-title',
						'desc'    => __( 'Post Title', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_theme-post-excerpt',
						'desc'    => __( 'Post Excerpt', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_theme-post-featured-image',
						'desc'    => __( 'Featured Image', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_author-box',
						'desc'    => __( 'Author Box', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_post-comments',
						'desc'    => __( 'Post Comments', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_post-info',
						'desc'    => __( 'Post Info', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),
					array(
						'name'    => 'granular_single_post-navigation',
						'desc'    => __( 'Post Navigation', 'blitz-controls-for-elementor' ),
						'type'    => 'checkboxui',
						'default' => '1'
					),


					
					
					
				),
				
				'granular_editor_settings' => array(
					array(
						'name'    => 'granular_editor_skin',
						'label'   => __( 'Change Editor Color', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Apply a custom color skin to the editor panel. Dark skin is by <a target="_blank" href="https://www.facebook.com/AlexIschenko2016">Alex Ischenko</a>', 'blitz-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'' 			=> __( 'Default', 'blitz-controls-for-elementor' ),
							'dark' 		=> __( 'Dark', 'blitz-controls-for-elementor' ),
							'lgrunge' 	=> __( 'Light Grunge', 'blitz-controls-for-elementor' ),
							'dgrunge' 	=> __( 'Dark Grunge', 'blitz-controls-for-elementor' ),
							'blue' 		=> __( 'Deep Blue', 'blitz-controls-for-elementor' ),
							'purple' 	=> __( 'Deep Purple', 'blitz-controls-for-elementor' ),
							'red' 		=> __( 'Red', 'blitz-controls-for-elementor' ),
							'gred' 		=> __( 'Grunge Red', 'blitz-controls-for-elementor' ),
							'custom' 		=> __( 'Custom', 'blitz-controls-for-elementor' )
						),
					),
					

					array(
						'name'    => 'granular_editor_bg_color',
						'label'   => __( 'Change Editor BG Color', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Choose bg color of elementor sidebar', 'blitz-controls-for-elementor' ),
						'type'    => 'color',
						'default' => '',
					),
					
					array(
						'name'    => 'granular_editor_module_bg_color',
						'label'   => __( 'Change Editor Modules BG Color', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Choose bg color of elementor module boxes', 'blitz-controls-for-elementor' ),
						'type'    => 'color',
						'default' => '',
					),
					
					array(
						'name'    => 'granular_editor_module_text_color',
						'label'   => __( 'Change Editor Modules Text Color', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Choose text color of elementor module', 'blitz-controls-for-elementor' ),
						'type'    => 'color',
						'default' => '',
					),
					array(
						'name'    => 'granular_editor_module_text_hcolor',
						'label'   => __( 'Change Editor Modules Text Hover Color', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Choose text hover color of elementor module', 'blitz-controls-for-elementor' ),
						'type'    => 'color',
						'default' => '',
					),
					array(
						'name'    => 'granular_editor_primary_color',
						'label'   => __( 'Primary Color', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Choose top & bottom bar color of elementor sidebar', 'blitz-controls-for-elementor' ),
						'type'    => 'color',
						'default' => '',
					),
					
					array(
						'name'    => 'granular_editor_exit_on',
						'label'   => __( 'Enable Exit Bar', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Don\'t like having to go through too many hoops in order to exit the editor? There\'s a control for that - just enable to get a 1 exit option bar!', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						) 
					),
					
					array(
						'name'    => 'granular_dashboard_off',
						'label'   => __( 'Remove Exit To Dashboard Menu From Side menu Panel', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Want To Remove Exit To Dashboard Menu From Side menu Panel?', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_dashboard_settings_off',
						'label'   => __( 'Remove Dashboard settings menu From side menu panel', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Want To Remove Dashboard settings From Side menu Panel?', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_about_editor_off',
						'label'   => __( 'Remove About Elmentor Editor menu From side menu panel', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Want To Remove About Elmentor Editor From Side menu Panel?', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_save_template_off',
						'label'   => __( 'Remove Save Template menu From side menu panel', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Want To Remove Save Template From Side menu Panel?', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no', 
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_category_title',
						'label'   => __( 'Remove Elements Category Titles', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Remove Elements Category Titles', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_upgrade_pro_nag_off',
						'label'   => __( 'Remove Upgrade to Pro Nag', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Kill the Upgrade to Pro Nag at the Bottom of the Element Select Column', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_loader_off',
						'label'   => __( 'Remove Elementor Stock Loader', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Remove Elementor Stock Loader', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_UAEL_Tag_off',
						'label'   => __( 'Remove UAEL and EE Tag', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Remove UAEL and EE Tag from Top Right Corner of UAEL and EE Element Icons', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_Advanced_tab_off',
						'label'   => __( 'Remove the Advanced tab', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'You can remove the Advanced tab in the page settings dialogue (no actual settings, just a pro nag)', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_Global_tab_off',
						'label'   => __( 'Remove the Global tab', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'You can remove the Global tab from the main Elementor column (has a pro nag)', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_custom_css_off',
						'label'   => __( 'Remove the Custom CSS section from the advanced tab', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'You can remove the Custom CSS section from the advanced tab (has an upgrade to pro nag)', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					
					array(
						'name'    => 'granular_update_options_off',
						'label'   => __( 'Remove the pop-out Update options', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'You can remove the pop-out button to the right of the update button (the one that lets the user save the page as a template)', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					//~ array(
						//~ 'name'    => 'granular_save_template_off',
						//~ 'label'   => __( 'Remove "save as template" on the right click menu of Element', 'blitz-controls-for-elementor' ),
						//~ 'desc'    => __( 'You can remove "save as template" on the right click menu of Element', 'blitz-controls-for-elementor' ),
						//~ 'type'    => 'radio',
						//~ 'default' => 'no',
						//~ 'options' => array(
							//~ 'yes' => 'Yes',
							//~ 'no'  => 'No'
						//~ )
					//~ ),
					
					array(
						'name'    => 'granular_page_setting_off',
						'label'   => __( 'Remove page settings at bottom of elemetor editor', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'You can pull out page settings altogether (bottom left of the screen near the update bar)', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_loader_text',
						'label'   => __( 'Add Your Own Builder Name to the Loader Screen ', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Add Your Own Builder Name to the Loader Screen ', 'blitz-controls-for-elementor' ),
						'type'    => 'text',
						'default' => __( 'Loading', 'blitz-controls-for-elementor' ),
					),
					array(
						'name'    => 'granular_loader_image',
						'label'   => __( 'Add Your Own Loader Image to the Loader Screen ', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Add Your Own Loader Image to the Loader Screen ', 'blitz-controls-for-elementor' ),
						'type'    => 'File',
						'default' => '',
					),
					
					array(
						'name'    => 'granular_panel_header_text',
						'label'   => __( 'Add Your Own Header to Panel Of Elementor ', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Add Your Own Header to Panel Of Elementor ', 'blitz-controls-for-elementor' ),
						'type'    => 'text',
						'default' => __( 'Elementor', 'blitz-controls-for-elementor' ),
					),
					array(
						'name'    => 'granular_editor_exit_point',
						'label'   => __( 'Exit Point', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Select where to land when the Exit To Dashboard buttons is clicked - Default is the current post/page edit screen', 'blitz-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'editor' 		=> __( 'Edit Screen', 'blitz-controls-for-elementor' ),
							'type_pages'	=> __( 'Pages List', 'blitz-controls-for-elementor' ),
							'type_posts'	=> __( 'Posts List', 'blitz-controls-for-elementor' ),
							'type_lib'		=> __( 'Library List', 'blitz-controls-for-elementor' ),
							'dashboard' 	=> __( 'Admin Dashboard', 'blitz-controls-for-elementor' ),
							'live' 			=> __( 'Site\'s Home Page', 'blitz-controls-for-elementor' )
						),
					),
					array(
						'name'    => 'granular_editor_exit_target',
						'label'   => __( 'Exit Target', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Select How the exit happens. Sometimes you might want to quickly pop into the Admin area without leaving the editor<br /> then setting the Exit Point to a new tab might be ideal for your work flow :)', 'blitz-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => array(
							'' 			=> __( 'Same Tab/Window', 'blitz-controls-for-elementor' ),
							'_blank'	=> __( 'New Tab/Window', 'blitz-controls-for-elementor' )
						),
					),
					array(
						'name'    => 'granular_editor_exit_name',
						'label'   => __( 'Exit Name', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'If you\'ve changed the default exit point it might be worth changing the button text too so that you know where you\'ll land on exit :) ', 'blitz-controls-for-elementor' ),
						'type'    => 'text',
						'default' => __( 'Exit To Dashboard', 'blitz-controls-for-elementor' ),
					),
					array(
						'name'    => 'granular_editor_live_view_name',
						'label'   => __( 'Live View Name', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Change the Live View text or leave empty to only show the icon :) ', 'blitz-controls-for-elementor' ),
						'type'    => 'text',
						'default' => __( 'View Live Page', 'blitz-controls-for-elementor' ),
					),
				),
				
				
				'granular_advanced_settings' => array(
					array(
						'name'    => 'granular_elementor_dashboard_on',
						'label'   => __( 'Elementor In Dashboard', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Enable use of Elementor content in the Admin Dashboard - below options will not function correctly with this setting turned off!.', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_welcome_on',
						'label'   => __( 'Welcome Panel', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Enable the custom Granular Welcome Panel in the Admin Dashboard.', 'blitz-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'granular_welcome_template_id',
						'label'   => __( 'Panel Template ID', 'blitz-controls-for-elementor' ),
						'desc'    => __( 'Select the template you\'d like to be used as the Welcome Panel in the Admin Dashboard.', 'blitz-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => $options,
					),
				)
			);

			return $settings_fields;
		}

		function granular_settings_page() {
			echo '<div class="wrap">';
				$this->settings_api->show_navigation();
				$this->settings_api->show_forms();
			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		function get_pages() {
			$pages = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ($pages as $page) {
					$pages_options[$page->ID] = $page->post_title;
				}
			}

			return $pages_options;
		}
		
		public static function get_templates() {
			return Plugin::elementor()->templates_manager->get_source( 'local' )->get_items();
		}

	}
}
