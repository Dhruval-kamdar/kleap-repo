<?php
	add_filter( 'excerpt_length', 'thb_supershort_excerpt_length' );

	$blog_sidebar = ot_get_option( 'blog_sidebar', 'on' );

	$vars          = $wp_query->query_vars;
	$thb_i         = array_key_exists( 'thb_i', $vars ) ? $vars['thb_i'] : false;
	$columns       = ( array_key_exists( 'columns', $vars ) && ! is_admin() ) ? $vars['columns'] : 'large-4';
	$thb_animation = array_key_exists( 'thb_animation', $vars ) ? $vars['thb_animation'] : false;
	$thb_date      = array_key_exists( 'thb_date', $vars ) ? $vars['thb_date'] : true;
	$thb_cat       = array_key_exists( 'thb_cat', $vars ) ? $vars['thb_cat'] : true;

	$format    = get_post_format();
	$permalink = get_the_permalink();
if ( 'link' === $format ) {
	$permalink = get_post_meta( get_the_ID(), 'post_link', true );
}
if ( $thb_i && 'on' === $blog_sidebar ) {
	$columns = 'large-6';
}

if ( $thb_i ) {
	switch ( $thb_i ) {
		case '1':
			$columns = 'on' === $blog_sidebar ? 'medium-12 half-height' : 'medium-12 large-8 half-height';
			break;
		default:
			$columns = 'on' === $blog_sidebar ? 'medium-6' : 'medium-6 large-4';
			break;
	}
}
?>
<div class="small-12 medium-6 <?php echo esc_attr( $columns ); ?> columns">
	<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post style5 ' . $thb_animation ); ?>>
		<figure class="post-gallery">
			<a href="<?php echo esc_url( $permalink ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"></a>
			<?php the_post_thumbnail( 'revolution-square-x2' ); ?>
			<div class="overlay"></div>
			<?php if ( $thb_cat ) { ?>
			<aside class="post-category">
				<?php the_category( ', ' ); ?>
			</aside>
			<?php } ?>
			<div class="style5-content-container">
				<header class="post-title entry-header">
					<?php the_title( '<h3 class="entry-title" itemprop="name headline"><a href="' . esc_url( $permalink ) . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h3>' ); ?>
				</header>
				<?php if ( $thb_date ) { ?>
				<aside class="post-meta">
					<?php do_action( 'thb_postdate' ); ?>
				</aside>
				<?php } ?>
			</div>
		</figure>
		<?php do_action( 'thb_postmeta' ); ?>
	</article>
</div>
