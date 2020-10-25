<?php
/*
 * Elementor Events Addon for Elementor Event Organiser Subscribe Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_subscribe'])) { // enable & disable

if ( is_plugin_active( 'event-organiser/event-organiser.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventOrganiserSubscribe extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_eo_subscribe';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events Subscribe', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-mail';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-eo-category'];
		}

		/**
		 * Register Events Addon for Elementor Event Organiser Subscribe widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Subscribe Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_title',
				[
					'label' => esc_html__( 'Button Title', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Subscribe to Events', 'events-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'btn_class',
				[
					'label' => esc_html__( 'Button Class', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type class text here', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Use <b>naeep-btn</b> class for NicheAddon style.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_id',
				[
					'label' => esc_html__( 'Button ID', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type ID text here', 'events-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'btn_style',
				[
					'label' => esc_html__( 'Button Style', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one' => esc_html__( 'Style One (Text)', 'events-addon-for-elementor' ),
						'two' => esc_html__( 'Style Two (Image)', 'events-addon-for-elementor' ),
					],
					'default' => 'one',
					'description' => esc_html__( 'Select your button style.', 'events-addon-for-elementor' ),

				]
			);
			$this->add_control(
				'btn_image',
				[
					'label' => esc_html__( 'Button Image', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::MEDIA,
					'frontend_available' => true,
					'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
					'condition' => [
						'btn_style' => array('two'),
					],
				]
			);
			$this->add_control(
				'btn_text',
				[
					'label' => esc_html__( 'Button Text', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Subscribe to Events', 'events-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type button text here', 'events-addon-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'btn_style!' => array('two'),
					],
				]
			);
			$this->add_control(
				'event_category',
				[
					'label' => __( 'Certain Categories?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-category'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'event_venue',
				[
					'label' => __( 'Certain Venues?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-venue'),
					'multiple' => true,
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
						'btn_style' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'image_width',
				[
					'label' => esc_html__( 'Image Width', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-eo-subscribe img' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_image_margin',
				[
					'label' => __( 'Margin', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-eo-subscribe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-eo-subscribe img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-eo-subscribe img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-eo-subscribe img',
				]
			);
			$this->end_controls_section();// end: Section

			// Button
			$this->start_controls_section(
				'section_button_style',
				[
					'label' => esc_html__( 'Button', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'btn_style!' => array('two'),
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .naeep-eo-subscribe a',
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
						'{{WRAPPER}} .naeep-eo-subscribe a' => 'min-width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-eo-subscribe a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-eo-subscribe a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .naeep-eo-subscribe a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-eo-subscribe a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-eo-subscribe a',
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
							'{{WRAPPER}} .naeep-eo-subscribe a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-eo-subscribe a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-eo-subscribe a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event Organiser Subscribe widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$btn_title 			= !empty( $settings['btn_title'] ) ? $settings['btn_title'] : '';
			$btn_class 		= !empty( $settings['btn_class'] ) ? $settings['btn_class'] : '';
			$btn_id 			= !empty( $settings['btn_id'] ) ? $settings['btn_id'] : '';
			$btn_style 	= !empty( $settings['btn_style'] ) ? $settings['btn_style'] : '';
			$btn_image 	= !empty( $settings['btn_image']['id'] ) ? $settings['btn_image']['id'] : '';
			$btn_text 	= !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
			$event_category 	= !empty( $settings['event_category'] ) ? $settings['event_category'] : '';
			$event_venue 	= !empty( $settings['event_venue'] ) ? $settings['event_venue'] : '';

			$image_url = wp_get_attachment_url( $btn_image );
			$image = $image_url ? '<img src="'.$image_url.'" alt="Subscribe">' : '';

			if ($btn_style === 'two') {
				$btn = $image;
			} else {
				$btn = $btn_text;
			}

			$title = $btn_title ? ' title="'.$btn_title.'"' : '';
			$class = $btn_class ? ' class="'.$btn_class.'"' : '';
			$id = $btn_id ? ' id="'.$btn_id.'"' : '';
			$event_venue = $event_venue ? ' venue="'.implode(',', $event_venue).'"' : '';
			$category = $event_category ? ' category="'.implode(',', $event_category).'"' : '';

	  	$output = '<div class="naeep-eo-subscribe">'.do_shortcode( '[eo_subscribe'. $title . $class . $id . $event_venue . $category .' type="google"] '.$btn.' [/eo_subscribe]' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventOrganiserSubscribe() );
}

} // enable & disable