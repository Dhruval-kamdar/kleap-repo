<?php

$show_sidebar = droow_acf_option( 'show_sidebar' , 'show' );
if ( droow_is_work() || droow_is_slider() || in_array( get_page_template_slug() , array( 'page-works.php' ) )
) {
    $show_sidebar = false;
}


if ( is_home() ) {
    $show_sidebar = get_theme_mod( 'home_show_sidebar' , 'show' );
} elseif ( is_archive() ) {
    $show_sidebar = get_theme_mod( 'archive_show_sidebar' , 'show' );
}


if ( is_active_sidebar( 'blog-sidebar' ) && $show_sidebar === 'show' ) : ?>

    <div class="dsn-button-sidebar">
        <span><i class="fa fa-arrow-left"></i></span>
    </div>
    <div class="dsn-sidebar">
        <div class="close-wind" data-dsn-close=".dsn-sidebar"></div>
        <div class="sidebar-single">
            <div class="sidebar-single-content">
                <?php dynamic_sidebar( 'blog-sidebar' ); ?>
            </div>
        </div>
    </div>

<?php endif; ?>