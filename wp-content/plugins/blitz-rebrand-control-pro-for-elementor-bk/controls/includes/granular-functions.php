<?php
namespace ElementorControls;

use Elementor;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin_Functions {
	
	private static $_instance;
	
	public function elementor_accordion_off() { ?>
		<script>
			jQuery(document).ready(function() {
				jQuery( '.elementor-accordion .elementor-tab-title' ).removeClass( 'elementor-active' );
				jQuery( '.elementor-accordion .elementor-tab-content' ).css( 'display', 'none' );
			});
		</script>
	<?php
	}
	public function elementor_loader_text() { 
	$loader_text = granular_get_options( 'granular_loader_text', 'granular_editor_settings', '' );?>
		<style type="text/css">
		.elementor-loading-title {color: transparent !important;}
			.elementor-loading-title:after {content: "<?php echo esc_html( $loader_text );?>" ; display: block; color: #000 !important;}
		</style>
		<?php	
	}
	
	public function elementor_loader_image() { 
	$loader_image = granular_get_options( 'granular_loader_image', 'granular_editor_settings', '' );?>
		<style type="text/css">
			.elementor-loader-boxes{display:none;}
		.elementor-loader {background-image: url(<?php echo esc_html( $loader_image );?>); background-size: contain; background-repeat: no-repeat; background-position: center center;}
		</style>
		<?php	
	}
	
	public function elementor_panel_header_text() { 
	$loader_header= granular_get_options( 'granular_panel_header_text', 'granular_editor_settings', '' );?>
		<style type="text/css">
			div#elementor-panel-header-title img {display:none;}
			div#elementor-panel-header-title:after {content: "<?php echo esc_html( $loader_header );?>" ; color: #fff !important;}
		</style>
		<?php	
	}
	public function elementor_category_title() { 
		echo '<style type="text/css">
			.elementor-panel-category-title {display: none;}
		</style>';	
	}
	
	public function elementor_dashboard_off() { 
		echo '<style type="text/css">
			.ps-container #elementor-panel-page-menu .elementor-panel-menu-item-exit-to-dashboard, .elementor-panel-menu-item.elementor-panel-menu-item-exit-to-dashboard {display: none !important;}
		</style>';	
	}
	
	public function elementor_dashboard_settings_off() { 
		echo '<style type="text/css">
			.elementor-panel-menu-item.elementor-panel-menu-item-elementor-settings {display: none !important;}
		</style>';	
	}
	
	public function elementor_about_editor_off() { 
		echo '<style type="text/css">
			.elementor-panel-menu-item.elementor-panel-menu-item-about-elementor {display: none !important;}
		</style>';	  
	}
	
	public function elementor_upgrade_pro() { 
		echo '<style type="text/css">
			.elementor-editor-active div#elementor-panel-get-pro-elements {display: none !important;}
		</style>';	
	}
	
	public function elementor_loader_off() { 
		echo '<style type="text/css">
			.elementor-loader-boxes {display: none;}
		</style>';	
	}
	
	public function elementor_UAEL_Tag_off() { 
		echo '<style type="text/css">
			div#elementor-panel-category-ultimate-elements .icon i:after {display: none;}
			#elementor-panel-elements-wrapper .elementor-element .nicon:after{display:none;}
		</style>';	
	}
	
	public function elementor_Advanced_tab_off() { 
		echo '<style type="text/css">
			.ps-container .elementor-panel-controls-stack .elementor-panel-navigation-tab.elementor-tab-control-advanced {display: none;}
		</style>';	
	}
	public function elementor_Global_tab_off() { 
		echo '<style type="text/css">
			div#elementor-panel-elements-navigation-global, #elementor-panel-page-elements .elementor-component-tab.elementor-panel-navigation-tab[data-tab=global] {display: none !important;} 
		</style>';	
	}
	public function elementor_custom_css_off() { 
		echo '<style type="text/css">
			.elementor-control.elementor-control-section_custom_css_pro {display: none;}
		</style>';	
	}
	public function elementor_update_options_off() { 
		echo '<style type="text/css">
			div#elementor-panel-saver-save-options {display: none;}
			div#elementor-panel-saver-publish {padding-right: 15px !important;}
		</style>';	
	}
	public function elementor_save_template_off() { 
		echo '<style type="text/css">
			.elementor-context-menu-list__item.elementor-context-menu-list__item-save, .elementor-panel-footer-tool div#elementor-panel-footer-sub-menu-item-save-template {display: none;}
		</style>';	
	}
	public function elementor_page_setting_off() { 
		echo '<style type="text/css">
			div#elementor-panel-footer-settings {display: none;}
		</style>';	
	}
	public function main_elmenent_basic() { 
		echo '<style type="text/css">
			div#elementor-panel-category-basic {display: none;}
			div#elementor-panel-category-basic .elementor-panel-category-items { display: none !important;}
			div#elementor-panel-category-basic .elementor-panel-category-items .elementor-element-wrapper{ display: none !important;}
		</style>';	
	}
	
	//~ public function main_elmenent_basic() { 
		//~ echo '<script>
			//~ setTimeout(function(){
			//~ alert("here");
				//~ jQuery(".elementor-editor-active #elementor-panel-content-wrapper #elementor-panel-categories div#elementor-panel-category-basic").html();
				//~ jQuery(".elementor-editor-active #elementor-panel-content-wrapper #elementor-panel-categories div#elementor-panel-category-basic").hide();
			 //~ }, 14000);
		//~ </script>';	
	//~ }
	public function main_elmenent_general() { 
		echo '<style type="text/css">
			div#elementor-panel-category-general {display: none;}
			div#elementor-panel-category-general .elementor-panel-category-items { display: none !important;}
			div#elementor-panel-category-general .elementor-panel-category-items .elementor-element-wrapper{ display: none !important;}
		</style>';	
	}
	public function main_elmenent_wordpress() { 
		echo '<style type="text/css">
			div#elementor-panel-category-wordpress {display: none;}
			div#elementor-panel-category-wordpress .elementor-panel-category-items { display: none !important;}
			div#elementor-panel-category-wordpress .elementor-panel-category-items .elementor-element-wrapper{ display: none !important;}
		</style>';	
	}
	
	public function main_elmenent_pro() { 
		echo '<style type="text/css">
			div#elementor-panel-category-pro-elements {display: none;}
			div#elementor-panel-category-pro-elements .elementor-panel-category-items { display: none !important;}
			div#elementor-panel-category-pro-elements .elementor-panel-category-items .elementor-element-wrapper{ display: none !important;}
		</style>';	
	}
	
	public function main_elmenent_site() { 
		echo '<style type="text/css">
			div#elementor-panel-category-theme-elements {display: none;}
			div#elementor-panel-category-theme-elements .elementor-panel-category-items { display: none !important;}
			div#elementor-panel-category-theme-elements .elementor-panel-category-items .elementor-element-wrapper{ display: none !important;}
		</style>';	
	}
	
	public function main_elmenent_single() { 
		echo '<style type="text/css">
			div#elementor-panel-category-theme-elements-single {display: none;}
			div#elementor-panel-category-theme-elements-single .elementor-panel-category-items { display: none !important;}
			div#elementor-panel-category-theme-elements-single .elementor-panel-category-items .elementor-element-wrapper{ display: none !important;}
		</style>';	
	}
	
	
	
	
	
	
		/**
	 * Get Elementor saved templates.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function get_templates()
	{
		$args = array(
            'post_type'         => 'elementor_library',
			'posts_per_page'    => '-1',
			'post_status'		=> 'publish'
		);

		$templates = get_posts( $args );

		// Multisite support.
		if ( is_multisite() ) {

            $blog_id = get_current_blog_id();

            if ( $blog_id != 1 ) {
                switch_to_blog(1);

                // Get posts from main site.
                $main_posts = get_posts( $args );

                // Loop through each main site post
                // and add site_id to post object.
                foreach ( $main_posts as $main_post ) {
                    $main_post->site_id = 1;
                }

                $templates = array_merge( $templates, $main_posts );

                restore_current_blog();
            }
            else {
                // Loop through each main site post
                // and add site_id to post object.
                foreach ( $templates as $template ) {
                    $template->site_id = 1;
                }
            }
        }
		
		$data = array();

        if ( ! empty( $templates ) && ! is_wp_error( $templates ) ){
            foreach ( $templates as $post ) {
                $data[ $post->ID ] = array(
					'title'	=> $post->post_title,
					'site'	=> isset( $post->site_id ) ? $post->site_id : null
				);
            }
		}
		
        return $data;
	}
	
	
	public function disable_elementor_dashboard_overview_widget() {
		remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal' );
	}
	
	public function db_ui_hack_2() {
		echo '<style type="text/css">
			.elementor-panel .panel-elements-category-items{display: flex; flex-wrap: wrap; justify-content: flex-start;}.elementor-panel .elementor-element-wrapper{flex: 1 1 100px;}
		</style>';	
	}
	
	public function enqueue_editor_skin_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$color = granular_get_options( 'granular_editor_skin', 'granular_editor_settings', '' );
		wp_enqueue_style(
			'elementor-editor-skin',
			ELEMENTOR_CONTROLS_ASSETS_URL . 'css/elementor-' . $color . '-skin.css',
			[],
			ELEMENTOR_CONTROLS_VERSION
		);

	}
	
	public function granular_editor_settings_bg_color() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$bgcolor = granular_get_options( 'granular_editor_bg_color', 'granular_editor_settings', '' );
		
		echo '<style type="text/css">
		.elementor-panel#elementor-panel, .elementor-panel .elementor-control {
			background: '.$bgcolor.';
		}
		.elementor-panel .wp-picker-container.wp-picker-active {
			background-color: '.$bgcolor.';
		}
		#elementor-mode-switcher,
		body.elementor-editor-preview #elementor-mode-switcher {
			background-color: '.$bgcolor.';
		}
		.elementor-panel .elementor-controls-popover:before {
			border-bottom-color: '.$bgcolor.';
		}
		.elementor-panel .elementor-controls-popover {
			background-color: '.$bgcolor.';
		}
		.select2-container--default .select2-selection--multiple {
			background-color: '.$bgcolor.';
		}
		</style>';	
		

	}

	
	public function granular_editor_settings_module_bg_color() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$moduleBgcolor = granular_get_options( 'granular_editor_module_bg_color', 'granular_editor_settings', '' );
		$rgb = $this->hex2rgba($moduleBgcolor);
		
		echo '<style type="text/css">
			
			/* MAIN ELEMENT */
			.elementor-panel .elementor-element {
				background-color: '.$rgb.'!important;
			}
	
		</style>';	
		
	}
	
	public function granular_editor_settings_module_text_color() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$moduleTextcolor = granular_get_options( 'granular_editor_module_text_color', 'granular_editor_settings', '' );
		
		echo '<style type="text/css">
			
			/* MAIN ELEMENT */
			.elementor-panel .elementor-element .title, .elementor-panel .elementor-element .icon, .elementor-panel .elementor-element .icon .power-pack-admin-icon, .elementor-panel .elementor-element .title, .elementor-panel.elementor-panel-category-title {
				color: '.$moduleTextcolor.'!important;
			}
	
		</style>';	
		
	}
	
	public function granular_editor_settings_module_text_hcolor() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$moduleTextHcolor = granular_get_options( 'granular_editor_module_text_hcolor', 'granular_editor_settings', '' );
		
		echo '<style type="text/css">
			
			/* MAIN ELEMENT */
			div.elementor-panel .elementor-element:hover .icon, div.elementor-panel .elementor-element:hover .title {
				color: '.$moduleTextHcolor.'!important;
			}
	
		</style>';	
		
	}
	
	
	public function granular_editor_settings_primary_color() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$modulePcolor = granular_get_options( 'granular_editor_primary_color', 'granular_editor_settings', '' );
		
		echo '<style type="text/css">
			
			/* Top & Bottom bar */
			.wp-picker-container .wp-color-result.button{
				background-color: '. $modulePcolor.' !important;
			}
			div.elementor-panel #elementor-panel-header,body #granular-top-bar,body #granular-top-bar .exit-to-dashboard,body #granular-top-bar .view-live-page,
			div.elementor-add-new-section .elementor-add-section-button,
			div#elementor-mode-switcher:hover {
				background-color: '.$modulePcolor.';
			}
			div.elementor-panel .elementor-panel-navigation .elementor-panel-navigation-tab.elementor-active {
				border-bottom-color: '.$modulePcolor.';
			}
			div.elementor-panel .elementor-control-type-gallery .elementor-control-gallery-clear,
			div.elementor-panel .elementor-element:hover .icon,
			div.elementor-panel .elementor-element:hover .title,
			div.elementor-panel a,
			div.elementor-panel a:hover {
				color: '.$modulePcolor.';
			}
			div.elementor-templates-modal__header .elementor-template-library-menu-item.elementor-active {
				border-bottom-color:  '.$modulePcolor.';
			}
			div.elementor-template-library-template-remote.elementor-template-library-pro-template .elementor-template-library-template-body:before {
				background-color:  '.$modulePcolor.';
			}
			
		</style>';	
		
	}
	
	
	//~ public function enqueue_welcome_panel_styles() {
		//~ $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		//~ $panel_id = granular_get_options( 'granular_welcome_template_id', 'granular_advanced_settings', '' );		
		//~ wp_enqueue_style( 'granular-dashboard-page', esc_url( site_url().'/wp-content/uploads/elementor/css/post-' . $panel_id . '.css', false, '1.1', 'all' ) );
	//~ }
	
	private function functions_setup_hooks() {
		
		$accord_closed = granular_get_options( 'granular_accordion_off', 'granular_general_settings', 'no' );
		if ( 'yes' === $accord_closed ) {
			add_action( 'wp_footer', [ $this, 'elementor_accordion_off' ], 99 );
		}
		
		
		$dash_widget_off = granular_get_options( 'granular_dashboard_widget_off', 'granular_general_settings', 'no' );
		if ( 'yes' === $dash_widget_off ) {
			add_action( 'wp_dashboard_setup', [ $this, 'disable_elementor_dashboard_overview_widget' ], 40 );
		}
		$skin = granular_get_options( 'granular_editor_skin', 'granular_editor_settings', 'default' );
		if ( ! empty ( $skin ) ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_skin_styles' ], 99 );
		}
		
		$category_title_off = granular_get_options( 'granular_category_title', 'granular_editor_settings', 'no' );
		if ( 'yes' === $category_title_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_category_title' ] );
		}
		
		$dash_off = granular_get_options( 'granular_dashboard_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $dash_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_dashboard_off' ] );
		}
		
		$dash_settings_off = granular_get_options( 'granular_dashboard_settings_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $dash_settings_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_dashboard_settings_off' ] );
		}
		
		$dash_about_off = granular_get_options( 'granular_about_editor_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $dash_about_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_about_editor_off' ] );
		}
		
		$upgrade_pro_nag_off = granular_get_options( 'granular_upgrade_pro_nag_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $upgrade_pro_nag_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_upgrade_pro' ] );
		}
		
		$loader_off = granular_get_options( 'granular_loader_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $loader_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_loader_off' ] );
		}
		$UAEL_Tag_off = granular_get_options( 'granular_UAEL_Tag_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $UAEL_Tag_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_UAEL_Tag_off' ] );
		}
		$Advanced_tab_off = granular_get_options( 'granular_Advanced_tab_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $Advanced_tab_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_Advanced_tab_off' ] );
		}
		$Global_tab_off = granular_get_options( 'granular_Global_tab_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $Global_tab_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_Global_tab_off' ] );
		}
		$custom_css_off = granular_get_options( 'granular_custom_css_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $custom_css_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_custom_css_off' ] );
		}
		$update_options_off = granular_get_options( 'granular_update_options_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $update_options_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_update_options_off' ] );
		}
		$save_template_off = granular_get_options( 'granular_save_template_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $save_template_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_save_template_off' ] );
		}
		$page_setting_off = granular_get_options( 'granular_page_setting_off', 'granular_editor_settings', 'no' );
		if ( 'yes' === $page_setting_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_page_setting_off' ] );
		}
		
		$loader_text = granular_get_options( 'granular_loader_text', 'granular_editor_settings', '' );
		if ( ! empty( $loader_text )) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_loader_text' ]);
		}
		
		$loader_image = granular_get_options( 'granular_loader_image', 'granular_editor_settings', '' );
		if ( ! empty( $loader_image )) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_loader_image' ]);
		}
		$loader_header = granular_get_options( 'granular_panel_header_text', 'granular_editor_settings', '' );
		if ( ! empty( $loader_header )) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_panel_header_text' ]);
		}
		$editor_hack_2 = granular_get_options( 'granular_editor_hack_2', 'granular_editor_settings', 'no' );
		if ( 'yes' === $editor_hack_2 ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'db_ui_hack_2' ] );
		}
		
		
		
		
		//elementor Basic elements
		$basic_off = granular_get_options( 'granular_main_elmenent_basic', 'granular_editor_elements_settings', '' );
		if ( '0' === $basic_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'main_elmenent_basic' ] );
		}
		
		$general_off = granular_get_options( 'granular_main_elmenent_general', 'granular_editor_elements_settings', '' );
		if ( '0' === $general_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'main_elmenent_general' ] );
		}
		
		$wordpress_off = granular_get_options( 'granular_main_elmenent_wordpress', 'granular_editor_elements_settings', '' );
		if ( '0' === $wordpress_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'main_elmenent_wordpress' ] );
		}
		
		//elementor Pro elements
		$pro_off = granular_get_options( 'granular_main_elmenent_pro-elements', 'granular_editor_elements_pro_settings', '' );
		if ( '0' === $pro_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'main_elmenent_pro' ] );
		}
		
		$site_off = granular_get_options( 'granular_main_elmenent_pro_theme-elements', 'granular_editor_elements_pro_settings', '' );
		if ( '0' === $site_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'main_elmenent_site' ] );
		}
		
		$single_off = granular_get_options( 'granular_main_elmenent_pro_theme-elements-single', 'granular_editor_elements_pro_settings', '' );
		if ( '0' === $single_off ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'main_elmenent_single' ] );
		}
		
		
		
		
		//editor BG Color
		
		if ( ! empty ( $skin )  && $skin == 'custom') {
			
			$editor_bg_color= granular_get_options( 'granular_editor_bg_color', 'granular_editor_settings', '' );
			if ( ! empty( $editor_bg_color ) ) {
				add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'granular_editor_settings_bg_color' ] );
			}
			
			//editor module buttons BG Color
			$editor_module_bg_color= granular_get_options( 'granular_editor_module_bg_color', 'granular_editor_settings', '' );
			if ( ! empty( $editor_module_bg_color ) ) {
				add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'granular_editor_settings_module_bg_color' ] );
			}
			$editor_module_text_color= granular_get_options( 'granular_editor_module_text_color', 'granular_editor_settings', '' );
			if ( ! empty( $editor_module_text_color ) ) {
				add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'granular_editor_settings_module_text_color' ] );
			}
			$editor_module_text_hcolor= granular_get_options( 'granular_editor_module_text_hcolor', 'granular_editor_settings', '' );
			if ( ! empty( $editor_module_text_hcolor ) ) {
				add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'granular_editor_settings_module_text_hcolor' ] );
			}
			
		}
		
		$editor_pcolor = granular_get_options( 'granular_editor_primary_color', 'granular_editor_settings', 'no' );
		if ( ! empty($editor_pcolor) ) {
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'granular_editor_settings_primary_color' ] );
		}
		
		
		//~ $custom_panel = granular_get_options( 'granular_welcome_on', 'granular_advanced_settings', 'no' );
		//~ if ( 'yes' === $custom_panel ) {
			//~ add_action( 'admin_notices', [ $this, 'granular_welcome_panel' ] );
			//~ add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_welcome_panel_styles' ] );
		//~ }
		
	}
	
	public function hex2rgba($color, $opacity = false) {
 
	$default = 'rgb(0,0,0)';
 
	//Return default if no color provided
	if(empty($color))
          return $default; 
    
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;   
	} 
 
	
	public function __construct() {
		$this->functions_setup_hooks();
	}
}
