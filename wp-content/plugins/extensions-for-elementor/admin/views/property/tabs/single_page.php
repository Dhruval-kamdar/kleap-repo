<?php

   $color_scheme = $font_color_scheme = $school_checker = '';
   if(!empty($single_page)):
      $color_scheme = (!empty($single_page->color_scheme)) ? $single_page->color_scheme : '';
      $font_color_scheme = (!empty($single_page->font_color_scheme)) ? $single_page->font_color_scheme : '';
      $school_checker = (!empty($single_page->school_checker)) ? $single_page->school_checker : '';
   endif;
?>
<div id="single_page" class="ee-mb-tabcontent">
   <form method="post" name="form_single_page" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php wp_nonce_field('single_page_settings','single_page_settings_nounce'); ?>
      <input type="hidden" name="action" value="elementor_extensions_settings"/>
     
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Color Scheme','elementor-extensions'); ?> </th>
               <td>
                   <input type="text" value="<?php echo esc_html($color_scheme); ?>" class="es_color_field" name="ee_mb_single_page[color_scheme]"/>
               </td>
            </tr>

            <tr>
               <th scope="row"><?php _e('Font Color Scheme','elementor-extensions'); ?> </th>
               <td>
                   <input type="text" value="<?php echo esc_html($font_color_scheme); ?>" class="es_color_field" name="ee_mb_single_page[font_color_scheme]"/>
               </td>
            </tr>

            <tr>
               <th scope="row"> <?php _e('Disable School Checker Tab','elementor-extensions'); ?> </th>
               <td>
                  <label><input type="checkbox" name="ee_mb_single_page[school_checker]" value="yes" <?php checked( $school_checker, 'yes', true ); ?>/></label>
                  <p class="description es-description"><?php printf( __( 'Check this option to hide school checker on frontend.', 'elementor-extensions' )); ?></p>
               </td>
            </tr>
   
         </tbody>
      </table>
      <hr>
      <p class="submit">
         <input type="submit" name="btn_update_single_page" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
      </p>
   </form>
</div>