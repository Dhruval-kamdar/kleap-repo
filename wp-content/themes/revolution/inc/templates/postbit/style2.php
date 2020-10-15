<?php
	$vars          = $wp_query->query_vars;
	$thb_date      = array_key_exists( 'thb_date', $vars ) ? $vars['thb_date'] : true;
	$thb_animation = array_key_exists( 'thb_animation', $vars ) ? $vars['thb_animation'] : false;
	$thb_excerpt   = array_key_exists( 'thb_excerpt', $vars ) ? $vars['thb_excerpt'] : false;
	$thb_cat       = array_key_exists( 'thb_cat', $vars ) ? $vars['thb_cat'] : true;
	$format        = get_post_format();
	$permalink     = get_the_permalink();
if ( 'link' === $format ) {
	$permalink = get_post_meta( get_the_ID(), 'post_link', true );
}
?>
<div class="small-12 medium-10 large-9 columns">
	<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post style2 ' . $thb_animation ); ?>>
		<?php if ( has_post_thumbnail() ) { ?>
		<figure class="post-gallery">
			<a href="<?php echo esc_url( $permalink ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'revolution-bloglarge-x2' ); ?>
				<div class="post-gallery-overlay"></div>
			</a>
		</figure>
		<?php } ?>
		<div class="blog-post-container">
			<?php if ( $thb_cat ) { ?>
			<aside class="post-category">
				<?php the_category( ', ' ); ?>
			</aside>
			<?php } ?>
			<header class="post-title entry-header">
				<?php the_title( '<h2 class="entry-title" itemprop="name headline"><a href="' . esc_url( $permalink ) . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h2>' ); ?>
			</header>
			<?php if ( $thb_excerpt ) { ?>
			<div class="post-content">
				<?php the_excerpt(); ?>
			</div>
			<?php } ?>
			<?php if ( $thb_date ) { ?>
			<aside class="post-meta">
				<?php do_action( 'thb_postdate' ); ?>
			</aside>
			<?php } ?>
		</div>
		<?php do_action( 'thb_postmeta' ); ?>
	</article>
</div>
