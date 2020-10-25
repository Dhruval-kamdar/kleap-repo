<?php
/*
 * All Elementor Init
 * Author & Copyright: NicheAddon
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Event_Elementor_Addon_Core_Elementor_init') ){
	class Event_Elementor_Addon_Core_Elementor_init{

		/*
		 * Minimum Elementor Version
		*/
		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		/*
		 * Minimum PHP Version
		*/
		const MINIMUM_PHP_VERSION = '5.6';

    /*
	   * Instance
	  */
		private static $instance;

		/*
		 * Main Events Addon for Elementor plugin Class Constructor
		*/
		public function __construct(){
			add_action( 'plugins_loaded', [ $this, 'init' ] );

			// Js Enqueue
			add_action( 'elementor/frontend/after_enqueue_scripts', function() {
				wp_enqueue_script( 'naevents-elementor', plugins_url( '/', __FILE__ ) . '/js/naevents-elementor.js', [ 'jquery' ], false, true );
			} );

		}

		/*
		 * Class instance
		*/
		public static function getInstance(){
			if (null === self::$instance) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/*
		 * Initialize the plugin
		*/
		public function init() {

			// Check for required Elementor version
			if ( is_plugin_active( 'elementor/elementor.php' ) ) {
				if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
					add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
					return;
				}
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
				return;
			}

			// elementor Custom Group Controls Include
			self::controls_helper();

			// elementor categories
			add_action( 'elementor/elements/categories_registered', [ $this, 'basic_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_unique_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_tec_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_em_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_eo_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_ee_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_aoec_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'pro_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_pro_aoec_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_pro_em_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_pro_eo_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_pro_tec_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naevents_pro_mec_widget_categories' ] );

			// Elementor Widgets Registered
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'naevents_basic_widgets_registered' ] );
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'naevents_widgets_registered' ] );
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'naevents_unique_widgets_registered' ] );
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'naevents_pro_widgets_registered' ] );

		}

		/*
		 * Admin notice
		 * Warning when the site doesn't have a minimum required Elementor version.
		*/
		public function admin_notice_minimum_elementor_version() {
			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'events-addon-for-elementor' ),
				'<strong>' . esc_html__( 'Events Addon for Elementor', 'events-addon-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'events-addon-for-elementor' ) . '</strong>',
				 self::MINIMUM_ELEMENTOR_VERSION
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/*
		 * Admin notice
		 * Warning when the site doesn't have a minimum required PHP version.
		*/
		public function admin_notice_minimum_php_version() {
			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
			$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'events-addon-for-elementor' ),
				'<strong>' . esc_html__( 'Events Addon for Elementor', 'events-addon-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'events-addon-for-elementor' ) . '</strong>',
				 self::MINIMUM_PHP_VERSION
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/*
		 * Class Group Controls
		*/
		public static function controls_helper(){
			$group_controls = ['lib'];
			foreach($group_controls as $control){
				if ( file_exists( plugin_dir_path( __FILE__ ) . '/lib/'.$control.'.php' ) ){
					require_once( plugin_dir_path( __FILE__ ) . '/lib/'.$control.'.php' );
				}
			}
		}

		/*
		 * Widgets elements categories
		*/
		public function basic_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-basic-category',
				[
					'title' => __( 'Basic Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}
		public function naevents_unique_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-unique-category',
				[
					'title' => __( 'Unique Event Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}
		public function naevents_tec_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-tec-category',
				[
					'title' => __( 'Events Calendar Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}
		public function naevents_em_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-em-category',
				[
					'title' => __( 'Events Manager Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}
		public function naevents_eo_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-eo-category',
				[
					'title' => __( 'Event Organiser Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}
		public function naevents_ee_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-ee-category',
				[
					'title' => __( 'Event Espresso Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}
		public function naevents_aoec_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-aoec-category',
				[
					'title' => __( 'AIOEC Elements : By Niche Addons', 'events-addon-for-elementor' ),
				]
			);
		}

		// Pro Categories
		public function pro_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-pro-category',
				[
					'title' => __( 'Pro Elements : By Niche Addons', 'events-addon-for-elemento' ),
				]
			);
		}
		public function naevents_pro_aoec_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-pro-aoec-category',
				[
					'title' => __( 'AIOEC Pro Elements : By Niche Addons', 'events-addon-for-elemento' ),
				]
			);
		}
		public function naevents_pro_em_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-pro-em-category',
				[
					'title' => __( 'Events Manager Pro Elements : By Niche Addons', 'events-addon-for-elemento' ),
				]
			);
		}
		public function naevents_pro_eo_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-pro-eo-category',
				[
					'title' => __( 'Event Organiser Pro Elements : By Niche Addons', 'events-addon-for-elemento' ),
				]
			);
		}
		public function naevents_pro_tec_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-pro-tec-category',
				[
					'title' => __( 'Events Calendar Pro Elements : By Niche Addons', 'events-addon-for-elemento' ),
				]
			);
		}
		public function naevents_pro_mec_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naevents-pro-mec-category',
				[
					'title' => __( 'Modern Events Calendar Pro Elements : By Niche Addons', 'events-addon-for-elemento' ),
				]
			);
		}

		/*
		 * Widgets registered
		*/
		public function naevents_basic_widgets_registered(){
			// init widgets
			$basic_dir = plugin_dir_path( __FILE__ ) . '/widgets/basic/';
			// Open a directory, and read its contents
			if (is_dir($basic_dir)){
			  $basic_dh = opendir($basic_dir);
		    while (($basic_file = readdir($basic_dh)) !== false){
		    	if (!in_array(trim($basic_file), ['.', '..'])) {
						$basic_template_file = plugin_dir_path( __FILE__ ) . '/widgets/basic/'.$basic_file;
						if ( $basic_template_file && is_readable( $basic_template_file ) ) {
							include_once $basic_template_file;
						}
			    }
		    }
		    closedir($basic_dh);
			}
		}

		public function naevents_widgets_registered(){
			// init widgets
			$dir = plugin_dir_path( __FILE__ ) . '/widgets/event/';
			// Open a directory, and read its contents
			if (is_dir($dir)){
			  $dh = opendir($dir);
		    while (($file = readdir($dh)) !== false){
		    	if (!in_array(trim($file), ['.', '..'])) {
						$template_file = plugin_dir_path( __FILE__ ) . '/widgets/event/'.$file;
						if ( $template_file && is_readable( $template_file ) ) {
							include_once $template_file;
						}
			    }
		    }
		    closedir($dh);
			}
		}

		public function naevents_unique_widgets_registered(){
			// init widgets
			$unique_dir = plugin_dir_path( __FILE__ ) . '/widgets/event-unique/';
			// Open a directory, and read its contents
			if (is_dir($unique_dir)){
			  $unique_dh = opendir($unique_dir);
		    while (($unique_file = readdir($unique_dh)) !== false){
		    	if (!in_array(trim($unique_file), ['.', '..'])) {
						$unique_template_file = plugin_dir_path( __FILE__ ) . '/widgets/event-unique/'.$unique_file;
						if ( $unique_template_file && is_readable( $unique_template_file ) ) {
							include_once $unique_template_file;
						}
			    }
		    }
		    closedir($unique_dh);
			}
		}

		public function naevents_pro_widgets_registered(){
			// init widgets
			$pro_dir = plugin_dir_path( __FILE__ ) . '/widgets/event-pro/';
			// Open a directory, and read its contents
			if (is_dir($pro_dir)){
			  $pro_dh = opendir($pro_dir);
		    while (($pro_file = readdir($pro_dh)) !== false){
		    	if (!in_array(trim($pro_file), ['.', '..'])) {
						$pro_template_file = plugin_dir_path( __FILE__ ) . '/widgets/event-pro/'.$pro_file;
						if ( $pro_template_file && is_readable( $pro_template_file ) ) {
							include_once $pro_template_file;
						}
			    }
		    }
		    closedir($pro_dh);
			}
		}

	} //end class

	if (class_exists('Event_Elementor_Addon_Core_Elementor_init')){
		Event_Elementor_Addon_Core_Elementor_init::getInstance();
	}

}

