<?php
namespace ElementorExtensions\Modules\AnchorScroll\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;

class EE_Anchor_Scroll extends Base_Widget{

    public function get_name(){
        return $this->widget_name_prefix.'anchor-scroll';
    }

    public function get_title(){
        return __('Anchor Scroll', 'elementor-extensions');
    }

    public function get_icon(){
        return 'fa fa-anchor';
    }

    public function get_script_depends() {
        return [
            'ee-mb-anchor-scroll'
        ];
    }

    public function get_keywords() {
		return [ 'as', 'anchor', 'scroll', 'anchorscroll', 'anchor scroll'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Anchor Scroll', 'elementor-extensions'),
                'tab' => Controls_Manager::TAB_CONTENT,
             ]
		);
		
		$this->add_control(
			'anchor_text',
			[
				'label' => __( 'Anchor Text', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click here', 'elementor-extensions' ),
			]
        );

        $this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elementor-extensions' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-circle-down',
					'library' => 'fa-solid',
				]
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Icon View', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'elementor-extensions' ),
					'stacked' => __( 'Stacked', 'elementor-extensions' ),
					'framed' => __( 'Framed', 'elementor-extensions' ),
				],
				'default' => 'default',
				'prefix_class' => 'elementor-view-',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => __( 'Circle', 'elementor-extensions' ),
					'square' => __( 'Square', 'elementor-extensions' ),
				],
				'default' => 'circle',
				'condition' => [
					'view!' => 'default',
				],
				'prefix_class' => 'elementor-shape-',
			]
		);

        $this->add_control(
			'section_id',
			[
				'label' => __( 'Section ID', 'elementor-extensions' ),
                'type' => Controls_Manager::TEXT,
                'description' => __('Copy `CSS ID` from section`s `advanced` tab and paste here without #.','elementor-extensions')
			]
        );
        
        $this->add_control(
			'anchor_icon_position',
			[
				'label' => __( 'Icon Position', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'up' => __( 'Up', 'elementor-extensions' ),
					'down' => __( 'Down', 'elementor-extensions' ),
					'left' => __( 'Left', 'elementor-extensions' ),
					'right' => __( 'Right', 'elementor-extensions' ),
				],
				'default' => 'up',
				'prefix_class' => 'ee-icon-position-'
			]
		);

        $this->end_controls_section();

       
        $this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Primary Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E44A70',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label' => __( 'Secondary Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#67C95E',
				'condition' => [
					'view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => __( 'Primary Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked .anchor-scroll:hover .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .anchor-scroll:hover .elementor-icon, {{WRAPPER}}.elementor-view-default .anchor-scroll:hover .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_secondary_color',
			[
				'label' => __( 'Secondary Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed .anchor-scroll:hover .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .anchor-scroll:hover .elementor-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor-extensions' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'size',
			[
				'label' => __( 'Size', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_control(
			'rotate',
			[
				'label' => __( 'Rotate', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view' => 'framed',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-wrapper .anchor-scroll' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_anchor_text',
			[
				'label' => __( 'Anchor Text', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'anchor_text_gap',
			[
				'label' => __( 'Gap', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.ee-icon-position-up .anchor-scroll .anchor_text' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ee-icon-position-down .anchor-scroll .anchor_text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ee-icon-position-left .anchor-scroll .anchor_text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ee-icon-position-right .anchor-scroll .anchor_text' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_anchor_text' );

			$this->start_controls_tab(
				'tabs_anchor_text_normal',
				[
					'label' => __( 'Normal', 'elementor-extensions' ),
				]
			);

				$this->add_control(
					'anchor_text_color',
					[
						'label' => __( 'Color', 'elementor-extensions' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .anchor-scroll .anchor_text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'anchor_text_typography',
						'label' => __( 'Typography', 'elementor-extensions' ),
						'selector' => '{{WRAPPER}} .anchor-scroll .anchor_text',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tabs_anchor_text_hover',
				[
					'label' => __( 'Hover', 'elementor-extensions' ),
				]
			);

				$this->add_control(
					'anchor_text_color_hover',
					[
						'label' => __( 'Color', 'elementor-extensions' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .anchor-scroll:hover .anchor_text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'anchor_text_typography_hover',
						'label' => __( 'Typography', 'elementor-extensions' ),
						'selector' => '{{WRAPPER}} .anchor-scroll:hover .anchor_text',
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
    }

    protected function render(){
        $settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-icon-wrapper' );

		$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon' );

		if ( ! empty( $settings['hover_animation'] ) ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$icon = '';
		$icon_tag = 'div';
		$has_icon = ! empty( $settings['icon']['value'] );
		if ($has_icon) {
			$this->add_render_attribute( 'icon', 'class', $settings['icon']['value'] );
			$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
			$icon = '<i '.$this->get_render_attribute_string( 'icon' ).'></i>';
		}
		
		$section_id = $settings['section_id'];
		$anchor_text = !empty($settings['anchor_text']) ? $settings['anchor_text'] : '';
		?>
		
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<a href="#<?php echo $section_id; ?>" class="anchor-scroll" data-class-to="body" data-on-scroll="blur-effect">
				<<?php echo $icon_tag . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
					<?php echo $icon; ?>
				</<?php echo $icon_tag; ?>>
				<div class="anchor_text"><?php echo $anchor_text; ?></div>
			</a>
		</div>
		<div class="fill-bg"><div class="animated popup v-align-html">Done!</div><div></div></div>
        <?php
    }

	protected function _content_template() {
        ?>
		<# var iconTag = 'div'; #>
		<div class="elementor-icon-wrapper">
			<a href="#{{{ settings.section_id }}}" class="anchor-scroll" data-class-to="body" data-on-scroll="blur-effect">
				<{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}">
					<i class="{{ settings.icon.value }}" aria-hidden="true"></i>
				</{{{ iconTag }}}>
				<div class="anchor_text">{{{ settings.anchor_text }}}</div>
			</a>
		</div>
		<div class="fill-bg"><div class="animated popup v-align-html">Done!</div><div></div></div>
		<?php
	}
}
