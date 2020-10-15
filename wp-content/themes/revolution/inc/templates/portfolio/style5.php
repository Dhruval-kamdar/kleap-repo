<?php
	$vars = $wp_query->query_vars;
	$thb_size = array_key_exists('thb_size', $vars) ? $vars['thb_size'] : false;
	$thb_animation = array_key_exists('thb_animation', $vars) ? $vars['thb_animation'] : false;
	$thb_aspect = array_key_exists('thb_aspect', $vars) ? $vars['thb_aspect'] : false;

	$thb_id = get_the_ID();
	$element_class = uniqid( 'portfolio-id-' . $thb_id . '-');
	$image_id = get_post_thumbnail_id($thb_id);
	$image_url = wp_get_attachment_image_src($image_id, 'full');

	// Colors
	$main_color_title = get_post_meta($thb_id, 'main_color_title', true);
	$main_color = get_post_meta($thb_id, 'main_color', true);

	// Categories
	$categories = get_the_term_list( $thb_id, 'portfolio-category', '', ', ', '' );
	if ($categories !== '' && !empty($categories)) {
		$categories = wp_strip_all_tags($categories);
	}

	$terms = get_the_terms( $thb_id, 'portfolio-category' );

	$cats = '';
	if (!empty($terms)) {
		foreach ($terms as $term) { $cats .= ' thb-cat-'.strtolower($term->slug); }
	}

	// Classes
	$class[] = 'columns';
	$class[] = 'type-portfolio';
	$class[] = 'style5';
	$class[] = $thb_size;
	$class[] = $thb_animation;
	$class[] = $cats;
	$class[] = $element_class;

	// Link Class
	$link_class[] = 'thb-portfolio-link';

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
	  $class[] = 'thb-video-item';
	}
	// Check Mobile Video
	if (thb_is_mobile() && ot_get_option( 'mobile_portfolio_video_disable','off') === 'on') {
		$main_listing_type = 'regular';
		$class[] = 'thb-video-disabled-mobile';
	}

	// Image sizes
	$image_size = $thb_aspect ? 'revolution-masonry-x2' : 'revolution-square-x2';
?>
<div <?php post_class( $class ); ?> id="portfolio-<?php the_ID(); ?>">
	<div class="portfolio-holder">
		<div class="thb-portfolio-image">
			<?php if ($main_listing_type == 'video') { ?>
			<div class="thb-portfolio-video" data-vide-bg="mp4: <?php echo esc_url($main_listing_video); ?>, poster: <?php echo esc_attr($image_url[0]); ?>" data-vide-options="posterType: 'auto', autoplay: false, loop: true, muted: true, position: 50% 50%, resizing: true"></div>
			<?php } ?>
			<?php the_post_thumbnail( $image_size ); ?>
			<div class="thb-portfolio-hover"></div>
		</div>
		<a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr(implode(' ', $link_class)); ?>"></a>
		<div class="thb-portfolio-content">
			<h6><?php the_title(); ?></h6>
			<?php if ($categories) { ?>
			<aside class="thb-categories"><span><?php echo esc_html($categories); ?></span></aside>
			<?php } ?>
		</div>
	</div>
	<?php if ($main_color) { ?>
	<style>
		.<?php echo esc_attr($element_class) ?>.style5 .thb-portfolio-hover {
			<?php echo esc_html(thb_css_gradient($main_color[0], $main_color[1], "0", true)); ?>
		}
	</style>
	<?php } ?>
</div>