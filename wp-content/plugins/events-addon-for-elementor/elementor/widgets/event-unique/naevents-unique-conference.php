<?php
/*
 * Elementor Events Addon for Elementor Unique Conference Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_conference'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Conference extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_conference';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Conference', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-alert';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Conference widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'event_option',
			[
				'label' => esc_html__( 'Conference Settings', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'conference_title',
			[
				'label' => esc_html__( 'Conference Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'About Conference', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'event_image',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
			]
		);
		$this->add_control(
			'event_title',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Hype-free applications of AI', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'title_link',
			[
				'label' => esc_html__( 'Title Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'event_content',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$this->add_control(
			'event_location',
			[
				'label' => esc_html__( 'Location', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'event_organizer',
			[
				'label' => esc_html__( 'Organizer', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'count_type',
			[
				'label' => __( 'Countdown Type', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'static' => esc_html__( 'Static Date', 'events-addon-for-elementor' ),
					'fake'    => esc_html__('Fake Date', 'events-addon-for-elementor'),
				],
				'default' => 'static',
				'description' => esc_html__( 'Select your countdown date type.', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'count_date_static',
			[
				'label' => esc_html__( 'Date & Time', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'dateFormat' => 'm/d/Y G:i:S',
					'enableTime' => 'true',
					'enableSeconds' => 'true',
				],
				'placeholder' => esc_html__( 'mm/dd/yyyy hh:mm:ss', 'events-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'count_type' => 'static',
				],
			]
		);
		$this->add_control(
			'fake_date',
			[
				'label' => esc_html__( 'Fake Date', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '3', 'events-addon-for-elementor' ),
				'description' => esc_html__( 'Enter your fake day count here. Ex: 2 or 3(in days)', 'events-addon-for-elementor' ),
				'condition' => [
					'count_type' => 'fake',
				],
			]
		);
		$this->add_control(
			'countdown_format',
			[
				'label' => esc_html__( 'Conference Format', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'dHMS', 'events-addon-for-elementor' ),
				'description' => __( '<b>For Conference Format Reference : <a href="http://keith-wood.name/countdown.html" target="_blank">Click Here</a></b>.', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'toggle_align',
			[
				'label' => esc_html__( 'Toggle Align?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'countdown_labels',
			[
				'label' => esc_html__( 'Conference Labels', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'plural_labels',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Plural Labels</b></div>',
			]
		);
		$this->add_control(
			'label_years',
			[
				'label' => esc_html__( 'Years Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'years', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_months',
			[
				'label' => esc_html__( 'Months Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'months', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_weeks',
			[
				'label' => esc_html__( 'Weeks Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'weeks', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_days',
			[
				'label' => esc_html__( 'Days Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'days', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_hours',
			[
				'label' => esc_html__( 'Hours Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'hours', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_minutes',
			[
				'label' => esc_html__( 'Minutes Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'minutes', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_seconds',
			[
				'label' => esc_html__( 'Seconds Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'seconds', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'singular_label',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Singular Labels</b></div>',
			]
		);
		$this->add_control(
			'label_year',
			[
				'label' => esc_html__( 'Year Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'year', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_month',
			[
				'label' => esc_html__( 'Month Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'month', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_week',
			[
				'label' => esc_html__( 'Week Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'week', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_day',
			[
				'label' => esc_html__( 'Day Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'day', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_hour',
			[
				'label' => esc_html__( 'Hour Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'hour', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_minute',
			[
				'label' => esc_html__( 'Minute Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'minute', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_second',
			[
				'label' => esc_html__( 'Second Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'second', 'events-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'image_style',
			[
				'label' => esc_html__( 'Image', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'team_image_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference .naeep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference .naeep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-conference .naeep-image img',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-conference .naeep-image img',
			]
		);
		$this->end_controls_section();// end: Section

		// Sub Title
		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__( 'Sub Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'subtitle_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .naeep-conference-item h5',
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item h5' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_name_style',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-conference-item h3',
			]
		);
		$this->start_controls_tabs( 'name_style' );
			$this->start_controls_tab(
				'title_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'name_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-conference-item h3, {{WRAPPER}} .naeep-conference-item h3 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'title_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'name_hover_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-conference-item h3 a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .naeep-conference-item p',
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
					'{{WRAPPER}} .naeep-conference-item p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Value
		$this->start_controls_section(
			'section_value_style',
			[
				'label' => esc_html__( 'Counter Value', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'value_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section .countdown_amount',
			]
		);
		$this->add_control(
			'value_color',
			[
				'label' => esc_html__( 'Value Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section .countdown_amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'value_bg_color',
			[
				'label' => esc_html__( 'Value Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'value_box_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'value_box_shadow',
				'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section',
			]
		);
		$this->add_control(
			'value_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Separator
		$this->start_controls_section(
			'section_value_sep_style',
			[
				'label' => esc_html__( 'Counter Separator', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'value_sep_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .countdown_section:after',
			]
		);
		$this->add_control(
			'value_sep_color',
			[
				'label' => esc_html__( 'Separator Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .countdown_section:after' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Counter Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Metas
		$this->start_controls_section(
			'section_metas_style',
			[
				'label' => esc_html__( 'Metas', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'metas_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'metas_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .naeep-conference-item ul li',
			]
		);
		$this->add_control(
			'metas_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item ul li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'metas_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-conference-item ul li' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'metas_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-conference-item ul li',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'metas_box_shadow',
				'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-conference-item ul li',
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Conference widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$conference_type = !empty( $settings['conference_type'] ) ? $settings['conference_type'] : '';
		$conference_title = !empty( $settings['conference_title'] ) ? $settings['conference_title'] : '';
		$event_image = !empty( $settings['event_image']['id'] ) ? $settings['event_image']['id'] : '';
		$event_title = !empty( $settings['event_title'] ) ? $settings['event_title'] : '';
		$title_link = !empty( $settings['title_link']['url'] ) ? $settings['title_link']['url'] : '';
		$title_link_external = !empty( $settings['title_link']['is_external'] ) ? 'target="_blank"' : '';
		$title_link_nofollow = !empty( $settings['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';

		$event_content = !empty( $settings['event_content'] ) ? $settings['event_content'] : '';
		$event_location = !empty( $settings['event_location'] ) ? $settings['event_location'] : '';
		$event_organizer = !empty( $settings['event_organizer'] ) ? $settings['event_organizer'] : '';

		$count_type = !empty( $settings['count_type'] ) ? $settings['count_type'] : '';
		$count_date_static = !empty( $settings['count_date_static'] ) ? $settings['count_date_static'] : '';
		$fake_date = !empty( $settings['fake_date'] ) ? $settings['fake_date'] : '';
		$countdown_format = !empty( $settings['countdown_format'] ) ? $settings['countdown_format'] : '';
		$toggle_align = !empty( $settings['toggle_align'] ) ? $settings['toggle_align'] : '';

		// Labels Plural
			$label_years = !empty( $settings['label_years'] ) ? $settings['label_years'] : '';
			$label_months = !empty( $settings['label_months'] ) ? $settings['label_months'] : '';
			$label_weeks = !empty( $settings['label_weeks'] ) ? $settings['label_weeks'] : '';
			$label_days = !empty( $settings['label_days'] ) ? $settings['label_days'] : '';
			$label_hours = !empty( $settings['label_hours'] ) ? $settings['label_hours'] : '';
			$label_minutes = !empty( $settings['label_minutes'] ) ? $settings['label_minutes'] : '';
			$label_seconds = !empty( $settings['label_seconds'] ) ? $settings['label_seconds'] : '';

			$label_years = $label_years ? esc_html($label_years) : esc_html__('Years','events-addon-for-elementor');
			$label_months = $label_months ? esc_html($label_months) : esc_html__('Months','events-addon-for-elementor');
			$label_weeks = $label_weeks ? esc_html($label_weeks) : esc_html__('Weeks','events-addon-for-elementor');
			$label_days = $label_days ? esc_html($label_days) : esc_html__('Days','events-addon-for-elementor');
			$label_hours = $label_hours ? esc_html($label_hours) : esc_html__('Hours','events-addon-for-elementor');
			$label_minutes = $label_minutes ? esc_html($label_minutes) : esc_html__('Minutes','events-addon-for-elementor');
			$label_seconds = $label_seconds ? esc_html($label_seconds) : esc_html__('Seconds','events-addon-for-elementor');

		// Labels Singular
			$label_year = !empty( $settings['label_year'] ) ? $settings['label_year'] : '';
			$label_month = !empty( $settings['label_month'] ) ? $settings['label_month'] : '';
			$label_week = !empty( $settings['label_week'] ) ? $settings['label_week'] : '';
			$label_day = !empty( $settings['label_day'] ) ? $settings['label_day'] : '';
			$label_hour = !empty( $settings['label_hour'] ) ? $settings['label_hour'] : '';
			$label_minute = !empty( $settings['label_minute'] ) ? $settings['label_minute'] : '';
			$label_second = !empty( $settings['label_second'] ) ? $settings['label_second'] : '';

			$label_year = $label_year ? esc_html($label_year) : esc_html__('Year','events-addon-for-elementor');
			$label_month = $label_month ? esc_html($label_month) : esc_html__('Month','events-addon-for-elementor');
			$label_week = $label_week ? esc_html($label_week) : esc_html__('Week','events-addon-for-elementor');
			$label_day = $label_day ? esc_html($label_day) : esc_html__('Day','events-addon-for-elementor');
			$label_hour = $label_hour ? esc_html($label_hour) : esc_html__('Hour','events-addon-for-elementor');
			$label_minute = $label_minute ? esc_html($label_minute) : esc_html__('Minute','events-addon-for-elementor');
			$label_second = $label_second ? esc_html($label_second) : esc_html__('Second','events-addon-for-elementor');

		$countdown_format = $countdown_format ? $countdown_format : '';

		if ($count_type === 'fake') {
			$count_date_actual = $fake_date;
		} else {
			$count_date_actual = $count_date_static;
		}

		$conference_title = $conference_title ? '<h5>'.esc_html($conference_title).'</h5>' : '';

		if ($toggle_align) {
			$align_class = ' toggle-align';
			$f_class = ' order-1';
			$s_class = ' order-2';
		} else {
			$align_class = '';
			$f_class = '';
			$s_class = '';
		}

		// Turn output buffer on
		ob_start();	?>
			<div class="naeep-conference<?php echo esc_attr( $align_class ); ?>">
				<div class="col-na-row align-items-center">
					<?php
						$image_url = wp_get_attachment_url( $event_image );
						$image = $image_url ? '<div class="naeep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($event_title).'"></div>' : '';
						$link = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($event_title).'</a>' : esc_html($event_title);
				  	$title = !empty( $event_title ) ? '<h3 class="event-title">'.$link.'</h3>' : '';
						$content = $event_content ? '<p>'.esc_html($event_content).'</p>' : '';
						$location = $event_location ? '<li><i class="fa fa-map-marker" aria-hidden="true"></i> '.esc_html($event_location).'</li>' : '';
						$organizer = $event_organizer ? '<li><i class="fa fa-user" aria-hidden="true"></i> '.esc_html($event_organizer).'</li>' : '';
					?>
					<div class="col-na-6<?php echo esc_attr( $s_class ); ?>"><?php echo $image; ?></div>
			    <div class="col-na-6<?php echo esc_attr( $f_class ); ?>">
					  <div class="naeep-conference-item">
					  	<?php echo $conference_title.$title; ?>
					    <div class="naeep-conference-info"><?php echo $content; ?></div>
					    <?php if ($count_date_actual) { ?>
							<div class="naeep-countdown-wrap need-separator">
			          <div class="naeep-countdown <?php echo esc_attr( $count_type ); ?>" data-date="<?php echo esc_attr($count_date_actual); ?>" data-years="<?php echo esc_attr($label_years); ?>" data-months="<?php echo esc_attr($label_months); ?>" data-weeks="<?php echo esc_attr($label_weeks); ?>" data-days="<?php echo esc_attr($label_days); ?>" data-hours="<?php echo esc_attr($label_hours); ?>" data-minutes="<?php echo esc_attr($label_minutes); ?>" data-seconds="<?php echo esc_attr($label_seconds); ?>" data-year="<?php echo esc_attr($label_year); ?>" data-month="<?php echo esc_attr($label_month); ?>" data-week="<?php echo esc_attr($label_week); ?>" data-day="<?php echo esc_attr($label_day); ?>" data-hour="<?php echo esc_attr($label_hour); ?>" data-minute="<?php echo esc_attr($label_minute); ?>" data-second="<?php echo esc_attr($label_second); ?>" data-format="<?php echo esc_attr($countdown_format); ?>"><div class="clearfix"></div>
			          </div>
			        </div>
					    <?php } ?>
			        <ul><?php echo $location.$organizer; ?></ul>
					  </div>
					</div>
				</div>
	    </div>
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Conference() );

} // enable & disable