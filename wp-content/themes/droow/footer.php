<?php droow_layout_pages( false ); ?>
<?php


if ( is_singular( 'page' ) ):
    droow_get_next_page();
elseif ( droow_is_work() ):
    if ( !get_theme_mod( 'hide_next_project' ) ):
        if ( is_singular() )
            droow_get_next_project();
    endif;
elseif ( is_singular( 'post' ) ):
    droow_get_next_posts();
endif;
echo get_theme_mod( 'html_footer_code', '' );
$type_footer = droow_acf_option( 'footer_show', 'normal' );
switch ( $type_footer ):
    case 'normal':
        get_template_part( 'template-parts/footer/footer', 'bottom' );
        break;
    case 'normal-widget':
        get_template_part( 'template-parts/footer/footer', 'normal-widget' );
        break;
    case 'feature':
        get_template_part( 'template-parts/footer/footer', 'feature' );
        break;
endswitch;

?>

</div><!-- close wrapper -->
</div> <!-- close dsn-scrollbar -->
<?php
get_template_part( 'template-parts/content/content', 'all-work' );
get_sidebar();

?>
</main>


<?php


get_template_part( 'template-parts/footer/footer', 'cursor' );
get_template_part( 'template-parts/footer/footer', 'loading' );
get_template_part( 'template-parts/footer/footer', 'style' );


wp_footer();

?>

</body>
</html>

