<?php 
namespace ElementorExtensions\Includes;

if ( ! defined( 'ABSPATH' ) ) exit;

class EE_MB_Controls_Manager{

    private static $_instance;

    public function __construct() {
        add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );
    }

    public function add_controls( $controls_manager ) {
        
        $grouped = array(
            'ee-mb-box-style' => 'EE_MB_Group_Control_Box_Style',
            'ee-mb-transition' => 'EE_MB_Group_Control_Transition'
        );

        foreach ( $grouped as $control_id => $class_name ) {    
            if ( $this->include_control( $class_name, true ) ) {
                $class_name = 'ElementorExtensions\Controls\\'.$class_name;
                $controls_manager->add_group_control( $control_id, new $class_name() );
            }
        }

        $single_control = array(
            'ee-mb-snazzy' => 'EE_MB_Snazzy_Control',
        );

        foreach ( $single_control as $control_id => $class_name ) {  
            if ( $this->include_control( $class_name, true ) ) {
                $class_name = 'ElementorExtensions\Controls\\'.$class_name;
                $controls_manager->register_control( $control_id, new $class_name() );
            }
        }

    }

    public function include_control( $class_name, $grouped = false ) {

        $create_filename = str_replace('_','-',strtolower($class_name));
        $filename = 'controls/'.$create_filename.'.php';

        if ( ! file_exists( ELEMENTOR_EXTENSIONS_PATH.$filename )) {
            return false;
        }

        require ELEMENTOR_EXTENSIONS_PATH.$filename;

        return true;
    }
    
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}
