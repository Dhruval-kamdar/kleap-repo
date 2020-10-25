<?php
//SAVE MESSAGE DATA
if(isset($_POST['save_message']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_message_nonce' ) )
	{
		$created_date = date("Y-m-d H:i:s");
		$subject = MJ_gmgt_strip_tags_and_stripslashes($_POST['subject']);
		$message_body = MJ_gmgt_strip_tags_and_stripslashes($_POST['message_body']);
		$created_date = date("Y-m-d H:i:s");
		$tablename="Gmgt_message";
		$role=$_POST['receiver'];
		if(isset($_REQUEST['class_id']))
		$class_id = $_REQUEST['class_id'];
		if($role == 'member' || $role == 'staff_member' || $role == 'accountant' || $role == 'administrator')
		{
			$userdata=MJ_gmgt_get_user_notice($role,$_REQUEST['class_id']);
			if(!empty($userdata))
			{
					$mail_id = array();
					$i = 0;
						foreach($userdata as $user)
						{
							if($role == 'parent' && $class_id != 'all')
							$mail_id[]=$user['ID'];
							else 
								$mail_id[]=$user->ID;
							$i++;
						}
					$post_id = wp_insert_post( array(
							'post_status' => 'publish',
							'post_type' => 'message',
							'post_title' => $subject,
							'post_content' =>$message_body
					) );
					foreach($mail_id as $user_id)
					{
						$reciever_id = $user_id;
						$message_data=array('sender'=>get_current_user_id(),
								'receiver'=>$user_id,
								'subject'=>$subject,
								'message_body'=>$message_body,
								'date'=>$created_date,
								'status' =>0,
								'post_id' =>$post_id
						);
						MJ_gmgt_insert_record($tablename,$message_data);
						//-----MESSAGE SEND NOTIFICATION TEMPLATE-------
						 $userdata = get_userdata($user_id);
						 $role=$userdata->roles;
						 $reciverrole=$role[0];
						 if($reciverrole == 'administrator' ) 
						 {
							$page_link=admin_url().'admin.php?page=Gmgt_message&tab=inbox';
						 }
						 else
						 {
							$page_link=home_url().'/?dashboard=user&page=message&tab=inbox';
						 } 
						$gymname=get_option( 'gmgt_system_name' );
						$userdata = get_userdata($user_id);
						$senderuserdata = get_userdata(get_current_user_id());
						$arr['[GMGT_RECEIVER_NAME]']=$userdata->display_name;	
						$arr['[GMGT_GYM_NAME]']=$gymname;
						$arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;
						$arr['[GMGT_MESSAGE_CONTENT]']=$message_body;
						$arr['[GMGT_MESSAGE_LINK]']=$page_link;
						$subject =get_option('message_received_subject');
						$sub_arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;;
						$sub_arr['[GMGT_GYM_NAME]']=$gymname;
						$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
						$message_template = get_option('message_received_template');	
						$message_replacement = MJ_gmgt_string_replacemnet($arr,$message_template);
							$to=$userdata->user_email;
								MJ_gmgt_send_mail($to,$subject,$message_replacement);	
					}
					$result=add_post_meta($post_id, 'message_for',$role);
					$result=add_post_meta($post_id, 'gmgt_class_id',$_REQUEST['class_id']);
			}
			else
			{
				$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'message',
					'post_title' => $subject,
					'post_content' =>$message_body
			
				) );
				$user_id =$_POST['receiver'];
				$message_data=array('sender'=>get_current_user_id(),
						'receiver'=>$user_id,
						'subject'=>$subject,
						'message_body'=>$message_body,
						'date'=>$created_date,
						'status' =>0,
						'post_id' =>$post_id
				);
				MJ_gmgt_insert_record($tablename,$message_data);
				//-----MESSAGE SEND NOTIFICATION TEMPLATE-------
					$userdata = get_userdata($user_id);
					$role=$userdata->roles;
					$reciverrole=$role[0];
					if($reciverrole == 'administrator' ) 
					{
						$page_link=admin_url().'admin.php?page=Gmgt_message&tab=inbox';
					}
					else
					{
						$page_link=home_url().'/?dashboard=user&page=message&tab=inbox';
					} 
					$gymname=get_option( 'gmgt_system_name' );
					$userdata = get_userdata($user_id);
					$senderuserdata = get_userdata(get_current_user_id());
					$arr['[GMGT_RECEIVER_NAME]']=$userdata->display_name;	
					$arr['[GMGT_GYM_NAME]']=$gymname;
					$arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;
					$arr['[GMGT_MESSAGE_CONTENT]']=$message_body;
					$arr['[GMGT_MESSAGE_LINK]']=$page_link;
					$subject =get_option('message_received_subject');
					$sub_arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;;
					$sub_arr['[GMGT_GYM_NAME]']=$gymname;
					$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
					$message_template = get_option('message_received_template');	
					$message_replacement = MJ_gmgt_string_replacemnet($arr,$message_template);
						$to=$userdata->user_email;
							MJ_gmgt_send_mail($to,$subject,$message_replacement);	
			
					$result=add_post_meta($post_id, 'message_for','user');
					$result=add_post_meta($post_id, 'message_gmgt_user_id',$user_id);
			}
		}
		else
		{
		$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'message',
					'post_title' => $subject,
					'post_content' =>$message_body
			
			) );

			$user_id =$_POST['receiver'];
			$message_data=array('sender'=>get_current_user_id(),
					'receiver'=>$user_id,
					'subject'=>$subject,
					'message_body'=>$message_body,
					'date'=>$created_date,
					'status' =>0,
					'post_id' =>$post_id
			);
			MJ_gmgt_insert_record($tablename,$message_data);
		
			//-----MESSAGE SEND NOTIFICATION-------
				  $userdata = get_userdata($user_id);
				 $role=$userdata->roles;
				 $reciverrole=$role[0];
				 if($reciverrole == 'administrator' ) 
				 {
					$page_link=admin_url().'admin.php?page=Gmgt_message&tab=inbox';
				 }
				 else
				 {
					$page_link=home_url().'/?dashboard=user&page=message&tab=inbox';
				 } 
				$gymname=get_option( 'gmgt_system_name' );
				$userdata = get_userdata($user_id);
				$senderuserdata = get_userdata(get_current_user_id());
				
				$arr['[GMGT_RECEIVER_NAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;
				$arr['[GMGT_MESSAGE_CONTENT]']=$message_body;
				$arr['[GMGT_MESSAGE_LINK]']=$page_link;
				$subject =get_option('message_received_subject');
				$sub_arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;;
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message_template = get_option('message_received_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message_template);
					$to=$userdata->user_email;
						MJ_gmgt_send_mail($to,$subject,$message_replacement);	
		
				$result=add_post_meta($post_id, 'message_for','user');
				$result=add_post_meta($post_id, 'message_gmgt_user_id',$user_id);
		}
	}
}
if(isset($result))
{?>
	<div id="message" class="updated below-h2">
		<p><?php _e('Message Sent Successfully!','gym_mgt');?></p>
	</div>
<?php 
}	
?>
<script type="text/javascript">
$(document).ready(function() 
{
	 $('#message_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
} );
</script>
<div class="mailbox-content"><!-- MAILBOX CONTENT DIV START -->
	<h2>
		<?php  
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			 echo esc_html( __( 'Edit Message', 'gym_mgt') );
			 $edit=1;
			 $exam_data= get_exam_by_id($_REQUEST['exam_id']);
		}
		?>
	</h2>
	<?php
	if(isset($message))
		echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
	?>
	<form name="class_form" action="" method="post" class="form-horizontal" id="message_form" style="padding-bottom: 50px;"><!-- COMPOSE MAIL FORM START -->
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="to"><?php _e('Message To','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="receiver" class="form-control validate[required] text-input message_to" id="to">
					<?php
					$curr_user_id=get_current_user_id();
					$obj_gym=new MJ_Gym_management($curr_user_id);

					$role = $obj_gym->role;
					if($role != 'member')
					{
					?>
					<option value="member"><?php _e('Members','gym_mgt');?></option>	
					<option value="staff_member"><?php _e('Staff Members','gym_mgt');?></option>	
					<option value="accountant"><?php _e('Accountant','gym_mgt');?></option>	
					
					<option value="administrator"><?php _e('Admin','gym_mgt');?></option>	
					
					<?php 
					}
					echo MJ_gmgt_get_all_user_in_message();
					?>
				</select>
			</div>
		</div>
		<?php
		if($role != 'member')
		{
		?>
		<div id="smgt_select_class" class="display_class_css">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sms_template"><?php _e('Select Class','gym_mgt');?></label>
				<div class="col-sm-8">
				
					 <select name="class_id"  id="class_list" class="form-control">
						<option value="all"><?php _e('All','gym_mgt');?></option>
						<?php
						  foreach(MJ_gmgt_get_allclass() as $classdata)
						  {  
						  ?>
						   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
					 <?php }?>
					</select>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject"><?php _e('Subject','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			   <input id="subject" class="form-control validate[required,custom[popup_category_validation]] onlyletter_number_space_validation" type="text" maxlength="100" name="subject" value="<?php if($edit){ echo $exam_data->exam_date;}?>">
			</div>
		</div>
		<!--nonce-->
		<?php wp_nonce_field( 'save_message_nonce' ); ?>
		<!--nonce-->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject"><?php _e('Message Comment','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			  <textarea name="message_body" id="message_body" class="form-control validate[required,custom[address_description_validation]]" maxlength="500"><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>
			</div>
		</div>			   
		<div class="form-group">                      
			<div class="col-sm-10 ">
				<div class="pull-right">
				<input type="submit" value="<?php if($edit){ _e('Save Message','gym_mgt'); }else{ _e('Send Message','gym_mgt');}?>" name="save_message" class="btn btn-success"/>
				</div>
			</div>
		</div>
	</form><!-- COMPOSE MAIL FORM END -->
</div><!-- MAILBOX CONTENT DIV END -->