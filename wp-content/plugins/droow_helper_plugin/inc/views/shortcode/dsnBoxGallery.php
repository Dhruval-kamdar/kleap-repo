<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$ids = $shortcode->gallery();
if(gettype($content) === 'array')
    $content = 'full';
?>


<?php if ( count( $ids ) ): ?>


    <div class="<?php echo esc_attr( $shortcode->className() . ' ' . $shortcode->layout() ) ?> gallery-col" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
        <div class="row">

            <?php foreach ( $ids as $id ): if ( $id && $img_show = wp_get_attachment_image_src( $id , $content ) ): ?>

                <div class="col-md-4 box-im section-padding">
                    <div class="image-zoom" data-dsn="parallax">
                        <div class="single-image" data-mfp-src="<?php echo esc_url( $img_show[ 0 ] ) ?>">
                            <?php echo wp_get_attachment_image( $id , $shortcode->sizeImage() ) ?>
                        </div>
                        <?php if ( $shortcode->showCaption() && $caption_text = wp_get_attachment_caption( $id ) ) : ?>
                            <div class="caption">
                                <?php echo esc_html( $caption_text ) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; endforeach; ?>
        </div>
    </div>

<?php endif; ?>
