<?php
/*
 * Elementor Events Addon for Elementor Event Espresso Attendees Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_attendees'])) { // enable & disable

if ( function_exists('espresso_version') ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventEspressoAttendees extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_ee_attendees';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events Attendees', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-person';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-ee-category'];
		}

		/**
		 * Register Events Addon for Elementor Event Espresso Attendees widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$events = get_posts( 'post_type="espresso_events"&numberposts=-1' );
	    $EventID = array();
	    if ( $events ) {
	      foreach ( $events as $event ) {
	        $EventID[ $event->ID ] = $event->post_title;
	      }
	    } else {
	      $EventID[ __( 'No ID\'s found', 'events-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Attendees Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Choose Event', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $EventID,
					'description' => __( 'Display a attendees selector for an event on a WordPress page or post', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_gravatar',
				[
					'label' => esc_html__( 'Show Gravatar?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->end_controls_section();// end: Section

			// List
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'List', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'link_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-ee-attendees ul.event-attendees-list li',
				]
			);
			$this->start_controls_tabs( 'link_style' );
				$this->start_controls_tab(
					'link_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-attendees ul.event-attendees-list li, {{WRAPPER}} .naeep-ee-attendees ul.event-attendees-list li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'link_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-attendees ul.event-attendees-list li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event Espresso Attendees widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_id 		= !empty( $settings['event_id'] ) ? $settings['event_id'] : '';
			$show_gravatar 		= !empty( $settings['show_gravatar'] ) ? $settings['show_gravatar'] : '';
			$show_gravatar = $show_gravatar ? 'true' : 'false';

			$event_id = $event_id ? ' event_id="'.$event_id.'"' : '';
			$show_gravatar = $show_gravatar ? ' show_gravatar="'.$show_gravatar.'"' : '';

	  	$output = '<div class="naeep-ee-attendees">'.do_shortcode( '[ESPRESSO_EVENT_ATTENDEES'.$event_id.$show_gravatar.']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventEspressoAttendees() );
}

} // enable & disable