<?php
/*
 * Elementor Events Addon for Elementor Unique Upcoming Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_upcoming'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Upcoming extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_upcoming';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Upcoming Events', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Upcoming widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_upcoming_settings',
			[
				'label' => esc_html__( 'Upcoming Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'upcoming_style',
			[
				'label' => __( 'Upcoming Events Style', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'events-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'events-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your upcoming style.', 'events-addon-for-elementor' ),
			]
		);
		$repeater = new Repeater();
			$repeater->add_control(
				'upcoming_image',
				[
					'label' => esc_html__( 'Background Image', 'restaurant-cafe-addon-for-elementor' ),
					'type' => Controls_Manager::MEDIA,
					'frontend_available' => true,
					'description' => esc_html__( 'Set your image.', 'restaurant-cafe-addon-for-elementor'),
				]
			);
			$repeater->add_control(
				'upcoming_title',
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
				'upcoming_time',
				[
					'label' => esc_html__( 'Upcoming Time', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type subtitle text here', 'events-addon-for-elementor' ),
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
				]
			);
			$repeater->add_control(
				'upcoming_speaker',
				[
					'label' => esc_html__( 'Speakers Name', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'speaker_icon',
				[
					'label' => esc_html__( 'Speaker Icon', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
				]
			);
			$repeater->add_control(
				'upcoming_venue',
				[
					'label' => esc_html__( 'Venue Name', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
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
				]
			);
			$this->add_control(
				'eventItem_groups',
				[
					'label' => esc_html__( 'Upcoming Items', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'upcoming_title' => esc_html__( 'Events', 'events-addon-for-elementor' ),
						],

					],
					'fields' => $repeater->get_controls(),
					'title_field' => '{{{ upcoming_title }}}',
					'condition' => [
						'upcoming_style' => array('one'),
					],
				]
			);

		$repeaterTwo = new Repeater();
		$repeaterTwo->add_control(
			'event_date',
			[
				'label' => esc_html__( 'Event Date ', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'dateFormat' => 'M d, Y',
					'enableTime' => 'false',
				],
				'placeholder' => esc_html__( 'Aug 15, 2019', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterTwo->add_control(
			'date_title',
			[
				'label' => esc_html__( 'Day Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterTwo->add_control(
			'upcoming_title',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterTwo->add_control(
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
		$repeaterTwo->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type subtitle text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterTwo->add_control(
			'upcoming_venue',
			[
				'label' => esc_html__( 'Venue Name', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterTwo->add_control(
			'more_icon',
			[
				'label' => esc_html__( 'Read More Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-long-arrow-right',
			]
		);
		$repeaterTwo->add_control(
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
		$this->add_control(
			'eventItemtwo_groups',
			[
				'label' => esc_html__( 'Upcoming Items', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'upcoming_title' => esc_html__( 'Events', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeaterTwo->get_controls(),
				'title_field' => '{{{ upcoming_title }}}',
				'condition' => [
					'upcoming_style' => array('two'),
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'info_padding',
				[
					'label' => __( 'Section Spacing', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-day' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .naeep-upcoming-day' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-day',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-day',
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
							'{{WRAPPER}} .naeep-upcoming-day.naeep-hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-day.naeep-hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_hover_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-day.naeep-hover',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectno_style',
				[
					'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('one'),
					],
				]
			);
			$this->add_responsive_control(
				'infoo_padding',
				[
					'label' => __( 'Section Spacing', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'scno_style' );
				$this->start_controls_tab(
					'scno_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'secno_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-upcoming-item, {{WRAPPER}} .naeep-upcoming-item:before' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secno_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secno_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-item',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'scno_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'secno_hover_title_color',
					[
						'label' => esc_html__( 'Title Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-upcoming-item:hover h3, {{WRAPPER}} .naeep-upcoming-item:hover h3 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'secno_hover_td_color',
					[
						'label' => esc_html__( 'Time / Date Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-upcoming-item:hover span.time, {{WRAPPER}} .naeep-upcoming-day:hover span.date' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'secno_hover_venu_color',
					[
						'label' => esc_html__( 'Venue Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-upcoming-item:hover span.venue, {{WRAPPER}} .naeep-upcoming-day:hover span.venue' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'secno_hover_og_color',
					[
						'label' => esc_html__( 'Organizer Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-upcoming-item:hover h5' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'secno_hover_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-upcoming-item:hover, {{WRAPPER}} .naeep-upcoming-item:hover:before' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secno_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-item:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secno_hover_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-upcoming-item:hover',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Day
			$this->start_controls_section(
				'section_day_style',
				[
					'label' => esc_html__( 'Day', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'day_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-day h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'day_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-upcoming-day h2',
				]
			);
			$this->add_control(
				'day_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-day h2' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Day Title
			$this->start_controls_section(
				'section_daytitle_style',
				[
					'label' => esc_html__( 'Day Title', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'daytitle_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-day h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'daytitle_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .naeep-upcoming-day h5',
				]
			);
			$this->add_control(
				'daytitle_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-day h5' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_name_style',
				[
					'label' => esc_html__( 'Event Title', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-upcoming-item h3, {{WRAPPER}} .naeep-upcoming-day h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'name_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-upcoming-item h3, {{WRAPPER}} .naeep-upcoming-day h3',
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
							'{{WRAPPER}} .naeep-upcoming-item h3, {{WRAPPER}} .naeep-upcoming-item h3 a, {{WRAPPER}} .naeep-upcoming-day h3, {{WRAPPER}} .naeep-upcoming-day h3 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .naeep-upcoming-item h3 a:hover, {{WRAPPER}} .naeep-upcoming-day h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Time
			$this->start_controls_section(
				'section_time_style',
				[
					'label' => esc_html__( 'Time / Date', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-upcoming-item span.time, {{WRAPPER}} .naeep-upcoming-day span.date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'time_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-upcoming-item span.time, {{WRAPPER}} .naeep-upcoming-day span.date',
				]
			);
			$this->add_control(
				'time_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item span.time, {{WRAPPER}} .naeep-upcoming-day span.date' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'time_icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item span.time i, {{WRAPPER}} .naeep-upcoming-day span.date i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Organizer
			$this->start_controls_section(
				'section_upcoming_style',
				[
					'label' => esc_html__( 'Organizer', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('one'),
					],
				]
			);
			$this->add_responsive_control(
				'upcoming_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'upcoming_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-upcoming-item h5',
				]
			);
			$this->add_control(
				'upcoming_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item h5' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'upcoming_icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item h5 i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-day p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-upcoming-day p',
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
						'{{WRAPPER}} .naeep-upcoming-day p' => 'color: {{VALUE}};',
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
				]
			);
			$this->add_responsive_control(
				'venue_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item span.venue, {{WRAPPER}} .naeep-upcoming-day span.venue' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'venue_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-upcoming-item span.venue, {{WRAPPER}} .naeep-upcoming-day span.venue',
				]
			);
			$this->add_control(
				'venue_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-upcoming-item span.venue, {{WRAPPER}} .naeep-upcoming-day span.venue' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upcoming_style' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'btn_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_width',
				[
					'label' => esc_html__( 'Button Width', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-btn' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'btn_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .naeep-btn',
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
							'{{WRAPPER}} .naeep-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-btn',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Upcoming widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Upcoming query
		$upcoming_style = !empty( $settings['upcoming_style'] ) ? $settings['upcoming_style'] : '';
		$eventItem = !empty( $settings['eventItem_groups'] ) ? $settings['eventItem_groups'] : '';
		$eventItemtwo = !empty( $settings['eventItemtwo_groups'] ) ? $settings['eventItemtwo_groups'] : '';

		// Turn output buffer on
		ob_start();
		if ($upcoming_style === 'two') { ?>
			<div class="naeep-upcoming">
				<div class="col-na-row">
				<?php
				// Group Param Output
				foreach ( $eventItemtwo as $each_logo ) {

					$event_date = !empty( $each_logo['event_date'] ) ? $each_logo['event_date'] : '';
					$date_title = !empty( $each_logo['date_title'] ) ? $each_logo['date_title'] : '';
					$upcoming_title = !empty( $each_logo['upcoming_title'] ) ? $each_logo['upcoming_title'] : '';
			  	$title_link = !empty( $each_logo['title_link']['url'] ) ? $each_logo['title_link']['url'] : '';
					$title_link_external = !empty( $each_logo['title_link']['is_external'] ) ? 'target="_blank"' : '';
					$title_link_nofollow = !empty( $each_logo['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
					$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';
					$content = !empty( $each_logo['content'] ) ? $each_logo['content'] : '';
					$upcoming_venue = !empty( $each_logo['upcoming_venue'] ) ? $each_logo['upcoming_venue'] : '';

					$more_icon = !empty( $each_logo['more_icon'] ) ? $each_logo['more_icon'] : '';
			  	$more_link = !empty( $each_logo['more_link']['url'] ) ? $each_logo['more_link']['url'] : '';
					$more_link_external = !empty( $each_logo['more_link']['is_external'] ) ? 'target="_blank"' : '';
					$more_link_nofollow = !empty( $each_logo['more_link']['nofollow'] ) ? 'rel="nofollow"' : '';
					$more_link_attr = !empty( $more_link ) ?  $more_link_external.' '.$more_link_nofollow : '';

					$then = $event_date;
					$then = strtotime($then);
					$now = time();
					$difference = $now - $then;
					$days = floor($difference / (60*60*24) );

					$days = $days*(-1);

					if ($days <= 0) {
						$days_final = 0;
					} else {
						$days_final = ($days < 10) ? ('0'.$days) : $days;
					}

			  	$event_day = $event_date ? '<h2>'.esc_html($days_final).'</h2>' : '';
			  	$date = $event_date ? '<span class="date"><i class="fa fa-clock-o" aria-hidden="true"></i> '.esc_html($event_date).'</span>' : '';
			  	$date_title = $date_title ? '<h5>'.esc_html($date_title).'</h5>' : '';

					$link_title = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($upcoming_title).'</a>' : esc_html($upcoming_title);
					$title = $upcoming_title ? '<h3 class="upcoming-title">'.$link_title.'</h3>' : '';

			  	$content = $content ? '<p>'.esc_html($content).'</p>' : '';
					$venue = $upcoming_venue ? '<span class="venue"><i class="fa fa-map-marker" aria-hidden="true"></i> '.esc_html($upcoming_venue).'</span>' : '';
					$icon = $more_icon ? '<i class="'.esc_attr($more_icon).'" aria-hidden="true"></i>' : '';
		  		$button = $more_link ? '<div class="naeep-btn-wrap"><a href="'.esc_url($more_link).'" '.$more_link_attr.' class="naeep-btn">'.$icon.'</a></div>' : '';
					?>
					<div class="col-na-4">
						<div class="naeep-upcoming-day">
							<?php echo $event_day.$date_title.$title.$date.$content.$venue.$button; ?>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		<?php } else { ?>
			<div class="naeep-upcoming">
				<?php
				// Group Param Output
				foreach ( $eventItem as $each_logo ) {
					$upcoming_image = !empty( $each_logo['upcoming_image']['id'] ) ? $each_logo['upcoming_image']['id'] : '';
					$image_url = wp_get_attachment_url( $upcoming_image );
					$upcoming_title = !empty( $each_logo['upcoming_title'] ) ? $each_logo['upcoming_title'] : '';
			  	$title_link = !empty( $each_logo['title_link']['url'] ) ? $each_logo['title_link']['url'] : '';
					$title_link_external = !empty( $each_logo['title_link']['is_external'] ) ? 'target="_blank"' : '';
					$title_link_nofollow = !empty( $each_logo['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
					$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';
					$upcoming_time = !empty( $each_logo['upcoming_time'] ) ? $each_logo['upcoming_time'] : '';
					$upcoming_speaker = !empty( $each_logo['upcoming_speaker'] ) ? $each_logo['upcoming_speaker'] : '';
					$upcoming_venue = !empty( $each_logo['upcoming_venue'] ) ? $each_logo['upcoming_venue'] : '';
					$time_icon = !empty( $each_logo['time_icon'] ) ? $each_logo['time_icon'] : '';
					$speaker_icon = !empty( $each_logo['speaker_icon'] ) ? $each_logo['speaker_icon'] : '';
					$venue_icon = !empty( $each_logo['venue_icon'] ) ? $each_logo['venue_icon'] : '';

					$time_icon = $time_icon ? '<i class="'.esc_attr($time_icon).'"></i> ' : '';
					$speaker_icon = $speaker_icon ? '<i class="'.esc_attr($speaker_icon).'"></i> ' : '';
					$venue_icon = $venue_icon ? '<i class="'.esc_attr($venue_icon).'"></i> ' : '';					

					$link_title = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($upcoming_title).'</a>' : esc_html($upcoming_title);
					$title = $upcoming_title ? '<h3 class="upcoming-title">'.$link_title.'</h3>' : '';
			  	$time = !empty( $upcoming_time ) ? '<span class="time">'.$time_icon.esc_html($upcoming_time).'</span>' : '';
					$speaker = $upcoming_speaker ? '<h5>'.$speaker_icon.esc_html($upcoming_speaker).'</h5>' : '';
					$venue = $upcoming_venue ? '<span class="venue">'.$venue_icon.esc_html($upcoming_venue).'</span>' : '';
					$bg_img = $image_url ? ' style="background-image: url('. esc_url($image_url).');"' : '';
					if ($image_url) {
						$bg_class = ' hav-bg';
					} else {
						$bg_class = '';
					}

					?>
					<div class="naeep-upcoming-item<?php echo esc_attr($bg_class); ?>"<?php echo $bg_img;?>>
						<div class="col-na-row align-items-center">
							<div class="col-na-5">
								<?php echo $title; ?>
							</div>
							<div class="col-na-7">
								<div class="col-na-row align-items-center">
									<div class="col-na-4"><?php echo $time; ?></div>
									<div class="col-na-4"><?php echo $speaker; ?></div>
									<div class="col-na-4"><?php echo $venue; ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php }
		// Return outbut buffer
		echo ob_get_clean();

	}

	/**
	 * Render Upcoming widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	*/

	//protected function _content_template(){}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Upcoming() );

} // enable & disable