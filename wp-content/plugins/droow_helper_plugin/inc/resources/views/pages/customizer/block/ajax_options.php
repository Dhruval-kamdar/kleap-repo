<?php


Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Ajax Options', 'droow' ),
    'icon'  => 'dashicons-align-none'

) );

Kirki::add_field( $dsn_customize, [
    'type'        => 'toggle',
    'settings'    => 'ajax_pages',
    'label'       => esc_html__( 'Load Pages With Ajax', 'droow' ),
//    'description' => esc_html__( 'When navigate like (menu , link) loads ', 'droow' ),
    'section'     => $dsn_section,
    'default'     => false,
] );

//
//Kirki::add_field( $dsn_customize , [
//    'type'            => 'toggle' ,
//    'settings'        => 'ajax_menu' ,
//    'label'           => esc_html__( 'Load Menu With Ajax' , 'droow' ) ,
//    'description'     => esc_html__( 'When navigate from Menu loads the target content without reloading the current page.' , 'droow' ) ,
//    'section'         => $dsn_section ,
//    'default'         => false ,
//    'active_callback' => [
//        [
//            'setting'  => 'ajax_pages' ,
//            'operator' => '==' ,
//            'value'    => true ,
//        ]
//    ] ,
//] );
