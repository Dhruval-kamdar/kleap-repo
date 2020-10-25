<?php
	$blog_top_content         = ot_get_option( 'blog_top_content' );
	$blog_top_content_padding = ot_get_option( 'blog_top_content_padding', 'off' );

	$classes[] = 'blog-header-style2';
	$classes[] = 'on' === $blog_top_content_padding ? 'page-padding' : false;
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php
		$blog_top_content = ot_get_option( 'blog_top_content' );
		do_action( 'thb_page_content', $blog_top_content );
	?>
</div>
