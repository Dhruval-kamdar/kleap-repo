<?php


    /**
    * Compatibility for Plugin Name: Asset CleanUp Pro: Page Speed Booster
    * Compatibility checked on Version:  1.1.7.6
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_wpacu
        {
            var $wph;
                            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter( 'wpacu_html_source_after_optimization',             array( $this,   'process_buffer'), 999 );       
                    
                    //ignore the files which where cached through the Cache plugin, as they where already processed
                    add_filter( 'wp-hide/module/general_js_combine/ignore_file' ,   array ( $this, '__general__combine_ignore_file' ), 99, 2 );
                    add_filter( 'wp-hide/module/general_css_combine/ignore_file' ,  array ( $this, '__general__combine_ignore_file' ), 99, 2 );

                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'wp-asset-clean-up-pro/wpacu.php' ) || is_plugin_active( 'wp-asset-clean-up/wpacu.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            function process_buffer( $buffer )
                {
                         
                    //do replacements for this url
                    $buffer =   $this->wph->proces_html_buffer( $buffer );
                                           
                    return $buffer;
                    
                }
                
        
        
                
            function __general__combine_ignore_file( $ignore, $file_src )
                {
                    
                    if ( stripos( $file_src, '/cache/asset-cleanup/' ) )
                        $ignore =   TRUE;    
                    
                    return $ignore;   
                }
     
                            
        }


        new WPH_conflict_handle_wpacu();
        
?>