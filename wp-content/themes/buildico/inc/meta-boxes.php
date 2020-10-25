<?php
/**
 * Meta Boxes
 *
 * @package buildico
 */

// Metabox Init
function buildico_theme_metabox_init(){
    CSFramework_Metabox::instance( array() );
}
add_action( 'init', 'buildico_theme_metabox_init' );

// Theme Meta Boxes
function buildico_theme_metaboxes( $options ){

    $options = array();

    // -----------------------------------------
    // Page Description Option                 -
    // -----------------------------------------

	$options[]      = array(
	  'id'            => 'custom_page_options',
	  'title'         => __('Page Options', 'buildico'),
	  'post_type'     => 'page',
	  'context'       => 'normal',
	  'priority'      => 'high',
	  'sections'      => array(

		// Page Settings
  	    array(
  	      'name'      => 'page_settings_section',
  	      'title'     => __('Page Settings', 'buildico'),
  	      'icon'      => 'fa fa-file',
  	      'fields'    => array(

  			  array(
                  'id'      => 'hide_page_title',
                  'type'    => 'switcher',
                  'title'   => esc_html__( 'Hide Page Header', 'buildico' ),
                  'desc'   => esc_html__( 'You can hide page header by using this switcher!', 'buildico' ),
                  'default' => false
                ),

                array(
                  'id'      => 'page_title_color',
                  'type'    => 'color_picker',
                  'title'   => esc_html__( 'Page Title Color', 'buildico' ),
                  'default' => '#fff',
                  'rgba'    => false,
                  'desc'      => esc_html__( 'Custom settings for page title color.', 'buildico' ),
                  'dependency'   => array( 'hide_page_title', '!=', 'true' ),
                ),

                array(
                  'id'    => 'page_description',
                  'type'  => 'textarea',
                  'title' => esc_html__( 'Page Description', 'buildico' ),
                  'attributes'    => array(
                    'placeholder' => esc_html__( 'Add page description here...', 'buildico' ),
                    'rows'        => 5,
                  ),
                  'dependency'   => array( 'hide_page_title', '!=', 'true' ),
                ),

                array(
                  'id'      => 'page_desc_color',
                  'type'    => 'color_picker',
                  'title'   => esc_html__( 'Description Color', 'buildico' ),
                  'default' => '#ddd',
                  'rgba'    => false,
                  'desc'      => esc_html__( 'Custom settings for page description color.', 'buildico' ),
                  'dependency'   => array( 'hide_page_title|page_description', '!=|!=', 'true|' ),
                ),

                array(
                  'id'    => 'enable_breadcrumb',
                  'type'  => 'select',
                  'title' => esc_html__( 'Enable Bread Crumb', 'buildico' ),
                  'options'    => array(
                    'default' => esc_html__( 'Default', 'buildico' ),
                    'enable' => esc_html__( 'Enable', 'buildico' ),
                    'disable' => esc_html__( 'Disable', 'buildico' ),
                  ),
                  'default'  => 'default',
                  'desc'  => esc_html__( 'Custom settings for breadcrumb.', 'buildico' ),
                  'dependency'   => array( 'hide_page_title', '!=', 'true' ),
                ),
			),
  	    ),

	    // begin header settings
	    array(
	      'name'      => 'header_sec',
	      'title'     => __('Header Settings', 'buildico'),
	      'icon'      => 'fa fa-align-justify',
	      'fields'    => array(

			  array(
                'id'    => 'header_select',
                'type'  => 'select',
                'title' => esc_html__( 'Select Header', 'buildico' ),
                'options'    => array(
                  'default' => esc_html__( 'Default', 'buildico' ),
                  'header-1' => esc_html__( 'Header 1', 'buildico' ),
                  'header-2' => esc_html__( 'Header 2', 'buildico' ),
                ),
                'default'  => 'default',
                'desc'  => esc_html__( 'Select custom header for this page.', 'buildico' )
              ),

              array(
                'id'    => 'header_color_select',
                'type'  => 'select',
                'title' => esc_html__( 'Header Background Color', 'buildico' ),
                'options'    => array(
                  'dark-header' => esc_html__( 'Dark Header', 'buildico' ),
                  'light-header' => esc_html__( 'Light Header', 'buildico' ),
                ),
                'default'  => 'light-header',
                'desc' => esc_html__( 'You can select header color from here.', 'buildico' ),
                'dependency' => array( 'header_select', '!=', 'default' )
              ),

              array(
                'id'    => 'transparent_header',
                'type'  => 'select',
                'title' => esc_html__( 'Transparent Header', 'buildico' ),
                'options'    => array(
                  'default' => esc_html__( 'Default', 'buildico' ),
                  'enable' => esc_html__( 'Enable', 'buildico' ),
                  'disable' => esc_html__( 'Disable', 'buildico' ),
                ),
                'default'  => 'default',
                'desc' => esc_html__( 'Custom settings for make header transparent.', 'buildico' ),
                'dependency' => array( 'header_select', '==', 'header-1' )
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
                'dependency' => array( 'transparent_header|header_select|header_select', '==|!=|!=', 'enable|default|header-2' )
              ),

              array(
                'id'    => 'custom_menu',
                'type'  => 'select',
                'title' => esc_html__( 'Select Custom Menu', 'buildico' ),
                'options'    => buildico_get_navbar_menu_choices(),
                'desc' => esc_html__( 'If you want to show different menu items for specepic pages. Then, you can select menu from here.', 'buildico' ),
              ),

              array(
                  'id'        => 'custom_logo',
                  'type'      => 'image',
                  'title'     => esc_html__( 'Custom Logo', 'buildico' ),
                  'add_title' => esc_html__( 'Upload Custom Logo', 'buildico' ),
                  'desc'      => esc_html__( 'You can upload your custom for this page.', 'buildico' ),
              ),

              array(
                  'id'        => 'sticky_header_logo',
                  'type'      => 'image',
                  'title'     => esc_html__( 'Sticky Header Logo', 'buildico' ),
                  'add_title' => esc_html__( 'Upload Sticky Header Logo', 'buildico' ),
                  'desc'      => esc_html__( 'You can upload your custom sticky header logo for this page.', 'buildico' ),
              ),

	      ),
	    ),

        // Footer
  	    array(
  	      'name'      => 'footer_settings_section',
  	      'title'     => __('Footer', 'buildico'),
  	      'icon'      => 'fa fa-minus-square',
  	      'fields'    => array(

                array(
                  'id'      => 'footer_widget_bg_color',
                  'type'    => 'color_picker',
                  'title'   => esc_html__( 'Background Color', 'buildico' ),
                  'default' => '',
                  'rgba'    => false,
                  'desc'      => esc_html__( 'You can select footer background color for each page footer.', 'buildico' )
                ),

                array(
                  'id'    => 'footer_widget_text_color',
                  'type'  => 'select',
                  'title' => esc_html__( 'Text Color', 'buildico' ),
                  'options'    => array(
                    'default' => esc_html__( 'Default', 'buildico' ),
                    'light' => esc_html__( 'light', 'buildico' ),
                    'dark' => esc_html__( 'Dark', 'buildico' ),
                  ),
                  'default'  => 'default',
                  'desc'  => esc_html__( 'You can select footer text color for each page footer.', 'buildico' )
                ),
			),
  	    ),
	  ),
	);

	global $wp_registered_sidebars;
	$sidebars = array();
	if( ! empty( $wp_registered_sidebars ) ){
		$sidebars['default'] = esc_html__( 'Default', 'buildico' );
		foreach ($wp_registered_sidebars as $sidebar ) {
			$sidebars[ $sidebar['id'] ] = $sidebar['name'];
		}
	}else{
		$sidebars[ esc_html__( 'No sidebar found', 'buildico' ) ] = 0;
	}

	// -----------------------------------------
	// Page Sidebar Option                 -
	// -----------------------------------------
	$options[]    = array(
		'id'        => 'custom_page_sidebar',
		'title'     => esc_html__( 'Sidebar Options', 'buildico' ),
		'post_type' => array( 'page', 'post' ),
		'context'   => 'side',
		'priority'  => 'low',
		'sections'  => array(

			array(
				'name'   => 'sidebar_settings',
				'fields' => array(

					array(
					'id'    => 'sidebar_position',
					'type'  => 'select',
					'title' => esc_html__( 'Sidebar Position', 'buildico' ),
					'options' => array(
						'left'     => esc_html__( 'Left Sidebar', 'buildico' ),
						'right'    => esc_html__( 'Right Sidebar', 'buildico' ),
						'none'     => esc_html__( 'No Sidebar', 'buildico' ),
						'default'  => esc_html__( 'Default', 'buildico' )
					),
					'default' => 'default',
					'desc'  => esc_html__( 'Select sidebar position for this page.', 'buildico' ),
					),

					array(
					'id'    => 'select_sidebars',
					'type'  => 'select',
					'title' => esc_html__( 'Select Sidebar', 'buildico' ),
					'options'   => $sidebars,
					'default'   => 'default',
					'desc'  => esc_html__( 'Select custom sidebar for this page.', 'buildico' ),
					'dependency'   => array( 'sidebar_position', '!=', 'none' ),
					),

				),
			),
		),
	);

    // -----------------------------------------
    // Team Metabox Options                    -
    // -----------------------------------------
    $options[]    = array(
      'id'        => '_team_metabox_options',
      'title'     => esc_html__( 'Team Info Box', 'buildico' ),
      'post_type' => 'wt-team',
      'context'   => 'normal',
      'priority'  => 'default',
      'sections'  => array(

        array(
          'name'   => 'team_infos',
          'fields' => array(

            array(
              'id'    => 'team_position',
              'type'  => 'text',
              'title' => esc_html__( 'Position', 'buildico' ),
              'desc'  => esc_html__( 'Team position or profession goes here.', 'buildico' ),
              'attributes'    => array(
                'placeholder' => esc_html__( 'Position', 'buildico' )
              )
            ),

            array(
              'id'              => 'social_links',
              'type'            => 'group',
              'title'           => esc_html__( 'Social Profiles', 'buildico' ),
              'button_title'    => esc_html__( 'Add New', 'buildico' ),
              'accordion_title' => esc_html__( 'Add New Social Profile', 'buildico' ),
              'fields'          => array(

                array(
                  'id'      => 'social_icon',
                  'type'    => 'icon',
                  'title'   => esc_html__( 'Choose Icon', 'buildico' ),
                  'default' => 'fa fa-facebook',
                ),

                array(
                  'id'    => 'social_link',
                  'type'  => 'text',
                  'title' => esc_html__( 'Link/URL', 'buildico' ),
                  'attributes'    => array(
                    'placeholder' => esc_html__( 'url goes here...', 'buildico' )
                  )
                ),
              ),
            ),
          ),
        ),
      ),
    );

    // -----------------------------------------
    // Slider Metabox Options                    -
    // -----------------------------------------
    $options[]    = array(
      'id'        => '_slider_slides',
      'title'     => esc_html__( 'Create Slides', 'buildico' ),
      'post_type' => 'wtslider',
      'context'   => 'normal',
      'priority'  => 'default',
      'sections'  => array(

        array(
          'name'   => 'wt_slides',
          'fields' => array(

            array(
              'id'          => 'slide_items',
              'type'        => 'gallery',
              'title'       => esc_html__( 'Create Slide Items', 'buildico' ),
              'add_title'   => esc_html__( 'Add Images for Slider', 'buildico' ),
              'edit_title'  => esc_html__( 'Edit Slides', 'buildico' ),
              'clear_title' => esc_html__( 'Remove Slides', 'buildico' ),
            ),

          ),
        ),

      ),
    );

    $effects = array(
        'fadeIn' => esc_html( 'FadeIn' ),
        'fadeInLeft' => esc_html( 'Fade In Left' ),
        'fadeInRight' => esc_html( 'Fade In Right' ),
        'fadeInTop' => esc_html( 'Fade In Top' ),
        'fadeInBottom' => esc_html( 'Fade In Bottom' ),
        'moveFromLeft' => esc_html( 'Move From Left' ),
        'moveFromRight' => esc_html( 'Move From Right' ),
        'moveFromTop' => esc_html( 'Move From Top' ),
        'moveFromBottom' => esc_html( 'Move From Bottom' ),
        'doorCloseFromLeft' => esc_html( 'Door Close From Left' ),
        'doorCloseFromRight' => esc_html( 'Door Close From Right' ),
        'pushReleaseFrom' => esc_html( 'Push Release From' ),
        'pushReleaseFromLeft' => esc_html( 'Push Release From Left' ),
        'pushReleaseFromRight' => esc_html( 'Push Release From Right' ),
        'pushReleaseFromTop' => esc_html( 'Push Release From Top' ),
        'pushReleaseFromBottom' => esc_html( 'Push Release From Bottom' ),
        'flipX' => esc_html( 'FlipX' ),
        'flipXZoomIn' => esc_html( 'FlipX Zoom In' ),
        'flipY' => esc_html( 'FlipY' ),
        'flipYZoomIn' => esc_html( 'FlipY Zoom In' ),
        'skewLeft' => esc_html( 'Skew Left' ),
        'skewRight' => esc_html( 'Skew Right' ),
        'skewInLeft' => esc_html( 'Skew In Left' ),
        'skewInRight' => esc_html( 'Skew In Right' ),
        'shockZoom' => esc_html( 'Shock Zoom' ),
        'shockInLeft' => esc_html( 'Shock In Left' ),
        'shockInRight' => esc_html( 'Shock In Right' ),
        'shockInTop' => esc_html( 'Shock In Top' ),
        'shockInBottom' => esc_html( 'Shock In Bottom' ),
        'pullRelease' => esc_html( 'Pull Release' ),
        'pushRelease' => esc_html( 'Push Release' ),
        'swingInLeft' => esc_html( 'Swing In Left' ),
        'swingInRight' => esc_html( 'Swing In Right' ),
        'swingInTop' => esc_html( 'Swing In Top' ),
        'swingInBottom' => esc_html( 'Swing In Bottom' ),
        'elevateLeft' => esc_html( 'Elevate Left' ),
        'elevateRight' => esc_html( 'Elevate Right' ),
        'rollFromLeft' => esc_html( 'Roll From Left' ),
        'rollFromRight' => esc_html( 'Roll From Right' ),
        'rollFromTop' => esc_html( 'Roll From Top' ),
        'rollFromBottom' => esc_html( 'Roll From Bottom' ),
        'rotate' => esc_html( 'Rotate' ),
        'rotateXIn' => esc_html( 'RotateX In' ),
        'rotateYIn' => esc_html( 'RotateY In' ),
        'rotateInLeft' => esc_html( 'Rotate In Left' ),
        'rotateInRight' => esc_html( 'Rotate In Right' ),
        'rotateInTop' => esc_html( 'Rotate In Top' ),
        'rotateInBottom' => esc_html( 'rotate In Bottom' ),
        'spinToLeft' => esc_html( 'Spin To Left' ),
        'spinToRight' => esc_html( 'Spin To Right' ),
        'spinToTop' => esc_html( 'Spin To Top' ),
        'spinToBottom' => esc_html( 'Spin To Bottom' ),
        'blurIn' => esc_html( 'Blur In' ),
        'blurInLeft' => esc_html( 'Blur In Left' ),
        'blurInRight' => esc_html( 'Blur In Right' ),
        'blurInTop' => esc_html( 'Blur In Top' ),
        'blurInBottom' => esc_html( 'Blur In Bottom' ),
        'bounceFromTop' => esc_html( 'Bounce From Top' ),
        'bounceFromDown' => esc_html( 'Bounce From Down' ),
        'bounceX' => esc_html( 'BounceX' ),
        'bounceY' => esc_html( 'BounceY' ),
        'bounceZoomIn' => esc_html( 'Bounce Zoom In' ),
        'bounceZoomOut' => esc_html( 'Bounce Zoom Out' ),
        'bounceInTop' => esc_html( 'Bounce In Top' ),
        'bounceInLeft' => esc_html( 'Bounce In Left' ),
        'bounceInRight' => esc_html( 'Bounce In Right' ),
        'bounceInBottom' => esc_html( 'Bounce In Bottom' ),
        'zoomIn' => esc_html( 'Zoom In' ),
        'zoomInLeft' => esc_html( 'Zoom In Left' ),
        'zoomInRight' => esc_html( 'Zoom In Right' ),
        'zoomInTop' => esc_html( 'Zoom In Top' ),
        'zoomInBottom' => esc_html( 'Zoom In Bottom' ),
        'danceTop' => esc_html( 'Dance Top' ),
        'danceMiddle' => esc_html( 'Dance Middle' ),
        'danceBottom' => esc_html( 'Dance Bottom' ),
        'leFadeIn sequence' => esc_html( 'Letter FadeIn' ),
        'leFadeInLeft sequence' => esc_html( 'Letter FadeInLeft' ),
        'leFadeInRight sequence' => esc_html( 'Letter FadeInRight' ),
        'leFadeInTop sequence' => esc_html( 'Letter FadeInTop' ),
        'leFadeInBottom sequence' => esc_html( 'Letter FadeInBottom' ),
        'lePeek sequence' => esc_html( 'Letter Peek' ),
        'leSnake sequence' => esc_html( 'Letter Snake' ),
        'effect3d' => esc_html( 'Effect3d' ),
        'leRainDrop sequence' => esc_html( 'Letter Rain Drop' ),
        'leWaterWave sequence' => esc_html( 'Letter Water Wave' ),
        'lightning' => esc_html( 'Lightning' ),
        'leJoltZoom sequence' => esc_html( 'Letter Jolt Zoom' ),
        'open' => esc_html( 'Open' ),
        'leMagnify sequence' => esc_html( 'Letter Magnify' ),
        'leBeat sequence' => esc_html( 'Letter Beat' ),
        'leMovingBackFromRight sequence' => esc_html( 'Letter Moving Back From Right' ),
        'leMovingBackFromLeft sequence' => esc_html( 'Letter Moving Back From Left' ),
        'leKickOutFront sequence' => esc_html( 'Letter Kick Out Front' ),
        'leKickOutBehind sequence' => esc_html( 'Letter Kick Out Behind' ),
        'leSkateX sequence' => esc_html( 'Letter Skate Left Right' ),
        'leSkateY sequence' => esc_html( 'Letter Skate Top Bottom' ),
        'leSkateXY sequence' => esc_html( 'Letter Skate Both' ),
        'leScaleXIn sequence' => esc_html( 'Letter ScaleXIn' ),
        'leScaleYIn sequence' => esc_html( 'Letter ScaleYIn' ),
        'leAboundTop sequence' => esc_html( 'Letter Abound Top' ),
        'leAboundBottom sequence' => esc_html( 'Letter Abound Bottom' ),
        'leAboundLeft sequence' => esc_html( 'Letter Abound Left' ),
        'leAboundRight sequence' => esc_html( 'Letter Abound Right' ),
        'leFlyInTop sequence' => esc_html( 'Letter Fly In Top' ),
        'leFlyInLeft sequence' => esc_html( 'Letter Fly In Left' ),
        'leFlyInRight sequence' => esc_html( 'Letter Fly In Right' ),
        'leFlyInBottom sequence' => esc_html( 'Letter Fly In Bottom' ),
        'leDoorCloseLeft sequence' => esc_html( 'Letter Door Close Left' ),
        'leDoorCloseRight sequence' => esc_html( 'Letter Door Close Right' ),
        'leRencontre sequence' => esc_html( 'Letter Rencontre' ),
        'lePulseShake sequence' => esc_html( 'Letter Pulse Shake' ),
        'leHorizontalShake sequence' => esc_html( 'Letter Horizontal Shake' ),
        'leVerticalShake sequence' => esc_html( 'Letter Vertical Shake' ),
        'leMadMax sequence' => esc_html( 'Letter Shake Mad Max' ),
        'leHorizontalTremble sequence' => esc_html( 'Letter Horizontal Tremble' ),
        'leVerticalTremble sequence' => esc_html( 'Letter Vertical Tremble' ),
        'leCrazyCool sequence' => esc_html( 'Letter Crazy Cool' ),
        'leVibration sequence' => esc_html( 'Letter Vibration' ),
        'lePushReleaseFrom sequence' => esc_html( 'Letter Push Release From' ),
        'lePushReleaseFromLeft sequence' => esc_html( 'Letter Push Release From Left' ),
        'lePushReleaseFromTop sequence' => esc_html( 'Letter Push Release From Top' ),
        'lePushReleaseFromBottom sequence' => esc_html( 'Letter Push Release From Bottom' ),
        'leFlipInTop sequence' => esc_html( 'Letter Flip In Top' ),
        'leFlipInBottom sequence' => esc_html( 'Letter Flip In Bottom' ),
        'leElevateLeft sequence' => esc_html( 'Letter Elevate Left' ),
        'leElevateRight sequence' => esc_html( 'Letter Elevate Right' ),
        'leRollFromLeft sequence' => esc_html( 'Letter Roll From Left' ),
        'leRollFromRight sequence' => esc_html( 'Letter Roll From Right' ),
        'leRollFromTop sequence' => esc_html( 'Letter Roll From Top' ),
        'leRollFromBottom sequence' => esc_html( 'Letter Roll From Bottom' ),
        'leRotateSkateInRight sequence' => esc_html( 'Letter Rotate Skate In Right' ),
        'leRotateSkateInLeft sequence' => esc_html( 'Letter Rotate Skate In Left' ),
        'leRotateSkateInTop sequence' => esc_html( 'Letter Rotate Skate In Top' ),
        'leRotateSkateInBottom sequence' => esc_html( 'Letter Rotate Skate In Bottom' ),
        'leRotateXZoomIn sequence' => esc_html( 'Letter RotateX Zoom In' ),
        'leRotateYZoomIn sequence' => esc_html( 'Letter RotateY Zoom In' ),
        'leRotateIn sequence' => esc_html( 'Letter Rotate In' ),
        'leRotateInLeft sequence' => esc_html( 'Leffter Rotate In Left' ),
        'leRotateInRight sequence' => esc_html( 'Leffter Rotate In Right' ),
        'leSpinInLeft sequence' => esc_html( 'Leffter Spin In Left' ),
        'leSpinInRight sequence' => esc_html( 'Leffter Spin In Right' ),
        'leBlurIn sequence' => esc_html( 'Leffter Blur In' ),
        'leBlurInRight sequence' => esc_html( 'Leffter Blur In Right' ),
        'leBlurInLeft sequence' => esc_html( 'Leffter Blur In Left' ),
        'leBlurInTop sequence' => esc_html( 'Leffter Blur In Top' ),
        'leBlurInBottom sequence' => esc_html( 'Leffter Blur In Bottom' ),
        'lePopUp sequence' => esc_html( 'Leffter PopUp' ),
        'lePopUpLeft sequence' => esc_html( 'Leffter Pop Up Left' ),
        'lePopUpRight sequence' => esc_html( 'Leffter Pop Up Right' ),
        'leBounceFromTop sequence' => esc_html( 'Leffter Bounce From Top' ),
        'leBounceFromDown sequence' => esc_html( 'Leffter Bounce From Down' ),
        'leBounceY sequence' => esc_html( 'Leffter BounceY' ),
        'leBounceZoomIn sequence' => esc_html( 'Leffter Bounce Zoom In' ),
        'leZoomIn sequence' => esc_html( 'Leffter Zoom In' ),
        'leZoomInLeft sequence' => esc_html( 'Leffter Zoom In Left' ),
        'leZoomInRight sequence' => esc_html( 'Leffter Zoom In Right' ),
        'leZoomInTop sequence' => esc_html( 'Leffter Zoom In Top' ),
        'leZoomInBottom sequence' => esc_html( 'Leffter Zoom In Bottom' ),
        'leDanceInTop sequence' => esc_html( 'Leffter Dance In Top' ),
        'leDanceInMiddle sequence' => esc_html( 'Leffter Dance In Middle' ),
        'leDanceInBottom sequence' => esc_html( 'Leffter Dance In Bottom' ),
        'oaoFadeIn sequence' => esc_html( 'Letter OneAfter FadeIn' ),
        'oaoFlyIn sequence' => esc_html( 'Letter OneAfter Fly In' ),
        'oaoRotateIn sequence' => esc_html( 'Letter OneAfter RotateIn' ),
        'oaoRotateXIn sequence' => esc_html( 'Letter OneAfter RotateXIn' ),
        'oaoRotateYIn sequence' => esc_html( 'Letter OneAfter RotateYIn' ),
    );

    $options[]    = array(
      'id'        => '_slider_captions',
      'title'     => esc_html__( 'Slider Caption', 'buildico' ),
      'post_type' => 'wtslider',
      'context'   => 'normal',
      'priority'  => 'default',
      'sections'  => array(

        array(
          'name'   => 'wt_slides_caption',
          'fields' => array(

            array(
              'id'              => 'slider_captions',
              'type'            => 'group',
              'title'           => esc_html__( 'Create Slider Caption', 'buildico' ),
              'button_title'    => esc_html__( 'Add New Caption', 'buildico' ),
              'accordion_title' => 'big_text',
              'fields'          => array(

                array(
                    'id'             => 'caption_align',
                    'type'           => 'select',
                    'title'          => esc_html__( 'Caption Align', 'buildico' ),
                    'options'        => array(
                        'left'       => esc_html__( 'Left', 'buildico' ),
                        'center'     => esc_html__( 'Center', 'buildico' ),
                        'right'      => esc_html__( 'Right', 'buildico' ),
                    ),
                    'default'        => 'left',
                ),

                array(
                    'type'          => 'subheading',
                    'content'       => esc_html__( 'Big Text', 'buildico' )
                ),

                array(
                    'id'            => 'big_text',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Big Caption', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'Slider big caption', 'buildico' )
                    ),
                ),

                array(
                    'id'         => 'bt_effect',
                    'type'       => 'select',
                    'title'      => esc_html__( 'Caption Effect', 'buildico' ),
                    'options'    => $effects,
                    'default'    => 'fadeInLeft2',
                ),

                array(
                    'id'            => 'bt_anim_delay',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Animation Delay', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'animation delay', 'buildico' )
                    ),
                    'default'       => '0.7s'
                ),

                array(
                    'type'          => 'subheading',
                    'content'       => esc_html__( 'Small Text', 'buildico' )
                ),

                array(
                    'id'       => 'small_text',
                    'type'     => 'textarea',
                    'title'    => esc_html__( 'Small Text', 'buildico' ),
                    'attributes' => array(
                        'placeholder' => esc_html__( 'Small text goes here...', 'buildico' ),
                        'rows'        => 5,
                    )
                ),

                array(
                    'id'         => 'sm_effect',
                    'type'       => 'select',
                    'title'      => esc_html__( 'Caption Effect', 'buildico' ),
                    'options'    => $effects,
                    'default'      => 'fadeInLeft2',
                ),

                array(
                    'id'            => 'sm_anim_delay',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Animation Delay', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'animation delay', 'buildico' )
                    ),
                    'default'       => '0.7s'
                ),

                array(
                    'type'          => 'subheading',
                    'content'       => esc_html__( 'Add Button', 'buildico' )
                ),

                array(
                    'type'          => 'content',
                    'content'       => esc_html__( 'Button 1', 'buildico' )
                ),

                array(
                    'id'            => 'btn1_text',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Button Text', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'Button text', 'buildico' )
                    ),
                    'default'       => esc_html__( 'Get Started', 'buildico' )
                ),

                array(
                    'id'            => 'btn1_url',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Button URL', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => 'www.your-link.com'
                    )
                ),

                array(
                    'id'         => 'btn1_effect',
                    'type'       => 'select',
                    'title'      => esc_html__( 'Caption Effect', 'buildico' ),
                    'options'    => $effects,
                    'default'    => 'fadeInLeft2',
                ),

                array(
                    'id'            => 'btn1_anim_delay',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Animation Delay', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'animation delay', 'buildico' )
                    ),
                    'default'       => '0.7s'
                ),

                array(
                    'type'          => 'content',
                    'content'       => esc_html__( 'Button 2', 'buildico' )
                ),

                array(
                    'id'            => 'btn2_text',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Button Text', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'Button text', 'buildico' )
                    ),
                    'default'       => esc_html__( 'Learn More', 'buildico' )
                ),

                array(
                    'id'            => 'btn2_url',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Button URL', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => 'www.your-link.com'
                    )
                ),

                array(
                    'id'         => 'btn2_effect',
                    'type'       => 'select',
                    'title'      => esc_html__( 'Caption Effect', 'buildico' ),
                    'options'    => $effects,
                    'default'    => 'fadeInLeft2',
                ),

                array(
                    'id'            => 'btn2_anim_delay',
                    'type'          => 'text',
                    'title'         => esc_html__( 'Animation Delay', 'buildico' ),
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'animation delay', 'buildico' )
                    ),
                    'default'       => '0.7s'
                ),
              ),
            ),
          ),
        ),
      ),
    );

    $options[]    = array(
      'id'        => '_slider_settings',
      'title'     => esc_html__( 'Slider Settings', 'buildico' ),
      'post_type' => 'wtslider',
      'context'   => 'side',
      'priority'  => 'default',
      'sections'  => array(

        array(
          'name'   => 'wt_slides',
          'fields' => array(

            array(
                'id'         => 'effect',
                'type'       => 'select',
                'title'      => esc_html__( 'Effect', 'buildico' ),
                'options'    => array(
                    'sliceDown'          => 'SliceDown',
                    'sliceDownLeft'      => 'SliceDownLeft',
                    'sliceUp'            => 'SliceUp',
                    'sliceUpLeft'        => 'SliceUpLeft',
                    'sliceUpDown'        => 'SliceUpDown',
                    'sliceUpDownLeft'    => 'SliceUpDownLeft',
                    'random'             => 'Random',
                    'fade'               => 'Fade',
                    'fold'               => 'Fold',
                    'slideInRight'       => 'SlideInRight',
                    'slideInLeft'        => 'SlideInLeft',
                    'boxRandom'          => 'BoxRandom',
                    'boxRain'            => 'BoxRain',
                    'boxRainReverse'     => 'BoxRainReverse',
                    'boxRainGrow'        => 'BoxRainGrow',
                    'boxRainGrowReverse' => 'BoxRainGrowReverse',
                ),
                'default'      => 'random',
            ),

            array(
              'id'      => 'animspeed',
              'type'    => 'number',
              'title'   => esc_html__( 'Animation Speed', 'buildico' ),
              'desc'    => esc_html__( 'Slide transition speed.', 'buildico' ),
              'default' => '500',
            ),

            array(
              'id'      => 'pausetime',
              'type'    => 'number',
              'title'   => esc_html__( 'Pause Time', 'buildico' ),
              'desc'    => esc_html__( 'How long each slide will show.', 'buildico' ),
              'default' => '5000',
            ),

            array(
              'id'      => 'nav',
              'type'    => 'switcher',
              'title'   => esc_html__( 'Navigation', 'buildico' ),
              'help'    => esc_html__( 'Next & Prev navigation.', 'buildico' ),
              'default' => true
            ),

            array(
              'id'      => 'dots',
              'type'    => 'switcher',
              'title'   => esc_html__( 'Dot Navigation', 'buildico' ),
              'help'    => esc_html__( 'Dots navigation.', 'buildico' ),
              'default' => true
            ),

            array(
              'id'      => 'thumbnail',
              'type'    => 'switcher',
              'title'   => esc_html__( 'Thumbnail Nav', 'buildico' ),
              'help'    => esc_html__( 'Use thumbnails for Control Nav.', 'buildico' ),
              'default' => false
            ),
          ),
        ),

      ),
    );

    // -----------------------------------------
    // Project Metabox Options                    -
    // -----------------------------------------
    $options[]    = array(
      'id'        => '_project_metabox_options',
      'title'     => esc_html__( 'Project Info Box', 'buildico' ),
      'post_type' => 'project',
      'context'   => 'normal',
      'priority'  => 'default',
      'sections'  => array(

        array(
          'name'   => 'project_infos',
          'fields' => array(

            array(
              'id'          => 'project_gallery',
              'type'        => 'gallery',
              'title'       => esc_html__( 'Project Photo Gallery', 'buildico' ),
              'add_title'   => esc_html__( 'Add Images', 'buildico' ),
              'edit_title'  => esc_html__( 'Edit Images', 'buildico' ),
              'clear_title' => esc_html__( 'Remove Images', 'buildico' ),
            ),

            array(
              'id'    => 'pj_location',
              'type'  => 'text',
              'title' => esc_html__( 'Location', 'buildico' ),
              'desc'  => esc_html__( 'Project location goes here.', 'buildico' ),
              'attributes'    => array(
                'placeholder' => esc_html__( 'Location', 'buildico' )
              )
            ),

            array(
              'id'    => 'pj_budget',
              'type'  => 'text',
              'title' => esc_html__( 'Budgets', 'buildico' ),
              'desc'  => esc_html__( 'Project budgets goes here.', 'buildico' ),
              'attributes'    => array(
                'placeholder' => esc_html__( 'Budgets', 'buildico' )
              )
            ),

            array(
              'id'    => 'pj_date',
              'type'  => 'datepicker',
              'title' => esc_html__( 'Date', 'buildico' ),
              'desc'  => esc_html__( 'Pick Project Complete date.', 'buildico' ),
              'attributes'    => array(
                'placeholder' => esc_html__( 'Pick a date', 'buildico' )
              )
            ),

            array(
              'id'    => 'pj_client_name',
              'type'  => 'text',
              'title' => esc_html__( 'Client Name', 'buildico' ),
              'desc'  => esc_html__( 'Client name  goes here.', 'buildico' ),
              'attributes'    => array(
                'placeholder' => esc_html__( 'Client name', 'buildico' )
              )
    		),

    		array(
    			'id'              => 'pj_extra_data',
    			'type'            => 'group',
    			'title'           => esc_html__( 'Add Your Own Field', 'buildico' ),
    			'desc'            => esc_html__( 'You can add your own field by using this option.', 'buildico' ),
    			'button_title'    => esc_html__( 'Add New Field', 'buildico' ),
    			'accordion_title' => 'pj_field_label',
    			'fields'          => array(
    				array(
    					'id'    => 'pj_field_label',
    					'type'  => 'text',
    					'title' => esc_html__( 'Label', 'buildico' ),
    					'attributes' => array(
    						'placeholder'	=> esc_html__('Enter your field label here...', 'buildico')
    					)
    				),
    				array(
    					'id'    => 'pj_field_data',
    					'type'  => 'text',
    					'title' => esc_html( 'Value', 'buildico' ),
    					'attributes' => array(
    						'placeholder'	=> esc_html__('Enter your field value here...', 'buildico')
    					)
    				)
    			),
    		),
          ),
        ),
      ),
    );

    return $options;
}
add_filter( 'cs_metabox_options', 'buildico_theme_metaboxes' );
