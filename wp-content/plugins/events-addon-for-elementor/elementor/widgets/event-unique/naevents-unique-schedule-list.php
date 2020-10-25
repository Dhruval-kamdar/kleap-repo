<?php
/*
 * Elementor Events Addon for Elementor Schedule List Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_schedule_list'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_ScheduleList extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_schedule_list';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Schedule List', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Schedule List widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_schedule',
			[
				'label' => __( 'Schedule List Item', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'schedule_style',
			[
				'label' => __( 'Schedule List Style', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'events-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'events-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three', 'events-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'events-addon-for-elementor' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'schedule_image',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'schedule_image2',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Works for Style Three', 'events-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'schedule_image3',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Works for Style Three', 'events-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'image_link',
			[
				'label' => esc_html__( 'Image Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'time_icon',
			[
				'label' => esc_html__( 'Time Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-clock-o',
			]
		);
		$repeater->add_control(
			'schedule_time',
			[
				'label' => esc_html__( 'Schedule Time', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type time text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_timeTwo',
			[
				'label' => esc_html__( 'Schedule End Time', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type time text here', 'events-addon-for-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Works for Style Three', 'events-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'schedule_title',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
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
		$repeater->add_control(
			'schedule_content',
			[
				'label' => esc_html__( 'Schedule Content', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'speaker_icon',
			[
				'label' => esc_html__( 'Speakers Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-user',
			]
		);
		$repeater->add_control(
			'schedule_speaker',
			[
				'label' => esc_html__( 'Speakers Name', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'speaker_link',
			[
				'label' => esc_html__( 'Speaker Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'venue_icon',
			[
				'label' => esc_html__( 'Venue Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-map-marker',
			]
		);
		$repeater->add_control(
			'schedule_venue',
			[
				'label' => esc_html__( 'Venue Name', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'venue_link',
			[
				'label' => esc_html__( 'Venue Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_more',
			[
				'label' => esc_html__( 'Read More Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'more_link',
			[
				'label' => esc_html__( 'Read More Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'more_icon',
			[
				'label' => esc_html__( 'More Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-map-marker',
			]
		);
		$repeater->add_control(
			'day_time',
			[
				'label' => esc_html__( 'Day Time', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'schedule_groups',
			[
				'label' => esc_html__( 'Schedule List Items', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'schedule_title' => esc_html__( 'Title', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ schedule_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'info_padding',
			[
				'label' => __( 'Section Spacing', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-schedule-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'info_margin',
			[
				'label' => __( 'Section Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-schedule-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-schedule-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'scn_style' );
			$this->start_controls_tab(
				'scn_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-list' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-schedule-list',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-schedule-list',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'scn_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_hover_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-list:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hover_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-schedule-list:hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hover_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-schedule-list:hover',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Metas
		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => esc_html__( 'Schedule Metas', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'schedule_style!' => array('three'),
				],
			]
		);
		$this->add_responsive_control(
			'meta_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .schedule-content ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .schedule-content ul li',
			]
		);
		$this->start_controls_tabs( 'meta_style' );
			$this->start_controls_tab(
				'meta_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'meta_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .schedule-content ul li, {{WRAPPER}} .schedule-content ul li a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'meta_icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .schedule-content ul li i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .schedule-content ul li:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'meta_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'meta_hover_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .schedule-content ul li a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_name_style',
			[
				'label' => esc_html__( 'Schedule Title', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .schedule-content h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .schedule-content h3',
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
						'{{WRAPPER}} .schedule-content h3, {{WRAPPER}} .schedule-content h3 a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .schedule-content h3 a:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .schedule-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .schedule-content p',
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
					'{{WRAPPER}} .schedule-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Day Time
		$this->start_controls_section(
			'section_time_style',
			[
				'label' => esc_html__( 'Day Time', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'time_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} span.day-time, {{WRAPPER}} .schedule-timing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'time_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} span.day-time, {{WRAPPER}} .schedule-timing',
			]
		);
		$this->add_control(
			'time_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} span.day-time, {{WRAPPER}} .schedule-timing' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'time_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} span.day-time, {{WRAPPER}} .schedule-timing' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Venue
		$this->start_controls_section(
			'section_venue_style',
			[
				'label' => esc_html__( 'Venue', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'schedule_style' => array('three'),
				],
			]
		);
		$this->add_responsive_control(
			'venue_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .schedule-venue' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'venue_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .schedule-venue',
			]
		);
		$this->add_control(
			'venue_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .schedule-venue' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'venue_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .schedule-venue' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Speaker
		$this->start_controls_section(
			'section_speaker_style',
			[
				'label' => esc_html__( 'Speaker', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'schedule_style' => array('three'),
				],
			]
		);
		$this->add_responsive_control(
			'speaker_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} ul.schedule-img-list li.speaker' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'speaker_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} ul.schedule-img-list li.speaker',
			]
		);
		$this->add_control(
			'speaker_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.schedule-img-list li.speaker' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Link
		$this->start_controls_section(
			'section_more_style',
			[
				'label' => esc_html__( 'Link', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'schedule_style!' => array('three'),
				],
			]
		);
		$this->add_responsive_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-link-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sasorganizer_more_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.naeep-link',
			]
		);
		$this->add_responsive_control(
			'link_width',
			[
				'label' => esc_html__( 'Link Width', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} a.naeep-link' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'organizer_more_style' );
			$this->start_controls_tab(
				'more_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'more_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} a.naeep-link' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'more_bg_color',
					'label' => __( 'Background Color', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} a.naeep-link',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'more_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'more_hov_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} a.naeep-link:hover' => 'color: {{VALUE}};',
					],
				]
			);			
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'more_bg_hov_color',
					'label' => __( 'Background Color', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} a.naeep-link:hover',
				]
			);
			$this->add_control(
				'more_hov_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} a.naeep-link:hover:before' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Schedule List widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Schedule List query
		$settings = $this->get_settings_for_display();
		$schedule_style = !empty( $settings['schedule_style'] ) ? $settings['schedule_style'] : '';
		$schedule = !empty( $settings['schedule_groups'] ) ? $settings['schedule_groups'] : '';

		if ($schedule_style === 'two') {
			$style_cls = ' style-two';
		} elseif ($schedule_style === 'three') {
			$style_cls = ' style-three';
		} else {
			$style_cls = '';
		}


		$output = '';
		$output .= '<div class="naeep-schedule-wrap'.$style_cls.'">';
			// Group Param Output
			foreach ( $schedule as $each_logo ) {
				$schedule_image = !empty( $each_logo['schedule_image']['id'] ) ? $each_logo['schedule_image']['id'] : '';
				$schedule_image2 = !empty( $each_logo['schedule_image2']['id'] ) ? $each_logo['schedule_image2']['id'] : '';
				$schedule_image3 = !empty( $each_logo['schedule_image3']['id'] ) ? $each_logo['schedule_image3']['id'] : '';
				$image_link = !empty( $each_logo['image_link']['url'] ) ? $each_logo['image_link']['url'] : '';
				$image_link_external = !empty( $each_logo['image_link']['is_external'] ) ? 'target="_blank"' : '';
				$image_link_nofollow = !empty( $each_logo['image_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$image_link_attr = !empty( $image_link ) ?  $image_link_external.' '.$image_link_nofollow : '';

				$day_time = !empty( $each_logo['day_time'] ) ? $each_logo['day_time'] : '';
				$schedule_title = !empty( $each_logo['schedule_title'] ) ? $each_logo['schedule_title'] : '';
		  	$title_link = !empty( $each_logo['title_link']['url'] ) ? $each_logo['title_link']['url'] : '';
				$title_link_external = !empty( $each_logo['title_link']['is_external'] ) ? 'target="_blank"' : '';
				$title_link_nofollow = !empty( $each_logo['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';

				$schedule_time = !empty( $each_logo['schedule_time'] ) ? $each_logo['schedule_time'] : '';
				$schedule_timeTwo = !empty( $each_logo['schedule_timeTwo'] ) ? $each_logo['schedule_timeTwo'] : '';

				$schedule_content = !empty( $each_logo['schedule_content'] ) ? $each_logo['schedule_content'] : '';

				$schedule_speaker = !empty( $each_logo['schedule_speaker'] ) ? $each_logo['schedule_speaker'] : '';
				$speaker_link = !empty( $each_logo['speaker_link']['url'] ) ? $each_logo['speaker_link']['url'] : '';
				$speaker_link_external = !empty( $each_logo['speaker_link']['is_external'] ) ? 'target="_blank"' : '';
				$speaker_link_nofollow = !empty( $each_logo['speaker_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$speaker_link_attr = !empty( $speaker_link ) ?  $speaker_link_external.' '.$speaker_link_nofollow : '';

				$schedule_venue = !empty( $each_logo['schedule_venue'] ) ? $each_logo['schedule_venue'] : '';
				$venue_link = !empty( $each_logo['venue_link']['url'] ) ? $each_logo['venue_link']['url'] : '';
				$venue_link_external = !empty( $each_logo['venue_link']['is_external'] ) ? 'target="_blank"' : '';
				$venue_link_nofollow = !empty( $each_logo['venue_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$venue_link_attr = !empty( $venue_link ) ?  $venue_link_external.' '.$venue_link_nofollow : '';

				$schedule_more = !empty( $each_logo['schedule_more'] ) ? $each_logo['schedule_more'] : '';
				$more_link = !empty( $each_logo['more_link']['url'] ) ? $each_logo['more_link']['url'] : '';
				$more_link_external = !empty( $each_logo['more_link']['is_external'] ) ? 'target="_blank"' : '';
				$more_link_nofollow = !empty( $each_logo['more_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$more_link_attr = !empty( $more_link ) ?  $more_link_external.' '.$more_link_nofollow : '';
				$more_icon = !empty( $each_logo['more_icon'] ) ? $each_logo['more_icon'] : '';
				$more_icon = $more_icon ? ' <i class="'.esc_attr($more_icon).'"></i>' : '';

				$time_icon = !empty( $each_logo['time_icon'] ) ? $each_logo['time_icon'] : '';
				$speaker_icon = !empty( $each_logo['speaker_icon'] ) ? $each_logo['speaker_icon'] : '';
				$venue_icon = !empty( $each_logo['venue_icon'] ) ? $each_logo['venue_icon'] : '';

				$time_icon = $time_icon ? '<i class="'.esc_attr($time_icon).'"></i> ' : '';
				$speaker_icon = $speaker_icon ? '<i class="'.esc_attr($speaker_icon).'"></i> ' : '';
				$venue_icon = $venue_icon ? '<i class="'.esc_attr($venue_icon).'"></i> ' : '';

				$image_url = wp_get_attachment_url( $schedule_image );
				$image_url2 = wp_get_attachment_url( $schedule_image2 );
				$image_url3 = wp_get_attachment_url( $schedule_image3 );
				$link_image = $image_link ? '<a href="'.esc_url($image_link).'" '.$image_link_attr.'><img src="'.esc_url($image_url).'" alt="'.esc_attr($schedule_title).'"></a>' : '<img src="'.esc_url($image_url).'" alt="'.esc_attr($schedule_title).'">';
				$image = $image_url ? '<div class="naeep-image">'.$link_image.'</div>' : '';

				$imageOne = $image_url ? '<li class="one"><div class="naeep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($schedule_title).'"></div></li>' : '';
				$imageTwo = $image_url2 ? '<li class="two"><div class="naeep-image"><img src="'.esc_url($image_url2).'" alt="'.esc_attr($schedule_title).'"></div></li>' : '';
				$imageThree = $image_url3 ? '<li class="three"><div class="naeep-image"><img src="'.esc_url($image_url3).'" alt="'.esc_attr($schedule_title).'"></div></li>' : '';

				$link_title = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($schedule_title).'</a>' : esc_html($schedule_title);
				$title = $schedule_title ? '<h3>'.$link_title.'</h3>' : '';

		  	$time = !empty( $schedule_time ) ? '<li>'.$time_icon.esc_html($schedule_time).'</li>' : '';
				$link_speaker = $speaker_link ? '<a href="'.esc_url($speaker_link).'" '.$speaker_link_attr.'>'.esc_html($schedule_speaker).'</a>' : esc_html($schedule_speaker);
				$speaker = $schedule_speaker ? '<li>'.$speaker_icon.$link_speaker.'</li>' : '';
				$link_venue = $venue_link ? '<a href="'.esc_url($venue_link).'" '.$venue_link_attr.'>'.esc_html($schedule_venue).'</a>' : esc_html($schedule_venue);
				$venue = $schedule_venue ? '<li>'.$venue_icon.$link_venue.'</li>' : '';
				$content = $schedule_content ? '<p>'.$schedule_content.'</p>' : '';
	  		$button = !empty($more_link) ? '<div class="naeep-link-wrap"><a href="'.esc_url($more_link).'" '.$more_link_attr.' class="naeep-link">'.esc_html($schedule_more).$more_icon.'</a></div>' : '';
				$day_time = $day_time ? '<span class="day-time">'.esc_html($day_time).'</span>' : '';
				if ($schedule_style === 'two') {
					$output .= '<div class="naeep-schedule-list">
				  							'.$day_time.'
				  							<div class="col-na-row align-items-center">
					  							<div class="col-na-2"><div class="schedule-image">'.$image.'</div></div>
					  							<div class="col-na-10"><div class="schedule-content">
					  							<ul>'.$time.$speaker.$venue.'</ul>
					  							'.$title.$content.'</div></div>
				  							</div>'.$button.'
									    </div>';
				} elseif ($schedule_style === 'three') {
					$output .= '<div class="naeep-schedule-list">
				  							<div class="col-na-row align-items-center">
					  							<div class="col-na-2">
					  								<div class="schedule-timing naeep-item">
						  								<div class="naeep-table-wrap"><div class="naeep-align-wrap">
						  								<span>'.$time_icon.esc_html($schedule_time).'</span> '.esc_html__( 'to', 'events-addon-for-elementor' ).' <span>'.$time_icon.esc_html($schedule_timeTwo).'</span>
						  								</div></div>
					  								</div>
					  							</div>
					  							<div class="col-na-7">
						  							<div class="schedule-content naeep-item">
						  								<div class="naeep-table-wrap"><div class="naeep-align-wrap">
							  							'.$title.$content.'
							  							<div class="schedule-venue">'.$venue_icon.$link_venue.'</div>
						  								</div></div>
						  							</div>
					  							</div>
					  							<div class="col-na-3">
					  								<div class="schedule-img-list-wrap naeep-item">
						  								<div class="naeep-table-wrap"><div class="naeep-align-wrap">
							  								<ul class="schedule-img-list">
							  									'.$imageOne.$imageTwo.$imageThree.'
							  									<li class="speaker">'.$speaker_icon.$link_speaker.'</li>
							  								</ul>
						  								</div></div>
					  								</div>
					  							</div>
				  							</div>
									    </div>';
				} else {
					$output .= '<div class="naeep-schedule-list">
				  							'.$day_time.'
				  							<div class="col-na-row align-items-center">
					  							<div class="col-na-2"><div class="schedule-image">'.$image.'</div></div>
					  							<div class="col-na-10"><div class="schedule-content">
					  							<ul>'.$time.$speaker.$venue.'</ul>
					  							'.$title.$content.$button.'</div></div>
				  							</div>
									    </div>';
				}			  
			}

		$output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_ScheduleList() );

} // enable & disable