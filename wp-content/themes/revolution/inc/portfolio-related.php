<?php
function thb_get_masonry_size( $size = '', $thb_grid_type = 4 ) {

	$class_image = array();
	switch ( $size ) {
		case 'large':
			$class_image['class'] = 'small-12 medium-6 large-6 masonry-large';
			if ( $thb_grid_type == '3' ) {
				$class_image['class'] = 'small-12 medium-8 masonry-large';
			}
			$class_image['image_size'] = $thb_grid_type == '3' ? 'revolution-squaresmall-x3' : 'revolution-square-x3';
			break;
		case 'wide':
			$class_image['class'] = 'small-12 medium-6 large-6 masonry-wide';
			if ( $thb_grid_type == '3' ) {
				$class_image['class'] = 'small-12 medium-8 masonry-wide';
			}
			$class_image['image_size'] = $thb_grid_type == '3' ? 'revolution-squaresmallwide-x3' : 'revolution-wide-x2';
			break;
		case 'tall':
			$class_image['class'] = 'small-12 medium-6 large-3 masonry-tall';
			if ( $thb_grid_type == '3' ) {
				$class_image['class'] = 'small-12 medium-4 masonry-tall';
			}
			$class_image['image_size'] = $thb_grid_type == '3' ? 'revolution-squaresmalltall-x3' : 'revolution-tall-x2';
			break;
		case 'small':
		default:
			$class_image['class'] = 'small-12 medium-6 large-3 masonry-small';
			if ( $thb_grid_type == '3' ) {
				$class_image['class'] = 'small-12 medium-4 masonry-small';
			}
			$class_image['image_size'] = $thb_grid_type == '3' ? 'revolution-squaresmall-x2' : 'revolution-square-x2';
			break;
	}
	return $class_image;
}

/* Portfolio Categories Array */
function thb_portfolioCategories() {
	$args                 = array(
		'taxonomy'   => 'portfolio-category',
		'hide_empty' => '0',
		'fields'     => 'id=>name',
	);
	$portfolio_categories = new WP_Term_Query( $args );

	$out = array();
	if ( $portfolio_categories->terms ) {
		foreach ( $portfolio_categories->terms as $id => $name ) {
			$out[ $name ] = $id;
		}
	}
	return $out;
}

/* Header Filter */
function thb_portfolio_categories( $categories, $id, $style, $portfolio_id_array = false, $filter_style2_color = 'light' ) {

	if ( ! empty( $categories ) || $categories ) {
		$portfolio_id_array = $portfolio_id_array ? $portfolio_id_array : array();

		?>
		<nav class="thb-portfolio-filter <?php echo esc_attr( $style ); ?>" id="thb-filter-<?php echo esc_attr( $id ); ?>" data-style2-color="<?php echo esc_attr( $filter_style2_color ); ?>">
			<?php if ( $style === 'style1' || $style === 'style3' ) { ?>
			<ul>
				<li><a data-filter="*" class="active"><?php esc_html_e( 'All', 'revolution' ); ?></a></li>
				<?php
				foreach ( $categories as $cat ) {
					$term = get_term( $cat );

					if ( $term && $term->taxonomy === 'portfolio-category' ) {
						echo '<li><a data-filter=".thb-cat-' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</a></li>';
					}
				}
				?>
			</ul>
			<?php } elseif ( $style === 'style2' ) { ?>
			<span class="thb-filter-by"><?php esc_html_e( 'Filter by', 'revolution' ); ?></span>
			<select class="thb-select2">
				<option value="*" selected><?php esc_html_e( 'All', 'revolution' ); ?></option>
				<?php
				foreach ( $categories as $cat ) {
					$term = get_term( $cat );
					echo '<option value=".thb-cat-' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
				}
				?>
			</select>
			<?php } ?>
		</nav>
		<?php
	}
}
add_action( 'thb-render-filter', 'thb_portfolio_categories', 10, 5 );


