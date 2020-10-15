<?php


if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function droow_style_Section( $controls )
{
    $controls->start_controls_section(
        'content_style_layout',
        [
            'label' => esc_html__( 'Style Layout', 'droow' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $f = new DroowInFields();
    $f->getData( array(
        'dsn_group_key' => 'group_5e33163f500e2'
    ), $controls );


    $controls->end_controls_section();
}


function droow_motion_effects( $controls )
{
    $controls->start_controls_section(
        'content_section_motion_effects',
        [
            'label' => esc_html__( 'Image Motion Effects', 'droow' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $f = new DroowInFields();
    $f->getData( array(
        'dsn_group_key' => 'group_5e3313c7bb9a0'
    ), $controls );


    $controls->end_controls_section();
}


function droow_block_style( $controls )
{
    $controls->start_controls_section(
        'content_section_block_style',
        [
            'label' => esc_html__( 'Image Block Style', 'droow' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $f = new DroowInFields();
    $f->getData( array(
        'dsn_group_key' => 'group_5e3314e52afd8'
    ), $controls );


    $controls->end_controls_section();
}

/**
 * Droow Widget Extension
 * The main class that initiates and runs the plugin.
 */
class Droow_Widget_Loader
{
    /**
     * Instance
     *
     * @access private
     * @static
     * @var Droow_Widget_Loader The single instance of the class.
     */
    private static $_instance = null;


    /**
     * Minimum ELEMENTOR Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     */
    const MIN_PHP_VERSION = '5.6';


    /**
     * Minimum ACF Version
     */
    const MIN_ACF_VERSION = '5.0';


    /**
     * Register Block Name
     */
    const DSN_BLOCK = array(
        'Brand',
        'Intro',
        'ParallaxImageWithBox',
        'Service',
        'Team',
        'Client',
        'Experience',
        'FeaturedPosts',
        'BoxVertical',
        'Video',
        'Paragraph',
        'Gallery',
        'BoxGallery',
        'Slider',
        'Contact',
        'ParallaxImage',
        'Map',
    );

    const DSN_WIDGET = array(
        'Copyright',
        'Social',
    );


    /**
     * Instance
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @access public
     * @static
     *
     * @return Droow_Widget_Loader An instance of the class.
     */
    public static function instance()
    {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }


    /**
     * Initializing the Droow_Widget_Loader class.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct()
    {
        add_action( 'widget_loader', [ $this, 'register_widget' ] );
    }

    /**
     *
     */

    /**
     * Initialize the Widget
     * Retrieve the current widget initial configuration.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_widget()
    {

        // Check for required ACF Plugin
        if ( !class_exists( 'ACF' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_acf_req' ] );
            return;
        }


        if ( did_action( 'elementor/loaded' ) ) {
            // Check for required Elementor version
            if ( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
                add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
                return;
            }
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MIN_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_min_php_version' ] );
            return;
        }


        /**
         * registering new Elementor widgets
         */

        add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );


        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );


        // Register widgets Theme
        add_action( 'widgets_init', [ $this, 'register_widgets_theme' ] );


        /**
         * Add new controls in the end of "General Settings"
         */
        add_action( 'elementor/element/wp-page/document_settings/after_section_end', [ $this, 'register_document_settings_theme' ] );
        add_action( 'elementor/element/wp-post/document_settings/after_section_end', [ $this, 'register_document_settings_theme' ] );


        /**
         * save document Settings controls in the end of
         */
        add_action( 'elementor/editor/after_save', [ $this, 'save_after_upate' ] );

    }


    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_acf_req()
    {

        if ( isset( $_GET[ 'activate' ] ) ) {
            unset( $_GET[ 'activate' ] );
        }

        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p><p>%2$s</p></div>', sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'droow' ),
            '<strong>' . esc_html__( 'Droow Widget Extension', 'droow' ) . '</strong>',
            '<strong>' . esc_html__( 'Advanced Custom Fields', 'droow' ) . '</strong>',
            self::MIN_ACF_VERSION
        ),
            esc_html__( "Use the Advanced Custom Fields plugin to take full control of your WordPress edit screens & custom field data, - you can Gutenberg editor blocks , - Widget Extension Elementor .", 'droow' ) );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version()
    {

        if ( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }


    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_min_php_version()
    {

        if ( isset( $_GET[ 'activate' ] ) ) {
            unset( $_GET[ 'activate' ] );
        }

        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p><p>%2$s</p></div>', sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'droow' ),
            '<strong>' . esc_html__( 'Droow Widget Extension', 'droow' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'droow' ) . '</strong>',
            self::MIN_PHP_VERSION
        ),
            esc_html__( "these older versions have reached official End Of Life and as such may expose your site to security vulnerabilities and bugs, and may not always work as expected.", 'droow' ) );

    }

    /**
     * Register Custom Widget Categories
     *
     * @access public
     *
     * @return void
     */
    public function register_widget_categories( $elements_manager )
    {

        $elements_manager->add_category(
            'droow_cat',
            [
                'title' => esc_html__( 'Droow (Design Grid)', 'droow' ),
                'icon'  => 'fa fa-plug',
            ]
        );
    }


    public function register_widgets()
    {

        require_once __DIR__ . '/widgets/control/DroowControl.php';
        require_once __DIR__ . '/widgets/control/DroowInFields.php';


        foreach ( self::DSN_BLOCK as $block ):
            if ( $block ) {
                $block = 'Droow' . $block;
                require_once __DIR__ . '/widgets/' . $block . '.php';
                \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $block() );
            }

        endforeach;


    }


    public function register_widgets_theme()
    {

        foreach ( self::DSN_WIDGET as $wid ):
            if ( $wid ) {
                $wid = 'Droow' . $wid;
                require_once __DIR__ . '/widgets/theme/' . $wid . '.php';
                register_widget( $wid );
            }

        endforeach;
    }

    public function register_document_settings_theme( \Elementor\Core\DocumentTypes\PageBase $page )
    {


        // Get the page settings manager
        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

        // Get the settings model for current post
        $page_settings_model = $page_settings_manager->get_model( $page->get_main_id() );


        $page_settings_model->set_settings( 'droow_menu_type', droow_acf_option( 'menu_type', 'hamburger-menu', $page->get_main_id() ) );
        $page_settings_model->set_settings( 'droow_background_color', droow_acf_option( 'background_color', 'background_color', $page->get_main_id() ) );


        $page->start_controls_section(
            'droow_lay',
            [
                'label' => esc_html__( 'Style Theme', 'droow' ),
                'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );


        /**
         * Page Color Theme
         */
        $page->add_control(
            'droow_menu_type',
            [
                'label'   => esc_html__( 'Type Nav', 'droow' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'Classic-menu'   => esc_html__( 'Classic Menu', 'droow' ),
                    'hamburger-menu' => esc_html__( 'Hamburger Menu', 'droow' )
                ],
                'default' => droow_acf_option( 'menu_type', 'hamburger-menu' ),
            ]
        );


        /**
         * Page Color Theme
         */
        $page->add_control(
            'droow_background_color',
            [
                'label'   => esc_html__( 'Page Color Theme', 'droow' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'dark'  => esc_html__( 'Dark', 'droow' ),
                    'light' => esc_html__( 'light', 'droow' )
                ],
                'default' => droow_acf_option( 'background_color', 'dark' ),
            ]
        );


        $page->end_controls_section();

    }

    public function save_after_upate( $post_id )
    {
        $this->set_update_value( array( 'menu_type', 'background_color' ), $post_id );
    }

    private function set_update_value( array $keys, $post_id )
    {


        // Get the page settings manager
        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

        // Get the settings model for current post
        $page_settings_model = $page_settings_manager->get_model( $post_id );

        if ( count( $keys ) ):
            foreach ( $keys as $key ):
                update_field( $key, $page_settings_model->get_settings( 'droow_' . $key ), $post_id );
            endforeach;
        endif;


    }

}

Droow_Widget_Loader::instance();


