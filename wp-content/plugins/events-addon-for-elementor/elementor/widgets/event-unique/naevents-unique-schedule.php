<?php
/*
 * Elementor Events Addon for Elementor Unique Schedule Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_schedule'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Schedule extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_schedule';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Schedule', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-radio';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Schedule widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_schedule_settings',
			[
				'label' => esc_html__( 'Schedule Options', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'schedule_style',
			[
				'label' => __( 'Schedule Style', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One (Slider)', 'events-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two (Grid)', 'events-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your event style.', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'schedule_col',
			[
				'label' => esc_html__( 'Schedule Column', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'2'          => esc_html__('Two', 'events-addon-for-elementor'),
          '3'          => esc_html__('Three', 'events-addon-for-elementor'),
          '4'          => esc_html__('Four', 'events-addon-for-elementor'),
				],
				'default' => '3',
				'condition' => [
					'schedule_style' => array('two'),
				],
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'schedule_date',
			[
				'label' => esc_html__( 'Schedule Date', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type date text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_image',
			[
				'label' => esc_html__( 'Upload Image', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'events-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'schedule_title',
			[
				'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
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
		$repeater->add_control(
			'schedule_time',
			[
				'label' => esc_html__( 'Schedule Time', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type subtitle text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_spker',
			[
				'label' => __( 'Speakers', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'schedule_speaker',
			[
				'label' => esc_html__( 'Speakers Name', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_speaker_title',
			[
				'label' => esc_html__( 'Speakers Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_vnu',
			[
				'label' => __( 'Venue', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'schedule_venue',
			[
				'label' => esc_html__( 'Venue Name', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'schedule_venue_title',
			[
				'label' => esc_html__( 'Venue Title', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'eventItem_groups',
			[
				'label' => esc_html__( 'Schedule Items', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'schedule_title' => esc_html__( 'Events', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ schedule_title }}}',
				'condition' => [
					'schedule_style!' => array('two'),
				],
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
			'image_link',
			[
				'label' => esc_html__( 'Image Link', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
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
		$repeaterOne->add_control(
			'schedule_vnu',
			[
				'label' => __( 'Venue', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeaterOne->add_control(
			'schedule_venue',
			[
				'label' => esc_html__( 'Venue Name', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type text here', 'events-addon-for-elementor' ),
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
				'label' => esc_html__( 'Schedule Items', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'schedule_title' => esc_html__( 'Events', 'events-addon-for-elementor' ),
					],

				],
				'fields' => $repeaterOne->get_controls(),
				'title_field' => '{{{ schedule_title }}}',
				'condition' => [
					'schedule_style' => array('two'),
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .naeep-schedule-item' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Carousel Options
			$this->start_controls_section(
				'section_carousel',
				[
					'label' => esc_html__( 'Carousel Options', 'events-addon-for-elementor' ),
					'condition' => [
						'schedule_style' => 'one',
					],
				]
			);
			$this->add_responsive_control(
				'carousel_items',
				[
					'label' => esc_html__( 'How many items?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => 3,
					'description' => esc_html__( 'Enter the number of items to show.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'carousel_margin',
				[
					'label' => __( 'Space Between Items', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' =>30,
					],
					'label_block' => true,
				]
			);
			$this->add_control(
				'carousel_autoplay_timeout',
				[
					'label' => __( 'Auto Play Timeout', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 5000,
				]
			);
			$this->add_control(
				'carousel_loop',
				[
					'label' => esc_html__( 'Disable Loop?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'carousel_dots',
				[
					'label' => esc_html__( 'Dots', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'events-addon-for-elementor' ),
					'default' => true,
				]
			);
			$this->add_control(
				'carousel_nav',
				[
					'label' => esc_html__( 'Navigation', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'events-addon-for-elementor' ),
					'default' => true,
				]
			);
			$this->add_control(
				'carousel_autoplay',
				[
					'label' => esc_html__( 'Autoplay', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'events-addon-for-elementor' ),
					'default' => true,
				]
			);
			$this->add_control(
				'carousel_animate_out',
				[
					'label' => esc_html__( 'Animate Out', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'CSS3 animation out.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'carousel_mousedrag',
				[
					'label' => esc_html__( 'Disable Mouse Drag?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'carousel_autowidth',
				[
					'label' => esc_html__( 'Auto Width', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Adjust Auto Width automatically for each carousel items.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'carousel_autoheight',
				[
					'label' => esc_html__( 'Auto Height', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Adjust Auto Height automatically for each carousel items.', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .naeep-schedule-item, {{WRAPPER}} .naeep-grid-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .naeep-schedule-item:after, {{WRAPPER}} .naeep-grid-info' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-item, {{WRAPPER}} .naeep-grid-info',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-item, {{WRAPPER}} .naeep-schedule-grid',
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
							'{{WRAPPER}} .naeep-schedule-item:hover:after, {{WRAPPER}} .naeep-schedule-grid:hover .naeep-grid-info' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-item:hover, {{WRAPPER}} .naeep-schedule-grid:hover .naeep-grid-info',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_hover_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .naeep-schedule-item:hover, {{WRAPPER}} .naeep-schedule-grid:hover',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Navigation
			$this->start_controls_section(
				'section_navigation_style',
				[
					'label' => esc_html__( 'Navigation', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_nav' => 'true',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'arrow_size',
				[
					'label' => esc_html__( 'Size', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 42,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'font-size: calc({{SIZE}}{{UNIT}} - 16px);line-height: calc({{SIZE}}{{UNIT}} - 20px);',
					],
				]
			);
			$this->start_controls_tabs( 'nav_arrow_style' );
				$this->start_controls_tab(
					'nav_arrow_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_arrow_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'nav_arrow_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_arrow_hov_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_active_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab

			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Dots
			$this->start_controls_section(
				'section_dots_style',
				[
					'label' => esc_html__( 'Dots', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_dots' => 'true',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dots_size',
				[
					'label' => esc_html__( 'Size', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'dots_margin',
				[
					'label' => __( 'Margin', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'dots_style' );
				$this->start_controls_tab(
					'dots_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'dots_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-dot' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'dots_active',
					[
						'label' => esc_html__( 'Active', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'dots_active_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-dot.active' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_active_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot.active',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Date
			$this->start_controls_section(
				'section_date_style',
				[
					'label' => esc_html__( 'Date', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'schedule_style!' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'date_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-item h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'date_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-schedule-item h2',
				]
			);
			$this->add_control(
				'date_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-item h2' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Meta
			$this->start_controls_section(
				'section_meta_style',
				[
					'label' => esc_html__( 'Meta', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'schedule_style' => array('two'),
					],
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
					'label' => esc_html__( 'Schedule Title', 'events-addon-for-elementor' ),
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
					'scheme' => Scheme_Typography::TYPOGRAPHY_2,
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
					'label' => esc_html__( 'Schedule Categories', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'schedule_style' => array('two'),
					],
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
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
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
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
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
					'condition' => [
						'schedule_style' => array('two'),
					],
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

		// Time
			$this->start_controls_section(
				'section_time_style',
				[
					'label' => esc_html__( 'Time / Sub Title', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'schedule_style!' => array('two'),
					],
				]
			);
			$this->add_responsive_control(
				'time_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-info span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'time_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-schedule-info span',
				]
			);
			$this->add_control(
				'time_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-schedule-info span' => 'color: {{VALUE}};',
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
						'schedule_style' => array('two'),
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
	 * Render Schedule widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Schedule query
		$schedule_style = !empty( $settings['schedule_style'] ) ? $settings['schedule_style'] : '';
		$schedule_col = !empty( $settings['schedule_col'] ) ? $settings['schedule_col'] : '';

		$eventItem = !empty( $settings['eventItem_groups'] ) ? $settings['eventItem_groups'] : '';
		$gridItem = !empty( $settings['gridItem_groups'] ) ? $settings['gridItem_groups'] : '';

		// Carousel Data
			$carousel_items = !empty( $settings['carousel_items'] ) ? $settings['carousel_items'] : '';
			$carousel_items_tablet = !empty( $settings['carousel_items_tablet'] ) ? $settings['carousel_items_tablet'] : '';
			$carousel_items_mobile = !empty( $settings['carousel_items_mobile'] ) ? $settings['carousel_items_mobile'] : '';
			$carousel_margin = !empty( $settings['carousel_margin']['size'] ) ? $settings['carousel_margin']['size'] : '';
			$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
			$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? $settings['carousel_loop'] : 'false';
			$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
			$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
			$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
			$carousel_animate_out  = ( isset( $settings['carousel_animate_out'] ) && ( 'true' == $settings['carousel_animate_out'] ) ) ? true : false;
			$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';
			$carousel_autowidth  = ( isset( $settings['carousel_autowidth'] ) && ( 'true' == $settings['carousel_autowidth'] ) ) ? true : false;
			$carousel_autoheight  = ( isset( $settings['carousel_autoheight'] ) && ( 'true' == $settings['carousel_autoheight'] ) ) ? true : false;

		// Carousel Data's
			$carousel_loop = $carousel_loop !== 'true' ? ' data-loop="true"' : ' data-loop="false"';
			$carousel_items = $carousel_items ? ' data-items="'. $carousel_items .'"' : ' data-items="3"';
			$carousel_margin = $carousel_margin ? ' data-margin="'. $carousel_margin .'"' : ' data-margin="30"';
			$carousel_dots = $carousel_dots ? ' data-dots="true"' : ' data-dots="false"';
			$carousel_nav = $carousel_nav ? ' data-nav="true"' : ' data-nav="false"';
			$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-autoplay-timeout="'. $carousel_autoplay_timeout .'"' : '';
			$carousel_autoplay = $carousel_autoplay ? ' data-autoplay="true"' : '';
			$carousel_animate_out = $carousel_animate_out ? ' data-animateout="true"' : '';
			$carousel_mousedrag = $carousel_mousedrag !== 'true' ? ' data-mouse-drag="true"' : ' data-mouse-drag="false"';
			$carousel_autowidth = $carousel_autowidth ? ' data-auto-width="true"' : '';
			$carousel_autoheight = $carousel_autoheight ? ' data-auto-height="true"' : '';
			$carousel_tablet = $carousel_items_tablet ? ' data-items-tablet="'. $carousel_items_tablet .'"' : ' data-items-tablet="2"';
			$carousel_mobile = $carousel_items_mobile ? ' data-items-mobile-landscape="'. $carousel_items_mobile .'"' : ' data-items-mobile-landscape="1"';
			$carousel_small_mobile = $carousel_items_mobile ? ' data-items-mobile-portrait="'. $carousel_items_mobile .'"' : ' data-items-mobile-portrait="1"';

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
				<?php if ($schedule_style === 'two') { ?>
				<div class="col-na-row">
				<?php } else { ?>
				<div class="owl-carousel" <?php echo $carousel_loop . $carousel_items . $carousel_margin . $carousel_dots . $carousel_nav . $carousel_autoplay_timeout . $carousel_autoplay . $carousel_animate_out . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight  . $carousel_tablet . $carousel_mobile . $carousel_small_mobile; ?>>
				<?php }
				if ($schedule_style === 'two') {
					// Group Param Output
					foreach ( $gridItem as $each_grid ) {
						$schedule_image = !empty( $each_grid['schedule_image']['id'] ) ? $each_grid['schedule_image']['id'] : '';
						$image_link = !empty( $each_grid['image_link']['url'] ) ? $each_grid['image_link']['url'] : '';
						$image_link_external = !empty( $each_grid['image_link']['is_external'] ) ? 'target="_blank"' : '';
						$image_link_nofollow = !empty( $each_grid['image_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$image_link_attr = !empty( $image_link ) ?  $image_link_external.' '.$image_link_nofollow : '';

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
						$schedule_venue = !empty( $each_grid['schedule_venue'] ) ? $each_grid['schedule_venue'] : '';
						$schedule_content = !empty( $each_grid['schedule_content'] ) ? $each_grid['schedule_content'] : '';

						$schedule_more = !empty( $each_grid['schedule_more'] ) ? $each_grid['schedule_more'] : '';
				  	$more_link = !empty( $each_grid['more_link']['url'] ) ? $each_grid['more_link']['url'] : '';
						$more_link_external = !empty( $each_grid['more_link']['is_external'] ) ? 'target="_blank"' : '';
						$more_link_nofollow = !empty( $each_grid['more_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$more_link_attr = !empty( $more_link ) ?  $more_link_external.' '.$more_link_nofollow : '';

						$image_url = wp_get_attachment_url( $schedule_image );

						$link_image = $image_link ? '<a href="'.esc_url($image_link).'" '.$image_link_attr.'><img src="'.esc_url($image_url).'" alt="'.esc_attr($schedule_title).'"></a>' : '<img src="'.esc_url($image_url).'" alt="'.esc_attr($schedule_title).'">';
						$image = $image_url ? $link_image : '';

						$link_title = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($schedule_title).'</a>' : esc_html($schedule_title);
						$title = $schedule_title ? '<h3 class="schedule-title">'.$link_title.'</h3>' : '';

						$link_category = $category_link ? '<a href="'.esc_url($category_link).'" '.$category_link_attr.'>'.esc_html($schedule_category).'</a>' : '';
						$content = $schedule_content ? '<p>'.esc_html($schedule_content).'</p>' : '';
			  		$button = !empty($more_link) ? '<div class="naeep-link-wrap"><a href="'.esc_url($more_link).'" '.$more_link_attr.' class="naeep-link">'.esc_html($schedule_more).'</a></div>' : '';

			  		if ($image_url) {
						  $img_class = '';
						} else {
						  $img_class = ' no-img';
						}
						?>
						<div class="<?php echo esc_attr($col_class); ?>">
							<div class="naeep-schedule-grid naeep-item<?php echo esc_attr($img_class); ?>">
								<?php if ($image_url) { ?>
						    <div class="naeep-image">
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
						      	<?php } if ($schedule_venue) { ?>
						      	<li><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $schedule_venue; ?></li>
						      	<?php } ?>
						      </ul>
						      <?php echo $title.$content.$button; ?>
						    </div>
						  </div>
						</div>
					<?php }
				} else {
					foreach ( $eventItem as $each_logo ) {
						$schedule_date = !empty( $each_logo['schedule_date'] ) ? $each_logo['schedule_date'] : '';
						$schedule_image = !empty( $each_logo['schedule_image']['id'] ) ? $each_logo['schedule_image']['id'] : '';
						$schedule_title = !empty( $each_logo['schedule_title'] ) ? $each_logo['schedule_title'] : '';
				  	$title_link = !empty( $each_logo['title_link']['url'] ) ? $each_logo['title_link']['url'] : '';
						$title_link_external = !empty( $each_logo['title_link']['is_external'] ) ? 'target="_blank"' : '';
						$title_link_nofollow = !empty( $each_logo['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';
						$schedule_time = !empty( $each_logo['schedule_time'] ) ? $each_logo['schedule_time'] : '';
						$schedule_speaker = !empty( $each_logo['schedule_speaker'] ) ? $each_logo['schedule_speaker'] : '';
						$schedule_speaker_title = !empty( $each_logo['schedule_speaker_title'] ) ? $each_logo['schedule_speaker_title'] : '';
						$schedule_venue = !empty( $each_logo['schedule_venue'] ) ? $each_logo['schedule_venue'] : '';
						$schedule_venue_title = !empty( $each_logo['schedule_venue_title'] ) ? $each_logo['schedule_venue_title'] : '';

						$schedule_speaker_title = $schedule_speaker_title ? $schedule_speaker_title : esc_html__( 'Speaker', 'events-addon-for-elementor' );
		  			$schedule_venue_title = $schedule_venue_title ? $schedule_venue_title : esc_html__( 'Venue', 'events-addon-for-elementor' );

						$image_url = wp_get_attachment_url( $schedule_image );
		  			$date = !empty( $schedule_date ) ? '<h2>'.esc_html($schedule_date).'</h2>' : '';
						$link_title = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($schedule_title).'</a>' : esc_html($schedule_title);
						$title = $schedule_title ? '<h3>'.$link_title.'</h3>' : '';
				  	$time = !empty( $schedule_time ) ? '<span>'.esc_html($schedule_time).'</span>' : '';

						$speaker = $schedule_speaker ? '<h3>'.esc_html($schedule_speaker).'</h3>' : '';
						$speaker_title = $schedule_speaker_title ? '<span>'.esc_html($schedule_speaker_title).'</span>' : '';
						$venue = $schedule_venue ? '<span>'.esc_html($schedule_venue).'</span>' : '';
						$venue_title = $schedule_venue_title ? '<h3>'.esc_html($schedule_venue_title).'</h3>' : '';
						?>
						<div class="item">
							<div class="naeep-schedule-item naeep-item" style="background-image: url(<?php echo esc_url($image_url); ?>);">
						    <?php echo $date; ?>
						    <div class="naeep-schedule-info">
						    	<?php echo $title.$time; ?>
						    </div>
						    <div class="naeep-schedule-info">
						    	<?php echo $speaker.$speaker_title; ?>
						    </div>
						    <div class="naeep-schedule-info">
						    	<?php echo $venue_title.$venue; ?>
						    </div>
							</div>
						</div>
					<?php }
				} ?>
				</div>
			</div>
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Schedule() );

} // enable & disable