<?php
/*
 * Elementor Events Addon for Elementor EventOrganiser Info Box Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_info_box'])) { // enable & disable

if ( is_plugin_active( 'event-organiser/event-organiser.php' ) ) {
	
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventOrganiser_InfoBox extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_eo_infobox';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Info Box', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-info-box';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-pro-eo-category'];
		}
		
		/**
		 * Register Events Addon for Elementor EventOrganiser Info Box widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$args = array(
	    'post_type' => 'event',
	    'posts_per_page' => -1,
	    );
	    $pages = eo_get_events($args);
	    $event_post = array();
	    if ( $pages ) {
	      foreach ( $pages as $page ) {
	        $event_post[ $page->ID ] = $page->post_title;
	      }
	    } else {
	      $event_post[ esc_html__( 'No Events found', 'events-addon-for-elementor' ) ] = 0;
	    }
			
			$this->start_controls_section(
				'section_infobox',
				[
					'label' => __( 'Info Box Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Event ID', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $event_post,
					'multiple' => false,
				]
			);		
			$this->start_controls_tabs( 'titles' );
				$this->start_controls_tab(
					'title_when',
					[
						'label' => esc_html__( 'When', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'when_icon',
					[
						'label' => esc_html__( 'When Icon', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::ICON,
						'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
						'frontend_available' => true,
						'default' => 'fa fa-calendar',
					]
				);
				$this->add_control(
					'when_title',
					[
						'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => esc_html__( 'When', 'events-addon-for-elementor' ),
						'label_block' => true,
						
					]
				);
				$this->add_control(
					'date_format',
					[
						'label' => esc_html__( 'Date Formate', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => esc_html__( 'd M, Y', 'events-addon-for-elementor' ),
						'description' => __( 'Enter date format (for more info <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">click here</a>).', 'events-addon-for-elementor' ),
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_where',
					[
						'label' => esc_html__( 'Where', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'where_icon',
					[
						'label' => esc_html__( 'Where Icon', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::ICON,
						'options' => NAEEP_Controls_Helper_Output::get_include_icons(),
						'frontend_available' => true,
						'default' => 'fa fa-map-marker',
					]
				);
				$this->add_control(
					'where_title',
					[
						'label' => esc_html__( 'Title Text', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => esc_html__( 'Where', 'events-addon-for-elementor' ),
						'label_block' => true,
						
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs		
			$this->end_controls_section();// end: Section

			// Section
				$this->start_controls_section(
					'section_box_style',
					[
						'label' => esc_html__( 'Section', 'events-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'row_margin',
					[
						'label' => __( 'Row Margin', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .naeep-event-info .col-na-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'col_padding',
					[
						'label' => __( 'Column Padding', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .naeep-event-info .col-na-4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'section_border_radius',
					[
						'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .event-info-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'section_padding',
					[
						'label' => __( 'Padding', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .event-info-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'section_style' );
					$this->start_controls_tab(
						'section_normal',
						[
							'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
						]
					);		
					$this->add_control(
						'section_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .event-info-item' => 'background-color: {{VALUE}};',
							],
						]
					);	
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'section_box_border',
							'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .event-info-item',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'section_box_shadow',
							'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .event-info-item',
						]
					);
					$this->end_controls_tab();  // end:Normal tab			
					$this->start_controls_tab(
						'section_hover',
						[
							'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'section_hov_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .event-info-item:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'section_hov_border',
							'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .event-info-item.naeep-hover',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'section_hov_box_shadow',
							'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .event-info-item.naeep-hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section	

			// Icon
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => esc_html__( 'Icon', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);		
			$this->add_responsive_control(
				'icon_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .event-info-item .naeep-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .event-info-item .naeep-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .event-info-item .naeep-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_name_style',
				[
					'label' => esc_html__( 'Title', 'events-addon-for-elementor' ),
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
						'{{WRAPPER}} .event-info-item h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'name_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .event-info-item h3',
				]
			);
			$this->add_control(
				'name_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .event-info-item h3' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .event-info-item span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .event-info-item span',
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
						'{{WRAPPER}} .event-info-item span' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section
		}

		/**
		 * Render App Works widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();

			$event_id = !empty( $settings['event_id'] ) ? $settings['event_id'] : '';					
			$when_icon = !empty( $settings['when_icon'] ) ? $settings['when_icon'] : '';
			$when_title = !empty( $settings['when_title'] ) ? $settings['when_title'] : '';
			$where_icon = !empty( $settings['where_icon'] ) ? $settings['where_icon'] : '';
			$where_title = !empty( $settings['where_title'] ) ? $settings['where_title'] : '';
			$date_format = !empty( $settings['date_format'] ) ? $settings['date_format'] : '';
	  	$date_format = $date_format ? $date_format : 'd M, Y';

			$when_icon = $when_icon ? '<div class="naeep-icon"><i class="'.$when_icon.'"></i></div>' : '';
			$where_icon = $where_icon ? '<div class="naeep-icon"><i class="'.$where_icon.'"></i></div>' : '';

			// Turn output buffer on
			ob_start();

			// Pagination
			global $paged;
			if ( get_query_var( 'paged' ) )
			  $my_page = get_query_var( 'paged' );
			else {
			  if ( get_query_var( 'page' ) )
				$my_page = get_query_var( 'page' );
			  else
				$my_page = 1;
			  set_query_var( 'paged', $my_page );
			  $paged = $my_page;
			}
			if ($event_id) {
				$event_id = json_encode( $event_id );
				$event_id = str_replace(array( '[', ']' ), '', $event_id);
				$event_id = str_replace(array( '"', '"' ), '', $event_id);
	      $event_id = explode(',',$event_id);
	    } else {
	      $event_id = '';
	    }
			$args = array(
			  'paged' => $my_page,
			  'post_type' => 'event',
			  'posts_per_page' => 1,
		  	'post__in' => $event_id,
			);
			$naevents_event = new \WP_Query( $args );
			if ($naevents_event->have_posts()) : ?>
					<div class="naeep-event-info">
					  <div class="col-na-row">
							<?php while ($naevents_event->have_posts()) : $naevents_event->the_post(); ?>
								<div class="col-na-6">
									<div class="event-info-item naeep-item">
										<?php echo $when_icon; ?>
										<h3><?php echo esc_html($when_title); ?></h3>
							    	<span><?php echo eo_get_the_start( 'l' ); ?> - <?php echo eo_get_the_end( 'l' ); ?></span>
							    	<span><?php echo eo_get_the_start( 'd' ); ?> - <?php echo eo_get_the_end( $date_format ); ?></span>
							    </div>
								</div>
								<div class="col-na-6">
									<div class="event-info-item naeep-item">
										<?php echo $where_icon; ?>
										<h3><?php echo esc_html($where_title); ?></h3>
							    	<span>
							    		<?php echo eo_get_venue_name(); ?>
							    	</span>
									</div>
								</div>
							<?php endwhile;						
							wp_reset_postdata(); ?>
						</div> <!-- event End -->
					</div> <!-- event End -->
				<?php 
			endif;
			// Return outbut buffer
			echo ob_get_clean();
			
		}
		
	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventOrganiser_InfoBox() );
}

} // enable & disable