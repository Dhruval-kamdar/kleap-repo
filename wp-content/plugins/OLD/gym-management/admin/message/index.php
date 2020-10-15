<?php 
$obj_message= new MJ_Gmgt_message;
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
		if($role == 'member' || $role == 'staff_member' || $role == 'accountant')
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

					//-----MESSAGE SEND NOTIFICATION-------
					$gymname=get_option( 'gmgt_system_name' );
					$userdata = get_userdata($user_id);
					$senderuserdata = get_userdata(get_current_user_id());
					$page_link=home_url().'/?dashboard=user&page=message&tab=inbox';
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
				$result=add_post_meta($post_id, 'message_for','user');
				$result=add_post_meta($post_id, 'message_gmgt_user_id',$user_id);
				
				//-----MESSAGE SEND NOTIFICATION-------
					$gymname=get_option( 'gmgt_system_name' );
					$userdata = get_userdata($user_id);
					$senderuserdata = get_userdata(get_current_user_id());
					$page_link=home_url().'/?dashboard=user&page=message&tab=inbox';
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
		}
		else
		{
			$user_id =$_POST['receiver'];
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'message',
				'post_title' => $subject,
				'post_content' =>$message_body
			) );
			$message_data=array('sender'=>get_current_user_id(),
				'receiver'=>$user_id,
				'subject'=>$subject,
				'message_body'=>$message_body,
				'date'=>$created_date,
				'status' =>0,
				'post_id' =>$post_id
			);
			MJ_gmgt_insert_record($tablename,$message_data);
			$result=add_post_meta($post_id, 'message_for','user');
			$result=add_post_meta($post_id, 'message_gmgt_user_id',$user_id);
			
			//-----MESSAGE SEND NOTIFICATION-------
			$gymname=get_option( 'gmgt_system_name' );
			$userdata = get_userdata($user_id);
			$senderuserdata = get_userdata(get_current_user_id());
			$page_link=home_url().'/?dashboard=user&page=message&tab=inbox';
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
	}
}
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'inbox';
?>
<script type="text/javascript">
$(document).ready(function()
{	
	$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	$('.edate').datepicker({dateFormat: "yy-mm-dd"});  
} );
</script>

<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV STRAT-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php
	if(isset($result))
	{ ?>
		<div id="message" class="updated below-h2">
			<p><?php _e('Message Sent Successfully!','gym_mgt');?></p>
		</div>
	<?php
	}	
	?>
	<div id="main-wrapper"><!-- Main-wrapper START -->
		<div class="row mailbox-header">
            <div class="col-md-2 col-sm-3 col-xs-4">
                <a class="btn btn-success btn-block" href="?page=Gmgt_message&tab=compose"><?php _e('Compose','gym_mgt');?></a>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-8">
                <h2><?php
					if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                         echo esc_html( __( 'Inbox', 'gym_mgt' ) );
					else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
						echo esc_html( __( 'Sent Item', 'gym_mgt' ) );
					else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
						echo esc_html( __( 'Compose', 'gym_mgt' ) );
					?>
				</h2>
            </div>                               
        </div>		
		<div class="col-md-2">
			<ul class="list-unstyled mailbox-nav">
			<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
				<a href="?page=Gmgt_message&tab=inbox"><i class="fa fa-inbox"></i> <?php _e('Inbox','gym_mgt');?><span class="badge badge-success pull-right"><?php echo count(MJ_gmgt_count_unread_message(get_current_user_id()));?></span></a></li>
				<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?page=Gmgt_message&tab=sentbox"><i class="fa fa-sign-out"></i><?php _e('Sent','gym_mgt');?></a></li>                 
			</ul>
		</div>
		<div class="col-md-10">
		 <?php  
			if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
				require_once GMS_PLUGIN_DIR. '/admin/message/sendbox.php';
			if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
				require_once GMS_PLUGIN_DIR. '/admin/message/inbox.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
				require_once GMS_PLUGIN_DIR. '/admin/message/composemail.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
				require_once GMS_PLUGIN_DIR. '/admin/message/view_message.php';
			
			?>
		</div>
	</div><!-- Main-wrapper END -->
</div><!-- Page-inner END-->