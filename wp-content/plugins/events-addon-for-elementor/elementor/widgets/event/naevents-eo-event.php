<?php
/*
 * Elementor Events Addon for Elementor EventOrganiser Event Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_list'])) { // enable & disable

if ( is_plugin_active( 'event-organiser/event-organiser.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventOrganiser_Event extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_eo_event';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Event', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-archive-posts';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-pro-eo-category'];
		}
		
		/**
		 * Register Events Addon for Elementor EventOrganiser Event widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$args = array(
	    'post_type' => 'event',
	    'posts_per_page' => -1,
	    );
	    $pages = eo_get_events($args);
	    $event_post = array();
	    if ( $pages ) {
	      foreach ( $pages as $page ) {
	        $event_post[ $page->ID ] = $page->post_title;
	      }
	    } else {
	      $event_post[ esc_html__( 'No Events found', 'events-addon-for-elementor' ) ] = 0;
	    }
			
			$this->start_controls_section(
				'section_event_settings',
				[
					'label' => esc_html__( 'Event Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_style',
				[
					'label' => __( 'Event Style', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one' => esc_html__( 'Style One Listing', 'events-addon-for-elementor' ),
						'two' => esc_html__( 'Style Two Listing', 'events-addon-for-elementor' ),
					],
					'default' => 'one',
					'description' => esc_html__( 'Select your event style.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_col',
				[
					'label' => esc_html__( 'Event Column', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'2'          => esc_html__('Two', 'events-addon-for-elementor'),
	          '3'          => esc_html__('Three', 'events-addon-for-elementor'),
	          '4'          => esc_html__('Four', 'events-addon-for-elementor'),
					],
					'default' => '3',
					'condition' => [
						'event_style!' => array('two'),
					],
				]
			);
			$this->add_control(
				'event_grid_title',
				[
					'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Day 1', 'events-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'event_style' => array('two'),
					],
				]
			);
			$this->add_control(
				'event_list_heading',
				[
					'label' => __( 'Listing', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'event_limit',
				[
					'label' => esc_html__( 'Limit', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => -1,
					'default' => -1,
					'step' => 1,
				]
			);
			$this->add_control(
				'event_order',
				[
					'label' => esc_html__( 'Order', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => [
						'DESC' => esc_html__('DESC', 'events-addon-for-elementor'),
						'ASC' => esc_html__('ASC', 'events-addon-for-elementor'),
					],
				]
			);
			$this->add_control(
				'event_orderby',
				[
					'label' => esc_html__( 'Order By', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => '',
					'options' => [
						'none' => esc_html__('None', 'events-addon-for-elementor'),
						'ID' => esc_html__('ID', 'events-addon-for-elementor'),
						'author' => esc_html__('Author', 'events-addon-for-elementor'),
						'title' => esc_html__('Name', 'events-addon-for-elementor'),
						'date' => esc_html__('Date', 'events-addon-for-elementor'),
						'rand' => esc_html__('Rand', 'events-addon-for-elementor'),
						'menu_order' => esc_html__('Menu Order', 'events-addon-for-elementor'),
					],
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Event ID', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $event_post,
					'multiple' => true,
					'condition' => [
						'event_style!' => array('two'),
					],
				]
			);
			$this->add_control(
				'exact_date',
				[
					'label' => esc_html__( 'Exact Date ', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'M d Y',
						'enableTime' => 'false',
					],
					'placeholder' => esc_html__( 'Aug 15 2019', 'events-addon-for-elementor' ),
					'label_block' => true,
					'description' => __( 'Events that start on this specific date given as a string in Aug 15 2019 format will shown in output.', 'events-addon-for-elementor' ),
					'condition' => [
						'event_style' => array('two'),
					],
				]
			);
			$this->add_control(
				'date_format',
				[
					'label' => esc_html__( 'Date Formate', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'M d Y', 'events-addon-for-elementor' ),
					'description' => __( 'Enter date format (for more info <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">click here</a>).', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_opt_heading',
				[
					'label' => __( 'Options', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'event_style!' => array('two'),
					],
				]
			);		
			$this->add_control(
				'event_pagination',
				[
					'label' => esc_html__( 'Pagination?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'condition' => [
						'event_style!' => array('two'),
					],
				]
			);
			$this->add_control(
				'short_content',
				[
					'label' => esc_html__( 'Excerpt Length', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'step' => 1,
					'default' => 15,
					'description' => esc_html__( 'How many words you want in short content paragraph.', 'events-addon-for-elementor' ),
					'condition' => [
						'event_style!' => array('two'),
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
						'{{WRAPPER}} .naeep-event-item' => 'text-align: {{VALUE}};',
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
							'{{WRAPPER}} .naeep-event-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
								'{{WRAPPER}} .naeep-event-item' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_border',
							'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .naeep-event-item',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .naeep-event-item',
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
								'{{WRAPPER}} .naeep-event-item.naeep-hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_hover_border',
							'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .naeep-event-item.naeep-hover',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_hover_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .naeep-event-item.naeep-hover',
						]
					);		
					$this->end_controls_tab();  // end:Normal tab
				$this->end_controls_tabs(); // end tabs		
				$this->end_controls_section();// end: Section

			// Section Title
				$this->start_controls_section(
					'section_sttl_style',
					[
						'label' => esc_html__( 'Section Title', 'events-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'event_style' => array('two'),
						],
					]
				);
				$this->add_responsive_control(
					'sttl_padding',
					[
						'label' => __( 'Padding', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naeep-event-item h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'sttl_typography',
						'scheme' => Scheme_Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .naeep-event-item h2',
					]
				);
				$this->add_control(
					'sttl_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-event-item h2' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_name_style',
				[
					'label' => esc_html__( 'Event Title', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-event-item h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'name_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .naeep-event-item h3',
				]
			);
			$this->start_controls_tabs( 'name_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'name_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-event-item h3, {{WRAPPER}} .naeep-event-item h3 a' => 'color: {{VALUE}};',
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
					'name_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-event-item h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs		
			$this->end_controls_section();// end: Section

		// Date
			$this->start_controls_section(
				'section_date_style',
				[
					'label' => esc_html__( 'Date', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'date_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-event-item h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'date_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .naeep-event-item h5',
				]
			);
			$this->add_control(
				'date_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-event-item h5' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section		

		// Time
			$this->start_controls_section(
				'section_time_style',
				[
					'label' => esc_html__( 'Time', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'time_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-event-item span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'time_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .naeep-event-item span',
				]
			);
			$this->add_control(
				'time_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-event-item span' => 'color: {{VALUE}};',
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
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-event-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-event-item p',
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
						'{{WRAPPER}} .naeep-event-item p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Pagination
			$this->start_controls_section(
				'section_pagi_style',
				[
					'label' => esc_html__( 'Pagination', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'organizer_pagination' => 'true',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_width',
				[
					'label' => esc_html__( 'Pagination Width', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-pagination ul li span, {{WRAPPER}} .naeep-pagination ul li a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagi_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-pagination ul li a, {{WRAPPER}} .naeep-pagination ul li span',
				]
			);
			$this->start_controls_tabs( 'pagi_style' );
				$this->start_controls_tab(
					'pagi_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination ul li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination ul li a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-pagination ul li a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				
				$this->start_controls_tab(
					'pagi_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination ul li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination ul li a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-pagination ul li a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
				$this->start_controls_tab(
					'pagi_active',
					[
						'label' => esc_html__( 'Active', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_active_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination ul li span.current' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_active_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination ul li span.current' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_active_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-pagination ul li span.current',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			
			// Event query
			$event_style = !empty( $settings['event_style'] ) ? $settings['event_style'] : '';
			$event_grid_title = !empty( $settings['event_grid_title'] ) ? $settings['event_grid_title'] : '';
			$event_limit = !empty( $settings['event_limit'] ) ? $settings['event_limit'] : '';
			$event_order = !empty( $settings['event_order'] ) ? $settings['event_order'] : '';
			$event_orderby = !empty( $settings['event_orderby'] ) ? $settings['event_orderby'] : '';
			$event_id = !empty( $settings['event_id'] ) ? $settings['event_id'] : '';
			$exact_date = !empty( $settings['exact_date'] ) ? $settings['exact_date'] : '';
			$date_format = !empty( $settings['date_format'] ) ? $settings['date_format'] : '';			
			$event_col = !empty( $settings['event_col'] ) ? $settings['event_col'] : '';
			$event_pagination  = ( isset( $settings['event_pagination'] ) && ( 'true' == $settings['event_pagination'] ) ) ? true : false;
			$short_content = !empty( $settings['short_content'] ) ? $settings['short_content'] : '';

	  	$event_col = $event_col ? $event_col : '3';
	  	$date_format = $date_format ? $date_format : 'M d Y';

	  	$originalDate = $exact_date;
			$formatDate = date($date_format, strtotime($originalDate));

	  	if ($event_col === '2') {
				$col_class = 'col-na-6';
			} elseif ($event_col === '4') {
				$col_class = 'col-na-3';
			} else {
				$col_class = 'col-na-4';
			}

			// Turn output buffer on
			ob_start();	

			// Pagination
			global $paged;
			if ( get_query_var( 'paged' ) )
			  $my_page = get_query_var( 'paged' );
			else {
			  if ( get_query_var( 'page' ) )
				$my_page = get_query_var( 'page' );
			  else
				$my_page = 1;
			  set_query_var( 'paged', $my_page );
			  $paged = $my_page;
			}
			$event_limit = $event_limit ? $event_limit : '-1';
			if ($event_id) {
				$event_id = json_encode( $event_id );
				$event_id = str_replace(array( '[', ']' ), '', $event_id);
				$event_id = str_replace(array( '"', '"' ), '', $event_id);
	      $event_id = explode(',',$event_id);
	    } else {
	      $event_id = '';
	    }

			$args = array(
			  'paged' => $my_page,
			  'post_type' => 'event',
			  'posts_per_page' => (int) $event_limit,
			  'orderby' => $event_orderby,
			  'order' => $event_order,
		  	'post__in' => $event_id,
			);
			$naevents_event = new \WP_Query( $args );
			if ($naevents_event->have_posts()) : 
				if ($event_style === 'two') { ?>
				  <div class="naeep-event-item event-item-two naeep-item">
			    	<h5><?php echo esc_html($formatDate); ?></h5>
			    	<?php if ($event_grid_title) { ?><h2><?php echo esc_html($event_grid_title); ?></h2><?php }
						while ($naevents_event->have_posts()) : $naevents_event->the_post(); 
							if ($exact_date == eo_get_the_start('M d Y' )) {
							?>
						    <div class="naeep-event-info">
						    	<span><?php echo eo_get_the_start('H:i' ); ?></span>
						      <h3 class="event-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3> 
					  		</div>
							<?php }
						endwhile; 
						wp_reset_postdata(); ?>
				  </div>
				<?php } else { ?>
					<div class="naeep-event">
						<div class="col-na-row">
							<?php while ($naevents_event->have_posts()) : $naevents_event->the_post(); 
							?>
							<div class="<?php echo esc_attr($col_class); ?>">
							  <div class="naeep-event-item naeep-item">
						    	<h5><?php echo eo_get_the_start($date_format); ?></h5>
						      <h3 class="event-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
							    <div class="naeep-event-info">
							    	<span><?php echo eo_get_the_start('H:i' ); ?> - <?php echo eo_get_the_end('H:i' ); ?></span>
							      <?php naevents_excerpt($short_content); ?>
							    </div>
							  </div>
							</div>
							<?php
						  endwhile;
							?>
						</div>
						<?php if ($naevents_event->max_num_pages > 1) {
							if ($event_pagination) { 
				    		if ( function_exists( 'naevents_paging_nav' ) ) {
			          	echo naevents_paging_nav($naevents_event->max_num_pages,"",$paged);
			        	}
			        }
			      } wp_reset_postdata(); ?>
					</div> <!-- event End -->
				<?php }
			endif;
			
			// Return outbut buffer
			echo ob_get_clean();
			
		}

		/**
		 * Render Event widget output in the editor.
		 * Written as a Backbone JavaScript template and used to generate the live preview.
		*/
		
		//protected function _content_template(){}
		
	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventOrganiser_Event() );
}

} // enable & disable