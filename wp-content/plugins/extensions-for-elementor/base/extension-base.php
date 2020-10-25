<?php
namespace ElementorExtensions\Base;

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use Elementor\Plugin;

class Extension_Base {

	protected $is_common = false;
	private $depended_scripts = [];
	private $depended_styles = [];

	public final function __construct() {
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );	
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		if ( $this->is_common ) {
			$this->add_common_sections_actions();
		}

		$this->add_actions();
	}

	public function add_script_depends( $handler ) {
		$this->depended_scripts[] = $handler;
	}

	public function add_style_depends( $handler ) {
		$this->depended_styles[] = $handler;
	}

	public function get_script_depends() {
		return $this->depended_scripts;
	}

	final public function enqueue_scripts() {
		foreach ( $this->get_script_depends() as $script ) {
			wp_enqueue_script( $script );
		}
	}

	final public function get_style_depends() {
		return $this->depended_styles;
	}

	public static function get_description() {}

	final public function enqueue_styles() {
		foreach ( $this->get_style_depends() as $style ) {
			wp_enqueue_style( $style );
		}
	}

	protected final function add_common_sections( $element, $args ) {

		$section_name = 'section_elementor_extensions_advanced';
		$section_exists = Plugin::instance()->controls_manager->get_control_from_stack( $element->get_unique_name(), $section_name );

		if ( ! is_wp_error( $section_exists ) ) {
			return false;
		}

		$element->start_controls_section(
			$section_name,
			[
				'tab' 	=> Controls_Manager::TAB_ADVANCED,
				'label' => __( 'Elementor Extensions', 'elementor-extensions' ),
			]
		);	

		$element->end_controls_section();
	}

	protected function add_common_sections_actions() {}

	protected function add_actions() {}

	protected function remove_controls( $element, $controls = null ) {
		if ( empty( $controls ) )
			return;

		if ( is_array( $controls ) ) {
			$control_id = $controls;

			foreach( $controls as $control_id ) {
				$element->remove_control( $control_id );
			}
		} else {
			$element->remove_control( $controls );
		}
	}

	public static function requires_elementor_pro() {
		return false;
	}

	public static function is_default_disabled() {
		return false;
	}
}
