<?php
namespace ElementorExtensions\Modules\Events;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Modules\Events\Widgets\EE_Events;
use ElementorExtensions\Classes\Cpt_Generator;
use ElementorExtensions\Classes\EE_MB_Event_Metabox;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'ee-mb-events';
	}

	public function get_widgets() {
		return [
			'Events',
		];
	}

	public function getSummaryListAjax(){
		if(empty($_POST['action'])){
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$ee_events = new EE_Events();
		$ee_events->getSummaryListAjax($_POST);
		wp_die();
	}

	protected function add_actions() {
		add_action('init', array($this, 'eventSliderCptRegistration'));
		add_action('init', array($this, 'eventSliderTaxonomyRegistration'));
		EE_MB_Event_Metabox::instance();

		add_action('wp_ajax_getSummaryListAjax', [ $this, 'getSummaryListAjax' ]);
		add_action('wp_ajax_nopriv_getSummaryListAjax', [ $this, 'getSummaryListAjax' ]);

		add_action('wp_ajax_getEventListByDay', [ $this, 'getEventListByDay' ]);
		add_action('wp_ajax_nopriv_getEventListByDay', [ $this, 'getEventListByDay' ]);
	} 

	public function eventSliderCptRegistration(){
        $singular = 'Event';
        $plural = 'Events';

        $custom_args['labels'] = [
            'singular' => $singular,
            'plural' => $plural
        ];

        $custom_args['args'] = [
            'menu_icon'     => 'dashicons-slides',
            'supports'      => array('title','editor','thumbnail','excerpt','revisions','author','page-attributes'),
            'slug'          => 'ee_mb_event_slider'
        ];

        Cpt_Generator::elementorExtensionCptGeneration($custom_args);
    }

    public function eventSliderTaxonomyRegistration(){
        
        $custom_args = [
            'singular'      => 'Category',
            'plural'        => 'Categories',
            'taxonomy_name' => 'ee_mb_event_cat',
            'post_type'     => 'ee_mb_event_slider'
        ];

        Cpt_Generator::elementorExtensionTaxonomyGeneration($custom_args);

        /*@ Tag registration */
        $custom_args = [
            'singular'      => 'Tag',
            'plural'        => 'Tags',
            'taxonomy_name' => 'ee_mb_event_tag',
            'post_type'     => 'ee_mb_event_slider'
        ];

        $custom_args['args'] = [
            'hierarchical' => false,
        ];

        Cpt_Generator::elementorExtensionTaxonomyGeneration($custom_args);
    }

	/*
	 * @ Run ajax on calendar click 
	 * It will fetch the list of events and display below the calendar
	 */
	public function getEventListByDay(){
		if(empty($_POST['action']) && $_POST['action'] !== 'getEventListByDay'){
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$myeventon = new EE_Events();
		$myeventon->getEventListByDay($_POST);
		wp_die();
	}
}
