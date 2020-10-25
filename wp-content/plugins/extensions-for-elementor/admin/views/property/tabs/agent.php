<?php

   $mail_subject = $mail_success = $mail_template = $default_agent = $sender_email = '';
   if(!empty($ee_mb_agent)):
      $mail_subject = (!empty($ee_mb_agent->mail_subject)) ? $ee_mb_agent->mail_subject : '';
      $mail_success = (!empty($ee_mb_agent->mail_success)) ? $ee_mb_agent->mail_success : '';
      $mail_template = (!empty($ee_mb_agent->mail_template)) ? html_entity_decode($ee_mb_agent->mail_template) : '';
      $default_agent = (!empty($ee_mb_agent->default_agent)) ? $ee_mb_agent->default_agent: ''; 
      $sender_email = (!empty($ee_mb_agent->sender_email)) ? $ee_mb_agent->sender_email : '';
   endif;

   $agent_arg = [
      'post_type'   => 'agent',
      'post_status' => 'publish'
   ];
   $agents = get_posts( $agent_arg );
?>
<div id="agent" class="ee-mb-tabcontent">
   <form method="post" name="form_single_page" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php wp_nonce_field('ee_mb_agent_settings','ee_mb_agent_settings_nounce'); ?>
      <input type="hidden" name="action" value="elementor_extensions_settings"/>
     
      <h2>Agent</h2>
      <hr/>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Default Agent','elementor-extensions'); ?> </th>
               <td>
                  <select name="ee_mb_agent[default_agent]">
                     <option value="">Select</option>
                     <?php 
                        if(!empty($agents)):
                           foreach($agents as $key => $agent):
                              $id = $agent->ID;
                              $name = get_post_meta($id, 'name', true);
                              echo '<option value="'.$id.'" '.selected( $default_agent, $id, true).'>'.$name.'</option>';
                           endforeach;
                        endif;
                     ?>
                  </select>
               </td>
            </tr>
         </tbody>
      </table>

      <h2>Mail Settings</h2>
      <hr/>
      <table class="form-table">
         <tbody>
            <tr>
               <th scope="row"><?php _e('Sender Email','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" name="ee_mb_agent[sender_email]" value="<?php echo esc_html($sender_email); ?>"/>
                  <p class="description es-description"><?php printf( __( 'Add sender email if you are using SMTP providers like `Amazon SES`, `SendinBlue`, `Mailgun`, `SendGrid` etc.', 'elementor-extensions' )); ?></p>
               </td>
            </tr>

            <tr>
               <th scope="row"><?php _e('Mail Subject','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" name="ee_mb_agent[mail_subject]" value="<?php echo esc_html($mail_subject); ?>"/>
               </td>
            </tr>

            <tr>
               <th scope="row"><?php _e('Mail Success','elementor-extensions'); ?> </th>
               <td>
                  <input type="text" name="ee_mb_agent[mail_success]" value="<?php echo esc_html($mail_success);  ?>"/>
               </td>
            </tr>

             <tr>
               <th scope="row"><?php _e('Mail Template','elementor-extensions'); ?> </th>
               <td>
                  <?php
                     $content = stripslashes($mail_template);
                     $editor_id = 'mail_template';
                     $settings = [
                        'textarea_name' => "ee_mb_agent[mail_template]",
                        'editor_height' => '400px'
                     ];
                     wp_editor( $content, $editor_id, $settings );
                  ?>
               </td>
            </tr>

         </tbody>
      </table>
      <hr>
      <p class="submit">
         <input type="submit" name="btn_update_agent" class="button button-primary" value="<?php _e('Save Changes','elementor-extensions'); ?>"/>
      </p>
   </form>
</div>