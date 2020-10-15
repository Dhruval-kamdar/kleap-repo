<?php
function thb_blog_posts() {
	check_ajax_referer( 'thb_blog_ajax', 'security' );
	$page       = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );
	$ppp        = get_option( 'posts_per_page' );
	$blog_style = ot_get_option( 'blog_style', 'style3' );

	$args = array(
		'posts_per_page' => $ppp,
		'paged'          => $page,
		'post_status'    => 'publish',
	);

	$thb_blog_columns = ot_get_option( 'thb_blog_columns', '4' );
	$blog_animation   = ot_get_option( 'blog_animation', '' );
	$columns          = thb_translate_columns( $thb_blog_columns );

	$more_query = new WP_Query( $args );
	add_filter( 'wp_get_attachment_image_attributes', 'thb_lazy_low_quality', 10, 3 );
	if ( $more_query->have_posts() ) :
		while ( $more_query->have_posts() ) :
			$more_query->the_post();
			set_query_var( 'columns', $columns );
			set_query_var( 'thb_animation', $blog_animation );
			if ( 'style5' === $blog_style ) {
				set_query_var( 'thb_i', '999' );
			}
			get_template_part( 'inc/templates/postbit/' . $blog_style );
	endwhile;
	endif;
	wp_die();
}
add_action( 'wp_ajax_nopriv_thb_blog_ajax', 'thb_blog_posts' );
add_action( 'wp_ajax_thb_blog_ajax', 'thb_blog_posts' );

function thb_posts_ajax() {
	check_ajax_referer( 'thb_posts_ajax', 'security' );
	$count   = filter_input( INPUT_POST, 'count', FILTER_VALIDATE_INT );
	$style   = filter_input( INPUT_POST, 'style', FILTER_SANITIZE_STRING );
	$columns = filter_input( INPUT_POST, 'columns', FILTER_SANITIZE_STRING );

	$thb_i         = filter_input( INPUT_POST, 'thb_i', FILTER_VALIDATE_INT );
	$thb_date      = filter_input( INPUT_POST, 'thb_date', FILTER_VALIDATE_BOOLEAN );
	$thb_cat       = filter_input( INPUT_POST, 'thb_cat', FILTER_VALIDATE_BOOLEAN );
	$thb_excerpt   = filter_input( INPUT_POST, 'thb_excerpt', FILTER_VALIDATE_BOOLEAN );
	$thb_animation = filter_input( INPUT_POST, 'thb_animation', FILTER_SANITIZE_STRING );

	$columns = thb_translate_columns( $columns );

	$loop = filter_input( INPUT_POST, 'loop', FILTER_SANITIZE_STRING );
	$page = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );

	$source_data          = VcLoopSettings::parseData( $loop );
	$source_data['paged'] = $page;
	$source_data          = thb_moveKeyBefore( $source_data, 'offset', 'paged' );
	$query_builder        = new ThbLoopQueryBuilder( $source_data );
	$posts                = $query_builder->build();
	$more_query           = $posts[1];

	add_filter( 'wp_get_attachment_image_attributes', 'thb_lazy_low_quality', 10, 3 );
	if ( $more_query->have_posts() ) :
		while ( $more_query->have_posts() ) :
			$more_query->the_post();
			set_query_var( 'columns', $columns );
			set_query_var( 'thb_i', false );
			set_query_var( 'thb_date', $thb_date );
			set_query_var( 'thb_cat', $thb_cat );
			set_query_var( 'thb_excerpt', $thb_excerpt );
			set_query_var( 'thb_animation', $thb_animation );
			get_template_part( 'inc/templates/postbit/' . $style );
	endwhile;
	endif;
	wp_die();
}
add_action( 'wp_ajax_nopriv_thb_posts_ajax', 'thb_posts_ajax' );
add_action( 'wp_ajax_thb_posts_ajax', 'thb_posts_ajax' );

function thb_load_more() {
	check_ajax_referer( 'thb_ajax', 'security' );
	$aspect    = filter_input( INPUT_POST, 'aspect', FILTER_VALIDATE_BOOLEAN );
	$columns   = filter_input( INPUT_POST, 'columns', FILTER_SANITIZE_STRING );
	$style     = filter_input( INPUT_POST, 'style', FILTER_SANITIZE_STRING );
	$masonry   = filter_input( INPUT_POST, 'masonry', FILTER_SANITIZE_STRING );
	$grid_type = filter_input( INPUT_POST, 'grid_type', FILTER_VALIDATE_INT );

	$loop = filter_input( INPUT_POST, 'loop', FILTER_SANITIZE_STRING );
	$page = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );

	$loop .= '|paged:' . $page;

	$source_data   = VcLoopSettings::parseData( $loop );
	$query_builder = new ThbLoopQueryBuilder( $source_data );
	$posts         = $query_builder->build();
	$posts         = $posts[1];

	add_filter( 'wp_get_attachment_image_attributes', 'thb_lazy_low_quality', 10, 3 );

	if ( $posts->have_posts() ) :
		while ( $posts->have_posts() ) :
			$posts->the_post();
			set_query_var( 'thb_masonry', $masonry );
			set_query_var( 'thb_aspect', $aspect );
			set_query_var( 'thb_size', $columns );
			set_query_var( 'thb_grid_type', $grid_type );
			get_template_part( 'inc/templates/portfolio/' . $style );
	endwhile;
	endif;

	wp_die();
}
add_action( 'wp_ajax_nopriv_thb_ajax', 'thb_load_more' );
add_action( 'wp_ajax_thb_ajax', 'thb_load_more' );

/** Thb Newsletter Popup. */
function thb_newsletter() {

	if ( ! class_exists( 'Revolution_plugin' ) ) {
		return; }

	$newsletter = ot_get_option( 'newsletter', 'off' );

	if ( 'on' !== $newsletter ) {
		return; }

	if ( ! is_admin() ) {
			$newsletter_image = ot_get_option( 'newsletter_image' );
		?>
		<aside id="newsletter-popup" class="mfp-hide theme-popup newsletter-popup">
			<?php if ( $newsletter_image ) { ?>
				<figure class="newsletter-image"><?php echo wp_get_attachment_image( $newsletter_image, 'revolution-tall-x2' ); ?></figure>
			<?php } ?>
			<div class="newsletter-content">
				<?php
					$newsletter_content = ot_get_option( 'newsletter_content' );
					echo do_shortcode( $newsletter_content );
				?>
			</div>
		</aside>
		<?php
	}
}
add_action( 'wp_footer', 'thb_newsletter' );
