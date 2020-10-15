<?php
	$footer_bar_social_link = ot_get_option( 'footer_bar_social_link' );
	$footer_bar_menu        = ot_get_option( 'footer_bar_menu' );
?>
<aside class="footer_bar style1">
	<div class="row">
		<div class="small-12 medium-6 columns social-column">
			<?php do_action( 'thb_social_links', $footer_bar_social_link, false, true ); ?>
		</div>
		<div class="small-12 medium-6 columns">
			<?php
			if ( $footer_bar_menu ) {
				wp_nav_menu(
					array(
						'menu'       => $footer_bar_menu,
						'depth'      => 1,
						'menu_class' => 'thb-footer-bar-menu',
					)
				); }
			?>
		</div>
	</div>
</aside>
