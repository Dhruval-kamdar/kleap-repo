<?php
namespace ElementorExtensions\Modules\EventSlider;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Classes\Cpt_Generator;
use ElementorExtensions\Classes\EE_MB_Event_Metabox;

class Module extends Module_Base {

	public function __construct(){
		parent::__construct();
		
		add_action('init', array($this, 'eventSliderCptRegistration'));
		add_action('init', array($this, 'eventSliderTaxonomyRegistration'));
        EE_MB_Event_Metabox::instance();
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
    

	public function get_name() {
		return 'ee-mb-event-slider';
	}

	public function get_widgets() {
		return [
			'Event_Slider',
		];
	}
}
