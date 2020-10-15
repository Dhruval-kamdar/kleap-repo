<header class="<?php droow_space_header(); ?>">
    <div class="headefr-fexid" data-dsn-header="project">

        <?php
        droow_get_img( array(
            'before'   => '<div class="bg ' . droow_acf_option( 'image_size' ) . '"  data-dsn-ajax="img">' ,
            'after'    => '</div>' ,
            'before_v' => '<div class="has-top-bottom" data-dsn="video" data-overlay="' . esc_attr( droow_overlay() ) . '" id="dsn-hero-parallax-img">' ,
            'after_v'  => '</div>' ,

            'attr' => array(
                'class'        => 'bg-image cover-bg z-index-0' ,
                'data-overlay' => esc_attr( droow_overlay() ) ,
                'id'           => 'dsn-hero-parallax-img'
            )
        ) );
        ?>


        <div class="project-title f-column f-center-h <?php echo esc_attr( droow_acf_option( 'justify_content' ) ) ?>"
             id="dsn-hero-parallax-title">
            <div class="title-text-header">
                <?php if ( is_page() || droow_subtitle_head() ):
                    if ( droow_subtitle_head() ):
                        printf( '<div class="cat"><span>%s</span></div>' , droow_subtitle_head() );
                    endif;
                else :
                    if ( droow_post_category( ', ' ) )
                        printf( '<div class="cat"><span>%s</span></div>' , droow_post_category( ', ' ) );
                endif; ?>

                <h1 class="title-text-header-inner">
                                <span class="dsn-title-header" data-dsn-animate="ajax">
                                    <?php echo droow_custom_title() ?>
                                </span>
                </h1>
                <div class="sub-text-header" data-dsn-animate="ajax">
                    <?php echo droow_description_head() ?>
                </div>
            </div>
        </div>


        <div class="dsn-bottom" data-dsn-animate="ajax" id="descover-holder">
            <div class="scroll scroll-down">
                <span class="background"></span>
                <span class="triangle"></span>
            </div>
            <?php
            if ( $link = droow_acf_option( 'header_link' ) ):
                printf( '<div class="link"><a target="_blank" href="%s">%s</a></div>' ,
                    esc_url( $link ) ,
                    esc_html__( 'View Website' , 'droow' ) );
            endif;
            ?>


        </div>


    </div>
</header>