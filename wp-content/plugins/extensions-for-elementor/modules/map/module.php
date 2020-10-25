<?php
namespace ElementorExtensions\Modules\Map;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-map';
	}

	public function get_widgets() {
		return [
			'Map',
		];
	}
}
