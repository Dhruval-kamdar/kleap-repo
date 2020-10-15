<?php
if ( droow_acf_option( 'use_slider_only' ) ) return;


$class_error = ( is_404() ) ? 'dsn-error-404' : '';
$sidebar_Normal_col = get_theme_mod( 'footer-normal-columns', array( 'footer-1', 'footer-2' ) );

?>

<footer class="wrapper-footer <?php droow_space_footer();
echo ' ' . esc_attr( $class_error ) ?>">
    <div class="info sidebar-single-content">
        <div class="row">
            <?php
            foreach ( $sidebar_Normal_col as $s_c ):?>
                <div class="col-md-<?php echo esc_attr( 12 / count( $sidebar_Normal_col ) ) ?> dsn-col-footer">
                    <?php
                    if ( is_active_sidebar( $s_c . '-normal' ) ) :
                        dynamic_sidebar( $s_c . '-normal' );
                    endif;
                    ?>
                </div>
            <?php endforeach; ?>
        </div
    </div>
</footer>