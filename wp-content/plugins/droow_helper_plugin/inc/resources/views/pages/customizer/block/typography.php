<?php


Kirki::add_section( $dsn_section , array(
    'panel' => $dsn_panel ,
    'title' => esc_html__( 'Typography' , 'droow' ),
    'icon'=>'dashicons-color-picker'

) );

Kirki::add_field( $dsn_customize , [
    'type'        => 'toggle' ,
    'settings'    => 'event_typography' ,
    'label'       => esc_html__( 'Event Typograph' , 'droow' ) ,
    'section'     => $dsn_section ,
    'default'     => false ,
] );

Kirki::add_field( $dsn_customize , array(
    'type'     => 'typography' ,
    'settings' => 'logo_font' ,
    'section'  => $dsn_section ,
    'label'    => esc_html__( 'Logo Font' , 'droow' ) ,

    'choices' => [
        'fonts' => [
            'standard' => [ 'sans-serif' ] ,
        ] ,
    ] ,

    'default'         => array(
        'font-family' => 'Roboto' ,
        'font-size'   => '30px' ,
    ) ,
    'output'          => array(
        array(
            'element' => '.main-logo h4' ,
        ) ,
    ) ,
    'active_callback' => [
        [
            'setting'  => 'event_typography' ,
            'operator' => '==' ,
            'value'    => true ,
        ]
    ] ,

) );


Kirki::add_field( $dsn_customize , array(
    'type'     => 'typography' ,
    'settings' => 'body_font' ,
    'section'  => $dsn_section ,
    'label'    => esc_html__( 'Primary Font' , 'droow' ) ,

    'choices' => [
        'fonts' => [
            'standard' => [ 'sans-serif' ] ,

        ] ,
    ] ,

    'default'         => array(
        'font-family' => 'Montserrat' ,
        'font-size'   => '15px' ,
        'line-height' => '1.2' ,
        'font-weight' => '500' ,
    ) ,
    'output'          => array(
        array(
            'element' => 'body' ,
        ) ,
    ) ,
    'active_callback' => [
        [
            'setting'  => 'event_typography' ,
            'operator' => '==' ,
            'value'    => true ,
        ]
    ] ,

) );


Kirki::add_field( $dsn_customize , array(
    'type'     => 'typography' ,
    'settings' => 'heading_font' ,
    'section'  => $dsn_section ,
    'label'    => esc_html__( 'Heading Font' , 'droow' ) ,

    'choices' => [
        'fonts' => [
            'standard' => [ 'sans-serif' ] ,
        ] ,
    ] ,

    'default'         => array(
        'font-family' => 'Montserrat' ,
        'font-weight' => '700' ,

    ) ,
    'output'          => array(
        array(
            'element' => 'h1 , h2 , h3 , h4 , h5' ,
        ) ,
    ) ,
    'active_callback' => [
        [
            'setting'  => 'event_typography' ,
            'operator' => '==' ,
            'value'    => true ,
        ]
    ] ,

) );


Kirki::add_field( $dsn_customize , array(
    'type'     => 'typography' ,
    'settings' => 'Lloading_text' ,
    'section'  => $dsn_section ,
    'label'    => esc_html__( 'Loading Title' , 'droow' ) ,

    'choices' => [
        'fonts' => [
            'standard' => [ 'sans-serif' ] ,
        ] ,
    ] ,

    'default'         => array(
        'font-family'    => 'Open Sans' ,
        'font-weight'    => '400' ,
        'font-size'      => '25px' ,
        'letter-spacing' => '8px' ,

    ) ,
    'output'          => array(
        array(
            'element' => '.preloader .preloader-block .title' ,
        ) ,
    ) ,
    'active_callback' => [
        [
            'setting'  => 'event_typography' ,
            'operator' => '==' ,
            'value'    => true ,
        ]
    ] ,

) );


