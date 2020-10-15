<?php get_header(); ?>
<?php
	$blog_header  = ot_get_option( 'blog_header', 'style1' );
	$blog_style   = ot_get_option( 'blog_style', 'style3' );
	$blog_sidebar = ot_get_option( 'blog_sidebar', 'on' );

	get_template_part( 'inc/templates/blog/blog-header-' . $blog_header );
?>
<div class="row max_width blog_row">
	<div class="small-12 columns">
		<div class="blog-main-container">
			<div class="blog-container blog-<?php echo esc_attr( $blog_style ); ?>">
				<?php
					get_template_part( 'inc/templates/blog/' . $blog_style );
				?>
			</div>
			<?php
			if ( 'on' === $blog_sidebar && in_array( $blog_style, array( 'style1', 'style3', 'style4', 'style5', 'style6', 'style7', 'style10' ), true ) ) {
				get_sidebar( 'blog' );
			}
			?>
		</div>
	</div>
</div>
<?php
get_footer();
