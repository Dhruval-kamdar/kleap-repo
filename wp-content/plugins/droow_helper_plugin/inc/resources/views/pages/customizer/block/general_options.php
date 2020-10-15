<?php

Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'General Options', 'droow' ),
    'icon'  => 'dashicons-buddicons-topics'
) );

Kirki::add_field( $dsn_customize, [
    'type'        => 'text',
    'settings'    => 'droow-project-slug',
    'label'       => esc_html__( 'Portfolio custom slug', 'droow' ),
    'description' => esc_html__( 'if you want your portfolio post type to have a custom slug in the url.', 'droow' ),
    'section'     => $dsn_section,
    'default'     => droow_project_slug()
] );


Kirki::add_field( $dsn_customize, [
    'type'        => 'text',
    'settings'    => 'droow-category-slug',
    'label'       => esc_html__( 'Portfolio custom slug', 'droow' ),
    'description' => esc_html__( 'if you want your portfolio post type to have a custom slug in the url.', 'droow' ),
    'section'     => $dsn_section,
    'default'     => droow_category_slug()
] );


Kirki::add_field( $dsn_customize, [
    'type'        => 'toggle',
    'settings'    => 'stop_parallax_image',
    'label'       => esc_html__( 'Stop Parallax Image', 'droow' ),
    'description' => esc_html__( 'Stop Paralax Image in ( Tablet , Mobile )', 'droow' ),
    'section'     => $dsn_section,
    'default'     => '1',
] );


Kirki::add_field( $dsn_customize, [
    'type'        => 'toggle',
    'settings'    => 'page_preloader',
    'label'       => esc_html__( 'Page Preloader', 'droow' ),
    'description' => esc_html__( 'Enable preloader mask while the page is loading.', 'droow' ),
    'section'     => $dsn_section,
    'default'     => '1',
] );

Kirki::add_field( $dsn_customize, [
    'type'     => 'toggle',
    'settings' => 'button_style_theme',
    'label'    => esc_html__( 'Show Button Style Theme', 'droow' ),
    'section'  => $dsn_section,
    'default'  => 0,
] );

Kirki::add_field( $dsn_customize, [
    'type'     => 'toggle',
    'settings' => 'effect_cursor',
    'label'    => esc_html__( 'Effect Cursor', 'droow' ),
    'section'  => $dsn_section,
    'default'  => 0
    ,
] );


Kirki::add_field( $dsn_customize, [
    'type'        => 'toggle',
    'settings'    => 'event_smooth_scrolling',
    'label'       => esc_html__( 'Event Smooth Scrolling', 'droow' ),
    'description' => esc_html__( 'Distance. Use smaller value for shorter scroll and greater value for longer scroll', 'droow' ),
    'section'     => $dsn_section,
    'default'     => false,
] );

droow_custom_Label( $dsn_customize, esc_html__( 'Info Menu', 'droow' ) );

Kirki::add_field( $dsn_customize, [
    'type'         => 'repeater',
    'label'        => esc_html__( 'All Information', 'droow' ),
    'section'      => $dsn_section,
    'transport'    => 'postMessage',
    'row_label'    => [
        'type'      => 'field',
        'value'     => esc_html__( 'Settings', 'droow' ),
        'field'     => 'name',
        'transport' => 'postMessage',

    ],
    'button_label' => esc_html__( '"Add new" (Info) ', 'droow' ),
    'settings'     => 'dsn_info_contact',
    'fields'       => [
        'name' => [
            'type'      => 'text',
            'label'     => esc_html__( 'Value', 'droow' ),
            'default'   => '',
            'required'  => true,
            'transport' => 'postMessage',

        ],
    ]
] );
