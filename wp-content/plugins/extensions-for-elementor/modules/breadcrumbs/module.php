<?php
namespace ElementorExtensions\Modules\Breadcrumbs;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'breadcrumbs';
	}

	public function get_widgets() {
		return [
			'Breadcrumbs',
		];
	}
}
