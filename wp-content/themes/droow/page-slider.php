<?php /* Template Name: Slider Template */ ?>

<?php

$is_custom = false;
$dsn_views = droow_acf_option( 'dsn-views', 'work' );
$layout = droow_acf_option( 'layout' );


if ( $dsn_views === 'custom' ):
    $is_custom = true;
    $myposts = droow_acf_option( 'custom_slider' );
else :
    $myposts = get_posts( array(
        'posts_per_page' => droow_acf_option( 'blog_pages_show_at_most', get_option( 'posts_per_page' ) ),
        'post_type'      => droow_project_slug(),
        'order'          => droow_acf_option( 'slid_order', 'DESC' )
    ) );
endif;

$use_slider_only = droow_acf_option( 'use_slider_only' );


$animate = 'dsn-slider';
if ( !$use_slider_only )
    $animate = 'project';

get_header();

if ( $myposts ):

    ?>
    <div class="dsn-slider <?php echo esc_attr( $layout ) ?>" data-dsn-header="<?php echo esc_attr( $animate ) ?>">
        <div class="dsn-root-slider has-top-bottom" id="dsn-hero-parallax-img">
            <div class="slide-inner">
                <div class="swiper-wrapper">
                    <?php

                    foreach ( $myposts as $mp ):
                        set_query_var( 'my_post', $mp );
                        set_query_var( 'is_custom', $is_custom );
                        get_template_part( 'template-parts/content/content', 'slider' );
                    endforeach;
                    ?>

                </div>
            </div>
        </div>

        <div id="dsn-hero-parallax-titles" class="dsn-slider-content project-title"></div>
        <?php if ( $layout !== 'demo3' ): ?>
            <div class="nav-slider">
                <div class="swiper-wrapper" role="navigation">


                    <?php
                    $index = 1;
                    foreach ( $myposts as $my_post ):

                        $id = droow_acf_option_array( droow_acf_option_array( $my_post, 'choose_post', $my_post ), 'ID' );

                        if ( $img = droow_acf_option( 'image_nav_slider', false, $id ) ) {
                            $img_src = wp_get_attachment_image_src( $img )[ 0 ];
                            $overlay = droow_overlay( 4, $id );
                        } elseif ( is_numeric( droow_acf_option_array( $my_post, 'image' ) ) ) {
                            $img_src = wp_get_attachment_image_src( droow_acf_option_array( $my_post, 'image' ) )[ 0 ];
                            $overlay = droow_acf_option_array( $my_post, 'opacity_overlay', 4 );
                        } else {
                            $img_src = droow_acf_option_array( $my_post, 'image', get_the_post_thumbnail_url( $id, 'thumbnail' ) );
                            $overlay = droow_overlay( 4, $id );
                        } ?>
                        <div class="swiper-slide">
                            <div class="image-container">
                                <div class="image-bg cover-bg" data-image-src="<?php echo esc_url( $img_src ) ?>"
                                     data-overlay="<?php echo esc_attr( $overlay ) ?>">
                                </div>
                            </div>
                            <div class="content">
                                <p><?php echo esc_html( droow_get_num( $index ) ) ?></p>
                            </div>
                        </div>

                        <?php $index++; endforeach; ?>


                </div>
            </div>
        <?php endif; ?>
        <section class="footer-slid" id="descover-holder">
            <div class="main-social">
                <div class="social-icon">
                    <div class="social-btn">
                        <div class="svg-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.3 23.2">
                                <path
                                        d="M19.4 15.5c-1.2 0-2.4.6-3.1 1.7L7.8 12v-.7l8.5-5.1c.7 1 1.9 1.6 3.1 1.6 2.1 0 3.9-1.7 3.9-3.9S21.6 0 19.4 0s-3.9 1.7-3.9 3.9v.4L7 9.3c-1.3-1.7-3.7-2-5.4-.8s-2.1 3.7-.8 5.4c.7 1 1.9 1.6 3.1 1.6s2.4-.6 3.1-1.6l8.5 5v.4c0 2.1 1.7 3.9 3.9 3.9s3.9-1.7 3.9-3.9c0-2.1-1.7-3.8-3.9-3.8zm0-13.6c1.1 0 1.9.9 1.9 1.9s-.9 1.9-1.9 1.9-1.9-.7-1.9-1.8.8-2 1.9-2zM3.9 13.6c-1.1 0-1.9-.9-1.9-1.9s.9-1.9 1.9-1.9 1.9.9 1.9 1.9-.8 1.9-1.9 1.9zm15.5 7.8c-1.1 0-1.9-.9-1.9-1.9s.9-1.9 1.9-1.9 1.9.9 1.9 1.9-.8 1.8-1.9 1.9z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
                <ul class="social-network">
                    <?php echo droow_social_setting( false ) ?>
                </ul>
            </div>

            <div class="control-num">
                <span class="sup active"><?php esc_html_e( '01', 'droow' ) ?></span>
            </div>
            <div class="control-nav">
                <div class="prev-container" data-dsn="parallax">
                    <svg viewBox="0 0 40 40">
                        <path class="path circle" d="M20,2A18,18,0,1,1,2,20,18,18,0,0,1,20,2"></path>
                        <polyline class="path" points="14.6 17.45 20 22.85 25.4 17.45"></polyline>
                    </svg>
                </div>

                <div class="next-container" data-dsn="parallax">
                    <svg viewBox="0 0 40 40">
                        <path class="path circle" d="M20,2A18,18,0,1,1,2,20,18,18,0,0,1,20,2"></path>
                        <polyline class="path" points="14.6 17.45 20 22.85 25.4 17.45"></polyline>
                    </svg>
                </div>
            </div>
        </section>

    </div>
<?php
else:
    if ( $use_slider_only ) {
        get_template_part( 'template-parts/content/content', 'none' );
    }

endif;
wp_reset_postdata();


if ( have_posts() && !$use_slider_only ) :
    the_post();
    ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class( 'root-project post-full-content single-project' ) ?>>
        <?php the_content(); ?>
    </div>

<?php
endif;

get_footer();
