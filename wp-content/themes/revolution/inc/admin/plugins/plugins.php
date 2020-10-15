<?php
if ( ! is_admin() ) {
	return;
}
require Thb_Theme_Admin::$thb_theme_directory . 'inc/admin/plugins/class-tgm-plugin-activation.php';

function thb_register_required_plugins() {
	$data = thb_theme_admin()->thb_check_for_update_plugins();

	if ( isset( $data->plugins ) ) {
		foreach ( $data->plugins as $plugin ) {
			switch ( $plugin->plugin_name ) {
				case 'WPBakery Visual Composer':
				case 'WPBakery Page Builder':
					$slug = 'js_composer';
					break;
			}
			$plugins[] = array(
				'name'               => $plugin->plugin_name,
				'slug'               => $slug,
				'source'             => $plugin->download_url,
				'force_activation'   => false,
				'force_deactivation' => false,
				'version'            => $plugin->version,
				'required'           => true,
				'external_url'       => '',
				'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/' . esc_attr( $slug ) . '.png',
			);
		}
	} else {
		$plugins[] = array(
			'name'               => 'WPBakery Visual Composer',
			'slug'               => 'js_composer',
			'source'             => get_template_directory() . '/inc/admin/plugins/plugins/codecanyon-242431-visual-composer-page-builder-for-wordpress-wordpress-plugin.zip',
			'version'            => '6.4.0',
			'force_activation'   => false,
			'force_deactivation' => false,
			'required'           => true,
			'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/js_composer.png',
		);
	}
	$plugins[] = array(
		'name'               => esc_html__( 'WooCommerce', 'revolution' ),
		'slug'               => 'woocommerce',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/woo.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'Revolution - Required Plugin', 'revolution' ),
		'slug'               => 'revolution-plugin',
		'source'             => get_template_directory() . '/inc/plugins/revolution-plugin.zip',
		'version'            => '1.2.4',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/revolution.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'MailChimp for WordPress', 'revolution' ),
		'slug'               => 'mailchimp-for-wp',
		'required'           => false,
		'force_activation'   => false,
		'force_deactivation' => false,
	);
	$plugins[] = array(
		'name'               => esc_html__( 'Contact Form 7', 'revolution' ),
		'slug'               => 'contact-form-7',
		'required'           => false,
		'force_activation'   => false,
		'force_deactivation' => false,
	);

	$config = array(
		'id'           => 'thb',
		'domain'       => 'revolution',
		'default_path' => '',
		'parent_slug'  => 'themes.php',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'return' => esc_html__( 'Return to Theme Plugins', 'revolution' ),
		),
	);
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'thb_register_required_plugins' );
