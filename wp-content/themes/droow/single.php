<?php
get_header();

$is_share = function_exists( 'droow_share_links' ) && get_theme_mod( 'share_link' );
$col      = 12;
if ( $is_share ) $col = 9;


if ( have_posts() ) {
    the_post();
    ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class( 'news-content' ) ?>>
        <div class="news-content-inner ">
            <div class="row">
                <div class="col-lg-<?php echo esc_attr( $col ) ?>">
                    <div class="post-full-content single-post post-content">
                        <?php
                        the_content();
                        wp_link_pages( array(
                            'before'      => '<div class="pagination-pages"><nav class="navigation pagination"><div class="nav-links"><span class="page-numbers">' . esc_html__( 'Pages' , 'droow' ) . '</span></div>' ,
                            'after'       => '</nav></div>' ,
                            'link_before' => '<div class="nav-links">' ,
                            'link_after'  => '</div>' ,
                            'pagelink'    => '<span class="page-numbers">%</span>'
                        ) );

                        echo droow_post_tag( '' );
                        ?>

                    </div>
                </div>
                <?php if ( $is_share ): ?>
                    <div class="col-lg-2 offset-lg-1">
                        <div class="News-socials-wrapper">
                            <div>
                                <div class="post-share">
                                    <h5 class="title-caption"><?php esc_html_e( 'Share with:' , 'droow' ) ?></h5>
                                    <ul>
                                        <?php
                                        echo droow_share_links( '<li>' , '</li>' );
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
        ?>
    </div>
    <?php
}
get_footer();
?>