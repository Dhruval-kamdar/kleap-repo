<?php

function thb_filter_radio_images( $array, $field_id ) {

	if ( 'full_menu_dropdown_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/dropdown_styles/style1.jpg',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/dropdown_styles/style2.jpg',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/dropdown_styles/style3.jpg',
			),
		);
	}

	if ( 'subheader_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/subheader_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/subheader_styles/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/subheader_styles/style3.png',
			),
		);
	}

	if ( 'blog_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style1.jpg',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style2.jpg',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style3.jpg',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style 4', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style4.jpg',
			),
			array(
				'value' => 'style5',
				'label' => esc_html__( 'Style 5', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style5.jpg',
			),
			array(
				'value' => 'style6',
				'label' => esc_html__( 'Style 6', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style6.jpg',
			),
			array(
				'value' => 'style7',
				'label' => esc_html__( 'Style 7', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style7.jpg',
			),
			array(
				'value' => 'style8',
				'label' => esc_html__( 'Style 8', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style8.jpg',
			),
			array(
				'value' => 'style9',
				'label' => esc_html__( 'Style 9', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style9.jpg',
			),
			array(
				'value' => 'style10',
				'label' => esc_html__( 'Style 10', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/blog_styles/style10.jpg',
			),
		);
	}

	if ( 'header_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style3.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style 4', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style4.png',
			),
			array(
				'value' => 'style5',
				'label' => esc_html__( 'Style 5', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style5.png',
			),
			array(
				'value' => 'style6',
				'label' => esc_html__( 'Style 6', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style6.png',
			),
			array(
				'value' => 'style7',
				'label' => esc_html__( 'Style 7', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style7.png',
			),
			array(
				'value' => 'style8',
				'label' => esc_html__( 'Style 8', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style8.png',
			),
			array(
				'value' => 'style9',
				'label' => esc_html__( 'Style 9', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style9.png',
			),
			array(
				'value' => 'style10',
				'label' => esc_html__( 'Style 10', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style10.png',
			),
			array(
				'value' => 'style11',
				'label' => esc_html__( 'Style 11', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style11.png',
			),
			array(
				'value' => 'style12',
				'label' => esc_html__( 'Style 12', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header/style12.png',
			),
		);
	}
	if ( 'article_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/article_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/article_styles/style2.png',
			),
		);
	}
	if ( in_array( $field_id, array( 'header_color', 'fixed_header_color' ), true ) ) {
		$array = array(
			array(
				'value' => 'light-header',
				'label' => esc_html__( 'Light - White Background', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/light.png',
			),
			array(
				'value' => 'dark-header',
				'label' => esc_html__( 'Dark - Black Background', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/dark.png',
			),
		);
	}
	if ( 'main_color_title' === $field_id ) {
		$array = array(
			array(
				'value' => 'light-title',
				'label' => esc_html__( 'Light', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/light.png',
			),
			array(
				'value' => 'dark-title',
				'label' => esc_html__( 'Dark', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/dark.png',
			),
		);
	}
	if ( in_array( $field_id, array( 'subheader_color', 'mobile_menu_color', 'footer_color', 'full_menu_dropdown_color', 'thb_cookie_bar_color' ), true ) ) {
		$array = array(
			array(
				'value' => 'light',
				'label' => esc_html__( 'Light - White Background', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/light.png',
			),
			array(
				'value' => 'dark',
				'label' => esc_html__( 'Dark - Black Background', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/dark.png',
			),
		);
	}
	if ( 'mobile_menu_icon_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m3.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style 4', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m4.png',
			),
		);
	}
	if ( 'masonry_size' === $field_id ) {
		$array = array(
			array(
				'value' => 'large',
				'label' => esc_html__( 'large', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/masonry/large.png',
			),
			array(
				'value' => 'small',
				'label' => esc_html__( 'small', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/masonry/small.png',
			),
			array(
				'value' => 'wide',
				'label' => esc_html__( 'wide', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/masonry/wide.png',
			),
			array(
				'value' => 'tall',
				'label' => esc_html__( 'tall', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/masonry/tall.png',
			),
		);
	}

	if ( 'footer_bar_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/footer_bar/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/footer_bar/style2.png',
			),
		);
	}

	if ( 'subfooter_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/subfooter/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/subfooter/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/subfooter/style3.png',
			),
		);
	}

	if ( 'footer_columns' === $field_id ) {
		$array = array(
			array(
				'value' => 'onecolumns',
				'label' => esc_html__( 'Single Column', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/one-columns.png',
			),
			array(
				'value' => 'twocolumns',
				'label' => esc_html__( 'Two Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/two-columns.png',
			),
			array(
				'value' => 'threecolumns',
				'label' => esc_html__( 'Three Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/three-columns.png',
			),
			array(
				'value' => 'fourcolumns',
				'label' => esc_html__( 'Four Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/four-columns.png',
			),
			array(
				'value' => 'doubleleft',
				'label' => esc_html__( 'Double Left Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/doubleleft-columns.png',
			),
			array(
				'value' => 'doubleright',
				'label' => esc_html__( 'Double Right Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/doubleright-columns.png',
			),
			array(
				'value' => 'fivecolumns',
				'label' => esc_html__( 'Five Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/five-columns.png',
			),
			array(
				'value' => 'sixcolumns',
				'label' => esc_html__( 'Six Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/six-columns.png',
			),
			array(
				'value' => 'fivelargerightcolumns',
				'label' => esc_html__( 'Five Columns - Larger Right Columns', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/five-columns-large-right.png',
			),
		);
	}

	if ( 'shop_product_listing' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_styles/style2.png',
			),
		);
	}

	if ( 'shop_product_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style - 3', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style3.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style - 4', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style4.png',
			),
			array(
				'value' => 'style5',
				'label' => esc_html__( 'Style - 5', 'revolution' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style5.png',
			),
		);
	}

	return $array;

}
add_filter( 'ot_radio_images', 'thb_filter_radio_images', 10, 2 );

function thb_social_links_settings( $id ) {

	$settings = array(
		array(
			'label'   => 'Social Network',
			'id'      => 'social_network',
			'type'    => 'select',
			'desc'    => 'Select your social network',
			'choices' => array(
				array(
					'label' => 'Facebook',
					'value' => 'facebook',
				),
				array(
					'label' => 'Twitter',
					'value' => 'twitter',
				),
				array(
					'label' => 'Pinterest',
					'value' => 'pinterest',
				),
				array(
					'label' => 'Linkedin',
					'value' => 'linkedin',
				),
				array(
					'label' => 'Instagram',
					'value' => 'instagram',
				),
				array(
					'label' => 'Flickr',
					'value' => 'flickr',
				),
				array(
					'label' => 'VK',
					'value' => 'vk',
				),
				array(
					'label' => 'Tumblr',
					'value' => 'tumblr',
				),
				array(
					'label' => 'Spotify',
					'value' => 'spotify',
				),
				array(
					'label' => 'Youtube',
					'value' => 'youtube-play',
				),
				array(
					'label' => 'Vimeo',
					'value' => 'vimeo',
				),
				array(
					'label' => 'Dribbble',
					'value' => 'dribbble',
				),
				array(
					'label' => '500px',
					'value' => '500px',
				),
				array(
					'label' => 'Behance',
					'value' => 'behance',
				),
				array(
					'label' => 'Soundcloud',
					'value' => 'soundcloud',
				),
				array(
					'label' => 'Telegram',
					'value' => 'telegram',
				),
				array(
					'label' => 'IMDB',
					'value' => 'imdb',
				),
				array(
					'label' => 'Whatsapp',
					'value' => 'whatsapp',
				),
				array(
					'label' => 'Medium',
					'value' => 'medium',
				),
				array(
					'label' => 'E-mail',
					'value' => 'envelope-o',
				),
				array(
					'label' => 'Phone',
					'value' => 'phone',
				),
				array(
					'label' => 'Podcast',
					'value' => 'podcast',
				),
				array(
					'label' => 'Trip Advisor',
					'value' => 'tripadvisor',
				),
				array(
					'label' => 'Yelp',
					'value' => 'yelp',
				),
				array(
					'label' => 'Houzz',
					'value' => 'houzz',
				),
				array(
					'label' => 'WeChat',
					'value' => 'wechat',
				),
				array(
					'label' => 'Weibo',
					'value' => 'weibo',
				),
				array(
					'label' => 'RSS',
					'value' => 'rss',
				),
			),
		),
		array(
			'id'    => 'href',
			'label' => 'Link',
			/* translators: http:// */
			'desc'  => sprintf( __( 'Enter a link to the profile or page on the social website. Remember to add the %s part to the front of the link.', 'revolution' ), '<code>http://</code>' ),
			'type'  => 'text',
		),
	);

	return $settings;

}
add_filter( 'ot_social_links_settings', 'thb_social_links_settings' );
add_filter( 'ot_type_social_links_load_defaults', '__return_false' );

function thb_filter_options_name() {
	return wp_kses(
		__( '<a href="https://fuelthemes.net">Fuel Themes</a>', 'revolution' ),
		array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		)
	);
}
add_filter( 'ot_header_version_text', 'thb_filter_options_name', 10, 2 );

function thb_filter_page_title() {
	return wp_kses(
		__( 'Revolution Theme Options', 'revolution' ),
		array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		)
	);
}
add_filter( 'ot_theme_options_page_title', 'thb_filter_page_title', 10, 2 );

function thb_filter_menu_title() {
	return wp_kses(
		__( 'Revolution Options', 'revolution' ),
		array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		)
	);
}
add_filter( 'ot_theme_options_menu_title', 'thb_filter_menu_title', 10, 2 );

function thb_filter_upload_text() {
	return wp_kses(
		__( 'Send to Theme Options', 'revolution' ),
		array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		)
	);
}
add_filter( 'ot_upload_text', 'thb_filter_upload_text', 10, 2 );

function thb_header_list() {
	echo '<li class="theme_link"><a href="http://fuelthemes.ticksy.com/" target="_blank">Support Forum</a></li>';
}
add_filter( 'ot_header_list', 'thb_header_list' );

function thb_filter_ot_recognized_font_families( $array, $field_id ) {
	$array['helveticaneue'] = "'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif";
	ot_fetch_google_fonts( true, false );
	$ot_google_fonts = wp_list_pluck( get_theme_mod( 'ot_google_fonts', array() ), 'family' );
	$array           = array_merge( $array, $ot_google_fonts );

	if ( ot_get_option( 'typekit_id' ) ) {
		$typekit_fonts = trim( ot_get_option( 'typekit_fonts' ), ' ' );
		$typekit_fonts = explode( ',', $typekit_fonts );

		$array = array_merge( $array, $typekit_fonts );
	}
	$self_hosted_names = array();
	if ( ot_get_option( 'self_hosted_fonts' ) ) {
		$self_hosted_fonts = ot_get_option( 'self_hosted_fonts' );

		foreach ( $self_hosted_fonts as $font ) {
			$self_hosted_names[] = $font['font_name'];
		}

		$array = array_merge( $array, $self_hosted_names );
	}

	foreach ( $array as $font => $value ) {
		$thb_font_array[ $value ] = $value;
	}
	return $thb_font_array;
}
add_filter( 'ot_recognized_font_families', 'thb_filter_ot_recognized_font_families', 10, 2 );


function thb_filter_typography_fields( $array, $field_id ) {

	if ( in_array( $field_id, array( 'primary_font', 'secondary_font', 'fullmenu_font', 'em_font', 'mobilemenu_font', 'label_font' ), true ) ) {
		$array = array( 'font-family' );
	} elseif ( in_array( $field_id, array( 'button_font' ), true ) ) {
		$array = array( 'font-family', 'font-weight', 'letter-spacing' );
	} elseif ( in_array( $field_id, array( 'h1_type', 'h2_type', 'h3_type', 'h4_type', 'h5_type', 'h6_type' ), true ) ) {
		$array = array( 'font-family', 'font-size', 'font-style', 'font-weight', 'text-transform', 'line-height', 'letter-spacing' );
	} elseif ( in_array(
		$field_id,
		array( 'body_type', 'fullmenu_type', 'fullmenu_sub_type', 'mobilemenu_type', 'mobilemenu_sub_type', 'mobilemenu_secondary_type', 'mobilemenu_footer_type', 'mobilemenu_social_type', 'widget_title_type', 'footer_type', 'secondarymenu_text_font_1', 'secondarymenu_text_font_2', 'subfooter_fullmenu_type', 'shop_product_title', 'shop_product_detail_title', 'shop_product_detail_excerpt' ),
		true
	) ) {
		$array = array( 'font-size', 'font-style', 'font-weight', 'text-transform', 'line-height', 'letter-spacing' );
	} elseif ( in_array( $field_id, array( 'fullmenu_social_type', 'subfooter_social_type' ), true ) ) {
		$array = array( 'font-size' );
	}
	return $array;

}

add_filter( 'ot_recognized_typography_fields', 'thb_filter_typography_fields', 10, 2 );

function thb_filter_spacing_fields( $array, $field_id ) {

	if ( in_array( $field_id, array( 'header_padding', 'header_padding_fixed', 'header_padding_mobile', 'footer_padding', 'subfooter_padding', 'footerbar_padding' ), true ) ) {
		$array = array( 'top', 'bottom' );
	}
	return $array;

}

add_filter( 'ot_recognized_spacing_fields', 'thb_filter_spacing_fields', 10, 2 );

function thb_filter_measurement_unit_types( $array, $field_id ) {
	if ( in_array( $field_id, array( 'site_borders_width' ), true ) ) {
		$array = array(
			'px' => 'px',
			'em' => 'em',
			'pt' => 'pt',
		);
	}
	if ( in_array( $field_id, array( 'thb_grid_size' ), true ) ) {
		$array = array(
			'px'  => 'px',
			'em'  => 'em',
			'rem' => 'rem',
			'%'   => '%',
		);
	}
	return $array;
}
add_filter( 'ot_measurement_unit_types', 'thb_filter_measurement_unit_types', 10, 2 );

function thb_ot_line_height_unit_type( $array, $field_id ) {
	return 'em';
}
add_filter( 'ot_line_height_unit_type', 'thb_ot_line_height_unit_type', 10, 2 );

function thb_ot_line_height_high_range( $array, $field_id ) {
	return 3;
}
add_filter( 'ot_line_height_high_range', 'thb_ot_line_height_high_range', 10, 2 );

function thb_ot_line_height_range_interval( $array, $field_id ) {
	return 0.05;
}
add_filter( 'ot_line_height_range_interval', 'thb_ot_line_height_range_interval', 10, 2 );

function thb_ot_letter_spacing_high_range( $array, $field_id ) {
	return '0.2';
}
add_filter( 'ot_letter_spacing_high_range', 'thb_ot_letter_spacing_high_range', 10, 2 );

function thb_ot_letter_spacing_low_range( $array, $field_id ) {
	return '-0.2';
}
add_filter( 'ot_letter_spacing_low_range', 'thb_ot_letter_spacing_low_range', 10, 2 );

function thb_filter_ot_recognized_link_color_fields( $array, $field_id ) {
	$array = array(
		'link'  => esc_html_x( 'Standard', 'color picker', 'revolution' ),
		'hover' => esc_html_x( 'Hover', 'color picker', 'revolution' ),
	);
	return $array;
}
add_filter( 'ot_recognized_link_color_fields', 'thb_filter_ot_recognized_link_color_fields', 10, 2 );

function thb_clear_font_cache() {
	$clear = filter_input( INPUT_GET, 'thb_clear_font_cache', FILTER_VALIDATE_BOOLEAN );
	if ( $clear && current_user_can( 'manage_options' ) ) {
		delete_transient( 'ot_google_fonts_cache' );
	}

}
add_action( 'admin_init', 'thb_clear_font_cache' );
