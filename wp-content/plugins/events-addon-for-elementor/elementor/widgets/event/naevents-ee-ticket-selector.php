<?php
/*
 * Elementor Events Addon for Elementor Event Espresso Ticket Selector Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_ticket_selector'])) { // enable & disable

if ( function_exists('espresso_version') ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventEspressoTicketSelector extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_ee_ticket_selector';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events Ticket Selector', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-price-list';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-ee-category'];
		}

		/**
		 * Register Events Addon for Elementor Event Espresso Ticket Selector widget controls.
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
					'label' => esc_html__( 'Events Ticket Selector Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Choose Event', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $EventID,
					'description' => __( 'Display a ticket selector for an event on a WordPress page or post', 'events-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

			// Table
			$this->start_controls_section(
				'table_style',
				[
					'label' => esc_html__( 'Table', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'table_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'table_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table td, {{WRAPPER}} .naeep-ee-ticket table thead:first-child tr:first-child th',
				]
			);
			$this->add_control(
				'odd_options',
				[
					'label' => __( 'Odd Row', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'odd_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table tbody>tr:nth-child(odd)>td' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'odd_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table tbody>tr:nth-child(odd)>td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'even_options',
				[
					'label' => __( 'Even Row', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'even_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table tbody>tr:nth-child(even)>td' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'even_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table tbody>tr:nth-child(even)>td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Head
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Table Head', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table thead tr' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table thead tr, {{WRAPPER}} .naeep-ee-ticket table thead:first-child tr:first-child th',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table thead tr',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'sastable_head_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table thead tr td',
				]
			);
			$this->add_control(
				'sastable_head_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table thead tr td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Text
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Text', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table td, {{WRAPPER}} .naeep-form form label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table td, {{WRAPPER}} .naeep-form form label',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table td, {{WRAPPER}} .naeep-form form label' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Form
			$this->start_controls_section(
				'section_form_style',
				[
					'label' => esc_html__( 'Form', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'form_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table input[type="text"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="email"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="date"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="time"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="number"],
						{{WRAPPER}} .naeep-ee-ticket table textarea,
						{{WRAPPER}} .naeep-ee-ticket table select,
						{{WRAPPER}} .naeep-ee-ticket table .form-control,
						{{WRAPPER}} .naeep-ee-ticket table .nice-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'form_height',
				[
					'label' => esc_html__( 'Form Height', 'events-addon-for-elementor' ),
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
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table input[type="text"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="email"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="date"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="time"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="number"],
						{{WRAPPER}} .naeep-ee-ticket table textarea,
						{{WRAPPER}} .naeep-ee-ticket table select,
						{{WRAPPER}} .naeep-ee-ticket table .form-control,
						{{WRAPPER}} .naeep-ee-ticket table .nice-select' => 'height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'form_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table input[type="text"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="email"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="date"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="time"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="number"],
					{{WRAPPER}} .naeep-ee-ticket table textarea,
					{{WRAPPER}} .naeep-ee-ticket table select,
					{{WRAPPER}} .naeep-ee-ticket table .form-control,
					{{WRAPPER}} .naeep-ee-ticket table .nice-select',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table input[type="text"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="email"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="date"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="time"],
					{{WRAPPER}} .naeep-ee-ticket table input[type="number"],
					{{WRAPPER}} .naeep-ee-ticket table textarea,
					{{WRAPPER}} .naeep-ee-ticket table select,
					{{WRAPPER}} .naeep-ee-ticket table .form-control,
					{{WRAPPER}} .naeep-ee-ticket table .nice-select',
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket table input[type="text"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="email"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="date"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="time"],
						{{WRAPPER}} .naeep-ee-ticket table input[type="number"],
						{{WRAPPER}} .naeep-ee-ticket table textarea,
						{{WRAPPER}} .naeep-ee-ticket table select,
						{{WRAPPER}} .naeep-ee-ticket table .form-control,
						{{WRAPPER}} .naeep-ee-ticket table .nice-select' => 'color: {{VALUE}} !important;',
					],
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
					'selector' => '{{WRAPPER}} .naeep-ee-ticket table td a',
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
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-ticket table td a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .naeep-ee-ticket table td a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Button
			$this->start_controls_section(
				'section_button_style',
				[
					'label' => esc_html__( 'Button', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .naeep-ee-ticket input[type="submit"]',
				]
			);
			$this->add_control(
				'button_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-ee-ticket input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'button_style' );
				$this->start_controls_tab(
					'button_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'button_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-ticket input[type="submit"]' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-ticket input[type="submit"]' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-ee-ticket input[type="submit"]',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'button_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'button_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-ticket input[type="submit"]:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-ee-ticket input[type="submit"]:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-ee-ticket input[type="submit"]:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event Espresso Ticket Selector widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_id 		= !empty( $settings['event_id'] ) ? $settings['event_id'] : '';

			$event_id = $event_id ? ' event_id="'.$event_id.'"' : '';

	  	$output = '<div class="naeep-ee-ticket naeep-form">'.do_shortcode( '[ESPRESSO_TICKET_SELECTOR'. $event_id .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventEspressoTicketSelector() );
}

} // enable & disable