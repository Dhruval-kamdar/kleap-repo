<?php
namespace ElementorExtensions\Modules\DocumentLibrary;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'document-library';
	}

	public function get_widgets() {
		return [
			'Document_Library',
		];
	}
}
