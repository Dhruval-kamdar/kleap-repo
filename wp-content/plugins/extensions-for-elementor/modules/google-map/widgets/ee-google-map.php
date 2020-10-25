<?php
namespace ElementorExtensions\Modules\GoogleMap\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use ElementorExtensions\Controls\EE_MB_Group_Control_Transition;
use ElementorExtensions\Admin\EE_MB_Setting_Common;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;

class EE_Google_Map extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'google-map';
	}

	public function get_title() {
		return __( 'Multi Point Map', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_script_depends() {
		return [
			'ee-mb-gmap3',
			'ee-mb-googlemap-api',
			'ee-mb-jquery-resize'
		];
	}

	public function get_keywords() {
		return [ 'map', 'multipointmap', 'multi', 'mp', 'mpm', 'point' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_pins',
			[
				'label' => __( 'Locations', 'elementor-extensions' ),
			]
		);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'pins_repeater' );

			$repeater->start_controls_tab( 'pins_pin', [ 'label' => __( 'Pin', 'elementor-extensions' ) ] );

				$repeater->add_control(
					'lat',
					[
						'label'		=> __( 'Latitude', 'elementor-extensions' ),
						'dynamic'	=> [ 'active' => true ],
						'type' 		=> Controls_Manager::TEXT,
						'default' 	=> '',
					]
				);

				$repeater->add_control(
					'lng',
					[
						'label'		=> __( 'Longitude', 'elementor-extensions' ),
						'dynamic'	=> [ 'active' => true ],
						'type' 		=> Controls_Manager::TEXT,
						'default' 	=> '',
					]
				);

				$repeater->add_control(
					'icon',
					[
						'label' 	=> __( 'Icon', 'elementor-extensions' ),
						'dynamic'	=> [ 'active' => true ],
						'description' => __( 'IMPORTANT: Your icon image needs to be a square to avoid distortion of the artwork.', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::MEDIA,
					]
				);

				$repeater->add_control(
					'pin_label',
					[
						'label' => __( 'Pin Text Override', 'elementor-extensions' ),
						'type' => Controls_Manager::TEXT,
						'dynamic'	=> [ 'active' => true ],
						'description' => __( 'IMPORTANT: Pin text will be added a text in map under the pin', 'elementor-extensions' ),
					]
				);
				
				$repeater->add_control(
					'pin_label_color',
					[
						'label' => __( 'Pin Text Color', 'elementor-extensions' ),
						'type' => Controls_Manager::COLOR,
						'dynamic'	=> [ 'active' => true ],
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						]
					]
				);
				
			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'pins_info', [ 'label' => __( 'Popup', 'elementor-extensions' ) ] );

				$repeater->add_control(
					'name',
					[
						'label'		=> __( 'Title', 'elementor-extensions' ),
						'dynamic'	=> [ 'active' => true ],
						'type' 		=> Controls_Manager::TEXT,
						'label_block' => true,
						'default' 	=> __( 'Pin', 'elementor-extensions' ),
					]
				);

				$repeater->add_control(
					'description',
					[
						'label'		=> __( 'Description', 'elementor-extensions' ),
						'dynamic'	=> [ 'active' => true ],
						'label_block' => true,
						'type' 		=> Controls_Manager::TEXTAREA,
					]
				);

				$repeater->add_control(
					'trigger',
					[
						'label'		=> __( 'Trigger', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'click',
						'label_block' => true,
						'options'	=> [
							'click' 	=> __( 'Click', 'elementor-extensions' ),
							'auto' 		=> __( 'Auto', 'elementor-extensions' ),
							'mouseover' => __( 'Mouse Over', 'elementor-extensions' ),
						],
					]
				);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'pins',
				[
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'name' => __( 'Tour Eiffel', 'elementor-extensions' ),
							'lat' => '48.8583736',
							'lng' => '2.2922873',
						],
						[
							'name' => __( 'Arc de Triomphe', 'elementor-extensions' ),
							'lat' => '48.8737952',
							'lng' => '2.2928335',
						],
						[
							'name' => __( 'Louvre Museum', 'elementor-extensions' ),
							'lat' => '48.8606146',
							'lng' => '2.33545',
						],
					],
					'fields' 		=> array_values( $repeater->get_controls() ),
					'title_field' 	=> '{{{ name }}}',
				]
			);
			
		$this->end_controls_section();

		$this->start_controls_section(
			'section_popups',
			[
				'label' => __( 'Popups', 'elementor-extensions' ),
			]
		);

			$this->add_control(
				'popups',
				[
					'label' 		=> __( 'Enable Popups', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'title_tag',
				[
					'label' 	=> __( 'Title Tag', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'h1' 	=> __( 'H1', 'elementor-extensions' ),
						'h2' 	=> __( 'H2', 'elementor-extensions' ),
						'h3' 	=> __( 'H3', 'elementor-extensions' ),
						'h4' 	=> __( 'H4', 'elementor-extensions' ),
						'h5' 	=> __( 'H5', 'elementor-extensions' ),
						'h6' 	=> __( 'H6', 'elementor-extensions' ),
						'div'	=> __( 'div', 'elementor-extensions' ),
						'span' 	=> __( 'span', 'elementor-extensions' ),
					],
					'default' => 'h5',
					'condition' => [
						'popups' => 'yes',
					],
				]
			);

			$this->add_control(
				'description_tag',
				[
					'label' 	=> __( 'Description Tag', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'p',
					'options' 	=> [
						'h6' 	=> __( 'p', 'elementor-extensions' ),
						'div'	=> __( 'div', 'elementor-extensions' ),
						'span' 	=> __( 'span', 'elementor-extensions' ),
					],
					'condition' => [
						'popups' => 'yes',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'elementor-extensions' ),
			]
		);

			$this->add_control(
				'heading_center',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Center Map', 'elementor-extensions' ),
					'condition'	=> [
						'route'	=> '',
					],
				]
			);

			$this->add_control(
				'fit',
				[
					'label' 		=> __( 'Fit to Locations', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
					'condition'		=> [
						'route'		=> '',
					],
				]
			);

			$this->add_control(
				'lat',
				[
					'label'		=> __( 'Latitude', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::TEXT,
					'dynamic'	=> [ 'active' => true ],
					'default' 	=> '48.8583736',
					'condition'	=> [
						'fit' 	=> '',
						'route'	=> '',
					],
				]
			);

			$this->add_control(
				'lng',
				[
					'label'		=> __( 'Longitude', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::TEXT,
					'dynamic'	=> [ 'active' => true ],
					'default' 	=> '2.2922873',
					'condition'	=> [
						'fit' 	=> '',
						'route'	=> '',
					],
				]
			);

			$this->add_control(
				'zoom',
				[
					'label' 		=> __( 'Zoom', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 10,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 18,
							'step'	=> 1,
						],
					],
					'condition' => [
						'fit' 	=> '',
						'route'	=> '',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'heading_settings',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Settings', 'elementor-extensions' ),
					'separator' => 'before',
				]
			);

			$this->add_control(
				'map_type',
				[
					'label'		=> __( 'Map Type', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'ROADMAP',
					'options'	=> [
						'ROADMAP' 	=> __( 'Roadmap', 'elementor-extensions' ),
						'SATELLITE' => __( 'Satellite', 'elementor-extensions' ),
						'TERRAIN' 	=> __( 'Terrain', 'elementor-extensions' ),
						'HYBRID' 	=> __( 'Hybrid', 'elementor-extensions' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'scrollwheel',
				[
					'label' 		=> __( 'Scrollwheel', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'clickable_icons',
				[
					'label' 		=> __( 'Clickable Icons', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'doubleclick_zoom',
				[
					'label' 		=> __( 'Double Click to Zoom', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'draggable',
				[
					'label' 		=> __( 'Draggable', 'elementor-extensions' ),
					'description'	=> __( 'Note: Map is not draggable in edit mode.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'keyboard_shortcuts',
				[
					'label' 		=> __( 'Keyboard Shortcuts', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'heading_controls',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Interface', 'elementor-extensions' ),
					'separator' => 'before',
				]
			);

			$this->add_control(
				'fullscreen_control',
				[
					'label' 		=> __( 'Fullscreen Control', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'map_type_control',
				[
					'label' 		=> __( 'Map Type Control', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'rotate_control',
				[
					'label' 		=> __( 'Rotate Control', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'scale_control',
				[
					'label' 		=> __( 'Scale Control', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'streetview_control',
				[
					'label' 		=> __( 'Street View Control', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'zoom_control',
				[
					'label' 		=> __( 'Zoom Control', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_polygon',
			[
				'label' => __( 'Polygon', 'elementor-extensions' ),
			]
		);

			$this->add_control(
				'polygon',
				[
					'label' 		=> __( 'Enable', 'elementor-extensions' ),
					'description' 	=> __( 'Draws a polygon on the map by connecting the locations.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_route',
			[
				'label' => __( 'Route', 'elementor-extensions' ),
			]
		);

			$this->add_control(
				'route',
				[
					'label' 		=> __( 'Enable', 'elementor-extensions' ),
					'description' 	=> __( 'Draws a route on the map between the locations.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'route_markers',
				[
					'label' 		=> __( 'Markers', 'elementor-extensions' ),
					'description' 	=> __( 'Enables direction markers to be shown on your route.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'condition' 	=> [
						'route!' => '',
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => __( 'Navigation', 'elementor-extensions' ),
			]
		);

			$this->add_responsive_control(
				'navigation',
				[
					'label' 		=> __( 'Enable', 'elementor-extensions' ),
					'description' 	=> __( 'Adds a list which visitors can use to navigate through your locations.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
					'label_off' 	=> __( 'No', 'elementor-extensions' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'navigation_hide_on',
				[
					'label' 	=> __( 'Hide On', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'mobile',
					'options' 	=> [
						'' 			=> __( 'None', 'elementor-extensions' ),
						'tablet' 	=> __( 'Mobile & Tablet', 'elementor-extensions' ),
						'mobile' 	=> __( 'Mobile Only', 'elementor-extensions' ),
					],
					'condition' => [
						'navigation!' => '',
					],
					'prefix_class' => 'ee-mb-google-map-navigation--hide-',
				]
			);

			$this->add_control(
				'all_text',
				[
					'label'		=> __( 'All label', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::TEXT,
					'default' 	=> __( 'All locations', 'elementor-extensions' ),
					'frontend_available' => true,
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'selected_navigation_icon',
				[
					'label' => __( 'Icon', 'elementor-extensions' ),
					'type' => Controls_Manager::ICONS,
					'label_block' => true,
					'default' 	=> [
						'value' => 'fas fa-map-marker',
						'library' => 'fa-solid',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'navigation_icon_align',
				[
					'label' => __( 'Icon Position', 'elementor-extensions' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'left' => __( 'Before', 'elementor-extensions' ),
						'right' => __( 'After', 'elementor-extensions' ),
					],
					'condition' => [
						'navigation!' => '',
						'selected_navigation_icon!' => '',
					],
				]
			);

			$this->add_control(
				'navigation_icon_indent',
				[
					'label' => __( 'Icon Spacing', 'elementor-extensions' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 50,
						],
					],
					'condition' => [
						'navigation!' => '',
						'selected_navigation_icon!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-icon--right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-icon--left' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pins',
			[
				'label' => __( 'Pins', 'elementor-extensions' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'pin_size',
				[
					'label' 		=> __( 'Size', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SLIDER,
					'description' 	=> __( 'Note: This setting only applies to custom pins.', 'elementor-extensions' ),
					'default' 	=> [
						'size' 	=> 50,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 100,
							'step'	=> 1,
						],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'pin_position_horizontal',
				[
					'label' 		=> __( 'Horizontal Position', 'elementor-extensions' ),
					'description' 	=> __( 'Note: This setting only applies to custom pins.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'center',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'pin_position_vertical',
				[
					'label' 		=> __( 'Vertical Position', 'elementor-extensions' ),
					'description' 	=> __( 'Note: This setting only applies to custom pins.', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'top',
					'options' 		=> [
						'top'    		=> [
							'title' 	=> __( 'Top', 'elementor-extensions' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle'    		=> [
							'title' 	=> __( 'Middle', 'elementor-extensions' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extensions' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_map',
			[
				'label' => __( 'Map', 'elementor-extensions' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'map_color',
			[
				'label' => __( 'Map Style', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0'  => __( 'Color', 'elementor-extensions' ),
					'100' => __( 'Black & White', 'elementor-extensions' ),
					'00' => __( 'Custom', 'elementor-extensions' ),
				],
				'selectors' => [
					'{{SELECTOR}} div.ee-mb-google-map' => 'filter: grayscale({{VALUE}}%)',
				],
			]
		);

		$this->add_control(
			'map_style_type',
			[
				'label' => __( 'Add style from', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'api',
				'options' => [
					'api' 	=> __( 'Snazzy Maps API', 'elementor-extensions' ),
					'json' 	=> __( 'Custom JSON', 'elementor-extensions' ),
				],
				'label_block' => true,
				'frontend_available' => true,
				'condition' => [
					'map_color'	=> '0'
				]
			]
		);

		$ee_mb_snazzy_map_endpoint = EE_MB_Setting_Common::get_settings_key( 'ee_mb_integration_setting', 'ee_mb_snazzy_map_endpoint' );

		$ee_mb_snazzy_key = EE_MB_Setting_Common::get_settings_key( 'ee_mb_integration_setting', 'ee_mb_snazzy_map_key' );

		if ( empty((array)$ee_mb_snazzy_map_endpoint )) {
		 	$ee_mb_snazzy_map_endpoint = 'explore';
		}

		if ( empty((array)$ee_mb_snazzy_key )) {
		 	$ee_mb_snazzy_key = '';
		}

		$this->add_control(
			'map_style_api',
			[
				'label' 				=> __( 'Search Snazzy Maps', 'elementor-extensions' ),
				'type' 					=> 'ee-mb-snazzy',
				'placeholder'			=> __( 'Search styles', 'elementor-extensions' ),
				'snazzy_options'		=> [
					'endpoint'			=> $ee_mb_snazzy_map_endpoint,
					'key'				=> $ee_mb_snazzy_key,
				],
				'default'				=> '',
				'frontend_available' 	=> true,
				'condition'				=> [
					'map_style_type'	=> 'api',
					'map_color'	=> '0'
				],
			]
		);

		$this->add_control(
			'map_style_json',
			[
				'label'					=> __( 'Custom JSON', 'elementor-extensions' ),
				'description' 			=> sprintf( __( 'Paste the JSON code for styling the map. You can get it from %1$sSnazzyMaps%2$s or similar services. Note: If you enter an invalid JSON string you\'ll be alerted.', 'elementor-extensions' ), '<a target="_blank" href="https://snazzymaps.com/explore">', '</a>' ),
				'type' 					=> Controls_Manager::TEXTAREA,
				'default' 				=> '',
				'frontend_available' 	=> true,
				'condition'				=> [
					'map_style_type'	=> 'json',
					'map_color'	=> '0'
				],
			]
		);

		$this->add_responsive_control(
			'map_height',
			[
				'label' 		=> __( 'Height', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', 'vh', '%' ],
				'default' 	=> [
					'size' 	=> 400,
				],
				'range' 	=> [
					'vh' 		=> [
						'min' => 0,
						'max' => 100,
					],
					'%' 	=> [
						'min' 	=> 10,
						'max' 	=> 100,
						'step'	=> 1,
					],
					'px' 	=> [
						'min' 	=> 100,
						'max' 	=> 1000,
						'step'	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ee-mb-google-map' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_map_style',
			[
				'label' => __( 'Custom Map Style', 'elementor-extensions' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'map_color'	=> '00'
				]
			]
		);

		$this->start_controls_tabs( 'map_filter' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} div.ee-mb-google-map',
			]
		);
		
		$this->add_control(
			'map_geometry',
			[
				'label' => __( 'Map Geometry', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'label_text_stroke',
			[
				'label' => __( 'Labels Text Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'label_text_fill',
			[
				'label' => __( 'Labels Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'administrative_locality',
			[
				'label' => __( 'Administrative Locality', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
				
		$this->add_control(
			'map_poi_color',
			[
				'label' => __( 'Map Poi Geometry', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'map_poi_fill',
			[
				'label' => __( 'Map Poi Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'map_park_color',
			[
				'label' => __( 'Map Park Geometry', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'map_park_fill',
			[
				'label' => __( 'Map Park Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'water_color',
			[
				'label' => __( 'Water Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'water_text_fill',
			[
				'label' => __( 'Water Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'water_text_stroke',
			[
				'label' => __( 'Water Text Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'road_color',
			[
				'label' => __( 'Road Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'road_stroke',
			[
				'label' => __( 'Road Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'highway_color',
			[
				'label' => __( 'Highway Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'highway_stroke',
			[
				'label' => __( 'Highway Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'highway_text_fill',
			[
				'label' => __( 'Highway Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'transit',
			[
				'label' => __( 'Transit', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'transit_text_fill',
			[
				'label' => __( 'Transit Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true,
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover div.ee-mb-google-map',
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} iframe' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_polygon',
			[
				'label' => __( 'Polygon', 'elementor-extensions' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'polygon!' => '',
				],
			]
		);

			$this->start_controls_tabs( 'polygon_tabs' );

			$this->start_controls_tab( 'polygon_default', [ 'label' => __( 'Default', 'elementor-extensions' ) ] );

				$this->add_control(
					'heading_polygon_stroke',
					[
						'type'		=> Controls_Manager::HEADING,
						'label' 	=> __( 'Stroke', 'elementor-extensions' ),
						'condition' => [
							'polygon!' => '',
						],
					]
				);

				$this->add_control(
					'polygon_stroke_weight',
					[
						'label' 		=> __( 'Weight', 'elementor-extensions' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 2,
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 10,
								'step'	=> 1,
							],
						],
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'polygon_stroke_color',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'polygon_stroke_opacity',
					[
						'label' 		=> __( 'Opacity', 'elementor-extensions' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 0.8,
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 1,
								'step'	=> 0.01,
							],
						],
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'heading_polygon_fill',
					[
						'type'		=> Controls_Manager::HEADING,
						'label' 	=> __( 'Fill', 'elementor-extensions' ),
						'separator' => 'before',
						'condition' => [
							'polygon!' => '',
						],
					]
				);

				$this->add_control(
					'polygon_fill_color',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'polygon_fill_opacity',
					[
						'label' 		=> __( 'Opacity', 'elementor-extensions' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 0.35,
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 1,
								'step'	=> 0.01,
							],
						],
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'polygon_hover', [ 'label' => __( 'Hover', 'elementor-extensions' ) ] );

				$this->add_control(
					'heading_polygon_stroke_hover',
					[
						'type'		=> Controls_Manager::HEADING,
						'label' 	=> __( 'Stroke', 'elementor-extensions' ),
						'condition' => [
							'polygon!' => '',
						],
					]
				);

				$this->add_control(
					'polygon_stroke_weight_hover',
					[
						'label' 		=> __( 'Weight', 'elementor-extensions' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 2,
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 10,
								'step'	=> 1,
							],
						],
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'polygon_stroke_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'polygon_stroke_opacity_hover',
					[
						'label' 		=> __( 'Opacity', 'elementor-extensions' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 0.8,
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 1,
								'step'	=> 0.01,
							],
						],
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'heading_polygon_fill_hover',
					[
						'type'		=> Controls_Manager::HEADING,
						'label' 	=> __( 'Fill', 'elementor-extensions' ),
						'separator' => 'before',
						'condition' => [
							'polygon!' => '',
						],
					]
				);

				$this->add_control(
					'polygon_fill_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'polygon_fill_opacity_hover',
					[
						'label' 		=> __( 'Opacity', 'elementor-extensions' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 0.35,
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 1,
								'step'	=> 0.01,
							],
						],
						'condition' => [
							'polygon!' => '',
						],
						'frontend_available' => true,
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => __( 'Navigation', 'elementor-extensions' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation!' => '',
				],
			]
		);

			$this->add_responsive_control(
				'navigation_position',
				[
					'label'		=> __( 'Position', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'top-left',
					'options'	=> [
						'top-left' 		=> __( 'Top Left', 'elementor-extensions' ),
						'top-right' 	=> __( 'Top Right', 'elementor-extensions' ),
						'bottom-right' 	=> __( 'Bottom Right', 'elementor-extensions' ),
						'bottom-left' 	=> __( 'Bottom Left', 'elementor-extensions' ),
					],
					'frontend_available' => true,
					'prefix_class' => 'ee-mb-google-map-navigation%s--',
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'navigation_width',
				[
					'label' 		=> __( 'Width', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SLIDER,
					'size_units' 	=> [ 'px', '%' ],
					'range' 		=> [
						'%' 		=> [
							'min' => 0,
							'max' => 100,
						],
						'px' 		=> [
							'min' => 100,
							'max' => 1000,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-google-map__navigation' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'navigation_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-google-map__navigation' => 'margin: {{SIZE}}{{UNIT}}; max-height: calc( 100% - {{SIZE}}px * 2 );',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'navigation_background',
				[
					'label' 	=> __( 'Background', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
					    'type' 	=> Scheme_Color::get_type(),
					    'value' => Scheme_Color::COLOR_1,
					],
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-mb-google-map__navigation' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'navigation_border',
					'label' 	=> __( 'Border', 'elementor-extensions' ),
					'selector' 	=> '{{WRAPPER}} .ee-mb-google-map__navigation',
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'navigation_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-google-map__navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .ee-mb-google-map__navigation__item:first-child a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
						'{{WRAPPER}} .ee-mb-google-map__navigation__item:last-child a' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'navigation_box_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-mb-google-map__navigation',
					'separator'	=> '',
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'heading_navigation_separator',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Separator', 'elementor-extensions' ),
					'separator' => 'before',
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'navigation_links_separator_thickness',
				[
					'label' 		=> __( 'Thickness', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 50,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-google-map__navigation__item:not(:last-child) a' => 'border-bottom: {{SIZE}}px solid;',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'heading_navigation_links',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Links', 'elementor-extensions' ),
					'separator' => 'before',
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'navigation_links_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default'		=> [
						'size'		=> 0,
					],
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 50,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-google-map__navigation__item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'navigation_links_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-google-map__navigation__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'navigation_links_typography',
					'label' 	=> __( 'Typography', 'elementor-extensions' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-mb-google-map__navigation',
					'condition' => [
						'navigation!' => '',
					],
				]
			);

			$this->add_control(
				'navigation_links_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'left',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extensions' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extensions' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extensions' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'condition' => [
						'navigation!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-mb-google-map__navigation__link' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				EE_MB_Group_Control_Transition::get_type(),
				[
					'name' 		=> 'image',
					'selector' 	=> '{{WRAPPER}} .ee-mb-google-map__navigation__link',
					'separator'	=> '',
				]
			);

			$this->start_controls_tabs( 'navigation_tabs' );

			$this->start_controls_tab( 'navigation_default', [ 'label' => __( 'Default', 'elementor-extensions' ) ] );

				$this->add_control(
					'navigation_links_color',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'navigation!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__link' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'navigation_links_separator_color',
					[
						'label' 	=> __( 'Separator Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'navigation!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__item:not(:last-child) a' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'navigation_links_background',
					[
						'label' 	=> __( 'Background', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__link' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'navigation!' => '',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'navigation_hover', [ 'label' => __( 'Hover', 'elementor-extensions' ) ] );

				$this->add_control(
					'navigation_links_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'navigation!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__link:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'navigation_links_separator_color_hover',
					[
						'label' 	=> __( 'Separator Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'navigation!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__item:not(:last-child) .ee-mb-google-map__navigation__link:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'navigation_links_background_hover',
					[
						'label' 	=> __( 'Background', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__link:hover' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'navigation!' => '',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'navigation_current', [ 'label' => __( 'Current', 'elementor-extensions' ) ] );

				$this->add_control(
					'navigation_links_color_current',
					[
						'label' 	=> __( 'Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'navigation!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__item.ee--is-active .ee-mb-google-map__navigation__link,
							 {{WRAPPER}} .ee-mb-google-map__navigation__item.ee--is-active .ee-mb-google-map__navigation__link:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'navigation_links_separator_color_current',
					[
						'label' 	=> __( 'Separator Color', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'condition' => [
							'navigation!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__item.ee--is-active .ee-mb-google-map__navigation__item:not(:last-child) .ee-mb-google-map__navigation__link,
							 {{WRAPPER}} .ee-mb-google-map__navigation__item.ee--is-active .ee-mb-google-map__navigation__item:not(:last-child) .ee-mb-google-map__navigation__link:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'navigation_links_background_current',
					[
						'label' 	=> __( 'Background', 'elementor-extensions' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-mb-google-map__navigation__item.ee--is-active .ee-mb-google-map__navigation__link,
							 {{WRAPPER}} .ee-mb-google-map__navigation__item.ee--is-active .ee-mb-google-map__navigation__link:hover' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'navigation!' => '',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$gmapkey = EE_MB_Setting_Common::get_settings_key( 'ee_mb_integration_setting', 'ee_mb_google_map_key' );

		if ( empty($gmapkey)) {
		 	echo __( 'You have not set your Google Maps API key.', 'elementor-extensions' );
			return;
		}

		$this->add_render_attribute( [
			'wrapper' => [
				'class' => [
					'ee-mb-google-map-wrapper',
				],
			],
			'map' => [
				'class' => [
					'ee-mb-google-map',
				],
				'data-lat' => $settings['lat'],
				'data-lng' => $settings['lng'],
				'data-map-color' => $settings['map_color'],
			],
			'title' => [
				'class' => 'ee-mb-google-map__pin__title',
			],
			'description' => [
				'class' => 'ee-mb-google-map__pin__description',
			],
		] );

		if ( ! empty( $settings['pins'] ) ) {

			?><div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>><?php

				if ( '' !== $settings['navigation'] ) {
					$this->render_navigation();
				}
				
				?><div <?php echo $this->get_render_attribute_string( 'map' ); ?>>
						
					<?php foreach ( $settings['pins'] as $index => $item ) {

						$key = $this->get_repeater_setting_key( 'pin', 'pins', $index );
						$title_key = $this->get_repeater_setting_key( 'title', 'pins', $index );
						$description_key = $this->get_repeater_setting_key( 'description', 'pins', $index );

						$this->add_render_attribute( [
							$key => [
								'class' => [
									'ee-mb-google-map__pin',
								],
								'data-trigger' 	   => $item['trigger'],
								'data-lat' 		   => $item['lat'],
								'data-lng' 		   => $item['lng'],
								'data-id' 		   => $item['_id'],
								'data-label' 	   => $item['pin_label'],
								'data-label-color' => $item['pin_label_color'],
							],
						] );

						if ( ! empty( $item['icon']['url'] ) ) {
							$this->add_render_attribute( $key, [
								'data-icon' => esc_url( $item['icon']['url'] ),
							] );
						}


						?><div <?php echo $this->get_render_attribute_string( $key ); ?>>
							<?php if ( '' !== $settings['popups'] ) {

								$title_tag = $settings['title_tag'];
								$description_tag = $settings['description_tag'];
								
								?><<?php echo $title_tag; ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
									<?php echo $item['name']; ?>
								</<?php echo $title_tag; ?>>
								<<?php echo $description_tag; ?> <?php echo $this->get_render_attribute_string( 'description' ); ?>>
									<?php echo $item['description']; ?>
								</<?php echo $description_tag; ?>>

							<?php } ?>
						</div><?php 
					}

				?></div><?php

			?></div><?php

		}
	}

	protected function render_navigation() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'navigation-wrapper' => [
				'class' => [
					'ee-mb-google-map__navigation',
				],
			],
			'navigation' => [
				'class' => [
					'ee-mb-nav',
					'ee-mb-nav--stacked',
					'ee-mb-google-map__navigation__items',
				],
			],
			'text' => [
				'class' => [
					'ee-mb-google-map__navigation__text'
				],
			],
		] );

		if ( ! empty( $settings['selected_navigation_icon'] )) {
			$this->add_render_attribute( 'icon', 'class', [
				'ee-button-icon',
				'ee-icon--' . $settings['navigation_icon_align'],
			] );
		}

		?><div <?php echo $this->get_render_attribute_string( 'navigation-wrapper' ); ?>>

			<ul <?php echo $this->get_render_attribute_string( 'navigation' ); ?>>
				
				<?php $this->render_all_link();

				foreach ( $settings['pins'] as $index => $item ) {

					$item_key = $this->get_repeater_setting_key( 'item', 'pins', $index );
					$link_key = $this->get_repeater_setting_key( 'link', 'pins', $index );

					$this->add_render_attribute( [
						$item_key => [
							'class' => [
								'ee-mb-google-map__navigation__item',
								'elementor-repeater-item-' . $item['_id'],
							],
							'data-id' => $item['_id'],
						],
						$link_key => [
							'class' => [
								'ee-mb-google-map__navigation__link',
								'ee-button',
								'ee-button-link',
							],
						],
					] );

					?><li <?php echo $this->get_render_attribute_string( $item_key ); ?>>
						<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
							<?php if ( ! empty( $settings['selected_navigation_icon']['value'] ) )  { ?>
								<span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
								<?php 
									Icons_Manager::render_icon( $settings['selected_navigation_icon'], [ 'aria-hidden' => 'true' ] );
								?>
								</span>
							<?php } ?>
							<span <?php echo $this->get_render_attribute_string( 'text' ); ?>>
								<?php echo $item['name']; ?>
							</span>
						</a>
					</li><?php 
				} ?>

			</ul>
		</div><?php
	}

	protected function render_all_link() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
				'all' => [
					'class' => [
						'ee-mb-google-map__navigation__item',
						'ee-mb-google-map__navigation__item--all',
					],
				],
				'link' => [
					'class' => [
						'ee-mb-google-map__navigation__link',
						'ee-button',
						'ee-button-link',
					],
				],
			] );

			?><li <?php echo $this->get_render_attribute_string( 'all' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
					<?php if ( ! empty( $settings['selected_navigation_icon']['value'] ) ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
						<?php 
							Icons_Manager::render_icon( $settings['selected_navigation_icon'], [ 'aria-hidden' => 'true' ] );
						 ?>
						</span>
					<?php } ?>
					<span <?php echo $this->get_render_attribute_string( 'text' ); ?>>
						<?php echo $settings['all_text']; ?>
					</span>
				</a>
			</li><?php
	}

	protected function _content_template() {}
}
