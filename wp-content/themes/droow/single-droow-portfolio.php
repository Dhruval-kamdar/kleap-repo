<?php
get_header();

if ( have_posts() ) :
    the_post();
    ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class( 'root-project post-full-content single-project' ) ?>>
        <?php the_content(); ?>
    </div>

<?php
endif;

get_footer();