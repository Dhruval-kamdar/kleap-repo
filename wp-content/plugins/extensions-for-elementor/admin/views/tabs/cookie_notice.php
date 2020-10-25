<?php 

$message = $button_text = $box_color = $font_color = $close_btn_color = $overlay = $vertical_position = $horizontal_position =  $enable = '';
if(!empty($cookie)):

    /*@ Sanitizing fields */
    $message = (!empty($cookie['message'])) ? html_entity_decode($cookie['message']) : '';
    $box_color = (!empty($cookie['box_color'])) ? esc_html($cookie['box_color']) : '';
    $font_color = (!empty($cookie['font_color'])) ? esc_html($cookie['font_color']) : '';
    $close_btn_color = (!empty($cookie['close_btn_color'])) ? esc_html($cookie['close_btn_color']) : '';

    $overlay = (!empty($cookie['overlay'])) ? esc_html($cookie['overlay']) : '';
    $enable = (!empty($cookie['enable'])) ? esc_html($cookie['enable']) : '';
    $vertical_position = (!empty($cookie['vertical_position'])) ? esc_html($cookie['vertical_position']) : '';
    $horizontal_position = (!empty($cookie['horizontal_position'])) ? esc_html($cookie['horizontal_position']) : '';
endif;
?>
<div id="cookie_notice" class="ee-mb-tabcontent">

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php 
        wp_nonce_field('elementor_extensions_cookie_notice','elementor_extensions_cookie_notice_nounce'); 
        ?>
        <input type="hidden" name="action" value="elementor_extensions_settings"/>

        <h2> <?php _e('Cookie Setting','elementor-extensions'); ?> </h2>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"> <?php _e('Enable cookie','elementor-extensions'); ?> </th>
                    <td>
                        <label><input type="checkbox" name="cookie[enable]" value="yes" class="chk_cookie_enable" <?php checked( $enable, 'yes', true ); ?>/></label>
                        <p class="description es-description"><?php printf( __( 'Check this option to show cookie consent bar on frontend.', 'elementor-extensions' )); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="cookie_options" style="<?php echo ($enable !== 'yes') ? 'display:none;' : ''; ?>">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e('Message','elementor-extensions'); ?> </th>
                        <td>
                            <?php 
                            
                            $settings = array(
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                                'textarea_name' => 'cookie[message]'
                            );

                            $content = $message;
                            $editor_id = 'es_cookie_message';

                            wp_editor( $content, $editor_id, $settings );
                            ?>
                            <p class="description es-description"><?php printf( __( 'Enter the cookie notice message.', 'elementor-extensions' )); ?></p>                     
                        </td>
                    </tr>

                    <?php /*<tr>
                        <th scope="row"><?php _e('Button Text','elementor-extensions'); ?> </th>
                        <td>
                            <input type="text" name="cookie[button_text]" value="<?php echo $button_text; ?>"/>
                            <p class="description es-description"><?php printf( __( 'The text of the option to accept the usage of the cookies and make the notification disappear.', 'elementor-extensions' )); ?></p>                     
                        </td>
                    </tr> */ ?>
                </tbody>
            </table>

            <hr/>

            <h2> <?php _e('Design','elementor-extensions'); ?> </h2>
            <table class="form-table">
                <tbody>

                    <tr>
                        <th scope="row"> <?php _e('Cookie overlay','elementor-extensions'); ?> </th>
                        <td>
                            <label><input type="checkbox" name="cookie[overlay]" value="yes" class="chk_cookie_overlay" <?php checked( $overlay, 'yes', true ); ?>/></label>
                            <p class="description es-description"><?php printf( __( 'By default cookie push the content down. By checking you can make it above the page and can set the position.', 'elementor-extensions' )); ?></p>
                        </td>
                    </tr>

                    <tr class="overlay_options" style="<?php echo ($overlay !== 'yes') ? 'display:none;' : ''; ?>">
                        <th scope="row"><?php _e('Position', 'elementor-extensions'); ?></th>
                        <td>
                            <p class="description es-description"><b><?php printf( __( 'Vertical Position', 'elementor-extensions' )); ?></b></p>
                            <label><input type="radio" name="cookie[vertical_position]" value="top" <?php checked( $vertical_position, 'top', true ); checked( $vertical_position, '', true ); ?>/><?php _e('Top','elementor-extensions'); ?></label>
                            <label><input type="radio" name="cookie[vertical_position]" value="bottom" <?php checked( $vertical_position, 'bottom', true ); ?>/><?php _e('Bottom','elementor-extensions'); ?></label>
                            
                            <p>&nbsp;</p>

                            <p class="description es-description"><b><?php printf( __( 'Horizontal Position', 'elementor-extensions' )); ?></b></p>
                            <label><input type="radio" name="cookie[horizontal_position]" value="left" <?php checked( $horizontal_position, 'left', true ); ?>/><?php _e('Left','elementor-extensions'); ?></label>
                            <label><input type="radio" name="cookie[horizontal_position]" value="right" <?php checked( $horizontal_position, 'right', true ); ?>/><?php _e('Right','elementor-extensions'); ?></label>
                            <label><input type="radio" name="cookie[horizontal_position]" value="none" <?php checked( $horizontal_position, 'none', true ); checked( $horizontal_position, '', true ); ?>/><?php _e('None','elementor-extensions'); ?></label>
                        </td>
                    </tr> 

                    <tr>
                        <th scope="row"><?php _e('Box Background','elementor-extensions'); ?> </th>
                        <td>
                            <input type="text" value="<?php echo $box_color; ?>" class="es_color_field" name="cookie[box_color]"/>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e('Font Color','elementor-extensions'); ?> </th>
                        <td>
                            <input type="text" value="<?php echo $font_color; ?>" class="es_color_field" name="cookie[font_color]"/>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e('Close Button Color','elementor-extensions'); ?> </th>
                        <td>
                            <input type="text" value="<?php echo $close_btn_color; ?>" class="es_color_field" name="cookie[close_btn_color]"/>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

        <p class="submit">
            <input type="submit" name="btn_update_cookie_settings" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
        </p>
    </form>
</div>