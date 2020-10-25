<?php
/*
 * Elementor Events Addon for Elementor Schedule Tab Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_schedule_tab'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_ScheduleTab extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_schedule_tab';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Schedule Tab', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Schedule Tab widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$templates = get_posts( 'post_type="elementor_library"&numberposts=-1' );
	  $elementor_templates = array();
	  if ( $templates ) {
	    foreach ( $templates as $template ) {
	      $elementor_templates[ $template->ID ] = $template->post_title;
	    }
	  } else {
	    $elementor_templates[ __( 'No templates found', 'events-addon-for-elementor' ) ] = 0;
	  }

		$this->start_controls_section(
			'section_active',
			[
				'label' => __( 'Tab Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'active',
			[
				'label' => __( 'Active Tab Number', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 1,
			]
		);

		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_tab_content',
			[
				'label' => __( 'Schedule Tab Group', 'events-addon-for-elementor' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'event_opt_heading',
			[
				'label' => __( 'Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Tab Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Day 1', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type tab title here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'tab_id',
			[
				'label' => esc_html__( 'Tab ID', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type tab id here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_date',
			[
				'label' => esc_html__( 'Schedule Date ', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'dateFormat' => 'M d, Y',
					'enableTime' => 'false',
				],
				'placeholder' => esc_html__( 'Aug 15, 2019', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'event_opt_cont',
			[
				'label' => __( 'Content', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'tab_content_templates',
			[
				'label' => __( 'Templates', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => [],
				'options' => $elementor_templates,
				'multiple' => false,
			]
		);
		$this->add_control(
			'schedule_groups',
			[
				'label' => esc_html__( 'Schedule Group', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'tab_title' => esc_html__( 'Day 1', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ tab_title }}}',
			]
		);
		$this->add_responsive_control(
			'tab_alignment',
			[
				'label' => esc_html__( 'Tab Alignment', 'events-addon-for-elementor' ),
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} ul.naeep-tab-links' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Tab
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Tab', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'info_padding',
			[
				'label' => __( 'Tab Spacing', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-tab-links a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'info_margin',
			[
				'label' => __( 'Tab Margin', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-tab-links li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Title Spacing', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-tab-links a span.title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Title Typography', 'events-addon-for-elementor' ),
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .naeep-tab-links a span.title',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Date Typography', 'events-addon-for-elementor' ),
				'name' => 'date_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .naeep-tab-links a span.date',
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
				'secn_tab_title_color',
				[
					'label' => esc_html__( 'Title Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links a span.title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_tab_date_color',
				[
					'label' => esc_html__( 'Date Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links a span.date' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_bg_color',
					'label' => __( 'Background Color', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-tab-links a',
				]
			);
			$this->add_control(
				'secn_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links a' => 'border-color: {{VALUE}};',
					],
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
				'secn_hover_tab_title_color',
				[
					'label' => esc_html__( 'Title Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links a:hover, {{WRAPPER}} .naeep-tab-links a:hover span.title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_hover_tab_date_color',
				[
					'label' => esc_html__( 'Date Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links a:hover span.date' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_hover_bg_color',
					'label' => __( 'Background Color', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-tab-links a:hover',
				]
			);
			$this->add_control(
				'secn_hover_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links a:hover' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'scn_active',
				[
					'label' => esc_html__( 'Active', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_active_tab_title_color',
				[
					'label' => esc_html__( 'Title Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links li.active a, {{WRAPPER}} .naeep-tab-links li.active a span.title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_active_tab_date_color',
				[
					'label' => esc_html__( 'Date Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links li.active a span.date' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_active_bg_color',
					'label' => __( 'Background Color', 'events-addon-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naeep-tab-links li.active a',
				]
			);
			$this->add_control(
				'secn_active_sep_color',
				[
					'label' => esc_html__( 'Active Tip Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links li.active a:after' => 'border-top-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_active_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-tab-links li.active a' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Schedule Tab widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Schedule Tab query
		$settings = $this->get_settings_for_display();
		$active = !empty( $settings['active'] ) ? $settings['active'] : '';
		$schedule_groups = !empty( $settings['schedule_groups'] ) ? $settings['schedule_groups'] : '';

			$output = '';
			if ( !empty( $schedule_groups ) && is_array( $schedule_groups ) ){
				$output .= '<ul class="naeep-tab-links">';
	      	$key = 1;
	      	foreach ( $schedule_groups as $each_logo ) {
						$title = !empty( $each_logo['tab_title'] ) ? $each_logo['tab_title'] : '';
						$tab_id = !empty( $each_logo['tab_id'] ) ? $each_logo['tab_id'] : '';
						$date = !empty( $each_logo['schedule_date'] ) ? $each_logo['schedule_date'] : '';

						$active_class = ( $key == $active ) ? ' class="active"' : '';
						$id = $tab_id ? sanitize_title($tab_id) : sanitize_title($title);
						$date = $date ? '<span class="date">'.esc_html($date).'</span>' : '';

						$output .= '<li'.$active_class.'><a class="naeep-item" href="#naeep-'.$key.$id.'"><span class="title">'.esc_html($title).'</span>'.$date.'</a></li>';
						$key++;
					}
	      $output .= '</ul><div class="naeep-tab-content">';
	      	$key = 1;
	      	foreach ( $schedule_groups as $each_logo ) {
	      		$templates = !empty( $each_logo['tab_content_templates'] ) ? $each_logo['tab_content_templates'] : '';
						$active_cls = ( $key == $active ) ? ' active' : '';

						$title = !empty( $each_logo['tab_title'] ) ? $each_logo['tab_title'] : '';
						$tab_id = !empty( $each_logo['tab_id'] ) ? $each_logo['tab_id'] : '';
						$id = $tab_id ? sanitize_title($tab_id) : sanitize_title($title);

						$output .= '<div class="naeep-tab'.$active_cls.'" id="naeep-'.$key.$id.'">'.do_shortcode('[naevents_elementor_template id="'.$templates.'"]').'</div>';
						$key++;
				  }
				$output .= '</div>';
			}
			echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_ScheduleTab() );

} // enable & disable