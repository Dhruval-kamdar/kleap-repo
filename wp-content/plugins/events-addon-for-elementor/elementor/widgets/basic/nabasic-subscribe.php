<?php
/*
 * Elementor Events Addon for Elementor Subscribe Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_bw_settings' )['naeafe_subscribe_contact'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Subscribe extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_basic_subscribe';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Subscribe / Contact', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-mailchimp';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-basic-category'];
	}

	/**
	 * Register Events Addon for Elementor Subscribe widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_subscribe',
			[
				'label' => esc_html__( 'Subscribe Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'subscribe_title',
			[
				'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sign up to our newsletter', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subscribe_content',
			[
				'label' => esc_html__( 'Content Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Sign up to receive news and updates. Each week well send you a summary of the latest articles. Keep an eye on your inbox!', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type content text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subscribe_form',
			[
				'label' => esc_html__( 'Subscribe Form', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '[mc4wp_form id="40"]', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Section Alignment', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-subscribe' => 'text-align: {{VALUE}};',
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
		$this->add_responsive_control(
			'section_width',
			[
				'label' => esc_html__( 'Section Width', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-form-wrap' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'label_width',
			[
				'label' => esc_html__( 'Label Width', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-form form label' => 'max-width:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .naeep-form form label' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'section_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-form-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-form-wrap' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .naeep-form-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'section_box_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-form-wrap',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'section_box_shadow',
				'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-form-wrap',
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
			'title_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-subscribe h3',
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
					'{{WRAPPER}} .naeep-subscribe h3' => 'color: {{VALUE}};',
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
			'content_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
				'name' => 'content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .naeep-subscribe p',
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
					'{{WRAPPER}} .naeep-subscribe p' => 'color: {{VALUE}};',
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
		$this->add_responsive_control(
			'input_margin',
			[
				'label' => __( 'Text Field Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'textarea_margin',
			[
				'label' => __( 'Textarea Field Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'form_section_width',
			[
				'label' => esc_html__( 'Form Width', 'events-addon-for-elementor' ),
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
					'{{WRAPPER}} .naeep-subscribe-form' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'form_padding',
			[
				'label' => __( 'Form Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input[type="text"],
					{{WRAPPER}} .naeep-subscribe input[type="email"],
					{{WRAPPER}} .naeep-subscribe input[type="date"],
					{{WRAPPER}} .naeep-subscribe input[type="time"],
					{{WRAPPER}} .naeep-subscribe input[type="number"],
					{{WRAPPER}} .naeep-subscribe input[type="password"], 
					{{WRAPPER}} .naeep-subscribe input[type="tel"], 
					{{WRAPPER}} .naeep-subscribe input[type="search"], 
					{{WRAPPER}} .naeep-subscribe input[type="url"], 
					{{WRAPPER}} .naeep-subscribe textarea,
					{{WRAPPER}} .naeep-subscribe select,
					{{WRAPPER}} .naeep-subscribe .form-control,
					{{WRAPPER}} .naeep-subscribe .nice-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-subscribe input[type="text"],
				{{WRAPPER}} .naeep-subscribe input[type="email"],
				{{WRAPPER}} .naeep-subscribe input[type="date"],
				{{WRAPPER}} .naeep-subscribe input[type="time"],
				{{WRAPPER}} .naeep-subscribe input[type="number"],
				{{WRAPPER}} .naeep-subscribe input[type="password"], 
				{{WRAPPER}} .naeep-subscribe input[type="tel"], 
				{{WRAPPER}} .naeep-subscribe input[type="search"], 
				{{WRAPPER}} .naeep-subscribe input[type="url"], 
				{{WRAPPER}} .naeep-subscribe textarea,
				{{WRAPPER}} .naeep-subscribe select,
				{{WRAPPER}} .naeep-subscribe .form-control,
				{{WRAPPER}} .naeep-subscribe .nice-select',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-subscribe input[type="text"],
				{{WRAPPER}} .naeep-subscribe input[type="email"],
				{{WRAPPER}} .naeep-subscribe input[type="date"],
				{{WRAPPER}} .naeep-subscribe input[type="time"],
				{{WRAPPER}} .naeep-subscribe input[type="number"],
				{{WRAPPER}} .naeep-subscribe input[type="password"], 
				{{WRAPPER}} .naeep-subscribe input[type="tel"], 
				{{WRAPPER}} .naeep-subscribe input[type="search"], 
				{{WRAPPER}} .naeep-subscribe input[type="url"], 
				{{WRAPPER}} .naeep-subscribe textarea,
				{{WRAPPER}} .naeep-subscribe select,
				{{WRAPPER}} .naeep-subscribe .form-control,
				{{WRAPPER}} .naeep-subscribe .nice-select',
			]
		);
		$this->add_control(
			'placeholder_text_color',
			[
				'label' => __( 'Placeholder Text Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .naeep-subscribe textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input[type="text"],
					{{WRAPPER}} .naeep-subscribe input[type="email"],
					{{WRAPPER}} .naeep-subscribe input[type="date"],
					{{WRAPPER}} .naeep-subscribe input[type="time"],
					{{WRAPPER}} .naeep-subscribe input[type="number"],
					{{WRAPPER}} .naeep-subscribe input[type="password"], 
					{{WRAPPER}} .naeep-subscribe input[type="tel"], 
					{{WRAPPER}} .naeep-subscribe input[type="search"], 
					{{WRAPPER}} .naeep-subscribe input[type="url"], 
					{{WRAPPER}} .naeep-subscribe textarea,
					{{WRAPPER}} .naeep-subscribe select,
					{{WRAPPER}} .naeep-subscribe .form-control,
					{{WRAPPER}} .naeep-subscribe .nice-select' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'form_bg_color',
			[
				'label' => __( 'Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input[type="text"],
					{{WRAPPER}} .naeep-subscribe input[type="email"],
					{{WRAPPER}} .naeep-subscribe input[type="date"],
					{{WRAPPER}} .naeep-subscribe input[type="time"],
					{{WRAPPER}} .naeep-subscribe input[type="number"],
					{{WRAPPER}} .naeep-subscribe input[type="password"], 
					{{WRAPPER}} .naeep-subscribe input[type="tel"], 
					{{WRAPPER}} .naeep-subscribe input[type="search"], 
					{{WRAPPER}} .naeep-subscribe input[type="url"], 
					{{WRAPPER}} .naeep-subscribe textarea,
					{{WRAPPER}} .naeep-subscribe select,
					{{WRAPPER}} .naeep-subscribe .form-control,
					{{WRAPPER}} .naeep-subscribe .nice-select' => 'background-color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'form_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input[type="text"],
					{{WRAPPER}} .naeep-subscribe input[type="email"],
					{{WRAPPER}} .naeep-subscribe input[type="date"],
					{{WRAPPER}} .naeep-subscribe input[type="time"],
					{{WRAPPER}} .naeep-subscribe input[type="number"],
					{{WRAPPER}} .naeep-subscribe input[type="password"], 
					{{WRAPPER}} .naeep-subscribe input[type="tel"], 
					{{WRAPPER}} .naeep-subscribe input[type="search"], 
					{{WRAPPER}} .naeep-subscribe input[type="url"], 
					{{WRAPPER}} .naeep-subscribe textarea,
					{{WRAPPER}} .naeep-subscribe select,
					{{WRAPPER}} .naeep-subscribe .form-control,
					{{WRAPPER}} .naeep-subscribe .nice-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

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
				'selector' => '{{WRAPPER}} .naeep-subscribe input[type="submit"]',
			]
		);
		$this->add_responsive_control(
			'btn_width',
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
					'{{WRAPPER}} .naeep-subscribe input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-subscribe input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-subscribe input[type="submit"]' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-subscribe input[type="submit"]' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-subscribe input[type="submit"]',
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
						'{{WRAPPER}} .naeep-subscribe input[type="submit"]:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-subscribe input[type="submit"]:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_hover_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-subscribe input[type="submit"]:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Subscribe widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$subscribe_title = !empty( $settings['subscribe_title'] ) ? $settings['subscribe_title'] : '';
		$subscribe_content = !empty( $settings['subscribe_content'] ) ? $settings['subscribe_content'] : '';
		$subscribe_form = !empty( $settings['subscribe_form'] ) ? $settings['subscribe_form'] : '';

		$title = $subscribe_title ? '<h3>'.esc_html($subscribe_title).'</h3>' : '';
		$content = $subscribe_content ? '<p>'.esc_html($subscribe_content).'</p>' : '';

		// Starts
		$output  = '<div class="naeep-subscribe naeep-form"><div class="naeep-form-wrap">'.$title.$content.'<div class="naeep-subscribe-form">';
		$output .= do_shortcode( $subscribe_form );
		$output .= '</div></div></div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Subscribe() );

} // enable & disable