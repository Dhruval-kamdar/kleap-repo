<?php
/*
 * Elementor Events Addon for Elementor TEC Calendar Button Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_calendar_button'])) { // enable & disable

if ( is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_TEC_CalendarButton extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_tec_calendar_button';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Calendar Button', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-button';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-tec-category'];
		}

		/**
		 * Register Events Addon for Elementor TEC Calendar Button widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$args = array(
	    'post_type' => 'tribe_events',
	    'posts_per_page' => -1,
	    );
	    $pages = get_posts($args);
	    $event_post = array();
	    if ( $pages ) {
	      foreach ( $pages as $page ) {
	        $event_post[ $page->ID ] = $page->post_title;
	      }
	    } else {
	      $event_post[ esc_html__( 'No Events found', 'events-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'calendar_button_date',
				[
					'label' => esc_html__( 'Calendar Button', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'button_type',
				[
					'label' => __( 'Calendar Button Type', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'static' => esc_html__( 'Static Button', 'events-addon-for-elementor' ),
						'google' => esc_html__( 'Dynamic Button (Google)', 'events-addon-for-elementor' ),
						'ical' => esc_html__( 'Dynamic Button (iCal)', 'events-addon-for-elementor' ),
					],
					'default' => 'static',
					'description' => esc_html__( 'Select your calendar button type.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Event ID', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $event_post,
					'multiple' => true,
					'condition' => [
						'button_type' => array('google','ical'),
					],
				]
			);
			$this->add_control(
				'btn_icon',
				[
					'label' => esc_html__( 'Button Icon', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-plus',
				]
			);
			$this->add_control(
				'btn_text',
				[
					'label' => esc_html__( 'Button Text', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Add To Calendar', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_link',
				[
					'label' => esc_html__( 'Button Link', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'button_type' => array('static'),
					],
				]
			);
			$this->add_control(
				'new_tab',
				[
					'label' => esc_html__( 'Oppen In New Tab?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'condition' => [
						'button_type' => array('google','ical'),
					],
				]
			);
			$this->add_responsive_control(
				'btn_alignment',
				[
					'label' => esc_html__( 'Button Alignment', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-btn-wrap' => 'text-align: {{VALUE}};',
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
				]
			);
			$this->add_responsive_control(
				'btn_margin',
				[
					'label' => __( 'Margin', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$this->add_responsive_control(
				'icon_padding',
				[
					'label' => __( 'Icon Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-btn i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-btn' => 'min-width:{{SIZE}}{{UNIT}};',
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
		 * Render Calendar Button widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$button_type = !empty( $settings['button_type'] ) ? $settings['button_type'] : '';
			$event_id = !empty( $settings['event_id'] ) ? $settings['event_id'] : '';
			$btn_icon = !empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '';
		  $btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		  $btn_link = !empty( $settings['btn_link'] ) ? $settings['btn_link'] : '';
			$link_url = !empty( $btn_link['url'] ) ? esc_url($btn_link['url']) : '';
			$link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
			$link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$link_attr = !empty( $btn_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
		  $new_tab = !empty( $settings['new_tab'] ) ? $settings['new_tab'] : '';

		  $new_tab = $new_tab ? ' target="_blank"' : '';

			$btn_icon = $btn_icon ? '<i class="'.$btn_icon.'" aria-hidden="true"></i>' : '';

			if ($button_type === 'google') {
				$link = tribe_get_gcal_link($event_id);
			} elseif ($button_type === 'ical') {
				$link = tribe_get_single_ical_link($event_id);
			} else {
				$link = $link_url;
			}

		  $button = '<a href="'.$link.'" '.$link_attr.$new_tab.' class="naeep-btn">'.$btn_icon.$btn_text.'</a>';

			$output = '<div class="naeep-btn-wrap">'.$button.'</div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_TEC_CalendarButton() );
}

} // enable & disable