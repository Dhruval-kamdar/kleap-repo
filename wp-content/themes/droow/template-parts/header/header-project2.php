<header class="<?php droow_space_header(); ?>">
    <div class="fullscreen-slider">
        <div class="swiper-container" data-dsn-header="project">
            <div id="descover-holder" class="descover-holder f-align-center w-100 f-center-h">
                <span class="letter"><?php esc_html_e( 'discover' , 'droow' ); ?></span>
                <div class="scroll-down__line"><span></span></div>
                <span class="letter"><?php esc_html_e( 'scroll to' , 'droow' ); ?></span>
            </div>
            <div class="swiper-wrapper">
                <div class="slider-item swiper-slide">

                    <?php
                    droow_get_img( array(
                        'before'   => '<div class="bg parallax-move-element"  data-dsn-ajax="img">' ,
                        'after'    => '</div>' ,
                        'before_v' => '<div class="has-top-bottom" data-dsn="video" data-overlay="' . esc_attr( droow_overlay() ) . '" id="dsn-hero-parallax-img">' ,
                        'after_v'  => '</div>' ,

                        'attr' => array(
                            'class'        => 'bg-image cover-bg' ,
                            'data-overlay' => esc_attr( droow_overlay() ) ,
                            'id'           => 'dsn-hero-parallax-img'
                        )
                    ) );
                    ?>

                    <div class="container h-100">
                        <div id="dsn-hero-parallax-title"
                             class="content-inner h-100 f-column f-center-h f-align-center">
                            <?php if ( is_page() || droow_subtitle_head() ):
                                if ( droow_subtitle_head() ):
                                    printf( '<div class="cat dsn-post-info"><span>%s</span></div>' , droow_subtitle_head() );
                                endif;
                            else :
                                printf( '<div class="cat dsn-post-info"><span>%s</span></div>' , droow_post_category( ', ' ) );
                            endif; ?>

                            <div class="content">
                                <div class="slider-header slider-header-top">
                                    <h1 class="dsn-title-header" data-dsn-animate="ajax">
                                        <?php echo droow_custom_title() ?>
                                    </h1>
                                </div>
                                <div id="dsn-hero-parallax-fill-title"
                                     class="slider-header slider-header-bottom">
                                    <h1 class="dsn-title-header" data-dsn-animate="ajax">
                                        <?php echo droow_custom_title() ?>
                                    </h1>
                                </div>
                            </div>
                            <div class="slider-descr">
                                <h5 data-dsn-animate="ajax">
                                    <?php echo droow_description_head() ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>