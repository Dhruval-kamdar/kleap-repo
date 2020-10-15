<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$ids = $shortcode->gallery();

if(gettype($content) === 'array')
    $content = 'full';

?>


<?php if ( count( $ids ) ): ?>
    <div class="gallery-portfolio <?php echo esc_attr( $shortcode->layout() . ' ' . $shortcode->className() ) ?> " <?php echo esc_attr( $shortcode->changeColor() ) ?>>
        <?php foreach ( $ids as $id ): if ( $id && $img_show = wp_get_attachment_image_src( $id , $content ) ): ?>
            <div class="link-pop" data-mfp-src="<?php echo esc_url( $img_show[ 0 ] ) ?>"
               >
                <?php echo wp_get_attachment_image( $id , $shortcode->sizeImage() ) ?>
                <?php if ( $shortcode->showCaption() && $caption_text = wp_get_attachment_caption( $id ) ) : ?>
                    <div class="cap">
                        <span><?php echo esc_html( $caption_text ) ?></span>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; endforeach; ?>

    </div>

<?php endif; ?>
