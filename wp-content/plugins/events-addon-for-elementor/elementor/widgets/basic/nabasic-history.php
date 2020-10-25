<?php
/*
 * Elementor Events Addon for Elementor History Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_bw_settings' )['naeafe_history'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_History extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_basic_history';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'History', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-history';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-basic-category'];
	}

	/**
	 * Register Events Addon for Elementor History widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_history',
			[
				'label' => __( 'History Item', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'history_style',
			[
				'label' => __( 'History Style', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Vertical Style', 'events-addon-for-elementor' ),
					'vertical' => esc_html__( 'Vertical Style Two', 'events-addon-for-elementor' ),
					'verticalTwo' => esc_html__( 'Vertical Style Three', 'events-addon-for-elementor' ),
					'verticalThree' => esc_html__( 'Vertical Style Four', 'events-addon-for-elementor' ),
					'two' => esc_html__( 'Horizontal Style', 'events-addon-for-elementor' ),
				],
				'default' => 'default',
			]
		);

		$repeater = new Repeater();
			$repeater->add_control(
				'history_image',
				[
					'label' => esc_html__( 'Upload Icon', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::MEDIA,
					'frontend_available' => true,
					'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
				]
			);
			$repeater->add_control(
				'image_title',
				[
					'label' => esc_html__( 'Image Title', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type year text here', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => esc_html__( 'Works for Vertical Style Four', 'events-addon-for-elementor'),
				]
			);
			$repeater->add_control(
				'need_hover',
				[
					'label' => esc_html__( 'Need Image Hover?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'need_popup',
				[
					'label' => esc_html__( 'Need Icon On Hover?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'popup_icon',
				[
					'label' => esc_html__( 'Popup Icon', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-search',
					'condition' => [
						'need_popup' => 'true',
					],
				]
			);
			$repeater->add_control(
				'pop_icon_style',
				[
					'label' => __( 'Icon Style', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one' 			=> esc_html__( 'Image Popup', 'events-addon-for-elementor' ),
						'two' 			=> esc_html__( 'Custom Link', 'events-addon-for-elementor' ),
					],
					'default' => 'one',
					'condition' => [
						'need_popup' => 'true',
					],
				]
			);
			$repeater->add_control(
				'icon_link',
				[
					'label' => esc_html__( 'Icon Link', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'pop_icon_style' => 'two',
					],
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
				'history_year',
				[
					'label' => esc_html__( 'Time', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '2019', 'events-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type year text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'history_title',
				[
					'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Title', 'events-addon-for-elementor' ),
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
				'history_content',
				[
					'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'history_more',
				[
					'label' => esc_html__( 'Read More Text', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Read More', 'events-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'history_more_link',
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
				'historyItem_groups',
				[
					'label' => esc_html__( 'History Items', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'history_title' => esc_html__( 'Title', 'events-addon-for-elementor' ),
						],

					],
					'fields' => $repeater->get_controls(),
					'title_field' => '{{{ history_title }}}',
				]
			);

		$this->end_controls_section();// end: Section

		// Section Item
		$this->start_controls_section(
			'section_three_style',
			[
				'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'lines',
			[
				'label' => __( 'Lines', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'vline_color',
			[
				'label' => esc_html__( 'Line Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-history-wrap.history-two .naeep-history-item .history-info:after, {{WRAPPER}} .naeep-history-wrap:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'vline_width',
			[
				'label' => esc_html__( 'Line Size', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-history-wrap.history-two .naeep-history-item .history-info:after' => 'height:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .naeep-history-wrap:before' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'circles',
			[
				'label' => __( 'Circles', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'circle_color',
			[
				'label' => esc_html__( 'Circle Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-history-item span' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'circle_bdr_color',
			[
				'label' => esc_html__( 'Circle Border Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-history-item span' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'circle_hover_color',
			[
				'label' => esc_html__( 'Circle Hover Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-history-item:hover .history-info span' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'circle_rip_color',
			[
				'label' => esc_html__( 'Pulse Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-history-item span, {{WRAPPER}} .naeep-history-item span:before, {{WRAPPER}} .naeep-history-item span:after' => 'box-shadow: 0 0 0 0 {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'circle_width',
			[
				'label' => esc_html__( 'Circle Size', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-history-item span, {{WRAPPER}} .naeep-history-item span:before, {{WRAPPER}} .naeep-history-item span:after' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Image', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .history-image .naeep-image.naeep-popup:after' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .history-image .naeep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .history-image .naeep-image img',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .history-image .naeep-image img',
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'need_popup' => 'true',
				],
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .history-image .naeep-image.naeep-popup a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_width',
			[
				'label' => esc_html__( 'Icon Width/Height', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .history-image .naeep-image.naeep-popup a' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .history-image .naeep-image.naeep-popup a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
				'ico_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .history-image .naeep-image.naeep-popup a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bgcolor',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .history-image .naeep-image.naeep-popup a' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'ico_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .history-image .naeep-image.naeep-popup a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_hover_bgcolor',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .history-image .naeep-image.naeep-popup a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Vertical Style Four
		$this->start_controls_section(
			'section_secthree_style',
			[
				'label' => esc_html__( 'Vertical Style Four', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'history_style' => array('verticalThree'),
				],
			]
		);
		$this->add_responsive_control(
			'history_secthree_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .history-info-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'secthree_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .history-info-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'secthree_color',
			[
				'label' => esc_html__( 'Border Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .history-info-inner' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'secthree_btop_color',
			[
				'label' => esc_html__( 'Border Top Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .history-info-inner' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section


		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'history_title_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .history-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sastool_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .history-info h3',
			]
		);
		$this->start_controls_tabs( 'title_style' );
			$this->start_controls_tab(
				'title_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .history-info h3, {{WRAPPER}} .history-info h3 a' => 'color: {{VALUE}};',
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
				'title_hover_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .history-info h3 a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Year
		$this->start_controls_section(
			'section_year_style',
			[
				'label' => esc_html__( 'Year', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'year_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .history-info h5' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'history_year_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .history-info h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sastool_year_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .history-info h5, {{WRAPPER}} .history-vertical .history-image h5',
			]
		);
		$this->add_control(
			'year_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .history-info h5, {{WRAPPER}} .history-vertical .history-image h5' => 'color: {{VALUE}};',
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
			]
		);
		$this->add_responsive_control(
			'history_cont_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .history-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sastool_content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .history-info p',
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
					'{{WRAPPER}} .history-info p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Image Title
		$this->start_controls_section(
			'section_imgtitle_style',
			[
				'label' => esc_html__( 'Image Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'history_style' => array('verticalThree'),
				],
			]
		);
		$this->add_responsive_control(
			'history_imgtitle_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} h3.image-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sastool_imgtitle_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} h3.image-title',
			]
		);
		$this->add_control(
			'imgtitle_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h3.image-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Link
		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => esc_html__( 'Link', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-link-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'btn_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .naeep-link',
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
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-link' => 'color: {{VALUE}};',
					],
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
						'{{WRAPPER}} .naeep-link:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_hover_color',
				[
					'label' => esc_html__( 'Line Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-link:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render History widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// History query
		$settings = $this->get_settings_for_display();
		$historyItem = $this->get_settings_for_display( 'historyItem_groups' );
		$history_style = !empty( $settings['history_style'] ) ? $settings['history_style'] : '';

		if ($history_style === 'two') {
			$style_class = ' history-two';
			$height_class = ' naeep-item';
		} elseif ($history_style === 'vertical') {
			$style_class = ' history-vertical';
			$height_class = ' naeep-item';
		} elseif ($history_style === 'verticalTwo') {
			$style_class = ' style-two';
			$height_class = ' naeep-item';
		} elseif ($history_style === 'verticalThree') {
			$style_class = ' style-three';
			$height_class = ' naeep-item';
		} else {
			$style_class = '';
			$height_class = '';
		}

		$output = '';

		$output .= '<div class="naeep-history-wrap'.esc_attr($style_class).'">';
			// Group Param Output
			foreach ( $historyItem as $each_logo ) {
				$history_image = !empty( $each_logo['history_image']['id'] ) ? $each_logo['history_image']['id'] : '';
				$image_title = !empty( $each_logo['image_title'] ) ? $each_logo['image_title'] : '';
				$time_icon = !empty( $each_logo['time_icon'] ) ? $each_logo['time_icon'] : '';
				$history_year = !empty( $each_logo['history_year'] ) ? $each_logo['history_year'] : '';
				$history_title = !empty( $each_logo['history_title'] ) ? $each_logo['history_title'] : '';
				$title_link = !empty( $each_logo['title_link']['url'] ) ? $each_logo['title_link']['url'] : '';
				$title_link_external = !empty( $each_logo['title_link']['is_external'] ) ? 'target="_blank"' : '';
				$title_link_nofollow = !empty( $each_logo['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';
				$history_content = !empty( $each_logo['history_content'] ) ? $each_logo['history_content'] : '';

				$need_hover = !empty( $each_logo['need_hover'] ) ? $each_logo['need_hover'] : '';
				$need_popup = !empty( $each_logo['need_popup'] ) ? $each_logo['need_popup'] : '';
				$popup_icon = !empty( $each_logo['popup_icon'] ) ? $each_logo['popup_icon'] : '';
				$pop_icon_style = !empty( $each_logo['pop_icon_style'] ) ? $each_logo['pop_icon_style'] : '';
				$icon_link = !empty( $each_logo['icon_link']['url'] ) ? $each_logo['icon_link']['url'] : '';
				$icon_link_external = !empty( $each_logo['icon_link']['is_external'] ) ? 'target="_blank"' : '';
				$icon_link_nofollow = !empty( $each_logo['icon_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$icon_link_attr = !empty( $icon_link ) ?  $icon_link_external.' '.$icon_link_nofollow : '';

				$history_more = !empty( $each_logo['history_more'] ) ? $each_logo['history_more'] : '';
				$history_more_link = !empty( $each_logo['history_more_link'] ) ? $each_logo['history_more_link'] : '';
				$more_link_url = !empty( $history_more_link['url'] ) ? esc_url($history_more_link['url']) : '';
				$more_link_external = !empty( $history_more_link['is_external'] ) ? 'target="_blank"' : '';
				$more_link_nofollow = !empty( $history_more_link['nofollow'] ) ? 'rel="nofollow"' : '';
				$more_link_attr = !empty( $history_more_link['url'] ) ?  $more_link_external.' '.$more_link_nofollow : '';

				$link = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($history_title).'</a>' : esc_html($history_title);
		  	$title = !empty( $history_title ) ? '<h3 class="history-title">'.$link.'</h3>' : '';
				$time_ico = $time_icon ? '<i class="'.esc_attr($time_icon).'" aria-hidden="true"></i> ' : '';
		  	$year = !empty( $history_year ) ? '<h5>'.$time_ico.esc_html($history_year).'</h5>' : '';
				$content = $history_content ? '<p>'.esc_html($history_content).'</p>' : '';
	  		$button = !empty($more_link_url) ? '<div class="naeep-link-wrap"><a href="'.esc_url($more_link_url).'" '.$more_link_attr.' class="naeep-link">'.esc_html($history_more).'</a></div>' : '';

				if ($need_hover) {
					$hover_class = ' hover-image';
				} else {
					$hover_class = '';
				}
				if ($need_popup) {
					$popup_class = ' naeep-popup';
				} else {
					$popup_class = '';
				}

				$image_url = wp_get_attachment_url( $history_image );
				$icon = $popup_icon ? '<i class="'.esc_attr($popup_icon).'" aria-hidden="true"></i>' : '';

				$image_pop = ($need_popup && $image_url) ? '<a href="'. esc_url($image_url) .'">'.$icon.'</a>' : '';
				$icon_link = $icon_link ? '<a href="'.esc_url($icon_link).'" '.$icon_link_attr.'>'.$icon.'</a>' : '';

				if ($pop_icon_style === 'two') {
					$icon_popup = $icon_link;
				} else {
					$icon_popup = $image_pop;
				}
				$history_image = $image_url ? '<div class="naeep-image'.esc_attr($popup_class.$hover_class).'"><img src="'.esc_url($image_url).'" alt="'.esc_attr($history_title).'">'.$icon_popup.'</div>' : '';
		  	$image_title = !empty( $image_title ) ? '<h3 class="image-title">'.$image_title.'</h3>' : '';

				if ($history_style === 'vertical') {
					$output .= '<div class="naeep-history-item"><div class="history-info'.esc_attr($height_class).'"><span></span>'.$title.$content.'</div><div class="history-image">'.$year.'</div></div>';
				} elseif ($history_style === 'verticalTwo') {
					$output .= '<div class="naeep-history-item">
												<div class="history-info'.esc_attr($height_class).'">
													<span></span>
													'.$title.$year.$content.$button.'
												</div>
												<div class="history-image">'.$history_image.'</div>
											</div>';
				} elseif ($history_style === 'verticalThree') {
					$output .= '<div class="naeep-history-item">
												<div class="history-info'.esc_attr($height_class).'">
													<div class="history-info-inner">
														<span></span>
														'.$year.$title.$content.$button.'
													</div>
												</div>
												<div class="history-image">'.$history_image.$image_title.'</div>
											</div>';
				} else {
			  	$output .= '<div class="naeep-history-item"><div class="history-info'.esc_attr($height_class).'"><span></span>'.$year.$title.$content.$button.'</div><div class="history-image">'.$history_image.'</div></div>';
				}

			}

		$output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_History() );

} // enable & disable