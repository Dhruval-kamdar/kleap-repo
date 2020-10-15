<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$items = $shortcode->getItems( $content );

?>


<section
        class="brand-client <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
    <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">

        <?php $shortcode->getBlocTitle() ?>


        <div class="wapper-client">


            <?php
            if ( count( $items ) ):
                foreach ( $items as $item ):
                    $shortcode = new DroowShortCode( $item );
                    if ( $id = $shortcode->Image() ):
                        ?>


                        <div class="logo-box">
                            <?php echo wp_get_attachment_image( $id , 'droow-brand-img' ) ?>
                            <?php
                            $url = droow_acf_option_array( $item , 'link' , $item );
                            ?>
                            <?php if ( $url_link = droow_acf_option_array( $url , 'url' , droow_acf_option_array( $url , 'link_url' )  ) ) : ?>
                                <div class="info">
                                    <div class="content">
                                        <div class="icon">
                                            <i class="fas fa-plus"></i>
                                        </div>

                                        <div class="entry">
                                            <div>
                                                <?php printf( '<h5>%s</h5>' , esc_html( droow_acf_option_array( $url , 'title' , droow_acf_option_array( $url , 'link_title' )  ) ) ) ?>
                                                <?php printf( '<a href="%s" target="_blank" rel="nofollow">%s</a>' , esc_url( $url_link ) , esc_html( $url_link ) ) ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php
                    endif;
                endforeach;
            endif;
            ?>

        </div>
    </div>
</section>