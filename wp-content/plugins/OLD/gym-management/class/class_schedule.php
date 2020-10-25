<?php 
//CLASS SCHEDULE CLASS START   
class MJ_Gmgtclassschedule extends  MJ_Gmgtmembership
{	
	//ADD CLASS DATA  
	public function MJ_gmgt_add_class($data)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$tbl_membership_class = $wpdb->prefix. 'gmgt_membership_class';
		$classdata['class_name']=MJ_gmgt_remove_tags_and_special_characters($data['class_name']);
		$classdata['staff_id']=$data['staff_id'];
		$classdata['asst_staff_id']=$data['asst_staff_id'];
		$classdata['day']=json_encode($data['day']);
		$classdata['staff_id']=$data['staff_id'];
		$classdata['class_creat_date']=date("Y-m-d");
		$classdata['class_created_id']=get_current_user_id();
        $classdata['start_date']=MJ_gmgt_get_format_for_db($data['start_date']);
		$classdata['end_date']=MJ_gmgt_get_format_for_db($data['end_date']);
		$classdata['color']=$data['class_color'];
		$classdata['member_limit']=$data['member_limit'];		
		
		if($data['action']=='edit')
		{
			$classdata['start_time']=$data['start_time'].':'.$data['start_min'].':'.$data['start_ampm'];
			$classdata['end_time']=$data['end_time'].':'.$data['end_min'].':'.$data['end_ampm'];
		
			$classid['class_id']=$data['class_id'];
			
			$result=$wpdb->update( $table_class, $classdata ,$classid);
			
			$new_membership =isset($data['membership_id'])?$data['membership_id']:array();
			$old_membership = $this->MJ_gmgt_old_membership($data['class_id']);
			
			$different_insert = array_diff($new_membership,$old_membership);
			$different_delete = array_diff($old_membership,$new_membership);
			
			if(!empty($different_insert))	
			{
				$membershipdata['class_id']=$data['class_id'];
				foreach($different_insert as $membership_id)
				{
					$membershipdata['membership_id']=$membership_id;
					$wpdb->insert($tbl_membership_class,$membershipdata);
				}
			}	
			if(!empty($different_delete))
			{
				foreach($different_delete as $membership_id)
				{	
					$wpdb->delete( $tbl_membership_class, array( 'membership_id' => $membership_id ) );
				}
			}	
			return $result;
		}
		else
		{			
			if(!empty($data['start_time']))
			{
				foreach($data['start_time'] as $key=>$start_time)
				{
					$classdata['start_time']=$start_time.':'.$data['start_min'][$key].':'.$data['start_ampm'][$key];
					$classdata['end_time']=$data['end_time'][$key].':'.$data['end_min'][$key].':'.$data['end_ampm'][$key];
					$result=$wpdb->insert($table_class,$classdata);	
					
					$classmeta=array();
					$classmeta['class_id'] = $wpdb->insert_id;
					if(!empty($data['membership_id']))
					{
						foreach($data['membership_id'] as $membership_id)
						{
							$classmeta['membership_id']=$membership_id;
							$result=$wpdb->insert( $tbl_membership_class, $classmeta );								
						}
					}	
				}
			}	
			
			return $result;
		}
	}
	//get all classes//
	public function MJ_gmgt_get_all_classes()
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$result = $wpdb->get_results("SELECT * FROM $table_class");
		return $result;
	
	}
	//get all classes by created by//
	public function MJ_gmgt_get_all_classes_by_class_created_id($user_id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$result = $wpdb->get_results("SELECT * FROM $table_class where class_created_id=$user_id");
		return $result;
	
	}
	//get all classes by staffmember//
	public function MJ_gmgt_get_all_classes_by_staffmember($user_id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$result = $wpdb->get_results("SELECT * FROM $table_class where staff_id=$user_id And asst_staff_id=$user_id");
		return $result;
	
	}
	//get all classes by member//
	public function MJ_gmgt_get_all_classes_by_member($cur_user_class_id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		if(!empty($cur_user_class_id))
		{
			$newarray = implode(", ", $cur_user_class_id);
			$result = $wpdb->get_results("SELECT * FROM $table_class where class_id IN ($newarray)");
		}
		return $result;
	
	}
	// get class by staff member//
	public function MJ_gmgt_getClassesByStaffmeber($id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$ClassData = $wpdb->get_results("SELECT * FROM $table_class where staff_id=$id");
		
		if(!empty($ClassData))
		{
			foreach($ClassData as $key=>$class_id)
			{
				$classids[]= $class_id->class_id;
			}
			return $classids;
		}	
	}	
	//get single class//
	public function MJ_gmgt_get_single_class($id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$result = $wpdb->get_row("SELECT * FROM $table_class where class_id=".$id);
		return $result;
	}
	//get class name//
	public function MJ_gmgt_get_class_name($id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$result = $wpdb->get_row("SELECT class_name FROM $table_class where class_id=".$id);
		return $result->class_name;
	}
	//delete class//
	public function MJ_gmgt_delete_class($id)
	{
		global $wpdb;
		$tbl_membership_class = $wpdb->prefix. 'gmgt_membership_class';
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$result = $wpdb->query("DELETE FROM $table_class where class_id= ".$id);		
		$result = $wpdb->query("DELETE FROM $tbl_membership_class where class_id= ".$id);
		return $result;
	}
	//get sedule by day//
	public function MJ_gmgt_get_schedule_byday($day)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$resultdata = $wpdb->get_results("SELECT * FROM $table_class ORDER BY start_time  ASC");		
		$day_array[]=array();
		foreach($resultdata as  $result)
		{				
			$class_days=json_decode($result->day);
			$class_days = isset($class_days)?$class_days:array();
			if(in_array($day,$class_days))
			{
				$day_array[]=array('dayname'=>$day,'start_time'=>$result->start_time,'end_time'=>$result->end_time,'class_id'=>$result->class_id,'staff_id'=>$result->staff_id,'asst_staff_id'=>$result->asst_staff_id,'class_created_id'=>$result->class_created_id);
			}
		}
		return $day_array;
	}
	//get class member//
	function MJ_gmgt_get_class_members($class_id)
	{
		global $wpdb;		
		$tbl_membership_class = $wpdb->prefix. 'gmgt_membership_class';
		return $wpdb->get_results("SELECT * FROM $tbl_membership_class WHERE class_id=$class_id");
	}
	//get old membership//
	function MJ_gmgt_old_membership($class_id)
	{
		global $wpdb;		
		$tbl_membership_class = $wpdb->prefix. 'gmgt_membership_class';
		$reesult = $wpdb->get_results("SELECT * FROM $tbl_membership_class WHERE class_id=$class_id");
		$data=array();
		if(!empty($reesult))
		{
			foreach($reesult as $key=>$val)
			{
				$data[]=$val->membership_id;
			}
		}
		return $data;
	}
	//member book class//
	public function MJ_gmgt_get_member_book_class($member_id)
	{		
		global $wpdb;
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';	
		$membership_id = get_user_meta($member_id,'membership_id',true);
		 $sql ="SELECT * FROM $table_booking_class WHERE member_id=$member_id AND membership_id=$membership_id";
		return $wpdb->get_results($sql);
	}
	
	//get all class in admin side//
	public function MJ_gmgt_get_all_booked_class()
	{		
		global $wpdb;
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';	
		$sql ="SELECT * FROM $table_booking_class";
		return $wpdb->get_results($sql);
	}
	//end get all class in admin side//
	
	//delete booked class by id //
	public function MJ_gmgt_delete_booked_class($id)
	{
		global $wpdb;
		$gmgt_booking_class = $wpdb->prefix. 'gmgt_booking_class';
		$result = $wpdb->query("DELETE FROM $gmgt_booking_class where id= ".$id);
		return $result;
	}
	
	//book class function for fronted side calendar//
	public function MJ_gmgt_booking_class($class_id,$dayname,$class_date,$action,$transfer_class_id,$class_date_date)
	{
		global $wpdb;
		$result = array();
		$bookingdata = array();
		$bookingdata['member_id']=get_current_user_id();	
		$member_id=get_current_user_id();
		$bookingdata['class_id']=$class_id;	
		$bookingdata['booking_date']=date('Y-m-d');
		$class_booking_date=$class_date_date;
		$bookingdata['class_booking_date']=$class_date_date;
		$userdata = get_userdata(get_current_user_id());		
		$membership_id = get_user_meta($userdata->ID,'membership_id',true);
		$membershipdata = $this->MJ_gmgt_get_single_membership($membership_id);	
		$bookingdata['membership_id']=$membership_id;
		$bookingdata['booking_day']=$dayname;		
		$userd_class = MJ_gmgt_get_user_used_membership_class($membership_id,get_current_user_id());
		$date = date('Y-m-d',strtotime(get_user_meta(get_current_user_id(),'end_date',true)));
		$begin_date = date('Y-m-d',strtotime(get_user_meta(get_current_user_id(),'begin_date',true)));
		$valid_class=$this->MJ_gmgt_get_booking_class_by_date_and_time(get_current_user_id(),$class_id,$dayname,$bookingdata['class_booking_date']);	
		$total_class_with_credit_limit=$membershipdata->on_of_classis;
		$classis_limit=$membershipdata->classis_limit;
		if(empty($valid_class))
		{		
	        if ( $class_booking_date >= $begin_date && $class_booking_date <= $date)
			{
				if( $class_booking_date <= $date )
				{
					
					   $table_booking_class = $wpdb->prefix. 'gmgt_booking_class';
					   
						if($action == 'book_now')
						{
							if($classis_limit=='unlimited')
							{
								$insert = $wpdb->insert($table_booking_class,$bookingdata);
								if($insert)
								{
									$result =__('Class book successfully.','gym_mgt');
								}
							}
							else	
							{							
								if($userd_class < $total_class_with_credit_limit)
								{
									$insert = $wpdb->insert($table_booking_class,$bookingdata);
									if($insert)
									{
										$result =__('Class book successfully.','gym_mgt');
									}
								} 
								else
								{			
									$result = __('Class Limit is over.','gym_mgt');			
								}
							}
						}
				} 
				else
				{
					$result=__('Your Membership is expire.','gym_mgt');
				}
			} 
			else
			{
				$result=__('Your Booking day is not between membership period.','gym_mgt');
			}
	    }
		else
		{
			$result=__('Already Book Class In This Date And Time.','gym_mgt');
		}
		return $result;
	}
	//check  booking class//
	public function MJ_gmgt_get_booking_class_by_date_and_time($member_id,$class_id,$dayname,$class_booking_date)
	{
		global $wpdb;		
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';	
		return $wpdb->get_results("SELECT * FROM $table_booking_class WHERE class_id=$class_id and member_id=$member_id and booking_day='$dayname' and class_booking_date='$class_booking_date'");
	}
	//get all class by membership//
	public function MJ_gmgt_get_all_classes_by_user_membership()
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$tbl_membership_class = $wpdb->prefix. 'gmgt_membership_class';
		$user_id=get_current_user_id();
		$membership_id = get_user_meta( $user_id,'membership_id',true ); 
		
		$class_data = $wpdb->get_results("SELECT class_id FROM $tbl_membership_class where membership_id=$membership_id");
		if(!empty($class_data))
		{
			$class_id_array=array();
			foreach($class_data as $data)
			{
				$class_id_array[]=$data->class_id;
			}
			$newarray = implode(", ", $class_id_array);
			$result = $wpdb->get_results("SELECT * FROM $table_class where class_id IN ($newarray)");
			return $result;		
		}
	}
	//end get all class by membership//
	//get all classes//
	public function MJ_gmgt_get_book_class_bydate($class_id,$class_date)
	{
		global $wpdb;
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';
		$result = $wpdb->get_var("SELECT COUNT(*) FROM $table_booking_class WHERE class_id=$class_id AND class_booking_date='$class_date'");
		return $result;
	}
	//get all classes Dashboard//
	public function MJ_gmgt_get_all_classes_dashboard()
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$result = $wpdb->get_results("SELECT * FROM $table_class ORDER BY class_id DESC limit 3");
		return $result;
	
	}
	//get all class in admin side dashboard//
	public function MJ_gmgt_get_all_booked_class_dashboard()
	{		
		global $wpdb;
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';	
		$sql ="SELECT * FROM $table_booking_class ORDER BY id DESC limit 3";
		return $wpdb->get_results($sql);
	}
	//get all class in admin side dashboard//
	public function MJ_gmgt_get_single_booked_class_($id)
	{		
		global $wpdb;
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';	
		$result = $wpdb->get_row("SELECT * FROM $table_booking_class where id=".$id);
		return $result;
	}
	//get all classes by staffmember//
	public function MJ_gmgt_get_all_classes_by_staffmember_dashboard($user_id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$result = $wpdb->get_results("SELECT * FROM $table_class where staff_id=$user_id And asst_staff_id=$user_id ORDER BY class_id DESC limit 3");
		return $result;
	
	}
	//get all classes by member//
	public function MJ_gmgt_get_all_classes_by_member_dashboard($cur_user_class_id)
	{
		 
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		if(!empty($cur_user_class_id))
		{
			$newarray = implode(", ", $cur_user_class_id);
			$result = $wpdb->get_results("SELECT * FROM $table_class where class_id IN ($newarray) ORDER BY class_id DESC limit 3");
		}
		
		return $result;
	
	}
	//get all classes by created by//
	public function MJ_gmgt_get_all_classes_by_class_created_id_dashboard($user_id)
	{
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	
		$result = $wpdb->get_results("SELECT * FROM $table_class where class_created_id=$user_id ORDER BY class_id DESC limit 3");
		return $result;
	
	}
	//member book class//
	public function MJ_gmgt_get_member_book_class_dashboard($member_id)
	{		
		global $wpdb;
		$table_booking_class = $wpdb->prefix. 'gmgt_booking_class';	
		$membership_id = get_user_meta($member_id,'membership_id',true);
		$current_date=date('Y-m-d');
		 $sql ="SELECT * FROM $table_booking_class WHERE member_id=$member_id AND membership_id=$membership_id AND class_booking_date >= '$current_date' ORDER BY class_booking_date ASC limit 3";
		return $wpdb->get_results($sql);
	}
}
//CLASS SCHEDULE CLASS END  
?>