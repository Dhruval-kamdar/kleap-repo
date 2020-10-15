<?php
if ( has_nav_menu( 'secondary-menu' ) ) {  ?>
	<span class="thb-secondary-bar"></span>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'secondary-menu',
			'depth'          => 1,
			'container'      => false,
			'menu_class'     => 'thb-secondary-menu',
		)
	);
}
