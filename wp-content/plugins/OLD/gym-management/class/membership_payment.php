<?php 
//MEMBERSHIP Payment CLASS START 	  
class MJ_Gmgt_membership_payment
{	
	//MEMBERSHIP Payment DATA ADD
	public function MJ_gmgt_add_membership_payment($data)
	{			
		global $wpdb;
		$table_gmgt_membership_payment=$wpdb->prefix.'Gmgt_membership_payment';
		$tbl_gmgt_member_class = $wpdb->prefix .'gmgt_member_class';	
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$payment_data['member_id']=$data['member_id'];
		$payment_data['membership_id']=$data['membership_id'];
		$payment_data['membership_fees_amount'] = MJ_gmgt_get_membership_price($data['membership_id']);
		$payment_data['membership_signup_amount'] = MJ_gmgt_get_membership_signup_amount($data['membership_id']);
		$payment_data['tax_amount'] = MJ_gmgt_get_membership_tax_amount($data['membership_id']);		
		$payment_data['membership_amount']=$data['membership_amount'];				
		$payment_data['start_date']=MJ_gmgt_get_format_for_db($data['start_date']);	
		$payment_data['end_date']=MJ_gmgt_get_format_for_db($data['end_date']);			
		
		$membershipclass = MJ_gmgt_get_class_id_by_membership_id($data['membership_id']);
		$DaleteWhere['member_id']=$data['member_id'];
		
		$wpdb->delete( $tbl_gmgt_member_class, $DaleteWhere);
		$inserClassData['member_id']=$data['member_id'];
		if($membershipclass)
		{
			foreach($membershipclass as $key=>$class_id)
			{
				$inserClassData['class_id']=$class_id;
				$inset = $wpdb->insert($tbl_gmgt_member_class,$inserClassData);				
			}
		}		
		update_user_meta($data['member_id'],'membership_id',$data['membership_id']);		
		update_user_meta( $data['member_id'],'begin_date',MJ_gmgt_get_format_for_db($data['start_date']));	
		update_user_meta( $data['member_id'],'end_date',MJ_gmgt_get_format_for_db($data['end_date']));
	
		$payment_data['created_by']=get_current_user_id();
		
		if($data['action']=='edit')
		{
			$whereid['mp_id']=$data['mp_id'];
			$paid_amount=$data['paid_amount'];
			$membership_amount=$data['membership_amount'];
			
			if($paid_amount >= $membership_amount)
			{
				$status='Fully Paid';
			}
			elseif($paid_amount > 0)
			{
				$status='Partially Paid';
			}
			else
			{
				$status= 'Unpaid';	
			}
			
			$payment_data['payment_status']=$status;
			
			$result=$wpdb->update( $table_gmgt_membership_payment, $payment_data ,$whereid);
			
			//save membership payment data into income table			
			$table_income=$wpdb->prefix.'gmgt_income_expense';
			
			$membership_name=MJ_gmgt_get_membership_name($data['membership_id']);
			$entry_array[]=array('entry'=>$membership_name,'amount'=>MJ_gmgt_get_membership_price($data['membership_id']));	
			$entry_array1[]=array('entry'=>__("Membership Signup Fee","gym_mgt"),'amount'=>MJ_gmgt_get_membership_signup_amount($data['membership_id']));	
			$entry_array_merge=array_merge($entry_array,$entry_array1);
			$incomedata['entry']=json_encode($entry_array_merge);	
			$incomedata['supplier_name']=$data['member_id'];			
			$incomedata['amount']=$data['membership_amount'];
			$incomedata['total_amount']=$data['membership_amount'];						
			$incomedata['payment_status']=$status;
			$incomedata['tax_id']=MJ_gmgt_get_membership_tax($data['membership_id']);
			$invoice_no['invoice_no']=$data['invoice_no'];
			
			$result=$wpdb->update( $table_income,$incomedata,$invoice_no); 
			
			return $result;
		}
		else
		{
			//invoice number generate
			$result_invoice_no=$wpdb->get_results("SELECT * FROM $table_income");						
			
			if(empty($result_invoice_no))
			{							
				$invoice_no='00001';
			}
			else
			{							
				$result_no=$wpdb->get_row("SELECT invoice_no FROM $table_income where invoice_id=(SELECT max(invoice_id) FROM $table_income)");
				$last_invoice_number=$result_no->invoice_no;
				$invoice_number_length=strlen($last_invoice_number);
				
				if($invoice_number_length=='5')
				{
					$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
				}
				else	
				{
					$invoice_no='00001';
				}				
			}
			
			$payment_data['invoice_no']=$invoice_no;
			$payment_data['payment_status']='Unpaid';
			$payment_data['created_date']=date('Y-m-d');
			$membership_status = 'continue';		
			$payment_data['membership_status'] = $membership_status;
			$result=$wpdb->insert( $table_gmgt_membership_payment,$payment_data);						
			
			//membership invoice mail send
			$insert_id=$wpdb->insert_id;
			$paymentlink=home_url().'?dashboard=user&page=membership_payment';
			$gymname=get_option( 'gmgt_system_name' );
			$userdata=get_userdata($data['member_id']);
			$arr['[GMGT_USERNAME]']=$userdata->display_name;	
			$arr['[GMGT_GYM_NAME]']=$gymname;
			$arr['[GMGT_PAYMENT_LINK]']=$paymentlink;
			$subject =get_option('generate_invoice_subject');
			$sub_arr['[GMGT_GYM_NAME]']=$gymname;
			$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
			$message = get_option('generate_invoice_template');	
			$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
			$to[]=$userdata->user_email;
			
			$type='membership_invoice';
			MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$insert_id,$type);
			
			//save membership payment data into income table			
			$table_income=$wpdb->prefix.'gmgt_income_expense';
			$membership_name=MJ_gmgt_get_membership_name($data['membership_id']);
			$entry_array[]=array('entry'=>$membership_name,'amount'=>MJ_gmgt_get_membership_price($data['membership_id']));	
			$entry_array1[]=array('entry'=>__("Membership Signup Fee","gym_mgt"),'amount'=>MJ_gmgt_get_membership_signup_amount($data['membership_id']));	
			$entry_array_merge=array_merge($entry_array,$entry_array1);
			$incomedata['entry']=json_encode($entry_array_merge);	
			  
			$incomedata['invoice_type']='income';
			$incomedata['invoice_label']=__("Fees Payment","gym_mgt");
			$incomedata['supplier_name']=$data['member_id'];
			$incomedata['invoice_date']=date('Y-m-d');
			$incomedata['receiver_id']=get_current_user_id();
			$incomedata['amount']=$data['membership_amount'];
			$incomedata['total_amount']=$data['membership_amount'];
			$incomedata['invoice_no']=$invoice_no;
			$incomedata['paid_amount']=0;
			$incomedata['tax_id']=MJ_gmgt_get_membership_tax($data['membership_id']);
			$incomedata['payment_status']='Unpaid';
			$result=$wpdb->insert( $table_income,$incomedata); 
			return $result;
		}
	}
	//add fees payment history //
	public function MJ_gmgt_add_feespayment_history($data)
	{		
		global $wpdb;
		$table_gmgt_membership_payment_history = $wpdb->prefix .'gmgt_membership_payment_history';
		
		$feedata['mp_id']=$data['mp_id'];
		$feedata['amount']=$data['amount'];
		$feedata['payment_method']=$data['payment_method'];	
		$feedata['payment_description']=$data['payment_description'];	
		if(isset($data['trasaction_id']))
		{
			$feedata['trasaction_id']=$data['trasaction_id'] ;
		}
		$feedata['paid_by_date']=date("Y-m-d");
		$feedata['created_by']=$data['created_by'];
		$paid_amount = $this->MJ_gmgt_get_paid_amount_by_feepayid($feedata['mp_id']);
		$membership_payment = $this->MJ_gmgt_get_membership_payments_by_mpid($feedata['mp_id']);
		
		$uddate_data['paid_amount'] = $paid_amount + $feedata['amount'];
		$uddate_data['mp_id'] = $data['mp_id'];
		
		$paid_amount=$uddate_data['paid_amount'];
		$membership_amount=$membership_payment->membership_amount;
		
		if($paid_amount >= $membership_amount)
		{
			$status='Fully Paid';
		}
		elseif($paid_amount > 0)
		{
			$status='Partially Paid';
		}
		else
		{
			$status= 'Unpaid';	
		}
			
		$uddate_data['payment_status'] = $status;
		
		$this->MJ_gmgt_update_paid_fees_amount($uddate_data);
		$result=$wpdb->insert( $table_gmgt_membership_payment_history,$feedata );
		$insert_id=$wpdb->insert_id;
		$payment_data=$this->MJ_gmgt_get_single_membership_payment($feedata['mp_id']);
		update_user_meta($payment_data->member_id,'membership_id',$payment_data->membership_id);
		update_user_meta( $payment_data->member_id,'begin_date',$payment_data->start_date);	
		update_user_meta( $payment_data->member_id,'end_date',$payment_data->end_date);
		update_user_meta( $payment_data->member_id,'membership_status','Continue');
		//payment success mail
		$gymname=get_option( 'gmgt_system_name' );
		$userdata=get_userdata($payment_data->member_id);
		$arr['[GMGT_USERNAME]']=$userdata->display_name;	
		$arr['[GMGT_GYM_NAME]']=$gymname;
		$subject =get_option('payment_received_against_invoice_subject');
		$sub_arr['[GMGT_GYM_NAME]']=$gymname;
		$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
		$message = get_option('payment_received_against_invoice_template');	
		$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
		$to[]=$userdata->user_email;
		
		$type='membership_invoice';
		
		MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$data['mp_id'],$type);
		return $result;		
	}
	//get paid amount by fees id
	public function MJ_gmgt_get_paid_amount_by_feepayid($mp_id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT paid_amount FROM $table_gmgt_membership_payment where mp_id = $mp_id");
		return $result->paid_amount;
	}
	//update paid fees amount
	public function MJ_gmgt_update_paid_fees_amount($data)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$feedata['paid_amount'] = $data['paid_amount'];
		$feedata['payment_status'] = $data['payment_status'];
		$fees_id['mp_id']=$data['mp_id'];
	
		$invoice_no['invoice_no']=$this->MJ_gmgt_get_invoice_no_by_mpid($fees_id['mp_id']);
		
		$result=$wpdb->update( $table_gmgt_membership_payment, $feedata ,$fees_id);
		$result_update_income=$wpdb->update( $table_income, $feedata ,$invoice_no);
		return $result;	
	}
	//get invoice no by membership id
	public function MJ_gmgt_get_invoice_no_by_mpid($mp_id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT invoice_no FROM $table_gmgt_membership_payment where mp_id=".$mp_id);
		return $result->invoice_no;
	}
	//get membership payment by membership id
	public function MJ_gmgt_get_membership_payments_by_mpid($mp_id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_gmgt_membership_payment where mp_id=".$mp_id);
		return $result;
	}
	//get all membership payment
	public function MJ_gmgt_get_all_membership_payment()
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';	
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment");
		return $result;	
	}
	//get all membership payment by member
	public function MJ_gmgt_get_all_membership_payment_byuserid($user_id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';	
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment where member_id=$user_id");
		return $result;	
	}
	//get all membership payment by member
	public function MJ_gmgt_get_all_membership_payment_by_member($user_id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';	
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment where member_id=$user_id");
		return $result;	
	}
	//get single membership payment
	public function MJ_gmgt_get_single_membership_payment($mp_id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_gmgt_membership_payment where mp_id=".$mp_id);
		return $result;
	}
	//delete payment
	public function MJ_gmgt_delete_payment($id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$invoice_no=$this->MJ_gmgt_get_invoice_no_bymp_id($id);
		
		$result = $wpdb->query("DELETE FROM $table_gmgt_membership_payment where mp_id= ".$id);
		
		$result_delete_income = $wpdb->query("DELETE FROM $table_income where invoice_no=".$invoice_no);
		
		return $result;
	}
	//get invoice no by membership id
	public function MJ_gmgt_get_invoice_no_bymp_id($id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT invoice_no FROM $table_gmgt_membership_payment where mp_id=".$id);
		return $result->invoice_no;
	}
	//get member subscription history
	public function MJ_gmgt_get_member_subscription_history($id)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment where member_id=".$id);
		return $result;
	}
	//get all member subscription history
	public function MJ_gmgt_get_all_member_subscription_history()
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment");
		return $result;
	}
	//check membership by or not
	public function MJ_gmgt_checkMembershipBuyOrNot($memberid,$joiningdate,$expiredate)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_gmgt_membership_payment where start_date='".$joiningdate."' and end_date='".$expiredate."' and member_id=".$memberid);
		return $result;
	}
}
//MEMBERSHIP Payment CLASS END
?>