<?php
/**
 * Initialize the meta boxes.
 */
add_action( 'admin_init', 'thb_custom_meta_boxes' );

/**
 * Meta Boxes demo code.
 *
 * You can find all the available option types
 * in demo-theme-options.php.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */


function thb_custom_meta_boxes() {

	/**
	 * Create a custom meta boxes array that we pass to
	 * the OptionTree Meta Box API Class.
	 */
	$page_metabox = array(
		'id'       => 'page_settings',
		'title'    => esc_html__( 'Page Settings', 'revolution' ),
		'pages'    => apply_filters(
			'revolution_page_metabox_pages',
			array( 'page' )
		),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => apply_filters(
			'revolution_page_metabox_fields',
			array(
				array(
					'id'    => 'tab1',
					'label' => esc_html__( 'Header Settings', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label' => esc_html__( 'Header Padding', 'revolution' ),
					'id'    => 'enable_pagepadding',
					'type'  => 'on_off',
					'desc'  => esc_html__( 'When enabled, page will leave a space for the header on top.', 'revolution' ),
					'std'   => 'on',
				),
				array(
					'label' => esc_html__( 'Main Header Color', 'revolution' ),
					'id'    => 'header_color',
					'type'  => 'radio-image',
					'desc'  => esc_html__( 'Overrides the main header color of the theme. Changes the logo and menu colors', 'revolution' ),
					'std'   => 'dark-header',
				),
				array(
					'id'    => 'tab2',
					'label' => esc_html__( 'Page Settings', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label' => esc_html__( 'Page Background', 'revolution' ),
					'id'    => 'page_bg',
					'type'  => 'background',
					'desc'  => esc_html__( 'Background settings for the page.', 'revolution' ),
				),
				array(
					'label' => esc_html__( 'Disable Footer', 'revolution' ),
					'id'    => 'disable_footer',
					'type'  => 'on_off',
					'desc'  => esc_html__( 'When enabled, footer will not be shown on this page.', 'revolution' ),
					'std'   => 'off',
				),
				array(
					'id'    => 'tab3',
					'label' => esc_html__( 'Attributes', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label'    => esc_html__( 'Add Custom Attribute', 'revolution' ),
					'id'       => 'client_more',
					'type'     => 'list-item',
					'settings' => array(
						array(
							'label' => esc_html__( 'Value', 'revolution' ),
							'id'    => 'client_custom_value',
							'type'  => 'textarea_simple',
							'desc'  => esc_html__( 'The value of the attribute', 'revolution' ),
						),
						array(
							'label' => esc_html__( 'Link', 'revolution' ),
							'id'    => 'client_custom_link',
							'type'  => 'text',
							'desc'  => esc_html__( 'The above link value will be linked to this address.', 'revolution' ),
						),
					),
				),
				array(
					'id'    => 'tab4',
					'label' => esc_html__( 'Listing Settings', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'id'    => 'listing_text',
					'label' => esc_html__( 'About Listing Settings', 'revolution' ),
					'desc'  => esc_html__( 'These settings are used when this page is used inside a source option within Page Builder elements. (For ex: Portfolio Grid)', 'revolution' ),
					'type'  => 'textblock',
				),
				array(
					'label' => esc_html__( 'Main Gradient Color', 'revolution' ),
					'id'    => 'main_color',
					'type'  => 'gradient',
					'desc'  => esc_html__( 'Used for hover colors. You can choose the same colors if you want to have a solid background.', 'revolution' ),
				),
				array(
					'label' => esc_html__( 'Main Title Color', 'revolution' ),
					'id'    => 'main_color_title',
					'type'  => 'radio-image',
					'desc'  => esc_html__( 'Used for the text colors.', 'revolution' ),
					'std'   => 'dark-title',
				),
				array(
					'label' => esc_html__( 'Hover Image', 'revolution' ),
					'id'    => 'main_hover_image',
					'type'  => 'upload',
					'class' => 'ot-upload-attachment-id',
					'desc'  => esc_html__( 'This is used when the hover style is set to "Show Hover Image" inside VC element settings', 'revolution' ),
				),
				array(
					'id'    => 'tab5',
					'label' => esc_html__( 'Standard Page Layout', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'id'    => 'standard_text',
					'label' => esc_html__( 'About Standard Page Layout Settings', 'revolution' ),
					'desc'  => esc_html__( 'These settings are used when the page template is set to "Standard Page Layout"', 'revolution' ),
					'type'  => 'textblock',
				),
				array(
					'label' => esc_html__( 'Display Title?', 'revolution' ),
					'id'    => 'display_title',
					'type'  => 'on_off',
					'desc'  => esc_html__( 'Shows the page title by default.', 'revolution' ),
					'std'   => 'on',
				),
				array(
					'label' => esc_html__( 'Use Sidebar', 'revolution' ),
					'id'    => 'sidebar',
					'type'  => 'on_off',
					'desc'  => esc_html__( 'Display a sidebar on this page.', 'revolution' ),
					'std'   => 'off',
				),
				array(
					'label'     => esc_html__( 'Sidebar Position', 'revolution' ),
					'id'        => 'sidebar_position',
					'type'      => 'radio',
					'desc'      => esc_html__( 'By default, the sidebar sits on the right.', 'revolution' ),
					'choices'   => array(
						array(
							'label' => esc_html__( 'Right', 'revolution' ),
							'value' => 'right',
						),
						array(
							'label' => esc_html__( 'Left', 'revolution' ),
							'value' => 'left',
						),
					),
					'std'       => 'right',
					'condition' => 'sidebar:is(on)',
				),
				array(
					'label'     => esc_html__( 'Use Custom Sidebar', 'revolution' ),
					'id'        => 'custom_sidebar',
					'type'      => 'on_off',
					'desc'      => esc_html__( 'Select another sidebar to display on this page.', 'revolution' ),
					'std'       => 'off',
					'condition' => 'sidebar:is(on)',
				),
				array(
					'label'     => esc_html__( 'Select Custom Sidebar to use', 'revolution' ),
					'id'        => 'custom_sidebar_id',
					'type'      => 'sidebar_select',
					'desc'      => esc_html__( 'Select another sidebar to display on this page.', 'revolution' ),
					'std'       => 'off',
					'condition' => 'sidebar:is(on)',
				),
			)
		),
	);

	$portfolio_metabox = array(
		'id'       => 'portfolio_meta_style',
		'title'    => esc_html__( 'Portfolio Settings', 'revolution' ),
		'pages'    => apply_filters(
			'revolution_portfolio_metabox_pages',
			array(
				'portfolio',
			)
		),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => apply_filters(
			'revolution_portfolio_metabox_fields',
			array(
				array(
					'id'    => 'tab1',
					'label' => esc_html__( 'Listing Settings', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label' => esc_html__( 'Main Gradient Color', 'revolution' ),
					'id'    => 'main_color',
					'type'  => 'gradient',
					'desc'  => esc_html__( 'Used for hover colors. You can choose the same colors if you want to have a solid background.', 'revolution' ),
				),
				array(
					'label' => esc_html__( 'Main Title Color', 'revolution' ),
					'id'    => 'main_color_title',
					'type'  => 'radio-image',
					'desc'  => esc_html__( 'Used for hover colors and certain sliders. Also used for overall colors in Full Screen template.', 'revolution' ),
					'std'   => 'dark-title',
				),
				array(
					'label' => esc_html__( 'Hover Image', 'revolution' ),
					'id'    => 'main_hover_image',
					'type'  => 'upload',
					'class' => 'ot-upload-attachment-id',
					'desc'  => esc_html__( 'This is used when the hover style is set to "Show Hover Image" inside VC element settings', 'revolution' ),
				),
				array(
					'label' => esc_html__( 'Masonry Size', 'revolution' ),
					'id'    => 'masonry_size',
					'type'  => 'radio-image',
					'desc'  => esc_html__( 'This changes the size of the masonry when Portfolio Masonry element is being used.', 'revolution' ),
					'std'   => 'small',
				),
				array(
					'label'   => esc_html__( 'Listing Type', 'revolution' ),
					'id'      => 'main_listing_type',
					'type'    => 'radio',
					'desc'    => esc_html__( 'By default, portfolio image links to the portfolio page.', 'revolution' ),
					'choices' => array(
						array(
							'label' => esc_html__( 'Regular', 'revolution' ),
							'value' => 'regular',
						),
						array(
							'label' => esc_html__( 'Lightbox', 'revolution' ),
							'value' => 'lightbox',
						),
						array(
							'label' => esc_html__( 'Lightbox Video', 'revolution' ),
							'value' => 'lightbox-video',
						),
						array(
							'label' => esc_html__( 'Link', 'revolution' ),
							'value' => 'link',
						),
						array(
							'label' => esc_html__( 'Video', 'revolution' ),
							'value' => 'video',
						),
					),
					'std'     => 'regular',
				),
				array(
					'label'     => esc_html__( 'Enter Link', 'revolution' ),
					'id'        => 'main_listing_link',
					'type'      => 'text',
					'desc'      => esc_html__( 'Enter the url of the page you want the portfolio item to link to or Video URL if "Lightbox Video" is selected', 'revolution' ),
					'operator'  => 'or',
					'condition' => 'main_listing_type:is(link),main_listing_type:is(lightbox-video)',
				),
				array(
					'label'     => esc_html__( 'Set Video URL', 'revolution' ),
					'id'        => 'main_listing_video',
					'type'      => 'upload',
					'desc'      => esc_html__( 'You can set the video for this portfolio here. Only MP4 extension is allowed.', 'revolution' ),
					'condition' => 'main_listing_type:is(video)',
				),
				array(
					'id'    => 'tab2',
					'label' => esc_html__( 'Attributes', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label'    => esc_html__( 'Add Custom Attribute', 'revolution' ),
					'id'       => 'client_more',
					'type'     => 'list-item',
					'settings' => array(
						array(
							'label' => esc_html__( 'Value', 'revolution' ),
							'id'    => 'client_custom_value',
							'type'  => 'textarea_simple',
							'desc'  => esc_html__( 'The value of the attribute', 'revolution' ),
						),
						array(
							'label' => esc_html__( 'Link', 'revolution' ),
							'id'    => 'client_custom_link',
							'type'  => 'text',
							'desc'  => esc_html__( 'The above link value will be linked to this address.', 'revolution' ),
						),
					),
				),
				array(
					'id'    => 'tab4',
					'label' => esc_html__( 'Header Settings', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label' => esc_html__( 'Header Padding', 'revolution' ),
					'id'    => 'enable_pagepadding',
					'type'  => 'on_off',
					'desc'  => esc_html__( 'When enabled, page will leave a space for the header on top.', 'revolution' ),
					'std'   => 'on',
				),
				array(
					'label' => esc_html__( 'Main Header Color', 'revolution' ),
					'id'    => 'header_color',
					'type'  => 'radio-image',
					'desc'  => esc_html__( 'Overrides the main header color of the theme. Changes the logo and menu colors', 'revolution' ),
					'std'   => 'dark-header',
				),
				array(
					'id'    => 'tab5',
					'label' => esc_html__( 'Page Settings', 'revolution' ),
					'type'  => 'tab',
				),
				array(
					'label' => esc_html__( 'Page Background', 'revolution' ),
					'id'    => 'page_bg',
					'type'  => 'background',
					'desc'  => esc_html__( 'Background settings for the portfolio.', 'revolution' ),
				),
			)
		),
	);

	$post_metabox = array(
		'id'       => 'post_meta_style',
		'title'    => esc_html__( 'Post Settings', 'revolution' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'id'    => 'tab2',
				'label' => esc_html__( 'Standard/Image Format Settings', 'revolution' ),
				'type'  => 'tab',
			),
			array(
				'label' => esc_html__( 'Header Background', 'revolution' ),
				'id'    => 'post_header_bg',
				'type'  => 'background',
				'desc'  => esc_html__( 'You can change the background of the post header here. By default, it uses the featured image.', 'revolution' ),
			),
			array(
				'id'    => 'tab3',
				'label' => esc_html__( 'Gallery Format Settings', 'revolution' ),
				'type'  => 'tab',
			),
			array(
				'id'    => 'gallery_post_layout_text',
				'label' => esc_html__( 'About Gallery Settings', 'revolution' ),
				'desc'  => esc_html__( 'These settings are used for "Gallery" post format.', 'revolution' ),
				'type'  => 'textblock',
			),
			array(
				'label' => esc_html__( 'Gallery Photos', 'revolution' ),
				'id'    => 'post-gallery-photos',
				'type'  => 'gallery',
			),
			array(
				'id'    => 'tab4',
				'label' => esc_html__( 'Video Format Settings', 'revolution' ),
				'type'  => 'tab',
			),
			array(
				'id'    => 'video_post_layout_text',
				'label' => esc_html__( 'About Video Settings', 'revolution' ),
				'desc'  => esc_html__( 'These settings are used for "Video" post format.', 'revolution' ),
				'type'  => 'textblock',
			),
			array(
				'label' => esc_html__( 'Video URL', 'revolution' ),
				'id'    => 'post_video',
				'type'  => 'text',
				'desc'  => esc_html__( 'Video URL. You can find a list of websites you can embed here: <a href="http://codex.wordpress.org/Embeds">Wordpress Embeds</a>', 'revolution' ),
			),
		),
	);

	$product_meta_box = array(
		'id'       => 'product_settings',
		'title'    => esc_html__( 'Product Page Settings', 'revolution' ),
		'pages'    => array( 'product' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'id'    => 'tab2',
				'label' => esc_html__( 'Sizing Guide', 'revolution' ),
				'type'  => 'tab',
			),
			array(
				'label' => esc_html__( 'Enable Sizing Guide', 'revolution' ),
				'id'    => 'sizing_guide',
				'type'  => 'on_off',
				'desc'  => esc_html__( 'Enabling the sizing guide will add a link to the product page that will open the below content in a lightbox.', 'revolution' ),
				'std'   => 'off',
			),
			array(
				'label'     => esc_html__( 'Sizing Guide Content', 'revolution' ),
				'id'        => 'sizing_guide_content',
				'type'      => 'textarea',
				'desc'      => esc_html__( 'You can insert your sizin guide content here. Preferablly an image.', 'revolution' ),
				'rows'      => '5',
				'condition' => 'sizing_guide:is(on)',
			),
		),
	);
	/**
	 * Register our meta boxes using the
	 * ot_register_meta_box() function.
	 */
	ot_register_meta_box( $page_metabox );
	ot_register_meta_box( $portfolio_metabox );
	ot_register_meta_box( $post_metabox );
	ot_register_meta_box( $product_meta_box );
}
