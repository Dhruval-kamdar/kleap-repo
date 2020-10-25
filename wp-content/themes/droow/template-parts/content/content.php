<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-list-item mb-section' ) ?> >

    <?php if ( has_post_thumbnail() ): ?>
        <figure>
            <a class="image-zoom effect-ajax w-100" href="<?php the_permalink() ?>" data-dsn-animate="up"
               rel="bookmark" title="<?php echo esc_attr( droow_custom_title() ) ?>"
            >
                <?php the_post_thumbnail(); ?>
            </a>
        </figure>
    <?php endif; ?>

    <div class="post-list-item-content" data-dsn-animate="up">
        <div class="post-info-top">
            <div class="post-info-date">
                <a href="<?php echo droow_date_link() ?>"
                   title="<?php the_time( 'F d, Y' ) ?>">
                    <span> <?php echo esc_html( get_the_date() ) ?> </span>
                </a>
            </div>

            <div class="post-info-category">
                <?php echo droow_post_category(); ?>
            </div>
        </div>
        <h3 class="dsn-title-p">
            <a href="<?php the_permalink() ?>" class="effect-ajax" rel="bookmark"
               title="<?php echo esc_attr( droow_custom_title() ) ?>">
                <?php the_title() ?>
            </a>
            <?php if ( is_sticky() ) : ?>
                <span class="sticky-post"><?php esc_html_e( 'Featured' , 'droow' ); ?></span>
            <?php endif; ?>

        </h3>

        <div class="link-custom">
            <a class="image-zoom effect-ajax" href="<?php the_permalink() ?>" data-dsn="parallax"
               rel="bookmark" title="<?php echo esc_attr( droow_custom_title() ) ?>">
                <span><?php esc_html_e( 'Read More' , 'droow' ); ?></span>
            </a>
        </div>

    </div>

</article>