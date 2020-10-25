<?php
if ( droow_acf_option( 'use_slider_only' ) ) return;


$class_error = ( is_404() ) ? 'dsn-error-404' : '';
$sidebar_Normal_col = get_theme_mod( 'footer-feature-columns', array( 'footer-1', 'footer-2', 'footer-3', 'footer-4' ) );


$footer_container = get_theme_mod( 'footer-container', 'container' );
?>

<footer class="wrapper-footer <?php droow_space_footer(); ?>  wrapper-footer-padding ">
    <div class="<?php echo esc_attr( $footer_container ) ?>">
        <div class="info   sidebar-single-content ">
            <div class="row ">
                <?php
                foreach ( $sidebar_Normal_col as $s_c ):?>
                    <div class="col-md-<?php echo esc_attr( 12 / count( $sidebar_Normal_col ) ) ?> dsn-col-footer">
                        <?php
                        if ( is_active_sidebar( $s_c . '-feature' ) ) :
                            dynamic_sidebar( $s_c . '-feature' );
                        endif;
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row ">
            <div class="col-12">
                <div class="copyright">
                    <?php
                    if ( is_active_sidebar( 'footer-sidebar-bottom' ) ) :
                        dynamic_sidebar( 'footer-sidebar-bottom' );
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

</footer>