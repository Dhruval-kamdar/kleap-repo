<?php
namespace ElementorExtensions\Modules\NavMenu;

if ( ! defined( 'ABSPATH' ) ) exit; 

use ElementorExtensions\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}
	
	protected function add_actions() {

		add_filter( 'walker_nav_menu_start_el', [$this, 'ee_mb_nav_menu_megamenu_shortcode' ], 10, 4 );

		add_action( 'elementor/element/section/section_effects/before_section_end', function( $element, $args ) {
		    $element->add_responsive_control(
		        'ee_mb_shrink_header',
		        [
		            'label' => __( 'Shrink Header', 'elementor-siteset' ),
		            'type' => Controls_Manager::SWITCHER,
		            'separator' => 'before',
		            'selectors' => [
		                '{{WRAPPER}}' => 'transition: background-color 1s ease;'
		            ],
		            'condition' => [
		                'sticky!' => ''
		            ]
		        ]
		    );
		    $element->add_responsive_control(
		        'ee_mb_shrink_header_height',
		        [
		            'label' => __( 'Height', 'elementor-siteset' ),
		            'type' => Controls_Manager::SLIDER,
		            'default' => [
		                'size' => 70,
		            ],
		            'range' => [
		                'px' => [
		                    'min' => 0,
		                    'max' => 500,
		                ],
		            ],
		            'size_units' => [ 'px'],
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'sticky!' => ''
		            ],
		            'selectors' => [
		                '{{WRAPPER}}.elementor-sticky--effects > .elementor-container' => 'min-height: transition: all 0.4s ease-in-out;-webkit-transition:all 0.4s ease-in-out; -moz-transition:all 0.4s ease-in-out;',
		                '{{WRAPPER}} > .elementor-container' => 'min-height: {{SIZE}}{{UNIT}};',
		                '{{WRAPPER}}.elementor-sticky--effects > .elementor-container' => 'min-height: 0px;'
		            ],
		        ]
		    );
		    $element->add_responsive_control(
		        'ee_mb_shrink_header_background_color',
		        [
		            'label' => __( 'Background Color', 'elementor-siteset' ),
		            'type' => Controls_Manager::COLOR,
		            'selectors' => [
		                '{{WRAPPER}}.elementor-sticky--effects' => 'background-color: {{VALUE}}!important;',
		            ],
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'sticky!' => ''
		            ]
		        ]
		    );
		    $element->add_responsive_control(
		        'ee_mb_header_over_content',
		        [
		            'label' => __( 'Layer Header Over Content', 'elementor-siteset' ),
		            'type' => Controls_Manager::SWITCHER,
		            'default' => '',
		            'separator' => 'before',
		            'selectors' => [
		                '{{WRAPPER}}' => 'position: absolute;width: 100%;display: block;z-index:9999;'
		            ]
		        ]
		    );
		    $element->add_control(
		        'ee_mb_shrink_header_logo',
		        [
		            'label' => __( 'Logo', 'elementor-siteset' ),
		            'type' => Controls_Manager::SWITCHER,				
		            'return_value' => 'yes',
		            'separator' => 'before',
		            'description' => __( 'Choose logo height after scrolling', 'elementor-siteset' ),
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'sticky!' => ''
		            ],
		        ]
		    );
		    $element->add_responsive_control(
		        'ee_mb_shrink_header_logo_height',
		        [
		            'label' => __( 'Height', 'elementor-siteset' ),
		            'type' => Controls_Manager::SLIDER,
		            'default' => [
		                'size' => '',
		            ],
		            'range' => [
		                'px' => [
		                    'min' => 0,
		                    'max' => 500,
		                ],
		            ],
		            'size_units' => [ 'px'],
		            'selectors' => [
		                '{{WRAPPER}} > .elementor-container img' => 'transition: all 0.4s ease-in-out;-webkit-transition:all 0.4s ease-in-out; -moz-transition:all 0.4s ease-in-out;',
		                '{{WRAPPER}}.elementor-sticky--effects > .elementor-container img' => 'padding:5px 0;height:{{SIZE}}{{UNIT}}!important;width:auto!important; transition: all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out; -moz-transition:all 0.3s ease-in-out;'
		            ],
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'ee_mb_shrink_header_logo' => 'yes',
		                'sticky!' => ''
		            ]		
		        ]
		    );
		    $element->add_control(
		        'ee_mb_shrink_bottom_border',
		        [
		            'label' => __( 'Bottom Border', 'elementor-siteset' ),
		            'type' => Controls_Manager::SWITCHER,
		            'separator' => 'before',
		            'description' => __( 'Choose bottom border size and color', 'elementor-siteset' ),
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'sticky!' => ''
		            ],
		            'selectors' => [
		                '{{WRAPPER}}.elementor-sticky--effects' => 'border-bottom-style: solid;',
		            ]	
		        ]
		    );
		            
		    $element->add_control(
		        'ee_mb_shrink_bottom_border_color',
		        [
		            'label' => __( 'Color', 'elementor-siteset' ),
		            'type' => Controls_Manager::COLOR,
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'ee_mb_shrink_bottom_border' => 'yes',
		                'sticky!' => ''
		            ],
		            'selectors' => [
		                '{{WRAPPER}}.elementor-sticky--effects' => 'border-bottom-color: {{VALUE}};',
		            ]				
		        ]
		    );
		            
		    $element->add_responsive_control(
		        'ee_mb_shrink_bottom_border_size',
		        [
		            'label' => __( 'Size', 'elementor-siteset' ),
		            'type' => Controls_Manager::SLIDER,
		            'default' => [
		                'size' => 0,
		            ],
		            'range' => [
		                'px' => [ 
		                    'min' => 0,
		                    'max' => 100,
		                ],
		            ],
		            'separator' => 'after',
		            'size_units' => ['px'],
		            'condition' => [
		                'ee_mb_shrink_bottom_border' => 'yes',
		                'ee_mb_shrink_header' => 'yes',
		                'sticky!' => ''
		            ],
		            'selectors' => [
		                '{{WRAPPER}}.elementor-sticky--effects' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
		            ]
		        ]
		    );
		    $element->add_group_control(
		        Group_Control_Box_Shadow::get_type(),
		        [
		            'name' => 'ee_mb_shrink_header_shadow',
		            'label' => __( 'Box Shadow', 'elementor-siteset' ),            
		            'selector' => '{{WRAPPER}}.elementor-sticky--effects',
		            'condition' => [
		                'ee_mb_shrink_header' => 'yes',
		                'sticky!' => ''
		            ]
		        ]
		    );
		    
		}, 10, 2 );
	}
	
	public function ee_mb_nav_menu_megamenu_shortcode( $item_output, $item, $depth, $args ) {
    
	    $desc = '';
	    if( ! $depth && $item->description ):
	        $desc = do_shortcode($item->description);
			$item_output = str_replace( '</a>', '</a> <div class="ee-mb-nav-shortcode">' . $desc . '</div>', $item_output );
		endif;
			
		return $item_output;
	}

	public function get_widgets() {
		return [
			'Nav_Menu',
		];
	}

	public function get_name() {
		return 'nav-menu';
	}
}
