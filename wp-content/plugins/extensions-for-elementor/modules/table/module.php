<?php
namespace ElementorExtensions\Modules\Table;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-table';
	}

	public function get_widgets() {
		return [
			'Table',
		];
	}
}
