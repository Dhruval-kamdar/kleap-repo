<?php
namespace ElementorExtensions\Modules\ImportTemplates\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use ElementorExtensions\Admin\EE_MB_Setting_Common;

class EE_Import_Templates extends Base_Widget {

	protected $api_site_uri = '';

	public function get_name() {
		return $this->widget_name_prefix.'import-templates';
	}

	public function get_title() {
		return __( 'Import Templates', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-file-download';
	}

	public function get_categories() {
		return [ 'elementor-extensions'];
	}

	public function get_keywords() {
		return [ 'import', 'templates', 'importtemplates', 'it', 'i', 't' ];
	}
	
	protected function _register_controls() {

		$this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_CONTENT,
             ]
		);

		$templates = $this->ee_mb_get_templates_dropdown();
		$templates['templates'][0] = 'Select';

		$this->add_control(
			'templates',
			[
				'label' 		=> __( 'Templates', 'elementor-siteset' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '0',
				'options'		=> $templates['templates']
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'h1_section',
            [
                'label' => __('H1', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'h1_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h1' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'h1_typo',
                'selector' => '{{WRAPPER}} h1',
            ]
		);

		$this->add_responsive_control(
		'h1_padding',
		[
		    'label' => __( 'Padding', 'elementor-extensions' ),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => [ 'px', '%', 'em' ],
		    'selectors' => [
		        '{{WRAPPER}} h1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
		    'separator' => 'before',
		]);

		$this->add_responsive_control(
		'h1_margin',
		[
		    'label' => __( 'Margin', 'elementor-extensions' ),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => [ 'px', '%', 'em' ],
		    'selectors' => [
		        '{{WRAPPER}} h1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
		    'separator' => 'before',
		]);

		$this->end_controls_section();

		$this->start_controls_section(
            'h2_section',
            [
                'label' => __('H2', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'h2_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h2' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'h2_typo',
                'selector' => '{{WRAPPER}} h2',
            ]
		);

		$this->add_responsive_control(
        'h2_padding',
        [
            'label' => __( 'Padding', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control(
        'h2_margin',
        [
            'label' => __( 'Margin', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

		$this->end_controls_section();

		$this->start_controls_section(
            'h3_section',
            [
                'label' => __('H3', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'h3_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h3' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'h3_typo',
                'selector' => '{{WRAPPER}} h3',
            ]
		);

		$this->add_responsive_control(
        'h3_padding',
        [
            'label' => __( 'Padding', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control(
        'h3_margin',
        [
            'label' => __( 'Margin', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

		$this->end_controls_section();

		$this->start_controls_section(
            'h4_section',
            [
                'label' => __('H4', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'h4_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h4' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'h4_typo',
                'selector' => '{{WRAPPER}} h4',
            ]
		);

		$this->add_responsive_control(
        'h4_padding',
        [
            'label' => __( 'Padding', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control(
        'h4_margin',
        [
            'label' => __( 'Margin', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

		$this->end_controls_section();

		$this->start_controls_section(
            'h5_section',
            [
                'label' => __('H5', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'h5_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h5' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'h5_typo',
                'selector' => '{{WRAPPER}} h5',
            ]
		);

		$this->add_responsive_control(
        'h5_padding',
        [
            'label' => __( 'Padding', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control(
        'h5_margin',
        [
            'label' => __( 'Margin', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

		$this->end_controls_section();

		$this->start_controls_section(
            'h6_section',
            [
                'label' => __('H6', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'h6_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h6' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'h6_typo',
                'selector' => '{{WRAPPER}} h6',
            ]
		);

		$this->add_responsive_control(
        'h6_padding',
        [
            'label' => __( 'Padding', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control(
        'h6_margin',
        [
            'label' => __( 'Margin', 'elementor-extensions' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

		$this->end_controls_section();

		$this->start_controls_section(
            'paragraph_section',
            [
                'label' => __('Paragraph', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

		$this->add_control(
            'paragraph_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} p' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'paragraph_typo',
                'selector' => '{{WRAPPER}} p',
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'link_section',
            [
                'label' => __('Link', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
		);

			$this->start_controls_tabs( 'tabs_links' );

				$this->start_controls_tab(
					'tabs_link_normal',
					[
						'label' => __( 'Normal', 'elementor-extensions' ),
					]
				);

					$this->add_control(
						'link_color',
						[
							'label' => __( 'Color', 'elementor-extensions' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'link_bgcolor',
						[
							'label' => __( 'Background', 'elementor-extensions' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a' => 'background: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'link_typo',
							'label' => __( 'Typographpy', 'elementor-extensions' ),
							'scheme' => Scheme_Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} a',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_link_hover',
					[
						'label' => __( 'Hover', 'elementor-extensions' ),
					]
				);

					$this->add_control(
						'link_color_hover',
						[
							'label' => __( 'Color', 'elementor-extensions' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'link_bgcolor_hover',
						[
							'label' => __( 'Background', 'elementor-extensions' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a:hover' => 'background: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'link_typo_hover',
							'label' => __( 'Typographpy', 'elementor-extensions' ),
							'scheme' => Scheme_Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} a:hover',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'link_border',
					'label' => __( 'Border', 'elementor-extensions' ),
					'show_label' => true,
					'selector' => '{{WRAPPER}} a',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'link_border_radius',
				[
					'label' => __( 'Border Radius', 'elementor-extensions' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px','%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						]
					],
					'label_block' => true,
					'selectors' => [
						'{{WRAPPER}} a' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
            'image_section',
            [
                'label' => __('Image', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'elementor-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'elementor-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __( 'Border', 'elementor-extensions' ),
                'show_label' => true,
                'selector' => '{{WRAPPER}} img',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px','%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'label_block' => true,
                'selectors' => [
                    '{{WRAPPER}} img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'elementor-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => __( 'Margin', 'elementor-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'icon_section',
            [
                'label' => __('Icon', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_STYLE,
             ]
        );

            $this->start_controls_tabs( 'tabs_icons' );

                $this->start_controls_tab(
                    'tabs_icon_normal',
                    [
                        'label' => __( 'Normal', 'elementor-extensions' ),
                    ]
                );

                    $this->add_control(
                        'icon_color',
                        [
                            'label' => __( 'Color', 'elementor-extensions' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .elementor-icon' => 'color: {{VALUE}}; fill:{{VALUE}}; border-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_size',
                        [
                            'label' => __( 'Size', 'elementor-extensions' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 5,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'tabs_icon_hover',
                    [
                        'label' => __( 'Hover', 'elementor-extensions' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_hover',
                        [
                            'label' => __( 'Color', 'elementor-extensions' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .elementor-icon:hover' => 'color: {{VALUE}}; fill:{{VALUE}}; border-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_hover_size',
                        [
                            'label' => __( 'Size', 'elementor-extensions' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 5,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-icon:hover' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
	}
		
	protected function render() {

		$this->api_site_uri = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_import_template_url');

        $integration_link = admin_url().'admin.php?page=elementor-extensions#integration';

		if(empty($this->api_site_uri)):
			echo sprintf( 'Please add master site url from Elementor Extensions settings from <a href="%1$s">here</a>',  $integration_link);
			return;
		endif;

        $templates = $this->ee_mb_get_templates_dropdown();
        $template_password = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_template_password');

        if((isset($templates['template_password']) && empty($templates['template_password'])) || empty($template_password)):
            echo sprintf( 'Please add add password in both the site from Elementor Extensions settings from <a href="%1$s">here</a>',  $integration_link);
            return;
        endif;

        if(!empty($templates['template_password']) && $templates['template_password'] !== $template_password):
            printf( "Password doesn't matched", "elementor-extensions");
            return;
        endif;

		$settings = $this->get_settings();
		$template = $settings['templates'];

		if(empty($template)):
			echo __('Please select templates','elementor-extensions');
			return;
		endif;

        /*@ Import remote templates from given site URL */
        $import_url = '';
        if(!isset($templates['template_paths']['baseurl'])):
            return;
        endif;

        $import_url = $templates['template_paths']['baseurl'].'/elementor/css/';
		$link_url = esc_url($import_url.'post-'.$template.'.css');
		?>
		<script type="text/javascript">
			var ee_mb_lnk_element = document.createElement("link");
			ee_mb_lnk_element.setAttribute("rel", "stylesheet");
			ee_mb_lnk_element.setAttribute("type", "text/css");
			ee_mb_lnk_element.setAttribute("href", "<?php echo $link_url; ?>");
			var ee_mb_referenceNode = document.querySelector('#elementor-frontend-css');
			ee_mb_referenceNode.after(ee_mb_lnk_element);
		</script>
		<?php

		$url = esc_url($this->api_site_uri.'wp-json/templates/get');
		$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'body' => array( 'template_id' => $template),
		    )
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Something went wrong: $error_message";
		} else {
			echo json_decode( wp_remote_retrieve_body( $response ), true ) ;
		}
	}

	/*@ Get templates */
	protected function ee_mb_get_templates_dropdown(){

		$this->api_site_uri = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_import_template_url');
		
		$response = wp_remote_get( esc_url($this->api_site_uri.'wp-json/templates/all-templates') );

		$response_code = wp_remote_retrieve_response_code( $response );

		$api_response = [];
		if ( is_array( $response ) && $response_code === 200 ) {
    		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		}

		return $api_response;
	}

	protected function _content_template() {
		
	}
}
