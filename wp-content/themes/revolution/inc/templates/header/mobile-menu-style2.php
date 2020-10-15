<?php
	$logo               = ot_get_option( 'logo', Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/logo.png' );
	$logo_light         = ot_get_option( 'logo_light', Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/logo-light.png' );
	$header_style       = ot_get_option( 'header_style', 'style1' );
	$mobile_menu_footer = ot_get_option( 'mobile_menu_footer' );

	$class[] = 'style2';
	$class[] = ot_get_option( 'mobile_menu_color', 'light' );
?>
<!-- Start Content Click Capture -->
<div class="click-capture"></div>
<!-- End Content Click Capture -->
<!-- Start Mobile Menu -->
<nav id="mobile-menu" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" data-behaviour="<?php echo esc_attr( ot_get_option( 'submenu_behaviour', 'thb-submenu' ) ); ?>">
	<a class="thb-mobile-close"><div><span></span><span></span></div></a>
	<div class="menubg-placeholder"></div>
	<div class="custom_scroll" id="menu-scroll">
			<div class="mobile-menu-top">
				<?php do_action( 'thb_language_switcher_mobile' ); ?>
				<?php do_action( 'thb_mobile_search' ); ?>
				<?php get_template_part( 'inc/templates/header/mobile-menu' ); ?>
			</div>
			<div class="mobile-menu-bottom">
				<?php get_template_part( 'inc/templates/header/mobile-menu-secondary' ); ?>
				<?php if ( $mobile_menu_footer ) { ?>
					<div class="menu-footer">
						<?php echo do_shortcode( $mobile_menu_footer ); ?>
					</div>
				<?php } ?>
				<?php do_action( 'thb_social_links', ot_get_option( 'mobile_menu_social_link' ), false, true ); ?>
			</div>
		</div>
</nav>
<!-- End Mobile Menu -->
