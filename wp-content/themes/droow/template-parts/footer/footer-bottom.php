<?php
if ( droow_acf_option( 'use_slider_only' ) ) return;


$class_error = ( is_404() ) ? 'dsn-error-404' : '';

?>

<footer class="wrapper-footer <?php droow_space_footer();
echo ' ' . esc_attr( $class_error ) ?>">
    <div class="info">
        <div class="row">
            <div class="col-md-6 dsn-col-footer">
                <div class="contact-footer">
                    <?php

                    if ( $title = get_theme_mod( 'footer_tel' ) ):
                        printf( '<a href="tel:%s" class="image-zoom phone" rel="nofollow" target="_blank" data-dsn="parallax">%s</a>', esc_attr( $title ), esc_html( $title ) );
                    endif;
                    ?>


                    <?php
                    if ( $title = get_theme_mod( 'footer_email' ) ):
                        printf( '<a href="mailto:%s" class="image-zoom email" rel="nofollow" target="_blank" data-dsn="parallax">%s</a>', antispambot( $title, 1 ), antispambot( $title ) );
                    endif;
                    ?>

                </div>
                <div class="copyright-social">
                    <p><?php echo str_replace( ':y', date( 'Y' ), get_theme_mod( 'footer_cr', date( 'Y' ) . ' &copy; ' . get_bloginfo( 'name' ) ) ) ?></p>
                </div>
            </div>
            <div class="col-md-6 dsn-col-footer">
                <div class="copyright-social h-100">
                    <ul class="d-flex justify-content-end h-100 align-items-end">
                        <?php echo droow_social_setting() ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</footer>