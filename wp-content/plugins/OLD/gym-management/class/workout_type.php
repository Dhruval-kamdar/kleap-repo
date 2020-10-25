<?php 
//WORKOUT TYPE CLASS
class MJ_Gmgtworkouttype
{	
    //ADD WORKOUT FUNCTION
	public function MJ_gmgt_add_workouttype($data)
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_assign_workout';
		$workoutdata['user_id']=$data['member_id'];
		$workoutdata['level_id']=$data['level_id'];
		$workoutdata['description']=MJ_gmgt_strip_tags_and_stripslashes($data['description']);		
		$workoutdata['start_date']=MJ_gmgt_get_format_for_db($data['start_date']);		
		$workoutdata['end_date']= MJ_gmgt_get_format_for_db($data['last_date']);
		$workoutdata['created_date']=date("Y-m-d");
		$workoutdata['created_by']=get_current_user_id();
		$new_array = array();
		$i = 0;
		$phpobj = array();
		if(!empty($data['activity_list']))
		{
			foreach($data['activity_list'] as $val)
			{
				$data_value = json_decode($val);
				$phpobj[] = json_decode(stripslashes($val),true);
			}
		}
		$j=0;
		$final_array = array();
		$resultarray =array();
		foreach($phpobj as $key => $val)
		{			
			$day = array();
			$activity = array();
			foreach($val as $key=>$key_val)
			{				
				if($key == "days")
				foreach($key_val as $val1)
				{
					$day['day'][] =$val1['day_name'] ;
				}
				if($key == "activity")
					foreach($key_val as $val2)
					{						
						echo $val2['activity']['activity'];
						$activity['activity'][] =array('activity'=>$val2['activity']['activity'],
													'reps'=>$val2['activity']['reps'],
													'sets'=>$val2['activity']['sets'],
													'kg'=>$val2['activity']['kg'],
													'time'=>$val2['activity']['time'],
						) ;
					}
			}
			$resultarray[] = array_merge($day, $activity);
		}
		if($data['action']=='edit')
		{
			$workoutid['id']=$data['assign_workout_id'];	
			$result=$wpdb->update( $table_workout, $workoutdata ,$workoutid);
			return $result;
		}
		else
		{
			
			$result=0;
			if(!empty($phpobj)){
				$result=$wpdb->insert( $table_workout, $workoutdata );
				$assign_workout_id = $wpdb->insert_id;
				$this->MJ_gmgt_assign_workout_detail($assign_workout_id,$resultarray);
				
				
			//SEND WORKOUT MAIL NOTIFICATION
			$userdata=get_userdata($data['member_id']);
			$username=$userdata->display_name;
			$useremail=$userdata->user_email;
			
			 $gymname=get_option( 'gmgt_system_name' );
		    $page_link='<a href='.home_url().'?dashboard=user&page=assign-workout>View Workout</a>';
			$arr['[GMGT_MEMBERNAME]']=$username;	
			$arr['[GMGT_GYM_NAME]']=$gymname;
			$arr['[GMGT_STARTDATE]']=$workoutdata['start_date'];
			$arr['[GMGT_ENDDATE]']=$workoutdata['end_date'];
			$arr['[GMGT_PAGE_LINK]']=$page_link;
			$subject =get_option('Assign_Workouts_Subject');			
			$sub_arr['[GMGT_GYM_NAME]']=$gymname;
			$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
			$message = get_option('Assign_Workouts_Template');
			$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
            $invoice=MJ_Assign_Workouts_Add_Html_Content($assign_workout_id);
						
            $invoic_concat=$message_replacement. $invoice;			
			$to[]=$useremail;
		    MJ_gmgt_send_mail_text_html($to,$subject,$invoic_concat);
			
			//SEND WORKOUT MAIL NOTIFICATION END
			}
			return $result;
		}
	
	}
	//ASIGN WORKOUT DETAILS FUNCTION
	public function MJ_gmgt_assign_workout_detail($workout_id,$work_outdata)
	{
		//get_userdata
		if(!empty($work_outdata))
		{
			global $wpdb;
			$table_workout = $wpdb->prefix. 'gmgt_workout_data';
			$workout_data = array();
			foreach($work_outdata as  $value)
			{				
				foreach($value['day'] as $day)
				{
					echo "day".$day;
					foreach($value['activity']  as $actname)
					{						
						$workout_data['day_name'] = $day;
						$workout_data['workout_name'] = $actname['activity'];
						$workout_data['sets'] = $actname['sets'];
						$workout_data['reps'] = $actname['reps'];
						$workout_data['kg'] = $actname['kg'];
						$workout_data['time'] = $actname['time'];
						$workout_data['workout_id'] = $workout_id;
						$workout_data['created_date'] = date("Y-m-d");
						$workout_data['create_by'] = get_current_user_id();
						$result=$wpdb->insert( $table_workout, $workout_data );
					}
				}
				
			}			
		}
	}
	//GET ALL ASIGN WORKOUT  FUNCTION
	public function MJ_gmgt_get_all_assignworkout()
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_assign_workout';
	
		$result = $wpdb->get_results("SELECT * FROM $table_workout");
		return $result;
	}	
	//GET ALL ASIGN WORKOUT TYPE  FUNCTION
	public function MJ_gmgt_get_all_workouttype()
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_workouts';
	
		$result = $wpdb->get_results("SELECT * FROM $table_workout");
		return $result;
	
	}
	//GET OWN ASIGN WORKOUT   FUNCTION
	public function MJ_gmgt_get_own_assigned_workout($role,$id)
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_workouts';
		if($role=='member')
			$result = $wpdb->get_results("SELECT * FROM $table_workout where member_id=".$id);
		else
			$result = $wpdb->get_results("SELECT * FROM $table_workout where created_by=".$id);
		return $result;
	}
	
	//GET SINGE  ASIGN WORKOUT  TYPE  FUNCTION
	public function MJ_gmgt_get_single_workouttype($id)
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_workouts';
		$result = $wpdb->get_row("SELECT * FROM $table_workout where id=".$id);
		return $result;
	}
	public function MJ_gmgt_get_assigned_workout($id)
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_workouts';
		$result = $wpdb->get_results("SELECT * FROM $table_workout where member_id=".$id);
		return $result;
	}
	//DELETE WORKOUT TYPE FUNCTION
	public function MJ_gmgt_delete_workouttype($id)
	{
		global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_workouts';
		$result = $wpdb->query("DELETE FROM $table_workout where id= ".$id);
		return $result;
	}
	//GET SINGLE WORKOUT DATA 
	public function MJ_gmgt_get_single_workoutdata($id)
	{
		global $wpdb;
		$table_workout = $wpdb->prefix.'gmgt_workout_data';
		$result = $wpdb->get_row("SELECT *FROM $table_workout where id=".$id);
		
		return $result;
	}
}
//END CLASS 
?>