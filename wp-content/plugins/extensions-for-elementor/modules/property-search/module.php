<?php
namespace ElementorExtensions\Modules\PropertySearch;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Modules\PropertySearch\Widgets\EE_Property_Search;
use ElementorExtensions\Classes\Cpt_Generator;
use ElementorExtensions\Classes\EE_MB_Event_Metabox;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

    public function eeMbAddPropertyMenu(){
        add_menu_page(
            __( 'Properties', 'elementor-extensions' ),
            'Property',
            'manage_options',
            'elementor-extensions-property-management',
            '', // Callback, leave empty
            'dashicons-admin-home',
            40
        );
    } 

    public function ee_mb_setting_view(){
    	require_once(ELEMENTOR_EXTENSIONS_PATH . 'admin/views/ee-mb-option-page.php');
    }

	public function eeMbPropertyCptRegistration(){
        $singular = 'Property';
        $plural = 'Properties';

        $custom_args['labels'] = [
            'singular' => $singular,
            'plural' => $plural
        ];

        $custom_args['args'] = [
            'menu_icon'      => 'dashicons-admin-home',
            'show_in_menu'   => 'elementor-extensions-property-management',
            'supports'       => array('title')
        ];

        Cpt_Generator::elementorExtensionCptGeneration($custom_args);
    }

    public function eeMbAgentCptRegistration(){
        $singular = 'Agent';
        $plural = 'Agents';

        $custom_args['labels'] = [
            'singular' => $singular,
            'plural' => $plural
        ];

        $custom_args['args'] = [
            'menu_icon'      => 'dashicons-buddicons-buddypress-logo',
            'show_in_menu'   => 'elementor-extensions-property-management',
            'supports'       => array('title')
        ];

        Cpt_Generator::elementorExtensionCptGeneration($custom_args);
    }

	protected function add_actions() {

		add_action('admin_menu',array($this, 'eeMbAddPropertyMenu'));
		add_action('init', array($this, 'eeMbPropertyCptRegistration'));
		add_action('init', array($this, 'eeMbAgentCptRegistration'));
	}

	public function get_name() {
		return 'ee-mb-property-search';
	}

	public function get_widgets() {
		return [
			'Property_Search',
		];
	}
} 
 