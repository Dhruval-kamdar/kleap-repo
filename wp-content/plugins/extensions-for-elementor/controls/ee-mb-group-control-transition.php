<?php
namespace ElementorExtensions\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

class EE_MB_Group_Control_Transition extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'ee-mb-transition';
	}

	public static function get_easings() {
		return [
			'linear' 		=> __( 'Linear', 'elementor-extensions' ),
			'ease-in' 		=> __( 'Ease In', 'elementor-extensions' ),
			'ease-out' 		=> __( 'Ease Out', 'elementor-extensions' ),
			'ease-in-out' 	=> __( 'Ease In Out', 'elementor-extensions' ),
		];
	}

	protected function init_fields() {
		$controls = [];

		$controls['property'] = [
			'label'			=> _x( 'Property', 'Transition Control', 'elementor-extensions' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'all',
			'options'		=> [
				'all'		=> __( 'All', 'elementor-extensions' ),
			],
			'selectors' => [
				'{{SELECTOR}}' => 'transition-property: {{VALUE}}',
			],
		];

		$controls['easing'] = [
			'label'			=> _x( 'Easing', 'Transition Control', 'elementor-extensions' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'linear',
			'options'		=> self::get_easings(),
			'selectors' => [
				'{{SELECTOR}}' => 'transition-timing-function: {{VALUE}}',
			],
		];

		$controls['duration'] = [
			'label'			=> _x( 'Duration', 'Transition Control', 'elementor-extensions' ),
			'type' 			=> Controls_Manager::NUMBER,
			'default' 		=> 0.3,
			'min' 			=> 0.05,
			'max' 			=> 2,
			'step' 			=> 0.05,
			'selectors' 	=> [
				'{{SELECTOR}}' => 'transition-duration: {{VALUE}}s;',
			],
			'separator' 	=> 'after',
		];

		return $controls;
	} 

	protected function prepare_fields( $fields ) {

		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'transition', 'popover_toggle' ] ) ) {
					return;
				}

				$field['condition']['transition'] = 'custom';
			}
		);

		return parent::prepare_fields( $fields );
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' 	=> 'transition',
				'starter_title' => _x( 'Transition', 'Transition Control', 'elementor-extensions' ),
			],
		];
	}
}
