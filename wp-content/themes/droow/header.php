<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="profile" href="<?php echo is_ssl() ? 'https' : 'http' ?>://gmpg.org/xfn/11">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ): ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>

    <?php wp_head(); ?>

</head>
<?php

$is_mouse_effect = get_theme_mod( 'effect_cursor' );

if ( $is_mouse_effect ) {
    $is_mouse_effect = 'true';
} else {
    $is_mouse_effect = 'false';
}


?>

<body <?php body_class(); ?> data-dsn-mousemove="<?php echo esc_attr( $is_mouse_effect ) ?>">

<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>

<?php if ( get_theme_mod( 'page_preloader', true ) ): ?>
    <div class="preloader">
        <div class="preloader-after"></div>
        <div class="preloader-before"></div>
        <div class="preloader-block">
            <div class="title"><?php echo esc_html( get_bloginfo( 'name' ) ) ?></div>
            <div class="percent v-middle"></div>
            <div class="loading"><?php esc_html_e( 'loading...', 'droow' ) ?></div>
        </div>
        <div class="preloader-bar">
            <div class="preloader-progress"></div>
        </div>
    </div>
<?php endif; ?>


<main class="main-root">

    <div class="dsn-nav-bar">
        <div class="site-header">
            <div class="extend-container">
                <div class="inner-header">
                    <div class="main-logo">
                        <?php droow_get_logo(); ?>
                    </div>
                </div>
                <nav class=" accent-menu main-navigation">
                    <?php

                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'droow-primary',
                        'menu_class'     => 'extend-container',
                        'container'      => false,
                        'depth'          => 2,
                        'fallback_cb'    => 'droow_class_nav_walker::fallback',
                        'walker'         => new \droow_class_nav_walker()
                    ) );


                    ?>
                </nav>
            </div>
        </div>
        <div class="header-top header-top-hamburger">
            <div class="header-container">
                <div class="logo main-logo">
                    <?php droow_get_logo(); ?>
                </div>


                <div class="menu-icon" data-dsn="parallax" data-dsn-move="5">
                    <div class="icon-m">
                        <i class="menu-icon-close fas fa-times"></i>
                        <span class="menu-icon__line menu-icon__line-left"></span>
                        <span class="menu-icon__line"></span>
                        <span class="menu-icon__line menu-icon__line-right"></span>
                    </div>

                    <div class="text-menu">
                        <div class="text-button"><?php esc_html_e( 'Menu', 'droow' ) ?></div>
                        <div class="text-open"><?php esc_html_e( 'Open', 'droow' ) ?></div>
                        <div class="text-close"><?php esc_html_e( 'Close', 'droow' ) ?></div>
                    </div>
                </div>

                <div class="nav">
                    <div class="inner">
                        <div class="nav__content">
                            <?php

                            wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'droow-primary-list',
                                'menu_class'     => 'nav__list',
                                'container'      => false,
                                'depth'          => 2,
                                'fallback_cb'    => 'droow_class_nav_list_walker::fallback',
                                'walker'         => new \droow_class_nav_list_walker()
                            ) );


                            ?>

                        </div>
                    </div>
                </div>
                <div class="nav-content">
                    <div class="inner-content">
                        <address class="v-middle">
                            <?php
                            $info = get_theme_mod( 'dsn_info_contact', array() );
                            if ( count( $info ) ):
                                foreach ( $info as $value ):
                                    printf( '<span>%s</span>', droow_acf_option_array( $value, 'name' ) );
                                endforeach;
                            endif;


                            ?>

                        </address>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) ) : ?>
        <nav class="dsn-multi-lang">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'dsn-lang',
                'menu_id'        => 'droow-lang',
                'menu_class'     => 'extend-container-lang',
                'container'      => false,
                'depth'          => 2,
                'fallback_cb'    => '__return_false'
            ) );
            ?>
        </nav>
    <?php endif; ?>


    <div id="dsn-scrollbar">

        <?php

        /**
         * Style Version
         */
        do_action( 'droow_style_version' );
        do_action( 'droow_menu_version' );


        $type_header = droow_type_header();

        if ( $type_header[ 'type' ] !== 'none' ) :
            get_template_part( 'template-parts/header/header', $type_header[ 'style' ] );
        endif;

        echo get_theme_mod( 'html_head_code', '' );
        ?>


        <div class="wrapper">

<?php

droow_layout_pages();
