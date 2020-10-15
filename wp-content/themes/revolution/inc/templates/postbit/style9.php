<?php
	$vars          = $wp_query->query_vars;
	$thb_animation = array_key_exists( 'thb_animation', $vars ) ? $vars['thb_animation'] : false;
	$thb_simple    = array_key_exists( 'thb_simple', $vars ) ? $vars['thb_simple'] : true;
	$thb_date      = array_key_exists( 'thb_date', $vars ) ? $vars['thb_date'] : true;
	$thb_excerpt   = array_key_exists( 'thb_excerpt', $vars ) ? $vars['thb_excerpt'] : true;
	$thb_cat       = array_key_exists( 'thb_cat', $vars ) ? $vars['thb_cat'] : true;

	$format    = get_post_format();
	$permalink = get_the_permalink();
if ( 'link' === $format ) {
	$permalink = get_post_meta( get_the_ID(), 'post_link', true );
}
?>
<div class="small-12 columns">
<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post style9 ' . $thb_animation ); ?>>
	<div class="style9-title">
		<?php if ( $thb_cat ) { ?>
		<aside class="post-category">
			<?php the_category( ', ' ); ?>
		</aside>
		<?php } ?>
		<header class="post-title entry-header">
			<?php the_title( '<h4 class="entry-title" itemprop="name headline"><a href="' . esc_url( $permalink ) . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h4>' ); ?>
		</header>
		<aside class="post-meta">
			<?php if ( $thb_date ) { ?>
				<?php echo get_the_date(); ?>
			<?php } ?>
		</aside>
		<div class="style9-arrow"><?php get_template_part( 'assets/img/svg/prev_arrow.svg' ); ?></div>
	</div>
	<?php if ( $thb_excerpt ) { ?>
	<div class="style9-content">
		<div class="post-content">
			<?php the_excerpt(); ?>
		</div>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="style9-readmore"><?php esc_html_e( 'Read More', 'revolution' ); ?></a>
	</div>
	<?php } ?>
	<?php do_action( 'thb_postmeta' ); ?>
</article>
</div>
