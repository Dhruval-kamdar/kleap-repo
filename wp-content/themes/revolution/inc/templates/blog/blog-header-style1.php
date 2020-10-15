<div class="thb-page-header">
	<h1><?php
	if ( is_archive() ) {
		echo single_term_title();
	} elseif ( is_search() ) {
		esc_html_e( 'Search Results for: ', 'revolution' );
		the_search_query();
	} else {
		single_post_title();
	}
	?></h1>
	<?php if ( is_search() ) { ?>
		<div class="row align-center">
			<div class="small-10 medium-7 large-4 columns">
				<?php get_search_form( 1 ); ?>
			</div>
		</div>
	<?php } ?>
	<?php if ( is_category() ) { ?>
		<div class="row align-center archive-description">
			<div class="small-12 medium-10 large-8 columns">
				<?php
				if ( ! isset( $desc ) ) {
					$desc = null;
				}
				if ( category_description() === $desc ) {
					echo wp_kses_post( $desc ); }
				?>
			</div>
		</div>
	<?php } ?>
	<?php
		$blog_header_categories = ot_get_option( 'blog_header_categories' );

	if ( $blog_header_categories && ! is_search() ) {
		?>
			<ul class="thb-blog-categories">
		<?php
		foreach ( $blog_header_categories as $thb_cat ) {
			$category = get_term_by( 'id', $thb_cat, 'category' );

			$active_id = get_queried_object_id();

			$class = $active_id === $thb_cat ? 'active' : '';
			?>
					<li><a href="<?php echo esc_url( get_category_link( $thb_cat ) ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $category->name ); ?></a></li>
				<?php
		}
		?>
			</ul>
		<?php
	}
	?>
</div>
