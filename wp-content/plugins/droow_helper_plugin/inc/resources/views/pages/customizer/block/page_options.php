<?php

/**
 *  Home Page
 */

Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Option Pages', 'droow' ),
    'icon'  => 'dashicons-screenoptions'

) );


droow_custom_Label( $dsn_section, 'Work Portfolio' );
Kirki::add_field( $dsn_customize, [
    'type'     => 'toggle',
    'settings' => 'hide_next_project',
    'label'    => esc_html__( 'Hide Next Project', 'droow' ),
    'section'  => $dsn_section,
    'default'  => false,
] );

droow_custom_Label( $dsn_section, 'Home' );


Kirki::add_field( $dsn_customize, [
    'type'     => 'select',
    'settings' => 'home_show_sidebar',
    'label'    => esc_html__( 'Sidebar', 'droow' ),
    'section'  => $dsn_section,
    'default'  => 'show',
    'multiple' => 1,
    'choices'  => [
        'show' => esc_html__( 'Show', 'droow' ),
        'hide' => esc_html__( 'Hide', 'droow' )
    ]
] );

Kirki::add_field( $dsn_customize, [
    'type'              => 'text',
    'settings'          => 'home_custom_title',
    'label'             => esc_html__( 'Title', 'droow' ),
    'section'           => $dsn_section,
    'default'           => 'Our Blog.',
    'sanitize_callback' => 'droow_sanitize_minimal_decoration',
] );


Kirki::add_field( $dsn_customize, [
    'type'     => 'text',
    'settings' => 'home_custom_subtitle',
    'label'    => esc_html__( 'Subtitle', 'droow' ),
    'section'  => $dsn_section,
    'default'  => 'NEWS & IDEAS',
] );

Kirki::add_field( $dsn_customize, [
    'type'            => 'textarea',
    'settings'        => 'home_custom_desc',
    'label'           => esc_html__( 'Description', 'droow' ),
    'section'         => $dsn_section,
    'default'         => 'We provide a free day to experience our benefits of digital world.',
    'active_callback' => [
        [
            'setting'  => 'home_header_type',
            'operator' => '==',
            'value'    => 'normal',
        ]
    ],
] );


droow_custom_Label( $dsn_section, 'Archive' );


Kirki::add_field( $dsn_customize, [
    'type'     => 'select',
    'settings' => 'archive_show_sidebar',
    'label'    => esc_html__( 'Sidebar', 'droow' ),
    'section'  => $dsn_section,
    'default'  => 'show',
    'multiple' => 1,
    'choices'  => [
        'show' => esc_html__( 'Show', 'droow' ),
        'hide' => esc_html__( 'Hide', 'droow' )
    ]
] );

droow_custom_Label( $dsn_section, '404' );
Kirki::add_field( $dsn_customize, [
    'type'     => 'image',
    'settings' => '404_bg_image',
    'label'    => esc_html__( 'Background Image', 'droow' ),
    'section'  => $dsn_section,
    'default'  => DROOW__PLUGIN_DIR_URL . 'assets/img/404.jpg',

] );

droow_custom_Label( $dsn_section, 'Code Html' );

Kirki::add_field( $dsn_customize, [
    'type'      => 'code',
    'settings'  => 'html_head_code',
    'label'     => esc_html__( 'Code HTML Header', 'droow' ),
    'section'   => $dsn_section,
    'default'   => '',
    'transport' => 'postMessage',
    'choices'   => [
        'language' => 'html',
    ],
    'priority'  => 160,
] );


Kirki::add_field( $dsn_customize, [
    'type'      => 'code',
    'settings'  => 'html_footer_code',
    'label'     => esc_html__( 'Code HTML Footer', 'droow' ),
    'section'   => $dsn_section,
    'default'   => '',
    'transport' => 'postMessage',
    'choices'   => [
        'language' => 'html',
    ],
    'priority'  => 161,
] );


