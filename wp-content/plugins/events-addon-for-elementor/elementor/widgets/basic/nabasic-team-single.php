<?php
/*
 * Elementor Events Addon for Elementor Team Single Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_bw_settings' )['naeafe_team_single'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Team_Single extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_basic_team_single';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Team Single', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-basic-category'];
	}

	/**
	 * Register Events Addon for Elementor Team Single widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_team',
			[
				'label' => __( 'Team Single Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_responsive_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'unset' => [
						'title' => esc_html__( 'Top', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-up',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-circle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'events-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-down',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .naeep-team-single-item' => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'image_after',
			[
				'label' => esc_html__( 'Hide Image Shape?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
				'return_value' => 'true',
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
		$this->add_control(
			'full_width',
			[
				'label' => esc_html__( 'Full Width?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'team_image',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
			]
		);
		$this->add_responsive_control(
			'image_alignment',
			[
				'label' => esc_html__( 'Image Alignment', 'events-addon-for-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .naeep-team-single-item .naeep-image' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'team_title',
			[
				'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'William Smith', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'team_title_link',
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
			'team_subtitle',
			[
				'label' => esc_html__( 'Sub Title Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'CEO/ Founder', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne = new Repeater();
		$repeaterOne->add_control(
			'list_title',
			[
				'label' => esc_html__( 'List Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'list_text',
			[
				'label' => esc_html__( 'List Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'text_link',
			[
				'label' => esc_html__( 'Text Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'infoList_groups',
			[
				'label' => esc_html__( 'Team Mate Informations', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeaterOne->get_controls(),
				'title_field' => '{{{ list_text }}}',
			]
		);
		$this->add_control(
			'team_content',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type your content here', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-facebook-square',
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
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'Social Iocns', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ social_icon }}}',
			]
		);
		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'events-addon-for-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .naeep-team-single-item .single-mate-info' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'full_width' => 'true',
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
					'{{WRAPPER}} .naeep-team-single-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-team-single-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'max_width',
			[
				'label' => esc_html__( 'Section Width', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-team-single-item' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'section_style' );
			$this->start_controls_tab(
				'section_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-team-single-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_box_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'section_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'section_hov_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-team-single-item:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_hov_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item:hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_hov_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Image', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'team_image_padding',
			[
				'label' => __( 'Image Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-team-single-item .naeep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'all_content_padding',
			[
				'label' => __( 'Content Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-mate-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'image_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-team-single-item .naeep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image img',
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
				'image_hover_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:hover img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hov_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:hover img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hov_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:hover img',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Image Border
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image Border', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'image_after!' => 'true',
				],
			]
		);
		$this->start_controls_tabs( 'bdr_style' );
			$this->start_controls_tab(
				'bdr_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_responsive_control(
				'border_top',
				[
					'label' => esc_html__( 'Border Top', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -200,
							'max' => 200,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .image-wrap:after' => 'top:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'border_left',
				[
					'label' => esc_html__( 'Border Left', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -200,
							'max' => 200,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .image-wrap:after' => 'left:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'img_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'img_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:after',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'img_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:after',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'bdr_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_responsive_control(
				'border_top_hover',
				[
					'label' => esc_html__( 'Border Top', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -200,
							'max' => 200,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .image-wrap:hover:after' => 'top:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'border_left_hover',
				[
					'label' => esc_html__( 'Border Left', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -200,
							'max' => 200,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .image-wrap:hover:after' => 'left:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'img_hov_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:hover:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'img_hov_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:hover:after',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'img_hov_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-team-single-item .naeep-image .image-wrap:hover:after',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-mate-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .single-mate-info h3',
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
						'{{WRAPPER}} .single-mate-info h3, {{WRAPPER}} .single-mate-info h3 a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .single-mate-info h3 a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Sub Title
		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__( 'Sub Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'subtitle_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-mate-info h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'subtitle_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .single-mate-info h5',
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
					'{{WRAPPER}} .single-mate-info h5' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// List
		$this->start_controls_section(
			'section_list_style',
			[
				'label' => esc_html__( 'List', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'list_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-mate-info ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'lbt',
			[
				'label' => __( 'List Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Title Typography', 'events-addon-for-elementor' ),
				'name' => 'sasstp_list_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .single-mate-info ul li span',
			]
		);
		$this->add_control(
			'list_title_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .single-mate-info ul li span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'lbgt',
			[
				'label' => __( 'List Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Text Typography', 'events-addon-for-elementor' ),
				'name' => 'sasstp_list_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .single-mate-info ul li',
			]
		);
		$this->start_controls_tabs( 'list_style' );
			$this->start_controls_tab(
				'list_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'list_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .single-mate-info ul li, {{WRAPPER}} .single-mate-info ul li a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'list_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'list_hover_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .single-mate-info ul li a:hover' => 'color: {{VALUE}};',
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
		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-mate-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .single-mate-info p',
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
					'{{WRAPPER}} .single-mate-info p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Social Icons', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
					'icon_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-social a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-social a' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-social a',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'icon_hover',
				[
					'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-social a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_hov',
				[
					'label' => esc_html__( 'Background Hover Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-social a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_hover_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-social a:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-social a' => 'font-size:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Width', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-social a' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$team_image = !empty( $settings['team_image']['id'] ) ? $settings['team_image']['id'] : '';
		$team_title = !empty( $settings['team_title'] ) ? $settings['team_title'] : '';
		$team_title_link = !empty( $settings['team_title_link']['url'] ) ? $settings['team_title_link']['url'] : '';
		$team_title_link_external = !empty( $settings['team_title_link']['is_external'] ) ? 'target="_blank"' : '';
		$team_title_link_nofollow = !empty( $settings['team_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$team_title_link_attr = !empty( $team_title_link ) ?  $team_title_link_external.' '.$team_title_link_nofollow : '';
		$team_subtitle = !empty( $settings['team_subtitle'] ) ? $settings['team_subtitle'] : '';
		$infoList_groups = !empty( $settings['infoList_groups'] ) ? $settings['infoList_groups'] : '';
		$team_content = !empty( $settings['team_content'] ) ? $settings['team_content'] : '';
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		$toggle_align = !empty( $settings['toggle_align'] ) ? $settings['toggle_align'] : '';
		$full_width = !empty( $settings['full_width'] ) ? $settings['full_width'] : '';
		$image_after = !empty( $settings['image_after'] ) ? $settings['image_after'] : '';

		if ($toggle_align) {
			$f_class = ' order-1';
			$s_class = ' order-2';
		} else {
			$f_class = '';
			$s_class = '';
		}

		if ($full_width) {
			$col_class = ' full-width';
		} else {
			$col_class = '';
		}

		if ($image_after) {
			$img_class = ' hide-shape';
		} else {
			$img_class = '';
		}

		// Image
		$image_url = wp_get_attachment_url( $team_image );
		$image = $image_url ? '<div class="naeep-image"><div class="image-wrap'.esc_attr($img_class).'"><img src="'.esc_url($image_url).'" alt="'.esc_attr($team_title).'"></div></div>' : '';

		$title_link = $team_title_link ? '<a href="'.esc_url($team_title_link).'" '.$team_title_link_attr.'>'.esc_html($team_title).'</a>' : esc_html($team_title);
		$title = $team_title ? '<h3 class="team-title">'.$title_link.'</h3>' : '';
		$subtitle = $team_subtitle ? '<h5>'.esc_html($team_subtitle).'</h5>' : '';
		$content = $team_content ? '<p>'.esc_html($team_content).'</p>' : '';

		$output = '<div class="naeep-team-single-wrap">
								<div class="naeep-team-single-item'.esc_attr($col_class).'">
								<div class="single-mate-image'.esc_attr($s_class).'">'.$image.'</div>
								<div class="single-mate-info'.esc_attr($f_class).'">
									'.$title.$subtitle.'
									<ul>';
										// Group Param Output
										if ( is_array( $infoList_groups ) && !empty( $infoList_groups ) ){
										  foreach ( $infoList_groups as $each_list ) {
										  $list_title = !empty( $each_list['list_title'] ) ? $each_list['list_title'] : '';
										  $list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';

										  $text_link = !empty( $each_list['text_link']['url'] ) ? $each_list['text_link']['url'] : '';
											$text_link_external = !empty( $each_list['text_link']['is_external'] ) ? 'target="_blank"' : '';
											$text_link_nofollow = !empty( $each_list['text_link']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link_attr = !empty( $text_link ) ?  $text_link_external.' '.$text_link_nofollow : '';

											$list_title = $list_title ? '<span>'.esc_html($list_title).'</span>' : '';
											$list_link = $text_link ? '<a href="'.esc_url($text_link).'" '.$text_link_attr.'>'.esc_html($list_text).'</a>' : $list_text;

									  	$output .= '<li>'.$list_title.$list_link.'</li>';
										} }
		$output .= '</ul>'.$content.'<div class="naeep-social rounded">';
									// Group Param Output
									if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
									  foreach ( $listItems_groups as $each_list ) {
									  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';

										$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
										$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
										$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
										$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';

									  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
										$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';

									  $output .= '<a href="'.esc_url($link_url).'" '.$link_attr.'>'.$icon.'</a>';
									} }
		$output .= '</div>
							</div>
							</div>
						</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Team_Single() );

} // enable & disable