<?php /* Template Name: Posts Template */ ?>


<?php
$numPage     = droow_acf_option( 'blog_pages_show_at_most' , get_option( 'posts_per_page' ) );
$CurrentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$myposts = new WP_Query( array( 'paged' => $CurrentPage , 'posts_per_page' => $numPage , 'post_type' => 'post' ) );


$is_ajax = droow_acf_option( 'ajax_load_more' );


get_header();
if ( $myposts->have_posts() ) :
    echo '<div class="root-blog">';
    while ( $myposts->have_posts() ) :
        $myposts->the_post();
        get_template_part( 'template-parts/content/content' );
    endwhile;

    if ( $myposts->have_posts() && $is_ajax ) : ?>
        <div class="image-zoom button-loadmore" data-type="post" data-page="2" data-dsn="parallax"
             data-id="<?php echo esc_attr( $numPage ) ?>"
             data-layout="">
            <span class="progress-text progress-load-more"><?php esc_html_e( 'Load More' , 'droow' ) ?></span>
            <span class="progress-text progress-no-more"><?php esc_html_e( 'NO MORE' , 'droow' ) ?></span>
            <div class="dsn-load-progress-ajax"></div>
        </div>
    <?php endif;

    if ( !$is_ajax ) :
        droow_pagination( array(
            'prev_text'          => droow_buttons_pagination( esc_html__( 'PREV' , 'droow' ) , 'dsn-prev' ) ,
            'next_text'          => droow_buttons_pagination( esc_html__( 'NEXT' , 'droow' ) ) ,
            'before_page_number' => '<span class="dsn-numbers"><span class="number">' ,
            'after_page_number'  => '</span></span>' ,
            'total'              => $myposts->max_num_pages ,

        ) );
    endif;

    echo '</div>';
else:
    get_template_part( 'template-parts/content/content' , 'none' );
endif;
wp_reset_postdata();

get_footer();