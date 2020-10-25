<?php
namespace ElementorExtensions\Modules\AnchorScroll;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'anchor-scroll';
	}

	public function get_widgets() {
		return [
			'Anchor_Scroll',
		];
	}
}
