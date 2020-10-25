<?php
	$footer_bar_menu = ot_get_option( 'footer_bar_menu' );
?>
<div class="footer-bar-container">
	<div class="row">
		<div class="small-12 columns">
			<aside class="footer_bar style2">
				<div class="row align-middle">
					<div class="small-12 medium-6 columns footer-bar-content">
						<?php echo wp_kses_post( ot_get_option( 'footer_bar_content' ) ); ?>
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
		</div>
	</div>
</div>
