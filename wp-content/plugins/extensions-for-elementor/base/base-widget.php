<?php
namespace ElementorExtensions\Base;

use Elementor\Widget_Base;
use ElementorExtensions\Admin\EE_MB_Setting_Common;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base_Widget extends Widget_Base {
	public $widget_name_prefix = 'ee-mb-';

	public function get_categories() {
		return [ 'elementor-extensions' ];
	}
}
