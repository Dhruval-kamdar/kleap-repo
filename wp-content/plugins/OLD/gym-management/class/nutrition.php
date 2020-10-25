<?php   
//Nutrition CLASS START 
class MJ_Gmgtnutrition
{	
	//ADD Nutrition DATA
	public function MJ_gmgt_add_nutrition($data)
	{
		global $wpdb;
		$table_nutrition = $wpdb->prefix. 'gmgt_nutrition';
		$nutritiondata['user_id']=$data['member_id'];		
		$nutritiondata['start_date']=date('Y-m-d',strtotime($data['start_date']));
		$nutritiondata['expire_date']=date('Y-m-d',strtotime($data['end_date']));
		$nutritiondata['created_date']=date("Y-m-d");
		$nutritiondata['created_by']=get_current_user_id();
		
		$phpobj = array();
		foreach($data['nutrition_list'] as $val)
		{
			$data_value = json_decode($val);
						
			$phpobj[] = json_decode(stripslashes($val),true);	
			
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
					$activity['activity'][] =array('activity'=>$val2['activity']['activity'],
												'value'=>stripslashes($val2['activity']['value'])										
					);
				}				
			}
			$resultarray[] = array_merge($day, $activity);
		}
		
		if($data['action']=='edit')
		{
			$productid['id']=$data['nutrition_id'];
			$result=$wpdb->update( $table_nutrition, $nutritiondata ,$productid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_nutrition, $nutritiondata );
			$nutrition_id = $wpdb->insert_id;
			$this->MJ_gmgt_nutrition_detail($nutrition_id,$resultarray);
				
			$userdata=get_userdata($data['member_id']);
			
			$username=$userdata->display_name;
			$useremail=$userdata->user_email;
			
			 $gymname=get_option( 'gmgt_system_name' );
			
		    $page_link='<a href='.home_url().'?gym-management/?dashboard=user&page=nutrition>View Nutrition</a>';
			$arr['[GMGT_MEMBERNAME]']=$username;	
			$arr['[GMGT_GYM_NAME]']=$gymname;
			$arr['[GMGT_STARTDATE]']=$nutritiondata['start_date'];
			$arr['[GMGT_ENDDATE]']=$nutritiondata['expire_date'];
			$arr['[GMGT_PAGE_LINK]']=$page_link;
			
			$subject =get_option('Assign_Nutrition_Schedule_Subject');
			
			$sub_arr['[GMGT_GYM_NAME]']=$gymname;
			$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
			$message = get_option('Assign_Nutrition_Schedule_Template');
			$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);			
            $invoice=MJ_asign_nutristion_content_send_mail($nutrition_id);		 
            $invoic_concat=$message_replacement. $invoice;				
			$to[]=$useremail;
		    MJ_gmgt_send_mail_text_html($to,$subject,$invoic_concat);
			//userragistation mail end			
			return $result;
		}	
	}
	//nutrition details
	public function MJ_gmgt_nutrition_detail($workout_id,$work_outdata)
	{
		if(!empty($work_outdata))
		{
			global $wpdb;
			$table_workout = $wpdb->prefix. 'gmgt_nutrition_data';
			$workout_data = array();
			foreach($work_outdata as  $value)
			{				
				foreach($value['day'] as $day)
				{					
					foreach($value['activity']  as $actname)
					{						
						$workout_data['day_name'] = $day;
						$workout_data['nutrition_time'] = $actname['activity'];
						$workout_data['nutrition_value'] = $actname['value'];
					
						$workout_data['nutrition_id'] = $workout_id;
						$workout_data['created_date'] = date("Y-m-d");
						$workout_data['create_by'] = get_current_user_id();
						$result=$wpdb->insert( $table_workout, $workout_data );
					}
				}				
			}
		}
	}
	//get all nutrition
	public function MJ_gmgt_get_all_nutrition()
	{
		global $wpdb;
		$table_nutrition = $wpdb->prefix. 'gmgt_nutrition';
		$result = $wpdb->get_results("SELECT * FROM $table_nutrition");
		return $result;	
	}
	//get single nutrition
	public function MJ_gmgt_get_single_nutrition($id)
	{
		global $wpdb;
		$table_nutrition = $wpdb->prefix. 'gmgt_nutrition';
		$result = $wpdb->get_row("SELECT * FROM $table_nutrition where id=".$id);
		return $result;
	}
	//delete nutrition
	public function MJ_gmgt_delete_nutrition($id)
	{
		global $wpdb;
		$table_nutrition = $wpdb->prefix. 'gmgt_nutrition';
		$result = $wpdb->query("DELETE FROM $table_nutrition where id= ".$id);
		return $result;
	}
}
//Nutrition CLASS END
?>