<?php
//MESSAGE CLASS START    
class MJ_Gmgt_message
{
	/*-------DELETE MESSAGE FUNCTION------------*/
	public function MJ_gmgt_delete_message($mid)
	{
		global $wpdb;
		$table_gmgt_message = $wpdb->prefix. 'Gmgt_message';
		$result = $wpdb->query("DELETE FROM $table_gmgt_message where message_id= ".$mid);
		return $result;
	}
	/*-------COUNT SEND MESSAGES------------*/
	public function MJ_gmgt_count_send_item($user_id)
	{
		global $wpdb;
		$posts = $wpdb->prefix."posts";
		$total =$wpdb->get_var("SELECT Count(*) FROM ".$posts." Where post_type = 'hmgt_message' AND post_author = $user_id");
		return $total;
	}
	/*----------SEND REPLAY MESSAGES-------------*/
	public function MJ_gmgt_send_replay_message($data)
	{			
		global $wpdb;
		$table_name = $wpdb->prefix . "gmgt_message_replies";
		$messagedata['message_id'] = $data['message_id'];
		$messagedata['sender_id'] = $data['user_id'];
		$messagedata['receiver_id'] = $data['receiver_id'];
		$messagedata['message_comment'] = MJ_gmgt_strip_tags_and_stripslashes($data['replay_message_body']);
		$messagedata['created_date'] = date("Y-m-d h:i:s");
		$result=$wpdb->insert( $table_name, $messagedata );
		
		   $gymname=get_option( 'gmgt_system_name' );
			$userdata = get_userdata($data['receiver_id']);
			$senderuserdata = get_userdata($data['user_id']);
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
		
			$arr['[GMGT_RECEIVER_NAME]']=$userdata->display_name;	
			$arr['[GMGT_GYM_NAME]']=$gymname;
			$arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;
			$arr['[GMGT_MESSAGE_CONTENT]']=MJ_gmgt_strip_tags_and_stripslashes($data['replay_message_body']);
			$arr['[GMGT_MESSAGE_LINK]']=$page_link;
			$subject =get_option('message_received_subject');
			$sub_arr['[GMGT_SENDER_NAME]']=$senderuserdata->display_name;;
			$sub_arr['[GMGT_GYM_NAME]']=$gymname;
			$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
			$message = get_option('message_received_template');	
			$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				MJ_gmgt_send_mail($to,$subject,$message_replacement);
			if($result)	
		return $result;		
	}
	/*---------FETCH ALL REPLAY--------------------*/
	public function MJ_gmgt_get_all_replies($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "gmgt_message_replies";
		return $result =$wpdb->get_results("SELECT *  FROM $table_name where message_id = $id");
	}
	/*-------COUNT REPLAY MESSAGE------------*/
	public function MJ_gmgt_count_reply_item($id)
	{
		global $wpdb;
		$tbl_name = $wpdb->prefix .'gmgt_message_replies';
		
		$result=$wpdb->get_var("SELECT count(*)  FROM $tbl_name where message_id = $id");
		return $result;
	}
	/*-------DELETE REPLAY MESSAGE------------*/
	public function MJ_gmgt_delete_reply($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "gmgt_message_replies";
		$reply_id['id']=$id;
		return $result=$wpdb->delete( $table_name, $reply_id);
	}	
}
//MESSAGE CLASS END
?>