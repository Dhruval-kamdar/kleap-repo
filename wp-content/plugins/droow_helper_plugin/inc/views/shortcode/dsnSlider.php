<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$ids = $shortcode->gallery();

?>


<?php if ( count( $ids ) ): ?>
    <div class="<?php echo esc_attr($shortcode->layout())?> slider-project <?php echo esc_attr( $shortcode->className() ) ?>"  <?php echo esc_attr( $shortcode->changeColor() ) ?>>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ( $ids as $id ): if ( $id && wp_get_attachment_image_src( $id ) ): ?>
                    <div class="swiper-slide">
                        <?php echo wp_get_attachment_image( $id , $shortcode->sizeImage() ) ?>
                        <?php if ( $shortcode->showCaption() && $caption_text = wp_get_attachment_caption( $id ) ) : ?>
                            <div class="cap">
                                <span><?php echo esc_html( $caption_text ) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>

        <div class="swiper-pagination"></div>

        <div class="slider-button-prev">
            <div>
                <svg viewBox="0 0 40 40">
                    <polyline class="path" points="25.4 22.55 20 17.15 14.6 22.55"></polyline>
                </svg>
            </div>
        </div>
        <div class="slider-button-next">
            <div>
                <svg viewBox="0 0 40 40">
                    <polyline class="path" points="14.6 17.45 20 22.85 25.4 17.45"></polyline>
                </svg>
            </div>
        </div>
    </div>

<?php endif; ?>




