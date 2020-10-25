<?php

    /**
    * Plugin Compatibility      :   Autoptimize
    * Introduced at version     :   2.7.4
    */


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_autoptimize
        {
                        
            var $wph;
                           
            function __construct()
                {
                    if( !   $this->is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter( 'autoptimize_css_after_minify',                 array( $this, 'autoptimize_css_after_minify' ), 999);
                    add_filter( 'autoptimize_filter_css_single_after_minify',   array( $this, 'autoptimize_css_after_minify' ), 999);
                    
                    add_filter( 'autoptimize_js_after_minify',                  array( $this, 'autoptimize_js_after_minify' ),  999);
                    add_filter( 'autoptimize_filter_js_single_after_minify',    array( $this, 'autoptimize_js_after_minify' ),  999);
                    
                    //ignore the files which where cached through the Cache plugin, as they where already processed through the filer wpfc_buffer_callback_filter
                    add_filter( 'wp-hide/module/general_js_combine/ignore_file' ,   array ( $this, '__general__combine_ignore_file' ), 99, 2 );
                    add_filter( 'wp-hide/module/general_css_combine/ignore_file' ,  array ( $this, '__general__combine_ignore_file' ), 99, 2 );
                    
                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'autoptimize/autoptimize.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            function autoptimize_css_after_minify( $buffer )
                {   
                    $WPH_module_general_css_combine =   new WPH_module_general_css_combine();
                                            
                    $option__css_combine_code    =   $this->wph->functions->get_site_module_saved_value('css_combine_code',  $this->wph->functions->get_blog_id_setting_to_use());
                    if ( in_array( $option__css_combine_code,   array( 'yes', 'in-place' ) ) )
                        $buffer =   $WPH_module_general_css_combine->css_recipient_process( $buffer );
                        else
                        $buffer =   $WPH_module_general_css_combine->_process_url_replacements( $buffer );  
                    
                    return $buffer;  
                                 
                }
                      
            function autoptimize_js_after_minify( $buffer )
                {   
                    $WPH_module_general_js_combine =   new WPH_module_general_js_combine();
                                            
                    $option__js_combine_code    =   $this->wph->functions->get_site_module_saved_value('js_combine_code',  $this->wph->functions->get_blog_id_setting_to_use());
                    if ( in_array( $option__js_combine_code,   array( 'yes', 'in-place' ) ) )
                        $buffer =   $WPH_module_general_js_combine->js_recipient_process( $buffer );
                        else
                        $buffer =   $WPH_module_general_js_combine->_process_url_replacements( $buffer );
                     
                    return $buffer;
                                 
                }

                
            function __general__combine_ignore_file( $ignore, $file_src )
                {
                    
                    if ( stripos( $file_src, '/cache/autoptimize/' ) )
                        $ignore =   TRUE;    
                    
                    return $ignore;   
                }
                            
        }


    new WPH_conflict_handle_autoptimize();
        
?>