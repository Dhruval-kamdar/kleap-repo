<?php
namespace ElementorExtensions\Modules\GoogleCalendar;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'ee-google-calendar';
	}

	public function get_widgets() {
		return [
			'Google_Calendar',
		];
	}
}
