<?php
namespace ElementorExtensions\Modules\GoogleCalendar\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use ElementorExtensions\Admin\EE_MB_Setting_Common;
use Elementor\Group_Control_Border;

class EE_Google_Calendar extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'google-calendar';
	}

	public function get_title() {
		return __( 'Google Calendar', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-archive-posts';
	}

	public function get_style_depends() {
		return [
			'ee-mb-fullcalendar'
		];
	}

	public function get_script_depends() {
		return [
			'moment',
			'ee-mb-fullcalendar',
			'ee-mb-gcal',
			'ee-mb-daygrid',
			'ee-mb-list',
			'ee-mb-interaction',
			'ee-mb-timegrid'
		];
	}

	public function get_keywords() {
		return [ 'google calendar', 'google', 'calendar', 'goo', 'cal', 'gc', 'g' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_top_header',
			[
				'label' => __( 'Google Calendar', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'cal_id',
			[
				'label' => __( 'Calendar ID', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'cal_layout_type',
			[
				'label' => __( 'Layout', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'grid' => __( 'Grid', 'elementor-extensions' ),
					'list' => __( 'List', 'elementor-extensions' ),
				],
				'default' => 'grid',
				'frontend_available' => true
			]
		);
			
		$this->end_controls_section();

		$this->start_controls_section(
            'month_header',
            [
                'label' => __( 'Month Header', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'month_header_color',
            [
                'label' => __( 'Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-mb-google-calendar .fc-header-toolbar .fc-center h2' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'month_header_typo',
                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-header-toolbar .fc-center h2',
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'header_navigation_button',
            [
                'label' => __( 'Navigation Button', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

			$this->start_controls_tabs( 'tabs_month_navigation_buttons' );

				$this->start_controls_tab(
					'tabs_month_navigation_button_normal',
					[
						'label' => __( 'Normal', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'normal_month_navigation_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button, {{WRAPPER}} .ee-mb-google-calendar .fc-next-button',
			            ]
					);

					$this->add_control(
			            'normal_month_navigation_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button' => 'color: {{VALUE}};',
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-next-button' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'normal_month_navigation_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button' => 'background: {{VALUE}};',
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-next-button' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_month_navigation_button_hover',
					[
						'label' => __( 'Hover', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'hover_month_navigation_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button:hover, {{WRAPPER}} .ee-mb-google-calendar .fc-next-button:hover',
			            ]
					);

					$this->add_control(
			            'hover_month_navigation_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button:hover' => 'color: {{VALUE}};',
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-next-button:hover' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'hover_month_navigation_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button:hover' => 'background: {{VALUE}};',
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-next-button:hover' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_month_navigation_button_active',
					[
						'label' => __( 'Active', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'active_month_navigation_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button:focus, {{WRAPPER}} .ee-mb-google-calendar .fc-next-button:focus',
			            ]
					);

					$this->add_control(
			            'active_month_navigation_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button:focus' => 'color: {{VALUE}};',
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-next-button:focus' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'active_month_navigation_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-prev-button:focus'  => 'background: {{VALUE}};',
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-next-button:focus' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
            'header_todays_button',
            [
                'label' => __( "Today's Button", 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

			$this->start_controls_tabs( 'tabs_todays_buttons' );

				$this->start_controls_tab(
					'tabs_todays_button_normal',
					[
						'label' => __( 'Normal', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'normal_todays_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-today-button',
			            ]
					);

					$this->add_control(
			            'normal_todays_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-today-button' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'normal_todays_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-today-button' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_todays_button_hover',
					[
						'label' => __( 'Hover', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'hover_todays_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-today-button:hover',
			            ]
					);

					$this->add_control(
			            'hover_todays_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-today-button:hover' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'hover_todays_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-today-button:hover' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
            'header_rightside_button',
            [
                'label' => __( "Day / Month / Year Buttons", 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

			$this->start_controls_tabs( 'tabs_rightside_buttons' );

				$this->start_controls_tab(
					'tabs_rightside_button_normal',
					[
						'label' => __( 'Normal', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'normal_rightside_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-right button',
			            ]
					);

					$this->add_control(
			            'normal_rightside_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-right button' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'normal_rightside_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-right button' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_rightside_button_hover',
					[
						'label' => __( 'Hover', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'hover_rightside_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-right button:hover',
			            ]
					);

					$this->add_control(
			            'hover_rightside_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-right button:hover' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'hover_rightside_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-right button:hover' => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_rightside_button_active',
					[
						'label' => __( 'Active', 'elementor-extensions' ),
					]
				);

					$this->add_group_control(
			            Group_Control_Typography::get_type(),
			            [
							'label' => __( 'Typography', 'elementor-extensions' ),
			                'name' => 'active_rightside_button_typo',
			                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-right button:focus',
			            ]
					);

					$this->add_control(
			            'active_rightside_button_color',
			            [
			                'label' => __( 'Text Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-right button:focus' => 'color: {{VALUE}};',
			                ],
			            ]
			        );
					
					$this->add_control(
			            'active_rightside_button_bg_color',
			            [
			                'label' => __( 'Background Color', 'elementor-extensions' ),
			                'type' => Controls_Manager::COLOR,
			                'selectors' => [
			                    '{{WRAPPER}} .ee-mb-google-calendar .fc-right button:focus'  => 'background: {{VALUE}};',
			                ],
			            ]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
            'day_header',
            [
                'label' => __( 'Day Header', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_name_typography',
                'selector' => '{{WRAPPER}} .ee-mb-google-calendar .fc-widget-header .fc-day-header',
            ]
        );

		$this->add_control(
            'day_name_color',
            [
                'label' => __( 'Text Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-mb-google-calendar .fc-day-header' => 'color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_control(
            'day_name_background_color',
            [
                'label' => __( 'Background Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-mb-google-calendar .fc-day-header' => 'background-color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'day_name_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .ee-mb-google-calendar th.fc-day-header,{{WRAPPER}} .ee-mb-google-calendar .fc-row.fc-widget-header',
				
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'date_section',
            [
                'label' => __( 'Date', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .ee-mb-google-calendar td .fc-day-number',
            ]
        );

		$this->add_control(
            'date_color',
            [
                'label' => __( 'Text Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-mb-google-calendar .fc-day-number' => 'color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_control(
            'date_background_color',
            [
                'label' => __( 'Background Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-mb-google-calendar .fc-day-top' => 'background-color: {{VALUE}};',
                ],
            ]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'grid_cell_section',
			[
				'label' => __( 'Grid Cell', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'cell_border',
				'placeholder' => '1px',
				'default' => '1px',
				'separator' => 'after',
				'selector' => '{{WRAPPER}} .fc-unthemed td',
			]
		);
	
		$this->add_control(
            'cell_today_color',
            [
                'label' => __( 'Today`s Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-mb-google-calendar div.fc-row td.fc-today' => 'background-color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_control(
            'cell_odd_row_color',
            [
                'label' => __( 'Odd Row Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} div.fc-row.fc-week:nth-child(odd) tbody td' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_control(
            'cell_even_row_color',
            [
                'label' => __( 'Even Row Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} div.fc-row.fc-week:nth-child(even) tbody td' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		
		$this->end_controls_section();
				
		$this->start_controls_section(
			'event_label_section',
			[
				'label' => __( 'Event Label', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'event_label_typography',
                'selector' => '{{WRAPPER}} .ee-mb-google-calendar tr .fc-event-container',
            ]
        );

		$this->add_control(
            'event_label_color',
            [
                'label' => __( 'Text Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-event' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_control(
            'event_label_hover_color',
            [
                'label' => __( 'Hover Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-event:hover' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_control(
            'event_label_bg_color',
            [
                'label' => __( 'Background Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-event' => 'background-color: {{VALUE}}; border-color:{{VALUE}};',
                ],
            ]
		);

		$this->add_control(
            'event_label_bg_hover_color',
            [
                'label' => __( 'Hover Background Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-event:hover' => 'background-color: {{VALUE}}; border-color:{{VALUE}};',
                ],
            ]
		);
		
		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();
		
		$cal_key = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_google_calendar_key');
		$cal_uid = uniqid();
		$cal_id = $settings['cal_id'];

		if( !empty($cal_key) && !empty($cal_id) ):
			echo '<div id="calendar-'.$cal_uid.'" class="ee-mb-google-calendar" data-cal-uid="'.$cal_uid.'" data-cal-api-key="'.$cal_key.'"></div>';
		else:
			echo '<div class="elementor-calendar-notice">Please add Google Calendar API key & Calendar ID.</div>';	
		endif;
	}

    protected function _content_template() {
    }

}