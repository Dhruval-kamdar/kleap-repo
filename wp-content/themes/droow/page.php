<?php
get_header();

if ( have_posts() ) :
    the_post();


    ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class( 'root-page post-full-content ' . droow_section_padding() ) ?>>
        <?php
        the_content();
        ?>
    </div>

    <?php
    wp_link_pages( array(
        'before'      => '<div class="pagination-pages"><nav class="navigation pagination"><div class="nav-links"><span class="page-numbers">' . esc_html__( 'Pages' , 'droow' ) . '</span></div>' ,
        'after'       => '</nav></div>' ,
        'link_before' => '<div class="nav-links">' ,
        'link_after'  => '</div>' ,
        'pagelink'    => '<span class="page-numbers">%</span>'
    ) );
    ?>
    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
    ?>

<?php
endif;
get_footer();
