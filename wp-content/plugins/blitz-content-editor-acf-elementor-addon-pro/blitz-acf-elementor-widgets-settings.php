<?php
namespace BZ_ACF_WZ;

define('BZ_ACF_WZ_BASE_DIR', 	dirname(__FILE__) . '/');
define('BZ_ACF_WZ_SL_PRODUCT_ID',   	'BZ-ACF-ELE-PRO');
define('BZ_ACF_WZ_EL_FILE', 'blitz-content-editor-acf-elementor-addon-pro/blitz-content-editor-acf-elementor-addon-pro.php');
define('BZ_ACF_WZ_EL_VERSION',   	'1.0.6');

class BZ_ACFWidgetSettings {
		
		public $masterslug 			= 'bz-cep-acf-elementor-settings';		
		public $acfwidgetadminSettings;

		public function init() {
		

			require_once (BZ_ACF_WZ_BASE_DIR .'admin/blitz-acf-elementor-widgets-adminsettings.php');		// Admin Panel

			$this->acfwidgetadminSettings 		=  new Admin\BZ_ACFWidgetadminSettings(BZ_ACF_WZ_SL_PRODUCT_ID);

			add_action('network_admin_menu', 	array($this->acfwidgetadminSettings,'bz_acf_wz_license_settings_page'));

			$validLicense 				= $this->acfwidgetadminSettings->bz_acf_wz_valid_license();


			if ($validLicense ) { 
			
					add_action( 'elementor/widgets/widgets_registered', array($this, 'bz_init_elementor_widgets'));
					add_action( 'elementor/elements/categories_registered', array($this, 'bz_add_elementor_widget_categories' ));
					add_action(	'wp_enqueue_scripts',   array($this, 'bz_acf_wz_load_frontend_scripts'));
			}
			

		}
		
		
		/**
		 * Include & Register Elementor's Widget files
		*/	
		public function bz_init_elementor_widgets() {

			// Basic Elementor's Plugin
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-icon-box.php' );
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-icon.php' );
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-image-box.php' );
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-counter.php' );
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-image.php' );
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-progress.php' );
			require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-testimonial.php' );
			
				
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Icon_Box() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Icon() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Image_Box() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Counter() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Image() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Progress() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Testimonial() );
			
			
			// Elementor's Pro Plugin
			if(is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
				
				//require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-flip-box.php' );
				//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Flip_Box() );
				
			}
			
			// Elementor's Essential Addons plugin
			if(is_plugin_active( 'essential-addons-elementor/essential_adons_elementor.php' ) ) {
				
				require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-infobox.php' );
				require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-dual-color-header.php' );
				//~ require_once( BZ_ACF_WZ_BASE_DIR . 'widgets/cep-progress-bar.php' );
				
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Eael_Info_Box() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Eael_Dual_Color_Header() );
				//~ \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Widget_Cep_Eael_Progress_Bar() );
				
			}
			
			
		
		}
	
	
		/**
		 * Include & Register Elementor's Widget files
		*/	
		public function bz_add_elementor_widget_categories( $elements_manager ) {

			$elements_manager->add_category(
				'content-editor-addons',
				[
					'title' => __( 'Content Editor/ACF Addons', 'plugin-name' ),
					'icon' => 'fa fa-plug',
				]
			);

		}
		
		
		
		/**
		 * Call to FRONTEND JS & CSS 
		*/
		public function bz_acf_wz_load_frontend_scripts(){
			
		   wp_enqueue_script('jquery'); //include jQuery
		   
		   wp_register_script( 'bz-acf-front-script', plugins_url( 'assets/js/frontend.js', __FILE__ ) );
		   wp_enqueue_script( 'bz-acf-front-script' );
		   
		   wp_register_style( 'bz-acf-front-style', plugins_url( 'assets/css/frontend.css', __FILE__ ) );
		   wp_enqueue_style( 'bz-acf-front-style' );
		
		}


		
	}
