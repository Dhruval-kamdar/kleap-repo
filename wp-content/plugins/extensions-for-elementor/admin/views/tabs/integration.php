<?php
   /*@ Integration tab data store */
   $gmap_key = $snazzy_key = $snazzy_endpoint = $gcal_key = $eventbrite_token = $insta_token = $import_template_url = $template_password = '';
   if(!empty($integration)):
        $gmap_key = (!empty($integration['ee_mb_google_map_key'])) ? stripslashes_deep(esc_attr($integration['ee_mb_google_map_key'])) : '';
        $snazzy_key = (!empty($integration['ee_mb_snazzy_map_key'])) ? stripslashes_deep(esc_attr($integration['ee_mb_snazzy_map_key'])) : '';
        $snazzy_endpoint = (!empty($integration['ee_mb_snazzy_map_endpoint'])) ? stripslashes_deep(esc_attr($integration['ee_mb_snazzy_map_endpoint'])) : '';
        $gcal_key = (!empty($integration['ee_mb_google_calendar_key'])) ? stripslashes_deep(esc_attr($integration['ee_mb_google_calendar_key'])) : '';
        $eventbrite_token = (!empty($integration['ee_mb_eventbrite_auth_token'])) ? stripslashes_deep(esc_attr($integration['ee_mb_eventbrite_auth_token'])) : '';
        $insta_token = (!empty($integration['ee_mb_instagram_access_token'])) ? stripslashes_deep(esc_attr($integration['ee_mb_instagram_access_token'])) : '';
        $import_template_url = (!empty($integration['ee_mb_import_template_url'])) ? esc_url($integration['ee_mb_import_template_url']) : '';
        $template_password = (!empty($integration['ee_mb_template_password'])) ? stripslashes_deep(esc_attr($integration['ee_mb_template_password'])) : '';
   endif;
   ?>
<div id="integration" class="ee-mb-tabcontent">
   <form method="post" name="form_integration" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php wp_nonce_field('integration_settings','integration_settings_nounce'); ?>
      <input type="hidden" name="action" value="elementor_extensions_settings"/>
      <h2> <?php _e('Google Map','elementor-extensions'); ?> </h2>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('API Key','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>google_map_key]" value="<?php echo $gmap_key; ?>"/>
               </td>
            </tr>
         </tbody>
      </table>
      <hr>
      <h2> <?php _e('Snazzy Maps','elementor-extensions'); ?> </h2>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"> <?php _e('API Key','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>snazzy_map_key]" value="<?php echo esc_attr($snazzy_key); ?>"/>
                  <p class="description es-description"><?php printf( __( 'You can get your API key <a target="_blank" href="https://snazzymaps.com/account/developer">here</a> after you create an account on Snazzy Maps.', 'elementor-extensions' )); ?></p>
               </td>
            </tr>
            <tr>
               <th scope="row"><?php _e('Endpoint','elementor-extensions'); ?></th>
               <td>
                  <select class="regular" name="ee_mb_integration_setting[<?php echo $prefix; ?>snazzy_map_endpoint]">
                     <option value="explore" <?php echo ($snazzy_endpoint == 'explore') ? 'selected="selected"' : ''; ?>><?php _e('Explore','elementor-extensions'); ?></option>
                     <option value="my-styles" <?php echo ($snazzy_endpoint == 'my-styles') ? 'selected="selected"' : ''; ?>><?php _e('My Styles','elementor-extensions'); ?></option>
                     <option value="favorites" <?php echo ($snazzy_endpoint == 'favorites') ? 'selected="selected"' : ''; ?>><?php _e('Favorites','elementor-extensions'); ?></option>
                  </select>
                  <p class="description es-description"><?php _e('Select where to search for map styles. "Explore" searches all public map styles, "My Styles" search the styles you created on Snazzy Maps and "Favorites" fetches styles from the ones you added to your favorites.','elementor-extensions'); ?></p>
               </td>
            </tr>
         </tbody>
      </table>
      <hr>
      <h2> <?php _e('Google Calendar','elementor-extensions'); ?> </h2>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('API Key','elementor-extensions'); ?></th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>google_calendar_key]" value="<?php echo $gcal_key; ?>"/>
               </td>
            </tr>
         </tbody>
      </table>
      <hr>
      <h2> <?php _e('Eventbrite Calendar','elementor-extensions'); ?> </h2>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Auth Token','elementor-extensions'); ?></th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>eventbrite_auth_token]" value="<?php echo $eventbrite_token; ?>">
                  <p class="description">
                     <?php printf( __( '<a href="https://www.eventbrite.com/myaccount/apps/" target="_blank">Get EventBrite Auth Token</a> ( Your personal OAuth token )', 'elementor-extensions' )); ?>
                  </p>
               </td>
            </tr>
         </tbody>
      </table>
      <hr>
      <h2> <?php _e('Instagram','elementor-extensions'); ?> </h2>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Access Token','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>instagram_access_token]" value="<?php echo $insta_token; ?>"/>
                  <p class="description">
                     <a href="https://www.instagram.com/developer/clients/manage/" target="_blank"><?php _e('Step 1: Register new insta app client.','elementor-extensions'); ?></a><br/>
                     <a href="https://elfsight.com/service/generate-instagram-access-token/" target="_blank"><?php _e('Step 2: Get you access token by just entering client id and client secret.','elementor-extensions'); ?></a><br/>
                     <?php _e('Step 3: Copy paste retrieved access token to above. ','elementor-extensions'); ?>
                  </p>
               </td>
            </tr>
         </tbody>
      </table>
      <hr>
      <h2> <?php _e('Import Templates','elementor-extensions'); ?> </h2>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Master Site URL','elementor-extensions'); ?></th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>import_template_url]" value="<?php echo $import_template_url; ?>" placeholder="For Ex. https://www.example.com"/>
                  <p class="description">
                     <?php 
                        printf( __( 'Enter the site URL from where you want to import the templates For Ex. https://www.example.com', 'elementor-extensions' ));
                        echo '<br/>';
                        printf( __( 'URL not required to add on master site.', 'elementor-extensions' ));
                      ?>
                  </p>
               </td>
            </tr>

            <tr>
               <th scope="row"><?php _e('Password','elementor-extensions'); ?></th>
               <td>
                  <input type="text" name="ee_mb_integration_setting[<?php echo $prefix; ?>template_password]" value="<?php echo $template_password; ?>" placeholder="For Ex. Ajkj345_JKL@#$455sdfJKJ777"/>
                  <p class="description">
                     <?php printf( __( 'Add password to secure your API. Password should be same in both the site.', 'elementor-extensions' )); ?>
                  </p>
               </td>
            </tr>
         </tbody>
      </table>
      <p class="submit">
         <input type="submit" name="btn_update_integration" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
      </p>
   </form>
</div>