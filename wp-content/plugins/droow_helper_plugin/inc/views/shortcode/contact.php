<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );

$titleForm = $shortcode->showCaption();


$items = $shortcode->getItems( $content );

?>


<div class="root-contact <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>>


    <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="box-info-contact">
                    <?php
                    if ( $title = $shortcode->Title() )
                        printf( '<h3 %s>%s</h3>' , $shortcode->titleAnimate() , esc_html( $title ) );

                    if ( $title = $shortcode->TitleCover() )
                        printf( '<h5 %s>%s</h5>' , $shortcode->titleAnimate() , esc_html( $title ) );

                    if ( $description = $shortcode->Description() )
                        printf( '<p %s>%s</p>' , $shortcode->DescriptionAnimate() , $description );
                    ?>

                    <ul>
                        <?php
                        $shortcode = new DroowShortCode( droow_acf_option_array( $items , 'phone' , array() ) );
                        if ( $shortcode->Title() || $shortcode->Description() ):
                            echo '<li>';
                            printf( '<span data-dsn-animate="%s">%s</span>' , $shortcode->titleAnimate() , esc_html( $shortcode->Title() ) );
                            printf( '<a href="tel:%s" target="_blank" rel="nofollow" data-dsn-animate="%s">%s</a>' , esc_attr( $shortcode->Description() ) , $shortcode->DescriptionAnimate() , esc_html( $shortcode->Description() ) );
                            echo '</li>';
                        endif;
                        ?>


                        <?php
                        $shortcode = new DroowShortCode( droow_acf_option_array( $items , 'e-mail' , array() ) );
                        if ( $shortcode->Title() ||  $shortcode->Description() ):
                            echo '<li>';
                            printf( '<span data-dsn-animate="%s">%s</span>' , $shortcode->titleAnimate() , esc_html( $shortcode->Title() ) );
                            printf( '<a href="mailto:%s" target="_blank" rel="nofollow" data-dsn-animate="%s">%s</a>' , antispambot( $shortcode->Description() , 1 ) , $shortcode->DescriptionAnimate() , antispambot( $shortcode->Description() ) );
                            echo '</li>';
                        endif;
                        ?>


                        <?php
                        $shortcode = new DroowShortCode( droow_acf_option_array( $items , 'address' , array() ) );

                        if ( $shortcode->Title() ||  $shortcode->Description() ):

                            echo '<li>';
                            printf( '<span data-dsn-animate="%s">%s</span>' , $shortcode->titleAnimate() , esc_html( $shortcode->Title() ) );
                            printf( '<p data-dsn-animate="%s">%s</p>' , $shortcode->DescriptionAnimate() , $shortcode->Description() );
                            echo '</li>';
                        endif;
                        ?>

                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-box">
                    <?php
                    if ( $titleForm )
                        printf( '<h3 data-dsn-animate="text">%s</h3>' , esc_html( $titleForm ) );

                    $post_contact = droow_acf_option_array($items , 'choose_contact_form' , droow_acf_option( 'choose_contact_form' ));
                    if ( $post_contact ):
                        $id    = droow_acf_option_array( $post_contact , 'ID' );
                        $title = droow_acf_option_array( $post_contact , 'post_title' );
                        echo do_shortcode( '[contact-form-7 id="' . $id . '" title="' . $title . '"]' );
                    endif;
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
