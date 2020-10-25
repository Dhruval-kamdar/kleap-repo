<?php

   $currency_symbol = $country_restriction = $sold_stc_label = '';
   if(!empty($general)):
      $currency_symbol = (!empty($ee_mb_agent->currency_symbol)) ? $general->currency_symbol : '';
      $country_restriction = (!empty($ee_mb_agent->country_restriction)) ? $general->country_restriction : '';
      $sold_stc_label = (!empty($general->sold_stc_label)) ? $general->sold_stc_label : '';
   endif;
?>
<div id="general" class="ee-mb-tabcontent">
   <form method="post" name="form_general" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php wp_nonce_field('ee_mb_general_settings','ee_mb_general_settings_nounce'); ?>
      <input type="hidden" name="action" value="elementor_extensions_settings"/>
     
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Currency Symbol','elementor-extensions'); ?> </th>
               <td>
                   <input type="text" value="<?php echo esc_html($currency_symbol); ?>" name="ee_mb_property_setting[currency_symbol]"/>
                   <p class="description es-description"><?php printf( __( 'Copy paste your currency code like ₹, $, ¥, £, € here', 'elementor-extensions' )); ?></p>
               </td>
            </tr>

            <tr>
               <th scope="row"><?php _e('Country Restriction','elementor-extensions'); ?> </th>
               <td>
                   <input type="text" value="<?php echo esc_html($country_restriction); ?>" name="ee_mb_property_setting[country_restriction]"/>
                   <p class="description es-description"><?php printf( __( 'Restrict autocomplete search by country restriction. Ex. "uk" for "United Kingdom", "in" for "India", "us" for "United States" ', 'elementor-extensions' )); ?></p>
               </td>
            </tr>

            <tr>
               <th scope="row"> <?php _e('Sold STC Label','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" value="<?php echo esc_html($sold_stc_label); ?>" name="ee_mb_property_setting[sold_stc_label]"/>
                  <p class="description es-description"><?php printf( __( 'Include sold subject to contract label', 'elementor-extensions' )); ?></p>
               </td>
            </tr>
   
         </tbody>
      </table>
      <hr>
      <p class="submit">
         <input type="submit" name="btn_update_general" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
      </p>
   </form>
</div>