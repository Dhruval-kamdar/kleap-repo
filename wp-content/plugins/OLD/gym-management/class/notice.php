<?php
//NOTICE CLASS START 	  
class MJ_Gmgtnotice
{
	 //add notice function
	public function MJ_gmgt_add_notice($data)
	{
		if($data['action']=='edit')
		{
			$args = array(
				'ID'           => $data['notice_id'],
				'post_title'   => MJ_gmgt_strip_tags_and_stripslashes($data['notice_title']),
				'post_content' =>  MJ_gmgt_strip_tags_and_stripslashes($data['notice_content']),
			);
			$result1=wp_update_post( $args );
			$result2=update_post_meta($data['notice_id'], 'notice_for', $data['notice_for']);
			if(isset($_POST['class_id']))
				$result3=update_post_meta($data['notice_id'], 'gmgt_class_id',$_REQUEST['class_id']);
			if(isset($_POST['class_id']))
				$result4=update_post_meta($data['notice_id'], 'gmgt_start_date',MJ_gmgt_get_format_for_db($_REQUEST['start_date']));
			if(isset($_POST['class_id']))
				$result5=update_post_meta($data['notice_id'], 'gmgt_end_date',MJ_gmgt_get_format_for_db($_REQUEST['end_date']));
			if($result1 || $result2 || $result3 || $resul4 || $result5)
				return $result=1;
		}
		else
		{			
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'gmgt_notice',
				'post_title' => MJ_gmgt_strip_tags_and_stripslashes($data['notice_title']),
				'post_content' => MJ_gmgt_strip_tags_and_stripslashes($data['notice_content'])
			) );			
			//Add Notice function				
			if(!empty($_POST['notice_for']))
			{
				delete_post_meta($post_id, 'notice_for');
				
				$result=add_post_meta($post_id, 'notice_for',$_POST['notice_for']);
				if(isset($_POST['class_id']))
					$result=add_post_meta($post_id, 'gmgt_class_id',$_POST['class_id']);
				if(isset($_POST['start_date']))
					$result=add_post_meta($post_id, 'gmgt_start_date',MJ_gmgt_get_format_for_db($_POST['start_date']));
				if(isset($_POST['end_date']))
					$result=add_post_meta($post_id, 'gmgt_end_date',MJ_gmgt_get_format_for_db($_POST['end_date']));
				
				$riciver=$_POST['notice_for'];
				
				if($riciver == 'member'  && !empty($_POST['class_id']))
				{						
					$classid=$_POST['class_id'];
					
					global $wpdb;
					$table_memberclass = $wpdb->prefix. 'gmgt_member_class';
				 
					$ClassData = $wpdb->get_results("SELECT * FROM $table_memberclass WHERE class_id=$classid");
					if(!empty($ClassData))
					{
						foreach ($ClassData as $userdata)
					    {
						   $member_id=$userdata->member_id;
						   
							$userinfo=get_userdata($member_id);
							$ricivermail=$userinfo->user_email;
							$ricivername=$userinfo->display_name;
							$userid=get_current_user_id();
							$user=get_userdata($userid);
							$username=$user->display_name;
							$gymname=get_option( 'gmgt_system_name' );							
							$page_link=home_url().'/?apartment-dashboard=user&page=notice-event&tab=notice_list';
							$arr['[GMGT_USERNAME]']=$ricivername;
							$arr['[GMGT_MEMBERNAME]']=$username;
							$arr['[GMGT_GYM_NAME]']=$gymname;
							$arr['[GMGT_NOTICE_TITLE]']=MJ_gmgt_strip_tags_and_stripslashes($data['notice_title']);
							$arr['[GMGT_NOTICE_FOR]']=$data['notice_for'];
							$arr['[GMGT_STARTDATE]']=$_POST['start_date'];
							$arr['[GMGT_ENDDATE]']=$_POST['end_date'];
							$arr['[GMGT_COMMENT]']=MJ_gmgt_strip_tags_and_stripslashes($data['notice_content']);
							$arr['[GMGT_NOTICE_LINK]']=$page_link;
							$subject =get_option('Add_Notice_Subject');
							$sub_arr['[GMGT_USERNAME]']=$ricivername;
							$sub_arr['[GMGT_GYM_NAME]']=$gymname;
							$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
							$message = get_option('Add_Notice_Template');	
							$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
							$to=$ricivermail;
							 MJ_gmgt_send_mail($to,$subject,$message_replacement);
						   //Add Notice
					    } 
					}
				}
				else
			    {
					if($riciver == 'all')
					{
						$get_member = array('role' => 'member');
						$get_accountant = array('role' => 'accountant');												
						$get_staff = array('role' => 'staff_member');
							
						$get_member1=get_users($get_member);						
						$get_accountant1 =get_users($get_accountant);
						$get_staff1 =get_users($get_staff);
						
						$staffdata = array_merge($get_member1,$get_accountant1,$get_staff1);						
					}
					else	
					{
						$get_staff = array('role' => $riciver);	
						$staffdata=get_users($get_staff);		
					}
					if(!empty($staffdata))
					{
						foreach ($staffdata as $retrieved_data)
						{							 
							$ricivermail=$retrieved_data->user_email;
							$ricivername=$retrieved_data->display_name;
							 
							$userid=get_current_user_id();
							$user=get_userdata($userid);
							$username=$user->display_name;
							$gymname=get_option( 'gmgt_system_name' );							
							$page_link=home_url().'/?apartment-dashboard=user&page=notice-event&tab=notice_list';
							$arr['[GMGT_USERNAME]']=$ricivername;
							$arr['[GMGT_MEMBERNAME]']=$username;
							$arr['[GMGT_GYM_NAME]']=$gymname;
							$arr['[GMGT_NOTICE_TITLE]']=MJ_gmgt_strip_tags_and_stripslashes($data['notice_title']);
							$arr['[GMGT_NOTICE_FOR]']=$data['notice_for'];
							$arr['[GMGT_STARTDATE]']=$_POST['start_date'];
							$arr['[GMGT_ENDDATE]']=$_POST['end_date'];
							$arr['[GMGT_COMMENT]']=MJ_gmgt_strip_tags_and_stripslashes($data['notice_content']);
							$arr['[GMGT_NOTICE_LINK]']=$page_link;
							$subject =get_option('Add_Notice_Subject');
							$sub_arr['[GMGT_USERNAME]']=$ricivername;
							$sub_arr['[GMGT_GYM_NAME]']=$gymname;
							$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
							$message = get_option('Add_Notice_Template');	
							$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
							$to=$ricivermail;
							MJ_gmgt_send_mail($to,$subject,$message_replacement);
						}
					}
				}
			}
			return $result;		
		}	
	}
	//get notice function
	public function MJ_gmgt_get_notice($role)
	{
		$args['post_type'] = 'gmgt_notice';
		$args['posts_per_page'] = -1;
		$args['post_status'] = 'public';
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
		$args['meta_query'] = array(
									'relation' => 'OR',
							        array(
							            'key' => 'notice_for',
							            'value' =>"all",						           
							        ),
									array(
											'key' => 'notice_for',
											'value' =>"$role",
									)
							   );
		$q = new WP_Query();
		
		$retrieve_notice = $q->query( $args );
		return $retrieve_notice;		
	}
	//get all notice
	public function MJ_gmgt_get_all_notice()
	{		
		$args = array(		
			  'post_type'   => 'gmgt_notice'
		);
 
		$retrieve_notice = get_posts( $args );

		return $retrieve_notice;		
	}
	//delete notice
	public function MJ_gmgt_delete_notice($id)
	{
		$result=wp_delete_post($id);
		return $result;
	}
	//get notice function dashboard
	public function MJ_gmgt_get_notice_dashboard($role)
	{
		$args['post_type'] = 'gmgt_notice';
		$args['posts_per_page'] = 3;
		$args['post_status'] = 'public';
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
		$args['meta_query'] = array(
									'relation' => 'OR',
							        array(
							            'key' => 'notice_for',
							            'value' =>"all",						           
							        ),
									array(
											'key' => 'notice_for',
											'value' =>"$role",
									)
							   );
		$q = new WP_Query();
		
		$retrieve_notice = $q->query( $args );
		return $retrieve_notice;		
	}
	//get all notice dashboard
	public function MJ_gmgt_get_all_notice_dashboard()
	{	
		$today = date('Y-m-d');
		 
		/* $args = array( 'post_type' => 'gmgt_notice', 'meta_key' => 'gmgt_end_date', 'meta_query' => array( array( 'key' => 'gmgt_end_date' ), array( 'key' => 'gmgt_end_date', 'value' => $today, 'compare' => '>=' ) ), 'orderby' => 'meta_value', 'order' => 'ASC' ); */
		$args = array(		
			  'post_type'   => 'gmgt_notice',
			   'posts_per_page'   => 3
		);  
		$retrieve_notice = get_posts( $args );
		
		return $retrieve_notice;		
	}
}
//NOTICE CLASS END
?>