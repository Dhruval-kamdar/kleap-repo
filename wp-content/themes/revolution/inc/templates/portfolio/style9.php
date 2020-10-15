<?php
	$vars          = $wp_query->query_vars;
	$thb_size      = array_key_exists( 'thb_size', $vars ) ? $vars['thb_size'] : false;
	$thb_animation = array_key_exists( 'thb_animation', $vars ) ? $vars['thb_animation'] : false;
	$thb_aspect    = array_key_exists( 'thb_aspect', $vars ) ? $vars['thb_aspect'] : false;
	$thb_masonry   = array_key_exists( 'thb_masonry', $vars ) ? $vars['thb_masonry'] : false;
	$thb_grid_type = array_key_exists( 'thb_grid_type', $vars ) ? $vars['thb_grid_type'] : 4;

	$thb_id        = get_the_ID();
	$element_class = uniqid( 'portfolio-id-' . $thb_id . '-' );
	$image_id      = get_post_thumbnail_id( $thb_id );
	$image_url     = wp_get_attachment_image_src( $image_id, 'full' );

	/* Colors */
	$main_color_title = get_post_meta( $thb_id, 'main_color_title', true );
	$main_color       = get_post_meta( $thb_id, 'main_color', true );

	/* Categories */
	$categories = get_the_term_list( $thb_id, 'portfolio-category', '', ', ', '' );
if ( '' !== $categories && ! empty( $categories ) ) {
	$categories = wp_strip_all_tags( $categories );
}

	$terms = get_the_terms( $thb_id, 'portfolio-category' );

	$cats = '';
if ( ! empty( $terms ) ) {
	foreach ( $terms as $term_val ) {
		$cats .= ' thb-cat-' . strtolower( $term_val->slug ); }
}

	/* Classes */
	$class[] = 'columns';
	$class[] = 'type-portfolio';
	$class[] = 'style9';
	$class[] = $thb_size;
	$class[] = $thb_animation;
	$class[] = $main_color_title;
	$class[] = $cats;
	$class[] = $element_class;

	/* Link Class */
	$link_class[] = 'thb-portfolio-link';

	/* Listing Type */
	$main_listing_type = get_post_meta( $thb_id, 'main_listing_type', true );
	$permalink         = '';
if ( 'lightbox' === $main_listing_type ) {
	$permalink    = $image_url[0];
	$link_class[] = 'mfp-image';
} elseif ( 'link' === $main_listing_type ) {
	$permalink = get_post_meta( $thb_id, 'main_listing_link', true );
} elseif ( 'lightbox-video' === $main_listing_type ) {
	$permalink    = get_post_meta( $thb_id, 'main_listing_link', true );
	$link_class[] = 'mfp-video';
} else {
	$permalink = get_the_permalink();
}

	/* Video Item */
if ( 'video' === $main_listing_type ) {
	$main_listing_video = get_post_meta( $thb_id, 'main_listing_video', true );
	$class[]            = 'thb-video-item';
}
	/* Check Mobile Video */
if ( thb_is_mobile() && ot_get_option( 'mobile_portfolio_video_disable', 'off' ) === 'on' ) {
	$main_listing_type = 'regular';
	$class[]           = 'thb-video-disabled-mobile';
}

	/* Image sizes */
	$image_size = $thb_aspect ? 'revolution-masonry-x2' : 'revolution-square-x2';
if ( $thb_masonry ) {
	$masonry_size     = get_post_meta( $thb_id, 'masonry_size', true );
	$thb_masonry_size = thb_get_masonry_size( $masonry_size, $thb_grid_type );
	$class[]          = $thb_masonry_size['class'];
	$image_size       = $thb_masonry_size['image_size'];
}
?>
<div <?php post_class( $class ); ?> id="portfolio-<?php the_ID(); ?>">
	<div class="portfolio-holder">
		<?php if ( 'video' === $main_listing_type ) { ?>
		<div class="thb-portfolio-video" data-vide-bg="mp4: <?php echo esc_url( $main_listing_video ); ?>, poster: <?php echo esc_attr( $image_url[0] ); ?>" data-vide-options="posterType: 'auto', autoplay: false, loop: true, muted: true, position: 50% 50%, resizing: true"></div>
		<?php } ?>
		<div class="thb-portfolio-image"><?php the_post_thumbnail( $image_size ); ?></div>
		<a href="<?php echo esc_url( $permalink ); ?>" class="<?php echo esc_attr( implode( ' ', $link_class ) ); ?>"></a>
		<div class="thb-portfolio-hover"></div>
		<div class="thb-portfolio-content">
				<?php if ( $categories ) { ?>
				<aside class="thb-categories"><span><?php echo esc_html( $categories ); ?></span></aside>
				<?php } ?>
				<h3><?php the_title(); ?></h3>
		</div>
	</div>
	<?php if ( $main_color ) { ?>
	<style>
		.<?php echo esc_attr( $element_class ); ?>.style9 .thb-portfolio-hover {
			<?php echo esc_html( thb_css_gradient( $main_color[0], $main_color[1], '-135', true ) ); ?>
		}
	</style>
	<?php } ?>
</div>
