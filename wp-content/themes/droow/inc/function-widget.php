<?php

add_action( 'widgets_init', function () {

    $before_widget = '<div id="%1$s" class="widget %2$s">';
    $after_widget = '</div>';
    $before_title = '<h4 class="title-s heading title-style">';
    $after_title = '</h4>';

    /**
     *
     *  Sidebar Blog
     *
     */
    register_sidebar( array(
        'id'            => 'blog-sidebar',
        'name'          => esc_html__( 'Blog Sidebar', 'droow' ),
        'description'   => esc_html__( 'This is the widgetized blog sidebar.', 'droow' ),
        'before_widget' => $before_widget,
        'after_widget'  => $after_widget,
        'before_title'  => $before_title,
        'after_title'   => $after_title,
    ) );


    $sidebar_Normal_col = get_theme_mod( 'footer-normal-columns', array( 'footer-1', 'footer-2' ) );

    foreach ( $sidebar_Normal_col as $n_c ):
        register_sidebar(
            array(
                'name'          => sprintf( esc_html__( 'Footer Normal %s Column', 'droow' ), str_replace( 'footer-', '', $n_c ) ),
                'id'            => $n_c . '-normal',
                'description'   => esc_html__( 'Appears in Page Footer.', 'droow' ),
                'before_widget' => $before_widget,
                'after_widget'  => $after_widget,
                'before_title'  => '<h4 class="footer-title">',
                'after_title'   => '</h4>',
            )
        );

    endforeach;


    $sidebar_Normal_col = get_theme_mod( 'footer-feature-columns', array( 'footer-1', 'footer-2', 'footer-3', 'footer-4' ) );

    foreach ( $sidebar_Normal_col as $n_c ):
        register_sidebar(
            array(
                'name'          => sprintf( esc_html__( 'Footer Feature %s Column', 'droow' ), str_replace( 'footer-', '', $n_c ) ),
                'id'            => $n_c . '-feature',
                'description'   => esc_html__( 'Appears in Page Footer.', 'droow' ),
                'before_widget' => $before_widget,
                'after_widget'  => $after_widget,
                'before_title'  => '<h4 class="footer-title">',
                'after_title'   => '</h4>',
            )
        );

    endforeach;


    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Bottom Row', 'droow' ),
            'id'            => 'footer-sidebar-bottom',
            'description'   => esc_html__( 'Appears in Page Footer.', 'droow' ),
            'before_widget' => $before_widget,
            'after_widget'  => $after_widget,
            'before_title'  => '<h4 class="footer-title">',
            'after_title'   => '</h4>',
        )
    );


    if ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) ) {

        register_sidebar(
            array(
                'name'          => esc_html__( 'Language Switcher', 'droow' ),
                'id'            => 'lang-switcher-sidebar',
                'description'   => esc_html__( 'Appears in the top menu.', 'droow' ),
                'before_widget' => $before_widget,
                'after_widget'  => $after_widget,
            )
        );

    }



} );