<?php
namespace ElementorExtensions\Modules\Button;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'button';
	}

	public function get_widgets() {
		return [
			'Button',
		];
	}
}
