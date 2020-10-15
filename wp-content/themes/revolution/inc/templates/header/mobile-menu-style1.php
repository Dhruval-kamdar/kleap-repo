<?php
	$logo               = ot_get_option( 'logo', Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/logo.png' );
	$logo_light         = ot_get_option( 'logo_light', Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/logo-light.png' );
	$header_style       = ot_get_option( 'header_style', 'style1' );
	$mobile_menu_footer = ot_get_option( 'mobile_menu_footer' );

	$class[] = 'style1';
	$class[] = ot_get_option( 'mobile_menu_color', 'light' );
	$class[] = 'style8' === $header_style ? 'header-style8-menu custom_scroll' : '';
?>
<!-- Start Content Click Capture -->
<div class="click-capture"></div>
<!-- End Content Click Capture -->
<!-- Start Mobile Menu -->
<nav id="mobile-menu" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" data-behaviour="<?php echo esc_attr( ot_get_option( 'submenu_behaviour', 'thb-submenu' ) ); ?>">
	<a class="thb-mobile-close"><div><span></span><span></span></div></a>
	<?php if ( 'style8' === $header_style ) { ?>
		<div class="header-style-8-content">
			<?php do_action( 'thb_logo', false ); ?>
			<div class="row">
				<div class="small-12 medium-6 large-3 columns">
					<?php dynamic_sidebar( 'header-style8-1' ); ?>
				</div>
				<div class="small-12 medium-6 large-3 columns">
					<?php dynamic_sidebar( 'header-style8-2' ); ?>
				</div>
				<div class="small-12 medium-6 large-3 columns">
			<?php dynamic_sidebar( 'header-style8-3' ); ?>
				</div>
			</div>
			<div class="mobile-menu-bottom">
			<?php if ( $mobile_menu_footer ) { ?>
					<div class="menu-footer">
						<?php echo do_shortcode( $mobile_menu_footer ); ?>
					</div>
				<?php } ?>
				<?php do_action( 'thb_social_links', ot_get_option( 'mobile_menu_social_link' ) ); ?>
			</div>
		</div>
	<?php } else { ?>
		<div class="menubg-placeholder"></div>
		<div class="custom_scroll" id="menu-scroll">
			<div class="mobile-menu-top">
				<?php do_action( 'thb_language_switcher_mobile' ); ?>
				<?php do_action( 'thb_mobile_search' ); ?>
				<?php get_template_part( 'inc/templates/header/mobile-menu' ); ?>
				<?php
				if ( 'on' === ot_get_option( 'header_button_mobile', 'off' ) ) {
					do_action( 'thb_header_button' ); }
				?>
				<?php get_template_part( 'inc/templates/header/mobile-menu-secondary' ); ?>
			</div>
			<div class="mobile-menu-bottom">
				<?php if ( ot_get_option( 'mobile_menu_footer' ) === $mobile_menu_footer ) { ?>
					<div class="menu-footer">
						<?php echo do_shortcode( $mobile_menu_footer ); ?>
					</div>
				<?php } ?>
				<?php do_action( 'thb_social_links', ot_get_option( 'mobile_menu_social_link' ), false, true ); ?>
			</div>
		</div>
		<?php
		if ( 'style7' === $header_style ) {
			do_action( 'thb_mobile_toggle', false );
		}
		?>
	<?php } ?>
</nav>
<!-- End Mobile Menu -->
