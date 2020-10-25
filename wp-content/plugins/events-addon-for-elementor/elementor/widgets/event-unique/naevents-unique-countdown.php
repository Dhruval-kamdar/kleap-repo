<?php
/*
 * Elementor Events Addon for Elementor Unique Countdown Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'eafe_unqw_settings' )['naeafe_unique_countdown'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Event_Elementor_Addon_Unique_Countdown extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naevents_unique_countdown';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Countdown', 'events-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naevents-unique-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Countdown widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'countdown_date',
			[
				'label' => esc_html__( 'Settings', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'count_type',
			[
				'label' => __( 'Countdown Type', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'static' => esc_html__( 'Static Date', 'events-addon-for-elementor' ),
					'fake'    => esc_html__('Fake Date', 'events-addon-for-elementor'),
				],
				'default' => 'static',
				'description' => esc_html__( 'Select your countdown date type.', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'count_date_static',
			[
				'label' => esc_html__( 'Date & Time', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'dateFormat' => 'm/d/Y G:i:S',
					'enableTime' => 'true',
					'enableSeconds' => 'true',
				],
				'placeholder' => esc_html__( 'mm/dd/yyyy hh:mm:ss', 'events-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'count_type' => 'static',
				],
			]
		);
		$this->add_control(
			'fake_date',
			[
				'label' => esc_html__( 'Fake Date', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '3', 'events-addon-for-elementor' ),
				'description' => esc_html__( 'Enter your fake day count here. Ex: 2 or 3(in days)', 'events-addon-for-elementor' ),
				'condition' => [
					'count_type' => 'fake',
				],
			]
		);

		$this->add_responsive_control(
			'section_max_width',
			[
				'label' => esc_html__( 'Width', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
						'step' => 2,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'countdown_format',
			[
				'label' => esc_html__( 'Countdown Format', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'dHMS', 'events-addon-for-elementor' ),
				'description' => __( '<b>For Countdown Format Reference : <a href="http://keith-wood.name/countdown.html" target="_blank">Click Here</a></b>.', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'need_separator',
			[
				'label' => esc_html__( 'Need Separator?', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'countdown_labels',
			[
				'label' => esc_html__( 'Countdown Labels', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'plural_labels',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Plural Labels</b></div>',
			]
		);

		$this->add_control(
			'label_years',
			[
				'label' => esc_html__( 'Years Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'years', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_months',
			[
				'label' => esc_html__( 'Months Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'months', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_weeks',
			[
				'label' => esc_html__( 'Weeks Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'weeks', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_days',
			[
				'label' => esc_html__( 'Days Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'days', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_hours',
			[
				'label' => esc_html__( 'Hours Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'hours', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_minutes',
			[
				'label' => esc_html__( 'Minutes Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'minutes', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_seconds',
			[
				'label' => esc_html__( 'Seconds Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'seconds', 'events-addon-for-elementor' ),
			]
		);

		$this->add_control(
			'singular_label',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Singular Labels</b></div>',
			]
		);

		$this->add_control(
			'label_year',
			[
				'label' => esc_html__( 'Year Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'year', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_month',
			[
				'label' => esc_html__( 'Month Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'month', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_week',
			[
				'label' => esc_html__( 'Week Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'week', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_day',
			[
				'label' => esc_html__( 'Day Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'day', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_hour',
			[
				'label' => esc_html__( 'Hour Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'hour', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_minute',
			[
				'label' => esc_html__( 'Minute Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'minute', 'events-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'label_second',
			[
				'label' => esc_html__( 'Second Text', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'second', 'events-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Value
		$this->start_controls_section(
			'section_value_style',
			[
				'label' => esc_html__( 'Countdown Value', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'min_width',
			[
				'label' => esc_html__( 'Width', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'value_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section .countdown_amount',
			]
		);
		$this->add_control(
			'value_color',
			[
				'label' => esc_html__( 'Value Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section .countdown_amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'value_bg_color',
			[
				'label' => esc_html__( 'Value Background Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'value_box_border',
				'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'value_box_shadow',
				'label' => esc_html__( 'Image Box Shadow', 'events-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section',
			]
		);
		$this->add_control(
			'value_border_radius',
			[
				'label' => __( 'Border Radius', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Separator
		$this->start_controls_section(
			'section_value_sep_style',
			[
				'label' => esc_html__( 'Countdown Separator', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'value_sep_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .countdown_section:after',
			]
		);
		$this->add_control(
			'value_sep_color',
			[
				'label' => esc_html__( 'Separator Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .countdown_section:after' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Countdown Title', 'events-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .naeep-countdown-wrap .countdown_section',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .naeep-countdown-wrap .countdown_section' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Countdown widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$count_type = !empty( $settings['count_type'] ) ? $settings['count_type'] : '';
		$count_date_static = !empty( $settings['count_date_static'] ) ? $settings['count_date_static'] : '';
		$fake_date = !empty( $settings['fake_date'] ) ? $settings['fake_date'] : '';
		$countdown_format = !empty( $settings['countdown_format'] ) ? $settings['countdown_format'] : '';
		$need_separator = !empty( $settings['need_separator'] ) ? $settings['need_separator'] : '';

		// Labels Plural
		$label_years = !empty( $settings['label_years'] ) ? $settings['label_years'] : '';
		$label_months = !empty( $settings['label_months'] ) ? $settings['label_months'] : '';
		$label_weeks = !empty( $settings['label_weeks'] ) ? $settings['label_weeks'] : '';
		$label_days = !empty( $settings['label_days'] ) ? $settings['label_days'] : '';
		$label_hours = !empty( $settings['label_hours'] ) ? $settings['label_hours'] : '';
		$label_minutes = !empty( $settings['label_minutes'] ) ? $settings['label_minutes'] : '';
		$label_seconds = !empty( $settings['label_seconds'] ) ? $settings['label_seconds'] : '';

		$label_years = $label_years ? esc_html($label_years) : esc_html__('Years','events-addon-for-elementor');
		$label_months = $label_months ? esc_html($label_months) : esc_html__('Months','events-addon-for-elementor');
		$label_weeks = $label_weeks ? esc_html($label_weeks) : esc_html__('Weeks','events-addon-for-elementor');
		$label_days = $label_days ? esc_html($label_days) : esc_html__('Days','events-addon-for-elementor');
		$label_hours = $label_hours ? esc_html($label_hours) : esc_html__('Hours','events-addon-for-elementor');
		$label_minutes = $label_minutes ? esc_html($label_minutes) : esc_html__('Minutes','events-addon-for-elementor');
		$label_seconds = $label_seconds ? esc_html($label_seconds) : esc_html__('Seconds','events-addon-for-elementor');

		// Labels Singular
		$label_year = !empty( $settings['label_year'] ) ? $settings['label_year'] : '';
		$label_month = !empty( $settings['label_month'] ) ? $settings['label_month'] : '';
		$label_week = !empty( $settings['label_week'] ) ? $settings['label_week'] : '';
		$label_day = !empty( $settings['label_day'] ) ? $settings['label_day'] : '';
		$label_hour = !empty( $settings['label_hour'] ) ? $settings['label_hour'] : '';
		$label_minute = !empty( $settings['label_minute'] ) ? $settings['label_minute'] : '';
		$label_second = !empty( $settings['label_second'] ) ? $settings['label_second'] : '';

		$label_year = $label_year ? esc_html($label_year) : esc_html__('Year','events-addon-for-elementor');
		$label_month = $label_month ? esc_html($label_month) : esc_html__('Month','events-addon-for-elementor');
		$label_week = $label_week ? esc_html($label_week) : esc_html__('Week','events-addon-for-elementor');
		$label_day = $label_day ? esc_html($label_day) : esc_html__('Day','events-addon-for-elementor');
		$label_hour = $label_hour ? esc_html($label_hour) : esc_html__('Hour','events-addon-for-elementor');
		$label_minute = $label_minute ? esc_html($label_minute) : esc_html__('Minute','events-addon-for-elementor');
		$label_second = $label_second ? esc_html($label_second) : esc_html__('Second','events-addon-for-elementor');

		$countdown_format = $countdown_format ? $countdown_format : '';

		if ($count_type === 'fake') {
			$count_date_actual = $fake_date;
		} else {
			$count_date_actual = $count_date_static;
		}

		if ($need_separator) {
			$sep_class = ' need-separator';
		} else {
			$sep_class = '';
		}

		$output = '';
		$output .= '<div class="naeep-countdown-wrap'.$sep_class.'">
				          <div class="naeep-countdown '.esc_attr($count_type).'" data-date="'.esc_attr($count_date_actual).'" data-years="'.esc_attr($label_years).'" data-months="'.esc_attr($label_months).'" data-weeks="'.esc_attr($label_weeks).'" data-days="'.esc_attr($label_days).'" data-hours="'.esc_attr($label_hours).'" data-minutes="'.esc_attr($label_minutes).'" data-seconds="'.esc_attr($label_seconds).'" data-year="'.esc_attr($label_year).'" data-month="'.esc_attr($label_month).'" data-week="'.esc_attr($label_week).'" data-day="'.esc_attr($label_day).'" data-hour="'.esc_attr($label_hour).'" data-minute="'.esc_attr($label_minute).'" data-second="'.esc_attr($label_second).'" data-format="'.esc_attr($countdown_format).'"><div class="clearfix"></div>
				          </div>
				        </div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_Unique_Countdown() );

} // enable & disable