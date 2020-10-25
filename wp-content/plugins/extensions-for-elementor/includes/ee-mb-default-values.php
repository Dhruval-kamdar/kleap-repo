<?php 

if( !function_exists('ee_mb_default_value_function') ){
	function ee_mb_default_value_function(){

		/*@ Agent settings */
		$agent['sender_email'] = '';
		$agent['default_agent'] = '';
		$agent['mail_success'] = 'Thank you for your message. It has been sent.';
		$agent['mail_subject'] = 'Property Enquiry';
		$agent['mail_template'] = '
			<table style="width: 600px; padding: 0; margin: 0px auto; background-color: #ffffff; border-collapse: collapse; border-radius: 5px;" width="600" align="center">
			   <tbody>
			      <tr>
			         <td>
			            <table style="width: 100%; background: #343399; padding: 20px 10%; margin: 0; text-align: center; border-top-left-radius: 5px; border-top-right-radius: 5px;">
			               <tbody>
			                  <tr>
			                     <td><a href="https://www.google.com" target="_blank" rel="noopener">
			                        <h1>ADD YOUR LOGO HERE</h1>
			                        </a>
			                     </td>
			                  </tr>
			               </tbody>
			            </table>
			         </td>
			      </tr>
			      <tr>
			         <td>
			            <table style="border-collapse: collapse; width: 100%; font-size: 14px; line-height: 18px; color: #535353; margin: 0; padding: 0; text-align: left; background: #fff; border: 1px solid gray;">
			               <tbody>
			                  <tr>
			                     <td>
			                        <table style="border-collapse: collapse; width: 90%; float: left; margin: 5%;" cellspacing="0" cellpadding="0">
			                           <tbody>
			                              <tr>
			                                 <td style="font-size: 16px; font-weight: bold;">Hello Admin,</td>
			                              </tr>
			                              <tr>
			                                 <td style="padding: 10px 0;" width="150px;">New contact request has been submitted.The details are as below :</td>
			                              </tr>
			                              <tr>
			                                 <td>
			                                    <table style="width: 100%; margin: 10px 0; font-size: 14px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
			                                       <tbody>
			                                          <tr>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;" width="30%">Name</td>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;">[contact_name]</td>
			                                          </tr>
			                                          <tr>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;" width="30%">Email</td>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;">[contact_email]</td>
			                                          </tr>
			                                          <tr>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;" width="30%">Phone</td>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;">[contact_number]</td>
			                                          </tr>
			                                          <tr>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;" width="30%">Message</td>
			                                             <td style="border: 1px solid #cccccc; padding: 10px;">[contact_message]</td>
			                                          </tr>
			                                       </tbody>
			                                    </table>
			                                 </td>
			                              </tr>
			                           </tbody>
			                        </table>
			                     </td>
			                  </tr>
			                  <tr>
			                     <td></td>
			                  </tr>
			               </tbody>
			            </table>
			         </td>
			      </tr>
			      <tr>
			         <td>
			            <table style="margin: 0 auto; width: 100%; background: #363636 repeat; padding: 10px 1%; text-align: center; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid grey; color: #fff;" align="center">
			               <tbody>
			                  <tr>
			                     <td>
			                        <h4>EMAIL US</h4>
			                        <h5 style="color: #fff;">Your address line 1,
			                        	Your address line 2
			                        </h5>
			                        <h5>Phone:<a style="color: #fff; font-style: none;" href="tel:01234 567891"> 01234 567891</a></h5>
			                     </td>
			                     <td><i class="icon-facebook" aria-hidden="true"></i>
			                        <i class="icon-twitter" aria-hidden="true"></i>
			                        <i class="icon-google-plus"></i>
			                     </td>
			                  </tr>
			               </tbody>
			            </table>
			         </td>
			      </tr>
			   </tbody>
			</table>';
		$agent = json_encode($agent);
		update_option( 'ee_mb_agent', $agent );

		/*@ General settings */
		$general_setting['currency_symbol'] = '$';
		$general_setting['country_restriction'] = '';
		$general_setting['sold_stc_label'] = 'Sold STC';
        $general_setting = json_encode($general_setting);
        update_option( 'ee_mb_property_setting', $general_setting );

        /*@ Single Page settings */
        $single_page['color_scheme'] = '';
		$single_page['font_color_scheme'] = '';
		$single_page['school_checker'] = '';

        $single_page = json_encode($single_page);
        update_option( 'ee_mb_single_page', $single_page );

        /*@ Integration settings */
        $integration['ee_mb_google_map_key'] = '';
        $integration['ee_mb_snazzy_map_key'] = '';
        $integration['ee_mb_snazzy_map_endpoint'] = 'explore';
        $integration['ee_mb_google_calendar_key'] = '';
        $integration['ee_mb_eventbrite_auth_token'] = '';
        $integration['ee_mb_instagram_access_token'] = '';
        $integration['ee_mb_import_template_url'] = '';

		$integration = json_encode($integration);
		update_option( 'ee_mb_integration_setting', $integration );
	}
	add_action('ee_mb_default_plugin_values', 'ee_mb_default_value_function');
}