<?php
add_filter( 'ot_show_pages', '__return_true' );
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_theme_mode', '__return_true' );
add_filter( 'ot_override_forced_textarea_simple', '__return_true' );
add_filter( 'ot_show_settings_import', '__return_false' );
add_filter( 'ot_show_options_ui', '__return_false' );
add_filter(
	'ot_google_fonts_api_key',
	function() {
		return 'AIzaSyA_sfIukXUl1YF8tpjXNGOvpYKNDnFKwFM';
	}
);
require get_template_directory() . '/inc/admin/option-tree/ot-radioimages.php';
require get_template_directory() . '/inc/admin/option-tree/ot-metaboxes.php';
require get_template_directory() . '/inc/admin/option-tree/ot-themeoptions.php';
require get_template_directory() . '/inc/admin/option-tree/ot-functions.php';
if ( ! class_exists( 'OT_Loader' ) ) {
	require get_template_directory() . '/inc/admin/option-tree/admin/ot-loader.php';
}

// Transient option gateway
$option_tree_all = get_option( 'option_tree' );
if ( $option_tree_all ) {
	if ( ! isset( $option_tree_all['header_button_data'] ) ) {
		$header_button_opt_pairs = array(
			'header_action_button_link' => 'url',
			'header_button_text'        => 'text',
			'header_button_target'      => 'target',
			'header_button_size'        => 'thb-button_styling__button_size',
			'header_button_style'       => 'thb-button_styling__button_style',
			'header_button_radius'      => 'thb-button_styling__button_border_radius',
			'header_button_color'       => 'thb-button_styling__button_color',
		);

		$header_button_defaults = array(
			'url'                                      => '',
			'text'                                     => '',
			'target'                                   => '_self',
			'thb-button_styling__button_size'          => 'small',
			'thb-button_styling__button_style'         => 'style1',
			'thb-button_styling__button_border_radius' => 'no-radius',
			'thb-button_styling__button_color'         => 'white',
		);
		foreach ( $header_button_opt_pairs as $old => $new ) {
			if ( isset( $option_tree_all[ $old ] ) ) {
				$header_button_defaults[ $new ] = $option_tree_all[ $old ];
			}
		}
		$option_tree_all['header_button_data'] = wp_json_encode( $header_button_defaults );
		update_option( 'option_tree', $option_tree_all );
	}
}
