<?php
/*
 * Elementor Events Addon for Elementor Event Organiser Map Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_map'])) { // enable & disable

if ( is_plugin_active( 'event-organiser/event-organiser.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventOrganiserMap extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_eo_map';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events Map', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-google-maps';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-eo-category'];
		}

		/**
		 * Register Events Addon for Elementor Event Organiser Map widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Map Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_venue',
				[
					'label' => __( 'Certain Venues?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-venue'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'custom_class',
				[
					'label' => esc_html__( 'Custom Class', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Enter your custom class here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_responsive_control(
				'map_width',
				[
					'label' => esc_html__( 'Map Width', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
				]
			);
			$this->add_responsive_control(
				'map_height',
				[
					'label' => esc_html__( 'Map Height', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
				]
			);
			$this->add_responsive_control(
				'map_zoom',
				[
					'label' => esc_html__( 'Map Zoom Level', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 1,
						],
					],
				]
			);
			$this->add_control(
				'zoomcontrol',
				[
					'label' => esc_html__( 'Map Zoom Control', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'map_info',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>The following options are only work in google map with valid API.</b></div>',
				]
			);
			$this->add_control(
				'rotatecontrol',
				[
					'label' => esc_html__( 'Map Rotate Control', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'pancontrol',
				[
					'label' => esc_html__( 'Map Pan Control', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'overviewmapcontrol',
				[
					'label' => esc_html__( 'Overview Map Control', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'streetviewcontrol',
				[
					'label' => esc_html__( 'Street View Control', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'draggable',
				[
					'label' => esc_html__( 'Draggable', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'maptypecontrol',
				[
					'label' => esc_html__( 'Map Type Control', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'maptypeid',
				[
					'label' => __( 'Map Type', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'HYBRID' => esc_html__( 'HYBRID', 'events-addon-for-elementor' ),
						'ROADMAP' => esc_html__( 'ROADMAP', 'events-addon-for-elementor' ),
						'SATELLITE' => esc_html__( 'SATELLITE', 'events-addon-for-elementor' ),
						'TERRAIN' => esc_html__( 'TERRAIN', 'events-addon-for-elementor' ),
					],
					'default' => 'HYBRID',
					'condition' => [
						'maptypecontrol' => 'true',
					],
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event Organiser Map widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_venue 				= !empty( $settings['event_venue'] ) ? $settings['event_venue'] : '';
			$custom_class 			= !empty( $settings['custom_class'] ) ? $settings['custom_class'] : '';
			$map_width 					= !empty( $settings['map_width']['size'] ) ? $settings['map_width']['size'] : '';
			$width_unit 				= !empty( $settings['map_width']['unit'] ) ? $settings['map_width']['unit'] : '';
			$map_height 				= !empty( $settings['map_height']['size'] ) ? $settings['map_height']['size'] : '';
			$height_unit 				= !empty( $settings['map_height']['unit'] ) ? $settings['map_height']['unit'] : '';
			$map_zoom 					= !empty( $settings['map_zoom']['size'] ) ? $settings['map_zoom']['size'] : '';
			$zoomcontrol 				= !empty( $settings['zoomcontrol'] ) ? $settings['zoomcontrol'] : '';
			$rotatecontrol 			= !empty( $settings['rotatecontrol'] ) ? $settings['rotatecontrol'] : '';
			$pancontrol 				= !empty( $settings['pancontrol'] ) ? $settings['pancontrol'] : '';
			$overviewmapcontrol = !empty( $settings['overviewmapcontrol'] ) ? $settings['overviewmapcontrol'] : '';
			$streetviewcontrol 	= !empty( $settings['streetviewcontrol'] ) ? $settings['streetviewcontrol'] : '';
			$draggable 					= !empty( $settings['draggable'] ) ? $settings['draggable'] : '';
			$maptypecontrol 		= !empty( $settings['maptypecontrol'] ) ? $settings['maptypecontrol'] : '';
			$maptypeid 					= !empty( $settings['maptypeid'] ) ? $settings['maptypeid'] : '';

			$zoomcontrol = $zoomcontrol ? 'true' : 'false';
			$rotatecontrol = $rotatecontrol ? 'true' : 'false';
			$pancontrol = $pancontrol ? 'true' : 'false';
			$overviewmapcontrol = $overviewmapcontrol ? 'true' : 'false';
			$streetviewcontrol = $streetviewcontrol ? 'true' : 'false';
			$draggable = $draggable ? 'true' : 'false';
			$maptypecontrol = $maptypecontrol ? 'true' : 'false';

			$venue 							= $event_venue ? ' event_venue="'.implode(',', $event_venue).'"' : ' event_venue="%all%"';
			$class 							= $custom_class ? ' class="'.$custom_class.'"' : '';
			$width 							= $map_width ? ' width ="'.$map_width.$width_unit.'"' : '';
			$height 						= $map_height ? ' height ="'.$map_height.$height_unit.'"' : '';
			$zoom 							= $map_zoom ? ' zoom ="'.$map_zoom.'"' : '';
			$zoomcontrol 				= $zoomcontrol ? ' zoomcontrol ="'.$zoomcontrol.'"' : '';
			$rotatecontrol 			= $rotatecontrol ? ' rotatecontrol ="'.$rotatecontrol.'"' : '';
			$pancontrol 				= $pancontrol ? ' pancontrol ="'.$pancontrol.'"' : '';
			$overviewmapcontrol = $overviewmapcontrol ? ' overviewmapcontrol ="'.$overviewmapcontrol.'"' : '';
			$streetviewcontrol 	= $streetviewcontrol ? ' streetviewcontrol ="'.$streetviewcontrol.'"' : '';
			$draggable 					= $draggable ? ' draggable ="'.$draggable.'"' : '';
			$maptypecontrol	 		= $maptypecontrol ? ' maptypecontrol ="'.$maptypecontrol.'"' : '';
			$maptypeid 					= $maptypeid ? ' maptypeid ="'.$maptypeid.'"' : '';

	  	$output = '<div class="naeep-eo-map">'.do_shortcode( '[eo_venue_map'. $venue . $class . $width . $height . $zoom . $zoomcontrol . $rotatecontrol . $pancontrol . $overviewmapcontrol . $streetviewcontrol . $draggable . $maptypecontrol . $maptypeid .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventOrganiserMap() );
}

} // enable & disable