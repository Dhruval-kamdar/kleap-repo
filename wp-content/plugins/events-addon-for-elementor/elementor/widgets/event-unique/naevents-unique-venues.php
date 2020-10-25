<?php
/*
 * Elementor Events Addon for Elementor Unique Venues Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_venues'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Venues extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_venues';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Venues', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Venues widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_schedule_settings',
			[
				'label' => esc_html__( 'Venues Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'schedule_col',
			[
				'label' => esc_html__( 'Venues Column', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'2'          => esc_html__('Two', 'events-addon-for-elementor'),
          '3'          => esc_html__('Three', 'events-addon-for-elementor'),
          '4'          => esc_html__('Four', 'events-addon-for-elementor'),
				],
				'default' => '3',
			]
		);

		$repeaterOne = new Repeater();
		$repeaterOne->add_control(
			'schedule_image',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
			]
		);
		$repeaterOne->add_control(
			'schedule_category',
			[
				'label' => esc_html__( 'Category', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'category_link',
			[
				'label' => esc_html__( 'Category Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'schedule_title',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'title_link',
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
		$repeaterOne->add_control(
			'schedule_date_title',
			[
				'label' => __( 'Date', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeaterOne->add_control(
			'schedule_date',
			[
				'label' => esc_html__( 'Venues Date ', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'dateFormat' => 'M d, Y',
					'enableTime' => 'false',
				],
				'placeholder' => esc_html__( 'Aug 15, 2019', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'schedule_content',
			[
				'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'more_icon',
			[
				'label' => esc_html__( 'More Icon', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
			]
		);
		$repeaterOne->add_control(
			'schedule_more',
			[
				'label' => esc_html__( 'Read More Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'events-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'more_link',
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
			'gridItem_groups',
			[
				'label' => esc_html__( 'Venues Items', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'schedule_title' => esc_html__( 'Brighton Waterfront Hotel', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeaterOne->get_controls(),
				'title_field' => '{{{ schedule_title }}}',
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .naeep-grid-info' => 'text-align: {{VALUE}};',
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
						'{{WRAPPER}} .naeep-grid-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'img_border_radius',
				[
					'label' => __( 'Image Border Radius', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-grid .naeep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'label' => esc_html__( 'Overlay Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-grid-info' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-grid-info',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-grid',
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
						'label' => esc_html__( 'Overlay Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-schedule-grid:hover .naeep-grid-info' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-grid:hover .naeep-grid-info',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_hover_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-grid:hover',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Meta
			$this->start_controls_section(
				'section_meta_style',
				[
					'label' => esc_html__( 'Meta', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'meta_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} ul.schedule-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'meta_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} ul.schedule-meta li',
				]
			);
			$this->add_control(
				'meta_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} ul.schedule-meta li' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_name_style',
				[
					'label' => esc_html__( 'Venues Title', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-schedule-info h3, {{WRAPPER}} .naeep-grid-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'name_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-schedule-info h3, {{WRAPPER}} .naeep-grid-info h3',
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
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-schedule-info h3, {{WRAPPER}} .naeep-schedule-info h3 a, {{WRAPPER}} .naeep-grid-info h3, {{WRAPPER}} .naeep-grid-info h3 a' => 'color: {{VALUE}};',
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
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-schedule-info h3 a:hover, {{WRAPPER}} .naeep-grid-info h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Categories
			$this->start_controls_section(
				'section_cat_style',
				[
					'label' => esc_html__( 'Venues Categories', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'cat_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-image .events-cat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'cat_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .naeep-image .events-cat a',
				]
			);
			$this->start_controls_tabs( 'cat_style' );
				$this->start_controls_tab(
					'cat_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'cat_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-image .events-cat a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'cat_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-image .events-cat a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'cat_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'cat_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-image .events-cat a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'cat_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-image .events-cat a:hover' => 'background-color: {{VALUE}};',
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
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-grid-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-grid-info p',
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
						'{{WRAPPER}} .naeep-grid-info p' => 'color: {{VALUE}};',
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
	 * Render Venues widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Venues query
		$schedule_col = !empty( $settings['schedule_col'] ) ? $settings['schedule_col'] : '';

		$gridItem = !empty( $settings['gridItem_groups'] ) ? $settings['gridItem_groups'] : '';

  	$schedule_col = $schedule_col ? $schedule_col : '3';
  	if ($schedule_col === '2') {
			$col_class = 'col-na-6';
		} elseif ($schedule_col === '4') {
			$col_class = 'col-na-3';
		} else {
			$col_class = 'col-na-4';
		}

		// Turn output buffer on
		ob_start();	?>
			<div class="naeep-schedule">
				<div class="col-na-row">
				<?php
					// Group Param Output
					foreach ( $gridItem as $each_grid ) {
						$schedule_image = !empty( $each_grid['schedule_image']['id'] ) ? $each_grid['schedule_image']['id'] : '';

						$schedule_category = !empty( $each_grid['schedule_category'] ) ? $each_grid['schedule_category'] : '';
				  	$category_link = !empty( $each_grid['category_link']['url'] ) ? $each_grid['category_link']['url'] : '';
						$category_link_external = !empty( $each_grid['category_link']['is_external'] ) ? 'target="_blank"' : '';
						$category_link_nofollow = !empty( $each_grid['category_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$category_link_attr = !empty( $category_link ) ?  $category_link_external.' '.$category_link_nofollow : '';

						$schedule_title = !empty( $each_grid['schedule_title'] ) ? $each_grid['schedule_title'] : '';
				  	$title_link = !empty( $each_grid['title_link']['url'] ) ? $each_grid['title_link']['url'] : '';
						$title_link_external = !empty( $each_grid['title_link']['is_external'] ) ? 'target="_blank"' : '';
						$title_link_nofollow = !empty( $each_grid['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';

						$schedule_date = !empty( $each_grid['schedule_date'] ) ? $each_grid['schedule_date'] : '';
						$schedule_content = !empty( $each_grid['schedule_content'] ) ? $each_grid['schedule_content'] : '';

						$more_icon = !empty( $each_grid['more_icon'] ) ? $each_grid['more_icon'] : '';
						$schedule_more = !empty( $each_grid['schedule_more'] ) ? $each_grid['schedule_more'] : '';
				  	$more_link = !empty( $each_grid['more_link']['url'] ) ? $each_grid['more_link']['url'] : '';
						$more_link_external = !empty( $each_grid['more_link']['is_external'] ) ? 'target="_blank"' : '';
						$more_link_nofollow = !empty( $each_grid['more_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$more_link_attr = !empty( $more_link ) ?  $more_link_external.' '.$more_link_nofollow : '';

						$image_url = wp_get_attachment_url( $schedule_image );
						$image = $image_url ? '<a href="'. esc_url($image_url) .'"><img src="'.esc_url($image_url).'" alt="'.$schedule_title.'"></a>' : '';

						$link_title = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($schedule_title).'</a>' : esc_html($schedule_title);
						$title = $schedule_title ? '<h3 class="schedule-title">'.$link_title.'</h3>' : '';

						$link_category = $category_link ? '<a href="'.esc_url($category_link).'" '.$category_link_attr.'>'.esc_html($schedule_category).'</a>' : '';
						$content = $schedule_content ? '<p>'.esc_html($schedule_content).'</p>' : '';
						$more_ico = $more_icon ? '<i class="'.esc_attr($more_icon).'" aria-hidden="true"></i> ' : '';
			  		$button = !empty($more_link) ? '<div class="naeep-link-wrap"><a href="'.esc_url($more_link).'" '.$more_link_attr.' class="naeep-link">'.$more_ico.esc_html($schedule_more).'</a></div>' : '';

			  		if ($image_url) {
						  $img_class = '';
						} else {
						  $img_class = ' no-img';
						}
						?>
						<div class="<?php echo esc_attr($col_class); ?>">
							<div class="naeep-schedule-grid naeep-venue-grid naeep-item<?php echo esc_attr($img_class); ?>">
								<?php if ($image_url) { ?>
						    <div class="naeep-image naeep-popup">
						      <?php echo $image; ?>
					      	<div class="events-cat">
					      		<?php echo $link_category; ?>
					      	</div>
						    </div>
						    <?php } ?>
						    <div class="naeep-grid-info">
						      <ul class="schedule-meta">
						      	<?php if ($schedule_date) { ?>
					      		<li><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $schedule_date; ?></li>
						      	<?php } ?>
						      </ul>
						      <?php echo $title.$content.$button; ?>
						    </div>
						  </div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Venues() );

} // enable & disable