if ( ! function_exists( 'naevents_elementor_default_typo_color_active' ) ) {
	function naevents_elementor_default_typo_color_active() {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}
	add_action( 'after_switch_theme', 'naevents_elementor_default_typo_color_active' );
}

if ( ! function_exists( 'naevents_elementor_default_typo_color_active_after' ) ) {
	function naevents_elementor_default_typo_color_active_after() {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}
	add_action( 'pt-ocdi/after_content_import_execution', 'naevents_elementor_default_typo_color_active_after' );
}

/* Add Featured Image support in event organizer */
add_post_type_support( 'tribe_organizer', 'thumbnail' );

/* Excerpt Length */
class Event_Elementor_Addon_Excerpt {
  public static $length = 55;
  public static $types = array(
    'short' => 25,
    'regular' => 55,
    'long' => 100
  );
  public static function length($new_length = 55) {
    Event_Elementor_Addon_Excerpt::$length = $new_length;
    add_filter('excerpt_length', 'Event_Elementor_Addon_Excerpt::new_length');
    Event_Elementor_Addon_Excerpt::output();
  }
  public static function new_length() {
    if ( isset(Event_Elementor_Addon_Excerpt::$types[Event_Elementor_Addon_Excerpt::$length]) )
      return Event_Elementor_Addon_Excerpt::$types[Event_Elementor_Addon_Excerpt::$length];
    else
      return Event_Elementor_Addon_Excerpt::$length;
  }
  public static function output() {
    the_excerpt();
  }
}

// Custom Excerpt Length
if ( ! function_exists( 'naevents_excerpt' ) ) {
  function naevents_excerpt($length = 55) {
    Event_Elementor_Addon_Excerpt::length($length);
  }
}

if ( ! function_exists( 'naevents_new_excerpt_more' ) ) {
  function naevents_new_excerpt_more( $more ) {
    return '...';
  }
  add_filter('excerpt_more', 'naevents_new_excerpt_more');
}

if ( ! function_exists( 'naevents_paging_nav' ) ) {
  function naevents_paging_nav($numpages = '', $pagerange = '', $paged='') {

      if (empty($pagerange)) {
        $pagerange = 2;
      }
      if (empty($paged)) {
        $paged = 1;
      } else {
        $paged = $paged;
      }
      if ($numpages == '') {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if (!$numpages) {
          $numpages = 1;
        }
      }
      global $wp_query;
      $big = 999999999;
      if ($wp_query->max_num_pages != '1' ) { ?>
      <div class="naeep-pagination">
        <?php echo paginate_links( array(
          'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
          'format' => '?paged=%#%',
          'prev_text' => '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
          'next_text' => '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
          'current' => $paged,
          'total' => $numpages,
          'type' => 'list'
        )); ?>
      </div>
    <?php }
  }
}

if ( ! function_exists( 'naevents_clean_string' ) ) {
	function naevents_clean_string($string) {
	  $string = str_replace(' ', '', $string);
	  return preg_replace('/[^\da-z ]/i', '', $string);
	}
}

/* Validate px entered in field */
if ( ! function_exists( 'naevents_core_check_px' ) ) {
  function naevents_core_check_px( $num ) {
    return ( is_numeric( $num ) ) ? $num . 'px' : $num;
  }
}