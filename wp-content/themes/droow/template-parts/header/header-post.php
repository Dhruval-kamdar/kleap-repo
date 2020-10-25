<header >
    <div class="header-single-post" data-dsn-header="project">
        <?php
        droow_get_img( array(
            'before'   => '<div class="post-parallax-wrapper" data-overlay="' . esc_attr( droow_overlay() ) . '"> ' ,
            'after'    => '</div>' ,
            'before_v' => '<div class="has-top-bottom" id="dsn-hero-parallax-img" data-dsn="video" data-dsn-ajax="img">' ,
            'after_v'  => '</div>' ,
            'type'     => 'img' ,

            'attr' => array(
                'class'           => 'has-top-bottom' ,
                'data-dsn-ajax'   => 'img' ,
                'data-dsn-header' => 'blog' ,
                'id'              => 'dsn-hero-parallax-img'
            )
        ) );

        ?>

        <div class="container">

            <div class="inner-box section-top">

                <div class="post-info ">
                    <?php if ( is_page() || droow_subtitle_head() ):
                        if ( droow_subtitle_head() ):
                            printf( '<div class="blog-post-date dsn-link"><span>%s</span></div>' , droow_subtitle_head() );
                        endif;
                    else: ?>
                        <a href="<?php echo esc_url( droow_date_link() ) ?>"
                           class="blog-post-date dsn-link effect-ajax dsn-post-info"
                           title="<?php the_time( 'F d, Y' ) ?>">
                            <?php echo esc_html( get_the_date() ) ?>
                        </a>
                        <div class="blog-post-cat dsn-link dsn-post-info ">
                            <?php
                            echo droow_post_category( ', ' );
                            ?>
                        </div>
                    <?php endif; ?>


                </div>
                <h3 class="title-box dsn-title-p"><?php echo droow_custom_title() ?></h3>
            </div>

        </div>

    </div>
</header>