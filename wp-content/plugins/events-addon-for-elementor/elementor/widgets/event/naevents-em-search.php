<?php
/*
 * Elementor Events Addon for Elementor Events Manager Search Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_event_search'])) { // enable & disable

if ( is_plugin_active( 'events-manager/events-manager.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventsManagerSearch extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_em_search';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Event Search', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-search';
		}

		/**
		 * Retrieve the list of form the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-em-category'];
		}

		/**
		 * Register Events Addon for Elementor Events Manager Search widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Search Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'form_title',
				[
					'label' => esc_html__( 'Search Title', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'form_content',
				[
					'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->end_controls_section();// end: Section

			// Search
			$this->start_controls_section(
				'section_form_style',
				[
					'label' => esc_html__( 'Search', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'form_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-em-search input[type="text"],
					{{WRAPPER}} .naeep-em-search input[type="email"],
					{{WRAPPER}} .naeep-em-search input[type="date"],
					{{WRAPPER}} .naeep-em-search input[type="time"],
					{{WRAPPER}} .naeep-em-search input[type="number"],
					{{WRAPPER}} .naeep-em-search textarea,
					{{WRAPPER}} .naeep-em-search select,
					{{WRAPPER}} .naeep-em-search .form-control,
					{{WRAPPER}} .naeep-em-search .nice-select',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-em-search input[type="text"],
					{{WRAPPER}} .naeep-em-search input[type="email"],
					{{WRAPPER}} .naeep-em-search input[type="date"],
					{{WRAPPER}} .naeep-em-search input[type="time"],
					{{WRAPPER}} .naeep-em-search input[type="number"],
					{{WRAPPER}} .naeep-em-search textarea,
					{{WRAPPER}} .naeep-em-search select,
					{{WRAPPER}} .naeep-em-search .form-control,
					{{WRAPPER}} .naeep-em-search .nice-select',
				]
			);
			$this->add_control(
				'placeholder_text_color',
				[
					'label' => __( 'Placeholder Text Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-search input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naeep-em-search textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-search input[type="text"],
						{{WRAPPER}} .naeep-em-search input[type="email"],
						{{WRAPPER}} .naeep-em-search input[type="date"],
						{{WRAPPER}} .naeep-em-search input[type="time"],
						{{WRAPPER}} .naeep-em-search input[type="number"],
						{{WRAPPER}} .naeep-em-search textarea,
						{{WRAPPER}} .naeep-em-search select,
						{{WRAPPER}} .naeep-em-search .form-control,
						{{WRAPPER}} .naeep-em-search,
						{{WRAPPER}} .naeep-em-search .nice-select' => 'color: {{VALUE}} !important;',
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
			$this->add_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-em-search h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-em-search h3',
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
						'{{WRAPPER}} .naeep-em-search h3' => 'color: {{VALUE}};',
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
			$this->add_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-em-search p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-em-search p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-em-search p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Events Manager Search widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$form_title = !empty( $settings['form_title'] ) ? $settings['form_title'] : '';
			$form_content = !empty( $settings['form_content'] ) ? $settings['form_content'] : '';
			$title = $form_title ? '<h3>'.$form_title.'</h3>' : '';
			$content = $form_content ? '<p>'.$form_content.'</p>' : '';

	  	$output = '<div class="naeep-form naeep-em-search">'.$title.$content.do_shortcode( '[event_search_form]' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventsManagerSearch() );
}

} // enable & disable