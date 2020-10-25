<?php
/*
 * Elementor Events Addon for Elementor Accommodation Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_accommodation'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Accommodation extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_accommodation';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Accommodation', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-menu-card';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Accommodation widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_venue',
			[
				'label' => __( 'Accommodation Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'venue_style',
			[
				'label' => __( 'Accommodation Style', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'events-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'events-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your venue style.', 'events-addon-for-elementor' ),
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
				'condition' => [
					'venue_style!' => array('two'),
				],
			]
		);
		$this->add_control(
			'venue_image',
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
					'{{WRAPPER}} .naeep-venue-item .naeep-image' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'venue_style!' => array('two'),
				],
			]
		);
		$this->add_control(
			'venue_title',
			[
				'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Walt Disney World Dolphin', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'venue_title_link',
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
			'venue_price',
			[
				'label' => esc_html__( 'Price Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$ 123.00', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'venue_price_duration',
			[
				'label' => esc_html__( 'Price Duration Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '/ Night', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'venue_style!' => array('two'),
				],
			]
		);
		$this->add_control(
			'venue_content',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__( 'This is Content text', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type your content text here', 'events-addon-for-elementor' ),
				'condition' => [
					'venue_style!' => array('two'),
				],
			]
		);
		$this->add_control(
			'rating',
			[
				'label' => esc_html__( 'Rating', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'default' => 3,
				'step' => 1,
			]
		);
		$this->add_control(
			'venue_location',
			[
				'label' => esc_html__( 'Location', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'venue_style' => array('two'),
				],
			]
		);
		$this->add_control(
			'venue_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type button text here', 'events-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'venue_style!' => array('two'),
				],
			]
		);
		$this->add_control(
			'venue_btn_link',
			[
				'label' => esc_html__( 'Button Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'venue_style!' => array('two'),
				],
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
					'{{WRAPPER}} .naeep-venue-item .venue-info-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'venue_style!' => array('two'),
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'section_box_style',
				[
					'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-venue-item, {{WRAPPER}} .naeep-venue-item.style-two .naeep-image:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'section_bg_hover_color',
				[
					'label' => esc_html__( 'Background Hover Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-venue-item.style-two:hover .naeep-image:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_box_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-venue-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-venue-item',
				]
			);
			$this->end_controls_section();// end: Section

		// Image
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Image', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'venue_style!' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'venue_image_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-venue-item .naeep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-venue-item .naeep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-venue-item .naeep-image img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-venue-item .naeep-image img',
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
			$this->add_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .venue-info-wrap h3',
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
							'{{WRAPPER}} .venue-info-wrap h3, {{WRAPPER}} .venue-info-wrap h3 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .venue-info-wrap h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Price
			$this->start_controls_section(
				'section_price_style',
				[
					'label' => esc_html__( 'Price', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'price_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap h5, {{WRAPPER}} .naeep-venue-item.style-two h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'price_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .venue-info-wrap h5, {{WRAPPER}} .naeep-venue-item.style-two h5',
				]
			);
			$this->add_control(
				'price_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap h5, {{WRAPPER}} .naeep-venue-item.style-two h5' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'price_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-venue-item.style-two h5' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'venue_style' => array('two'),
					],
				]
			);
			$this->add_control(
				'price_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-venue-item.style-two h5' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .naeep-venue-item.style-two h5 span.bottom:before,
						{{WRAPPER}} .naeep-venue-item.style-two h5 span.bottom:after,
						{{WRAPPER}} .naeep-venue-item.style-two h5 span.top:after,
						{{WRAPPER}} .naeep-venue-item.style-two h5 span.top:before' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'venue_style' => array('two'),
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Price Duration
			$this->start_controls_section(
				'section_pricedur_style',
				[
					'label' => esc_html__( 'Price Duration', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'venue_style!' => array('two'),
					],
				]
			);
			$this->add_control(
				'pricedur_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap h5 span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'pricedur_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .venue-info-wrap h5 span',
				]
			);
			$this->add_control(
				'pricedur_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap h5 span' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Rating
			$this->start_controls_section(
				'section_rating_style',
				[
					'label' => esc_html__( 'Rating', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'rating_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-rating i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'sasstp_rating_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .naeep-rating',
				]
			);
			$this->start_controls_tabs( 'rating_style' );
				$this->start_controls_tab(
					'rating_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'rating_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-rating' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'rating_hover',
					[
						'label' => esc_html__( 'Active', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'rating_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-rating i.active' => 'color: {{VALUE}};',
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
					'condition' => [
						'venue_style!' => array('two'),
					],
				]
			);
			$this->add_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .venue-info-wrap p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .venue-info-wrap p',
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
						'{{WRAPPER}} .venue-info-wrap p' => 'color: {{VALUE}};',
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
						'venue_style!' => array('two'),
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
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$venue_style = !empty( $settings['venue_style'] ) ? $settings['venue_style'] : '';
		$venue_image = !empty( $settings['venue_image']['id'] ) ? $settings['venue_image']['id'] : '';
		$venue_title = !empty( $settings['venue_title'] ) ? $settings['venue_title'] : '';
		$venue_title_link = !empty( $settings['venue_title_link']['url'] ) ? $settings['venue_title_link']['url'] : '';
		$venue_title_link_external = !empty( $settings['venue_title_link']['is_external'] ) ? 'target="_blank"' : '';
		$venue_title_link_nofollow = !empty( $settings['venue_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$venue_title_link_attr = !empty( $venue_title_link ) ?  $venue_title_link_external.' '.$venue_title_link_nofollow : '';
		$venue_price = !empty( $settings['venue_price'] ) ? $settings['venue_price'] : '';
		$venue_price_duration = !empty( $settings['venue_price_duration'] ) ? $settings['venue_price_duration'] : '';
		$venue_content = !empty( $settings['venue_content'] ) ? $settings['venue_content'] : '';
		$rating = !empty( $settings['rating'] ) ? $settings['rating'] : '';
		$venue_location = !empty( $settings['venue_location'] ) ? $settings['venue_location'] : '';
		$venue_btn_text = !empty( $settings['venue_btn_text'] ) ? $settings['venue_btn_text'] : '';
		$venue_btn_link = !empty( $settings['venue_btn_link']['url'] ) ? $settings['venue_btn_link']['url'] : '';
		$venue_btn_link_external = !empty( $settings['venue_btn_link']['is_external'] ) ? 'target="_blank"' : '';
		$venue_btn_link_nofollow = !empty( $settings['venue_btn_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$venue_btn_link_attr = !empty( $venue_btn_link ) ?  $venue_btn_link_external.' '.$venue_btn_link_nofollow : '';
		$toggle_align = !empty( $settings['toggle_align'] ) ? $settings['toggle_align'] : '';

		if ($toggle_align) {
			$f_class = ' order-1';
			$s_class = ' order-2';
		} else {
			$f_class = '';
			$s_class = '';
		}

		// Image
		$image_url = wp_get_attachment_url( $venue_image );
		$image = $image_url ? '<div class="naeep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($venue_title).'"></div>' : '';
		$imagetwo = $image_url ? '<img src="'.esc_url($image_url).'" alt="'.esc_attr($venue_title).'">' : '';

		$title_link = $venue_title_link ? '<a href="'.esc_url($venue_title_link).'" '.$venue_title_link_attr.'>'.esc_html($venue_title).'</a>' : $venue_title;
		$title = $venue_title ? '<h3 class="venue-title">'.$title_link.'</h3>' : '';
		$duration = $venue_price_duration ? '<span>'.esc_html($venue_price_duration).'</span>' : '';
		$price = $venue_price ? '<h5>'.esc_html($venue_price).$duration.'</h5>' : '';
		$pricetwo = $venue_price ? '<h5><span class="top"></span>'.esc_html($venue_price).$duration.'<span class="bottom"></span></h5>' : '';
		$location = $venue_location ? '<h4><i class="fa fa-map-marker" aria-hidden="true"></i> '.esc_html($venue_location).'</h4>' : '';
		$content = $venue_content ? $venue_content : '';
		$venue_btn = $venue_btn_link ? '<div class="naeep-link-wrap"><a href="'.esc_url($venue_btn_link).'" class="naeep-link" '.$venue_btn_link_attr.'>'.esc_html($venue_btn_text).'</a></div>' : '';

		if ($venue_style === 'two') {
			$output = '<div class="naeep-venue-item style-two">
									<div class="naeep-image">'.$imagetwo.$pricetwo.'
										<div class="venue-info-wrap">
											<div class="naeep-rating">';
			                  for( $i=1; $i<= $rating; $i++) {
			                    $output .= '<i class="fa fa-star active" aria-hidden="true"></i>';
			                  }
			                  for( $i=5; $i > $rating; $i--) {
			                    $output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
			                  }
					$output .= '</div>'.$title.$location.'
										</div>
									</div>
								</div>';
		} else {
			$output = '<div class="naeep-venue-item">
									<div class="col-na-row align-items-center">
										<div class="col-na-6'.$s_class.'">'.$image.'</div>
										<div class="col-na-6'.$f_class.'">
											<div class="venue-info-wrap">'.$price.$title.'<div class="naeep-rating">';
			                  for( $i=1; $i<= $rating; $i++) {
			                    $output .= '<i class="fa fa-star active" aria-hidden="true"></i>';
			                  }
			                  for( $i=5; $i > $rating; $i--) {
			                    $output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
			                  }
						$output .= '</div>'.$content.$venue_btn.'</div>
										</div>
									</div>
								</div>';
		}

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Accommodation() );

} // enable & disable