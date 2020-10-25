<?php
namespace ElementorExtensions\Modules\ScrollNavigation\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use ElementorExtensions\Controls\EE_MB_Group_Control_Box_Style;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class EE_Scroll_Navigation extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'scroll-navigation';
	}

	public function get_title() {
		return __( 'Scroll Navigation', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-skill-bar';
	}

	public function get_keywords() {
		return [ 'scroll', 'nav', 'navigation', 'scroll nav', 'scroll navigation', 'sn' ];
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'elementor-extensions/scroll-navigation/css-scheme',
			array(
				'instance' => '.ee-mb-scroll-navigation',
				'item'     => '.ee-mb-scroll-navigation__item',
				'hint'     => '.ee-mb-scroll-navigation__item-hint',
				'icon'     => '.ee-mb-scroll-navigation__icon',
				'label'    => '.ee-mb-scroll-navigation__label',
				'dots'     => '.ee-mb-scroll-navigation__dot',
			)
		);
		
		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => __( 'Items', 'elementor-extensions' ),
			)
		);
		
		$repeater = new Repeater();

		$repeater->add_control(
			'item_icon',
			[
				'label' => __( 'Section Label Icon', 'elementor-extensions' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' 	=> [
					'value' => 'fas fa-arrow-circle-right',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'item_dot_icon',
			array(
				'label'       => __( 'Dot Icon', 'elementor-extensions' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'item_label',
			array(
				'label'   => __( 'Label', 'elementor-extensions' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'item_section_id',
			array(
				'label'   => __( 'Section Id', 'elementor-extensions' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'item_section_invert',
			array(
				'label'        => __( 'Invert Under This Section', 'elementor-extensions' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'elementor-extensions' ),
				'label_off'    => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'item_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'item_label'      => __( 'Section 1', 'elementor-extensions' ),
						'item_section_id' => 'section_1',
					),
					array(
						'item_label'      => __( 'Section 2', 'elementor-extensions' ),
						'item_section_id' => 'section_2',
					),
					array(
						'item_label'      => __( 'Section 3', 'elementor-extensions' ),
						'item_section_id' => 'section_3',
					),
				),
				'title_field' => '{{{ item_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => __( 'Settings', 'elementor-extensions' ),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'   => __( 'Position', 'elementor-extensions' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => __( 'Left', 'elementor-extensions' ),
					'right' => __( 'Right', 'elementor-extensions' ),
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => __( 'Scroll Speed', 'elementor-extensions' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'   => __( 'Scroll Offset', 'elementor-extensions' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
			)
		);

		$this->add_control(
			'full_section_switch',
			array(
				'label'        => __( 'Full Section Switch', 'elementor-extensions' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'elementor-extensions' ),
				'label_off'    => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => __( 'General', 'elementor-extensions' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'instance_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'instance_border',
				'label'       => __( 'Border', 'elementor-extensions' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_responsive_control(
			'instance_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'instance_padding',
			array(
				'label'      => __( 'Padding', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'instance_margin',
			array(
				'label'      => __( 'Margin', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'instance_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hint_style',
			array(
				'label'      => __( 'Section Label', 'elementor-extensions' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hint_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['hint'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'hint_border',
				'label'       => __( 'Border', 'elementor-extensions' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['hint'],
			)
		);

		$this->add_responsive_control(
			'hint_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['hint'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hint_padding',
			array(
				'label'      => __( 'Padding', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['hint'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hint_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['hint'],
			)
		);

		$this->add_control(
			'hint_icon_style',
			array(
				'label'     => __( 'Section Label Icon', 'elementor-extensions' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hint_icon_color',
			array(
				'label'  => __( 'Icon Color', 'elementor-extensions' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'hint_icon_size',
			array(
				'label'      => __( 'Icon Size', 'elementor-extensions' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'hint_icon_margin',
			array(
				'label'      => __( 'Margin', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hint_label_style',
			array(
				'label'     => __( 'Section Label Label', 'elementor-extensions' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hint_label_color',
			array(
				'label'  => __( 'Text Color', 'elementor-extensions' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'hint_label_margin',
			array(
				'label'      => __( 'Margin', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hint_label_padding',
			array(
				'label'      => __( 'Padding', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hint_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			)
		);

		$this->add_control(
			'hint_visible',
			array(
				'label'     => __( 'Section Label Visible', 'elementor-extensions' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'desktop_hint_hide',
			array(
				'label'        => __( 'Hide On Desktop', 'elementor-extensions' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'elementor-extensions' ),
				'label_off'    => __( 'Show', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'tablet_hint_hide',
			array(
				'label'        => __( 'Hide On Tablet', 'elementor-extensions' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'elementor-extensions' ),
				'label_off'    => __( 'Show', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'mobile_hint_hide',
			array(
				'label'        => __( 'Hide On Mobile', 'elementor-extensions' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'elementor-extensions' ),
				'label_off'    => __( 'Show', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'      => __( 'Dots', 'elementor-extensions' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

			$this->start_controls_tab(
				'tab_dots_normal',
				array(
					'label' => __( 'Normal', 'elementor-extensions' ),
				)
			);

			$this->add_group_control(
				EE_MB_Group_Control_Box_Style::get_type(),
				array(
					'name'           => 'dots_style',
					'label'          => __( 'Dots Style', 'elementor-extensions' ),
					'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['dots'],
					'fields_options' => array(
						'color' => array(
							'scheme' => array(
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_2,
							),
						),
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_dots_invert',
				array(
					'label' => __( 'Invert', 'elementor-extensions' ),
				)
			);

			$this->add_group_control(
				EE_MB_Group_Control_Box_Style::get_type(),
				array(
					'name'           => 'dots_style_invert',
					'label'          => __( 'Dots Style', 'elementor-extensions' ),
					'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . '.invert ' . $css_scheme['dots'],
					'fields_options' => array(
						'color' => array(
							'scheme' => array(
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_3,
							),
						),
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_dots_hover',
				array(
					'label' => __( 'Hover', 'elementor-extensions' ),
				)
			);

			$this->add_group_control(
				EE_MB_Group_Control_Box_Style::get_type(),
				array(
					'name'           => 'dots_style_hover',
					'label'          => __( 'Dots Style', 'elementor-extensions' ),
					'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['dots'],
					'fields_options' => array(
						'color' => array(
							'scheme' => array(
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							),
						),
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_dots_active',
				array(
					'label' => __( 'Active', 'elementor-extensions' ),
				)
			);

			$this->add_group_control(
				EE_MB_Group_Control_Box_Style::get_type(),
				array(
					'name'           => 'dots_style_active',
					'label'          => __( 'Dots Style', 'elementor-extensions' ),
					'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . '.active ' . $css_scheme['dots'],
					'fields_options' => array(
						'color' => array(
							'scheme' => array(
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_1,
							),
						),
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dots_padding',
			array(
				'label'      => __( 'Dots Padding', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dots'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label'      => __( 'Dots Margin', 'elementor-extensions' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {		
		$settings = $this->get_settings();

		$data_settings = $this->generate_setting_json();
		$position = $settings['position'];

		$classes_list[] = 'ee-mb-scroll-navigation';
		$classes_list[] = 'ee-mb-scroll-navigation--position-' . $position;
		$classes = implode( ' ', $classes_list );
		?>
		<div class="<?php echo $classes; ?>" <?php echo $data_settings; ?>>

			<div class="ee-mb-scroll-navigation__inner">

				<?php
				$hint_classes_array = array( 'ee-mb-scroll-navigation__item-hint' );


				if ( filter_var( $settings['desktop_hint_hide'], FILTER_VALIDATE_BOOLEAN ) ) {
					$hint_classes_array[] = 'elementor-hidden-desktop';
				}

				if ( filter_var( $settings['tablet_hint_hide'], FILTER_VALIDATE_BOOLEAN ) ) {
					$hint_classes_array[] = 'elementor-hidden-tablet';
				}

				if ( filter_var( $settings['mobile_hint_hide'], FILTER_VALIDATE_BOOLEAN ) ) {
					$hint_classes_array[] = 'elementor-hidden-phone';
				}

				$hint_classes = implode( ' ', $hint_classes_array );


				foreach($settings['item_list'] as $key => $item):
					$section_id_attr = $item['item_section_id'];
					$section_invert = $item['item_section_invert'];

					$item_label = $item['item_label'];
				?>
				<div class="ee-mb-scroll-navigation__item" data-anchor="<?php echo $section_id_attr; ?>" data-invert="<?php echo $section_invert; ?>">
				
					<div class="ee-mb-scroll-navigation__dot">
						<?php Icons_Manager::render_icon( $item['item_dot_icon'], [ 'aria-hidden' => 'true' ] );  ?>
					</div>

					<div class="<?php echo $hint_classes; ?>">
						<span class="ee-mb-scroll-navigation__icon">
							<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] );  ?>
						</span>
						<span class="ee-mb-scroll-navigation__label"><?php echo $item_label; ?></span>
					</div>
				</div>
				<?php endforeach; ?>

			</div>
		</div>
		<?php
	}

	protected function _content_template() {
		
	}

	public function generate_setting_json() {
		$settings = $this->get_settings();

		$instance_settings = array(
			'position'      => $settings['position'],
			'speed'         => absint( $settings['speed'] ),
			'offset'        => absint( $settings['offset'] ),
			'sectionSwitch' => filter_var( $settings['full_section_switch'], FILTER_VALIDATE_BOOLEAN ),
		);

		$instance_settings = json_encode( $instance_settings );

		return sprintf( 'data-settings=\'%1$s\'', $instance_settings );
	}
}
