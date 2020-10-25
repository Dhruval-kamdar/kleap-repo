<?php
use ElementorExtensions\Classes\Utils;

    $ee_mb_post_types = Utils::get_post_types();
    $exclude_post_types = [ 'post', 'page', 'product', 'agent', 'ee_mb_member' ];
?>
<div id="section_settings" class="ee-mb-tabcontent">
    <form method="post" name="form_section_settings" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field('update_section_settings','wp_section_nounce'); ?>
        <input type="hidden" name="action" value="elementor_extensions_settings"/>
        
        <h2> <?php _e('Single Page','elementor-extensions'); ?> </h2>
        <p class="description">
            <?php printf( __( "Disabled plugin single page template if you want to design your own template using Elementor Pro", 'elementor-extensions' ) ); ?>
        </p>

        <table class="form-table es-table-section-settings">
            <tbody>
                <?php 
                    $checked_cpt = get_option('ee_mb_cpt_single');
                    foreach($ee_mb_post_types as $key => $single_cpt):

                        if(in_array($key, $exclude_post_types)):
                            continue;
                        endif;

                        $value = $key;
                    
                        $checked = "";
                        if(!empty($checked_cpt) && in_array($value, $checked_cpt)):
                            $checked = "checked='checked'";
                        endif;
                ?>
                <tr>
                    <th scope="row"> <?php _e($single_cpt,'elementor-extensions'); ?>: </th>
                    <td>
                        <label><input type="checkbox" name="ee_mb_cpt_single[]" value="<?php echo esc_html($value); ?>" <?php echo $checked; ?>/> <?php _e('Disable','elementor-extensions'); ?></label>
                    </td>
                </tr> 
                <?php endforeach; ?>
            </tbody>
        </table>
    
        <p class="submit">
            <input type="submit" name="btn_update_section_settings" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
        </p>
    </form>
</div>