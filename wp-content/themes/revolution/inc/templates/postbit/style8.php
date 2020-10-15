<?php
add_filter( 'excerpt_length', 'thb_supershort_excerpt_length' );

$vars          = $wp_query->query_vars;
$columns       = array_key_exists( 'columns', $vars ) ? $vars['columns'] : 'large-4';
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

$classes[] = 'small-12';
$classes[] = $columns;
$classes[] = 'columns';
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post style8 ' . $thb_animation ); ?>>
		<?php if ( $thb_cat ) { ?>
		<aside class="post-category">
			<?php the_category( ', ' ); ?>
		</aside>
		<?php } ?>
		<div class="style8-container">
			<header class="post-title entry-header">
				<?php the_title( '<h3 class="entry-title" itemprop="name headline"><a href="' . esc_url( $permalink ) . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h3>' ); ?>
			</header>
			<?php if ( $thb_excerpt ) { ?>
				<div class="post-content">
					<?php the_excerpt(); ?>
				</div>
			<?php } ?>
		</div>
		<div class="style8-meta">
			<aside class="post-meta">
				<?php if ( $thb_date ) { ?>
					<?php do_action( 'thb_postdate' ); ?>
				<?php } ?>
			</aside>
			<div class="style8-link">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Read More', 'revolution' ); ?></a>
			</div>
		</div>

		<?php do_action( 'thb_postmeta' ); ?>
	</article>
</div>
