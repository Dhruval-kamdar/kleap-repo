<?php
	$blog_pagination_style = is_home() ? ot_get_option( 'blog_pagination_style', 'style1' ) : 'style1';
	$blog_sidebar          = ot_get_option( 'blog_sidebar', 'on' );
	$blog_animation        = ot_get_option( 'blog_animation', '' );
	set_query_var( 'thb_animation', $blog_animation );

	$thb_blog_columns = ot_get_option( 'thb_blog_columns', '4' );
	$columns          = thb_translate_columns( $thb_blog_columns );
	set_query_var( 'columns', $columns );


	$classes[] = 'row masonry';
	$classes[] = 'pagination-' . $blog_pagination_style;
	$classes[] = 'blog-sidebar-' . $blog_sidebar;

	$ppp = get_option( 'posts_per_page' );
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-layoutmode="packery" data-count="<?php echo esc_attr( $ppp ); ?>" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_blog_ajax' ) ); ?>">
	<?php
	$i = 1;
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<?php
			set_query_var( 'thb_i', $i );
			get_template_part( 'inc/templates/postbit/style5' );
			?>
			<?php
			$i++;
	endwhile; else :
		?>
			<?php get_template_part( 'inc/templates/not-found' ); ?>
		<?php endif; ?>
</div>
<?php do_action( 'thb_blog_pagination' ); ?>
