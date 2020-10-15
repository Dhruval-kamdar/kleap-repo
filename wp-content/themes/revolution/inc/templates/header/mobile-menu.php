<?php
if ( has_nav_menu( 'nav-menu' ) ) {
	wp_nav_menu(
		array(
			'theme_location' => 'nav-menu',
			'depth'          => 4,
			'container'      => false,
			'menu_class'     => 'thb-mobile-menu',
			'walker'         => new thb_mobileDropdown(),
		)
	);
}

