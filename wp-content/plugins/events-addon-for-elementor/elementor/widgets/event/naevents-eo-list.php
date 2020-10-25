<?php
/*
 * Elementor Events Addon for Elementor Event Organiser List Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_list'])) { // enable & disable

if ( is_plugin_active( 'event-organiser/event-organiser.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventOrganiserList extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_eo_list';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events List', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-archive-posts';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-eo-category'];
		}

		/**
		 * Register Events Addon for Elementor Event Organiser List widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events List Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_limit',
				[
					'label' => esc_html__( 'Limit', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => 4,
					'description' => esc_html__( 'Enter the number of items to show.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_start_before',
				[
					'label' => esc_html__( 'Event Start Before', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that start before date given as a string in YYYY-MM-DD format.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_start_after',
				[
					'label' => esc_html__( 'Event Start After', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that start after date given as a string in YYYY-MM-DD format.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_end_before',
				[
					'label' => esc_html__( 'Event End Before', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that end before date given as a string in YYYY-MM-DD format.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_end_after',
				[
					'label' => esc_html__( 'Event End After', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that end after date given as a string in YYYY-MM-DD format.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'ondate',
				[
					'label' => esc_html__( 'Ondate ', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that start on this specific date given as a string in YYYY-MM-DD format.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'showpastevents',
				[
					'label' => esc_html__( 'Show Past Events?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => __( 'True or False. If set to false, excludes events that have already started. Default value is set in Event Organiser settings page.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_category',
				[
					'label' => __( 'Certain Categories?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-category'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'event_tag',
				[
					'label' => __( 'Certain Tags?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-tag'),
					'multiple' => true,
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
				'event_order',
				[
					'label' => __( 'Order', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'ASC' => esc_html__( 'Asending', 'events-addon-for-elementor' ),
						'DESC' => esc_html__( 'Desending', 'events-addon-for-elementor' ),
					],
					'default' => 'ASC',
				]
			);
			$this->add_control(
				'event_orderby',
				[
					'label' => __( 'Order By', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'eventstart' => esc_html__( 'Event Start Date', 'events-addon-for-elementor' ),
						'eventend' => esc_html__( 'Event End Date', 'events-addon-for-elementor' ),
					],
					'default' => 'eventstart',
				]
			);
			$this->end_controls_section();// end: Section

			// Link
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'Link', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'link_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .naeep-eo-list ul.eo-events li',
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
							'value' => Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-eo-list ul.eo-events li, {{WRAPPER}} .naeep-eo-list ul.eo-events li a' => 'color: {{VALUE}};',
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
						'selectors' => [
							'{{WRAPPER}} .naeep-eo-list ul.eo-events li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event Organiser List widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_limit 			= !empty( $settings['event_limit'] ) ? $settings['event_limit'] : '';
			$event_start_before 		= !empty( $settings['event_start_before'] ) ? $settings['event_start_before'] : '';
			$event_start_after 			= !empty( $settings['event_start_after'] ) ? $settings['event_start_after'] : '';
			$event_end_before 	= !empty( $settings['event_end_before'] ) ? $settings['event_end_before'] : '';
			$event_end_after 	= !empty( $settings['event_end_after'] ) ? $settings['event_end_after'] : '';
			$ondate 	= !empty( $settings['ondate'] ) ? $settings['ondate'] : '';
			$showpastevents 	= !empty( $settings['showpastevents'] ) ? $settings['showpastevents'] : '';
			$event_venue 	= !empty( $settings['event_venue'] ) ? $settings['event_venue'] : '';
			$event_category 	= !empty( $settings['event_category'] ) ? $settings['event_category'] : '';
			$event_tag 				= !empty( $settings['event_tag'] ) ? $settings['event_tag'] : '';
			$event_order 			= !empty( $settings['event_order'] ) ? $settings['event_order'] : '';
			$event_orderby 		= !empty( $settings['event_orderby'] ) ? $settings['event_orderby'] : '';

			$limit = $event_limit ? ' numberposts="'.$event_limit.'"' : '';
			$start_before = $event_start_before ? ' event_start_before="'.$event_start_before.'"' : '';
			$start_after = $event_start_after ? ' event_start_after="'.$event_start_after.'"' : '';
			$end_before = $event_end_before ? ' event_end_before="'.$event_end_before.'"' : '';
			$end_after = $event_end_after ? ' event_end_after="'.$event_end_after.'"' : '';
			$ondate = $ondate ? ' ondate="'.$ondate.'"' : '';
			$showpastevents = $showpastevents ? ' showpastevents="'.$showpastevents.'"' : '';
			$event_venue = $event_venue ? ' venue="'.implode(',', $event_venue).'"' : '';
			$category = $event_category ? ' event_category="'.implode(',', $event_category).'"' : '';
			$tag = $event_tag ? ' event_tag="'.implode(',', $event_tag).'"' : '';
			$order = $event_order ? ' order="'.$event_order.'"' : '';
			$orderby = $event_orderby ? ' orderby="'.$event_orderby.'"' : '';

	  	$output = '<div class="naeep-eo-list">'.do_shortcode( '[event_search event_start_after="now" filters="date"][eo_events'. $limit . $start_before . $start_after . $end_before . $end_after . $ondate . $showpastevents . $event_venue . $category . $tag . $order . $orderby .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventOrganiserList() );
}

} // enable & disable