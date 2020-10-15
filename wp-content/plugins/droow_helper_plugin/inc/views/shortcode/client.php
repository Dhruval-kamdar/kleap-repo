<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$items = $shortcode->getItems( $content );

?>
<?php
//--> show Caption  = show Image
if ( $shortcode->showCaption() ):?>

    <section
            class="client-see dsn-arrow <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
        <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">
            <div class="inner">
                <?php if ( $title = $shortcode->Title() ) : ?>
                    <div class="left">
                        <h2 class="title" data-dsn-grid="move-section" data-dsn-move="-60"
                            data-dsn-duration="100%" data-dsn-opacity="1" data-dsn-responsive="tablet">
                            <span class="text"><?php echo esc_html( $title ) ?></span>
                        </h2>
                    </div>

                <?php endif; ?>

                <div class="items">
                    <div class="slick-slider">
                        <?php
                        if ( count( $items ) ):
                            foreach ( $items as $item ):
                                $shortcode = new DroowShortCode( $item );
                                ?>
                                <div class="item">

                                    <?php
                                    if ( $description = $shortcode->Description() )
                                        printf( '<div class="quote"><p >%s</p></div>' , $description );
                                    ?>
                                    <div class="bottom f-align-center">
                                        <?php
                                        if ( $id = $shortcode->Image() )
                                            printf( '<div class="avatar">%s</div>' , wp_get_attachment_image( $id ) );

                                        if ( $title = $shortcode->Title() )
                                            printf( '<div class="label"><div class="cell">%s %s</div></div>' , $title , $shortcode->TitleCover() );
                                        ?>
                                    </div>
                                </div>

                            <?php
                            endforeach;
                        endif;
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <section
            class="our-client <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
        <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">
            <?php $shortcode->getBlocTitle(); ?>

            <div class="client-curs">
                <?php
                if ( count( $items ) ):
                    foreach ( $items as $item ):
                        $shortcode = new DroowShortCode( $item );
                        ?>

                        <div class="client-item">
                            <div>
                                <?php
                                if ( $description = $shortcode->Description() )
                                    printf( '<p >%s</p>' , $description );
                                if ( $title = $shortcode->Title() )
                                    printf( '<h5>%s</h5>' , $title );

                                if ( $po = $shortcode->TitleCover() )
                                    printf( '<span>%s</span>' , $po );
                                ?>
                            </div>
                        </div>

                    <?php
                    endforeach;
                endif;
                ?>

            </div>
        </div>
    </section>

<?php endif; ?>
