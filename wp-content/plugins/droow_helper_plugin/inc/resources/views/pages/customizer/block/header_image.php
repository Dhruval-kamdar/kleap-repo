<?php


Kirki::add_section( $dsn_section , array(
    'panel' => $dsn_panel ,
    'title' => esc_html__( 'Header Image' , 'droow' ) ,
    'icon'=>'dashicons-welcome-widgets-menus'

) );


Kirki::add_field( $dsn_customize , [
    'type'     => 'image' ,
    'settings' => 'custom_logo' ,
    'label'    => esc_html__( 'Light Logo' , 'droow' ) ,
    'section'  => $dsn_section ,
    'default'  => '' ,
    'choices'  => [
        'save_as' => 'id' ,
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'     => 'image' ,
    'settings' => 'custom_logo_dark' ,
    'label'    => esc_html__( 'Dark Logo' , 'droow' ) ,
    'section'  => $dsn_section ,
    'default'  => '' ,
    'choices'  => [
        'save_as' => 'id' ,
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'        => 'number' ,
    'settings'    => 'width_number' ,
    'label'       => esc_html__( 'Logo Width' , 'droow' ) ,
    'description' => esc_html__( '0 for auto width' , 'droow' ) ,
    'section'     => $dsn_section ,
    'default'     => 80 ,
    'choices'     => [
        'min'  => 0 ,
        'step' => 10 ,
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'        => 'number' ,
    'settings'    => 'height_number' ,
    'label'       => esc_html__( 'Logo Height' , 'droow' ) ,
    'description' => esc_html__( '0 for auto Height' , 'droow' ) ,
    'section'     => $dsn_section ,
    'default'     => 0 ,
    'choices'     => [
        'min'  => 0 ,
        'step' => 10 ,
    ] ,
] );

Kirki::add_field( $dsn_customize , [
    'type'     => 'number' ,
    'settings' => 'logo_size_number' ,
    'label'    => esc_html__( 'Logo text size' , 'droow' ) ,
    'section'  => $dsn_section ,
    'default'  => 30 ,
    'choices'  => [
        'min'  => 0 ,
        'step' => 10 ,
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'     => 'image' ,
    'settings' => 'admin_custom_logo' ,
    'label'    => esc_html__( 'Admin Login Logo' , 'droow' ) ,
    'section'  => $dsn_section ,
    'default'  => '' ,

] );


Kirki::add_field( $dsn_customize , [
    'type'        => 'number' ,
    'settings'    => 'admin_width_number' ,
    'label'       => esc_html__( 'Admin Logo Width' , 'droow' ) ,
    'description' => esc_html__( '0 for auto width' , 'droow' ) ,
    'section'     => $dsn_section ,
    'default'     => 80 ,
    'choices'     => [
        'min'  => 0 ,
        'step' => 10 ,
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'        => 'number' ,
    'settings'    => 'admin_height_number' ,
    'label'       => esc_html__( 'Admin Logo Height' , 'droow' ) ,
    'description' => esc_html__( '0 for auto Height' , 'droow' ) ,
    'section'     => $dsn_section ,
    'default'     => 80 ,
    'choices'     => [
        'min'  => 0 ,
        'step' => 10 ,
    ] ,
] );



Kirki::add_field( $dsn_customize , [
    'type'      => 'code' ,
    'settings'  => 'html_head_code' ,
    'label'     => esc_html__( 'Code HTML Header' , 'droow' ) ,
    'section'   => $dsn_section ,
    'default'   => '' ,
    'transport' => 'auto' ,
    'choices'   => [
        'language' => 'html' ,
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'      => 'code' ,
    'settings'  => 'css_head_code' ,
    'label'     => esc_html__( 'Code CSS Header' , 'droow' ) ,
    'section'   => $dsn_section ,
    'default'   => '' ,
    'transport' => 'auto' ,
    'choices'   => [
        'language' => 'css' ,
    ] ,
] );

Kirki::add_field( $dsn_customize , [
    'type'      => 'code' ,
    'settings'  => 'js_head_code' ,
    'label'     => esc_html__( 'Code JS Header' , 'droow' ) ,
    'section'   => $dsn_section ,
    'transport' => 'auto' ,
    'default'   => '' ,
    'choices'   => [
        'language' => 'js' ,
    ] ,
] );


add_action( 'wp_head' , function () {
    $width_logo       = get_theme_mod( 'width_number' , 80 );
    $width_logo       = ( (int) $width_logo === 0 ) ? 'auto' : $width_logo . 'px';
    $height_logo      = get_theme_mod( 'height_number' , 0 );
    $height_logo      = ( (int) $height_logo === 0 ) ? 'auto' : $height_logo . 'px';
    $logo_size_number = get_theme_mod( 'logo_size_number' , 30 );

    printf( '<style>.custom-logo,.site-header .extend-container .inner-header .main-logo{width:%s !important;height: %s !important}.main-logo h4{font-size:%s}</style>' , $width_logo , $height_logo , $logo_size_number . 'px' );
    printf( '<style>%s</style>' , get_theme_mod( 'css_head_code' ) );
    printf( '<script>%s</script>' , get_theme_mod( 'js_head_code' ) );


} );


add_action( 'wp_footer' , function () {
    printf( '<script>%s</script>' , get_theme_mod( 'js_footer_code' ) );
} );

add_action( 'droow_add_code_header' , function () {
    echo get_theme_mod('html_head_code');
} );
