<?php

/**
 *
 *
 * ==============================================
 *
 *          Customize Register
 *
 * ===============================================
 *
 * The 'customize_register' action hook is used to customize and manipulate the Theme
 * Customization admin screen introduced .
 *
 */

if ( class_exists( 'Kirki' ) ):


    $dsn_customize = 'dsn_customize';
    $index = 0;
    function droow_custom_Label( $section, $text = '', $label = '', $active_callback = true )
    {
        $out = '';
        if ( $text ) {
            $out = '<div style="padding: 10px;background-color: #008ec2;color: #fff;border-radius: 3px;text-align: center;font-size: 14px;text-transform: capitalize;">' . $text . '</div><p>'.$label.'</p>';
        }

        Kirki::add_field( 'dsn_customize', [
            'type'            => 'custom',
            'settings'        => 'custom_setting_' . sanitize_title( $text ) . sanitize_title( $label ),
            'section'         => $section,
            'default'         => $out,
            'active_callback' => $active_callback
        ] );
    }


    /**
     * Update Kirki Config
     */

    Kirki::add_config( $dsn_customize, array(
        'capability'  => 'edit_theme_options',
        'option_type' => 'theme_mod',
    ) );


    $dsn_panel = 'dsn_panel';
    Kirki::add_panel( $dsn_panel, array(
        'priority'    => 10,
        'title'       => esc_html__( 'Droow Customize Option', 'droow' ),
        'description' => esc_html__( 'My Options Theme', 'droow' ),
        'icon'=>'dashicons-buddicons-topics'
    ) );


    /**
     * General options
     */

    $dsn_section = 'general_options';
    droow_resources_customize( 'general_options', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * ajax_options
     */

    $dsn_section = 'ajax_options';
    droow_resources_customize( 'ajax_options', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * event_smooth_scrolling
     */


//    $dsn_section = 'event_smooth_scrolling';
//    droow_resources_customize( 'event_smooth_scrolling' , array(
//        'dsn_panel'     => $dsn_panel ,
//        'dsn_customize' => $dsn_customize ,
//        'dsn_section'   => $dsn_section
//    ) );


    /**
     * Header Image
     */
    $dsn_section = 'header_image';
    droow_resources_customize( 'header_image', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * Fonts_setting
     */
    $dsn_section = 'section_fonts';
    droow_resources_customize( 'typography', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * social_settings
     */
    $dsn_section = 'social_settings';
    droow_resources_customize( 'social_settings', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * Map_settings
     */
    $dsn_section = 'map_setting';
    droow_resources_customize( 'map_setting', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * footer_settings
     */
    $dsn_section = 'share_setting';
    droow_resources_customize( 'share', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * footer_settings
     */
    $dsn_section = 'footer_setting';
    droow_resources_customize( 'footer_setting', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


    /**
     * options_page
     */
    $dsn_section = 'options_page';
    droow_resources_customize( 'page_options', array(
        'dsn_panel'     => $dsn_panel,
        'dsn_customize' => $dsn_customize,
        'dsn_section'   => $dsn_section
    ) );


endif;


add_action( 'wp_head', function () {
    $width_logo = get_theme_mod( 'width_number', 80 );
    $width_logo = ( (int)$width_logo === 0 ) ? 'auto' : $width_logo . 'px';
    $height_logo = get_theme_mod( 'height_number', 0 );
    $height_logo = ( (int)$height_logo === 0 ) ? 'auto' : $height_logo . 'px';
    $logo_size_number = get_theme_mod( 'logo_size_number', 30 );

    printf( '<style>.custom-logo,.site-header .extend-container .inner-header .main-logo{width:%s !important;height: %s !important}.custom-logo-link h1.dsn-text-size{font-size:%s}</style>', $width_logo, $height_logo, $logo_size_number . 'px' );
    printf( '<style>%s</style>', get_theme_mod( 'css_head_code' ) );
    printf( '<script>%s</script>', get_theme_mod( 'js_head_code' ) );


} );


