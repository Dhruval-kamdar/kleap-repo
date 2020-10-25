<?php
/*
 * Elementor Events Addon for Elementor Services Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_bw_settings' )['naeafe_services'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Services extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_basic_services';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Services', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-settings';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-basic-category'];
	}

	/**
	 * Register Events Addon for Elementor Services widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_services',
			[
				'label' => __( 'Services Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'service_style',
			[
				'label' => __( 'Services Style', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'events-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'events-addon-for-elementor' ),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'upload_type',
			[
				'label' => __( 'Icon Type', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'events-addon-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'events-addon-for-elementor' ),
				],
				'default' => 'image',
			]
		);
		$this->add_control(
			'service_image',
			[
				'label' => esc_html__( 'Upload Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'upload_type' => 'image',
				],
				'frontend_available' => true,
				'description' => esc_html__( 'Set your icon image.', 'events-addon-for-elementor'),
			]
		);
		$this->add_control(
			'img_link',
			[
				'label' => esc_html__( 'Image Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'upload_type' => 'image',
				],
			]
		);
		$this->add_responsive_control(
			'img_width',
			[
				'label' => esc_html__( 'Image Width', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'condition' => [
					'upload_type' => 'image',
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-service-item .naeep-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'service_icon',
			[
				'label' => esc_html__( 'Sub Title Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-cog',
				'condition' => [
					'upload_type' => 'icon',
				],
			]
		);
		$this->add_control(
			'services_title',
			[
				'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Goal Setting', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'services_title_link',
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
			'services_content',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'default' => esc_html__( 'The road and back again your heart has oure a pal and anfis confidant.', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type your content here', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$this->add_control(
			'services_more',
			[
				'label' => esc_html__( 'Read More Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'services_more_link',
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
			'need_hover',
			[
				'label' => esc_html__( 'Need Hover Effect?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'need_border',
			[
				'label' => esc_html__( 'Need Animated Border?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon/Image Position', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-up',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-left',
					],
					'none' => [
						'title' => esc_html__( 'None', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-circle',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-right',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-down',
					],
				],
				'condition' => [
					'service_style' => 'one',
				],
				'label_block' => true,
				'default' => 'none',
			]
		);
		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Alignment', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-service-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'service_section_margin',
			[
				'label' => __( 'Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-service-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'service_section_padding',
			[
				'label' => __( 'Section Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-service-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'service_info_padding',
			[
				'label' => __( 'Content Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .service-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'service_bg_image',
			[
				'label' => esc_html__( 'Background Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your background image.', 'events-addon-for-elementor'),
				'selectors' => [
					'{{WRAPPER}} .naeep-service-item' => 'background-image: url({{url}});',
				],
			]
		);
		$this->start_controls_tabs( 'secn_style' );
			$this->start_controls_tab(
				'secn_normal',
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
						'{{WRAPPER}} .naeep-service-item, {{WRAPPER}} .naeep-service-item.with-bg:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-service-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-service-item',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'secn_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_nrml_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item.naeep-hover h3,
						 {{WRAPPER}} .naeep-service-item.naeep-hover a,
						 {{WRAPPER}} .naeep-service-item.naeep-hover .naeep-icon i,
						 {{WRAPPER}} .naeep-service-item.naeep-hover p,
						 {{WRAPPER}} .naeep-service-item.naeep-hover' => 'color: {{VALUE}};',
						 '{{WRAPPER}} .naeep-service-item.naeep-hover .naeep-link:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_bdrg_color',
				[
					'label' => esc_html__( 'Hover Border Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item.service-border:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item.naeep-hover, {{WRAPPER}} .naeep-service-item.with-bg.naeep-hover:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hov_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-service-item.naeep-hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hov_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-service-item.naeep-hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'upload_type' => array('image'),
				],
			]
		);
		$this->add_control(
			'image_padding',
			[
				'label' => __( 'Image Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-service-item .naeep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'upload_type' => array('icon'),
				],
			]
		);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item .naeep-icon i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'icon_bgcolor',
					'label' => __( 'Background Color', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-service-item .naeep-icon',
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
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item .naeep-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_wheight',
				[
					'label' => esc_html__( 'Icon width & Height', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item .naeep-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_lheight',
				[
					'label' => esc_html__( 'Line Height', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-service-item .naeep-icon i' => 'line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-service-item .naeep-icon',
					'condition' => [
						'service_style' => 'two',
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
						'{{WRAPPER}} .naeep-service-item .naeep-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-service-item h3',
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
						'{{WRAPPER}} .naeep-service-item h3, {{WRAPPER}} .naeep-service-item h3 a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .naeep-service-item h3 a:hover' => 'color: {{VALUE}};',
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
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-service-item p',
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
						'{{WRAPPER}} .naeep-service-item p' => 'color: {{VALUE}};',
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
						'value' => Scheme_Color::COLOR_4,
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
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
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
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$icon_position = !empty( $settings['icon_position'] ) ? $settings['icon_position'] : '';
		$service_style = !empty( $settings['service_style'] ) ? $settings['service_style'] : '';
		$upload_type = !empty( $settings['upload_type'] ) ? $settings['upload_type'] : '';
		$service_image = !empty( $settings['service_image']['id'] ) ? $settings['service_image']['id'] : '';
		$img_link = !empty( $settings['img_link']['url'] ) ? $settings['img_link']['url'] : '';
		$img_link_external = !empty( $settings['img_link']['is_external'] ) ? 'target="_blank"' : '';
		$img_link_nofollow = !empty( $settings['img_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$img_link_attr = !empty( $img_link ) ?  $img_link_external.' '.$img_link_nofollow : '';
		$service_icon = !empty( $settings['service_icon'] ) ? $settings['service_icon'] : '';
		$services_title = !empty( $settings['services_title'] ) ? $settings['services_title'] : '';
		$services_title_link = !empty( $settings['services_title_link']['url'] ) ? $settings['services_title_link']['url'] : '';
		$services_title_link_external = !empty( $settings['services_title_link']['is_external'] ) ? 'target="_blank"' : '';
		$services_title_link_nofollow = !empty( $settings['services_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$services_title_link_attr = !empty( $services_title_link ) ?  $services_title_link_external.' '.$services_title_link_nofollow : '';
		$services_content = !empty( $settings['services_content'] ) ? $settings['services_content'] : '';
		$need_hover = !empty( $settings['need_hover'] ) ? $settings['need_hover'] : '';
		$need_border = !empty( $settings['need_border'] ) ? $settings['need_border'] : '';
		$section_alignment = !empty( $settings['section_alignment'] ) ? $settings['section_alignment'] : '';
		$services_more = !empty( $settings['services_more'] ) ? $settings['services_more'] : '';
		$services_more_link = !empty( $settings['services_more_link'] ) ? $settings['services_more_link'] : '';
		$more_link_url = !empty( $services_more_link['url'] ) ? esc_url($services_more_link['url']) : '';
		$more_link_external = !empty( $services_more_link['is_external'] ) ? 'target="_blank"' : '';
		$more_link_nofollow = !empty( $services_more_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$more_link_attr = !empty( $services_more_link['url'] ) ?  $more_link_external.' '.$more_link_nofollow : '';
		$service_bg_image = !empty( $settings['service_bg_image']['id'] ) ? $settings['service_bg_image']['id'] : '';

		if ($need_hover) {
			$hover_cls = ' service-hover';
		} else {
			$hover_cls = '';
		}
		if ($service_bg_image) {
			$bg_cls = ' with-bg';
		} else {
			$bg_cls = '';
		}

		if($service_style === 'two') {
			$service_style_cls = ' service-style-two';
		} else {
			$service_style_cls = ' service-style-one';
		}

		if ($section_alignment === 'left') {
			$salign_class = ' service-left';
		} elseif ($section_alignment === 'right') {
			$salign_class = ' service-right';
		} else {
			$salign_class = ' service-center';
		}

		if ($need_border) {
			$border_cls = ' service-border';
		} else {
			$border_cls = '';
		}
		// Image
		$image_url = wp_get_attachment_url( $service_image );
		$naevents_alt = get_post_meta($service_image, '_wp_attachment_image_alt', true);
		
		$image_link = $img_link ? '<a href="'.esc_url($img_link).'" '.$img_link_attr.'><img src="'.esc_url($image_url).'" alt="'.esc_attr($services_title).'"></a>' : '<img src="'.esc_url($image_url).'" alt="'.esc_attr($services_title).'">';
		$services_image = $image_url ? '<div class="naeep-image">'.$image_link.'</div>' : '';
		$services_icon = $service_icon ? '<div class="naeep-icon"><i class="'.esc_attr($service_icon).'"></i></div>' : '';

		if ($upload_type === 'icon'){
		  $icon_main = $services_icon;
		  $img_class = '';
		} else {
		  $icon_main = $services_image;
		  $img_class = ' have-img';
		}
		$title_link = $services_title_link ? '<a href="'.esc_url($services_title_link).'" '.$services_title_link_attr.'>'.esc_html($services_title).'</a>' : esc_html($services_title);
		$title = $services_title ? '<h3 class="service-title">'.$title_link.'</h3>' : '';
		$content = $services_content ? '<p>'.esc_html($services_content).'</p>' : '';
	  $button = !empty($more_link_url) ? '<div class="naeep-link-wrap"><a href="'.esc_url($more_link_url).'" '.$more_link_attr.' class="naeep-link">'.esc_html($services_more).'</a></div>' : '';
	  if($service_style === 'two') {
	  	$style_cls = '';
	  } else {
			if ($icon_position === 'top'){
			  $style_cls = ' icon-top';
			} elseif ($icon_position === 'bottom'){
			  $style_cls = ' icon-bottom';
			} elseif ($icon_position === 'left'){
			  $style_cls = ' icon-left';
			} elseif ($icon_position === 'right'){
			  $style_cls = ' icon-right';
			} else {
			  $style_cls = '';
			}
	  }

		$output = '<div class="naeep-service-item'.$service_style_cls.$style_cls.$hover_cls.$border_cls.$salign_class.$img_class.$bg_cls.'">'.$icon_main.'<div class="service-info">'.$title.$content.$button.'</div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Services() );

} // enable & disable