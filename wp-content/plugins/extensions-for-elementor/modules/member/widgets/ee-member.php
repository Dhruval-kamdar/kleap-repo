<?php
namespace ElementorExtensions\Modules\Member\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

class EE_Member extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'member';
	}

	public function get_title() {
		return __( 'Member', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_keywords() {
		return [ 'member', 'mem', 'me', 'm' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
            'global_style',
            [
                'label' => __( 'Global Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'members_background',
				'label' => __( 'Background', 'elementor-extensions' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .all_members_wrapper',
				'show_label' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'members_border',
				'placeholder' => '1px',
				'default' => '0px',
				'selector' => '{{WRAPPER}} .all_members_wrapper',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
			]
		);

		$this->add_responsive_control(
			'members_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'members_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'filter_style',
            [
                'label' => __( 'Filter Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'default_member_sort',
			[
				'label' => __( 'Default Sort', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'default' => '_ee_mb_company_name',
				'options' => [
					'_ee_mb_company_name'  => __( 'Company Name', 'elementor-extensions' ),
					'name' => __( 'Name', 'elementor-extensions' ),
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'filter_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .filters_wrapper select',
            ]
		);
		
		$this->add_control(
            'filter_background_color',
            [
                'label' => __( 'Background Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .all_members_wrapper .filters_wrapper select' => 'background-color: {{VALUE}};',
                ],
            ]
		);

		$this->add_control(
            'filter_font_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .filters_wrapper select' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'filter_select_border',
				'placeholder' => '1px',
				'default' => '0px',
				'selector' => '{{WRAPPER}} .all_members_wrapper .filters_wrapper select',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filter_select_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .filters_wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'filter_alignment',
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
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .filters_wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .filters_wrapper select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_marging',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .filters_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'individual_member_style',
            [
                'label' => __( 'Individual Member Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'single_members_background',
				'label' => __( 'Background', 'elementor-extensions' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper',
				'show_label' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'single_member_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
			]
		);

		$this->add_responsive_control(
			'individual_member_height',
			[
				'label' 	=> __( 'Height', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' 	=> [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper' => 'height: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'single_member_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'single_member_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'image_style',
            [
                'label' => __( 'Left Image Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'image_size',
			[
				'label' 	=> __( 'Image Quality', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'medium',
				'options' 	=> [
					'thumbnail' 		=> __( 'Thumbnail', 'elementor-extensions' ),
					'medium' 	=> __( 'Medium', 'elementor-extensions' ),
					'large' 	=> __( 'Large', 'elementor-extensions' ),
					'medium_large' 	=> __( 'Medium Large', 'elementor-extensions' ),
					'full' 	=> __( 'Full', 'elementor-extensions' ),
				],
			]
		);

		$this->add_responsive_control(
			'member_image_max_height',
			[
				'label' 	=> __( 'Image Max Height', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' 	=> [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .members_wrapper .single_member_wrapper > div.member_image_wrapper .member_image_inner_wrapper img' => 'max-height: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'member_image_width',
			[
				'label' 	=> __( 'Image Width', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' 	=> [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .members_wrapper .single_member_wrapper > div.member_image_wrapper .member_image_inner_wrapper' => 'width: {{SIZE}}%;',
				],
			]
		);

		$this->add_control(
			'no_image',
			[
				'label' => __( 'Image Placeholder', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-extensions' ),
				'label_off' => __( 'Hide', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'member_image_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper > div.member_image_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_image_align',
			[
				'label' => __( 'Image Alignment', 'elementor-extensions' ),
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
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .members_wrapper .single_member_wrapper > div.member_image_wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_image_vertical_align',
			[
				'label' => __( 'Vertical Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'elementor-extensions' ),
						'icon' => 'fa fa-chevron-up',
					],
					'middle' => [
						'title' => __( 'Middle', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'elementor-extensions' ),
						'icon' => 'fa fa-chevron-down',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .members_wrapper .single_member_wrapper > div.member_image_wrapper' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'right_content_style',
            [
                'label' => __( 'Right Content Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'members_content_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div',
            ]
        );

		$this->add_control(
            'members_content_font_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'member_content_align',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'label_style',
            [
                'label' => __( 'Label Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'label_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div label',
            ]
        );

		$this->add_control(
            'lable_font_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper label' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'lable_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'name_style',
            [
                'label' => __( 'Name Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'show_name_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div.member_name > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'name_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_name',
            ]
        );

		$this->add_control(
            'name_font_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_name' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'name_alignment',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_name' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'name_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'company_name_style',
            [
                'label' => __( 'Company Name Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'show_company_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div.company_name > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'company_name_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .company_name',
            ]
        );

		$this->add_control(
            'company_name_font_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .company_name' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'company_name_alignment',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .company_name' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'company_name_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .company_name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'industry_sector_style',
            [
                'label' => __( 'Status Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'show_industry_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div.member_status > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'industry_sector_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_status',
            ]
        );

		$this->add_control(
            'industry_sector_font_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_status' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'industry_sector_alignment',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_status' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'industry_sector_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .member_status' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'status_style',
            [
                'label' => __( 'Industry Sector Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'show_status_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div.industrial_sector > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'status_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .industrial_sector',
            ]
        );

		$this->add_control(
            'status_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .industrial_sector' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'status_alignment',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .industrial_sector' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'status_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .industrial_sector' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'linkedin_style',
            [
                'label' => __( 'LinkedIn Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'linkedin_url_new_tab',
			[
				'label' => __( 'Open Link In New Tab', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'show_linkedin_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div.linkedin > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'linkedin_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .linkedin p a ',
            ]
        );

		$this->add_control(
            'linkedin_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .linkedin p a' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'linkedin_alignment',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .linkedin' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'linkedin_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .linkedin' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'business_journey_style',
            [
                'label' => __( 'Business Journey Style', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => true,
            ]
		);

		$this->add_control(
			'show_business_journey_label',
			[
				'label' => __( 'Show Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'inline-block',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > div.business_journey > label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'name' => 'business_journey_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
                'selector' => '{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .business_journey',
            ]
        );

		$this->add_control(
            'business_journey_color',
            [
                'label' => __( 'Font Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .business_journey' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'business_journey_alignment',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .business_journey' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'business_journey_margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .all_members_wrapper .members_wrapper .single_member_wrapper .member_content_wrapper > .business_journey' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$members = $this->getMembersData($settings);

		echo '<div class="all_members_wrapper">';

		if(!empty($members) || (empty($members) && isset($_GET['statusby']))):
			$this->getFilters($settings);
		endif;

		$new_tab = '';
		if(!empty($settings['linkedin_url_new_tab']) && $settings['linkedin_url_new_tab'] == 'yes'):
			$new_tab = 'target="_blank"';
		endif;

		if(!empty($members)):
			echo '<div class="members_wrapper" id="members_wrapper">';
			foreach($members as $key => $member):
				$id = $member->ID;
				$member_meta = get_post_meta($id);

				$profile_id= (!empty($member_meta['_ee_mb_profile_image'][0])) ? $member_meta['_ee_mb_profile_image'][0] : '';

				$name = (!empty($member_meta['_ee_mb_name'][0])) ? $member_meta['_ee_mb_name'][0] :'';
				$company_name = (!empty($member_meta['_ee_mb_company_name'][0])) ? $member_meta['_ee_mb_company_name'][0] :'';
				$business_journey = (!empty($member_meta['_ee_mb_business_journey'][0])) ? $member_meta['_ee_mb_business_journey'][0] :''; 
				$linkedin = (!empty($member_meta['_ee_mb_linkedin'][0])) ? $member_meta['_ee_mb_linkedin'][0] :'';
				
				$status = $profile = $industrial_sector = '';

				$status_terms = wp_get_post_terms($id, 'ee_mb_member_status', array("fields" => "all"));
				if(!empty($status_terms)):
					$status = $status_terms[0]->name;
				endif;

				$industrial_terms = wp_get_post_terms($id, 'ee_mb_member_industrial_sector', array("fields" => "all"));
				if(!empty($industrial_terms)):
					$industrial_sector = $industrial_terms[0]->name;
				endif;
			
				if(!empty($profile_id)):
					$profile_arr = wp_get_attachment_image_src($profile_id,$settings['image_size']);
					$profile = $profile_arr[0];
				elseif($settings['no_image'] == 'yes'):
					$profile = plugins_url( 'assets/img/no-image.jpg', ELEMENTOR_EXTENSIONS__FILE__ );
				endif;

				echo '<div class="single_member_wrapper">';
					echo '<div class="member_image_wrapper">';
						echo '<div class="member_image_inner_wrapper">';
							echo '<img src="'.$profile.'"/>';
						echo '</div>';
					echo '</div>';

					echo '<div class="member_content_wrapper">';
						if(!empty($name)):
							echo '<div class="member_name"><label>Name: </label> <p>'.$name.'</p></div>';
						endif;

						if(!empty($company_name)):
							echo '<div class="company_name"><label>Company Name: </label> <p>'.$company_name.'</p></div>';
						endif;

						if(!empty($status)):
							echo '<div class="member_status"><label>Status: </label> <p>'.$status.'</p></div>';
						endif;

						if(!empty($industrial_sector)):
							echo '<div class="industrial_sector"><label>Industrial Sector: </label> <p>'.$industrial_sector.'</p></div>';
						endif;

						if(!empty($linkedin)):
							echo '<div class="linkedin"><label>LinkedIn: </label> <p><a href="'.$linkedin.'" '.$new_tab.'>'.$linkedin.'</a></p></div>';
						endif;

						if(!empty($business_journey)):
							echo '<div class="business_journey"><label>Business Journey: </label> <p>'.$business_journey.'</p></div>';
						endif;

					echo '</div>';
				echo '</div>';
				echo '<div style="clear:both;"></div>';
			endforeach;
			echo '</div>';

		else:
			echo '<p align="center" class="no_member_found">No member found</p>';
		endif;	
		
		echo '</div>';
		echo '<div style="clear:both;"></div>';
	}

	public function getFilters($settings){

		$get_filters = get_terms( array(
			'taxonomy' => 'filter',
			'hide_empty' => true,
		));
		
		echo '<div class="filters_wrapper">';

			$remembered_sort = $remembered_industry = $remembered_statusby = '';

			$get_industrial_sector = get_terms( array(
				'taxonomy' => 'ee_mb_member_industrial_sector',
				'hide_empty' => true,
			));

			if(!empty($get_industrial_sector)):
				if(isset($_GET['industry'])):
					$remembered_industry = esc_html($_GET['industry']);
				endif;
				echo '<div class="inner_filter_wrapper">';
					echo '<select class="drp_industrial_sector ee_mb_drp_member" id="drp_industrial_sector">';
						echo '<option value="" selected> -- Industrial Sector -- </option>';
						foreach($get_industrial_sector as $key => $filter):
							echo '<option value="'.$filter->term_id.'" '.(( $filter->term_id == $remembered_industry) ? 'selected' : '').'>'.$filter->name.'</option>';
						endforeach;
					echo '</select>';
				echo '</div>';
			endif;

			$get_status = get_terms( array(
				'taxonomy' => 'ee_mb_member_status',
				'hide_empty' => true,
			));

			if(!empty($get_status)):
				if(isset($_GET['statusby'])):
					$remembered_statusby = esc_html($_GET['statusby']);
				endif;
				echo '<div class="inner_filter_wrapper">';
					echo '<select class="drp_status ee_mb_drp_member" id="drp_status">';
						echo '<option value="" selected> -- Status -- </option>';
						foreach($get_status as $key => $filter):
							echo '<option value="'.$filter->term_id.'" '.(( $filter->term_id == $remembered_statusby) ? 'selected' : '').'>'.$filter->name.'</option>';
						endforeach;
					echo '</select>';
				echo '</div>';
			endif;

			if(isset($_GET['sortby'])):
				$remembered_sort = esc_html($_GET['sortby']);
			else:
				$remembered_sort = $settings['default_member_sort'];
			endif;

			echo '<div class="sorting_wrapper">';
				echo '<select class="drp_sorting ee_mb_drp_member" id="drp_sorting">';
					echo '<option value="" selected> -- Sort By -- </option>';
					echo '<option value="_ee_mb_name" '.(( $remembered_sort == '_ee_mb_name') ? 'selected' : '').'>Name</option>';
					echo '<option value="_ee_mb_company_name" '.(( $remembered_sort == '_ee_mb_company_name') ? 'selected' : '').'>Company Name</option>';
				echo '</select>';
			echo '</div>';

		echo '</div>';
		echo '<div style="clear:both;"></div>';
	}

	public function getMembersData($settings){

		$member_args = array(
			'post_type' => 'ee_mb_member',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		);

		if(!empty($_GET['sortby'])){
			$member_args['meta_key'] = sanitize_text_field($_GET['sortby']);
			$member_args['orderby'] = 'meta_value';
			$member_args['order'] = 'ASC';
		}else{
			$member_args['meta_key'] = $settings['default_member_sort'];
			$member_args['orderby'] = 'meta_value';
			$member_args['order'] = 'ASC';
		}

		if(!empty($_GET['filterby']) || !empty($_GET['statusby']) || !empty($_GET['industry'])){
			$filterby = $statusby = $industry = '';

			if(!empty($_GET['filterby'])){
				$filterby = array(
					'taxonomy'=> 'filter',
					'field' => 'id',
					'terms' => sanitize_text_field($_GET['filterby']),
					'include_children' => false
				);
			}

			if(!empty($_GET['statusby'])){
				$statusby = array(
					'taxonomy'=> 'ee_mb_member_status',
					'field' => 'id',
					'terms' => sanitize_text_field($_GET['statusby']),
					'include_children' => false
				);
			}

			if(!empty($_GET['industry'])){
				$industry = array(
					'taxonomy'=> 'ee_mb_member_industrial_sector',
					'field' => 'id',
					'terms' => sanitize_text_field($_GET['industry']),
					'include_children' => false
				);
			}
			
			$member_args['tax_query'] = array(
				'relation' => 'AND',
				$filterby, 
				$statusby,
				$industry
			);
		}

		return get_posts($member_args);
	}

	protected function _content_template() {
		
	}	
}
