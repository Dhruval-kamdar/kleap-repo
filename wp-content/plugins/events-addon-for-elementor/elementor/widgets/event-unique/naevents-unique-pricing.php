<?php
/*
 * Elementor Events Addon for Elementor Unique Pricing Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_pricing'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Pricing extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_pricing';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Pricing Table', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Retrieve the pricing of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Pricing widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_pricing',
			[
				'label' => esc_html__( 'Pricing Options', 'events-addon-for-elementor' ),
			]
		);

		$this->add_control(
			'pricing_title',
			[
				'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Starter Plan', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'pricing_price',
			[
				'label' => esc_html__( 'Price', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Free', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type price text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'total_tickets',
			[
				'label' => esc_html__( 'Total Tickets', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'default' => 500,
				'step' => 1,
			]
		);
		$this->add_control(
			'filled_tickets',
			[
				'label' => esc_html__( 'Filled Tickets', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'default' => 250,
				'step' => 1,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'pricing_text',
			[
				'label' => esc_html__( 'Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'disable_text',
			[
				'label' => esc_html__( 'Disabled?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'events-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'false',
			]
		);
		$this->add_control(
			'pricingItems_groups',
			[
				'label' => esc_html__( 'Pricings', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'pricing_text' => esc_html__( 'Item #1', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ pricing_text }}}',
			]
		);
		$this->add_control(
			'pricing_btn',
			[
				'label' => esc_html__( 'Button Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Ticket', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type btn text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'pricing_btn_link',
			[
				'label' => esc_html__( 'Button Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'disable_animation',
			[
				'label' => esc_html__( 'Disable Button Animation?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'events-addon-for-elementor' ),
				'return_value' => 'true',
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
				'selectors' => [
					'{{WRAPPER}} .naeep-price-item' => 'text-align: {{VALUE}};',
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
			]
		);
		$this->add_responsive_control(
			'info_padding',
			[
				'label' => __( 'Section Spacing', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-price-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-price-item, {{WRAPPER}} .naeep-price-item .naeep-btn:before, {{WRAPPER}} .naeep-price-item .naeep-btn:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-price-item',
				]
			);
			$this->add_control(
				'section_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-price-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-price-item',
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
						'{{WRAPPER}} .naeep-price-item.naeep-hover, {{WRAPPER}} .naeep-price-item.naeep-hover .naeep-btn:before, {{WRAPPER}} .naeep-price-item.naeep-hover .naeep-btn:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hover_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-price-item.naeep-hover',
				]
			);
			$this->add_control(
				'section_hover_border_radius',
				[
					'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-price-item.naeep-hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hover_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-price-item.naeep-hover',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
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
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'saspri_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-price-item h4',
			]
		);
		$this->add_control(
			'pricing_title_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-price-item h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Price
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pricing_price_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .naeep-price-item h2',
			]
		);
		$this->add_control(
			'pricing_price_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-price-item h2' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Progress Bar
		$this->start_controls_section(
			'section_pro_style',
			[
				'label' => esc_html__( 'Progress Bar', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'saspri_pro_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .naeep-progress-item h4',
			]
		);
		$this->add_control(
			'pricing_pro_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-progress-item h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->start_controls_tabs( 'prog_style' );
			$this->start_controls_tab(
				'prog_normal',
				[
					'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'pricing_pro_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-progress' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'prog_hover',
				[
					'label' => esc_html__( 'Active', 'events-addon-for-elementor' ),
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'pricing_pro_background',
					'label' => __( 'Background', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-progress-bar',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// List
		$this->start_controls_section(
			'section_list_style',
			[
				'label' => esc_html__( 'List', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pricing_list_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .naeep-price-item ul li',
			]
		);
		$this->add_control(
			'pricing_list_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
				'selectors' => [
					'{{WRAPPER}} .naeep-price-item ul li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'pricing_dlist_color',
			[
				'label' => esc_html__( 'Disable Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-price-item ul li.disable' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Button
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button Style', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn',
			]
		);
		$this->add_responsive_control(
			'button_min_width',
			[
				'label' => esc_html__( 'Width', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-btn-wrap .naeep-btn' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-btn-wrap .naeep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .naeep-btn-wrap .naeep-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naeep-btn-wrap .naeep-btn' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'button_bg_color',
					'label' => __( 'Background', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_box_shadow',
					'label' => esc_html__( 'Button Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn',
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
						'{{WRAPPER}} .naeep-btn-wrap .naeep-btn:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'button_bg_hover_color',
					'label' => __( 'Background', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn:hover',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_hover_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn:hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_hover_box_shadow',
					'label' => esc_html__( 'Button Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-btn-wrap .naeep-btn:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Pricing widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$pricing_style = !empty( $settings['pricing_style'] ) ? $settings['pricing_style'] : '';
		$pricing_title = !empty( $settings['pricing_title'] ) ? $settings['pricing_title'] : '';
		$pricing_price = !empty( $settings['pricing_price'] ) ? $settings['pricing_price'] : '';
		$total_tickets = !empty( $settings['total_tickets'] ) ? $settings['total_tickets'] : '';
		$filled_tickets = !empty( $settings['filled_tickets'] ) ? $settings['filled_tickets'] : '';

		$pricingItems_groups = !empty( $settings['pricingItems_groups'] ) ? $settings['pricingItems_groups'] : [];

		$pricing_btn = !empty( $settings['pricing_btn'] ) ? $settings['pricing_btn'] : '';
		$pricing_btn_link = !empty( $settings['pricing_btn_link']['url'] ) ? $settings['pricing_btn_link']['url'] : '';
		$pricing_btn_link_external = !empty( $settings['pricing_btn_link']['is_external'] ) ? 'target="_blank"' : '';
		$pricing_btn_link_nofollow = !empty( $settings['pricing_btn_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$pricing_btn_link_attr = !empty( $pricing_btn_link ) ?  $pricing_btn_link_external.' '.$pricing_btn_link_nofollow : '';
		$disable_animation = !empty( $settings['disable_animation'] ) ? $settings['disable_animation'] : '';

		$title = $pricing_title ? '<h4 class="price-subtitle">'.esc_html($pricing_title).'</h4>' : '';
		$price = $pricing_price ? '<h2 class="price-title">'.esc_html($pricing_price).'</h2>' : '';
  	$button = $pricing_btn_link ? '<div class="naeep-btn-wrap"><a href="'.esc_url($pricing_btn_link).'" '.$pricing_btn_link_attr.' class="naeep-btn">'.esc_html($pricing_btn).'</a></div>' : '';

  	$tickets = $total_tickets ? ($filled_tickets/$total_tickets) : '';
  	$tickets_percent = $tickets ? ($tickets*100) : '';

  	if ($disable_animation) {
  		$btn_cls = ' no-hover';
  	} else {
  		$btn_cls = '';
  	}

		$output = '<div class="naeep-price-item naeep-item'.$btn_cls.'">
	              '.$title.$price.'
	              <div class="naeep-progress-item">
	                <h4 class="naeep-progress-title">'.$filled_tickets.'/'.$total_tickets.'</h4>
	                <div class="naeep-progress">
	                  <div class="naeep-progress-bar" style="width: '.$tickets_percent.'%;"></div>
	                </div>
	              </div>
	              <ul>';
	                if ( is_array( $pricingItems_groups ) && !empty( $pricingItems_groups ) ) {
									  foreach ( $pricingItems_groups as $each_pricing ) {
										$pricing_text = $each_pricing['pricing_text'] ? $each_pricing['pricing_text'] : '';
										$disable_text = $each_pricing['disable_text'] ? $each_pricing['disable_text'] : '';
										if ($disable_text == 'true') {
											$disable_class = ' class="disable"';
										} else {
											$disable_class = '';
										}
										  $output .= '<li'.$disable_class.'>'. do_shortcode($pricing_text) .'</li>';
									  }
									}
    $output .= '</ul>
	              '.$button.'
	            </div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Pricing() );

} // enable & disable