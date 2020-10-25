<?php
namespace ElementorExtensions\Modules\Member;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Classes\Cpt_Generator;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action('init', array($this, 'memberCptRegistration'));
		add_action('init', array($this, 'memberTaxonomyRegistration'));
	}

	public function memberCptRegistration(){
        $singular = 'Member';
        $plural = 'Members';

        $custom_args['labels'] = [
            'singular' => $singular,
            'plural' => $plural
        ];

        $custom_args['args'] = [
            'menu_icon'     => 'dashicons-groups',
            'supports'      => array('title','editor','thumbnail','excerpt','revisions','author','page-attributes'),
            'slug'          => 'ee_mb_member'
        ];

        Cpt_Generator::elementorExtensionCptGeneration($custom_args);
    }

    public function memberTaxonomyRegistration(){

		$this->industry_singular = 'Industrial Sector';
		$this->industry_plural = 'Industrial Sectors';
        
        $status_args = [
            'singular'      => 'Status',
            'plural'        => 'Status',
            'taxonomy_name' => 'ee_mb_member_status',
            'post_type'     => 'ee_mb_member'
        ];

        Cpt_Generator::elementorExtensionTaxonomyGeneration($status_args);


        $industrial_args = [
            'singular'      => 'Industrial Sector',
            'plural'        => 'Industrial Sectors',
            'taxonomy_name' => 'ee_mb_member_industrial_sector',
            'post_type'     => 'ee_mb_member'
        ];

        Cpt_Generator::elementorExtensionTaxonomyGeneration($industrial_args);
    }

	public function get_name() {
		return 'ee-mb-member';
	}

	public function get_widgets() {
		return [
			'Member',
		];
	}
} 
