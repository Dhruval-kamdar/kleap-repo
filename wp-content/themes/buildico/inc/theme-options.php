<?php
/**
* Theme Options
*
* @package buildico
*/

// Theme Option Init
function buildico_theme_option_init() {
    $settings = array(
        'menu_title'      => esc_html__( 'Buildico Options', 'buildico' ),
        'menu_type'       => 'menu',
        'menu_slug'       => 'buildico-options',
        'framework_title' => esc_html__( 'Buildico Options', 'buildico' ),
        'menu_icon'       => 'dashicons-admin-home',
        'menu_position'   => 4,
        'ajax_save'       => true,
        'show_reset_all'  => true
    );

    $options = buildico_cs_theme_options();
    new CSFramework( $settings, $options );
}
add_action( 'init', 'buildico_theme_option_init' );

function buildico_cs_theme_options(){
    $options = array();

    // ----------------------------------------
    // General  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'general',
        'title'       => esc_html__( 'General', 'buildico' ),
        'icon'        => 'fa fa-cog',
        'sections'	=> array(

            // Post Date Link
            array(
                'name'      => 'general_blog',
                'title'     =>  esc_html__( 'Blog', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Blog', 'buildico')
                    ),

                    array(
                        'id'      => 'datelink_enable',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Post Date Link Enable', 'buildico' ),
                        'desc'    => esc_html__( 'Post Date Link Enable. Default is enable.', 'buildico' ),
                        'default' => true
                    ),

                ), // end: fields

            ), // end: breadcrumbs

            // breadcrumbs
            array(
                'name'      => 'general_breadcrumbs',
                'title'     =>  esc_html__( 'Breadcrumbs', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Breadcrumbs', 'buildico')
                    ),

                    array(
                        'id'      => 'breadcrumbs_enable',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Breadcrumbs', 'buildico' ),
                        'desc'    => esc_html__( 'Enable or Disable Breadcrumbs.', 'buildico' ),
                        'default' => true
                    ),

                ), // end: fields

            ), // end: breadcrumbs

            // Scroll Top
            array(
                'name'      => 'general_scrolltop',
                'title'     =>  esc_html__( 'Scroll Top', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Scroll Top', 'buildico')
                    ),

                    array(
                        'id'      => 'gototop_btn',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Scroll To Top', 'buildico' ),
                        'desc'    => esc_html__( 'Enable scroll to top of your site.', 'buildico' ),
                        'default' => true
                    ),

                    array(
                        'id'      => 'gototop_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false,
                        'dependency'    => array(
                            'gototop_btn', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'gototop_btn_bg',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Scroll To Top Background Color', 'buildico' ),
                        'default' => '#fab702',
                        'rgba'    => true,
                        'desc'    => esc_html__( 'You can control scroll to top background color from this color picker. This color picker support RGBA color!', 'buildico' ),
                        'dependency'    => array(
                            'gototop_btn|gototop_custom_style', '==|==', 'true|true'
                        )
                    ),

                    array(
                        'id'      => 'gototop_btn_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Scroll To Top Icon Color', 'buildico' ),
                        'default' => '#fff',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can control scroll to top icon color from this color picker. This color picker support RGBA color!', 'buildico' ),
                        'dependency'    => array(
                            'gototop_btn|gototop_custom_style', '==|==', 'true|true'
                        )
                    ),

                ), // end: fields

            ), // end: Scroll Top

            // preloader
            array(
                'name'      => 'general_preloader',
                'title'     =>  esc_html__( 'Preloader', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Preloader', 'buildico')
                    ),

                    array(
                        'id'         => 'preloader_control',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Preloader Control', 'buildico' ),
                        'options'    => array(
                            'disable'    => esc_html__( 'Disable', 'buildico' ),
                            'show_all'   => esc_html__( 'Show In All Page.', 'buildico' ),
                            'only_home'  => esc_html__( 'Show In Only Homepage', 'buildico' )
                        ),
                        'default' => 'show_all',
                        'desc'    => esc_html__( 'You can select from where preloader will show.', 'buildico' )
                    ),

                    array(
                        'id'         => 'preloader_select',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Preloader Options', 'buildico' ),
                        'options'    => array(
                            'default'   => esc_html__( 'Default', 'buildico' ),
                            'custom_preloader' => esc_html__( 'Upload Your Own Preloader.', 'buildico' )
                        ),
                        'default'    => 'default',
                        'desc'    => esc_html__( 'You can use your own preloader by selecting <b>Upload Your Own Preloader</b> Options. Otherwise it will show default one.', 'buildico' ),
                        'dependency'    => array(
                            'preloader_control', '!=', 'disable'
                        )
                    ),

                    array(
                        'id'         => 'preloader_style',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Preloader Style', 'buildico' ),
                        'options'    => array(
                            'style-1'   => esc_html__( 'Style 1', 'buildico' ),
                            'style-2' => esc_html__( 'Style 2', 'buildico' )
                        ),
                        'default'    => 'style-1',
                        'dependency'    => array(
                            'preloader_control|preloader_select', '!=|==', 'disable|default'
                        )
                    ),

                    array(
                        'id'      => 'preloader_img',
                        'type'    => 'image',
                        'title'   => esc_html__( 'Upload Preloader', 'buildico' ),
                        'desc'    => esc_html__( 'Upload your own preloader using this uploader.', 'buildico' ),
                        'dependency'    => array(
                            'preloader_control|preloader_select', '!=|==', 'disable|custom_preloader'
                        )
                    ),

                    array(
                        'id'      => 'preloader_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false,
                        'dependency'    => array(
                            'preloader_control', '!=', 'disable'
                        )
                    ),

                    array(
                        'id'      => 'preloader_bg',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Background Color', 'buildico' ),
                        'default' => '#232427',
                        'rgba'    => true,
                        'desc'    => esc_html__( 'You can control preloader background color from this color picker. This color picker support RGBA color!', 'buildico' ),
                        'dependency'    => array(
                            'preloader_control|preloader_custom_style|preloader_custom_style', '!=|==|==', 'disable|true|true'
                        )
                    ),

                    array(
                        'id'      => 'preloader_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Preloader Color', 'buildico' ),
                        'default' => '#fab702',
                        'rgba'    => true,
                        'desc'    => esc_html__( 'You can control preloader background color from this color picker. This color picker support RGBA color!', 'buildico' ),
                        'dependency'    => array(
                            'preloader_control|preloader_select|preloader_style|preloader_custom_style', '!=|!=|!=|==', 'disable|custom_preloader|style-1|true'
                        )
                    ),

                ), // end: fields

            ), // end: preloader

        )
    );

    // ----------------------------------------
    // Header Option  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'header_options',
        'title'       => esc_html__( 'Header', 'buildico' ),
        'icon'        => 'fa fa-align-justify',
        'sections' => array(
            // General
            array(
                'name'      => 'header_general',
                'title'     =>  esc_html__( 'General', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('General', 'buildico')
                    ),

                    array(
                        'id'      => 'select_header',
                        'type'    => 'select',
                        'title'   => esc_html__( 'Select Header Style', 'buildico' ),
                        'options' => array(
                            'header-1'     => esc_html__( 'Header 1', 'buildico' ),
                            'header-2'     => esc_html__( 'Header 2', 'buildico' )
                        ),
                        'desc'    => esc_html__( 'Select header style from here.', 'buildico' )
                    ),

                    array(
                        'id'      => 'transparent_header',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Transparent Header', 'buildico' ),
                        'desc'    => esc_html__( 'Enable transparent header.', 'buildico' ),
                        'default' => false,
                        'dependency' => array( 'select_header', '==', 'header-1' )
                    ),

                    array(
                        'id'    => 'header_bg_color',
                        'type'  => 'select',
                        'title' => esc_html__( 'Header Color', 'buildico' ),
                        'options'    => array(
                            'dark-header' => esc_html__( 'Dark Header', 'buildico' ),
                            'light-header' => esc_html__( 'Light Header', 'buildico' ),
                        ),
                        'default'  => 'light-header',
                        'desc' => esc_html__( 'You can choose light and dark header from here.', 'buildico' )
                    ),

                    array(
                        'id'      => 'fixed_header',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Fixed Header', 'buildico' ),
                        'desc'    => esc_html__( 'Enable fixed header by using this switcher.', 'buildico' ),
                        'default' => true
                    ),

                    array(
                        'id'    => 'transparent_header_text',
                        'type'  => 'select',
                        'title' => esc_html__( 'Header Text', 'buildico' ),
                        'options'    => array(
                            'dark' => esc_html__( 'Dark', 'buildico' ),
                            'light' => esc_html__( 'Light', 'buildico' ),
                        ),
                        'default'  => 'light',
                        'desc' => esc_html__( 'Custom settings for make header transparent text.', 'buildico' ),
                        'dependency' => array( 'transparent_header', '==', 'true' )
                    ),

                ), // end: fields

            ), // end: General

            // Top Header
            array(
                'name'      => 'header_top',
                'title'     =>  esc_html__( 'Top Header', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Top Header', 'buildico')
                    ),

                    array(
                        'id'      => 'hidetop_bar',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Header Top', 'buildico' ),
                        'desc'    => esc_html__( 'You can hide header top by using this option. Default is true (Enable).', 'buildico' ),
                        'default' => true
                    ),

                    array(
                        'id'        => 'header_one_top',
                        'type'      => 'fieldset',
                        'title'     => __('Header One', 'buildico'),
                        'desc'      => __('This top header info only showing if you select header one.', 'buildico'),
                        'un_array'  => true,
                        'dependency'      => array(
                            'hidetop_bar', '==', 'true'
                        ),
                        'fields'    => array(

                            array(
                                'type'    => 'subheading',
                                'content' => __('Phone Number', 'buildico')
                            ),

                            array(
                                'id'    => 'b_phone_label',
                                'type'  => 'text',
                                'title' => __('Label', 'buildico'),
                                'default' => __('Phone', 'buildico')
                            ),

                            array(
                                'id'    => 'b_phone_number',
                                'type'  => 'text',
                                'title' => __('Phone Number', 'buildico'),
                                'default' => __('+123 456 7890', 'buildico')
                            ),

                            array(
                                'type'    => 'subheading',
                                'content' => __('Email', 'buildico')
                            ),

                            array(
                                'id'    => 'b_email_label',
                                'type'  => 'text',
                                'title' => __('Label', 'buildico'),
                                'default' => __('Email', 'buildico')
                            ),

                            array(
                                'id'    => 'b_email_addr',
                                'type'  => 'text',
                                'title' => __('Email Address', 'buildico'),
                                'default' => get_option('admin_email')
                            ),

                        ),
                    ),

                    array(
                        'id'        => 'header_two_top',
                        'type'      => 'fieldset',
                        'title'     => __('Header Two', 'buildico'),
                        'desc'      => __('This top header info only showing if you select header two.', 'buildico'),
                        'dependency'      => array(
                            'hidetop_bar', '==', 'true'
                        ),
                        'fields'    => array(

                            array(
                                'type'    => 'subheading',
                                'content' => __('Content 1', 'buildico')
                            ),

                            array(
                                'id'    => 'b_item1_label',
                                'type'  => 'text',
                                'title' => __('Label', 'buildico'),
                                'default' => __('Mon – Sat', 'buildico')
                            ),

                            array(
                                'id'    => 'b_item1_value',
                                'type'  => 'text',
                                'title' => __('Value', 'buildico'),
                                'default' => __('7.00 – 18.00', 'buildico')
                            ),

                            array(
                                'type'    => 'subheading',
                                'content' => __('Content 2', 'buildico')
                            ),

                            array(
                                'id'    => 'b_item2_label',
                                'type'  => 'text',
                                'title' => __('Label', 'buildico'),
                                'default' => __('Sunday', 'buildico')
                            ),

                            array(
                                'id'    => 'b_item2_value',
                                'type'  => 'text',
                                'title' => __('Value', 'buildico'),
                                'default' => __('Closed', 'buildico')
                            ),

                            array(
                                'type'    => 'subheading',
                                'content' => __('Content 3', 'buildico')
                            ),

                            array(
                                'id'    => 'b_item3_label',
                                'type'  => 'text',
                                'title' => __('Label', 'buildico'),
                                'default' => __('Emergency', 'buildico')
                            ),

                            array(
                                'id'    => 'b_item3_value',
                                'type'  => 'text',
                                'title' => __('Value', 'buildico'),
                                'default' => __('24h / 7days', 'buildico')
                            ),

                        ),
                    ),

                ), // end: fields

            ), // end: Top Header

            // Middle Header
            array(
                'name'      => 'header_middle',
                'title'     =>  esc_html__( 'Middle Header', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Middle Header', 'buildico')
                    ),

                    array(
                        'id'        => 'middle_header_content',
                        'type'      => 'fieldset',
                        'title'     => __('Middle Header Content', 'buildico'),
                        'desc'      => __('This middle header content only showing if you select header two.', 'buildico'),
                        'fields'    => array(

                            array(
                                'type'    => 'subheading',
                                'content' => __('Quote Info', 'buildico')
                            ),

                            array(
                                'id'      => 'bm_quote_icon',
                                'type'    => 'icon',
                                'title'   => __('Choose a Icon', 'buildico'),
                            ),

                            array(
                                'id'    => 'bm_sub_head1',
                                'type'  => 'text',
                                'title' => __('Sub Heading', 'buildico'),
                                'default' => __('Want an approximate price?', 'buildico')
                            ),

                            array(
                                'id'    => 'bm_heading1',
                                'type'  => 'text',
                                'title' => __('Heading', 'buildico'),
                                'default' => __('GE A FREE QUOTE', 'buildico')
                            ),

                            array(
                                'id'    => 'bm_heading_link1',
                                'type'  => 'text',
                                'title' => __('Link', 'buildico'),
                                'default' => '#'
                            ),

                            array(
                                'type'    => 'subheading',
                                'content' => __('Call Us', 'buildico')
                            ),

                            array(
                                'id'      => 'bm_phone_icon',
                                'type'    => 'icon',
                                'title'   => __('Choose a Icon', 'buildico'),
                            ),

                            array(
                                'id'    => 'bm_phone_label',
                                'type'  => 'text',
                                'title' => __('Heading', 'buildico'),
                                'default' => __('Call us now', 'buildico')
                            ),

                            array(
                                'id'    => 'bm_phone_number',
                                'type'  => 'text',
                                'title' => __('Phone Number', 'buildico'),
                                'default' => __('+01 234 56789', 'buildico')
                            ),
                        ),
                    ),

                ), // end: fields

            ), // end: Middle Header

            // Header Search Options
            array(
                'name'      => 'header_search',
                'title'     =>  esc_html__( 'Search Icon', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Search Icon', 'buildico')
                    ),

                    array(
                        'id'      => 'hideheader_search_icon',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Search Icon', 'buildico' ),
                        'desc'    => esc_html__( 'You can hide search icon by using this option. Default is true (Enable).', 'buildico' ),
                        'default' => true
                    ),

                ), // end: fields
            ), // end: Menu Button

            // Logo Settings
            array(
                'name'      => 'logo_settings',
                'title'     =>  esc_html__( 'Logo Settings', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Logo Settings', 'buildico')
                    ),

                    array(
                        'type'    => 'content',
                        'content' => esc_html__( 'You can set your own logo cropping size from here. Remember after setting your sizes you need to re-upload your logo.', 'buildico' ),
                    ),

                    array(
                        'id'      => 'crop_width',
                        'type'    => 'number',
                        'title'   => esc_html__( 'Width', 'buildico' ),
                        'desc'    => esc_html__( 'Set your own logo cropping custom width.', 'buildico' ),
                        'default' => '180',
                    ),

                    array(
                        'id'      => 'crop_height',
                        'type'    => 'number',
                        'title'   => esc_html__( 'Height', 'buildico' ),
                        'desc'    => esc_html__( 'Set your own logo cropping custom height.', 'buildico' ),
                        'default' => '40',
                    ),

                ), // end: fields

            ), // end: Logo Settings

        )
    );

    // ----------------------------------------
    // Theme Layout  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'theme_layout',
        'title'       => esc_html__( 'Theme Layout', 'buildico' ),
        'icon'        => 'fa fa-th-large',

        // begin: fields
        'fields'    => array(

            array(
                'type'    => 'heading',
                'content' => __('Theme Layout', 'buildico')
            ),

            array(
                'id'        => 'select_theme_layout',
                'type'      => 'image_select',
                'title'     => esc_html__( 'Select Theme Layout', 'buildico' ),
                'options'   => array(
                    'container' => get_template_directory_uri() .'/assets/img/fixed-width-container.jpg',
                    'container-fluid' => get_template_directory_uri() .'/assets/img/full-width-container.jpg',
                ),
                'default'   => 'container',
                'desc'      => esc_html__( 'Slect theme layout from here. Default is Fixed Width Container. Another is Full Width Container.', 'buildico' )
            ),

            array(
                'id'        => 'sidebar_position',
                'type'      => 'image_select',
                'title'     => esc_html__( 'Sidebar Positioning', 'buildico' ),
                'options'   => array(
                    'right'   => get_template_directory_uri() .'/assets/img/sidebar-right.jpg',
                    'left'    => get_template_directory_uri() .'/assets/img/sidebar-left.jpg',
                    'none'    => get_template_directory_uri() .'/assets/img/no-sidebar.jpg',
                ),
                'default'   => 'right',
                'desc'      => esc_html__( 'Set sidebar\'s default position. Can either be: right, left or none. Note: this can be overridden on individual pages.', 'buildico' )
            )

        ), // end: fields
    );

    // ------------------------------
    // a option section with tabs   -
    // ------------------------------
    $options[]   = array(
        'name'     => 'typo_options',
        'title'    =>  esc_html__( 'Typography', 'buildico' ),
        'icon'     => 'fa fa-text-width',
        'sections' => array(

            // Theme Color
            array(
                'name'      => 'theme_color',
                'title'     =>  esc_html__( 'Color', 'buildico' ),
                'icon'      => 'fa fa-paint-brush',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Color', 'buildico')
                    ),

                    array(
                        'id'      => 'primary_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false
                    ),

                    array(
                        'id'      => 'primary_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Primary Color', 'buildico' ),
                        'default' => '#fab702',
                        'rgba'    => true,
                        'desc'    =>  esc_html__( 'You can whole theme primary color using this color picker. Default is #fab702.', 'buildico' ),
                        'dependency'    => array(
                            'primary_custom_style', '==', 'true'
                        )
                    ),

                ), // end: fields

            ), // end: Theme Color

            // Body Typography
            array(
                'name'      => 'body_typo',
                'title'     =>  esc_html__( 'Body', 'buildico' ),
                'icon'      => 'fa fa-square',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Body', 'buildico')
                    ),

                    array(
                        'id'      => 'body_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false
                    ),

                    array(
                        'id'        => 'body_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Open Sans',
                            'font'    => 'google',
                        ),
                        'variant'   => false,
                        'desc'      =>  esc_html__( 'Select body font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'body_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'body_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change body font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'body_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'body_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change body text color from here.', 'buildico' ),
                        'dependency'    => array(
                            'body_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'body_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change body font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'body_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'body_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change body font letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'body_custom_style', '==', 'true'
                        )
                    ),

                ), // end: fields

            ), // end: Body Typography

            // Heading Typography
            array(
                'name'      => 'heading_typo',
                'title'     =>  esc_html__( 'Heading', 'buildico' ),
                'icon'      => 'fa fa-h-square',

                // begin: fields
                'fields'    => array(

                    array(
                        'id'      => 'heading_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false
                    ),

                    array(
                        'type'    => 'heading',
                        'content' => __('Heading', 'buildico'),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'type'    => 'subheading',
                        'content' =>  esc_html__( 'H1 Typography', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h1_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Poppins',
                            'font'    => 'google',
                        ),
                        'desc'      =>  esc_html__( 'Select heading font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h1_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H1 font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'h1_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change H1 color from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h1_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H1 font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h1_margin',
                        'type'      => 'text',
                        'title'     =>  esc_html__( 'Margin', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H1 margin from here. Margin format is: top right bottom left (0px 0px 0px 0px)', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h1_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H1 letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'type'    => 'subheading',
                        'content' =>  esc_html__( 'H2 Typography', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h2_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Poppins',
                            'font'    => 'google',
                        ),
                        'desc'      =>  esc_html__( 'Select heading font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h2_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H2 font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'h2_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change H2 color from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h2_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H2 font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h2_margin',
                        'type'      => 'text',
                        'title'     =>  esc_html__( 'Margin', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H2 margin from here. Margin format is: top right bottom left (0px 0px 0px 0px).', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h2_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H2 letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'type'    => 'subheading',
                        'content' =>  esc_html__( 'H3 Typography', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h3_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Poppins',
                            'font'    => 'google',
                        ),
                        'desc'      =>  esc_html__( 'Select heading font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h3_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H3 font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'h3_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change H3 color from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h3_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H3 font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h3_margin',
                        'type'      => 'text',
                        'title'     =>  esc_html__( 'Margin', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H3 margin from here. Margin format is: top right bottom left (0px 0px 0px 0px).', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h3_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H3 letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'type'    => 'subheading',
                        'content' =>  esc_html__( 'H4 Typography', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h4_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Poppins',
                            'font'    => 'google',
                        ),
                        'desc'      =>  esc_html__( 'Select heading font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h4_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H4 font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'h4_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change H4 color from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h4_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H4 font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h4_margin',
                        'type'      => 'text',
                        'title'     =>  esc_html__( 'Margin', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H4 margin from here. Margin format is: top right bottom left (0px 0px 0px 0px).', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h4_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H4 letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'type'    => 'subheading',
                        'content' =>  esc_html__( 'H5 Typography', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h5_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Poppins',
                            'font'    => 'google',
                        ),
                        'desc'      =>  esc_html__( 'Select heading font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h5_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H5 font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'h5_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change H5 color from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h5_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H5 font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h5_margin',
                        'type'      => 'text',
                        'title'     =>  esc_html__( 'Margin', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H5 margin from here. Margin format is: top right bottom left (0px 0px 0px 0px).', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h5_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H5 letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'type'    => 'subheading',
                        'content' =>  esc_html__( 'H6 Typography', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h6_font_family',
                        'type'      => 'typography',
                        'title'     =>  esc_html__( 'Font Family', 'buildico' ),
                        'default'   => array(
                            'family'  => 'Poppins',
                            'font'    => 'google',
                        ),
                        'desc'      =>  esc_html__( 'Select heading font family from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h6_font_size',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Font Size', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H6 font size from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'      => 'h6_font_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Font Color', 'buildico' ),
                        'default' => '',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change H6 color from here.', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h6_font_lh',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Line Height', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H6 font line height from here. Unit calculate in "PX".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h6_margin',
                        'type'      => 'text',
                        'title'     =>  esc_html__( 'Margin', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H6 margin from here. Margin format is: top right bottom left (0px 0px 0px 0px).', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                    array(
                        'id'        => 'h6_font_ls',
                        'type'      => 'number',
                        'title'     =>  esc_html__( 'Letter Spacing', 'buildico' ),
                        'default'   => '',
                        'desc'      =>  esc_html__( 'You can change H6 letter spacing from here. Unit calculate in "EM".', 'buildico' ),
                        'dependency'    => array(
                            'heading_custom_style', '==', 'true'
                        )
                    ),

                ), // end: fields

            ), // end: Heading Typography

        )
    );

    // ----------------------------------------
    // Archives  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'archives_options',
        'title'       => esc_html__( 'Archives', 'buildico' ),
        'icon'        => 'fa fa-archive',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Archives', 'buildico')
            ),

            array(
                'id'      => 'archive_pageheader',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Page Header', 'buildico' ),
                'desc'    => esc_html__( 'Turn on/off page header from archive page.', 'buildico' ),
                'default' => true
            ),

            array(
                'id'        => 'archive_bg_img',
                'type'      => 'image',
                'title'     => esc_html__( 'Background Image', 'buildico' ),
                'add_title' => esc_html__( 'Add Background Image', 'buildico' ),
                'desc'      => esc_html__( 'Set archive page header background image. Default is background color which you can change from Theme Layout Options.', 'buildico' ),
                'dependency' => array( 'archive_pageheader', '==', true )
            ),

            array(
                'id'      => 'archive_excerpt',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Excerpt', 'buildico' ),
                'desc'    => esc_html__( 'Turn on/off post excerpt from archive page.', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'archive_author',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Author', 'buildico' ),
                'desc'    => esc_html__( 'Turn on/off post author from archive page.', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'archive_date',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Date', 'buildico' ),
                'desc'    => esc_html__( 'Turn on/off post date from archive page.', 'buildico' ),
                'default' => true
            )

        ), // end: fields
    );

    // ----------------------------------------
    // Single Post  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'single_post_options',
        'title'       => esc_html__( 'Single Post', 'buildico' ),
        'icon'        => 'fa fa-square',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Single Post', 'buildico')
            ),

            array(
                'id'           => 'featured_img_post',
                'type'         => 'select',
                'title'        => esc_html__( 'Featured Image Position', 'buildico' ),
                'options'      => array(
                    'in_header'  => esc_html__( 'In Header', 'buildico' ),
                    'in_body'    => esc_html__( 'In Body', 'buildico' ),
                    'in_both'    => esc_html__( 'In Both Header & Body', 'buildico' )
                ),
                'default'      => 'in_body',
                'desc'         => esc_html__( 'Select feature image position for single posts.', 'buildico' )
            ),

            array(
                'id'      => 'single_pub_date',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Post Published Date', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide post published date. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'single_author',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Post Author', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide post author name. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'single_tags',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Post Tags', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide post tags. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'single_post_nav',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Post Navigation', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide post navigation. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'single_author_bio',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Author Bio', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide post author bio. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'type'    => 'subheading',
                'content' => esc_html__( 'Related Posts', 'buildico' )
            ),

            array(
                'id'      => 'single_rel_post',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Related Posts', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide Related Posts. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'single_rel_title',
                'type'    => 'text',
                'title'   => esc_html__( 'Related Posts Title', 'buildico' ),
                'desc'    => esc_html__( 'Change related post title.', 'buildico' ),
                'default' => esc_html__( 'Related Posts', 'buildico' ),
                'dependency' => array( 'single_rel_post', '==', 'true' )
            ),

            array(
                'id'           => 'single_rel_post_select',
                'type'         => 'select',
                'title'        => esc_html__( 'Related Posts - Select', 'buildico' ),
                'options'      => array(
                    'category' => esc_html__( 'By Category', 'buildico' ),
                    'post_tag' => esc_html__( 'By Post Tag', 'buildico' )
                ),
                'default'      => 'category',
                'desc'         => esc_html__( 'Get Related Posts by Category or Post Tags.', 'buildico' ),
                'dependency'   => array( 'single_rel_post', '==', 'true' )
            ),

            array(
                'id'      => 'single_rel_count',
                'type'    => 'number',
                'title'   => esc_html__( 'Related Posts - Count', 'buildico' ),
                'desc'    => esc_html__( 'Number of related posts.', 'buildico' ),
                'default' => '3',
                'dependency' => array( 'single_rel_post', '==', 'true' )
            ),

            array(
                'id'           => 'single_rel_post_orderby',
                'type'         => 'select',
                'title'        => esc_html__( 'Related Posts - Orderby', 'buildico' ),
                'options'      => array(
                    'date'       => esc_html__( 'By Date', 'buildico' ),
                    'author'     => esc_html__( 'By Author', 'buildico' ),
                    'title'      => esc_html__( 'By Title', 'buildico' ),
                    'comment_count' => esc_html__( 'By Comment Count', 'buildico' ),
                    'rand'       => esc_html__( 'By Random', 'buildico' ),
                ),
                'default'      => 'date',
                'desc'         => esc_html__( 'Sorting orderby related posts.', 'buildico' ),
                'dependency'   => array( 'single_rel_post', '==', 'true' )
            ),

            array(
                'id'      => 'single_comment',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Comment', 'buildico' ),
                'desc'    => esc_html__( 'Show/hide post comment. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'hide_social_share',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Hide Social Share Buttons', 'buildico' ),
                'desc'    => esc_html__( 'Hide social share buttons by using this switcher. Default is Off( Show ).', 'buildico' ),
                'default' => false
            ),

        ), // end: fields
    );

    // ----------------------------------------
    // Single Page  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'single_page_options',
        'title'       => esc_html__( 'Page', 'buildico' ),
        'icon'        => 'fa fa-file',
        'sections'	=> array(

            // Page Header
            array(
                'name'      => 'page_header',
                'title'     =>  esc_html__( 'Page Header', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Page Header', 'buildico')
                    ),

                    array(
                        'id'      => 'page_header_align',
                        'type'    => 'radio',
                        'title'   => esc_html__( 'Text Alignment', 'buildico' ),
                        'options' => array(
                            'left'   => esc_html__( 'Left', 'buildico' ),
                            'center'   => esc_html__( 'Center', 'buildico' ),
                            'right'   => esc_html__( 'Right', 'buildico' )
                        ),
                        'default' => 'left',
                        'desc'    => esc_html__( 'This action will affect on all page header.', 'buildico' )
                    ),

                    array(
                        'id'      => 'ph_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false
                    ),

                    array(
                        'id'      => 'ph_bg_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Background Color', 'buildico' ),
                        'default' => '#333',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can change page header background color from here.', 'buildico' ),
                        'dependency'   => array( 'ph_custom_style', '==', 'true' )
                    ),

                    array(
                        'id'      => 'ph_heading_size',
                        'type'    => 'number',
                        'title'   => esc_html__( 'Heading Text Size', 'buildico' ),
                        'desc'    => esc_html__( 'Increase/Decrease page header heading text size. Size unit will increase or decrease in "px".', 'buildico' ),
                        'default' => '24',
                        'dependency'   => array( 'ph_custom_style', '==', 'true' )
                    ),

                    array(
                        'id'      => 'ph_heading_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Heading Text Color', 'buildico' ),
                        'default' => '#fff',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can change heading text color from here.', 'buildico' ),
                        'dependency'   => array( 'ph_custom_style', '==', 'true' )
                    ),

                    array(
                        'id'      => 'ph_border_hide',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Show/Hide Heading Border', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on/off heading border bottom by using this.', 'buildico' ),
                        'default' => true,
                        'dependency'   => array( 'ph_custom_style', '==', 'true' )
                    ),

                    array(
                        'id'      => 'ph_border_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Heading Border Color', 'buildico' ),
                        'default' => '#fab702',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can change heading border bottom color from here.', 'buildico' ),
                        'dependency' => array( 'ph_border_hide|ph_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'ph_border_width',
                        'type'    => 'number',
                        'title'   => esc_html__( 'Border Width', 'buildico' ),
                        'desc'    => esc_html__( 'Increase/Decrease page header heading border width. Width unit will increase or decrease in "px".', 'buildico' ),
                        'default' => '80',
                        'dependency' => array( 'ph_border_hide|ph_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'ph_border_height',
                        'type'    => 'number',
                        'title'   => esc_html__( 'Border Height', 'buildico' ),
                        'desc'    =>  esc_html__( 'Increase/Decrease page header heading border height. Height unit will increase or decrease in "px".', 'buildico' ),
                        'default' => '5',
                        'dependency' => array( 'ph_border_hide|ph_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'ph_desc_hide',
                        'type'    => 'switcher',
                        'title'   =>  esc_html__( 'Show/Hide Page Description', 'buildico' ),
                        'desc'    =>  esc_html__( 'Turn on/off page description by using this.', 'buildico' ),
                        'default' => true
                    ),

                    array(
                        'id'      => 'ph_desc_font_size',
                        'type'    => 'number',
                        'title'   =>  esc_html__( 'Description Text Size', 'buildico' ),
                        'desc'    =>  esc_html__( 'Increase/Decrease page description text size. Size unit will increase or decrease in "px".', 'buildico' ),
                        'default' => '14',
                        'dependency' => array( 'ph_desc_hide|ph_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'ph_desc_lheight',
                        'type'    => 'number',
                        'title'   =>  esc_html__( 'Description Line Height', 'buildico' ),
                        'desc'    =>  esc_html__( 'Increase/Decrease page description line height. height unit will increase or decrease in "px".', 'buildico' ),
                        'default' => '24',
                        'dependency' => array( 'ph_desc_hide|ph_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'ph_desc_text_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Description Text Color', 'buildico' ),
                        'default' => '#ddd',
                        'rgba'    => false,
                        'desc'    =>  esc_html__( 'You can change description text color from here.', 'buildico' ),
                        'dependency' => array( 'ph_desc_hide|ph_custom_style', '==|==', 'true|true' )
                    ),

                ), // end: fields

            ), // end: Page Header

            // Page Settings
            array(
                'name'      => 'page_settings',
                'title'     =>  esc_html__( 'Page Settings', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Page Settings', 'buildico')
                    ),

                    array(
                        'id'      => 'page_comment',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Page Comments', 'buildico' ),
                        'desc'    => esc_html__( 'Enable/Disable Page Comments. Default is On( Enable ).', 'buildico' ),
                        'default' => true
                    )

                ), // end: fields

            ), // end: Page Settings

        )
    );

    // ----------------------------------------
    // WooCommerce  -
    // ----------------------------------------
    if( class_exists( 'WooCommerce' ) ){
		
		$options[]      = array(
		  'name'        => 'woocommerce-options',
		  'title'       => esc_html__( 'WooCommerce', 'buildico' ),
		  'icon'        => 'fa fa-shopping-cart',
		  'sections'    => array(
	
			// Layout
			array(
				'name'      => 'woo_layout',
				'title'     =>  esc_html__( 'Layout', 'buildico' ),
				'icon'      => 'fa fa-angle-right',
	
				// begin: fields
				'fields'    => array(
	
					array(
						'type'    => 'heading',
						'content' => esc_html__( 'Layout', 'buildico' )
					),
	
					array(
					  'id'        => 'woo_archive_header_img',
					  'type'      => 'image',
					  'title'     => esc_html__( 'Page Header Image', 'buildico' ),
					  'add_title' => esc_html__( 'Add Image', 'buildico' ),
					  'desc'      => esc_html__( 'Set WooCommerce archive page header background image.', 'buildico' )
					),
	
					array(
						'id'        => 'woo_sidebar_position',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Page Layout', 'buildico' ),
						'options'   => array(
						'right'   => get_template_directory_uri() .'/assets/img/sidebar-right.jpg',
						'left'    => get_template_directory_uri() .'/assets/img/sidebar-left.jpg',
						'none'    => get_template_directory_uri() .'/assets/img/no-sidebar.jpg',
						),
						'default'   => 'none',
						'desc'      => esc_html__( 'Set sidebar\'s default position. Can either be: right, left or none.', 'buildico' )
					),
	
				), // end: fields
	
			), // end: Layout
	
			// Shop Page
			array(
				'name'      => 'woo_shoppage',
				'title'     =>  esc_html__( 'Shop Page', 'buildico' ),
				'icon'      => 'fa fa-angle-right',
	
				// begin: fields
				'fields'    => array(
	
					array(
						'type'    => 'heading',
						'content' => esc_html__( 'Shop Page', 'buildico' )
					),
	
					array(
					  'id'           => 'woo_cart_option',
					  'type'         => 'select',
					  'title'        => esc_html__( 'Cart Option', 'buildico' ),
					  'options'      => array(
						'none'       => esc_html__( 'Hide Cart', 'buildico' ),
						'show_all'     => esc_html__( 'Show In All Pages', 'buildico' ),
						'show_only_shop'      => esc_html__( 'Show Only Shop Pages', 'buildico' )
					  ),
					  'default'      => 'show_only_shop',
					  'desc'         => esc_html__( 'You can control header cart display option from here.', 'buildico' )
					),
	
					array(
					  'id'           => 'woo_product_column',
					  'type'         => 'select',
					  'title'        => esc_html__( 'Number of columns', 'buildico' ),
					  'options'      => array(
						'1'       => esc_html__( '1', 'buildico' ),
						'2'     => esc_html__( '2', 'buildico' ),
						'3'      => esc_html__( '3', 'buildico' ),
						'4'      => esc_html__( '4', 'buildico' )
					  ),
					  'default'      => '3',
					  'desc'         => esc_html__( 'You can control number of columns from here.', 'buildico' )
					),
	
					array(
					  'id'        => 'woo_ppp',
					  'type'      => 'number',
					  'title'     =>  esc_html__( 'Number of products per page to display', 'buildico' ),
					  'default'   => 9,
					  'desc'      =>  esc_html__( 'You can change number of products per page to display.', 'buildico' ),
					),
	
				), // end: fields
	
			), // end: Shop Page
	
			// Product Single
			array(
				'name'      => 'woo_single_product',
				'title'     =>  esc_html__( 'Single Product', 'buildico' ),
				'icon'      => 'fa fa-angle-right',
	
				// begin: fields
				'fields'    => array(
	
					array(
						'type'    => 'heading',
						'content' => esc_html__( 'Single Product', 'buildico' )
					),
	
					array(
					  'id'      => 'woo_rp_control',
					  'type'    => 'switcher',
					  'title'   => esc_html__( 'Hide Related Products', 'buildico' ),
					  'desc'    => esc_html__( 'You can control related products show or hide from here. Default is OFF(Show).', 'buildico' ),
					  'default' => false
					),
	
					array(
					  'id'      => 'woo_rp_heading',
					  'type'    => 'text',
					  'title'   => esc_html__( 'Related Product Heading', 'buildico' ),
					  'desc'    => esc_html__( 'You can change related product heading from here.', 'buildico' ),
					  'default' => esc_html__( 'Related products', 'buildico' ),
					  'dependency'   => array( 'woo_rp_control', '==', 'false' )
					),
	
					array(
					  'id'        => 'woo_rp_ppp',
					  'type'      => 'number',
					  'title'     =>  esc_html__( 'Number of products per page to display', 'buildico' ),
					  'default'   => 4,
					  'desc'      =>  esc_html__( 'You can change number of products per page to display.', 'buildico' ),
					  'dependency'   => array( 'woo_rp_control', '==', 'false' )
					),
	
					array(
					  'id'           => 'woo_rp_column',
					  'type'         => 'select',
					  'title'        => esc_html__( 'Number of columns', 'buildico' ),
					  'options'      => array(
						'1'     => esc_html__( '1', 'buildico' ),
						'2'     => esc_html__( '2', 'buildico' ),
						'3'     => esc_html__( '3', 'buildico' ),
						'4'     => esc_html__( '4', 'buildico' )
					  ),
					  'default' => '4',
					  'desc'    => esc_html__( 'You can control number of columns from here.', 'buildico' ),
					  'dependency' => array( 
						'woo_rp_control', '==', 'false' )
					),
	
				), // end: fields
	
			), // end: Product Single
	
		  )
		);
	}

    // ----------------------------------------
    // Search Page  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'search_page_options',
        'title'       => esc_html__( 'Search Page', 'buildico' ),
        'icon'        => 'fa fa-search',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Search Page', 'buildico')
            ),

            array(
                'id'      => 'search_page_thumb',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Thumbnails in Search Results', 'buildico' ),
                'desc'    => esc_html__( 'Hide post thumbnails in Search Results page. Default is Off( Hide ).', 'buildico' ),
                'default' => false
            ),

            array(
                'id'      => 'search_page_excerpt',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Show/Hide Search Post Excerpt', 'buildico' ),
                'desc'    => esc_html__( 'Turn On/Off post excerpt from search results. Default is On( Show ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'search_page_exclude',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Exclude pages', 'buildico' ),
                'desc'    => esc_html__( 'Exclude pages from search results. Default is On( Exclude ).', 'buildico' ),
                'default' => true
            ),

        ), // end: fields
    );

    // ----------------------------------------
    // Author Page  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'author_page_options',
        'title'       => esc_html__( 'Author Page', 'buildico' ),
        'icon'        => 'fa fa-user',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Author Page', 'buildico')
            ),

            array(
                'id'        => 'author_header_img',
                'type'      => 'image',
                'title'     => esc_html__( 'Page Header Image', 'buildico' ),
                'add_title' => esc_html__( 'Add Image', 'buildico' ),
                'desc'      => esc_html__( 'Set author page header background image.', 'buildico' )
            ),

            array(
                'id'      => 'enable_author_page',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Enable Author Page', 'buildico' ),
                'desc'    => esc_html__( 'By enabling Author Page, it will enable link on author name in each post. Default is On( Enable ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'author_socail_acc',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Enable Social Accounts', 'buildico' ),
                'desc'    => esc_html__( 'Enable author social accounts. Default is On( Enable ).', 'buildico' ),
                'default' => true
            ),

        ), // end: fields
    );

    // ----------------------------------------
    // 404 Page  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'notfound_page_options',
        'title'       => esc_html__( '404 Page', 'buildico' ),
        'icon'        => 'fa fa-exclamation-circle',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('404 Page', 'buildico')
            ),

            array(
                'id'      => 'error_page_icon_color',
                'type'    => 'color_picker',
                'title'   => esc_html__( 'Icon Color', 'buildico' ),
                'default' => '#fab702',
                'rgba'    => false,
                'desc'    => esc_html__( 'You can change error page icon color from here.', 'buildico' )
            ),

            array(
                'id'      => 'error_page_title',
                'type'    => 'text',
                'title'   => esc_html__( 'Page Title', 'buildico' ),
                'desc'    => esc_html__( 'Title of Page Not Found - 404 page.', 'buildico' ),
                'default' => esc_html__( '404 Not Found!', 'buildico' )
            ),

            array(
                'id'      => 'error_page_content',
                'type'    => 'textarea',
                'title'   => esc_html__( 'Page Content', 'buildico' ),
                'desc'    => esc_html__( 'Content of Page Not Found - 404 page.', 'buildico' ),
                'attributes'    => array(
                    'rows'        => 5,
                ),
                'default' => esc_html__( 'The page you are looking for might have been removed had its name changed or is temporarily unavailable!', 'buildico' )
            ),

            array(
                'id'      => 'error_home_btn',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Back to home button', 'buildico' ),
                'desc'    => esc_html__( 'Enable Back To Home button. Default is On( Enable ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'error_home_text',
                'type'    => 'text',
                'title'   => esc_html__( '"Back to home" Button Text', 'buildico' ),
                'desc'    => esc_html__( 'Title of Page Not Found - 404 page.', 'buildico' ),
                'default' => esc_html__( 'BACK TO HOMEPAGE', 'buildico' ),
                'dependency' => array( 'error_home_btn', '==', 'true' )
            )

        ), // end: fields
    );

    // ----------------------------------------
    // Social Profiles  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'social_profiles',
        'title'       => esc_html__( 'Social Profiles', 'buildico' ),
        'icon'        => 'fa fa-users',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Social Profiles', 'buildico')
            ),

            array(
                'id'              => 'social_lists',
                'type'            => 'group',
                'title'           => esc_html__( 'Social Profiles', 'buildico' ),
                'button_title'    => esc_html__( 'Add New Profile', 'buildico' ),
                'accordion_title' => esc_html__( 'Add New', 'buildico' ),
                'fields'          => array(

                    array(
                        'id'    => 'social_icon',
                        'type'  => 'icon',
                        'title' => esc_html__( 'Choose Icon', 'buildico' ),
                        'desc'  => esc_html__( 'Choose a social icon from this icon picker.', 'buildico' )
                    ),

                    array(
                        'id'    => 'social_link',
                        'type'  => 'text',
                        'title' => esc_html__( 'Profile URL/Link', 'buildico' ),
                        'desc'  => esc_html__( 'Put your social profile full url/link here.', 'buildico' )
                    )
                )
            ),

        ), // end: fields
    );

    // ----------------------------------------
    // Custom ScrollBar  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'scrollbar_options',
        'title'       => esc_html__( 'Custom ScrollBar', 'buildico' ),
        'icon'        => 'fa fa-minus-square',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Custom ScrollBar', 'buildico')
            ),

            array(
                'id'      => 'scrollbar_enable',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Enable Custom ScrollBar', 'buildico' ),
                'desc'    => esc_html__( 'Enable custom scrollbar on your site. Default is On( Enable ).', 'buildico' ),
                'default' => true
            ),

            array(
                'id'      => 'mobile_disable_scrollbar',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Mobile Device', 'buildico' ),
                'desc'    => esc_html__( 'Disable/Enable custom scrollbar on mobile device. Default is On( Enable ).', 'buildico' ),
                'default' => false,
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'scrollbar_bg',
                'type'    => 'color_picker',
                'title'   => esc_html__( 'Background Color', 'buildico' ),
                'default' => '#232427',
                'rgba'    => false,
                'desc'    => esc_html__( 'You can change ScrollBar background color from here.', 'buildico' ),
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'cursor_color',
                'type'    => 'color_picker',
                'title'   => esc_html__( 'Cursor Color', 'buildico' ),
                'default' => '#fab702',
                'rgba'    => false,
                'desc'    => esc_html__( 'You can change text color from here.', 'buildico' ),
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'cursor_width',
                'type'    => 'number',
                'title'   => esc_html__( 'Cursor Width', 'buildico' ),
                'desc'    => esc_html__( 'You can increase or decrease cursor width using this field.', 'buildico' ),
                'default' => '12',
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'scroll_speed',
                'type'    => 'number',
                'title'   => esc_html__( 'Scroll Speed', 'buildico' ),
                'desc'    => esc_html__( 'You can increase or decrease scroll speed using this field.', 'buildico' ),
                'default' => '50',
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'mouse_scroll_step',
                'type'    => 'number',
                'title'   => esc_html__( 'Mouse Scroll Step', 'buildico' ),
                'desc'    => esc_html__( 'You can increase or decrease mouse scroll step using this field.', 'buildico' ),
                'default' => '60',
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'cursor_border',
                'type'    => 'text',
                'title'   => esc_html__( 'Cursor Border', 'buildico' ),
                'desc'    => esc_html__( 'You can change or control cursor border.', 'buildico' ),
                'default' => '0px solid #ddd',
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'cursor_border_radius',
                'type'    => 'number',
                'title'   => esc_html__( 'Cursor Border Radius', 'buildico' ),
                'desc'    => esc_html__( 'You can set cursor border radius using this field. Default is 0.', 'buildico' ),
                'default' => '0',
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'         => 'autohide_mode',
                'type'       => 'radio',
                'title'      => esc_html__( 'Auto Hide Mode', 'buildico' ),
                'options'    => array(
                    'true'   => esc_html__( 'Hide when no scrolling.', 'buildico' ),
                    'cursor' => esc_html__( 'Only cursor hidden.', 'buildico' ),
                    'false'  => esc_html__( 'Do not hide.', 'buildico' ),
                    'leave'  => esc_html__( 'hide only if pointer leaves content.', 'buildico' ),
                    'hidden' => esc_html__( 'Hide always.', 'buildico' ),
                    'scroll' => esc_html__( 'Show only on scroll.', 'buildico' ),
                ),
                'default' => 'false',
                'desc'    => esc_html__( 'You can control auto hide mode from here.', 'buildico' ),
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

            array(
                'id'      => 'scrollbar_zindex',
                'type'    => 'number',
                'title'   => esc_html__( 'Z-Index', 'buildico' ),
                'desc'    => esc_html__( 'Change z-index for scrollbar. Default is 999.', 'buildico' ),
                'default' => '999',
                'dependency' => array( 'scrollbar_enable', '==', 'true' )
            ),

        ), // end: fields
    );

    // ----------------------------------------
    // Admin Page  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'admin_login_options',
        'title'       => esc_html__( 'Admin Login Options', 'buildico' ),
        'icon'        => 'fa fa-user-circle',

        // begin: fields
        'fields'      => array(

            array(
                'type'    => 'heading',
                'content' => __('Admin Login Options', 'buildico')
            ),

            array(
                'id'      => 'login_bg_img',
                'type'    => 'image',
                'title'   => esc_html__( 'Background Image', 'buildico' ),
                'desc'    => esc_html__( 'You can use custom login background image using this image uploader. Upload your favorite image which show in the admin login background!', 'buildico' )
            ),

            array(
                'id'      => 'login_bg_overlay',
                'type'    => 'color_picker',
                'title'   => esc_html__( 'Background Overlay', 'buildico' ),
                'desc'    => esc_html__( 'You can use overlay in background image by using this picker! You can control color opacity.', 'buildico' ),
                'rgba'    => true,
                'default' => 'rgba( 17, 17, 17, 0.6)'
            ),

            array(
                'id'      => 'admin_logo',
                'type'    => 'image',
                'title'   => esc_html__( 'Login Logo', 'buildico' ),
                'desc'    => esc_html__( 'Upload a logo to change your admin login logo. Max Height is 98px.', 'buildico' )
            ),

            array(
                'id'      => 'logo_width',
                'type'    => 'text',
                'title'   => esc_html__( 'Logo Width', 'buildico' ),
                'desc'    => esc_html__( 'Customize admin logo width.', 'buildico' ),
                'default' => '100%',
            ),

            array(
                'id'      => 'logo_height',
                'type'    => 'text',
                'title'   => esc_html__( 'Logo Height', 'buildico' ),
                'desc'    => esc_html__( 'Customize admin logo height.', 'buildico' ),
                'default' => '95px',
            ),

            array(
                'id'      => 'login_header_title',
                'type'    => 'text',
                'title'   => esc_html__( 'Login Header Title', 'buildico' ),
                'desc'    => esc_html__( 'change login header title from here!', 'buildico' ),
                'default' => '',
                'attributes'    => array(
                    'placeholder' => esc_html__( 'Enter header title...', 'buildico' )
                ),
            )

        ), // end: fields
    );

    // ----------------------------------------
    // Footer  -
    // ----------------------------------------
    $options[]      = array(
        'name'        => 'footer_options',
        'title'       => esc_html__( 'Footer', 'buildico' ),
        'icon'        => 'fa fa-minus-square',
        'sections'	=> array(

            // Footer Top
            array(
                'name'      => 'footer_top',
                'title'     =>  esc_html__( 'Footer Top', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'    => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Footer Top', 'buildico')
                    ),

					array(
					  'id'      => 'footer_widget_column',
					  'type'    => 'select',
					  'title'   => esc_html__( 'Widget Column', 'buildico' ),
					  'options' => array(
						'12' => esc_html__( '1 Column', 'buildico' ),
						'6' => esc_html__( '2 Column', 'buildico' ),
						'4' => esc_html__( '3 Column', 'buildico' ),
						'3' => esc_html__( '4 Column', 'buildico' ),
						'2' => esc_html__( '6 Column', 'buildico' ),
					  ),
					  'default' => '3',
					  'desc' => esc_html__( 'Footer widget column options. Default 4 Column.', 'buildico' ),
					),

                    array(
                        'id'      => 'footer_top_bg_hide',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Disable Background Image', 'buildico' ),
                        'desc'    => esc_html__( 'Disable/Enable footer top background image. Default is On( Enable ).', 'buildico' ),
                        'default' => true,
                    ),

                    array(
                        'id'      => 'ft_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false,
                        'dependency' => array( 'footer_top_bg_hide', '==', 'true' )
                    ),

                    array(
                        'id'      => 'footer_top_bg_img',
                        'type'    => 'image',
                        'title'   => esc_html__( 'Background Image', 'buildico' ),
                        'desc'    => esc_html__( 'Upload image for changing footer top background image.', 'buildico' ),
                        'dependency' => array( 'footer_top_bg_hide|ft_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'footer_top_bg_img_opacity',
                        'type'    => 'number',
                        'title'   => esc_html__( 'Background Image Opacity', 'buildico' ),
                        'desc'    => esc_html__( 'You can control background image opacity from here.', 'buildico' ),
                        'default' => '0.1',
                        'dependency' => array( 'footer_top_bg_hide|ft_custom_style', '==|==', 'true|true' )
                    ),

                    array(
                        'id'      => 'footer_top_bg_color',
                        'type'    => 'color_picker',
                        'title'   =>  esc_html__( 'Background Color', 'buildico' ),
                        'default' => '#232427',
                        'rgba'    => true,
                        'desc'    =>  esc_html__( 'You can change footer top background color using this color picker. Default is #232427.', 'buildico' ),
                        'dependency' => array( 'ft_custom_style', '==', 'true' )
                    ),

                ), // end: fields

            ), // end: Footer Top

            // Footer Bottom
            array(
                'name'      => 'footer_bottom',
                'title'     =>  esc_html__( 'Footer Bottom', 'buildico' ),
                'icon'      => 'fa fa-angle-double-right',

                // begin: fields
                'fields'      => array(

                    array(
                        'type'    => 'heading',
                        'content' => __('Footer Bottom', 'buildico')
                    ),

                    array(
                        'id'      => 'footer_copy_text',
                        'type'    => 'textarea',
                        'title'   => esc_html__( 'Copyright Text', 'buildico' ),
                        'desc'    => esc_html__( 'Put your copyright text here.', 'buildico' ),
                        'attributes'    => array(
                            'rows'        => 5,
                        ),
                        'default' => esc_html__( '&copy; Copyright 2017 WowThemez - All Rights Reserved', 'buildico' )
                    ),

                    array(
                        'id'      => 'fb_custom_style',
                        'type'    => 'switcher',
                        'title'   => esc_html__( 'Custom Style', 'buildico' ),
                        'desc'    => esc_html__( 'Turn on to apply your custom style.', 'buildico' ),
                        'default' => false
                    ),

                    array(
                        'id'      => 'footer_bg_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Background Color', 'buildico' ),
                        'default' => '#232427',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can change background color from here.', 'buildico' ),
                        'dependency' => array( 'fb_custom_style', '==', 'true' )
                    ),

                    array(
                        'id'      => 'footer_text_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Text Color', 'buildico' ),
                        'default' => '#bbb',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can change text color from here.', 'buildico' ),
                        'dependency' => array( 'fb_custom_style', '==', 'true' )
                    ),

                    array(
                        'id'      => 'footer_bd_color',
                        'type'    => 'color_picker',
                        'title'   => esc_html__( 'Border Top Color', 'buildico' ),
                        'default' => '#222',
                        'rgba'    => false,
                        'desc'    => esc_html__( 'You can change border top color from here.', 'buildico' ),
                        'dependency' => array( 'fb_custom_style', '==', 'true' )
                    ),

                ), // end: fields

            ), // end: Footer Bottom
        )
    );

    // ----------------------------------------
    // Custom Sidebars  -
    // ----------------------------------------
    $options[]      = array(
		'name'        => 'custom_sidebars',
		'title'       => esc_html__( 'Custom Sidebar', 'buildico' ),
		'icon'        => 'fa fa-list-ul',
  
		// begin: fields
		'fields'      => array(
  
		  array(
			  'type'    => 'heading',
			  'content' => esc_html__( 'Custom Sidebars', 'buildico' )
		  ),
		  
		  // start fields
          array(
            'id'              => 'custom_sidebar',
            'title'           => esc_html__('Sidebars', 'buildico'),
            'desc'            => esc_html__('Go to Appearance -> Widgets after create sidebars', 'buildico'),
            'type'            => 'group',
            'fields'          => array(
              array(
                'id'          => 'sidebar_name',
                'type'        => 'text',
                'title'       => esc_html__('Sidebar Name', 'buildico'),
              ),
              array(
                'id'          => 'sidebar_desc',
                'type'        => 'text',
                'title'       => esc_html__('Custom Description', 'buildico'),
              )
            ),
            'accordion'       => true,
            'button_title'    => esc_html__('Add New Sidebar', 'buildico'),
            'accordion_title' => esc_html__('New Sidebar', 'buildico'),
          ),
          // end fields
		  
		), // end: fields
    );

    // ------------------------------
    // backup                       -
    // ------------------------------
    $options[]   = array(
        'name'     => 'backup_section',
        'title'    => esc_html__( 'Backup', 'buildico' ),
        'icon'     => 'fa fa-shield',
        'fields'   => array(

            array(
                'type'    => 'heading',
                'content' => __('Backup', 'buildico')
            ),

            array(
                'type'    => 'notice',
                'class'   => 'warning',
                'content' => esc_html__( 'You can save your current options. Download a Backup and Import.', 'buildico' ),
            ),

            array(
                'type'    => 'backup',
            ),

        )
    );

    return $options;
}

add_filter('cs_framework_options', 'buildico_cs_theme_options');
