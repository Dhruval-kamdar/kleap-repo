<?php
function thb_register_sidebars() {
	if ( thb_wc_supported() ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop Sidebar', 'revolution' ),
				'id'            => 'thb-shop-filters',
				'description'   => esc_html__( 'Sidebar used for filters on the Shop page', 'revolution' ),
				'before_widget' => '<div id="%1$s" class="widget woo cf %2$s">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<h6>',
				'after_title'   => '</h6><div class="widget_content">',
			)
		);
	}
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'revolution' ),
			'id'            => 'blog',
			'description'   => esc_html__( 'Blog', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Article Sidebar', 'revolution' ),
			'id'            => 'single',
			'description'   => esc_html__( 'Articles', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Page Sidebar', 'revolution' ),
			'id'            => 'page',
			'description'   => esc_html__( 'Sidebar for pages with "Standard Page Layout" template', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 1', 'revolution' ),
			'id'            => 'footer1',
			'description'   => esc_html__( 'Footer - first column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 2', 'revolution' ),
			'id'            => 'footer2',
			'description'   => esc_html__( 'Footer - second column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 3', 'revolution' ),
			'id'            => 'footer3',
			'description'   => esc_html__( 'Footer - third column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 4', 'revolution' ),
			'id'            => 'footer4',
			'description'   => esc_html__( 'Footer - forth column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 5', 'revolution' ),
			'id'            => 'footer5',
			'description'   => esc_html__( 'Footer - fifth column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 6', 'revolution' ),
			'id'            => 'footer6',
			'description'   => esc_html__( 'Footer - sixth column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Header - Style 8 - Column 1', 'revolution' ),
			'id'            => 'header-style8-1',
			'description'   => esc_html__( 'Header - Style 8 - first column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Header - Style 8 - Column 2', 'revolution' ),
			'id'            => 'header-style8-2',
			'description'   => esc_html__( 'Header - Style 8 - second column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Header - Style 8 - Column 3', 'revolution' ),
			'id'            => 'header-style8-3',
			'description'   => esc_html__( 'Header - Style 8 - third column', 'revolution' ),
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

}
add_action( 'widgets_init', 'thb_register_sidebars' );

function thb_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'thb_remove_recent_comments_style' );

/** If no title then add widget content wrapper to before widget. */
function thb_check_sidebar_params( $params ) {
	global $wp_registered_widgets;

	if ( 'thb-shop-filters' === $params[0]['id'] ) {
		$settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
		$settings        = $settings_getter->get_settings();
		$settings        = $settings[ $params[1]['number'] ];

		if ( isset( $settings['title'] ) && empty( $settings['title'] ) ) {
			$params[0]['after_widget'] = '</div>';
		}
	}
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'thb_check_sidebar_params' );
