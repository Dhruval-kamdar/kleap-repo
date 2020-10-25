<?php
namespace ElementorExtensions\Modules\CopyrightYear;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function ee_dynamic_copyright_year_shortcode(){
        return date('Y');
    }

	protected function add_actions() {
		add_shortcode('copyright_year', [$this, 'ee_dynamic_copyright_year_shortcode']);
	}

	public function get_name() {
		return 'copyright-year';
	}

	public function get_widgets() {
		return [
			'Copyright_Year',
		];
	}
}
