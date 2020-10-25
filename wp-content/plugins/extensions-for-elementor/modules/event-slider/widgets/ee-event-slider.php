<?php
namespace ElementorExtensions\Modules\EventSlider\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;

class EE_Event_Slider extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'event-slider';
	}

	public function get_title() {
		return __( 'Event Slider', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_script_depends() {
		return [ 'jquery-slick' ];
	}

	public function get_keywords() {
		return [ 'event slider', 'event', 'slider', 'es', 'e', 's' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Event Slider', 'elementor-extensions' ),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
			]
		);

		$slides_to_show = range( 1, 6 );

		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_control(
			'slide_orderby',
			[
				'label' => __( 'Sort By', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'elementor-extensions' ),
					'title' => __( 'Alphabet', 'elementor-extensions' ),
					'date' => __( 'Date', 'elementor-extensions' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slide_order',
			[
				'label' => __( 'Order', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC' => __( 'Asc', 'elementor-extensions' ),
					'DESC' => __( 'Desc', 'elementor-extensions' ),
				],
				'condition' => [
					'slide_orderby!' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label' => __( 'Slides to Show', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'elementor-extensions' ),
				] + $slides_to_show,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => __( 'Slides to Scroll', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => $slides_to_show,
				'condition' => [
					'slides_to_show!' => '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_rows',
			[
				'label' => __( 'Rows', 'elementor-extensions' ),
				'type' => Controls_Manager::NUMBER,
				'frontend_available' => true,
				'default' => 1,
			]
		);

		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both' => __( 'Arrows and Dots', 'elementor-extensions' ),
					'arrows' => __( 'Arrows', 'elementor-extensions' ),
					'dots' => __( 'Dots', 'elementor-extensions' ),
					'none' => __( 'None', 'elementor-extensions' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slide_excerpt',
			[
				'label' => __( 'Character Limit', 'elementor-extensions' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'step' => 1,
				'default' => 151,
			]
		);


		$this->add_control(
			'slide_offset',
			[
				'label' => __( 'Offset', 'elementor-extensions' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'step' => 1,
				'default' => 0,
			]
		);


		$this->add_control(
			'exclude',
			[
				'label' => __( 'Exclude By', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'current_post' => __( 'Current Post', 'elementor-extensions' ),
					'manual_selection' => __( 'Manual Selection', 'elementor-extensions' ),
					'terms' => __( 'Term', 'elementor-extensions' ),
					'authors' => __( 'Author', 'elementor-extensions' ),
				],
				'label_block' => true
			]
		);

		$events = $this->getEventSliderPostTypeOptions();
		$this->add_control(
			'exclude_ids',
			[
				'label' => __( 'Search & Select', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $events,
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'exclude' => 'manual_selection',
				]
			]
		);

		$terms = $this->getEventSliderTermOptions();
		$this->add_control(
			'exclude_term_ids',
			[
				'label' => __( 'Term', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $terms,
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'exclude' => 'terms',
				]
			]
		);

		$author = $this->getAuthorOptions();
		$this->add_control(
			'exclude_authors',
			[
				'label' => __( 'Author', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $author,
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'exclude' => 'authors'
				]
			]
		);
	
		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor-extensions' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'elementor-extensions' ),
					'no' => __( 'No', 'elementor-extensions' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'elementor-extensions' ),
					'no' => __( 'No', 'elementor-extensions' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'elementor-extensions' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'elementor-extensions' ),
					'no' => __( 'No', 'elementor-extensions' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'effect',
			[
				'label' => __( 'Effect', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'elementor-extensions' ),
					'fade' => __( 'Fade', 'elementor-extensions' ),
				],
				'condition' => [
					'slides_to_show' => '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Animation Speed', 'elementor-extensions' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => __( 'Left', 'elementor-extensions' ),
					'rtl' => __( 'Right', 'elementor-extensions' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'whole_event_block_heading',
			[
				'label' => __( 'Whole Event Block', 'elementor-extensions' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'whole_event_block_padding',
			[
				'label' => __( 'Whole Block Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'whole_event_block_background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'background: {{VALUE}};',
				]
			]
		);


		$this->add_control(
			'event_block_heading',
			[
				'label' => __( 'Individual Event Block', 'elementor-extensions' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'event_block_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 5,
					'right'  => 5,
					'bottom' => 5,
					'left'   => 5
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper > .elementor-image-carousel .slick-track > .slick-slide.ee_mb_event_slider, {{WRAPPER}} .elementor-image-carousel-wrapper > .elementor-image-carousel .slick-track > .slick-slide:not(.ee_mb_event_slider)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'event_block_padding',
			[
				'label' => __( 'Content Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 10,
					'right'  => 10,
					'bottom' => 10,
					'left'   => 10
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-slide.ee_mb_event_slider  .es_event_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'event_block_shadow',
				'label' => __( 'Box Shadow', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider,{{WRAPPER}} .elementor-image-carousel-wrapper .slick-track > .slick-slide > div',
			]
		);

		$this->add_control(
			'event_block_background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#bcbcbc',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-track > .slick-slide > div' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_block_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .imagebox-repeater-desc-inner-wrapper, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .imagebox-repeater-title-inner-wrapper, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .event_date_time > span' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'event_block_border',
				'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-track > .slick-slide.ee_mb_event_slider, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-track > .slick-slide > div',
				'separator' => 'before'
			]
		);


		$this->add_control(
			'event_block_align',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-extensions' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'event_block_minheight',
			[
				'label' => __( 'Min Height', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'description' => 'This is useful when slider have more than one rows',
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'default' => [
					'size' => 410,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-track > .slick-slide > div' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => __( 'Navigation', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label' => __( 'Arrows', 'elementor-extensions' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => __( 'Arrows Position', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => __( 'Inside', 'elementor-extensions' ),
					'outside' => __( 'Outside', 'elementor-extensions' ),
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => __( 'Arrows Size', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Arrows Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-next:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label' => __( 'Dots', 'elementor-extensions' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => __( 'Dots Position', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'outside' => __( 'Outside', 'elementor-extensions' ),
					'inside' => __( 'Inside', 'elementor-extensions' ),
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => __( 'Dots Size', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Dots Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-dots li button:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Height', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'default' => [
					'size' => 200,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_event_slider .ee_mb_img_wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_position',
			[
				'label' 		=> __( 'Position', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'default' 		=> 'top',
				'options' 		=> [
					'top'    => [
						'title' 	=> __( 'Top', 'elementor-extensions' ),
						'icon' 		=> 'eicon-v-align-top',
					],
					'center'    	=> [
						'title' 	=> __( 'Middle', 'elementor-extensions' ),
						'icon' 		=> 'eicon-v-align-middle',
					],
					'bottom' 	=> [
						'title' 	=> __( 'Bottom', 'elementor-extensions' ),
						'icon' 		=> 'eicon-v-align-bottom',
					],
				],
				'selectors'		=> [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .ee_mb_event_slider .ee_mb_img_wrapper' => 'background-position: center {{VALUE}}!important;',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .ee_mb_event_slider .ee_mb_img_wrapper',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .ee_mb_event_slider .ee_mb_img_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-slide.ee_mb_event_slider .ee_mb_img_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_datetime_style',
			[
				'label' => __( 'Date & Time', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'datetime_align',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-extensions' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .event_date_time' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'datetime_text_color',
			[
				'label' => __( 'Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .event_date_time > span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'datetime_typography',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .event_date_time span',
			]
		);

		$this->add_control(
			'datetime_top_spacing',
			[
				'label' => __( 'Top Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 5,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .event_date_time' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'datetime_bottom_spacing',
			[
				'label' => __( 'Bottom Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 5,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .event_date_time' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'datetime_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .event_date_time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'caption_align',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-extensions' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_text_color',
			[
				'label' => __( 'Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption .imagebox-repeater-title-inner-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption .imagebox-repeater-title-inner-wrapper',
			]
		);

		$this->add_control(
			'caption_spacing',
			[
				'label' => __( 'Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 10,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_title .imagebox-repeater-title-inner-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_description_style',
			[
				'label' => __( 'Description', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_align',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-extensions' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_desc' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'descriptiontext_color',
			[
				'label' => __( 'Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_desc .imagebox-repeater-desc-inner-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_desc .imagebox-repeater-desc-inner-wrapper',
			]
		);

		$this->add_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 10,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_desc' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .elementor-image-carousel-caption.slide_desc .imagebox-repeater-desc-inner-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_link_style',
			[
				'label' => __( 'Link', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'link_align',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-extensions' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .event_slider_link' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'link_styles' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'normal_link_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .event_slider_link a',
			]
		);

		$this->add_control(
			'normal_link_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .event_slider_link a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hover_link_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .event_slider_link a:hover',
			]
		);

		$this->add_control(
			'hover_link_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .event_slider_link a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'link_top_spacing',
			[
				'label' => __( 'Top Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 15,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .event_slider_link' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'link_bottom_spacing',
			[
				'label' => __( 'Bottom Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'show_label' => true,
				'selectors' => [
					'{{WRAPPER}} .event_slider_link' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slide.ee_mb_event_slider .event_slider_link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$slides = [];
		$event_sliders = $this->getEventSliderData($settings);

		if(empty($event_sliders)):
			printf( "Please add events", "elementor-extensions");
			return;
		endif;

        foreach ($event_sliders as $index => $event) {

			$id = $event->ID;
			$event_meta = get_post_meta($id);
	
			$start_date= (!empty($event_meta['_ee_mb_start_date'][0])) ? $event_meta['_ee_mb_start_date'][0] :'';
			$start_time = (!empty($event_meta['_ee_mb_start_time'][0])) ? $event_meta['_ee_mb_start_time'][0] :'';
			$end_time = (!empty($event_meta['_ee_mb_end_time'][0])) ? $event_meta['_ee_mb_end_time'][0] :'';
			$end_date = (!empty($event_meta['_ee_mb_end_date'][0])) ? $event_meta['_ee_mb_end_date'][0] :''; 
			$event_website = (!empty($event_meta['_ee_mb_event_website'][0])) ? $event_meta['_ee_mb_event_website'][0] :'';
			$event_location = (!empty($event_meta['_ee_mb_event_location'][0])) ? $event_meta['_ee_mb_event_location'][0] :'';
			
			$ee_mb_event_cat = $event_image = $ee_mb_event_tag = '';

			$event_cat_terms = wp_get_post_terms($id, 'ee_mb_event_cat', array("fields" => "all"));
			if(!empty($event_cat_terms)):
				$ee_mb_event_cat = $event_cat_terms[0]->name;
			endif;

			$event_tag_terms = wp_get_post_terms($id, 'ee_mb_event_tag', array("fields" => "all"));
			if(!empty($event_tag_terms)):
				$ee_mb_event_tag = $event_tag_terms[0]->name;
			endif;
		
			if(!empty($id)):
				$image_url = get_the_post_thumbnail_url($id,$settings['thumbnail_size']);
			elseif($settings['no_image'] == 'yes'):
				$image_url = plugins_url( 'assets/img/no-image.jpg', ELEMENTOR_ES__FILE__ );
			endif;

			$title = $event->post_title;
			$desc = $event->post_excerpt;

			if($settings['slide_excerpt']):
				$limit = $settings['slide_excerpt'];
			else:
				$limit = 151;
			endif;

			$desc = substr($desc, 0, $limit);
			
			$image_html = '<div class="ee_mb_img_wrapper" style="background:url('.esc_attr( $image_url ).')"></div>';

			$slide_html = '<div class="slick-slide ee_mb_event_slider"><figure class="slick-slide-inner">' . $image_html;

			$slide_html .= '<div class="es_event_content">';

			if(!empty($start_date) || !empty($start_time)):
				$slide_html .= '<div class="event_date_time">
				'.((!empty($start_date)) ? '<span>'.date('l jS F Y',strtotime($start_date)).'</span> ' :'').'
				'.((!empty($start_time)) ? '<span>'.$start_time.' - '.$end_time.'</span></div>' :'');
			endif;
			
			if (! empty($title)) {
				$slide_html .= '<figcaption class="elementor-image-carousel-caption slide_title"><div class="imagebox-repeater-title-inner-wrapper">'.$title.'</div></figcaption>';
			}
			
			if (! empty($desc)) {
				$slide_html .= '<figcaption class="elementor-image-carousel-caption slide_desc"><div class="imagebox-repeater-desc-inner-wrapper">'.$desc.'</div></figcaption>';
			}
			
			$slide_html .= '<div class="event_slider_link">
								<a href="'.get_permalink($id).'">Read more</a>
							</div>';

			$slide_html .= '</div>
				</figure>
			</div>';

			$slides[] = $slide_html;
           
		}
		

		if ( empty( $slides ) ) {
			return;
		}

		$this->add_render_attribute( 'carousel', 'class', 'elementor-image-carousel' );

		if ( 'none' !== $settings['navigation'] ) {
			if ( 'dots' !== $settings['navigation'] ) {
				$this->add_render_attribute( 'carousel', 'class', 'slick-arrows-' . $settings['arrows_position'] );
			}

			if ( 'arrows' !== $settings['navigation'] ) {
				$this->add_render_attribute( 'carousel', 'class', 'slick-dots-' . $settings['dots_position'] );
			}
		}
		?>
		
		<div class="elementor-image-carousel-wrapper elementor-slick-slider" dir="<?php echo $settings['direction']; ?>">
			<div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
				<?php echo implode( '', $slides ); ?>
			</div>
		</div>
		<?php
	}

	public function getEventSliderData($settings){

		$event_args = array(
			'post_type' => 'ee_mb_event_slider',
			'post_status' => 'publish',
		);

		/**
		 * Alphabetical & date wise sorting
		 */
		if(!empty($settings['slide_orderby'])):
			if($settings['slide_orderby'] == 'date'):
				$event_args['meta_key'] = '_ee_mb_start_date';
				$event_args['orderby'] = 'meta_value';
			else:
				$event_args['orderby'] = $settings['slide_orderby'];
			endif;
			$event_args['order'] = $settings['slide_order'];
		endif;

		if(!empty($settings['slide_offset'])):
			$event_args['offset'] = $settings['slide_offset'];
		else:
			$event_args['posts_per_page'] = -1;
		endif;

		if(!empty($settings['exclude'])):

			if(!empty($settings['exclude_ids']) && in_array('manual_selection',$settings['exclude'])):
				$event_args['exclude'] = $settings['exclude_ids'];
			endif;

			if(!empty($settings['exclude_term_ids']) && in_array('terms',$settings['exclude'])):
				$event_args['tax_query'] = array(
					'relation' => 'OR',
					array(
						'taxonomy'  => 'ee_mb_event_tag',
						'field'     => 'id',
						'terms'     => $settings['exclude_term_ids'],
						'operator'  => 'NOT IN'
					),
					array(
						'taxonomy'  => 'ee_mb_event_cat',
						'field'     => 'id',
						'terms'     => $settings['exclude_term_ids'],
						'operator'  => 'NOT IN'
					)
				);
			endif;

			if(!empty($settings['exclude_authors']) && in_array('authors',$settings['exclude'])):
				$event_args['author__not_in'] = $settings['exclude_authors'];
			endif;
		endif;

		return get_posts($event_args);
	}

	/**
	 * Get all the ee_mb_event_slider post type
	 * Return options
	 */
	public function getEventSliderPostTypeOptions(){

		$media_query = new \WP_Query(
			array(
				'post_type' => 'ee_mb_event_slider',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			)
		);

		$list = array();
		foreach ($media_query->posts as $post) {
			$name =  $post->post_title;
			$list[$post->ID] = __( $name, 'elementor-extensions' );
		}
		return $list;
	}

	/**
	 * Get all the ee_mb_event_slider Terms
	 * Return options
	 */
	public function getEventSliderTermOptions(){

		$tag =  get_terms( array(
			'taxonomy' => 'ee_mb_event_tag',
			'hide_empty' => false,
		));

		$cat =  get_terms( array(
			'taxonomy' => 'ee_mb_event_cat',
			'hide_empty' => false,
		));
	
		$term_list = array_merge($tag, $cat);
		$list = array();
		foreach ($term_list as $term) {
			$name =  $term->name;
			$list[$term->term_id] = __( $name, 'elementor-extensions' );
		}
		return $list;
	}


	/**
	 * Get all the authors
	 * Return options
	 */
	public function getAuthorOptions(){
		$users = $blogusers = get_users( array( 'fields' => array( 'display_name', 'ID', 'user_nicename' ) ) );
	
		$list = array();
		foreach ($users as $user) {
			$name =  (!empty($user->user_nicename)) ? $user->user_nicename : $user->display_name ;
			$list[$user->ID] = __( $name, 'elementor-extensions' );
		}
		return $list;
	}
}
