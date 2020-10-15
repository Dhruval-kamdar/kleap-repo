<?php
$attr = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );


$post_type = droow_acf_option_array( $attr, 'choose_post_type', 'custom' );
$items = array();
if ( $post_type === 'custom' ):
    $items = $shortcode->getItems( $content );
else :
    $post_type = get_posts( array( 'posts_per_page' => droow_acf_option_array( $attr, 'number_of_posts', 4 ), 'post_type' => $post_type ) );
    if ( count( $post_type ) ) {
        foreach ( $post_type as $po ):
            $items[] = array( 'link_post' => $po->ID );
        endforeach;
    }
endif;
?>

<?php
//--> show Caption  = show Post
if ( $shortcode->showCaption( 'full' ) === 'half' ):?>
    <section
            class="our-news dsn-arrow <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>>
        <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">
            <?php $shortcode->getBlocTitle() ?>
            <div class="custom-container">
                <div class="slick-slider">
                    <?php
                    if ( count( $items ) ):
                        foreach ( $items as $item ):
                            $shortcode = new DroowShortCode( $item );
                            $postId = $shortcode->LinkId();
                            if ( !$postId ) continue;

                            ?>
                            <div class="item-new slick-slide">
                                <div class="image" data-overlay="<?php echo esc_attr( $shortcode->overlay( 0 ) ) ?>">
                                    <?php
                                    if ( gettype( $shortcode->Image() ) === 'integer' && $id = $shortcode->Image() ):
                                        echo wp_get_attachment_image( $id, 'medium_large' );
                                    else:
                                        echo get_the_post_thumbnail( $postId, 'medium_large' );
                                    endif;
                                    ?>
                                </div>
                                <div class="content">
                                    <?php
                                    $subTitle = $shortcode->TitleCover();

                                    if ( $subTitle = $shortcode->TitleCover() ) :
                                        printf( '<h5>%s</h5>', esc_html( $subTitle ) );
                                    elseif ( $subTitle = droow_post_category( ', ', false, false, $postId ) ) :
                                        printf( '<h5>%s</h5>', esc_html( $subTitle ) );
                                    elseif ( $subTitle = droow_subtitle_head( '', $postId ) ) :
                                        printf( '<h5>%s</h5>', esc_html( $subTitle ) );

                                    endif;
                                    ?>


                                    <div class="cta">
                                        <?php
                                        $title = $shortcode->Title();
                                        if ( !$title )
                                            $title = droow_custom_title( '', $postId );
                                        ?>
                                        <a class="effect-ajax" href="<?php the_permalink( $postId ) ?>"
                                           data-dsn="parallax"
                                           rel="bookmark" title="<?php echo esc_attr( $title ) ?>">
                                            <?php echo esc_html( $title ) ?>
                                        </a>
                                    </div>

                                    <?php
                                    $description = $shortcode->Description();
                                    if ( $description )
                                        printf( '<p>%s</p>', $description )
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
    </section>

<?php else: ?>
    <section
            class="our-work dsn-arrow work-under-header <?php echo esc_attr( $shortcode->className() ) ?>" <?php echo esc_attr( $shortcode->changeColor() ) ?>
            data-dsn-col="3">
        <div class="<?php echo esc_attr( $shortcode->layout() ) ?>">
            <?php $shortcode->getBlocTitle() ?>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 offset-lg-3">
                    <div class="work-container">
                        <div class="slick-slider">

                            <?php
                            if ( count( $items ) ):
                                foreach ( $items as $item ):
                                    $shortcode = new DroowShortCode( $item );
                                    $postId = $shortcode->LinkId();
                                    if ( !$postId ) continue;
                                    $title = $shortcode->Title();
                                    if ( !$title )
                                        $title = droow_custom_title( '', $postId );
                                    ?>
                                    <div class="work-item slick-slide">

                                        <?php
                                        if ( gettype( $shortcode->Image() ) === 'integer' && $id = $shortcode->Image() ):
                                            echo wp_get_attachment_image( $id, 'medium_large' );
                                        else:
                                            echo get_the_post_thumbnail( $postId, 'medium_large' );
                                        endif;
                                        ?>
                                        <div class="item-border"></div>
                                        <div class="item-info">
                                            <a class="effect-ajax" href="<?php the_permalink( $postId ) ?>"
                                               data-dsn-grid="move-up"
                                               rel="bookmark" title="<?php echo esc_attr( $title ) ?>">
                                                <?php
                                                if ( $subTitle = $shortcode->TitleCover() ) :
                                                    printf( '<h5 class="cat">%s</h5>', esc_html( $subTitle ) );
                                                elseif ( $subTitle = droow_post_category( ', ', false, false, $postId ) ) :
                                                    printf( '<h5 class="cat">%s</h5>', esc_html( $subTitle ) );

                                                elseif ( $subTitle = droow_subtitle_head( '', $postId ) ) :
                                                    printf( '<h5 class="cat">%s</h5>', esc_html( $subTitle ) );
                                                endif;

                                                printf( '<h4>%s</h4>', $title );

                                                if ( get_post_type( $postId ) === 'post' ):
                                                    printf( '<span><span>%s</span></span>', esc_html__( 'View Post', 'droow' ) );
                                                else:
                                                    printf( '<span><span>%s</span></span>', esc_html__( 'View Project', 'droow' ) );
                                                endif;

                                                ?>
                                            </a>


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
        </div>
    </section>

<?php endif; ?>
