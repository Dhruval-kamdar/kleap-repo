<div id="widget_settings" class="ee-mb-tabcontent">
    <form method="post" name="form_widget_settings" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
    >
        <?php wp_nonce_field('update_widget_settings','wp_widget_nounce'); ?>
        <input type="hidden" name="action" value="elementor_extensions_settings"/>

        <h2> <?php _e('Widgets','elementor-extensions'); ?> </h2>
        <p class="description">
            <?php printf( __( 'We strongly recommend disabling the widgets you don`t plan on using to improve the load time of the Frontend & Elementor editor.', 'elementor-extensions' ) ); ?>
        </p>

        <table class="form-table es-table-widget-settings">
            <tbody>
                <?php 
                    if(!empty($modules)):
                        foreach($modules as $key => $module_name):
     
                            if(!empty($module_name)):
                       
                            /*@ 
                             * By default all widgets are enabled
                             * But when user disable the widget then it will save all the widget excluding unchecked widget.
                             * Unchecked widget will be removed from the includes/modules-manager.php
                             */
                            if($module_name == 'query-control'){
                                continue;
                            }

                            $checked = "checked='checked'";
                            if( !empty($checked_widget) && !in_array($module_name, $checked_widget) ):
                                $checked = "";
                            endif;

                            $class_name = str_replace( 'ee-mb-', ' ', $module_name);
                            $class_name = str_replace( '-', ' ', $class_name );
                            $class_name = ucwords($class_name);
                        ?>
                        <tr>
                            <th scope="row"> <?php _e($class_name,'elementor-extensions'); ?> </th>
                            <td>
                                <label><input type="checkbox" name="ee_mb_hide_show_widgets[]" value="<?php echo esc_html($module_name); ?>" <?php echo $checked; ?>/> <?php _e('Enable','elementor-extensions'); ?></label>
                            </td>
                        </tr> 
                            <?php endif;
                        endforeach; 
                    endif;
                    ?>
            </tbody>
        </table>
    
        <p class="submit">
            <input type="submit" name="btn_update_widget_settings" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
        </p>
    </form>
</div>