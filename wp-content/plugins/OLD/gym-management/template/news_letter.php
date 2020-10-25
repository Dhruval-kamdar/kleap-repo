<?php 
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_class=new MJ_Gmgtclassschedule;
$apikey = get_option('gmgt_mailchimp_api');
$api = new MJ_GYM_MCAPI($apikey);
$active_tab = isset($_GET['tab'])?$_GET['tab']:'mailchimp_setting';
//access right
$user_access=MJ_gmgt_get_userrole_wise_page_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_gmgt_access_right_page_not_access_message();
		die;
	}
}
//SAVE MAILCHIMP API DATA
if(isset($_REQUEST['save_setting']))
{
	update_option( 'gmgt_mailchimp_api', $_REQUEST['gmgt_mailchimp_api']);
	$message = "Save Setting Successfully.";
}
//ADD Synchronize EMAIL DATA
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
	$message = "Synchronize Mail Successfully.";
}
//SEND CHAMPING MAIL
if(isset($_REQUEST['send_campign']))
{
	$retval = $api->campaigns();
	$retval1 = $api->lists();
	$emails = array();
	$listId = $_REQUEST['list_id'];
	$campaignId =$_REQUEST['camp_id'];
	$listmember = $api->listMembers($listId, 'subscribed', null, 0, 5000 );
	foreach($listmember['data'] as $member){
		$emails[] = $member['email'];
	}
	$retval2 = $api->campaignSendTest($campaignId, $emails);
	if ($api->errorCode){
		$message = "Campaign Tests Not Sent!\n";
	} else {
		$message = "Campaign Tests Sent!\n";
	}
}
if(isset($message))
{
?>
	<div id="message" class="updated below-h2"><p>
		<?php 
			echo $message;
		?></p>
	</div>
	<?php
}?>
<div class="panel-body panel-white"><!-- PANEL BODY DIV START -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!-- NAV TABS MENU START -->
     
		<li class="<?php if($active_tab=='mailchimp_setting'){?>active<?php }?>">
				<a href="?dashboard=user&page=news_letter&tab=mailchimp_setting" class="nav-tab <?php echo $active_tab == 'mailchimp_setting' ? 'nav-tab-active' : ''; ?>">
			 <i class="fa fa-align-justify"></i> <?php _e('Setting', 'gym_mgt'); ?></a>
			 
		</li>
		 
		<li class="<?php if($active_tab=='sync'){?>active<?php }?>">
				<a href="?dashboard=user&page=news_letter&tab=sync" class="nav-tab <?php echo $active_tab == 'sync' ? 'nav-tab-active' : ''; ?>">
			<i class="fa fa-plus-circle"></i> <?php _e('Sync Mail', 'gym_mgt'); ?></a>
		</li>
		<li class="<?php if($active_tab=='campaign'){?>active<?php }?>">
				<a href="?dashboard=user&page=news_letter&tab=campaign" class="nav-tab <?php echo $active_tab == 'campaign' ? 'nav-tab-active' : ''; ?>">
			<i class="fa fa-plus-circle"></i> <?php _e('Campaign', 'gym_mgt'); ?></a>
		</li>
    </ul><!-- NAV TABS MENU END -->
	<div class="tab-content"><!-- TAB CONTENT DIV START -->
	<?php
	if($active_tab == 'mailchimp_setting')
	{ ?>
		<div class="panel-body"><!-- PANEL BODY DIV START -->
			<form name="newsletterform" method="post" id="newsletterform" class="form-horizontal"><!-- MAILCHIMP SETTINGS FORM START -->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="wpcrm_mailchimp_api"><?php _e('MailChimp API key','gym_mgt');?></label>
					<div class="col-sm-8">
						<input id="gmgt_mailchimp_api" class="form-control" type="text" value="<?php echo get_option( 'gmgt_mailchimp_api' );?>"  name="gmgt_mailchimp_api">
					</div>
				</div>					
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php _e('Save', 'gym_mgt' ); ?>" name="save_setting" class="btn btn-success"/>
				</div>
				
			  </form><!-- MAILCHIMP SETTINGS FORM END -->
		  </div><!-- PANEL BODY DIV END -->
     <?php 
	}
	if($active_tab == 'sync')
	{
        $retval = $api->lists();?>
        <div class="panel-body"><!-- PANEL BODY DIV START -->
			<form name="template_form" action="" method="post" class="form-horizontal" id="setting_form"><!--SYNCRONIZE USER FORM START -->
			    <div class="form-group">
					<label class="col-sm-2 control-label" for="enable_quote_tab"><?php _e('Class List','gym_mgt');?></label>
					<div class="col-sm-8">
						<div class="checkbox">
						<?php 	$classdata=$obj_class->MJ_gmgt_get_all_classes();
							if(!empty($classdata))
							{
								foreach ($classdata as $retrieved_data)
								{?>										
									<label>
										<input type="checkbox" name="syncmail[]"  value="<?php echo $retrieved_data->class_id?>"/><?php echo $retrieved_data->class_name;?> ( <?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->start_time).' - '.MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->end_time);?>)
								  </label><br/>
								<?php 
								}
							}?>
						 </div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="list_id"><?php _e('Mailing list','gym_mgt');?></label>
					<div class="col-sm-8">
						<select name="list_id" id="list_id"  class="form-control">
							<option value=""><?php _e('Select list','gym_mgt');?></option>
							<?php 
							foreach ($retval['data'] as $list){
								
								echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-8">        	
					<input type="submit" value="<?php _e('Sync Mail', 'gym_mgt' ); ?>" name="sychroniz_email" class="btn btn-success"/>
				</div>
			</form><!--SYNCRONIZE USER FORM END -->
		</div><!-- PANEL BODY DIV END -->
	<?php 
	}
	if($active_tab == 'campaign')
	{
			$retval = $api->campaigns();
			$retval1 = $api->lists();?>
			<div class="panel-body"><!-- PANEL BODY DIV STRAT -->
				<form name="student_form" action="" method="post" class="form-horizontal" id="setting_form"><!-- MAILCHIMP FORM START-->
					<div class="form-group">
						<label class="col-sm-2 control-label" for="quote_form"><?php _e('MailChimp list','gym_mgt');?></label>
						<div class="col-sm-8">
							<select name="list_id" id="quote_form"  class="form-control">
								<option value=""><?php _e('Select list','gym_mgt');?></option>
								<?php 
								foreach ($retval1['data'] as $list){
									
									echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="quote_form"><?php _e('Campaign list','gym_mgt');?></label>
						<div class="col-sm-8">
							<select name="camp_id" id="quote_form"  class="form-control">
								<option value=""><?php _e('Select Campaign','gym_mgt');?></option>
								<?php 
								foreach ($retval['data'] as $c){
									
									echo '<option value="'.$c['id'].'">'.$c['title'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">        	
						<input type="submit" value="<?php _e('Send Campaign', 'gym_mgt' ); ?>" name="send_campign" class="btn btn-success"/>
					</div>
				</form><!-- MAILCHIMP FORM END-->
            </div><!-- PANEL BODY DIV END -->
		<?php
		}
		?>
	</div><!-- TAB CONTENT DIV END -->
</div><!-- PANEL BODY DIV END -->