<?php
namespace ElementorExtensions\Modules\ImageboxRepeater;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-imagebox-repeater';
	}

	public function get_widgets() {
		return [
			'Imagebox_Repeater',
		];
	}
}
