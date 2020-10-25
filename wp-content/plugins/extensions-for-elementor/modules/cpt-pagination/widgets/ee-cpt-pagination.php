<?php
namespace ElementorExtensions\Modules\CptPagination\Widgets;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use ElementorExtensions\Classes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class EE_Cpt_Pagination extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'cpt-pagination';
	}

	public function get_title() {
		return __( 'CPT Next/Prev', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
	}

	public function get_keywords() {
		return [ 'cpt', 'navigation', 'next prev', 'next', 'prev', 'pagination', 'cpt navigation', 'cpt pagination' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_post_navigation_content',
			[
				'label' => __( 'CPT Navigation', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Label', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-extensions' ),
				'label_off' => __( 'Hide', 'elementor-extensions' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => __( 'Previous Label', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'elementor-extensions' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => __( 'Next Label', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Next', 'elementor-extensions' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_arrow',
			[
				'label' => __( 'Arrows', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-extensions' ),
				'label_off' => __( 'Hide', 'elementor-extensions' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'arrow',
			[
				'label' => __( 'Arrows Type', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fa fa-angle-left' => __( 'Angle', 'elementor-extensions' ),
					'fa fa-angle-double-left' => __( 'Double Angle', 'elementor-extensions' ),
					'fa fa-chevron-left' => __( 'Chevron', 'elementor-extensions' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'elementor-extensions' ),
					'fa fa-caret-left' => __( 'Caret', 'elementor-extensions' ),
					'fa fa-arrow-left' => __( 'Arrow', 'elementor-extensions' ),
					'fa fa-long-arrow-left' => __( 'Long Arrow', 'elementor-extensions' ),
					'fa fa-arrow-circle-left' => __( 'Arrow Circle', 'elementor-extensions' ),
					'fa fa-arrow-circle-o-left' => __( 'Arrow Circle Negative', 'elementor-extensions' ),
				],
				'default' => 'fa fa-angle-left',
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_borders',
			[
				'label' => __( 'Borders', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-extensions' ),
				'label_off' => __( 'Hide', 'elementor-extensions' ),
				'default' => 'yes',
				'prefix_class' => 'elementor-post-navigation-borders-',
			]
		);

		$post_types = Utils::get_post_types();

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Post Type', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => $post_types,
				'description' => 'Should be same as main widget',
			]
		);

		$this->add_control(
			'post_per_pages',
			[
				'label' => __( 'Posts Per Page', 'elementor-extensions' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'step' => 1,
				'default' => 6,
				'description' => 'Should be same as main widget',
			]
		);

		$this->add_control(
			'important_note',
			[
				'label' => __( '<strong>Important Note</strong>', 'elementor-extensions' ),
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<p style="margin-top:10px;">Add <b><code>`siteset_custom_query`</code></b> into the <b>Query</b> section -> <b>Query ID</b> field into the main widget</p>', 'elementor-extensions' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'label_style',
			[
				'label' => __( 'Label', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_label_style' );

		$this->start_controls_tab(
			'label_color_normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} span.post-navigation__prev--label' => 'color: {{VALUE}};',
					'{{WRAPPER}} span.post-navigation__next--label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'label_color_hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'label_hover_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} span.post-navigation__prev--label:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} span.post-navigation__next--label:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} span.post-navigation__prev--label, {{WRAPPER}} span.post-navigation__next--label',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'arrow_style',
			[
				'label' => __( 'Arrow', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_post_navigation_arrow_style' );

		$this->start_controls_tab(
			'arrow_color_normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-navigation__arrow-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrow_color_hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-navigation__arrow-wrapper:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrow_size',
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
					'{{WRAPPER}} .post-navigation__arrow-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrow_padding',
			[
				'label' => __( 'Gap', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-right: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-next' => 'padding-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .post-navigation__arrow-next' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'borders_section_style',
			[
				'label' => __( 'Borders', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_borders!' => '',
				],
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post-navigation__separator' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-post-navigation' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'borders_width',
			[
				'label' => __( 'Size', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post-navigation__separator' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post-navigation' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-post-navigation__next.elementor-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
					'{{WRAPPER}} .elementor-post-navigation__prev.elementor-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
				],
			]
		);

		$this->add_control(
			'borders_spacing',
			[
				'label' => __( 'Spacing', 'elementor-extensions' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-post-navigation' => 'padding: {{SIZE}}{{UNIT}} 0;',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$settings = $this->get_active_settings();


		if(!empty($settings['post_type']) && !empty($settings['post_per_pages'])):
		
			global $wp_query; 
			$temp = $wp_query; 
			$wp_query = null; 
			$wp_query = new \WP_Query(); 

			$wp_query->query('showposts='.$settings['post_per_pages'].'&post_type='.$settings['post_type'].'&paged='.$paged); 
			
			$prev_label = '';
			$next_label = '';
			$prev_arrow = '';
			$next_arrow = '';

			if ( 'yes' === $settings['show_label'] ) {
				$prev_label = '<span class="post-navigation__prev--label">' . $settings['prev_label'] . '</span>';
				$next_label = '<span class="post-navigation__next--label">' . $settings['next_label'] . '</span>';
			}

			if ( 'yes' === $settings['show_arrow'] ) {
				if ( is_rtl() ) {
					$prev_icon_class = str_replace( 'left', 'right', $settings['arrow'] );
					$next_icon_class = $settings['arrow'];
				} else {
					$prev_icon_class = $settings['arrow'];
					$next_icon_class = str_replace( 'left', 'right', $settings['arrow'] );
				}

				$prev_arrow = '<span class="post-navigation__arrow-wrapper post-navigation__arrow-prev"><i class="' . $prev_icon_class . '" aria-hidden="true"></i><span class="elementor-screen-only">' . esc_html__( 'Prev', 'elementor-extensions' ) . '</span></span>';
				$next_arrow = '<span class="post-navigation__arrow-wrapper post-navigation__arrow-next"><i class="' . $next_icon_class . '" aria-hidden="true"></i><span class="elementor-screen-only">' . esc_html__( 'Next', 'elementor-extensions' ) . '</span></span>';
			}

			?>
			<div class="elementor-post-navigation elementor-grid">
				<div class="elementor-post-navigation__prev elementor-post-navigation__link">
					<?php previous_posts_link( $prev_arrow . '<span class="elementor-post-navigation__link__prev">' . $prev_label  . '</span>'); ?>
				</div>
				<?php if ( 'yes' === $settings['show_borders'] ) : ?>
					<div class="elementor-post-navigation__separator-wrapper">
						<div class="elementor-post-navigation__separator"></div>
					</div>
				<?php endif; ?>
				<div class="elementor-post-navigation__next elementor-post-navigation__link">
					<?php next_posts_link('<span class="elementor-post-navigation__link__next">' . $next_label  . '</span>' . $next_arrow); ?>
				</div>
			</div>
			<?php

			$wp_query = null; 
			$wp_query = $temp;
	else:
		echo '<p>Please select `Post Type` & add `Posts Per Page` number</p>';
	endif;
	}
}