/* Portfolio Attributes */
function thb_portfolio_attributes( $style, $columns, $animation, $element_id = false ) {
	$id          = get_the_ID();
	$client_more = get_post_meta( $id, 'client_more', true );

	$classes[] = 'portfolio-attributes';
	$classes[] = $style;
	$classes[] = 'row';

	$attr_classes[] = isset( $columns ) ? 'small-12 ' . $columns : 'small-12';
	$attr_classes[] = 'columns';
	$attr_classes[] = $animation;
	?>
	<?php if ( ! empty( $client_more ) ) { ?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
								<?php
								if ( $id ) {
									?>
			id="<?php echo esc_attr( $element_id ); ?>"<?php } ?>>
			<?php foreach ( $client_more as $more ) { ?>
				<div class="<?php echo esc_attr( implode( ' ', $attr_classes ) ); ?>">
					<div class="attribute">
						<h6><?php echo esc_attr( $more['title'] ); ?>: </h6>
						<?php if ( $more['client_custom_link'] !== '' ) { ?>
							<a href="<?php echo esc_html( $more['client_custom_link'] ); ?>" target="_blank">
						<?php } ?>
							<?php echo wp_kses_post( apply_filters( 'the_content', $more['client_custom_value'] ) ); ?>
						<?php if ( $more['client_custom_link'] !== '' ) { ?>
							</a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
add_action( 'thb_portfolio_attributes', 'thb_portfolio_attributes', 3, 5 );

/** Social Sharing */
function thb_social_article_detail() {
	$id           = get_the_ID();
	$sharing_type = ot_get_option( 'sharing_buttons', array() );
	?>
	<?php if ( ! empty( $sharing_type ) ) { ?>
<aside class="share_wrapper">
	<a href="#" class="share-post-link"><?php get_template_part( 'assets/img/svg/share.svg' ); ?><span><?php esc_html_e( 'Share', 'revolution' ); ?></span></a>
		<?php
		function thb_add_shareform( $id ) {
			$permalink    = get_permalink( $id );
			$title        = the_title_attribute(
				array(
					'echo' => 0,
					'post' => $id,
				)
			);
			$image_id     = get_post_thumbnail_id( $id );
			$image        = wp_get_attachment_image_src( $image_id, 'full' );
			$twitter_user = ot_get_option( 'twitter_bar_username', 'anteksiler' );
			$sharing_type = ot_get_option( 'sharing_buttons', array() );
			?>
		<div class="share_container">
			<div class="spacer"></div>
			<div class="vcenter">
				<div class="product_share">
					<h4><?php esc_html_e( 'Share This', 'revolution' ); ?></h4>
					<?php if ( in_array( 'facebook', $sharing_type, true ) ) { ?>
					<a href="<?php echo 'http://www.facebook.com/sharer.php?u=' . rawurlencode( esc_url( $permalink ) ) . ''; ?>" class="social facebook boxed-icon white-fill"><i class="fa fa-facebook"></i></a>
					<?php } ?>
					<?php if ( in_array( 'twitter', $sharing_type, true ) ) { ?>
					<a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . rawurlencode( $title ) . '&url=' . rawurlencode( esc_url( $permalink ) ) . '&via=' . rawurlencode( get_bloginfo( 'name' ) ) . '' ); ?>" class="social twitter boxed-icon white-fill"><i class="fa fa-twitter"></i></a>
					<?php } ?>
					<?php if ( in_array( 'pinterest', $sharing_type, true ) ) { ?>
					<a href="<?php echo esc_url( 'http://pinterest.com/pin/create/link/?url=' . $permalink . '&amp;media=' . ( ! empty( $image[0] ) ? $image[0] : '' ) . '&description=' . rawurlencode( $title ) ); ?>" class="social pinterest boxed-icon white-fill" nopin="nopin" data-pin-no-hover="true"><i class="fa fa-pinterest"></i></a>
					<?php } ?>
					<?php if ( in_array( 'linkedin', $sharing_type, true ) ) { ?>
					<a href="<?php echo esc_url( 'https://www.linkedin.com/cws/share?url=' . esc_url( $permalink ) . '' ); ?>" class="social linkedin boxed-icon white-fill"><i class="fa fa-linkedin"></i></a>
					<?php } ?>
					<?php if ( in_array( 'whatsapp', $sharing_type, true ) ) { ?>
					<a href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( $title ) ); ?>" class="whatsapp social boxed-icon white-fill" data-href="<?php echo esc_url( $permalink ); ?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i></a>
					<?php } ?>
					<?php if ( in_array( 'facebook-messenger', $sharing_type, true ) ) { ?>
						<?php $fb_app_id = ot_get_option( 'facebook_app_id' ); ?>
					<a href="<?php echo 'fb-messenger://share/?link=' . esc_url( $permalink ) . '&app_id=' . esc_url( $fb_app_id ); ?>" class="facebook-messenger social boxed-icon white-fill"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="96 93 322 324"><path d="M257 93c-88.918 0-161 67.157-161 150 0 47.205 23.412 89.311 60 116.807V417l54.819-30.273C225.449 390.801 240.948 393 257 393c88.918 0 161-67.157 161-150S345.918 93 257 93zm16 202l-41-44-80 44 88-94 42 44 79-44-88 94z" /></svg></a>
					<?php } ?>
					<?php if ( in_array( 'vkontakte', $sharing_type, true ) ) { ?>
					<a href="<?php echo esc_url( 'http://vk.com/share.php?url=' . esc_url( $permalink ) . '' ); ?>" class="social vk boxed-icon white-fill"><i class="fa fa-vk"></i></a>
					<?php } ?>
					<?php if ( in_array( 'email', $sharing_type, true ) ) { ?>
					<a href="<?php echo esc_url( 'mailto:?Subject=' . rawurlencode( $title ) ); ?>" class="email social boxed-icon white-fill"><i class="fa fa-envelope"></i></a>
					<?php } ?>
				</div>
				<div class="product_copy">
					<h4><?php esc_html_e( 'Copy Link to Clipboard', 'revolution' ); ?></h4>
					<form>
						<input type="text" class="copy-value" value="<?php echo esc_attr( $permalink ); ?>" readonly/>
						<a class="btn"><?php esc_html_e( 'Copy', 'revolution' ); ?></a>
					</form>
				</div>
			</div>
		</div>
			<?php
		}
		add_action( 'wp_footer', 'thb_add_shareform', 100, 1 );
		?>
</aside>
	<?php } ?>
	<?php
}
add_action( 'thb_social_article_detail', 'thb_social_article_detail', 3, 3 );

/** Portfolio Share */
function thb_portfolio_share( $widget_title, $sharing_type, $thb_alignment, $style = 'style1' ) {
	$id           = get_the_ID();
	$permalink    = get_permalink( $id );
	$title        = the_title_attribute(
		array(
			'echo' => 0,
			'post' => $id,
		)
	);
	$image_id     = get_post_thumbnail_id( $id );
	$image        = wp_get_attachment_image_src( $image_id, 'full' );
	$twitter_user = ot_get_option( 'twitter_bar_username' );

	$class[] = 'thb-share-icons';
	$class[] = $thb_alignment;
	$class[] = $style;

	$icon_class = 'social ' . ( 'style1' === $style ? 'boxed-icon' : '' );
	?>
	<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
		<?php
		if ( $widget_title ) {
			?>
			<h6><?php echo esc_html( $widget_title ); ?></h6><?php } ?>
		<ul>
			<?php if ( in_array( 'facebook', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'http://www.facebook.com/sharer.php?u=' . rawurlencode( esc_url( $permalink ) ) . ''; ?>" class="<?php echo esc_attr( $icon_class ); ?> facebook"><i class="fa fa-facebook"></i></a></li>
			<?php } ?>
			<?php if ( in_array( 'twitter', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'https://twitter.com/intent/tweet?text=' . rawurlencode( $title ) . '&url=' . rawurlencode( esc_url( $permalink ) ) . '&via=' . rawurlencode( $twitter_user ? $twitter_user : get_bloginfo( 'name' ) ) . ''; ?>" class="<?php echo esc_attr( $icon_class ); ?> twitter"><i class="fa fa-twitter"></i></a></li>
			<?php } ?>
			<?php if ( in_array( 'pinterest', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'http://pinterest.com/pin/create/link/?url=' . esc_url( $permalink ) . '&amp;media=' . ( ! empty( $image[0] ) ? $image[0] : '' ) . '&description=' . rawurlencode( $title ); ?>" class="<?php echo esc_attr( $icon_class ); ?> pinterest" nopin="nopin" data-pin-no-hover="true"><i class="fa fa-pinterest"></i></a></li>
			<?php } ?>
			<?php if ( in_array( 'linkedin', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'https://www.linkedin.com/cws/share?url=' . esc_url( $permalink ) . ''; ?>" class="<?php echo esc_attr( $icon_class ); ?> linkedin"><i class="fa fa-linkedin"></i></a></li>
			<?php } ?>
			<?php if ( in_array( 'whatsapp', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'https://wa.me/?text=' . rawurlencode( $title . ' ' . esc_url( $permalink ) ); ?>" class="<?php echo esc_attr( $icon_class ); ?> whatsapp" data-href="<?php echo esc_url( $permalink ); ?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i></a></li>
			<?php } ?>
			<?php if ( in_array( 'facebook-messenger', $sharing_type, true ) ) { ?>
				<?php $fb_app_id = ot_get_option( 'facebook_app_id' ); ?>
			<li><a href="<?php echo 'fb-messenger://share/?link=' . esc_url( $permalink ) . '&app_id=' . esc_url( $fb_app_id ); ?>" class="<?php echo esc_attr( $icon_class ); ?> facebook-messenger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="96 93 322 324"><path d="M257 93c-88.918 0-161 67.157-161 150 0 47.205 23.412 89.311 60 116.807V417l54.819-30.273C225.449 390.801 240.948 393 257 393c88.918 0 161-67.157 161-150S345.918 93 257 93zm16 202l-41-44-80 44 88-94 42 44 79-44-88 94z" /></svg></a></li>
			<?php } ?>
			<?php if ( in_array( 'vkontakte', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'http://vk.com/share.php?url=' . esc_url( $permalink ) . ''; ?>" class="<?php echo esc_attr( $icon_class ); ?> vk"><i class="fa fa-vk"></i></a></li>
			<?php } ?>
			<?php if ( in_array( 'email', $sharing_type, true ) ) { ?>
			<li><a href="<?php echo 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( esc_url( $permalink ) ); ?>" class="<?php echo esc_attr( $icon_class ); ?> email"><i class="fa fa-envelope"></i></a></li>
			<?php } ?>
		</ul>
	</div>
	<?php
}
add_action( 'thb_portfolio_share', 'thb_portfolio_share', 3, 4 );

/** Article Prev/Next */
function thb_portfolio_nav() {

	if ( 'on' !== ot_get_option( 'portfolio_nav', 'on' ) ) {
		return; }

	$portfolio_nav_style       = ot_get_option( 'portfolio_nav_style', 'style3' );
	$portfolio_nav_style3_hide = ot_get_option( 'portfolio_nav_style3_hide', 'off' );
	$portfolio_nav_cat         = ot_get_option( 'portfolio_nav_cat', 'off' );
	$in_same_term              = 'on' === $portfolio_nav_cat ? true : false;
	$prev                      = get_adjacent_post( $in_same_term, false, true, 'portfolio-category' );
	$next                      = get_adjacent_post( $in_same_term, false, false, 'portfolio-category' );
	?>
	<div class="thb_portfolio_nav <?php echo esc_attr( $portfolio_nav_style ); ?>" data-hide="<?php echo esc_attr( $portfolio_nav_style3_hide ); ?>">
		<?php if ( 'style1' === $portfolio_nav_style ) { ?>
			<div class="row full-width-row no-row-padding no-padding">
				<div class="small-12 columns">
					<?php
					if ( $prev ) {
						$image_id = get_post_thumbnail_id( $prev->ID );
						?>
						<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post_nav_link prev">
							<strong>
								<span class="next">
								<?php esc_html_e( 'Next Project', 'revolution' ); ?>
									<span class="title"><?php echo esc_html( $prev->post_title ); ?></span>
								</span>
							</strong>
						<?php
						if ( $image_id ) {
							?>
							<div class="inner"><?php echo wp_get_attachment_image( $image_id, 'revolution-bloglarge-x2' ); ?></div><?php } ?>
						</a>
					<?php } ?>
				</div>
			</div>
		<?php } elseif ( 'style2' === $portfolio_nav_style ) { ?>
			<div class="row full-width-row no-row-padding no-padding">
				<div class="small-12 medium-6 columns">
					<?php
					if ( $prev ) {
						$image_id = get_post_thumbnail_id( $prev->ID );
						?>
						<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post_nav_link prev">
							<strong>
							<?php esc_html_e( '(p) Previous', 'revolution' ); ?>
							</strong>
							<div>
								<span><?php echo esc_html( $prev->post_title ); ?></span>
								<em><?php esc_html_e( 'View Portfolio', 'revolution' ); ?></em>
							</div>
						<?php
						if ( $image_id ) {
							?>
							<div class="inner"><?php echo wp_get_attachment_image( $image_id, 'revolution-bloglarge-x2' ); ?></div><?php } ?>
						</a>
					<?php } ?>
				</div>
				<div class="small-12 medium-6 columns">
					<?php
					if ( $next ) {
						$image_id = get_post_thumbnail_id( $next->ID );
						?>
						<a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="post_nav_link next">
							<strong>
							<?php esc_html_e( 'Next (n)', 'revolution' ); ?>
							</strong>
							<div>
								<span><?php echo esc_html( $next->post_title ); ?></span>
								<em><?php esc_html_e( 'View Portfolio', 'revolution' ); ?></em>
							</div>
						<?php
						if ( $image_id ) {
							?>
							<div class="inner"><?php echo wp_get_attachment_image( $image_id, 'revolution-bloglarge-x2' ); ?></div><?php } ?>
						</a>
					<?php } ?>
				</div>
			</div>
		<?php } elseif ( 'style3' === $portfolio_nav_style ) { ?>
			<div class="row">
				<div class="small-12 columns">
					<p><?php esc_html_e( 'More Works', 'revolution' ); ?></p>
					<ul>
					<?php
						$count      = ot_get_option( 'portfolio_nav_count', '10' );
						$post_id    = get_the_ID();
						$args       = array(
							'posts_per_page' => $count,
							'post__not_in'   => array( $post_id ),
							'post_type'      => 'portfolio',
							'post_status'    => 'publish',
						);
						$more_posts = new WP_Query( $args );

						if ( $more_posts->have_posts() ) :
							while ( $more_posts->have_posts() ) :
								$more_posts->the_post();
								$image_id   = get_post_thumbnail_id();
								$image_link = wp_get_attachment_image_src( $image_id, 'revolution-bloglarge-x2' );
								?>
								<li>
									<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<?php $images[] = $image_id ? $image_id : ''; ?>
								</li>

								<?php
						endwhile;
endif;

						?>
					</ul>
				</div>
			</div>
			<?php $i = 0; foreach ( $images as $image ) { ?>
				<div class="inner
				<?php
				if ( ! $i && 'off' === $portfolio_nav_style3_hide ) {
					?>
					active<?php } ?>"><?php echo wp_get_attachment_image( $image, 'revolution-bloglarge-x2' ); ?></div>
			<?php $i++; } ?>
		<?php } elseif ( 'style4' === $portfolio_nav_style ) { ?>
			<div class="row">
				<div class="small-6 columns">
					<?php
					if ( $prev ) {
						?>
						<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post_nav_link prev">
						<?php get_template_part( 'assets/img/svg/prev_arrow.svg' ); ?>
							<span><?php echo esc_html( $prev->post_title ); ?></span>
						</a>
						<?php
					}
					?>
				</div>
				<div class="small-6 columns">
					<?php
					if ( $next ) {
						?>
						<a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="post_nav_link next">
							<span><?php echo esc_html( $next->post_title ); ?></span>
							<?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?>
						</a>
						<?php
					}
					?>
				</div>
			</div>
		<?php } elseif ( 'style5' === $portfolio_nav_style ) { ?>

			<?php
				$count                         = ot_get_option( 'portfolio_nav_count', '10' );
				$portfolio_nav_columns         = ot_get_option( 'portfolio_nav_columns', '3' );
				$portfolio_nav_portfolio_style = ot_get_option( 'portfolio_nav_portfolio_style', 'style1' );
				$columns                       = thb_translate_columns( $portfolio_nav_columns );

				$classes[] = 'small-6 medium-6';
				$classes[] = $columns;
				$classes[] = 'columns';

				$post_id    = get_the_ID();
				$args       = array(
					'posts_per_page' => $count,
					'post__not_in'   => array( $post_id ),
					'post_type'      => 'portfolio',
					'post_status'    => 'publish',
				);
				$more_posts = new WP_Query( $args );
				?>
			<div class="row thb-portfolio regular-padding">
				<?php
				if ( $more_posts->have_posts() ) :
					while ( $more_posts->have_posts() ) :
						$more_posts->the_post();
						?>
											<?php
												set_query_var( 'thb_size', implode( ' ', $classes ) );
												get_template_part( 'inc/templates/portfolio/' . $portfolio_nav_portfolio_style );
											?>
									<?php
				endwhile;
endif;
				?>
			</div>
		<?php } ?>
	</div>
	<?php
}
add_action( 'thb_portfolio_nav', 'thb_portfolio_nav' );

/** Portfolio Video Header */
function thb_portfolio_video() {
	$id                         = get_the_ID();
	$portfolio_header_video     = get_post_meta( $id, 'portfolio_header_video', true );
	$portfolio_header_video_url = wp_get_attachment_url( $portfolio_header_video );
	if ( $portfolio_header_video_url ) {
		$portfolio_header_video_poster     = get_post_meta( $id, 'portfolio_header_video_poster', true );
		$portfolio_header_video_poster_url = wp_get_attachment_url( $portfolio_header_video_poster );
		$video_type                        = wp_check_filetype( $portfolio_header_video_url, wp_get_mime_types() );
		$poster_type                       = wp_check_filetype( $portfolio_header_video_poster_url, wp_get_mime_types() );
		$portfolio_header_video_loop       = get_post_meta( $id, 'portfolio_header_video_loop', true ) !== 'off' ? 'true' : 'false';

		$attributes[] = 'data-vide-bg="' . $video_type['ext'] . ': ' . esc_attr( $portfolio_header_video_url ) . ( $portfolio_header_video_poster_url ? ', poster: ' . esc_attr( $portfolio_header_video_poster_url ) : '' ) . '"';

		$attributes[] = 'data-vide-options="posterType: ' . ( $poster_type['ext'] ? esc_attr( $poster_type['ext'] ) : 'none' ) . ', loop: ' . $portfolio_header_video_loop . ', muted: true, position: 50% 50%, resizing: true"';
	} else {
		$attributes[] = '';
	}
	return $attributes;
}

/** Disable GIF Sizes */
function thb_disable_gif_sizes( $sizes, $metadata ) {

	/* Get filetype data. */
	$filetype = wp_check_filetype( $metadata['file'] );

	/* Check if is gif. */
	if ( 'image/gif' === $filetype['type'] ) {
		// Unset sizes if file is gif.
		$sizes = array();
	}

	/* Return sizes you want to create from image (None if image is gif.) */
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'thb_disable_gif_sizes', 10, 2 );

/** Featured Portfolio */
function thb_featured_portfolio() {
	if ( 'on' === ot_get_option( 'side_portfolio', 'off' ) ) {
		$side_portfolio_list = ot_get_option( 'side_portfolio_list' );
		?>
		<a href="#" class="featured-portfolio"><?php esc_html_e( 'Featured Items', 'revolution' ); ?></a>
<div id="thb-featured-portfolio" class="side-panel">
	<header>
				<a href="#" class="thb-mobile-close" title="<?php esc_attr_e( 'Close', 'revolution' ); ?>"><div><span></span><span></span></div></a>
			</header>
			<div class="side-panel-content custom_scroll">
				<?php
				foreach ( $side_portfolio_list as $portfolio ) {
					$id = $portfolio['portfolio'];

					/* Categories */
					$categories = get_the_term_list( $id, 'portfolio-category', '', ', ', '' );

					if ( '' !== $categories && ! empty( $categories ) ) {
						$categories = wp_strip_all_tags( $categories );
					}
					?>
<a class="quick-portfolio portfolio" id="qp-portfolio-<?php echo esc_attr( $id ); ?>" href="<?php echo esc_url( get_the_permalink( $id ) ); ?>">
			<div class="post-gallery">
					<?php echo get_the_post_thumbnail( $id, 'revolution-rectangle-x2' ); ?>
			</div>
			<div class="post-content">
	<h5><?php echo esc_html( get_the_title( $portfolio['portfolio'] ) ); ?></h5>
					<?php if ( $categories ) { ?>
				<aside class="thb-categories"><?php echo esc_html( $categories ); ?></aside>
<?php } ?>
</div>
</a>
<?php } ?>
			</div>
			</div>
		<?php
	}
}
add_action( 'thb_featured_portfolio', 'thb_featured_portfolio', 3 );

/** Related Portfolio */
function thb_related_portfolio() {
	if ( 'on' === ot_get_option( 'portfolio_related', 'off' ) ) {

		$post_id = get_the_id();
		$tags    = wp_get_post_tags( $post_id );

		if ( $tags ) {
			$tag_ids = array();
			foreach ( $tags as $individual_tag ) {
				$tag_ids[] = $individual_tag->term_id; }
			$args          = array(
				'tag__in'             => $tag_ids,
				'post__not_in'        => array( $post_id ),
				'posts_per_page'      => 3,
				'post_status'         => 'publish',
				'orderby'             => 'rand',
				'ignore_sticky_posts' => 1,
				'post_type'           => 'portfolio',
				'no_found_rows'       => true,
			);
			$related_posts = new WP_Query( $args );
			?>
			<?php if ( $related_posts->have_posts() ) : ?>
			<aside class="related-projects cf hide-on-print ">
				<div class="row">
					<div class="small-12 columns">
						<h5><?php esc_html_e( 'Related Projects', 'revolution' ); ?></h5>
					</div>
				</div>
				<div class="row thb-portfolio">
				<?php
				$portfolio_related_style = ot_get_option( 'portfolio_related_style', 'style7' );
				while ( $related_posts->have_posts() ) :
					$related_posts->the_post();
					set_query_var( 'thb_size', 'small-12 medium-4' );
					get_template_part( 'inc/templates/portfolio/' . $portfolio_related_style );
			endwhile;
				?>
		</div>
			</aside>
				<?php
			endif;
			wp_reset_postdata();
		}
	}
}
add_action( 'thb_related_portfolio', 'thb_related_portfolio', 3 );
