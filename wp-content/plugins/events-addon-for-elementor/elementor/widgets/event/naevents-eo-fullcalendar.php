<?php
/*
 * Elementor Events Addon for Elementor Event Organiser Full Calendar Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_full_calendar'])) { // enable & disable

if ( is_plugin_active( 'event-organiser/event-organiser.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventOrganiserFullCalendar extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_eo_fullcalendar';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events Full Calendar', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-calendar';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-eo-category'];
		}

		/**
		 * Register Events Addon for Elementor Event Organiser Full Calendar widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Full Calendar Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'defaultView',
				[
					'label' => __( 'Default View ', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'month' => esc_html__( 'Month', 'events-addon-for-elementor' ),
						'agendaWeek' => esc_html__( 'Agenda Week', 'events-addon-for-elementor' ),
						'agendaDay' => esc_html__( 'Agenda Day', 'events-addon-for-elementor' ),
						'basicWeek' => esc_html__( 'Basic Week', 'events-addon-for-elementor' ),
						'basicDay' => esc_html__( 'Basic Day', 'events-addon-for-elementor' ),
					],
					'default' => 'month',
				]
			);
			$this->add_control(
				'event_year',
				[
					'label' => esc_html__( 'Year', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 2019,
					'max' => 3000,
					'step' => 1,
					'default' => '',
					'description' => esc_html__( 'Enter the month of items to show.', 'events-addon-for-elementor' ),
					'description' => __( 'If set to a year <b>(e.g. 2019)</b> only events that start or end during this year will be returned', 'events-addon-for-elementor'),
				]
			);
			$this->add_control(
				'event_month',
				[
					'label' => esc_html__( 'Month', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 12,
					'step' => 1,
					'default' => '',
					'description' => esc_html__( 'Enter the month of items to show.', 'events-addon-for-elementor' ),
					'condition' => [
						'event_year!' => '',
					],
				]
			);
			$this->add_control(
				'event_date',
				[
					'label' => esc_html__( 'Date', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 31,
					'step' => 1,
					'default' => '',
					'description' => esc_html__( 'Enter the date of items to show.', 'events-addon-for-elementor' ),
					'condition' => [
						'event_month!' => '',
					],
				]
			);
			$this->add_control(
				'headerLeft',
				[
					'label' => __( 'Header Left', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => [
						'title' 		 => esc_html__( 'Title', 'events-addon-for-elementor' ),
						'month'      => esc_html__( 'Month', 'events-addon-for-elementor' ),
						'agendaWeek' => esc_html__( 'Agenda Week', 'events-addon-for-elementor' ),
						'agendaDay'  => esc_html__( 'Agenda Day', 'events-addon-for-elementor' ),
						'basicWeek'  => esc_html__( 'Basic Week', 'events-addon-for-elementor' ),
						'basicDay' 	 => esc_html__( 'Basic Day', 'events-addon-for-elementor' ),
						'category' 	 => esc_html__( 'Category', 'events-addon-for-elementor' ),
						'venue' 		 => esc_html__( 'Venue', 'events-addon-for-elementor' ),
						'next' 			 => esc_html__( 'Next', 'events-addon-for-elementor' ),
						'prev' 			 => esc_html__( 'Prev', 'events-addon-for-elementor' ),
						'today' 		 => esc_html__( 'Today', 'events-addon-for-elementor' ),
						'goto' 			 => esc_html__( 'Goto', 'events-addon-for-elementor' ),
					],
					'multiple' => true,
				]
			);
			$this->add_control(
				'headerCenter',
				[
					'label' => __( 'Header Center', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => [
						'title' 		 => esc_html__( 'Title', 'events-addon-for-elementor' ),
						'month'      => esc_html__( 'Month', 'events-addon-for-elementor' ),
						'agendaWeek' => esc_html__( 'Agenda Week', 'events-addon-for-elementor' ),
						'agendaDay'  => esc_html__( 'Agenda Day', 'events-addon-for-elementor' ),
						'basicWeek'  => esc_html__( 'Basic Week', 'events-addon-for-elementor' ),
						'basicDay' 	 => esc_html__( 'Basic Day', 'events-addon-for-elementor' ),
						'category' 	 => esc_html__( 'Category', 'events-addon-for-elementor' ),
						'venue' 		 => esc_html__( 'Venue', 'events-addon-for-elementor' ),
						'next' 			 => esc_html__( 'Next', 'events-addon-for-elementor' ),
						'prev' 			 => esc_html__( 'Prev', 'events-addon-for-elementor' ),
						'today' 		 => esc_html__( 'Today', 'events-addon-for-elementor' ),
						'goto' 			 => esc_html__( 'Goto', 'events-addon-for-elementor' ),
					],
					'multiple' => true,
				]
			);
			$this->add_control(
				'headerRight',
				[
					'label' => __( 'Header Right', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => [
						'title' 		 => esc_html__( 'Title', 'events-addon-for-elementor' ),
						'month'      => esc_html__( 'Month', 'events-addon-for-elementor' ),
						'agendaWeek' => esc_html__( 'Agenda Week', 'events-addon-for-elementor' ),
						'agendaDay'  => esc_html__( 'Agenda Day', 'events-addon-for-elementor' ),
						'basicWeek'  => esc_html__( 'Basic Week', 'events-addon-for-elementor' ),
						'basicDay' 	 => esc_html__( 'Basic Day', 'events-addon-for-elementor' ),
						'category' 	 => esc_html__( 'Category', 'events-addon-for-elementor' ),
						'venue' 		 => esc_html__( 'Venue', 'events-addon-for-elementor' ),
						'next' 			 => esc_html__( 'Next', 'events-addon-for-elementor' ),
						'prev' 			 => esc_html__( 'Prev', 'events-addon-for-elementor' ),
						'today' 		 => esc_html__( 'Today', 'events-addon-for-elementor' ),
						'goto' 			 => esc_html__( 'Goto', 'events-addon-for-elementor' ),
					],
					'multiple' => true,
				]
			);
			$this->add_control(
				'event_category',
				[
					'label' => __( 'Certain Categories?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-category'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'event_tag',
				[
					'label' => __( 'Certain Tags?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-tag'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'theme',
				[
					'label' => esc_html__( 'Disable jQuery UI Theme?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => __( 'Whether to use the jQuery UI theme. Setting this to false makes styling the calendar easier.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'tooltip',
				[
					'label' => esc_html__( 'Need Tooltip?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => __( 'Whether to display a tooltip.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'weekends',
				[
					'label' => esc_html__( 'Need Weekends?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => __( 'Whether to include weekends in the calendar.', 'events-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

			// Table
			$this->start_controls_section(
				'table_style',
				[
					'label' => esc_html__( 'Table', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'table_active_bg_color',
				[
					'label' => esc_html__( 'Active Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eo-fullcalendar-reset.fc-unthemed .fc-today' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'table_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .fc td, {{WRAPPER}} .fc th',
				]
			);
			$this->end_controls_section();// end: Section

			// Head
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Table Head', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-em-calendar table thead tr, {{WRAPPER}} .naeep-em-calendar table thead:first-child tr:first-child th',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'sastable_head_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .naeep-em-calendar table thead tr td',
				]
			);
			$this->add_control(
				'sastable_head_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table thead tr td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Text
			$this->start_controls_section(
				'section_text_style',
				[
					'label' => esc_html__( 'Table Text', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'text_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .eo-fullcalendar .fc-event, {{WRAPPER}} .fc-row .fc-content-skeleton td',
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .eo-fullcalendar .fc-event' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'date_color',
				[
					'label' => esc_html__( 'Date Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .fc-row .fc-content-skeleton td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event Organiser Full Calendar widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$defaultView 	= !empty( $settings['defaultView'] ) ? $settings['defaultView'] : '';
			$event_year 			= !empty( $settings['event_year'] ) ? $settings['event_year'] : '';
			$event_month 			= !empty( $settings['event_month'] ) ? $settings['event_month'] : '';
			$event_date 			  = !empty( $settings['event_date'] ) ? $settings['event_date'] : '';
			$event_category 	= !empty( $settings['event_category'] ) ? $settings['event_category'] : '';
			$event_tag 				= !empty( $settings['event_tag'] ) ? $settings['event_tag'] : '';
			$headerLeft 	= !empty( $settings['headerLeft'] ) ? $settings['headerLeft'] : '';
			$headerCenter 	= !empty( $settings['headerCenter'] ) ? $settings['headerCenter'] : '';
			$headerRight 	= !empty( $settings['headerRight'] ) ? $settings['headerRight'] : '';
			$theme 	= !empty( $settings['theme'] ) ? $settings['theme'] : '';
			$tooltip 	= !empty( $settings['tooltip'] ) ? $settings['tooltip'] : '';
			$weekends 	= !empty( $settings['weekends'] ) ? $settings['weekends'] : '';

			$theme = $theme ? 'true' : 'false';
			$tooltip = $tooltip ? 'true' : 'false';
			$weekends = $weekends ? 'true' : 'false';

			$defaultView = $defaultView ? ' defaultView="'.$defaultView.'"' : '';
			$year = $event_year ? ' year="'.$event_year.'"' : '';
			$month = $event_month ? ' month="'.$event_month.'"' : '';
			$date = $event_date ? ' date="'.$event_date.'"' : '';
			$category = $event_category ? ' category="'.implode(',', $event_category).'"' : '';
			$tag = $event_tag ? ' tag="'.implode(',', $event_tag).'"' : '';
			$headerLeft = $headerLeft ? ' headerLeft="'.implode(',', $headerLeft).'"' : '';
			$headerRight = $headerRight ? ' headerRight="'.implode(',', $headerRight).'"' : '';
			$headerCenter = $headerCenter ? ' headerCenter="'.implode(',', $headerCenter).'"' : '';
			$theme = $theme ? ' theme="'.$theme.'"' : '';
			$tooltip = $tooltip ? ' tooltip="'.$tooltip.'"' : '';
			$weekends = $weekends ? ' weekends="'.$weekends.'"' : '';

	  	$output = '<div class="naeep-eo-fullcalendar">'.do_shortcode( '[eo_fullcalendar'. $defaultView . $year . $month . $date . $category . $tag . $headerLeft . $headerRight . $headerCenter . $theme . $tooltip . $weekends .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventOrganiserFullCalendar() );
}

} // enable & disable