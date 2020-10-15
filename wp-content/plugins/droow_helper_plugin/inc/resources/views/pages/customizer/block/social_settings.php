<?php
Kirki::add_section( $dsn_section , array(
    'panel' => $dsn_panel ,
    'title' => esc_html__( 'Social Settings' , 'droow') ,
    'icon'=>'dashicons-networking'

) );


Kirki::add_field( $dsn_customize , [
    'type'     => 'radio' ,
    'settings' => 'display_social_footer' ,
    'label'    => esc_html__( 'Display Social ( Footer )' , 'droow') ,
    'section'  => $dsn_section ,
    'default'  => 'icon' ,
    'choices'  => [
        'name'      => esc_html__( 'Social Name' , 'droow') ,
        'init_name' => esc_html__( 'initial Social Name' , 'droow') ,
        'icon'      => esc_html__( 'Icon Social' , 'droow')
    ] ,
] );

Kirki::add_field( $dsn_customize , [
    'type'     => 'radio' ,
    'settings' => 'display_social_slider' ,
    'label'    => esc_html__( 'Display Social ( Slider )' , 'droow') ,
    'section'  => $dsn_section ,
    'default'  => 'icon' ,
    'choices'  => [
        'name'      => esc_html__( 'Social Name' , 'droow') ,
        'init_name' => esc_html__( 'initial Social Name' , 'droow') ,
        'icon'      => esc_html__( 'Icon Social' , 'droow')
    ] ,
] );


Kirki::add_field( $dsn_customize , [
    'type'         => 'repeater' ,
    'label'        => esc_html__( 'All Social' , 'droow') ,
    'section'      => $dsn_section ,
    'transport'    => 'postMessage' ,
    'row_label'    => [
        'type'      => 'field' ,
        'value'     => esc_html__( 'Social Settings' , 'droow') ,
        'field'     => 'name' ,
        'transport' => 'postMessage' ,
    ] ,
    'button_label' => esc_html__( '"Add new" (Social) ' , 'droow') ,
    'settings'     => 'dsn_social' ,
    'fields'       => [
        'name'           => [
            'type'      => 'text' ,
            'label'     => esc_html__( 'Social Name' , 'droow') ,
            'default'   => '' ,
            'required'  => true ,
            'transport' => 'postMessage' ,

        ] ,
        'init_name'      => [
            'type'      => 'text' ,
            'label'     => esc_html__( 'initial Social Name' , 'droow') ,
            'default'   => '' ,
            'transport' => 'postMessage' ,

        ] ,
        'icon'           => [
            'type'        => 'text' ,
            'label'       => esc_html__( 'Icon Social' , 'droow') ,
            'description' => __( 'You can control this icons from <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" >This Page </a> copy into class like fab fa-500px' , 'droow') ,
            'default'     => '' ,
            'transport'   => 'postMessage' ,


        ] ,
        'link'           => [
            'type'    => 'textarea' ,
            'label'   => esc_html__( 'URL' , 'droow') ,
            'default' => '' ,

        ] ,
        'show_in_slider' => [
            'type'      => 'checkbox' ,
            'label'     => esc_html__( 'Show In Slider' , 'droow') ,
            'default'   => 1 ,
            'transport' => 'postMessage' ,

        ] ,
        'show_in_footer' => [
            'type'      => 'checkbox' ,
            'label'     => esc_html__( 'Show In Footer' , 'droow') ,
            'default'   => 1,
            'transport' => 'postMessage' ,

        ] ,
    ]
] );
