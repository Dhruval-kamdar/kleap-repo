<?php
namespace ElementorExtensions\Modules\Flipbox;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-flipbox';
	}

	public function get_widgets() {
		return [
			'Flipbox',
		];
	}
}
