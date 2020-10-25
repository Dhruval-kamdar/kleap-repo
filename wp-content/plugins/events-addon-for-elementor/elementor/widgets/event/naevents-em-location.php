<?php
/*
 * Elementor Events Addon for Elementor Events Manager Location Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if (!isset(get_option( 'eafe_prow_settings' )['naeafe_pro_event_locations'])) { // enable & disable

if ( is_plugin_active( 'events-manager/events-manager.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Event_Elementor_Addon_EventsManagerLocation extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naevents_em_location';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Event Locations', 'events-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-google-maps';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naevents-em-category'];
		}

		/**
		 * Register Events Addon for Elementor Events Manager Location widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_event',
				[
					'label' => esc_html__( 'Events Location Options', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_limit',
				[
					'label' => esc_html__( 'Limit', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => 4,
					'description' => esc_html__( 'Enter the number of items to show.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_offset',
				[
					'label' => esc_html__( 'Offset', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => '',
					'description' => esc_html__( 'For example, if you have ten results, if you set this to 5, only the last 5 results will be returned. A limit higher than 0 is required for offsets to work.', 'events-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'event_scope',
				[
					'label' => __( 'Scope', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'all' 			 => esc_html__( 'All', 'events-addon-for-elementor' ),
						'future' 		 => esc_html__( 'Future', 'events-addon-for-elementor' ),
						'past' 			 => esc_html__( 'Past', 'events-addon-for-elementor' ),
						'today' 		 => esc_html__( 'Today', 'events-addon-for-elementor' ),
						'tomorrow' 	 => esc_html__( 'Tomorrow', 'events-addon-for-elementor' ),
						'month' 		 => esc_html__( 'Month', 'events-addon-for-elementor' ),
						'next-month' => esc_html__( 'Next Month', 'events-addon-for-elementor' ),
						'1-months' 	 => esc_html__( '1 Months', 'events-addon-for-elementor' ),
						'2-months' 	 => esc_html__( '2 Months', 'events-addon-for-elementor' ),
						'3-months' 	 => esc_html__( '3 Months', 'events-addon-for-elementor' ),
						'6-months' 	 => esc_html__( '6 Months', 'events-addon-for-elementor' ),
						'12-months'  => esc_html__( '12 Months', 'events-addon-for-elementor' ),
					],
					'default' => 'all',
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
				'event_order',
				[
					'label' => __( 'Order', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'ASC' => esc_html__( 'Asending', 'events-addon-for-elementor' ),
						'DESC' => esc_html__( 'Desending', 'events-addon-for-elementor' ),
					],
					'default' => 'ASC',
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
				'event_bookings',
				[
					'label' => esc_html__( 'Only Locations With Booking?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'event_pagination',
				[
					'label' => esc_html__( 'Location Pagination?', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'events-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'events-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->end_controls_section();// end: Section

			// List
			$this->start_controls_section(
				'section_list_style',
				[
					'label' => esc_html__( 'List', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'list_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-em-location ul.em-locations-list li',
				]
			);
			$this->start_controls_tabs( 'list_style' );
				$this->start_controls_tab(
					'list_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'list_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-em-location ul.em-locations-list li, {{WRAPPER}} .naeep-em-location ul.em-locations-list li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Bullet Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-em-location ul.em-locations-list li:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'list_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'list_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-em-location ul.em-locations-list li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Sub List
			$this->start_controls_section(
				'section_sub_list_style',
				[
					'label' => esc_html__( 'Sub List', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'events-addon-for-elementor' ),
					'name' => 'sub_list_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .naeep-em-location ul.em-locations-list ul li',
				]
			);
			$this->start_controls_tabs( 'sub_list_style' );
				$this->start_controls_tab(
					'sub_list_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'sub_list_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						],
						'selectors' => [
							'{{WRAPPER}} .naeep-em-location ul.em-locations-list ul li, {{WRAPPER}} .naeep-em-location ul.em-locations-list ul li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'subicon_color',
					[
						'label' => esc_html__( 'Bullet Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-em-location ul.em-locations-list ul li:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'sub_list_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'sub_list_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naeep-em-location ul.em-locations-list ul li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Pagination
			$this->start_controls_section(
				'section_pagi_style',
				[
					'label' => esc_html__( 'Pagination', 'events-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'event_pagination' => 'true',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_min_width',
				[
					'label' => esc_html__( 'Size', 'events-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 35,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .em-pagination a, {{WRAPPER}} .em-pagination span' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagi_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .em-pagination a, {{WRAPPER}} .em-pagination span',
				]
			);
			$this->start_controls_tabs( 'pagi_style' );
				$this->start_controls_tab(
					'pagi_normal',
					[
						'label' => esc_html__( 'Normal', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .em-pagination a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .em-pagination a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .em-pagination a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'pagi_hover',
					[
						'label' => esc_html__( 'Hover', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_hover_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .em-pagination a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .em-pagination a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_hover_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .em-pagination a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
				$this->start_controls_tab(
					'pagi_active',
					[
						'label' => esc_html__( 'Active', 'events-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_active_color',
					[
						'label' => esc_html__( 'Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .em-pagination span.current' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_active_color',
					[
						'label' => esc_html__( 'Background Color', 'events-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .em-pagination span.current' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_active_border',
						'label' => esc_html__( 'Border', 'events-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .em-pagination span.current',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Events Manager Location widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$event_limit 			= !empty( $settings['event_limit'] ) ? $settings['event_limit'] : '';
			$event_offset 		= !empty( $settings['event_offset'] ) ? $settings['event_offset'] : '';
			$event_scope 			= !empty( $settings['event_scope'] ) ? $settings['event_scope'] : '';
			$event_countries 	= !empty( $settings['event_countries'] ) ? $settings['event_countries'] : '';
			$event_order 			= !empty( $settings['event_order'] ) ? $settings['event_order'] : '';
			$event_category 	= !empty( $settings['event_category'] ) ? $settings['event_category'] : '';
			$event_category_hide 	= !empty( $settings['event_category_hide'] ) ? $settings['event_category_hide'] : '';
			$event_tag 				= !empty( $settings['event_tag'] ) ? $settings['event_tag'] : '';
			$event_tag_hide 	= !empty( $settings['event_tag_hide'] ) ? $settings['event_tag_hide'] : '';
			$event_year 			= !empty( $settings['event_year'] ) ? $settings['event_year'] : '';
			$event_month 			= !empty( $settings['event_month'] ) ? $settings['event_month'] : '';
			$event_bookings 	= !empty( $settings['event_bookings'] ) ? $settings['event_bookings'] : '';
			$event_pagination = !empty( $settings['event_pagination'] ) ? $settings['event_pagination'] : '';

			$category_hide = $event_category_hide ? '-' : '';
			$tag_hide = $event_tag_hide ? '-' : '';

			$event_bookings = $event_bookings ? '1' : '';
			$event_pagination = $event_pagination ? '1' : '';

			$limit = $event_limit ? ' limit="'.$event_limit.'"' : '';
			$offset = $event_offset ? ' offset="'.$event_offset.'"' : '';
			$scope = $event_scope ? ' scope="'.$event_scope.'"' : '';
			$countries = $event_countries ? ' country="'.$event_countries.'"' : '';
			$order = $event_order ? ' order="'.$event_order.'"' : '';
			$category = $event_category ? ' category="'.$category_hide.implode(', '.$category_hide, $event_category).'"' : '';
			$tag = $event_tag ? ' tag="'.$tag_hide.implode(', '.$tag_hide, $event_tag).'"' : '';
			$year = $event_year ? ' year="'.$event_year.'"' : '';
			$month = $event_month ? ' month="'.$event_month.'"' : '';
			$bookings = $event_bookings ? ' bookings="'.$event_bookings.'"' : '';
			$pagination = $event_pagination ? ' pagination="'.$event_pagination.'"' : '';

	  	$output = '<div class="naeep-em-location"' . $limit . $offset . $scope . $countries . $order . $category . $tag . $year . $month . $pagination . '>'.do_shortcode( '[locations_list' . $limit . $offset . $scope . $countries . $order . $category . $tag . $year . $month . $bookings . $pagination . ']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Event_Elementor_Addon_EventsManagerLocation() );
}

} // enable & disable