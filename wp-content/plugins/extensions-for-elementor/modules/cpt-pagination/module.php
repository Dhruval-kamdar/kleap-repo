<?php
namespace ElementorExtensions\Modules\CptPagination;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'cpt-pagination';
	}

	public function get_widgets() {
		return [
			'Cpt_Pagination',
		];
	}
}
