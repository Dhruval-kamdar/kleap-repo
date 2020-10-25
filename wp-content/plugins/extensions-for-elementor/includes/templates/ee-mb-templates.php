<?php
namespace ElementorExtensions\Includes\Templates;

class EE_MB_Templates{

    private static $_instance;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){
        add_action('page_template',array($this, 'addPropertySearchPageTemplate'));
        add_filter('single_template', array($this, 'addSinglePageTemplate'), 10, 1);
    }

    public function addSinglePageTemplate($single){
        global $post;

        $checked_cpt = [];
        if(get_option('ee_mb_cpt_single')):
            $checked_cpt = get_option('ee_mb_cpt_single');
        endif;

        if(empty($checked_cpt) && !is_array($checked_cpt)):
            $checked_cpt = [];
        endif;

        /* Checks for single template by post type */
        if ( $post->post_type == 'property' && !in_array('property',$checked_cpt)):
            if ( file_exists( ELEMENTOR_EXTENSIONS_PATH . 'includes/templates/single-property.php' ) ):
                return ELEMENTOR_EXTENSIONS_PATH . 'includes/templates/single-property.php';
            endif;
        endif;

        if ($post->post_type == 'ee_mb_event_slider' && !in_array('ee_mb_event_slider',$checked_cpt)) {

            /*@ Provide a way of override template if available in theme */
            $new_template = locate_template( array( 'singe-ee_mb_event_slider.php' ) );
         
            if ( !empty($new_template)) {
                return $new_template;
            }else{
                if ( file_exists( ELEMENTOR_EXTENSIONS_PATH . 'includes/templates/single-ee_mb_event_slider.php' ) ) {
                    return ELEMENTOR_EXTENSIONS_PATH . 'includes/templates/single-ee_mb_event_slider.php';
                }
            }
        }
        
        return $single;
    }

    public function addPropertySearchPageTemplate($page_template){

        if( is_page('property-search') ):
            if(file_exists(ELEMENTOR_EXTENSIONS_PATH.'includes/templates/page-property-search.php'))
                return ELEMENTOR_EXTENSIONS_PATH. 'includes/templates/page-property-search.php';
        endif;

        return $page_template;
    }
}
