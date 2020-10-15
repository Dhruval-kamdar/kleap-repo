<?php $shop_full_width = ot_get_option( 'shop_full_width', 'off' ); ?>
<div class="shop_bar">
	<div class="row
	<?php
	if ( 'on' === $shop_full_width ) {
		?>
	full-width-row<?php } ?>">
	<div class="small-6 columns category_bar">
	<a href="#" id="thb-shop-filters"><?php get_template_part( 'assets/img/svg/filter.svg' ); ?> <?php esc_html_e( 'Filter', 'revolution' ); ?></a>
	<?php do_action( 'thb_breadcrumbs' ); ?>
	</div>
	<div class="small-6 columns ordering">
	<?php if ( have_posts() ) : ?>
		<?php do_action( 'thb_before_shop_loop_result_count' ); ?>
		<?php do_action( 'thb_before_shop_loop_catalog_ordering' ); ?>
	<?php endif; ?>
	</div>
</div>
</div>
