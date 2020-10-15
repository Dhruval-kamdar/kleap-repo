<?php

add_action( 'after_setup_theme', function () {
    register_nav_menus( array(
        'primary'  => esc_html__( 'Header Navigation Menu', 'droow' ),
        'dsn-lang' => esc_html__( 'Lang Navigation Menu', 'droow' ),
    ) );
} );

