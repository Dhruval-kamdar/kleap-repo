<?php 
$obj_class=new MJ_Gmgtclassschedule;
$apikey = get_option('gmgt_mailchimp_api');
$api = new MJ_GYM_MCAPI();
$result=$api->MCAPI($apikey);
$api->useSecure(true);
$active_tab = isset($_GET['tab'])?$_GET['tab']:'mailchimp_setting';
?>

<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV STRAT-->
	<div class="page-title">
			<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE MAILCHIMP API DATA
	if(isset($_REQUEST['save_setting']))
	{
		update_option( 'gmgt_mailchimp_api', $_REQUEST['gmgt_mailchimp_api']);
		$message = __("Setting added successfully.","gym_mgt");
	}
	//SYNCRONIZE USER EMAIL WITH MAILCHIMP
	if(isset($_REQUEST['sychroniz_email']))
	{
		$retval = $api->lists();
		$subcsriber_emil = array();
		if(isset($_REQUEST['syncmail']))
		{
			$syncmail = $_REQUEST['syncmail'];
	
			$class_id_array = implode("','",$syncmail);
		
			global $wpdb;
			$tbl_class = $wpdb->prefix .'gmgt_member_class';
			$result = $wpdb->get_results("SELECT member_id FROM $tbl_class where class_id IN ('".$class_id_array."')");
			
			$user_id_array=array();
			if(!empty($result))
			{
				foreach ($result as $retrieved_data)
				{
					$user_id_array[]=$retrieved_data->member_id;
					
				}
			}
			$user_id_array_unique=array_unique($user_id_array);
			if(!empty($user_id_array_unique))
			{
				foreach ($user_id_array_unique as $data)
				{
					$user_info = get_userdata($data);
					$firstname=get_user_meta($user_info->ID,'first_name',true);
					$lastname=get_user_meta($user_info->ID,'last_name',true);
					if(trim($user_info->user_email) !='')
						$subcsriber_emil[] = array('fname'=>$firstname,'lname'=>$lastname,'email'=>$user_info->user_email); 
				}
			}	
		}
	
		if(!empty($subcsriber_emil))
		{
			foreach ($subcsriber_emil as $value)
			{	
				/* add subscriber start*/
		
				$email = $value['email'];
				$status = 'subscribed'; // "subscribed" or "unsubscribed" or "cleaned" or "pending"
				$list_id = $_REQUEST['list_id']; // where to get it read above
				$api_key = get_option( 'gmgt_mailchimp_api' ); // where to get it read above
				$merge_fields = array('FNAME'=>$value['fname'], 'LNAME'=>$value['lname']);

				$data = array(
				'apikey'        => $api_key,
				'email_address' => $email,
				'status'        => $status,
				'merge_fields'  => $merge_fields
					);
					$mch_api = curl_init(); // initialize cURL connection
				 
					curl_setopt($mch_api, CURLOPT_URL, 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address'])));
					curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.base64_encode( 'user:'.$api_key )));
					curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
					curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
					curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
					curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
					curl_setopt($mch_api, CURLOPT_POST, true);
					curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
				 
					$result = curl_exec($mch_api);
				/* add subscriber end*/
			}
		}
		$message = __("Synchronize Mail Successfully.","gym_mgt");
	}
	//SEND MAILCHIMP 
	if(isset($_REQUEST['send_campign']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'send_campign_nonce' ) )
		{
			$retval = $api->campaigns();
			$retval1 = $api->lists();
			$emails = array();
			$listId = $_REQUEST['list_id'];
			$campaignId =$_REQUEST['camp_id'];
			$listmember = $api->listMembers($listId, 'subscribed', null, 0, 5000 );
			foreach($listmember['data'] as $member)
			{			
				$emails[] = $member['email'];
			}

			$retval2 = $api->campaignSendTest($campaignId, $emails);

			if ($api->errorCode)
			{			
				$message = __("Campaign Tests Not Sent!","gym_mgt");
			} 
			else 
			{
				$message = __("Campaign Tests Sent!","gym_mgt");
			}
		}
	}
	if(isset($message))
	{
	?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			echo $message;
		?></p></div>
		<?php 
	}
	?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV STRAT-->
		<div class="row"><!--ROW DIV STRAT-->
			<div class="col-md-12"><!--COL 12 DIV STRAT-->
				<div class="panel panel-white"><!--PANEL WHITE DIV STRAT-->
					<div class="panel-body"><!--PANEL BODY DIV STRAT-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU STRAT-->
							<a href="?page=gmgt_newsletter&tab=mailchimp_setting" class="nav-tab <?php echo $active_tab == 'mailchimp_setting' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Setting', 'gym_mgt'); ?></a>
							
						  
							<a href="?page=gmgt_newsletter&tab=sync" class="nav-tab <?php echo $active_tab == 'sync' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Sync Mail', 'gym_mgt'); ?></a>  
							
							<a href="?page=gmgt_newsletter&tab=campaign" class="nav-tab <?php echo $active_tab == 'campaign' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Campaign', 'gym_mgt'); ?></a>
						</h2><!--NAV TAB WRAPPER MENU END-->
						<?php 						
						if($active_tab == 'mailchimp_setting')
						{ ?>
							<div class="panel-body"><!--PANEL BODY DIV STRAT-->
								<form name="newsletterform" method="post" id="newsletterform" class="form-horizontal"><!--NEWSSLETER FORM STRAT-->
									<div class="form-group">
											<label class="col-sm-2 control-label" for="wpcrm_mailchimp_api"><?php _e('MailChimp API key','gym_mgt');?></label>
											<div class="col-sm-8">
												<input id="gmgt_mailchimp_api" class="form-control" type="text" value="<?php echo get_option( 'gmgt_mailchimp_api' );?>"  name="gmgt_mailchimp_api">
											</div>
										</div>
										
										<div class="col-sm-offset-2 col-sm-8">
											<input type="submit" value="<?php _e('Save', 'gym_mgt' ); ?>" name="save_setting" class="btn btn-success"/>
									</div>									
								</form><!--NEWSSLETER FORM END-->
							 </div><!--PANEL BODY DIV END-->
						 <?php 
						}
						if($active_tab == 'sync')
						{
							require_once GMS_PLUGIN_DIR. '/admin/news-letter/sync.php';
						}
						if($active_tab == 'campaign')
						{
							require_once GMS_PLUGIN_DIR. '/admin/news-letter/campaign.php';
						}
						?>
					</div><!--PANEL BODY DIV END-->
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
        </div><!--ROW DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->