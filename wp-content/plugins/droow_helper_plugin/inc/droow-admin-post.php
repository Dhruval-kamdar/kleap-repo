<?php


$dsn_projects = droow_project_slug();
$custom_slug  = droow_custom_project_slug();
add_theme_support( 'post-thumbnails' , $dsn_projects );

add_action( 'init' , function () use ( $dsn_projects , $custom_slug ) {


    /**
     * Add Page Projects
     */

    $args = array(
        'menu_icon'       => DROOW__PLUGIN_DIR_URL . '/assets/img/work.png' ,
        'hierarchical'    => true ,
        'capability_type' => 'post' ,
        'supports'        => array( 'title' , 'editor' , 'author' , 'thumbnail' , 'revisions' ) ,
        'labels'          => array(
            'name'         => esc_html__( 'Works' , 'droow' ) ,
            'new_item'     => esc_html__( 'New Work' , 'droow' ) ,
            'add_new'      => esc_html__( 'Add Work' , 'droow' ) ,
            'add_new_item' => esc_html__( 'Add New Work' , 'droow' ) ,
        ) ,
        'rewrite'         => array( 'slug' => $custom_slug , 'with_front' => false ) ,
        'show_in_rest'    => true ,
        'public'          => true ,

    );


    register_post_type( $dsn_projects , $args );
    flush_rewrite_rules();

    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
        'name'                       => esc_html__( 'Categories' , 'droow' ) ,
        'singular_name'              => esc_html__( 'Categories' , 'droow' ) ,
        'search_items'               => esc_html__( 'Search Categories' , 'droow' ) ,
        'popular_items'              => esc_html__( 'Popular Categories' , 'droow' ) ,
        'all_items'                  => esc_html__( 'All Categories' , 'droow' ) ,
        'parent_item'                => null ,
        'parent_item_colon'          => null ,
        'edit_item'                  => esc_html__( 'Edit Categories' , 'droow' ) ,
        'update_item'                => esc_html__( 'Update Categories' , 'droow' ) ,
        'add_new_item'               => esc_html__( 'Add New Categories' , 'droow' ) ,
        'new_item_name'              => esc_html__( 'New Categories Name' , 'droow' ) ,
        'separate_items_with_commas' => esc_html__( 'Separate Categories with commas' , 'droow' ) ,
        'add_or_remove_items'        => esc_html__( 'Add or remove Categories' , 'droow' ) ,
        'choose_from_most_used'      => esc_html__( 'Choose from the most used Categories' , 'droow' ) ,
        'not_found'                  => esc_html__( 'No Categories found.' , 'droow' ) ,
        'menu_name'                  => esc_html__( 'Categories' , 'droow' ) ,
    );

    $cat_slug = droow_category_slug();
    $args     = array(
        'hierarchical' => true ,
        'labels'       => $labels ,
        'show_ui'      => true ,
        'query_var'    => true ,
        'show_in_rest' => true ,
        'rewrite'         => array( 'slug' => droow_custom_category_slug() , 'with_front' => false ) ,


    );


    register_taxonomy( $cat_slug , $dsn_projects , $args );


} );


