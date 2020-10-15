<?php 
//MEMBERSHIP CLASS START   
class MJ_Gmgtmembership
{	
	//MEMBERSHIP DATA ADD
	public function MJ_gmgt_add_membership($data,$member_image_url)
	{		
		global $wpdb;
		$obj_activity=new MJ_Gmgtactivity;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
		//------- membership  table data --------------
		$membershipdata['membership_label']=MJ_gmgt_strip_tags_and_stripslashes($data['membership_name']);
		$membershipdata['membership_cat_id']=$data['membership_category'];
		$membershipdata['membership_length_id']=$data['membership_period'];		
		$membershipdata['membership_class_limit']=$data['member_limit'];
		
		if(isset($data['on_of_member']))
			$membershipdata['on_of_member']=$data['on_of_member'];
		else
			$membershipdata['on_of_member']=0;
		
		$membershipdata['classis_limit']=$data['classis_limit'];		
		if(isset($data['on_of_classis']))
			$membershipdata['on_of_classis']=$data['on_of_classis'];
		else
			$membershipdata['on_of_classis']=0;
		
		$membershipdata['install_plan_id']=$data['installment_plan'];
		$membershipdata['membership_amount']=$data['membership_amount'];
		$membershipdata['installment_amount']=$data['installment_amount'];
		$membershipdata['signup_fee']=$data['signup_fee'];
		$membershipdata['membership_description']=$data['description'];
		$membershipdata['gmgt_membershipimage']=$member_image_url;
		$membershipdata['created_date']=date("Y-m-d");
		$membershipdata['created_by_id']=get_current_user_id();	
		$membershipdata['activity_cat_status']=1;	
		
		if(isset($data['tax']))
		{
			$membershipdata['tax']=implode(",",$data['tax']);		
		}
		else
		{
			$membershipdata['tax']=null;		
		}	
		
		if(isset($data['activity_cat_id']))
		{
			$membershipdata['activity_cat_id']=implode(",",$data['activity_cat_id']);		
		}
		else
		{
			$membershipdata['activity_cat_id']=null;
		}	
		if($data['action']=='edit')
		{
			$membershipid['membership_id']=$data['membership_id'];
			$result=$wpdb->update( $table_membership, $membershipdata ,$membershipid);
			
			$obj_activity->MJ_gmgt_add_membership_activities($data);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_membership, $membershipdata );
			if($result)
				$result=$wpdb->insert_id;
			$data['membership_id']=$result;
			$obj_activity->MJ_gmgt_add_membership_activities($data);
			return $result;
		}	
	}
	//get all membership
	public function MJ_gmgt_get_all_membership()
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	
		$result = $wpdb->get_results("SELECT * FROM $table_membership");
		return $result;	
	}
	//get member own membership
	public function MJ_gmgt_get_member_own_membership($membership_id)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	
		$result = $wpdb->get_results("SELECT * FROM $table_membership where membership_id=$membership_id");
		return $result;	
	}
	//get  membership by created by
	public function MJ_gmgt_get_membership_by_created_by($user_id)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	
		$result = $wpdb->get_results("SELECT * FROM $table_membership where created_by_id=$user_id");
		return $result;	
	}
	//get single membership
	public function MJ_gmgt_get_single_membership($id)
	{
		if($id == '')
		return '';
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
		$result = $wpdb->get_row("SELECT * FROM $table_membership where membership_id= ".$id);
		return $result;
	}
	//delete membership
	public function MJ_gmgt_delete_membership($id)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
		$result = $wpdb->query("DELETE FROM $table_membership where membership_id= ".$id);
		return $result;
	}
	//update membership  image
	public function MJ_gmgt_update_membershipimage($id,$imagepath)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
		$image['gmgt_membershipimage']=$imagepath;
		$membershipid['membership_id']=$id;
		return $result=$wpdb->update( $table_membership, $image, $membershipid);
	}
	//get membership activities
	public function MJ_gmgt_get_membership_activities($id)
	{
		global $wpdb;
		$table_gmgt_membership_activities = $wpdb->prefix. 'gmgt_membership_activities';
	
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_activities where membership_id= ".$id);
		return $result;	
	}	
	//update membership Activity Category
	public function MJ_gmgt_update_membership_activity_category($membership_id,$category_id)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
		
		$membership_data = $wpdb->get_row("SELECT * FROM $table_membership where membership_id= ".$membership_id);
		
		$activity_cat_id_array=explode(",",$membership_data->activity_cat_id);
		
		if (in_array($category_id, $activity_cat_id_array))
		{
			
			return $result='';
		}
		else
		{				
			array_push($activity_cat_id_array,$category_id);	
			
			$membershipdata['activity_cat_id']=implode(',',array_filter($activity_cat_id_array));
			$membershipid['membership_id']=$membership_id;
			$result=$wpdb->update( $table_membership, $membershipdata, $membershipid);
			
			return $result;
		}	
	}
	//get all membership
	public function MJ_gmgt_get_all_membership_dashboard()
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	
		$result = $wpdb->get_results("SELECT * FROM $table_membership ORDER BY membership_id DESC limit 3");
		return $result;	
	}
	//get member own membership dashboard
	public function MJ_gmgt_get_member_own_membership_dashboard($membership_id)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	
		$result = $wpdb->get_results("SELECT * FROM $table_membership where membership_id=$membership_id ORDER BY membership_id DESC limit 3");
		return $result;	
	}
	//get  membership by created by dashboard
	public function MJ_gmgt_get_membership_by_created_by_dashboard($user_id)
	{
		global $wpdb;
		$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	
		$result = $wpdb->get_results("SELECT * FROM $table_membership where created_by_id=$user_id ORDER BY membership_id DESC limit 3");
		return $result;	
	}
}
//MEMBERSHIP CLASS END
?>