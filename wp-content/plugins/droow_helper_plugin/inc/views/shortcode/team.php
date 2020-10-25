<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$items = $shortcode->getItems( $content );
?>


<section class="our-team dsn-arrow <?php echo esc_attr( $shortcode->className() ) ?> section-padding" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
    <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">

        <?php $shortcode->getBlocTitle() ?>


        <div class="custom-container">
            <div class="slick-slider">
                <?php
                if ( count( $items ) ):
                    foreach ( $items as $item ):
                        $shortcode = new DroowShortCode( $item );
                        if ( $id = $shortcode->Image() ):
                            ?>

                            <div class="team-item slick-slide">
                                <div class="box-img">
                                    <?php echo wp_get_attachment_image( $id , 'medium_large' ) ?>
                                </div>

                                <div class="box-content">
                                    <?php
                                    if ( $name = $shortcode->Title() )
                                        printf( '<h4>%s</h4>' , esc_html( $name ) );
                                    if ( $name = $shortcode->TitleCover() )
                                        printf( '<p>%s</p>' , esc_html( $name ) );
                                    ?>
                                </div>
                            </div>

                        <?php
                        endif;
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</section>