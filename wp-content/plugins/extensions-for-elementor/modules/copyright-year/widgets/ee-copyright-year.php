<?php
namespace ElementorExtensions\Modules\CopyrightYear\Widgets;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EE_Copyright_Year extends Base_Widget{

    public function get_name(){
        return $this->widget_name_prefix.'copyright-year';
    }

    public function get_title(){
        return __('Copyright Year', 'elementor-extensions');
    }

    public function get_icon(){
        return 'fa fa-copyright';
    }

    public function get_keywords() {
		return [ 'copyright', 'co', 'copy', 'year', 'cy', 'cp' ];
    }
    
    protected function _register_controls(){

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Copyright Year', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_CONTENT,
             ]
        );

        $this->add_control(
			'copyright_text_notes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<b>Note: </b> <i>Use shortcode</i> [copyright_year] <i>to print dynamic year</i>', 'elementor-extensions' ),
			]
		);

        $this->add_control(
			'copyright_text',
			[
				'label' => __( 'Copyright text', 'elementor-extensions' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Copyright &copy; [copyright_year] Loreum Ipsum', 'elementor-extensions' ),
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
			'background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper p' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .copyright_year_container .wrapper p',
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .copyright_year_container .wrapper',
				'separator' => 'before'
			]
        );
        
        $this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_alignment',
			[
				'label' => __( 'Text Alignment', 'elementor-extensions' ),
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
					'{{WRAPPER}} .copyright_year_container .wrapper' => 'text-align: {{VALUE}};',
				],
			]
        );

        $this->add_responsive_control(
			'blokc_alignment',
			[
				'label' => __( 'Block Alignment', 'elementor-extensions' ),
                'type' => Controls_Manager::CHOOSE,
                'description' => 'This will help you to align whole block with width adjustment',
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container' => 'text-align: {{VALUE}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'elementor-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
				'default' => [
                    'unit' => '%',
					'size' => '',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
                    ],
                    'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);
        
        $this->end_controls_section();

        $this->start_controls_section(
			'section_link_style',
			[
				'label' => __( 'Link', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
        );
        
        $this->add_control(
			'link_section_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<i>Style the link added from the Editor.</i>', 'elementor-extensions' ),
			]
		);

		$this->start_controls_tabs( 'link_styles' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'link_background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper a' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .copyright_year_container .wrapper a',
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper a' => 'color: {{VALUE}}',
				]
			]
        );
        
		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'hover_link_background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper a:hover' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hover_link_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .copyright_year_container .wrapper a:hover',
			]
		);

		$this->add_control(
			'hover_link_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .copyright_year_container .wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
    }

    protected function render(){
        $settings = $this->get_settings_for_display();
        $copyright_text = $settings['copyright_text'];

        echo '<div class="copyright_year_container">';
            echo '<div class="wrapper">';
                echo do_shortcode( shortcode_unautop( $copyright_text ) );
            echo '</div>';
        echo '</div>';
    }

    protected function _content_template(){

    }
}
