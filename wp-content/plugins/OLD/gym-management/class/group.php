<?php 	  
//GROUP CLASS START 
class MJ_Gmgtgroup
{		
	//GROUP DATA ADD 
	public function MJ_gmgt_add_group($data,$member_image_url)
	{		
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
		$groupdata['group_name']=MJ_gmgt_strip_tags_and_stripslashes($data['group_name']);
		$groupdata['group_description']=MJ_gmgt_strip_tags_and_stripslashes($data['group_description']);
		$groupdata['gmgt_groupimage']=$member_image_url;
		$groupdata['created_date']=date("Y-m-d");
		$groupdata['created_by']=get_current_user_id();		
	
		if($data['action']=='edit')
		{
			$groupid['id']=$data['group_id'];
			$result=$wpdb->update( $table_group, $groupdata ,$groupid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_group, $groupdata );
			if($result)
				$result=$wpdb->insert_id;
			return $result;
		}	
	}
	//get all group
	public function MJ_gmgt_get_all_groups()
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
	
		$result = $wpdb->get_results("SELECT * FROM $table_group");
		return $result;
	
	}
	//get all group by created_by
	public function MJ_gmgt_get_all_groups_by_created_by($user_id)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
	
		$result = $wpdb->get_results("SELECT * FROM $table_group where created_by=$user_id");
		return $result;
	
	}
	//get member all group 
	public function MJ_gmgt_get_member_all_groups($user_id)
	{
		global $wpdb;
		$table_groupmember = $wpdb->prefix. 'gmgt_groupmember';
		$table_group = $wpdb->prefix. 'gmgt_groups';
		
		$group_data = $wpdb->get_results("SELECT group_id FROM $table_groupmember where member_id=$user_id");
		$group_array=array();
		if(!empty($group_data))
		{
			foreach ($group_data as $retrieved_data)
			{
				$group_array[]=$retrieved_data->group_id;
			}
		}	
		
		$result = $wpdb->get_results("SELECT * FROM $table_group where id IN (". implode(',', array_map('intval', $group_array)).")");
		
		return $result;
	
	}
	//get single group
	public function MJ_gmgt_get_single_group($id)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
		$result = $wpdb->get_row("SELECT * FROM $table_group where id=".$id);
		return $result;
	}
	//delete group
	public function MJ_gmgt_delete_group($id)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
		$result = $wpdb->query("DELETE FROM $table_group where id= ".$id);
		return $result;
	}
	//count group members
	function MJ_gmgt_count_group_members($id)
	{		
		global $wpdb;
		$table_gmgt_groupmember = $wpdb->prefix. 'gmgt_groupmember';
		$result = $wpdb->get_var("SELECT count(member_id) FROM $table_gmgt_groupmember where group_id=".$id);
		return $result;		
	}
	//update group images
	function MJ_gmgt_update_groupimage($id,$imagepath)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
		$image['gmgt_groupimage']=$imagepath;
		$groupid['id']=$id;
		return $result=$wpdb->update( $table_group, $image, $groupid);
	}	
	//get all group
	public function MJ_gmgt_get_all_groups_dashboard()
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
	
		$result = $wpdb->get_results("SELECT * FROM $table_group ORDER BY id DESC limit 3");
		return $result;
	
	}
	//get member all group  dashboard
	public function MJ_gmgt_get_member_all_groups_dashboard($user_id)
	{
		global $wpdb;
		$table_groupmember = $wpdb->prefix. 'gmgt_groupmember';
		$table_group = $wpdb->prefix. 'gmgt_groups';
		
		$group_data = $wpdb->get_results("SELECT group_id FROM $table_groupmember where member_id=$user_id");
		$group_array=array();
		if(!empty($group_data))
		{
			foreach ($group_data as $retrieved_data)
			{
				$group_array[]=$retrieved_data->group_id;
			}
		}	
		
		$result = $wpdb->get_results("SELECT * FROM $table_group where id IN (". implode(',', array_map('intval', $group_array)).")ORDER BY id DESC limit 3");
		
		return $result;
	}
	//get all group by created_by dashboard
	public function MJ_gmgt_get_all_groups_by_created_by_dashboard($user_id)
	{
		global $wpdb;
		$table_group = $wpdb->prefix. 'gmgt_groups';
	
		$result = $wpdb->get_results("SELECT * FROM $table_group where created_by=$user_id ORDER BY id DESC limit 3");
		return $result;
	
	}
}
//GROUP CLASS END
?>