<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$title_address          = miao_acf_option( 'title_address' );
$address                = miao_acf_option( 'address' );
$title_email_and_mobile = miao_acf_option( 'title_email_and_mobile' );
$title_mobile           = miao_acf_option( 'title_mobile' );
$mobile                 = miao_acf_option( 'mobile' );
$title_form             = miao_acf_option( 'title_form' );
$email                  = sanitize_email( miao_acf_option( 'e-mail' ) );
$map_zoom               = get_theme_mod( 'map_zoom_level' , 14 );


?>


<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-full-content' ) ?> >
    <div class="row">

        <?php
        if ( miao_acf_option( 'show_map' ) ):
            $map = miao_acf_option( 'map' );
            ?>
            <div class="col-lg-12  section-bottom" data-aos="fade-up">
                <div class="map-custom" id="map"
                     data-dsn-lat="<?php echo miao_acf_option_array( $map , 'lat' , '30.0594699' ) ?>"
                     data-dsn-len="<?php echo miao_acf_option_array( $map , 'lng' , '31.328505' ) ?>"
                     data-dsn-zoom="<?php echo esc_attr( $map_zoom ) ?>">
                </div>
            </div>
        <?php

        endif;
        ?>
        <div class="col-lg-12">
            <div class="heading">
                <h2>
                    <?php echo esc_html( $title_form ) ?>
                </h2>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="contact-info" data-aos="fade-up">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php if ( $title_address ) printf( '<h5>%s</h5>' , esc_html( $title_address ) ); ?>
                    <?php if ( $address ) printf( '<p>%s</p>' , apply_filters( 'miao_filter_description' , $address ) ); ?>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <?php if ( $title_email_and_mobile ): printf( '<h5>%s</h5>' , $title_email_and_mobile ); endif; ?>
                    <?php if ( $email ): ?>
                        <a class="c-button c-button--text c-button--bordered c-button--small"
                           href="mailto:<?php echo antispambot( $email , 1 ) ?>" rel="nofollow">
                            <?php echo antispambot( $email ) ?>
                        </a>
                    <?php endif; ?>

                </div>
                <div class="info-item">
                    <i class="fas fa-phone-volume"></i>
                    <?php if ( $title_mobile ): printf( '<h5>%s</h5>' , $title_mobile ); endif; ?>
                    <?php if ( $mobile ) printf( '<p>%s</p>' , esc_html( $mobile ) ); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-box" data-aos="fade-up">
                <?php
                the_content();
                ?>
            </div>


        </div>


    </div>

</div>