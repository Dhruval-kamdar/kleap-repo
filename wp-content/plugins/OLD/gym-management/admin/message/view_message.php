<?php 
$obj_message= new MJ_Gmgt_message; 
//DELETE SENDBOX DATA
if($_REQUEST['from']=='sendbox')
{
	$message = get_post($_REQUEST['id']);
	$box='sendbox';
	if(isset($_REQUEST['delete']))
	{
			echo $_REQUEST['delete'];
			wp_delete_post($_REQUEST['id']);
			wp_safe_redirect(admin_url()."admin.php?page=Gmgt_message&tab=sentbox" );
			exit();
	}
}
//Delete INBOX DATA
if($_REQUEST['from']=='inbox')
{
	$message = MJ_gmgt_get_message_by_id($_REQUEST['id']);
	MJ_gmgt_change_read_status($_REQUEST['id']);
	$box='inbox';

	if(isset($_REQUEST['delete']))
	{
		echo $_REQUEST['delete'];
			
		MJ_gmgt_delete_message('smgt_message',$_REQUEST['id']);
		wp_safe_redirect(admin_url()."admin.php?page=Gmgt_message&tab=inbox" );
		exit();
	}

}
//SAVE Reply MESSAGE DATA
if(isset($_POST['replay_message']))
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=$obj_message->MJ_gmgt_send_replay_message($_POST);
	if($result)
		wp_safe_redirect(admin_url()."admin.php?page=Gmgt_message&tab=view_message&from=".$message_from."&id=$message_id&message=1" );
}
//Delete REPLY MESSAGE DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete-reply' && isset($_REQUEST['reply_id']))
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=$obj_message->MJ_gmgt_delete_reply($_REQUEST['reply_id']);
	if($result)
	{
		wp_redirect (admin_url().'admin.php?page=Gmgt_message&tab=view_message&action=delete-reply&from='.$message_from.'&id='.$message_id.'&message=2');
	}
}
?>
<div class="mailbox-content"><!--MAILBOX CONTENT DIV STRAT-->
 	<div class="message-header">
		<h3><span><?php _e('Subject','gym_mgt')?> :</span>  <?php if($box=='sendbox'){ echo $message->post_title; } else{ echo $message->subject; } ?></h3>
        <p class="message-date"><?php  if($message->date != '')echo  MJ_gmgt_getdate_in_input_box($message->date);?></p>
	</div>
	<div class="message-sender">                                
    	<p><?php if($box=='sendbox'){ echo MJ_gmgt_get_display_name($message->post_author); } else{ echo MJ_gmgt_get_display_name($message->sender); } ?> <span>&lt;<?php if($box=='sendbox'){ echo MJ_gmgt_get_emailid_byuser_id($message->post_author); } else{ echo MJ_gmgt_get_emailid_byuser_id($message->sender); } ?>&gt;</span></p>
    </div>
  
	<div class="message-content"><!--MESSAGE CONTENT DIV STRAT-->
    	<p><?php $receiver_id=0;
		if($box=='sendbox'){ 
		echo $message->post_content; 
		$receiver_id=(get_post_meta($_REQUEST['id'],'message_for_userid',true));} else{ echo $message->message_body;
		$receiver_id=$message->sender;}?></p>
		<div class="message-options pull-right">
			<a class="btn btn-default" href="?page=cmgt-message&tab=view_message&id=<?php echo $_REQUEST['id'];?>&from=<?php echo $box;?>&delete=1" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','gym_mgt');?>');"><i class="fa fa-trash m-r-xs"></i><?php _e('Delete','gym_mgt')?></a> 
	   </div>
   </div><!--MESSAGE CONTENT DIV END-->
     <?php 
	 //GET MESSAGE ALL REPLY DATA
	 if(isset($_REQUEST['from']) && $_REQUEST['from']=='inbox')
			$allreply_data=$obj_message->MJ_gmgt_get_all_replies($message->post_id);
		else
			$allreply_data=$obj_message->MJ_gmgt_get_all_replies($_REQUEST['id']);
		if(!empty($allreply_data))
		{		
			foreach($allreply_data as $reply)
			{?>
				<div class="message-content"><!--MESSAGE CONTENT DIV STRAT-->
					<p><?php echo $reply->message_comment;?><br><h5><?php _e('Reply By:','gym_mgt');?> <?php echo MJ_gmgt_get_display_name($reply->sender_id);if($reply->sender_id == get_current_user_id())
					{?>		
					<span class="comment-delete">
					<a href="?dashboard=user&page=Gmgt_message&tab=view_message&action=delete-reply&from=<?php echo $_REQUEST['from'];?>&id=<?php echo $_REQUEST['id'];?>&reply_id=<?php echo $reply->id;?>"><?php _e('Delete','gym_mgt');?></a></span> 
					<?php } ?>
					<span class="timeago" title="<?php echo $reply->created_date;?>"></span>
					</h5> 
					</p>
				</div><!--MESSAGE CONTENT DIV END-->
			<?php 
			}
		}		
		?>
		<form name="message-replay" method="post" id="message-replay"><!--MESSAGE REPLY FORM START-->
		   <input type="hidden" name="message_id" value="<?php if($_REQUEST['from']=='sendbox') echo $_REQUEST['id']; else echo $message->post_id;?>">
		   <input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>">
		   <input type="hidden" name="receiver_id" value="<?php echo $receiver_id;?>">
			<div class="message-content">
			 <div class="col-sm-8">
				<textarea name="replay_message_body" id="replay_message_body" class="form-control text-input validate[required,custom[address_description_validation]]" maxlength="500"></textarea>				
			</div>
			<div class="message-options pull-right reply-message-btn">
					<button type="submit" name="replay_message" class="btn btn-default"><i class="fa fa-reply m-r-xs"></i><?php _e('Reply','gym_mgt')?></button>				
			   </div>
			</div>
		</form><!--MESSAGE REPLY FORM END-->
</div><!--MAILBOX CONTENT DIV END-->