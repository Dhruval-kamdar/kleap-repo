<?php
namespace ElementorExtensions\Modules\Properties;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Modules\Properties\Widgets\EE_Properties;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	protected function add_actions() {

		add_action('wp_ajax_eeMbPropertySearchAjax', [ $this, 'eeMbPropertySearchAjax' ]);
		add_action('wp_ajax_nopriv_eeMbPropertySearchAjax', [ $this, 'eeMbPropertySearchAjax' ]);

		add_action('wp_ajax_eeMbAgentMailSendAjax', [ $this, 'eeMbAgentMailSendAjax' ]);
		add_action('wp_ajax_nopriv_eeMbAgentMailSendAjax', [ $this, 'eeMbAgentMailSendAjax' ]);
	}

	public function eeMbPropertySearchAjax(){

		if(empty($_POST['action'])){
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$property_search = new EE_Properties();
		$property_search->getPropertyCpt($_POST);
		wp_die();
	}

	public function eeMbAgentMailSendAjax(){
		if(empty($_POST['action'])){
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$property_search = new EE_Properties();
		$property_search->sendAgentMail($_POST);
		wp_die();
	}

	public function get_name() {
		return 'ee-mb-properties';
	}

	public function get_widgets() {
		return [
			'Properties',
		];
	}
} 
 