<?php
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

class Widget_Cep_Eael_Info_Box extends \Elementor\Widget_Base {


	public function get_name() {
		return 'cep-eael-info-box';
	}

	public function get_title() {
		return esc_html__( 'Content Editor: Info Box', 'elementor' );
	}

	public function get_icon() {
		return 'eicon-info-box';
	}

   public function get_categories() {
		return [ 'content-editor-addons' ];
	}

	protected function _register_controls() {

  		/**
  		 * Infobox Image Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_infobox_content_settings',
  			[
  				'label' => esc_html__( 'Infobox Image', 'elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_infobox_img_type',
		  	[
		   	'label'       	=> esc_html__( 'Infobox Type', 'elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'img-on-top',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'img-on-top'  	=> esc_html__( 'Image/Icon On Top', 'elementor' ),
		     		'img-on-left' 	=> esc_html__( 'Image/Icon On Left', 'elementor' ),
		     		'img-on-right' 	=> esc_html__( 'Image/Icon On Right', 'elementor' ),
		     	],
		  	]
		);

		$this->add_responsive_control(
			'eael_infobox_img_or_icon',
			[
				'label' => esc_html__( 'Image or Icon', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'none' => [
						'title' => esc_html__( 'None', 'elementor' ),
						'icon' => 'fa fa-ban',
					],
					'number' => [
						'title' => esc_html__( 'Number', 'elementor' ),
						'icon' => 'fa fa-sort-numeric-desc',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'elementor' ),
						'icon' => 'fa fa-info-circle',
					],
					'img' => [
						'title' => esc_html__( 'Image', 'elementor' ),
						'icon' => 'fa fa-picture-o',
					]
				],
				'default' => 'icon',
			]
		);

		$this->add_responsive_control(
			'icon_vertical_position',
			[
				'label'                 => __( 'Icon Position', 'elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'top',
				'condition'			=> [
					'eael_infobox_img_type!'	=> 'img-on-top'
				],
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'elementor' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Middle', 'elementor' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'elementor' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .eael-infobox .infobox-icon'	=> 'align-self: {{VALUE}};'
				],
				'selectors_dictionary'  => [
					'top'          => 'baseline',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
			]
		);
		
		$this->add_control(
			'graphic_element_image',
			[
				'label' => __( 'Infobox Image or Shortcode', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'eael_infobox_image_short' => [
						'title' => __( 'Shortcode', 'elementor-pro' ),
						'icon' => 'fa fa-code',
					],
					'eael_infobox_image' => [
						'title' => __( 'Image', 'elementor-pro' ),
						'icon' => 'fa fa-picture-o',
					],
				],
				'default' => 'eael_infobox_icon_short',
				'condition' => [
					'eael_infobox_img_or_icon' => 'img',
				],
			]
		);
		
		
		$this->add_control(
			'eael_infobox_image_short',
			[
				'label' => __( 'Infobox Shortcode', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '[short type=fieldtype item=0]',
				'condition' => [
					'graphic_element_image' => 'eael_infobox_image_short',
					'eael_infobox_img_or_icon' => 'img',
				],
			]
		);
		

		/**
		 * Condition: 'eael_infobox_img_or_icon' => 'img'
		 */
		$this->add_control(
			'eael_infobox_image',
			[
				'label' => esc_html__( 'Infobox Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'graphic_element_image' => 'eael_infobox_image',
					'eael_infobox_img_or_icon' => 'img',
				],
			]
		);
		
		$this->add_control(
			'graphic_element',
			[
				'label' => __( 'Icon or Shortcode', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'eael_infobox_icon_short' => [
						'title' => __( 'Shortcode', 'elementor-pro' ),
						'icon' => 'fa fa-code',
					],
					'eael_infobox_icon' => [
						'title' => __( 'Icon', 'elementor-pro' ),
						'icon' => 'fa fa-star',
					],
				],
				'default' => 'eael_infobox_icon_short',
				'condition' => [
					'eael_infobox_img_or_icon' => 'icon',
				],
			]
		);
		
		
		$this->add_control(
			'eael_infobox_icon_short',
			[
				'label' => __( 'Shortcode', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '[short type=fieldtype item=0]',
				'condition' => [
					'graphic_element' => 'eael_infobox_icon_short',
					'eael_infobox_img_or_icon' => 'icon',
				],
			]
		);
		
		/**
		 * Condition: 'eael_infobox_img_or_icon' => 'icon'
		 */
		$this->add_control(
			'eael_infobox_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-building-o',
				'condition' => [
					'eael_infobox_img_or_icon' => 'icon',
					'graphic_element' => 'eael_infobox_icon',

				]
			]
		);

		/**
		 * Condition: 'eael_infobox_img_or_icon' => 'number'
		 */
		$this->add_control(
			'eael_infobox_number',
			[
				'label' => esc_html__( 'Number', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'eael_infobox_img_or_icon' => 'number'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * Infobox Content
		 */
		$this->start_controls_section(
			'eael_infobox_content',
			[
				'label' => esc_html__( 'Infobox Content', 'elementor' ),
			]
		);
		$this->add_control(
			'eael_infobox_title',
			[
				'label' => esc_html__( 'Infobox Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				],
				'default' => esc_html__( 'This is an icon box', 'elementor' )
			]
		);
		$this->add_control(
            'eael_infobox_text_type',
            [
                'label'                 => __( 'Content Type', 'elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                    'content'       => __( 'Content', 'elementor' ),
                    'template'      => __( 'Saved Templates', 'elementor' ),
                ],
                'default'               => 'content',
            ]
        );

        $this->add_control(
            'eael_primary_templates',
            [
                'label'                 => __( 'Choose Template', 'elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => eael_get_page_templates(),
				'condition'             => [
					'eael_infobox_text_type'      => 'template',
				],
            ]
        );
		$this->add_control(
			'eael_infobox_text',
			[
				'label' => esc_html__( 'Infobox Content', 'elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				],
				'default' => esc_html__( 'Write a short description, that will describe the title or something informational and useful.', 'elementor' ),
				'condition'             => [
					'eael_infobox_text_type'      => 'content',
				],
			]
		);
		$this->add_control(
			'eael_show_infobox_content',
			[
				'label' => __( 'Show Content', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'elementor' ),
				'label_off' => __( 'Hide', 'elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_responsive_control(
			'eael_infobox_content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'eael-infobox-content-align-',
				'condition' => [
					'eael_infobox_img_type' => 'img-on-top'
				]
			]
		);
		$this->end_controls_section();

		/**
		 * ----------------------------------------------
		 * Infobox Button
		 * ----------------------------------------------
		 */
		$this->start_controls_section(
			'eael_infobox_button',
			[
				'label' => esc_html__( 'Link', 'elementor' )
			]
		);

		$this->add_control(
			'eael_show_infobox_button',
			[
				'label' => __( 'Show Infobox Button', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor' ),
				'label_off' => __( 'No', 'elementor' ),
				'condition'	=> [
					'eael_show_infobox_clickable!'	=> 'yes'
				]
			]
		);

		$this->add_control(
			'eael_show_infobox_clickable',
			[
				'label' => __( 'Infobox Clickable', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'elementor' ),
				'label_off' => __( 'No', 'elementor' ),
				'return_value' => 'yes',
				'condition'	=> [
					'eael_show_infobox_button!'	=> 'yes'
				]
			]
		);

		$this->add_control(
			'eael_show_infobox_clickable_link',
			[
				'label' => esc_html__( 'Infobox Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
        			'url' => '#',
        			'is_external' => '',
     			],
     			'show_external' => true,
     			'condition' => [
     				'eael_show_infobox_clickable' => 'yes'
     			]
			]
		);

		$this->add_control(
			'infobox_button_text',
			[
				'label' => __( 'Button Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Click Me!',
				'separator'	=> 'before',
				'placeholder' => __( 'Enter button text', 'elementor' ),
				'title' => __( 'Enter button text here', 'elementor' ),
				'condition'	=> [
					'eael_show_infobox_button'	=> 'yes'
				]
			]
		);

		$this->add_control(
			'infobox_button_link_url',
			[
				'label' => __( 'Link URL', 'elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'Enter link URL for the button', 'elementor' ),
				'show_external'	=> true,
				'default'		=> [
					'url'	=> '#'
				],
				'title' => __( 'Enter heading for the button', 'elementor' ),
				'condition'	=> [
					'eael_show_infobox_button'	=> 'yes'
				]
			]
		);
		
		$this->add_control(
			'eael_infobox_button_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
				'condition'	=> [
					'eael_show_infobox_button'	=> 'yes'
				]
			]
		);

		$this->add_control(
			'eael_infobox_button_icon_alignment',
			[
				'label' => esc_html__( 'Icon Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'elementor' ),
					'right' => esc_html__( 'After', 'elementor' ),
				],
				'condition' => [
					'eael_infobox_button_icon!' => '',
					'eael_show_infobox_button'	=> 'yes'
				],
			]
		);

		$this->add_control(
			'eael_infobox_button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 60,
					],
				],
				'condition' => [
					'eael_infobox_button_icon!' => '',
					'eael_show_infobox_button'	=> 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .eael_infobox_button_icon_right' => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} .eael_infobox_button_icon_left' => 'margin-right: {{SIZE}}px;',
				],
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Image)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_imgae_style_settings',
			[
				'label' => esc_html__( 'Image Style', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_infobox_img_or_icon' => 'img'
		     	]
			]
		);

		$this->start_controls_tabs('eael_infobox_image_style');
			
			$this->start_controls_tab(
				'eael_infobox_image_icon_normal',
				[
					'label'		=> __( 'Normal', 'elementor' )
				]
			);

				$this->add_control(
					'eael_infobox_image_icon_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-infobox .infobox-icon img' => 'background-color: {{VALUE}};',
						]
					]
				);

				$this->add_responsive_control(
					'eael_infobox_image_icon_padding',
					[
						'label' => esc_html__( 'Padding', 'elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => [
							'{{WRAPPER}} .eael-infobox .infobox-icon img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						 ],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_infobox_image_border',
							'label' => esc_html__( 'Border', 'elementor' ),
							'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon img'
						]
				);
		
				$this->add_control(
				'eael_infobox_img_shape',
					[
					'label'     	=> esc_html__( 'Image Shape', 'elementor' ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'square',
						'label_block' 	=> false,
						'options' 		=> [
							'square'  	=> esc_html__( 'Square', 'elementor' ),
							'circle' 	=> esc_html__( 'Circle', 'elementor' ),
							'radius' 	=> esc_html__( 'Radius', 'elementor' ),
						],
						'prefix_class' => 'eael-infobox-shape-',
						'condition' => [
							'eael_infobox_img_or_icon' => 'img'
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'eael_infobox_image_icon_hover',
				[
					'label'		=> __( 'Hover', 'elementor' )
				]
			);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'eael_infobox_image_icon_hover_shadow',
						'selectors' => [
							'{{WRAPPER}} .eael-infobox .infobox-icon:hover img' => 'background-color: {{VALUE}};',
						]
					]
				);

				$this->add_control(
					'eael_infobox_image_icon_hover_animation',
					[
						'label' => esc_html__( 'Animation', 'elementor' ),
						'type' => Controls_Manager::HOVER_ANIMATION
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_infobox_hover_image_border',
							'label' => esc_html__( 'Border', 'elementor' ),
							'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon img'
						]
				);
		
				$this->add_control(
				'eael_infobox_hover_img_shape',
					[
					'label'     	=> esc_html__( 'Image Shape', 'elementor' ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'square',
						'label_block' 	=> false,
						'options' 		=> [
							'square'  	=> esc_html__( 'Square', 'elementor' ),
							'circle' 	=> esc_html__( 'Circle', 'elementor' ),
							'radius' 	=> esc_html__( 'Radius', 'elementor' ),
						],
						'prefix_class' => 'eael-infobox-hover-img-shape-',
						'condition' => [
							'eael_infobox_img_or_icon' => 'img'
						]
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_infobox_image_resizer',
			[
				'label' => esc_html__( 'Image Resizer', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100
				],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-icon img' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .eael-infobox.icon-on-left .infobox-icon' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .eael-infobox.icon-on-right .infobox-icon' => 'width: {{SIZE}}px;',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'condition' => [
					'eael_infobox_image[url]!' => '',
				],
				'condition' => [
					'eael_infobox_img_or_icon' => 'img',
				]
			]
		);

		$this->add_responsive_control(
			'eael_infobox_img_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-infobox .infobox-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->end_controls_section();


		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Number Icon Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_number_icon_style_settings',
			[
				'label' => esc_html__( 'Number Icon Style', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_infobox_img_or_icon' => 'number'
		     	]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
			'name' => 'eael_infobox_number_icon_typography',
				'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-number',
			]
		);

		$this->add_responsive_control(
    		'eael_infobox_number_icon_bg_size',
    		[
        		'label' => __( 'Icon Background Size', 'elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 90,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 300,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
        		],
        		'condition' => [
					'eael_infobox_icon_bg_shape!' => 'none'
				]
    		]
		);

		$this->add_responsive_control(
			'eael_infobox_number_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-infobox .infobox-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->start_controls_tabs( 'eael_infobox_numbericon_style_controls' );

			$this->start_controls_tab(
				'eael_infobox_number_icon_normal',
				[
					'label'		=> esc_html__( 'Normal', 'elementor' ),
				]
			);

				$this->add_control(
					'eael_infobox_number_icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#4d4d4d',
						'selectors' => [
							'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-number' => 'color: {{VALUE}};',
							'{{WRAPPER}} .eael-infobox.icon-beside-title .infobox-content .title figure .infobox-icon-number' => 'color: {{VALUE}};',
						],
					]
				);
		
				$this->add_control(
					'eael_infobox_number_icon_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
						],
						'condition' => [
							'eael_infobox_icon_bg_shape!' => 'none',
						]
					]
				);
		
				$this->add_control(
				'eael_infobox_number_icon_bg_shape',
					[
					'label'     	=> esc_html__( 'Background Shape', 'elementor' ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'none',
						'label_block' 	=> false,
						'options' 		=> [
							'none'  	=> esc_html__( 'None', 'elementor' ),
							'circle' 	=> esc_html__( 'Circle', 'elementor' ),
							'radius' 	=> esc_html__( 'Radius', 'elementor' ),
							'square' 	=> esc_html__( 'Square', 'elementor' ),
						],
						'prefix_class' => 'eael-infobox-icon-bg-shape-'
					]
				);
		
				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_infobox_number_icon_border',
							'label' => esc_html__( 'Border', 'elementor' ),
							'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap'
						]
				);
		
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'eael_infobox_number_icon_shadow',
						'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap',
					]
				);

			$this->end_controls_tab();


			$this->start_controls_tab(
				'eael_infobox_number_icon_hover',
				[
					'label'		=> esc_html__( 'Hover', 'elementor' ),
				]
			);

			$this->add_control(
				'eael_infobox_number_icon_hover_animation',
				[
					'label' => esc_html__( 'Animation', 'elementor' ),
					'type' => Controls_Manager::HOVER_ANIMATION
				]
			);

			$this->add_control(
				'eael_infobox_number_icon_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#4d4d4d',
					'selectors' => [
						'{{WRAPPER}} .eael-infobox:hover .infobox-icon .infobox-icon-number' => 'color: {{VALUE}};',
						'{{WRAPPER}} .eael-infobox.icon-beside-title:hover .infobox-content .title figure .infobox-icon-number' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_infobox_number_icon_hover_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .eael-infobox:hover .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
					],
					'condition' => [
						'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
						'eael_infobox_icon_bg_shape!' => 'none',
					]
				]
			);

			$this->add_control(
			'eael_infobox_number_icon_hover_bg_shape',
				[
				'label'     	=> esc_html__( 'Background Shape', 'elementor' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'none',
					'label_block' 	=> false,
					'options' 		=> [
						'none'  	=> esc_html__( 'None', 'elementor' ),
						'circle' 	=> esc_html__( 'Circle', 'elementor' ),
						'radius' 	=> esc_html__( 'Radius', 'elementor' ),
						'square' 	=> esc_html__( 'Square', 'elementor' ),
					],
					'prefix_class' => 'eael-infobox-icon-hover-bg-shape-',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
					[
						'name' => 'eael_infobox_hover_number_icon_border',
						'label' => esc_html__( 'Border', 'elementor' ),
						'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap'
					]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'eael_infobox_number_icon_hover_shadow',
					'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Icon Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_icon_style_settings',
			[
				'label' => esc_html__( 'Icon Style', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_infobox_img_or_icon' => 'icon'
		     	]
			]
		);

		$this->add_responsive_control(
    		'eael_infobox_icon_size',
    		[
        		'label' => __( 'Icon Size', 'elementor' ),
       		'type' => Controls_Manager::SLIDER,
        		'default' => [
            	'size' => 40,
        		],
        		'range' => [
            	'px' => [
                	'min' => 20,
                	'max' => 100,
                	'step' => 1,
            	]
        		],
        		'selectors' => [
            	'{{WRAPPER}} .eael-infobox .infobox-icon i' => 'font-size: {{SIZE}}px;',
        		],
    		]
		);

		$this->add_responsive_control(
    		'eael_infobox_icon_bg_size',
    		[
        		'label' => __( 'Icon Background Size', 'elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 90,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 300,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
        		],
        		'condition' => [
					'eael_infobox_icon_bg_shape!' => 'none'
				]
    		]
		);

		$this->add_responsive_control(
			'eael_infobox_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-infobox .infobox-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

			$this->start_controls_tabs( 'eael_infobox_icon_style_controls' );

				$this->start_controls_tab(
					'eael_infobox_icon_normal',
					[
						'label'		=> esc_html__( 'Normal', 'elementor' ),
					]
				);

					$this->add_control(
						'eael_infobox_icon_color',
						[
							'label' => esc_html__( 'Icon Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '#4d4d4d',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox .infobox-icon i' => 'color: {{VALUE}};',
								'{{WRAPPER}} .eael-infobox.icon-beside-title .infobox-content .title figure i' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'eael_infobox_icon_bg_shape',
						[
						'label'     	=> esc_html__( 'Background Shape', 'elementor' ),
							'type' 			=> Controls_Manager::SELECT,
							'default' 		=> 'none',
							'label_block' 	=> false,
							'options' 		=> [
								'none'  	=> esc_html__( 'None', 'elementor' ),
								'circle' 	=> esc_html__( 'Circle', 'elementor' ),
								'radius' 	=> esc_html__( 'Radius', 'elementor' ),
								'square' 	=> esc_html__( 'Square', 'elementor' ),
							],
							'prefix_class' => 'eael-infobox-icon-bg-shape-'
						]
					);
			
					$this->add_control(
						'eael_infobox_icon_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
							],
							'condition' => [
								'eael_infobox_icon_bg_shape!' => 'none',
							]
						]
					);
			
					$this->add_group_control(
						Group_Control_Border::get_type(),
							[
								'name' => 'eael_infobox_icon_border',
								'label' => esc_html__( 'Border', 'elementor' ),
								'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap'
							]
					);
			
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'eael_infobox_icon_shadow',
							'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap',
						]
					);

				$this->end_controls_tab();


				$this->start_controls_tab(
					'eael_infobox_icon_hover',
					[
						'label'		=> esc_html__( 'Hover', 'elementor' ),
					]
				);

				$this->add_control(
					'eael_infobox_icon_hover_animation',
					[
						'label' => esc_html__( 'Animation', 'elementor' ),
						'type' => Controls_Manager::HOVER_ANIMATION
					]
				);

				$this->add_control(
					'eael_infobox_icon_hover_color',
					[
						'label' => esc_html__( 'Icon Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#4d4d4d',
						'selectors' => [
							'{{WRAPPER}} .eael-infobox:hover .infobox-icon i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .eael-infobox.icon-beside-title:hover .infobox-content .title figure i' => 'color: {{VALUE}};',
						],
					]
				);
		
				$this->add_control(
					'eael_infobox_icon_hover_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-infobox:hover .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
						],
						'condition' => [
							'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
							'eael_infobox_icon_bg_shape!' => 'none',
						]
					]
				);
		
				$this->add_control(
				  'eael_infobox_icon_hover_bg_shape',
					  [
					   'label'     	=> esc_html__( 'Background Shape', 'elementor' ),
						 'type' 			=> Controls_Manager::SELECT,
						 'default' 		=> 'none',
						 'label_block' 	=> false,
						 'options' 		=> [
							 'none'  	=> esc_html__( 'None', 'elementor' ),
							 'circle' 	=> esc_html__( 'Circle', 'elementor' ),
							 'radius' 	=> esc_html__( 'Radius', 'elementor' ),
							 'square' 	=> esc_html__( 'Square', 'elementor' ),
						 ],
						 'prefix_class' => 'eael-infobox-icon-hover-bg-shape-',
					  ]
				);
		
				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_infobox_hover_icon_border',
							'label' => esc_html__( 'Border', 'elementor' ),
							'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap'
						]
				);
		
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'eael_infobox_icon_hover_shadow',
						'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap',
					]
				);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Info Box Button Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_button_settings',
			[
				'label' => esc_html__( 'Button Styles', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'eael_show_infobox_button'	=> 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
			'name' => 'eael_infobox_button_typography',
				'selector' => '{{WRAPPER}} .eael-infobox .infobox-button a.eael-infobox-button',
			]
		);

		$this->add_responsive_control(
			'eael_creative_button_padding',
			[
				'label' => esc_html__( 'Button Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-button a.eael-infobox-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_infobox_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-button a.eael-infobox-button' => 'border-radius: {{SIZE}}px;'
				],
			]
		);

		$this->start_controls_tabs('infobox_button_styles_controls_tabs');

			$this->start_controls_tab('infobox_button_normal', [
				'label' => esc_html__( 'Normal', 'elementor' )
			]);

				$this->add_control(
					'eael_infobox_button_text_color',
					[
						'label' => esc_html__( 'Text Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors'	=> [
							'{{WRAPPER}} .eael-infobox .eael-infobox-button' => 'color: {{VALUE}};'
						]
					]
				);

				$this->add_control(
					'eael_infobox_button_background_color',
					[
						'label' => esc_html__( 'Background Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333333',
						'selectors'	=> [
							'{{WRAPPER}} .eael-infobox .eael-infobox-button' => 'background: {{VALUE}};'
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_infobox_button_border',
						'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_box_shadow',
						'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab('infobox_button_hover', [
				'label' => esc_html__( 'Hover', 'elementor' )
			]);

				$this->add_control(
					'eael_infobox_button_hover_text_color',
					[
						'label' => esc_html__( 'Text Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors'	=> [
							'{{WRAPPER}} .eael-infobox .eael-infobox-button:hover' => 'color: {{VALUE}};'
						]
					]
				);

				$this->add_control(
					'eael_infobox_button_hover_background_color',
					[
						'label' => esc_html__( 'Background Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333333',
						'selectors'	=> [
							'{{WRAPPER}} .eael-infobox .eael-infobox-button:hover' => 'background: {{VALUE}};'
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_infobox_button_hover_border',
						'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button:hover',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_hover_box_shadow',
						'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button:hover',
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_title_style_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->start_controls_tabs('infobox_content_hover_style_tab');

					$this->start_controls_tab('infobox_content_normal_style', [
						'label'	=> esc_html__( 'Normal', 'elementor' )
					]);

					$this->add_control(
						'eael_infobox_title_heading',
						[
							'label' => esc_html__( 'Title Style', 'elementor' ),
							'type' => Controls_Manager::HEADING,
						]
					);
			
					$this->add_control(
						'eael_infobox_title_color',
						[
							'label' => esc_html__( 'Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '#4d4d4d',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox .infobox-content .title' => 'color: {{VALUE}};',
							],
						]
					);
			
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
						'name' => 'eael_infobox_title_typography',
							'selector' => '{{WRAPPER}} .eael-infobox .infobox-content .title',
						]
					);
			
					$this->add_responsive_control(
						'eael_infobox_title_margin',
						[
							'label' => esc_html__( 'Margin', 'elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
									'{{WRAPPER}} .eael-infobox .infobox-content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'eael_infobox_content_heading',
						[
							'label' => esc_html__( 'Content Style', 'elementor' ),
							'type' => Controls_Manager::HEADING,
							'separator' => 'before'
						]
					);

					$this->add_responsive_control(
						'eael_infobox_content_margin',
						[
							'label' => esc_html__( 'Content Only Margin', 'elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
									'{{WRAPPER}} .eael-infobox .infobox-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'eael_infobox_content_background',
						[
							'label' => esc_html__( 'Content Only Background', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox .infobox-content' => 'background: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'eael_infobox_content_only_padding',
						[
							'label' => esc_html__( 'Content Only Padding', 'elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
								'{{WRAPPER}} .eael-infobox .infobox-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'eael_infobox_content_color',
						[
							'label' => esc_html__( 'Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '#4d4d4d',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox .infobox-content p' => 'color: {{VALUE}};',
							],
						]
					);
			
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
						'name' => 'eael_infobox_content_typography_hover',
							'selector' => '{{WRAPPER}} .eael-infobox .infobox-content p',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab('infobox_content_hover_style', [
					'label'	=> esc_html__( 'Hover', 'elementor' )
				]);

					$this->add_control(
						'eael_infobox_title_hover_color',
						[
							'label' => esc_html__( 'Title Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox:hover .infobox-content h4' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'eael_infobox_content_hover_color',
						[
							'label' => esc_html__( 'Content Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .eael-infobox:hover .infobox-content p' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'eael_infobox_content_transition',
						[
							'label'		=> esc_html__( 'Transition', 'elementor' ),
							'description'		=> esc_html__( 'Transition will applied to ms (ex: 300ms).', 'elementor' ),
							'type'		=> Controls_Manager::NUMBER,
							'separator'	=> 'before',
							'min'		=> 100,
							'max'		=> 1000,
							'default'	=> 100,
							'selectors'	=> [
								'{{WRAPPER}} .eael-infobox:hover .infobox-content h4' => 'transition: {{SIZE}}ms;',
								'{{WRAPPER}} .eael-infobox:hover .infobox-content p' => 'transition: {{SIZE}}ms;'
							]
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * This function is responsible for rendering divs and contents
	 * for infobox before partial.
	 * 
	 * @param	$settings
	 */
	protected function eael_infobox_before($settings) {

		$this->add_render_attribute('eael_infobox_inner', 'class', 'eael-infobox');

		if( 'img-on-left' == $settings['eael_infobox_img_type'] )
			$this->add_render_attribute('eael_infobox_inner', 'class', 'icon-on-left');

		if( 'img-on-right' == $settings['eael_infobox_img_type'] )
			$this->add_render_attribute('eael_infobox_inner', 'class', 'icon-on-right');

		$target = $settings['eael_show_infobox_clickable_link']['is_external'] ? 'target="_blank"' : '';
		$nofollow = $settings['eael_show_infobox_clickable_link']['nofollow'] ? 'rel="nofollow"' : '';

		ob_start();
		?>
		<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?><a href="<?php echo esc_url( $settings['eael_show_infobox_clickable_link']['url'] ) ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php endif;?>
		<div <?php echo $this->get_render_attribute_string('eael_infobox_inner'); ?>>
		<?php
		echo ob_get_clean();
	}

	/**
	 * This function is rendering closing divs and tags
	 * of before partial for infobox.
	 * 
	 * @param	$settings
	 */
	protected function eael_infobox_after($settings) {
		ob_start();?></div><?php
		if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?></a><?php endif;
		echo ob_get_clean();
	}

	/**
	 * This function is rendering appropriate icon for infobox.
	 * 
	 * @param $settings
	 */
	protected function render_infobox_icon($settings) {

		if( 'none' == $settings['eael_infobox_img_or_icon'] ) return;

		if( 'eael_infobox_image' == $settings['graphic_element_image'] ) {
		
			$infobox_image = $this->get_settings( 'eael_infobox_image' );
		
		} else {
			
			$infobox_image1 = $this->get_settings( 'eael_infobox_image_short' );
			$image_ID = do_shortcode($infobox_image1);
			$image_URL = wp_get_attachment_url($image_ID);
		
			foreach($settings as $settingKey => $settingVal) {
				
				if($settingKey == 'eael_infobox_image_short') {
				 $infobox_image['url'] = $image_URL;
				 $infobox_image['id'] = $image_ID;
				}
			}
			
		}
		
		$infobox_image_url = Group_Control_Image_Size::get_attachment_image_src( $infobox_image['id'], 'thumbnail', $settings );
		
		if( empty( $infobox_image_url ) ) : $infobox_image_url = $infobox_image['url']; else: $infobox_image_url = $infobox_image_url; endif;

		$this->add_render_attribute(
			'infobox_icon',
			[
				'class' => ['infobox-icon']
			]
		);

		if( $settings['eael_infobox_icon_hover_animation'] ) {
			$this->add_render_attribute('infobox_icon', 'class', 'elementor-animation-' . $settings['eael_infobox_icon_hover_animation']);
		}

		if( $settings['eael_infobox_image_icon_hover_animation'] ) {
			$this->add_render_attribute('infobox_icon', 'class', 'elementor-animation-' . $settings['eael_infobox_image_icon_hover_animation']);
		}
		
		if( $settings['eael_infobox_number_icon_hover_animation'] ) {
			$this->add_render_attribute('infobox_icon', 'class', 'elementor-animation-' . $settings['eael_infobox_number_icon_hover_animation']);
		}
		
		if( 'icon' == $settings['eael_infobox_img_or_icon'] ) {
			$this->add_render_attribute('infobox_icon', 'class', 'eael-icon-only');
		}

		ob_start();
		?>
			<div <?php echo $this->get_render_attribute_string('infobox_icon'); ?>>

				<?php if( 'img' == $settings['eael_infobox_img_or_icon'] ) : ?>
					<img src="<?php echo esc_url( $infobox_image_url ); ?>" alt="Icon Image">
				<?php endif; ?>
				
				<?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : ?>
				
				<?php if( 'eael_infobox_icon' == $settings['graphic_element'] ) : ?>
				
				<div class="infobox-icon-wrap">
					<i class="<?php echo esc_attr( $settings['eael_infobox_icon'] ); ?>"></i>
				</div>
				
				<?php else: ?>
				
				<div class="infobox-icon-wrap">
					<i class="fa <?php echo esc_attr( do_shortcode($settings['eael_infobox_icon_short'] )); ?>"></i>
				</div>
				
				<?php endif; ?>
				
				<?php endif; ?>

				<?php if( 'number' == $settings['eael_infobox_img_or_icon'] ) : ?>
				<div class="infobox-icon-wrap">
					<span class="infobox-icon-number"><?php echo esc_attr( $settings['eael_infobox_number'] ); ?></span>
				</div>
				<?php endif; ?>

			</div>
		<?php
		echo ob_get_clean();
	}


	protected function render_infobox_content( $settings ) {

		$this->add_render_attribute( 'infobox_content', 'class', 'infobox-content' );
		if( 'icon' == $settings['eael_infobox_img_or_icon'] )
			$this->add_render_attribute( 'infobox_content', 'class', 'eael-icon-only' );

		ob_start();
		?>
			<div <?php echo $this->get_render_attribute_string('infobox_content'); ?>>
				<h4 class="title"><?php echo $settings['eael_infobox_title']; ?></h4>
				<?php if( 'yes' == $settings['eael_show_infobox_content'] ) : ?>
					<?php if( 'content' === $settings['eael_infobox_text_type'] ) : ?>
						<?php if ( ! empty( $settings['eael_infobox_text'] ) ) : ?>
							<p><?php echo $settings['eael_infobox_text']; ?></p>
						<?php endif; ?>
						<?php $this->render_infobox_button($this->get_settings_for_display()); ?>
					<?php elseif( 'template' === $settings['eael_infobox_text_type'] ) :
						if ( !empty( $settings['eael_primary_templates'] ) ) {
							$eael_template_id = $settings['eael_primary_templates'];
							$eael_frontend = new Frontend;

							echo $eael_frontend->get_builder_content( $eael_template_id, true );
						}
					endif; ?>
				<?php endif; ?>
			</div>
		<?php

		echo ob_get_clean();
	}

	/**
	 * This function rendering infobox button
	 * 
	 * @param $settings
	 */
	protected function render_infobox_button( $settings ) {
		if('yes' == $settings['eael_show_infobox_clickable'] || 'yes' != $settings['eael_show_infobox_button']) return;

		$this->add_render_attribute('infobox_button', 'class', 'eael-infobox-button' );

		if($settings['infobox_button_link_url']['url'])
			$this->add_render_attribute('infobox_button', 'href', esc_url($settings['infobox_button_link_url']['url']) );

		if('on' == $settings['infobox_button_link_url']['is_external'])
			$this->add_render_attribute('infobox_button', 'target', '_blank');

		if('on' == $settings['infobox_button_link_url']['nofollow'])
			$this->add_render_attribute('infobox_button', 'rel', 'nofollow');

		$this->add_render_attribute('button_icon', [
			'class'	=> esc_attr($settings['eael_infobox_button_icon']),
			'aria-hidden'	=> 'true'
		]);

		if( 'left' == $settings['eael_infobox_button_icon_alignment'])
			$this->add_render_attribute('button_icon', 'class', 'eael_infobox_button_icon_left');

		if( 'right' == $settings['eael_infobox_button_icon_alignment'])
			$this->add_render_attribute('button_icon', 'class', 'eael_infobox_button_icon_right');

		ob_start();
		?>
		<div class="infobox-button">
			<a <?php echo $this->get_render_attribute_string('infobox_button'); ?>>
				<?php if( 'left' == $settings['eael_infobox_button_icon_alignment']) : ?><i <?php echo $this->get_render_attribute_string('button_icon'); ?>></i><?php endif; ?>
				<?php echo esc_attr($settings['infobox_button_text']); ?>
				<?php if( 'right' == $settings['eael_infobox_button_icon_alignment']) : ?><i <?php echo $this->get_render_attribute_string('button_icon'); ?>></i><?php endif; ?>
			</a>
		</div>
		<?php
		echo ob_get_clean();
	}


	protected function render() {
		$this->eael_infobox_before( $this->get_settings_for_display() );
		$this->render_infobox_icon( $this->get_settings_for_display() );
		$this->render_infobox_content( $this->get_settings_for_display() );
		$this->eael_infobox_after( $this->get_settings_for_display() );
	}

	protected function content_template() {}
}
