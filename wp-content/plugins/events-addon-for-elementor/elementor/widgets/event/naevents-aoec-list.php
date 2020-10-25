<?php
/*
 * Elementor Events Addon for Elementor All-in-One Event Calendar List Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_list'])) { // enable & disable

if ( is_plugin_active( 'all-in-one-event-calendar/all-in-one-event-calendar.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_AOEC_List extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_aoec_list';
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
			return ['naevents-aoec-category'];
		}

		/**
		 * Register Events Addon for Elementor All-in-One Event Calendar List widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$events = get_posts( 'post_type="ai1ec_event"&numberposts=-1' );
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
					'label' => esc_html__( 'Events List Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_view',
				[
					'label' => __( 'View', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'monthly' => esc_html__( 'Monthly', 'events-addon-for-elementor' ),
						'weekly' => esc_html__( 'Weekly', 'events-addon-for-elementor' ),
						'agenda' => esc_html__( 'Agenda', 'events-addon-for-elementor' ),
						'posterboard' => esc_html__( 'Poster Board', 'events-addon-for-elementor' ),
					],
					'default' => 'monthly',
				]
			);
			$this->add_control(
				'cat_name',
				[
					'label' => __( 'Certain Categories?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'events_categories'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'tag_name',
				[
					'label' => __( 'Certain Tags?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'events_tags'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'post_id',
				[
					'label' => __( 'Certain Event\'s?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $EventID,
					'multiple' => true,
				]
			);
			$this->add_control(
				'display_filters',
				[
					'label' => esc_html__( 'Display Filters?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'event_limit',
				[
					'label' => esc_html__( 'Limit', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => -1,
					'max' => 100,
					'step' => 1,
					'default' => -1,
					'description' => esc_html__( 'Enter the number of items to show.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'exact_date',
				[
					'label' => esc_html__( 'Exact Date ', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'd-m-Y',
						'enableTime' => 'false',
					],
					'placeholder' => esc_html__( 'DD-MM-YYYY', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that start on this specific date given as a string in DD-MM-YYYY format will show in day view at output.', 'events-addon-for-elementor' ),
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
			$this->add_responsive_control(
				'secn_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-aoec-list .ai1ec-month-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-week-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-oneday-view th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-aoec-list .ai1ec-month-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-week-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-oneday-view th' => 'background-color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'text_shadow',
					'label' => esc_html__( 'Text Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-aoec-list .ai1ec-month-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-week-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-oneday-view th',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'table_head_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-aoec-list .ai1ec-month-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-week-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-oneday-view th',
				]
			);
			$this->add_control(
				'table_head_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-aoec-list .ai1ec-month-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-week-view th, {{WRAPPER}} .naeep-aoec-list .ai1ec-oneday-view th' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Date
			$this->start_controls_section(
				'section_date_style',
				[
					'label' => esc_html__( 'Table Dates', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'date_style' );
				$this->start_controls_tab(
					'date_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'date_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .ai1ec-month-view .ai1ec-date, {{WRAPPER}} .ai1ec-month-view .ai1ec-date a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'date_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-month-view .ai1ec-date' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'date_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'date_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-month-view .ai1ec-date a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Event
			$this->start_controls_section(
				'section_event_style',
				[
					'label' => esc_html__( 'Table Events', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'event_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai1ec-month-view .ai1ec-allday .ai1ec-event,
						{{WRAPPER}} .ai1ec-month-view .ai1ec-multiday .ai1ec-event,
						{{WRAPPER}} .ai1ec-week-view .ai1ec-allday-events .ai1ec-allday .ai1ec-event,
						{{WRAPPER}} .ai1ec-week-view .ai1ec-allday-events .ai1ec-multiday .ai1ec-event,
						{{WRAPPER}} .ai1ec-oneday-view .ai1ec-allday-events .ai1ec-allday .ai1ec-event,
						{{WRAPPER}} .ai1ec-oneday-view .ai1ec-allday-events .ai1ec-multiday .ai1ec-event' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'event_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai1ec-month-view .ai1ec-allday .ai1ec-event,
						{{WRAPPER}} .ai1ec-month-view .ai1ec-multiday .ai1ec-event,
						{{WRAPPER}} .ai1ec-week-view .ai1ec-allday-events .ai1ec-allday .ai1ec-event,
						{{WRAPPER}} .ai1ec-week-view .ai1ec-allday-events .ai1ec-multiday .ai1ec-event,
						{{WRAPPER}} .ai1ec-oneday-view .ai1ec-allday-events .ai1ec-allday .ai1ec-event,
						{{WRAPPER}} .ai1ec-oneday-view .ai1ec-allday-events .ai1ec-multiday .ai1ec-event,
						{{WRAPPER}} .ai1ec-month-view .ai1ec-multiday-arrow1' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .ai1ec-month-view .ai1ec-multiday-arrow1' => 'border-left-color: {{VALUE}};',
						'{{WRAPPER}} .ai1ec-month-view .ai1ec-multiday-arrow2' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'event_today_color',
				[
					'label' => esc_html__( 'Today Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai1ec-month-view .ai1ec-today,
						{{WRAPPER}} .ai1ec-week-view .ai1ec-today' => 'background-color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Filter
			$this->start_controls_section(
				'section_filter_style',
				[
					'label' => esc_html__( 'Filter', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'frontend_available' => true,
					'condition' => [
						'display_filters' => 'true',
					],
				]
			);
			$this->add_responsive_control(
				'filter_padding',
				[
					'label' => __( 'Filter Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-calendar-toolbar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'filter_margin',
				[
					'label' => __( 'Filter Margin', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-calendar-toolbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'filter_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-calendar-toolbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'filter_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .ai1ec-calendar-toolbar',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'filter_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ai1ec-calendar-toolbar',
				]
			);
			$this->start_controls_tabs( 'filter_style' );
				$this->start_controls_tab(
					'filter_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'filter_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .timely .ai1ec-nav > li > a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'filter_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .timely .ai1ec-nav > li > a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'filter_active',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'filter_active_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .timely .ai1ec-nav > li > a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'filter_active_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .timely .ai1ec-nav > li > a:hover, {{WRAPPER}} .timely .ai1ec-nav > li > a:focus' => 'background-color: {{VALUE}}',
						],
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Pagination
			$this->start_controls_section(
				'section_pagi_style',
				[
					'label' => esc_html__( 'Pagination', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'pagi_padding',
				[
					'label' => __( 'Pagination Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_margin',
				[
					'label' => __( 'Pagination Margin', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagi_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .ai1ec-pagination a',
				]
			);
			$this->start_controls_tabs( 'pagi_style' );
				$this->start_controls_tab(
					'pagi_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						],
						'selectors' => [
							'{{WRAPPER}} .ai1ec-pagination a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-pagination a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .ai1ec-pagination a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'pagi_active',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_active_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-pagination a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'pagi_active_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-pagination a:hover, {{WRAPPER}} .ai1ec-pagination a:focus' => 'background-color: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_active_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .ai1ec-pagination a:hover, {{WRAPPER}} .ai1ec-pagination a:focus',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'btn_padding',
				[
					'label' => __( 'Button Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a, {{WRAPPER}} .ai1ec-subscribe-dropdown > .ai1ec-subscribe' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_margin',
				[
					'label' => __( 'Button Margin', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group, {{WRAPPER}} .ai1ec-subscribe-dropdown > .ai1ec-subscribe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'btn_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a, {{WRAPPER}} .ai1ec-subscribe-dropdown > .ai1ec-subscribe',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a, {{WRAPPER}} .ai1ec-subscribe-dropdown > .ai1ec-subscribe' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a, {{WRAPPER}} .ai1ec-subscribe-dropdown > .ai1ec-subscribe' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a, {{WRAPPER}} .ai1ec-subscribe-dropdown',
					]
				);
				$this->add_control(
					'btn_radius',
					[
						'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a, {{WRAPPER}} .ai1ec-subscribe-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_active',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_active_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:hover, {{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:focus, {{WRAPPER}} .ai1ec-subscribe-dropdown:hover > .ai1ec-subscribe' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'btn_active_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:hover, {{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:focus, {{WRAPPER}} .ai1ec-subscribe-dropdown:hover > .ai1ec-subscribe' => 'background-color: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_active_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:hover, {{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:focus, {{WRAPPER}} .ai1ec-subscribe-dropdown:hover',
					]
				);
				$this->add_control(
					'btn_active_radius',
					[
						'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:hover, {{WRAPPER}} .ai1ec-views-dropdown.ai1ec-btn-group a:focus, {{WRAPPER}} .ai1ec-subscribe-dropdown:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render All-in-One Event Calendar List widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_view 		= !empty( $settings['event_view'] ) ? $settings['event_view'] : '';
			$cat_name 	= !empty( $settings['cat_name'] ) ? $settings['cat_name'] : '';
			$tag_name 				= !empty( $settings['tag_name'] ) ? $settings['tag_name'] : '';
			$post_id 	= !empty( $settings['post_id'] ) ? $settings['post_id'] : '';
			$display_filters 			= !empty( $settings['display_filters'] ) ? $settings['display_filters'] : '';
			$event_limit 			= !empty( $settings['event_limit'] ) ? $settings['event_limit'] : '';
			$exact_date 	= !empty( $settings['exact_date'] ) ? $settings['exact_date'] : '';

			$display_filters = $display_filters ? 'true' : 'false';

			$view = $event_view ? ' view="'.$event_view.'"' : '';
			$category = $cat_name ? ' cat_name="'.implode(',', $cat_name).'"' : '';
			$tag = $tag_name ? ' tag_name="'.implode(',', $tag_name).'"' : '';
			$post_id = $post_id ? ' post_id="'.implode(',', $post_id).'"' : '';
			$filters = $display_filters ? ' display_filters="'.$display_filters.'"' : '';
			$limit = $event_limit ? ' events_limit="'.$event_limit.'"' : '';
			$exact_date = $exact_date ? ' exact_date="'.$exact_date.'"' : '';

	  	$output = '<div class="naeep-aoec-list">'.do_shortcode( '[ai1ec'. $view . $category . $tag . $post_id . $filters . $limit . $exact_date .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_AOEC_List() );
}

} // enable & disable