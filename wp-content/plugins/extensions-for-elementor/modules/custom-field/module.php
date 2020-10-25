<?php
namespace ElementorExtensions\Modules\CustomField;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'custom-field';
	}

	public function get_widgets() {
		return [
			'Custom_Field',
		];
	}
}
