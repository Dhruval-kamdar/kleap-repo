<?php
namespace ElementorExtensions\Modules\ScrollNavigation;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-scroll-navigation';
	}

	public function get_widgets() {
		return [
			'Scroll_Navigation',
		];
	}
}
