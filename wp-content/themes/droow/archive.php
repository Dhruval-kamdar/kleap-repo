<?php
get_header();
if ( have_posts() ) :
    echo '<div class="root-blog">';
    while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content/content' );
    endwhile;

    droow_pagination( array(
        'prev_text'          => droow_buttons_pagination( esc_html__( 'PREV' , 'droow' ) , 'dsn-prev' ) ,
        'next_text'          => droow_buttons_pagination( esc_html__( 'NEXT' , 'droow' ) ) ,
        'before_page_number' => '<span class="dsn-numbers"><span class="number">' ,
        'after_page_number'  => '</span></span>'
    ) );

    echo '</div>';
else:
    get_template_part( 'template-parts/content/content' , 'none' );
endif;
get_footer();