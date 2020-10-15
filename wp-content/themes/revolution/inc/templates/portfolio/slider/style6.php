<?php
	add_filter( 'excerpt_length', 'thb_mini_excerpt_length' );
	$thb_id = get_the_ID();
	$image_id = get_post_thumbnail_id($thb_id);
	$image_url = wp_get_attachment_image_src($image_id, 'full');

	// Categories
	$categories = get_the_term_list( $thb_id, 'portfolio-category', '', ', ', '' );
	if ($categories !== '' && !empty($categories)) {
		$categories = wp_strip_all_tags($categories);
	}

	// Link Class
	$link_class[] = 'view-project-btn animation bottom-to-top-3d-small';

	// Listing Type
	$main_listing_type = get_post_meta($thb_id, 'main_listing_type', true);
	$permalink = '';
	if ($main_listing_type == 'lightbox') {
		$permalink = $image_url[0];
		$link_class[] = 'mfp-image';
	} elseif ($main_listing_type == 'link') {
		$permalink = get_post_meta($thb_id, 'main_listing_link', true);
	} elseif ($main_listing_type == 'lightbox-video') {
		$permalink = get_post_meta($thb_id, 'main_listing_link', true);
		$link_class[] = 'mfp-video';
	} else {
		$permalink = get_the_permalink();
	}
	// Video Item
	if ($main_listing_type == 'video') {
	  $main_listing_video = get_post_meta($thb_id, 'main_listing_video', true);
	  $class[] = 'thb-video-slide';
	}
	// Check Mobile Video
	if (thb_is_mobile() && ot_get_option( 'mobile_portfolio_video_disable','off') === 'on') {
		$main_listing_type = 'regular';
		$class[] = 'thb-video-disabled-mobile';
	}

	// Classes
	$class[] = 'portfolio-slide';
	$class[] = 'portfolio-slide-style6';
	$class[] = 'perspective-wrap';
	$class[] = 'thb-portfolio-' . $thb_id;
?>
<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
	<?php if ($main_listing_type == 'video') { ?>
	<div class="thb-portfolio-video" data-vide-bg="mp4: <?php echo esc_url($main_listing_video); ?>, poster: <?php echo esc_attr($image_url[0]); ?>" data-vide-options="posterType: 'auto', autoplay: false, loop: true, muted: true, position: 50% 50%, resizing: true"></div>
	<?php } ?>
	<div class="cover-bg">
		<?php the_post_thumbnail('full'); ?>
	</div>
	<div class="row max_width align-center">
		<div class="small-12 medium-9 large-6 columns">
			<aside class="thb-categories animation bottom-to-top-3d"><span><?php echo esc_html($categories); ?></span></aside>
			<div class="thb-line thb-line-top"><span class="animation scale"></span></div>
			<h1 class="animation bottom-to-top-3d"><a href="<?php echo esc_url($permalink); ?>" class="<?php if ($main_listing_type == 'lightbox') { echo esc_attr('mfp-image'); } ?>"><?php the_title(); ?></a></h1>
			<div class="thb-line thb-line-bottom"><span class="animation scale"></span></div>
			<a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr(implode(' ', $link_class)); ?>"><span><?php esc_html_e('View Project', 'revolution' ); ?></span></a>
		</div>
	</div>
	<?php if (get_the_excerpt()) { ?>
	<div class="style6-info-container animation">
		<h6><?php esc_html_e('INFO', 'revolution' ); ?></h6>
		<?php the_excerpt(); ?>
	</div>
	<?php } ?>
</div>
