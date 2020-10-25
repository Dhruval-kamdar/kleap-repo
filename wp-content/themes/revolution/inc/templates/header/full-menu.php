<?php $full_menu_hover_style = ot_get_option( 'full_menu_hover_style', 'thb-standard' ); ?>
<!-- Start Full Menu -->
<nav class="full-menu" id="full-menu">
	<?php
	if ( has_nav_menu( 'nav-menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'nav-menu',
				'depth'          => 4,
				'container'      => false,
				'menu_class'     => 'thb-full-menu ' . $full_menu_hover_style,
				'walker'         => new thb_fullmenu(),
			)
		);
	}
	?>
	<?php do_action( 'thb_social_links', ot_get_option( 'fullmenu_social_link' ), true, true ); ?>
</nav>
<!-- End Full Menu -->
