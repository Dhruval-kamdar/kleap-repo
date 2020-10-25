<?php
/*
 * Elementor Events Addon for Elementor Events Manager Calendar Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_events_calendar'])) { // enable & disable

if ( is_plugin_active( 'events-manager/events-manager.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventsManagerCalendar extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_em_calendar';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Events Calendar', 'events-addon-for-elementor' );
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
			return ['naevents-em-category'];
		}

		/**
		 * Register Events Addon for Elementor Events Manager Calendar widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$events = get_posts( 'post_type="event"&numberposts=-1' );
	    $EventID = array();
	    if ( $events ) {
	      foreach ( $events as $event ) {
	        $EventID[ $event->ID ] = $event->post_title;
	      }
	    } else {
	      $EventID[ __( 'No ID\'s found', 'events-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Calendar Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_category',
				[
					'label' => __( 'Certain Categories?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-categories'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'event_category_hide',
				[
					'label' => esc_html__( 'Hide Selected Categories?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'event_tag',
				[
					'label' => __( 'Certain Tags?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NAEEP_Controls_Helper_Output::get_terms_names( 'event-tags'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'event_tag_hide',
				[
					'label' => esc_html__( 'Hide Selected Tags?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Certain Event\'s?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $EventID,
					'multiple' => true,
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
					'default' => 2019,
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
				]
			);
			$this->add_control(
				'event_countries',
				[
					'label' => __( 'Countries', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' => 'None',
						'AF' => 'Afghanistan',
						'AL' => 'Albania',
						'DZ' => 'Algeria',
						'AS' => 'American Samoa',
						'AD' => 'Andorra',
						'AO' => 'Angola',
						'AQ' => 'Antarctica',
						'AG' => 'Antigua and Barbuda',
						'AR' => 'Argentina',
						'AM' => 'Armenia',
						'AW' => 'Aruba',
						'AU' => 'Australia',
						'AT' => 'Austria',
						'AZ' => 'Azerbaijan',
						'BS' => 'Bahamas',
						'BH' => 'Bahrain',
						'BD' => 'Bangladesh',
						'BB' => 'Barbados',
						'BY' => 'Belarus',
						'BE' => 'Belgium',
						'BZ' => 'Belize',
						'BM' => 'Bermuda',
						'BJ' => 'Benin',
						'BT' => 'Bhutan',
						'BO' => 'Bolivia',
						'BA' => 'Bosnia and Herzegovina',
						'BW' => 'Botswana',
						'BR' => 'Brazil',
						'VG' => 'British Virgin Islands',
						'BN' => 'Brunei',
						'BG' => 'Bulgaria',
						'BF' => 'Burkina Faso',
						'BI' => 'Burundi',
						'CI' => 'C&ocirc;te D\'Ivoire',
						'KH' => 'Cambodia',
						'CM' => 'Cameroon',
						'CA' => 'Canada',
						'CV' => 'Cape Verde',
						'KY' => 'Cayman Islands',
						'CF' => 'Central African Republic',
						'TD' => 'Chad',
						'CL' => 'Chile',
						'CN' => 'China',
						'CO' => 'Colombia',
						'KM' => 'Comoros',
						'CR' => 'Costa Rica',
						'HR' => 'Croatia',
						'CU' => 'Cuba',
						'CY' => 'Cyprus',
						'CZ' => 'Czech Republic',
						'KP' => 'Democratic People\'s Republic of Korea',
						'CD' => 'Democratic Republic of the Congo',
						'DK' => 'Denmark',
						'DJ' => 'Djibouti',
						'DM' => 'Dominica',
						'DO' => 'Dominican Republic',
						'EC' => 'Ecuador',
						'EG' => 'Egypt',
						'SV' => 'El Salvador',
						'XE' => 'England',
						'GQ' => 'Equatorial Guinea',
						'ER' => 'Eritrea',
						'EE' => 'Estonia',
						'ET' => 'Ethiopia',
						'FJ' => 'Fiji',
						'FI' => 'Finland',
						'FR' => 'France',
						'PF' => 'French Polynesia',
						'GA' => 'Gabon',
						'GM' => 'Gambia',
						'GE' => 'Georgia',
						'DE' => 'Germany',
						'GH' => 'Ghana',
						'GR' => 'Greece',
						'GL' => 'Greenland',
						'GD' => 'Grenada',
						'GP' =>'Guadeloupe',
						'GU' => 'Guam',
						'GT' => 'Guatemala',
						'GN' => 'Guinea',
						'GW' => 'Guinea Bissau',
						'GY' => 'Guyana',
						'HT' => 'Haiti',
						'HN' => 'Honduras',
						'HK' => 'Hong Kong',
						'HU' => 'Hungary',
						'IS' => 'Iceland',
						'IN' => 'India',
						'ID' => 'Indonesia',
						'IR' => 'Iran',
						'IQ' => 'Iraq',
						'IE' => 'Ireland',
						'IL' => 'Israel',
						'IT' => 'Italy',
						'JE' => 'Jersey',
						'JM' => 'Jamaica',
						'JP' => 'Japan',
						'JO' => 'Jordan',
						'KZ' => 'Kazakhstan',
						'KE' => 'Kenya',
						'KI' => 'Kiribati',
						'KV' => 'Kosovo',
						'KW' => 'Kuwait',
						'KG' => 'Kyrgyzstan',
						'LA' => 'Laos',
						'LV' => 'Latvia',
						'LB' => 'Lebanon',
						'LS' => 'Lesotho',
						'LR' => 'Liberia',
						'LY' => 'Libya',
						'LI' => 'Liechtenstein',
						'LT' => 'Lithuania',
						'LU' => 'Luxembourg',
						'MO' => 'Macao',
						'MK' => 'Macedonia',
						'MG' => 'Madagascar',
						'MW' => 'Malawi',
						'MY' => 'Malaysia',
						'MV' => 'Maldives',
						'ML' => 'Mali',
						'MT' => 'Malta',
						'MH' => 'Marshall Islands',
						'MQ' => 'Mauritania',
						'MU' => 'Mauritius',
						'MR' => 'Mauritania',
						'MX' => 'Mexico',
						'FM' => 'Micronesia',
						'MD' => 'Moldova',
						'MC' => 'Monaco',
						'MN' => 'Mongolia',
						'ME' => 'Montenegro',
						'MA' => 'Morocco',
						'MZ' => 'Mozambique',
						'MM' => 'Myanmar(Burma)',
						'NA' => 'Namibia',
						'NR' => 'Nauru',
						'NP' => 'Nepal',
						'NL' => 'Netherlands',
						'AN' => 'Netherlands Antilles',
						'NC' => 'New Caledonia',
						'NZ' => 'New Zealand',
						'NI' => 'Nicaragua',
						'NE' => 'Niger',
						'NG' => 'Nigeria',
						'XI' => 'Northern Ireland',
						'MP' => 'Northern Mariana Islands',
						'NO' => 'Norway',
						'OM' => 'Oman',
						'PK' => 'Pakistan',
						'PW' => 'Palau',
						'PS' => 'Palestine',
						'PA' => 'Panama',
						'PG' => 'Papua New Guinea',
						'PY' => 'Paraguay',
						'PE' => 'Peru',
						'PH' => 'Philippines',
						'PL' => 'Poland',
						'PT' => 'Portugal',
						'PR' => 'Puerto Rico',
						'QA' => 'Qatar',
						'CG' => 'Republic of the Congo',
						'RN' => 'Réunion',
						'RO' => 'Romania',
						'RU' => 'Russia',
						'RW' => 'Rwanda',
						'ST' => 'S&agrave;o Tom&eacute; And Pr&iacute;ncipe',
						'KN' => 'Saint Kitts and Nevis',
						'LC' => 'Saint Lucia',
						'VC' => 'Saint Vincent and the Grenadines',
						'WS' => 'Samoa',
						'SM' => 'San Marino',
						'SA' => 'Saudi Arabia',
						'XS' => 'Scotland',
						'SN' => 'Senegal',
						'RS' => 'Serbia',
						'SC' => 'Seychelles',
						'SL' => 'Sierra Leone',
						'SG' => 'Singapore',
						'SK' => 'Slovakia',
						'SI' => 'Slovenia',
						'SB' => 'Solomon Islands',
						'SO' => 'Somalia',
						'ZA' => 'South Africa',
						'KR' => 'South Korea',
						'ES' => 'Spain',
						'LK' => 'Sri Lanka',
						'SD' => 'Sudan',
						'SR' => 'Suriname',
						'SZ' => 'Swaziland',
						'SE' => 'Sweden',
						'CH' => 'Switzerland',
						'SY' => 'Syria',
						'TW' => 'Taiwan',
						'TJ' => 'Tajikistan',
						'TZ' => 'Tanzania',
						'TH' => 'Thailand',
						'TL' => 'Timor-Leste',
						'TG' => 'Togo',
						'TO' => 'Tonga',
						'TT' => 'Trinidad and Tobago',
						'TN' => 'Tunisia',
						'TR' => 'Turkey',
						'TM' => 'Turkmenistan',
						'TV' => 'Tuvalu',
						'VI' => 'US Virgin Islands',
						'UG' => 'Uganda',
						'UA' => 'Ukraine',
						'AE' => 'United Arab Emirates',
						'GB' => 'United Kingdom',
						'US' => 'United States',
						'UY' => 'Uruguay',
						'UZ' => 'Uzbekistan',
						'VU' => 'Vanuatu',
						'VA' => 'Vatican',
						'VE' => 'Venezuela',
						'VN' => 'Vietnam',
						'XW' => 'Wales',
						'YE' => 'Yemen',
						'ZM' => 'Zambia',
						'ZW' => 'Zimbabwe',
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'event_bookings',
				[
					'label' => esc_html__( 'Only Events With Booking?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'event_full',
				[
					'label' => esc_html__( 'Full Sized Calendar?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'event_long_events',
				[
					'label' => esc_html__( 'Long Events?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
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
				'table_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'table_active_bg_color',
				[
					'label' => esc_html__( 'Active Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table.em-calendar td.eventless-today, {{WRAPPER}} .naeep-em-calendar table.em-calendar td.eventful-today' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'table_border',
					'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-em-calendar table td, {{WRAPPER}} .naeep-em-calendar table thead:first-child tr:first-child th',
				]
			);
			$this->add_control(
				'odd_options',
				[
					'label' => __( 'Odd Row', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'odd_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table tbody>tr:nth-child(odd)>td' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'odd_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table tbody>tr:nth-child(odd)>td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'even_options',
				[
					'label' => __( 'Even Row', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'even_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table tbody>tr:nth-child(even)>td' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'even_color',
				[
					'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table tbody>tr:nth-child(even)>td' => 'color: {{VALUE}};',
					],
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
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naeep-em-calendar table thead tr' => 'background-color: {{VALUE}};',
					],
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
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'events-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .naeep-em-calendar table thead tr',
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
						'selector' => '{{WRAPPER}} .naeep-em-calendar table td',
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
							'{{WRAPPER}} .naeep-em-calendar table td' => 'color: {{VALUE}};',
						],
					]
				);
			$this->end_controls_section();// end: Section

			// Link
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'Table Links', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'link_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .naeep-em-calendar table td a',
				]
			);
			$this->start_controls_tabs( 'link_style' );
				$this->start_controls_tab(
					'link_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-em-calendar table td a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'link_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-em-calendar table td a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Events Manager Calendar widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_category 	= !empty( $settings['event_category'] ) ? $settings['event_category'] : '';
			$event_category_hide 	= !empty( $settings['event_category_hide'] ) ? $settings['event_category_hide'] : '';
			$event_tag 				= !empty( $settings['event_tag'] ) ? $settings['event_tag'] : '';
			$event_tag_hide 	= !empty( $settings['event_tag_hide'] ) ? $settings['event_tag_hide'] : '';
			$event_id 			  = !empty( $settings['event_id'] ) ? $settings['event_id'] : '';
			$event_year 			= !empty( $settings['event_year'] ) ? $settings['event_year'] : '';
			$event_month 			= !empty( $settings['event_month'] ) ? $settings['event_month'] : '';
			$event_countries 	= !empty( $settings['event_countries'] ) ? $settings['event_countries'] : '';
			$event_bookings 	= !empty( $settings['event_bookings'] ) ? $settings['event_bookings'] : '';
			$event_full 			= !empty( $settings['event_full'] ) ? $settings['event_full'] : '';
			$event_long_events = !empty( $settings['event_long_events'] ) ? $settings['event_long_events'] : '';

			$category_hide = $event_category_hide ? '-' : '';
			$tag_hide = $event_tag_hide ? '-' : '';

			$event_bookings = $event_bookings ? '1' : '';
			$event_full = $event_full ? '1' : '';
			$event_long_events = $event_long_events ? '1' : '';

			$category = $event_category ? ' category="'.$category_hide.implode(', '.$category_hide, $event_category).'"' : '';
			$tag = $event_tag ? ' tag="'.$tag_hide.implode(', '.$tag_hide, $event_tag).'"' : '';
			$show_id = $event_id ? ' post_id="'.implode(',', $event_id).'"' : '';
			$year = $event_year ? ' year="'.$event_year.'"' : '';
			$month = $event_month ? ' month="'.$event_month.'"' : '';
			$countries = $event_countries ? ' country="'.$event_countries.'"' : '';
			$bookings = $event_bookings ? ' bookings="'.$event_bookings.'"' : '';
			$full = $event_full ? ' full="'.$event_full.'"' : '';
			$long_events = $event_long_events ? ' long_events="'.$event_long_events.'"' : '';

	  	$output = '<div class="naeep-em-calendar">'.do_shortcode( '[events_calendar'. $full . $category . $tag . $show_id . $year . $month . $countries . $bookings . $long_events . ']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventsManagerCalendar() );
}

} // enable & disable