<?php
namespace ElementorExtensions\Modules\GoogleMap;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-google-map';
	}

	public function get_widgets() {
		return [
			'Google_Map',
		];
	}
}
