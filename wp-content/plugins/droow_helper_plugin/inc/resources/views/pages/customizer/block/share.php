<?php


Kirki::add_section( $dsn_section , array(
    'panel' => $dsn_panel ,
    'title' => esc_html__( 'Share Links' , 'droow' ),
    'icon'=>'dashicons-share'

) );



droow_custom_Label( $dsn_section , 'Share Links' );

Kirki::add_field( $dsn_customize , [
    'type'        => 'toggle' ,
    'settings'    => 'share_link' ,
    'label'       => esc_html__( 'Sharing Buttons' , 'droow' ) ,
    'description' => esc_html__( 'enable you to add social share buttons to WordPress' , 'droow' ) ,
    'section'     => $dsn_section ,
    'default'     => false ,
] );


Kirki::add_field( $dsn_customize , [
    'type'            => 'sortable' ,
    'settings'        => 'show_hide_share_link' ,
    'label'           => esc_html__( 'This is Share Buttons' , 'droow' ) ,
    'section'         => $dsn_section ,
    'default'         => [
        'facebook' ,
        'twitter' ,
        'google-plus' ,
        'pinterest' ,
    ] ,
    'choices'         => [
        'facebook'    => esc_html__( 'Facebook' , 'droow' ) ,
        'twitter'     => esc_html__( 'Twitter' , 'droow' ) ,
        'google-plus' => esc_html__( 'Google+' , 'droow' ) ,
        'pinterest'   => esc_html__( 'Pinterest' , 'droow' ) ,
        'get-pocket'  => esc_html__( 'Get Pocket' , 'droow' ) ,
        'telegram'    => esc_html__( 'Telegram' , 'droow' ) ,
    ] ,
    'active_callback' => [
        [
            'setting'  => 'share_link' ,
            'operator' => '==' ,
            'value'    => true ,
        ]
    ] ,
] );