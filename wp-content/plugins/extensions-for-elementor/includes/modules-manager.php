<?php
namespace ElementorExtensions\Includes;

if ( ! defined( 'ABSPATH' ) ) exit;

final class Modules_Manager {

	public $_modules = null;

	private function is_module_active( $module_id ) {
		$module_data = $this->get_module_data( $module_id );
		if ( $module_data['required'] ) {
			return true;
		}

		$options = get_option( 'elementor_extensions_active_modules', [] );
		if ( ! isset( $options[ $module_id ] ) ) {
			return $module_data['default_activation'];
		}

		return 'true' === $options[ $module_id ];
	}

	private function get_module_data( $module_id ) {
		return isset( $this->_modules[ $module_id ] ) ? $this->_modules[ $module_id ] : false;
	}

	public function get_modules() {
		
		$modules = [
			'anchor-scroll',
			'atoz-listing',
			'breadcrumbs',
			'button',
			'cpt-pagination',
			'copyright-year',
			'custom-field',
			'document-library',
			'events',
			'event-slider',
			'flipbox',
			'google-map',
			'google-calendar',
			'import-templates',
			'map',
			'member',
			'nav-menu',
			'imagebox-repeater',
			'properties',
			'property-search',
			'table',
			'testimonial-swiper',
			'scroll-navigation'
		];

		return $modules;
	}

	public function __construct() {

		$modules = $this->get_modules();

		$checked_widget = get_option('ee_mb_hide_show_widgets');

		foreach ( $modules as $module_name ):
			if( !empty($module_name) ):
				$class_name = str_replace( '-', ' ', $module_name );
				$class_name = str_replace( ' ', '', ucwords( $class_name ) );
				$class_name =  'ElementorExtensions\\Modules\\' . $class_name . '\Module';

				/*
				 * Disable widget from setting page
				 */
				if( $module_name !== 'ee-mb-query-control' && is_array($checked_widget) && !empty($checked_widget) && !in_array($module_name,$checked_widget) ):
					continue;
				endif;

				/** @var Module_Base $class_name */
				if($module_name !== 'nav-menu'):
					$module_name = 'ee-mb-'.$module_name;
				endif;

				$this->_modules[ $module_name ] = $class_name::instance();
			endif;
		endforeach;
	}
}
