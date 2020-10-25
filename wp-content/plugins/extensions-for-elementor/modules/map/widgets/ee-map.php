<?php
namespace ElementorExtensions\Modules\Map\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorExtensions\Admin\EE_MB_Setting_Common;

class EE_Map extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'map';
	}

	public function get_title() {
		return __( 'Google Map', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_keywords() {
		return [ 'g', 'google', 'map', 'embed', 'gm' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'elementor-extensions' ),
			]
		);

		$default_address = __( 'London Eye, London, United Kingdom', 'elementor-extensions' );
		$this->add_control(
			'address',
			[
				'label' => __( 'Address', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
					],
				],
				'placeholder' => $default_address,
				'default' => $default_address,
				'label_block' => true,
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'map_markerlabel',
			[
				'label' => __( 'Pin Text Override', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'London', 'elementor-extensions' ),
				'placeholder' => __( 'Type your custom label here', 'elementor-extensions' ),
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'map_markerlabel_color',
			[
				'label' => __( 'Pin Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'map_type',
			[
				'label' => __( 'Map type', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ROADMAP',
				'options' => [
					'ROADMAP' => __( 'ROADMAP', 'elementor-extensions' ),
					'SATELLITE' => __( 'SATELLITE', 'elementor-extensions' ),
					'HYBRID' => __( 'HYBRID', 'elementor-extensions' ),
					'TERRAIN' => __( 'TERRAIN', 'elementor-extensions' ),
				],
				'frontend_available' => true
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
				],
				'selectors' => [
					'{{SELECTOR}} div.es-google-map' => 'filter: grayscale({{VALUE}}%)',
				],
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __( 'Zoom', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'separator' => 'before',
				'frontend_available' => true
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} div.es-google-map' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'prevent_scroll',
			[
				'label' => __( 'Prevent Scroll', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor-extensions' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
				'frontend_available' => true
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_style',
			[
				'label' => __( 'Map', 'elementor-extensions' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'selector' => '{{WRAPPER}} div.es-google-map',
			]
		);
		
		$this->add_control(
			'map_geometry',
			[
				'label' => __( 'Map Geometry', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'label_text_stroke',
			[
				'label' => __( 'Labels Text Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'label_text_fill',
			[
				'label' => __( 'Labels Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'administrative_locality',
			[
				'label' => __( 'Administrative Locality', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
				
		$this->add_control(
			'map_poi_color',
			[
				'label' => __( 'Map Poi Geometry', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'map_poi_fill',
			[
				'label' => __( 'Map Poi Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'map_park_color',
			[
				'label' => __( 'Map Park Geometry', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'map_park_fill',
			[
				'label' => __( 'Map Park Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'water_color',
			[
				'label' => __( 'Water Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'water_text_fill',
			[
				'label' => __( 'Water Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'water_text_stroke',
			[
				'label' => __( 'Water Text Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'road_color',
			[
				'label' => __( 'Road Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'road_stroke',
			[
				'label' => __( 'Road Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'highway_color',
			[
				'label' => __( 'Highway Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'highway_stroke',
			[
				'label' => __( 'Highway Stroke', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'highway_text_fill',
			[
				'label' => __( 'Highway Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'transit',
			[
				'label' => __( 'Transit', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'transit_text_fill',
			[
				'label' => __( 'Transit Text Fill', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'frontend_available' => true
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
				'selector' => '{{WRAPPER}}:hover div.es-google-map',
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
	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$gmapkey = EE_MB_Setting_Common::get_settings_key( 'ee_mb_integration_setting', 'ee_mb_google_map_key' );
		
		if ( empty( $settings['address'] ) ) {
			echo 'Address required';
			return;
		}
		
		if ( empty( (array)$gmapkey ) ):
			echo 'Please add google map api key';
			return;
		endif;
				
		echo '<div id="map" class="es-google-map" style="width:100%; height:300px;"></div>';
	}

	protected function _content_template() {}
}
