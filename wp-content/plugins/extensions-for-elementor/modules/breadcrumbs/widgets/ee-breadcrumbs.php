<?php
namespace ElementorExtensions\Modules\Breadcrumbs\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Base_Widget;
use ElementorExtensions\Classes\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;	
use Elementor\Icons_Manager;

class EE_Breadcrumbs extends Base_Widget {

	private $_query = null;
	private $_separator = null;

	public function get_name() {
		return $this->widget_name_prefix.'breadcrumbs';
	}

	public function get_title() {
		return __( 'Breadcrumbs', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Display', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label' 	=> __( 'Source', 'elementor-extensions' ),
				'type'		=> Controls_Manager::SELECT,
				'default'	=> '',
				'options'	=> [
					''		=> __( 'Current page', 'elementor-extensions' ),
					'id'	=> __( 'Specific page', 'elementor-extensions' ),
				]
			]
		);

		$this->add_control(
			'source_id',
			[
				'label' 		=> __( 'ID', 'elementor-extensions' ),
				'type'			=> Controls_Manager::NUMBER,
				'min' 			=> 0,
				'placeholder' 	=> '15',
				'condition'		=> [
					'source'	=> 'id',
				]
			]
		);

		$this->add_control(
			'show_home',
			[
				'label' 		=> __( 'Show Home', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
				'label_off' 	=> __( 'No', 'elementor-extensions' ),
				'return_value' 	=> 'yes',
			]
		);

		$this->add_control(
			'hide_parent',
			[
				'label' 		=> __( 'Hide Parent', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
				'label_off' 	=> __( 'No', 'elementor-extensions' ),
				'return_value' 	=> 'yes'
			]
		);

		$this->add_control(
			'cpt_crumbs',
			[
				'label' 		=> __( 'CPT Crumbs', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options'		=> [
					'' 			=> __( 'CPT Name', 'elementor-extensions' ),
					'terms' 	=> __( 'Taxonomy Terms', 'elementor-extensions' ),
					'both' 		=> __( 'CPT & Terms Both', 'elementor-extensions' ),
					'page' 		=> __( 'Page', 'elementor-extensions' ),
				],
			]
		);

		$pages = $this->get_available_pages();
		
		$this->add_control(
			'cpt_page',
			[
				'label' 		=> __( 'Page', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options'		=> $pages,
				'condition'     => [
					'cpt_crumbs' => 'page'
				]
			]
		);

		$this->add_control(
			'show_current_page',
			[
				'label' 		=> __( 'Show Current', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Yes', 'elementor-extensions' ),
				'label_off' 	=> __( 'No', 'elementor-extensions' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'cpt_crumbs' => 'page',
					'cpt_page!' => ''
				]
			]
		);

		$this->add_control(
			'home_text',
			[
				'label' 		=> __( 'Home Text', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Homepage', 'elementor-extensions' ),
				'dynamic'		=> [
					'active'	=> true,
					'categories' => [ TagsModule::POST_META_CATEGORY ]
				],
				'condition'		=> [
					'show_home' => 'yes'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator',
			[
				'label' => __( 'Separator', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'separator_type',
			[
				'label'		=> __( 'Type', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'icon',
				'options' 	=> [
					'text' 		=> __( 'Text', 'elementor-extensions' ),
					'icon' 		=> __( 'Icon', 'elementor-extensions' ),
				],
			]
		);

		$this->add_control(
			'separator_text',
			[
				'label' 		=> __( 'Text', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( '>', 'elementor-extensions' ),
				'condition'		=> [
					'separator_type' => 'text'
				],
			]
		);

		$this->add_control(
			'separator_icon',
			[
				'label' => __( 'Icon', 'elementor-extensions' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' 	=> [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition'		=> [
					'separator_type' => 'icon'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' 	=> __( 'Crumbs', 'elementor-extensions' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'items_align',
				[
					'label' 		=> __( 'Align', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
							'title' 	=> __( 'Left', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extensions' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'prefix_class' 	=> 'ee-mb-breadcrumbs-align-',
				]
			);

			$this->add_responsive_control(
				'items_text_align',
				[
					'label' 		=> __( 'Align', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
							'title' 	=> __( 'Left', 'elementor-extensions' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extensions' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extensions' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-mb-breadcrumbs' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'item_spacing',
				[
					'label' 	=> __( 'Spacing', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> 12
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 36,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-mb-breadcrumbs' => 'margin-left: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-mb-breadcrumbs__item' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-mb-breadcrumbs__separator' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'item_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-breadcrumbs__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'allowed_dimensions' => [ 'right', 'left' ],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'item_border',
					'label' 	=> __( 'Border', 'elementor-extensions' ),
					'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__item',
				]
			);

			$this->add_control(
				'item_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-breadcrumbs__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'item_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__text',
				]
			);

			$this->start_controls_tabs( 'crumb_style' );		
			
			$this->start_controls_tab( 'crumb_default', [ 'label' => __( 'Default', 'elementor-extensions' ) ] );		
		
				$this->add_control(		
					'item_background_color',		
					[		
						'label' 	=> __( 'Background Color', 'elementor-extensions' ),		
						'type' 		=> Controls_Manager::COLOR,		
						'selectors' => [		
							'{{WRAPPER}} .ee-mb-breadcrumbs__item' => 'background-color: {{VALUE}};',		
						],		
					]		
				);		
		
				$this->add_control(		
					'item_color',		
					[		
						'label' 	=> __( 'Color', 'elementor-extensions' ),		
						'type' 		=> Controls_Manager::COLOR,		
						'default'	=> '',		
						'selectors' => [		
							'{{WRAPPER}} .ee-mb-breadcrumbs__item' => 'color: {{VALUE}};',		
							'{{WRAPPER}} .ee-mb-breadcrumbs__item a' => 'color: {{VALUE}};',		
						],		
					]		
				);		

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' 		=> 'item_typo',
						'label' 	=> __( 'Typography', 'elementor-extensions' ),
						'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
						'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__item',
					]
				);
		
			$this->end_controls_tab();		
		
			$this->start_controls_tab( 'crumb_hover', [ 'label' => __( 'Hover', 'elementor-extensions' ) ] );		
		
				$this->add_control(		
					'item_background_color_hover',		
					[		
						'label' 	=> __( 'Background Color', 'elementor-extensions' ),		
						'type' 		=> Controls_Manager::COLOR,		
						'selectors' => [		
							'{{WRAPPER}} .ee-mb-breadcrumbs__item:hover' => 'background-color: {{VALUE}};',		
						],		
					]		
				);		
		
				$this->add_control(		
					'item_color_hover',		
					[		
						'label' 	=> __( 'Color', 'elementor-extensions' ),		
						'type' 		=> Controls_Manager::COLOR,		
						'default'	=> '',		
						'selectors' => [		
							'{{WRAPPER}} .ee-mb-breadcrumbs__item:hover' => 'color: {{VALUE}};',		
							'{{WRAPPER}} .ee-mb-breadcrumbs__item:hover a' => 'color: {{VALUE}};',		
						],		
					]		
				);
				
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' 		=> 'item_typo_hover',
						'label' 	=> __( 'Typography', 'elementor-extensions' ),
						'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
						'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__item:hover',
					]
				);
					
			$this->end_controls_tab();		
		
			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator_style',
			[
				'label' 	=> __( 'Separators', 'elementor-extensions' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'separator_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-breadcrumbs__separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'allowed_dimensions' => [ 'right', 'left' ],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'separator_border',
					'label' 	=> __( 'Border', 'elementor-extensions' ),
					'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__separator',
				]
			);

			$this->add_control(
				'separator_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-breadcrumbs__separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'separator_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-mb-breadcrumbs__separator' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'separator_color',
				[
					'label' 	=> __( 'Color', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-mb-breadcrumbs__separator' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'separator_typography',
					'label' 	=> __( 'Typography', 'elementor-extensions' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__separator',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_current_style',
			[
				'label' 	=> __( 'Current', 'elementor-extensions' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'current_border',
					'label' 	=> __( 'Border', 'elementor-extensions' ),
					'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__item--current',
				]
			);

			$this->add_control(
				'current_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extensions' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-mb-breadcrumbs__item--current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'current_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-mb-breadcrumbs__item--current' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'current_color',
				[
					'label' 	=> __( 'Color', 'elementor-extensions' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-mb-breadcrumbs__item--current' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'current_typography',
					'label' 	=> __( 'Typography', 'elementor-extensions' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-mb-breadcrumbs__item--current .ee-mb-breadcrumbs__text',
				]
			);

		$this->end_controls_section();

	}

	protected function get_query() {

		global $post;

		$settings 	= $this->get_settings_for_display();
		$_id 		= null;
		$_post_type = 'post';

		if ( 'id' === $settings['source'] && '' !== $settings['source_id'] ) {

			$_id = $settings['source_id'];
			$_post_type = 'any';

			$_args = array(
				'p' 		=> $_id,
				'post_type' => $_post_type,
			);

			/* Create custom query */
			$_post_query = new \WP_Query( $_args );

			return $_post_query;
		}

		return false;
	}

	protected function set_separator() {

		$settings = $this->get_settings_for_display();

		if ( 'icon' === $settings['separator_type'] ) {
			
			if ( !empty($settings['separator_icon']['value']) ) :
				ob_start();
					Icons_Manager::render_icon( $settings['separator_icon'], [ 'aria-hidden' => 'true' ] );
				$separator = ob_get_clean();
			endif;  
		
		} else {

			$this->add_inline_editing_attributes( 'separator_text' );
			$this->add_render_attribute( 'separator_text', 'class', 'ee-mb-breadcrumbs__separator__text' );
			
			$separator = '<span ' . $this->get_render_attribute_string( 'separator_text' ) . '>' . $settings['separator_text'] . '</span>';

		}

		$this->_separator = $separator;
	}

	protected function get_separator() {
		return $this->_separator;
	}

	protected function render() {

		$settings 	= $this->get_settings_for_display();
		$_query 	= $this->get_query();

		$this->set_separator();
		$this->add_render_attribute( 'breadcrumbs', [
			'class' => 'ee-mb-breadcrumbs',
			'itemscope' => "",
			'itemtype' => "http://schema.org/BreadcrumbList",
		]);

		if ( $_query ) {
			if ( $_query->have_posts() ) {

				/* Setup post */
				$_query->the_post();

				/* Render using the new query */
				$this->render_breadcrumbs( $_query );

				/* Reset post data to original query */
				wp_reset_postdata();
				wp_reset_query();

			} else {

				_e( 'Post or page not found', 'elementor-extensions' );

			}
		} else {
			/* Render using the original query */
			$this->render_breadcrumbs();
		}
	}

	protected function render_home_link() {
		$settings = $this->get_settings_for_display();

		$this->add_item_render_attribute( 'home-item', 0 );
		$this->add_render_attribute( 'home-item', [
			'class' => 'ee-mb-breadcrumbs__item--home',
		] );

		$this->add_link_render_attribute( 'home-link' );
		$this->add_render_attribute( 'home-link', [
			'class' => [
				'ee-mb-breadcrumbs__crumb--link',
				'ee-mb-breadcrumbs__crumb--home'
			],
			'href' 	=> get_home_url(),
			'title' => $settings['home_text'],
		] );

		$this->add_render_attribute( 'home-text', [
			'itemprop' => 'name',
			'class' 	=> 'ee-mb-breadcrumbs__text',
		] );

		?><li <?php echo $this->get_render_attribute_string( 'home-item' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'home-link' ); ?>>
				<span <?php echo $this->get_render_attribute_string( 'home-text' ); ?>>
					<?php echo $settings['home_text']; ?>
				</span>
			</a>
		</li><?php

		$this->render_separator();

	}

	protected function render_separator( $output = true ) {

		$this->add_render_attribute( 'separator', 'class', 'ee-mb-breadcrumbs__separator' );

		$markup = '<li ' . $this->get_render_attribute_string( 'separator' ) . '>';
		$markup .= $this->get_separator();
		$markup .= '</li>';

		if ( $output === true ) {
			echo $markup;
			return;
		}

		return $markup;
	}

	protected function render_breadcrumbs( $query = false ) {

		global $post, $wp_query;

		if ( $query === false ) {

			/* Reset post data to parent query */
			$wp_query->reset_postdata();

			/* Set active query to native query */
			$query = $wp_query;
		}

		$settings = $this->get_settings_for_display();
		$separator = $this->get_separator();

		$custom_taxonomy = 'product_cat';

		if ( ! $query->is_front_page() ) { ?>
		
			<ul <?php echo $this->get_render_attribute_string( 'breadcrumbs' ); ?>>

			<?php

			if ( 'yes' === $settings['show_home'] ) {
				$this->render_home_link();
			}

			/* ——— Custom Archive ——— */
			if ( $query->is_archive() && ! $query->is_tax() && ! $query->is_category() && ! $query->is_tag() && ! $query->is_date() && ! $query->is_author() ) {

				$this->render_item( 'archive', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'archive',
					'content' 	=> post_type_archive_title( '', false ),
				] );
				
			/* ——— Custom Taxonomy Archive ——— */
			} else if ( $query->is_archive() && $query->is_tax() && ! $query->is_category() && ! $query->is_tag() ) {

				$queried_object = get_queried_object();
				$parents = get_ancestors( $queried_object->term_id, $queried_object->taxonomy );

				$parent_terms = get_terms( [
					'taxonomy' => $queried_object->taxonomy,
					'include' => $parents,
				] );

				$parent_terms = array_reverse( $parent_terms );

				$post_type = get_post_type();
				$post_type_object = get_post_type_object( $post_type );

				$this->render_item( 'post-type-archive', [
					'index'		=> 1,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'post-type-archive',
					'ids' 		=> [ $post_type ],
					'content' 	=> $post_type_object->labels->name,
					'link'		=> get_post_type_archive_link( $post_type ),
				] );

				if ( $parents )  {
					$parent_terms = get_terms( [
						'taxonomy' => $queried_object->taxonomy,
						'include' => $parents,
					] );

					$parent_terms = array_reverse( $parent_terms );

					$counter = 2;
					foreach ( $parent_terms as $term ) {
						$this->render_item( 'custom-tax-archive-parents', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'custom-tax-archive-' . $term->term_id,
							'ids' 		=> [ $term->term_id, $term->slug ],
							'content' 	=> $term->name,
							'link'		=> get_term_link( $term ),
						] );
						$counter++;
					}
				}

				$this->render_item( 'custom-tax-archive', [
					'index'		=> $counter,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'custom-tax-archive',
					'ids' 		=> [ $post_type ],
					'content' 	=> get_queried_object()->name,
					'link'		=> '',
				] );

			} else if ( $query->is_post_type_archive() ) {

				$post_type = get_post_type();
				$post_type_object = get_post_type_object( $post_type );

				$this->render_item( 'post-type-archive', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'post-type-archive',
					'ids' 		=> [ $post_type ],
					'content' 	=> $post_type_object->labels->name,
					'link'		=> get_post_type_archive_link( $post_type ),
				] );
				
			} else if ( $query->is_single() ) {
				
				$post_type = get_post_type();
				
				if ( $post_type !== 'post' ) {

					$counter = 1;

					if ( '' === $settings['cpt_crumbs'] || 'both' === $settings['cpt_crumbs'] ) {

						$post_type_object = get_post_type_object( $post_type );
						$item_content = $post_type_object->labels->name;

						$this->render_item( 'post-type-archive', [
							'index'		=> 1,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'post-type-archive',
							'ids' 		=> [ $post_type ],
							'content' 	=> $item_content,
							'link'		=> get_post_type_archive_link( $post_type ),
						] );

						$counter++;
					}

					if ( in_array( $settings['cpt_crumbs'], [ 'terms', 'both' ] ) ) {
						$item_content = 'terms';

						$terms = Utils::get_parent_terms_highest( $post->ID );

						if ( $terms ) {
							$counter = 1;
							foreach( $terms as $term ) {
								$this->render_item( 'post-type-terms', [
									'index'		=> $counter,
									'current' 	=> false,
									'separator'	=> true,
									'key' 		=> 'terms-' . $term->term_id,
									'ids' 		=> [ $term->term_id, $term->slug ],
									'content' 	=> $term->name,
									'link'		=> get_term_link( $term ),
								] );

								$counter++;
							}
						}

					}
					
				} else {

					$posts_page_id = get_option( 'page_for_posts' );

					if ( $posts_page_id ) {

						$posts_page = get_post( $posts_page_id );

						$this->render_item( 'blog', [
							'index'		=> 1,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'blog',
							'ids' 		=> [ $posts_page->ID ],
							'content' 	=> $posts_page->post_title,
							'link'		=> get_permalink( $posts_page->ID ),
						] );
					}
				}


				if ( 'page' === $settings['cpt_crumbs']) {

					if ( $post->post_parent && 'yes' !== $settings['hide_parent']) {
					
						$anc = get_post_ancestors( $post->ID );
						$anc = array_reverse($anc);
							
						if ( ! isset( $parents ) ) $parents = null;
	
						$counter = 1;
	
						foreach ( $anc as $ancestor ) {
	
							$this->render_item( 'ancestor', [
								'index'		=> $counter,
								'current' 	=> false,
								'separator'	=> true,
								'key' 		=> 'ancestor-' . $ancestor,
								'ids' 		=> [ $ancestor ],
								'content' 	=> get_the_title( $ancestor ),
								'link'		=> get_permalink( $ancestor ),
							] );
	
							$counter++;
						}
					}
	
					$counter = 1;
	
					$page_id = $settings['cpt_page'];

					if (!empty($page_id)) {
						$page = get_post($page_id);

						$this->render_item('post-type-page', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> ($settings['show_current_page']) ? true : false,
							'key' 		=> 'page-'.$page_id,
							'ids' 		=> [ $page_id, $page->post_name ],
							'content' 	=> $page->post_title,
							'link'		=> get_the_permalink($page_id),
						]);

						$counter++;
					}

					if(!empty($settings['show_current_page'])):
						$page = get_post(get_the_ID());

						$this->render_item('post-type-page', [
							'index'		=> $counter,
							'current' 	=> true,
							'separator'	=> false,
							'key' 		=> 'page-'.$page_id,
							'ids' 		=> [ $page_id, $page->post_name ],
							'content' 	=> $page->post_title,
							'link'		=> get_the_permalink($page_id),
						]);

						$counter++;
					endif;
					
				}else{

					$category = get_the_category();
					$last_category = null;

					if( ! empty( $category ) ) {

						$cat_display = '';

						$values = array_values($category);

						$last_category = get_term( Utils::get_most_parents_category( $category ) );
							
						$cat_parents = array_reverse( get_ancestors( $last_category->term_id, 'category' ) );
					}

					$taxonomy_exists = taxonomy_exists( $custom_taxonomy );

					if( empty( $last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists ) {
							$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );

						if ( $taxonomy_terms ) {
							$cat_id = $taxonomy_terms[0]->term_id;
							$cat_nicename = $taxonomy_terms[0]->slug;
							$cat_link = get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
							$cat_name = $taxonomy_terms[0]->name;
						}
					}

					if( ! empty( $last_category ) ) {
						$counter = 1;

						foreach ( $cat_parents as $parent ) {
							$_parent = get_term( $parent );

							if ( has_category( $_parent->term_id, $post ) ) {

								$this->render_item( 'category', [
									'index'		=> $counter,
									'current' 	=> false,
									'separator'	=> true,
									'key' 		=> 'category-' . $_parent->term_id,
									'ids' 		=> [ $_parent->term_id, $_parent->slug ],
									'content' 	=> $_parent->name,
									'link'		=> get_term_link( $_parent ),
								] );

								$counter++;
							}
						}

						$this->render_item( 'category', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'category' . $last_category->term_id,
							'ids' 		=> [ $last_category->term_id, $last_category->slug ],
							'content' 	=> $last_category->name,
							'link'		=> get_term_link( $last_category ),
						] );

						$this->render_item( 'single', [
							'index'		=> $counter++,
							'current' 	=> true,
							'separator'	=> false,
							'key' 		=> 'single',
							'ids' 		=> [ $post->ID ],
							'content' 	=> get_the_title(),
						] );
						
					} else if ( ! empty( $cat_id ) ) {

						$this->render_item( 'category', [
							'index'		=> 1,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'category',
							'ids' 		=> [ $cat_nicename, $cat_id ],
							'content' 	=> $cat_name,
							'link'		=> $cat_link,
						] );

						$this->render_item( 'single', [
							'index'		=> 2,
							'current' 	=> true,
							'separator'	=> false,
							'key' 		=> 'single',
							'ids' 		=> [ $post->ID ],
							'content' 	=> get_the_title(),
						] );

					} else {

						$this->render_item( 'single', [
							'index'		=> 1,
							'current' 	=> true,
							'separator'	=> false,
							'key' 		=> 'single',
							'ids' 		=> [ $post->ID ],
							'content' 	=> get_the_title(),
						] );

					}
				}

				
				
			} else if ( $query->is_category() ) {

				$cat_id = get_query_var( 'cat' );
				$cat = get_category( $cat_id );

				$cat_parents = array_reverse( get_ancestors( $cat_id, 'category' ) );
				$counter = 1;

				foreach ( $cat_parents as $parent ) {
					$_parent = get_term( $parent );

					$this->render_item( 'category', [
						'index'		=> $counter,
						'current' 	=> false,
						'separator'	=> true,
						'key' 		=> 'category-' . $_parent->term_id,
						'ids' 		=> [ $_parent->term_id, $_parent->slug ],
						'content' 	=> $_parent->name,
						'link'		=> get_term_link( $_parent ),
					] );
					$counter++;
				}

				$this->render_item( 'category', [
					'index'		=> $counter,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'category',
					'ids' 		=> [ $cat_id, $cat->slug ],
					'content' 	=> single_cat_title( '', false ),
				] );
				
			} else if ( $query->is_page() ) {
				
				if ( $post->post_parent && 'yes' !== $settings['hide_parent']) {
						
					$anc = get_post_ancestors( $post->ID );
					$anc = array_reverse($anc);
						
					if ( ! isset( $parents ) ) $parents = null;

					$counter = 1;

					foreach ( $anc as $ancestor ) {

						$this->render_item( 'ancestor', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'ancestor-' . $ancestor,
							'ids' 		=> [ $ancestor ],
							'content' 	=> get_the_title( $ancestor ),
							'link'		=> get_permalink( $ancestor ),
						] );

						$counter++;
					}
				}

				$counter = 1;

				if ( 'page' === $settings['cpt_crumbs']) {

					$page_id = $settings['cpt_page'];

                    if (!empty($page_id)) {
                        $page = get_post($page_id);

                        $this->render_item('post-type-page', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'page-'.$page_id,
							'ids' 		=> [ $page_id, $page->post_name ],
							'content' 	=> $page->post_title,
							'link'		=> get_the_permalink($page_id),
						]);

                        $counter++;
                    }
				}

				$this->render_item( 'page', [
					'index'		=> $counter,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'page',
					'ids' 		=> [ $post->ID ],
					'content' 	=> get_the_title(),
				] );
				
			} else if ( $query->is_tag() ) {
				
				$term_id 		= get_query_var('tag_id');
				$taxonomy 		= 'post_tag';
				$args 			= 'include=' . $term_id;
				$terms 			= get_terms( $taxonomy, $args );
				$get_term_id 	= $terms[0]->term_id;
				$get_term_slug 	= $terms[0]->slug;
				$get_term_name 	= $terms[0]->name;

				$this->render_item( 'tag', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'tag',
					'ids' 		=> [ $get_term_id, $get_term_slug ],
					'content' 	=> sprintf( __( 'Tag: %s', 'elementor-extensions' ), $get_term_name ),
				] );
			
			} else if ( $query->is_day() ) {

				$this->render_item( 'year', [
					'index'		=> 1,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'year',
					'ids' 		=> [ get_the_time('Y') ],
					'content' 	=> sprintf( __( '%s Archives', 'elementor-extensions' ), get_the_time('Y') ),
					'link'		=> get_year_link( get_the_time('Y') ),
				] );

				$this->render_item( 'month', [
					'index'		=> 2,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'month',
					'ids' 		=> [ get_the_time('m') ],
					'content' 	=> sprintf( __( '%s Archives', 'elementor-extensions' ), get_the_time('F') ),
					'link'		=> get_month_link( get_the_time('Y'), get_the_time('m') ),
				] );

				$this->render_item( 'day', [
					'index'		=> 3,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'day',
					'ids' 		=> [ get_the_time('j') ],
					'content' 	=> sprintf( __( '%1$s %2$s Archives', 'elementor-extensions' ), get_the_time('F'), get_the_time('jS') ),
				] );
				
			} else if ( $query->is_month() ) {

				$this->render_item( 'year', [
					'index'		=> 1,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'year',
					'ids' 		=> [ get_the_time('Y') ],
					'content' 	=> sprintf( __( '%s Archives', 'elementor-extensions' ), get_the_time('Y') ),
					'link'		=> get_year_link( get_the_time('Y') ),
				] );

				$this->render_item( 'month', [
					'index'		=> 2,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'month',
					'ids' 		=> [ get_the_time('m') ],
					'content' 	=> sprintf( __( '%s Archives', 'elementor-extensions' ), get_the_time('F') ),
				] );
				
			} else if ( $query->is_year() ) {

				$this->render_item( 'year', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'year',
					'ids' 		=> [ get_the_time('Y') ],
					'content' 	=> sprintf( __( '%s Archives', 'elementor-extensions' ), get_the_time('Y') ),
				] );
				
			} else if ( $query->is_author() ) {
				
				global $author;

				$userdata = get_userdata( $author );

				$this->render_item( 'author', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'author',
					'ids' 		=> [ $userdata->user_nicename ],
					'content' 	=> sprintf( __( 'Author: %s', 'elementor-extensions' ), $userdata->display_name ),
				] );
				
			} else if ( $query->is_search() ) {

				$this->render_item( 'search', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'search',
					'content' 	=> sprintf( __( 'Search results for: %s', 'elementor-extensions' ), get_search_query() ),
				] );
			
			} elseif ( $query->is_404() ) {

				$this->render_item( '404', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> '404',
					'content' 	=> __( 'Page not found', 'elementor-extensions' ),
				] );
			}
		
			echo '</ul>';
			
		}
	}

	protected function render_item( $slug, $args ) {

		$defaults = [
			'current' 		=> false,
			'separator'		=> false,
			'key' 			=> false,
			'ids'			=> [],
			'content'		=> '',
			'index'			=> false,
			'link'			=> false,
		];

		$args = wp_parse_args( $args, $defaults );

		$item_key 	= $args['key'] . '-item';
		$text_key 	= $args['key'] . '-text';
		$link_key 	= ( ! $args['current'] ) ? '-link' : '-current';
		$link_key 	= $args['key'] . $link_key;
		$link_tag 	= ( ! $args['current'] ) ? 'a' : 'strong';
		$link 		= ( ! $args['current'] ) ? ' href="' . $args['link'] .'" ' : ' ';
		$classes 	= [];

		if ( $args['current'] ) {
			$classes[] = 'ee-mb-breadcrumbs__item--current';
		} else {
			$classes[] = 'ee-mb-breadcrumbs__item--parent';
		}

		if ( $slug )
			$classes[] = 'ee-mb-breadcrumbs__item--' . $slug;

		if ( $args['ids'] ) {
			foreach( $args['ids'] as $id ) {
				if ( $slug ) {
					$classes[] = 'ee-mb-breadcrumbs__item--' . $slug . '-' . $id;
				} else { $classes[] = 'ee-mb-breadcrumbs__item--' . $id; }
			}
		}

		$this->add_item_render_attribute( $item_key, $args['index'] );
		$this->add_render_attribute( $item_key, [
			'class' => $classes,
		] );

		$this->add_link_render_attribute( $link_key );
		$this->add_render_attribute( $text_key, [
			'itemprop' 	=> 'name',
			'class' 	=> 'ee-mb-breadcrumbs__text',
		] );

		?><li <?php echo $this->get_render_attribute_string( $item_key ); ?>>
			<<?php echo $link_tag; ?><?php echo $link; ?><?php echo $this->get_render_attribute_string( $link_key ); ?>>
				<span <?php echo $this->get_render_attribute_string( $text_key ); ?>>
					<?php echo $args['content']; ?>
				</span>
			</<?php echo $link_tag; ?>>
		</li><?php

		if ( $args['separator'] )
			$this->render_separator();
	}

	protected function add_item_render_attribute( $key, $index = 0 ) {

		$this->add_render_attribute( $key, [
			'class' => [
				'ee-mb-breadcrumbs__item',
			],
			'itemprop' 	=> 'itemListElement',
			'position' 	=> $index,
			'itemscope' => '',
			'itemtype' 	=> 'http://schema.org/ListItem',
		] );
	}

	protected function add_link_render_attribute( $key ) {
		$this->add_render_attribute( $key, [
			'class' => [
				'ee-mb-breadcrumbs__crumb',
			],
			'itemprop' 	=> 'item',
			'rel' 		=> 'v:url',
			'property' 	=> 'v:title',
		] );
	}

	private function get_available_pages() {
		$pages = get_posts(array(
			'post_type' => 'page',
			'post_status' => 'publish',
			'orderby' => 'title',
			'posts_per_page' => -1,
		));

		$options = [];
	
		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}
		$options[ '' ] = 'Select Page';
		return $options;
	}

	protected function _content_template() {
		
	}
}