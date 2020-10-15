<?php
	$thb_id = get_queried_object_id();

	$fixed_header_color  = ot_get_option( 'fixed_header_color', 'dark-header' );
	$fixed_header_shadow = ot_get_option( 'fixed_header_shadow' );
	$header_color        = thb_get_header_color( $thb_id );

	$header_class[] = 'header style1';
	$header_class[] = $fixed_header_shadow;
	$header_class[] = $header_color;

?>
<!-- Start Header -->

<header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>" data-header-color="<?php echo esc_attr( $header_color ); ?>" data-fixed-header-color="<?php echo esc_attr( $fixed_header_color ); ?>">
	<div class="header_overlay_menu">
		<div class="header_overlay_padding">
			<div class="row">
				<div class="small-12 columns">
					<?php do_action( 'thb_logo', false ); ?>
					<div class="header_overlay_menu_holder">
						<?php
						if ( has_nav_menu( 'nav-menu' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'nav-menu',
									'depth'          => 2,
									'container'      => false,
									'link_after'     => '<span></span>',
									'menu_class'     => 'thb-header-menu',
									'walker'         => new thb_fullmenu(),
								)
							);
						}
						?>
						<?php if ( has_nav_menu( 'secondary-menu' ) ) { ?>
							<div class="thb-secondary-menu-container">
								<span class="thb-secondary-line"></span>
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'secondary-menu',
										'depth'          => 1,
										'container'      => false,
									)
								);
								?>
								<?php do_action( 'thb_social_links', ot_get_option( 'fullmenu_social_link' ), true, true ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row align-middle">
		<div class="small-12 columns">
			<?php do_action( 'thb_logo', true ); ?>
			<div class="style1-holder">
				<?php do_action( 'thb_mobile_toggle', false ); ?>
				<?php do_action( 'thb_secondary_area', true ); ?>
			</div>
		</div>

	</div>
</header>
<!-- End Header -->
