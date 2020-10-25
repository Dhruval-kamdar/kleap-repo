<?php
namespace ElementorExtensions\Modules\AtozListing;

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Modules\AtozListing\Widgets\EE_Atoz_Listing;


if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	protected function add_actions() {
		add_action('wp_ajax_getPostsByCategory', [ $this, 'getPostsByCategory' ]);
		add_action('wp_ajax_nopriv_getPostsByCategory', [ $this, 'getPostsByCategory' ]);
	}

	public function get_name() {
		return 'atoz-listing';
	}

	public function getPostsByCategory(){
		if(empty($_POST['action'])){
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}
		
		$atoz = new EE_Atoz_Listing();
		$atoz->getPostCategoriesById($_POST);
		wp_die();
	}

	public function get_widgets() {
		return [
			'Atoz_Listing',
		];
	}
}
