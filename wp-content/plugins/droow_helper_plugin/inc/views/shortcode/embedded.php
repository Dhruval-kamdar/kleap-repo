<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortCode = new DroowShortCode( $attr );

$ids = explode( ',' , $shortCode->Image() );

$caption_text = $shortCode->Caption();
$title        = $shortCode->titleCaption();
$description  = $shortCode->descriptionCaption();


$arrayImage = array_filter( array(
    'class'          => $shortCode->parallaxType() ,
    'data-dsn-scale' => $shortCode->scale() ,
    'data-dsn-y'     => $shortCode->transY() ,
    'style'          => $shortCode->style()

) );


foreach ( $ids as $id ): if ( $id && wp_get_attachment_image_src( $id ) ):
    ?>


    <div class="<?php echo esc_attr( $shortCode->layout() . ' ' . $shortCode->className() ) ?>"
        <?php echo esc_attr( $shortCode->changeColor() ) ?> <?php echo esc_attr( $shortCode->durationColor() ) ?> >
        <div class="img-box-small z-index-after" data-dsn-grid="move-up"
             data-overlay="<?php echo esc_attr( $shortCode->overlay( 0 ) ) ?>"
            <?php echo esc_attr( $shortCode->triggerhook() ) ?> <?php echo esc_attr( $shortCode->duration() ) ?>>

            <?php

            echo wp_get_attachment_image( $id , $shortCode->sizeImage( 'full' ) , false , $arrayImage );
            ?>




            <div class="container v-middle">
                <div class="box-middle-text ">
                    <?php
                    if ( $title )
                        printf( '<h2 %s>%s</h2>' , $shortCode->titleAniamteCaption() , $title );
                    if ( $description )
                        printf( '<p %s>%s</p>' , $shortCode->descriptionAnimateCaption() , $description );
                    ?>
                    <a href="<?php echo esc_url( $content ) ?>" class="vid">
                        <div class="play-button">
                            <div class="play-btn">
                                <i class="image-zoom fas fa-play" data-dsn="parallax"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php if ( $caption_text ) : ?>
                <div class="cap">
                    <span><?php echo esc_html( $caption_text ) ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>


<?php endif; endforeach; ?>
