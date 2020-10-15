<?php
$attr = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$items = new DroowShortCode( $shortcode->getItems( $content ) );
//dd($items);
?>
<section
        class="intro-about <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
    <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">

        <div class="row">
            <div class="col-lg-6">
                <div class="intro-content-text">
                    <?php if ( $title = $shortcode->Title() ) : ?>

                        <h2 data-dsn-grid="move-section" data-dsn-move="-30" data-dsn-duration="100%"
                            data-dsn-opacity="1.2" data-dsn-responsive="tablet">
                            <?php printf( $title ) ?>
                        </h2>
                    <?php endif;
                    if ( $description = $shortcode->Description() )
                        printf( '<p class="span-small" %s>%s</p>', $shortcode->DescriptionAnimate(), esc_html( $description ) );

                    if ( $title = $items->Title() )
                        printf( '<h6 data-dsn-animate="%s">%s</h6>', $items->titleAnimate(), esc_html( $title ) );

                    if ( $title = $items->TitleCover() )
                        printf( '<small data-dsn-animate="%s">%s</small>', $items->titleAnimate(), esc_html( $title ) );
                    ?>

                    <div class="exper">

                        <?php if ( $items->overlay() ) : ?>
                            <div class="numb-ex">
                                <?php printf( '<span class="word" data-dsn-animate="%s">%s</span>', $items->DescriptionAnimate(), $items->overlay() ) ?>
                            </div>
                        <?php endif; ?>
                        <?php
                        if ( $title = $items->Description() )
                            printf( '<h4 data-dsn-animate="%s">%s</h4>', $items->DescriptionAnimate(), $title );
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="background-mask">
        <div class="img-box h-100">
            <div class="img-cent h-100" data-dsn-grid="move-up">
                <div class="img-container h-100">
                    <?php
                    echo wp_get_attachment_image( $shortcode->Image(), $shortcode->sizeImage(), false, array( 'data-dsn-y' => '30%' ) )
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

