<?php 	  
//PAYMENT CLASS START 
class MJ_Gmgtpayment
{	
	//ADD Payment DATA
	public function MJ_gmgt_add_payment($data)
	{
		global $wpdb;
		$table_payment=$wpdb->prefix.'gmgt_payment';
		$paymentdata['title']=MJ_gmgt_strip_tags_and_stripslashes($data['payment_title']);
		$paymentdata['member_id']=$data['member_id'];
		$paymentdata['due_date']=MJ_gmgt_get_format_for_db($data['due_date']);		
		$paymentdata['total_amount']=$data['total_amount'];
		$paymentdata['discount']=$data['discount'];
		$paymentdata['payment_status']=$data['payment_status'];		
		$paymentdata['description']=MJ_gmgt_strip_tags_and_stripslashes($data['description']);
		$paymentdata['payment_date']=date("Y-m-d");
		$paymentdata['receiver_id']=get_current_user_id();		
		
		if($data['action']=='edit')
		{
			$paymentid['payment_id']=$data['payment_id'];
			$result=$wpdb->update( $table_payment, $paymentdata ,$paymentid);
			return $result;
		}
		else
		{			
			$result=$wpdb->insert( $table_payment,$paymentdata);
			return $result;
		}	
	}
	//get all payment
	public function MJ_gmgt_get_all_payment()
	{
		global $wpdb;
		$table_payment = $wpdb->prefix. 'gmgt_payment';
	
		$result = $wpdb->get_results("SELECT * FROM $table_payment");
		return $result;	
	}
	//get single payment
	public function MJ_gmgt_get_single_payment($id)
	{
		global $wpdb;
		$table_payment = $wpdb->prefix. 'gmgt_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_payment where payment_id=".$id);
		return $result;
	}
	//get own payment
	public function MJ_gmgt_get_own_payment($id)
	{
		global $wpdb;
		$table_payment = $wpdb->prefix. 'gmgt_payment';
		$result = $wpdb->get_results("SELECT * FROM $table_payment where member_id=".$id);
		return $result;
	}
	//delete payment
	public function MJ_gmgt_delete_payment($id)
	{
		global $wpdb;
		$table_payment = $wpdb->prefix. 'gmgt_payment';
		$result = $wpdb->query("DELETE FROM $table_payment where payment_id= ".$id);
		return $result;
	}
	//--------Income entry----------------
	public function MJ_gmgt_get_entry_records($data)
	{
		$all_income_entry=$data['income_entry'];
		$all_income_amount=$data['income_amount'];
		
		$entry_data=array();
		$i=0;
		foreach($all_income_entry as $one_entry)
		{
			$entry_data[]= array('entry'=>MJ_gmgt_strip_tags_and_stripslashes($one_entry),
						'amount'=>$all_income_amount[$i]);
			$i++;		
		}
		return json_encode($entry_data);
	}
	//add income
	public function MJ_gmgt_add_income($data)
	{		
		$entry_value=$this->MJ_gmgt_get_entry_records($data);
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$incomedata['invoice_type']=$data['invoice_type'];
		$incomedata['invoice_label']=MJ_gmgt_strip_tags_and_stripslashes($data['invoice_label']);
		$incomedata['supplier_name']=MJ_gmgt_strip_tags_and_stripslashes($data['supplier_name']);	
		
		$incomedata['entry']=$entry_value;
		
		//count amount by entry
		$value=json_decode($entry_value);
		if(!empty($value))
		{
			foreach($value as $entry)
			{
				 $total+=$entry->amount;
			}
		}
		$incomedata['amount']=$total;
		$incomedata['discount']=$data['discount'];
		$incomedata['tax']=  0;
		
		if(isset($data['tax']))
		{
			$incomedata['tax_id']=implode(",",$data['tax']);
		}
		else
		{
			$incomedata['tax_id']=null;	
		}	
		
		$total_after_discount_amount= $total - $data['discount'];
		
		if(!empty($data['tax']))
		{
			$total_tax=0;
			foreach($data['tax'] as $tax_id)
			{
				$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
									
				$tax_amount=$total_after_discount_amount * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;				
			}
			
			$total_amount_withtax=$total_after_discount_amount + $total_tax;
		}
		else
		{
			$total_tax=0;			
			$total_amount_withtax=$total_after_discount_amount + $total_tax;
		}
			
		$incomedata['total_amount']=$total_amount_withtax;
		
		$incomedata['receiver_id']=get_current_user_id();
		
		if($data['action']=='edit')
		{
			$incomedata['invoice_no']=$data['invoice_no'];
			$income_dataid['invoice_id']=$data['income_id'];
			$incomedata['paid_amount']=$data['paid_amount'];
			
			$paid_amount=$data['paid_amount'];
			
			if($paid_amount == 0 || $paid_amount == 0.00)
			{
				$status="Unpaid";
			}
			elseif($paid_amount < $total_amount_withtax)
			{
				$status="Partially Paid";
			}
			elseif($paid_amount >= $total_amount_withtax)
			{
				$status="Fully Paid";
			}	
			
			$incomedata['payment_status']=$status;
			$result=$wpdb->update( $table_income, $incomedata ,$income_dataid);
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
			$incomedata['invoice_date']=MJ_gmgt_get_format_for_db($data['invoice_date']);
			$incomedata['invoice_no']=$invoice_no;
			$incomedata['paid_amount']=0;
			$incomedata['payment_status']='Unpaid';
			$incomedata['create_by']=get_current_user_id();
			$result=$wpdb->insert( $table_income,$incomedata);
			
			//income invoice mail send
			$insert_id=$wpdb->insert_id;
			
			$gymname=get_option( 'gmgt_system_name' );
			$userdata=get_userdata(MJ_gmgt_strip_tags_and_stripslashes($data['supplier_name']));
			
			$arr['[GMGT_USERNAME]']=$userdata->display_name;	
			$arr['[GMGT_GYM_NAME]']=$gymname;
			$subject =get_option('add_income_subject');
			$sub_arr['[GMGT_ROLE_NAME]']=implode(',', $userdata->roles);
			$sub_arr['[GMGT_GYM_NAME]']=$gymname;
			$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
			$message = get_option('add_income_template');	
			$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
			$to[]=$userdata->user_email;
	
			$type='income';
			MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$insert_id,$type);
			return $result;
			
		}
	}	
	//add income payment
	public function MJ_gmgt_add_income_payment($data)
	{
		global $wpdb;
		$income_data['mp_id']=$data['mp_id'];
		$income_data['amount']=$data['amount'];
		$income_data['payment_method']=$data['payment_method'];				
		$paid_amount_data= $this->MJ_gmgt_get_all_income_data_bymp_id($data['mp_id']);	
		$total_amount=$paid_amount_data->total_amount;
		$paid_amount=$paid_amount_data->paid_amount;
		$due_amount=$total_amount - $paid_amount;
		$total_paid_amount=	$paid_amount + $income_data['amount'];
		if($total_paid_amount == 0 || $total_paid_amount == 0.00)
		{
			$status="Unpaid";
		}
		elseif($total_paid_amount < $total_amount)
		{
			$status="Partially Paid";
		}
		elseif($total_paid_amount >= $total_amount)
		{
			$status="Fully Paid";
		}	
			
			$income['payment_status']=$status;
			$income['paid_amount']=$paid_amount + $income_data['amount'];
			$income_id['invoice_id']=$data['mp_id'];
		
			$table_income=$wpdb->prefix.'gmgt_income_expense';
			$table_gmgt_income_payment_history=$wpdb->prefix.'gmgt_income_payment_history';
			
			$result=$wpdb->update($table_income, $income,$income_id);
			
			$invoicedata=$this->MJ_gmgt_get_single_income_invoice_bymp_id($data['mp_id']);	
			
			if($invoicedata->invoice_label!='Sell Product' && $invoicedata->invoice_label!='Fees Payment')
			{				
				$incomedata['invoice_id']=$data['mp_id'];
				$incomedata['member_id']=$invoicedata->supplier_name;
				$incomedata['amount']=$data['amount'];
				$incomedata['payment_method']=$data['payment_method'];	
				$incomedata['payment_description']=$data['payment_description'];				
				if(isset($data['trasaction_id']))
				{
					$incomedata['trasaction_id']=$data['trasaction_id'] ;
				}
				$incomedata['paid_by_date']=date("Y-m-d");
				$incomedata['created_by']=$data['created_by'];
				
				$result=$wpdb->insert( $table_gmgt_income_payment_history,$incomedata );
				
				//payment success mail
				$gymname=get_option( 'gmgt_system_name' );
				$member_id=$invoicedata->supplier_name;
				$userdata=get_userdata($member_id);
				$arr['[GMGT_USERNAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$subject =get_option('payment_received_against_invoice_subject');
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message = get_option('payment_received_against_invoice_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				
				$type='income';
				MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$data['mp_id'],$type);
				return $result;
			}
			
			$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
			$table_gmgt_membership_payment_history = $wpdb->prefix .'gmgt_membership_payment_history';
			$gmgt_sales_payment_history = $wpdb->prefix. 'gmgt_sales_payment_history';
			
			$invoice_no=$this->MJ_gmgt_get_invoice_no_bymp_id($data['mp_id']);
			
			$membership_payment_id['mp_id']=$this->MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no);
			$membershipid=$this->MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no);
			$mp_id=$this->MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no);
			
			if(!empty($membershipid))
			{		
				$result=$wpdb->update($table_gmgt_membership_payment, $income,$membership_payment_id);
				
				$feedata['mp_id']=$mp_id;
				$feedata['amount']=$data['amount'];
				$feedata['payment_method']=$data['payment_method'];	
				$feedata['payment_description']=$data['payment_description'];	
				if(isset($data['trasaction_id']))
				{
					$feedata['trasaction_id']=$data['trasaction_id'] ;
				}
				$feedata['paid_by_date']=date("Y-m-d");
				$feedata['created_by']=$data['created_by'];
				
				$result=$wpdb->insert( $table_gmgt_membership_payment_history,$feedata );
				
				//payment success mail
				$gymname=get_option( 'gmgt_system_name' );
				$member_id=$this->MJ_gmgt_get_member_id_by_mp_id($mp_id);
				$userdata=get_userdata($member_id);
				$arr['[GMGT_USERNAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$subject =get_option('payment_received_against_invoice_subject');
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message = get_option('payment_received_against_invoice_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				
				$type='membership_invoice';
				MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$mp_id,$type);
				return $result;
			}
			
			$gmgt_sales_payment_history = $wpdb->prefix. 'gmgt_sales_payment_history';
			$table_sell = $wpdb->prefix. 'gmgt_store';
			$sell_product_data=$this->MJ_gmgt_get_sell_product_id_invoice_no($invoice_no);
			
			$sell_product_id['id']=$sell_product_data->id;
			$sellid=$sell_product_data->id;
			
			if(!empty($sellid))
			{				
				$result=$wpdb->update($table_sell, $income,$sell_product_id);
				
				$saledata['sell_id']=$sell_product_data->id;
				$saledata['member_id']=$sell_product_data->member_id;
				$saledata['amount']=$data['amount'];
				$saledata['payment_method']=$data['payment_method'];	
				$saledata['payment_description']=$data['payment_description'];	
				if(isset($data['trasaction_id']))
				{
					$saledata['trasaction_id']=$data['trasaction_id'] ;
				}
				$saledata['paid_by_date']=date("Y-m-d");
				$saledata['created_by']=$data['created_by'];
				
				$result=$wpdb->insert( $gmgt_sales_payment_history,$saledata );
				
				//payment success mail
				$gymname=get_option( 'gmgt_system_name' );
				$userdata=get_userdata($sell_product_data->member_id);
				$arr['[GMGT_USERNAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$subject =get_option('payment_received_against_invoice_subject');
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message = get_option('payment_received_against_invoice_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				
				$type='sell_invoice';
				MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$sell_product_data->id,$type);
				return $result;
			}
		return $result;			
	}
	// add income payment history
	public function MJ_gmgt_add_income_payment_history($data)
	{	
		global $wpdb;
		
		if($data['payment_method'] == "Stripe")
		{
		  $income_data['income_id']=$data['mp_id'];
	      
		}
		else
		{
			$income_data['income_id']=$data['income_id'];
		}
		$income_data['amount']=$data['amount'];
		$income_data['payment_method']=$data['payment_method'];
		$paid_amount_data= $this->MJ_gmgt_get_all_income_data_bymp_id($income_data['income_id']);
		
		$total_amount=$paid_amount_data->total_amount;
		$paid_amount=$paid_amount_data->paid_amount;
		
		$due_amount=$total_amount - $paid_amount;
		
		$total_paid_amount=	$paid_amount + $data['amount'];
		 
			if($total_paid_amount == 0 || $total_paid_amount == 0.00)
			{
				$status="Unpaid";
			}
			elseif($total_paid_amount < $total_amount)
			{
				$status="Partially Paid";
			}
			elseif($total_paid_amount >= $total_amount)
			{
				$status="Fully Paid";
			}	
		        $income['payment_status']=$status;
				$income['paid_amount']=$paid_amount + $data['amount'];
				$income_id['invoice_id']=$income_data['income_id'];
				
			$table_income=$wpdb->prefix.'gmgt_income_expense';
			$table_gmgt_income_payment_history=$wpdb->prefix.'gmgt_income_payment_history';
			
			$result=$wpdb->update($table_income, $income,$income_id);
			
			$invoicedata=$this->MJ_gmgt_get_single_income_invoice_bymp_id($income_data['income_id']);	
			
			if($invoicedata->invoice_label!='Sell Product' && $invoicedata->invoice_label!='Fees Payment')
			{				
				$incomedata['invoice_id']=$income_data['income_id'];
				$incomedata['member_id']=$invoicedata->supplier_name;
				$incomedata['amount']=$data['amount'];
				$incomedata['payment_method']=$data['payment_method'];	
				if(isset($data['trasaction_id']))
				{
					$incomedata['trasaction_id']=$data['trasaction_id'] ;
				}
				$incomedata['paid_by_date']=date("Y-m-d");
				$incomedata['created_by']=$data['created_by'];
				
				$result=$wpdb->insert( $table_gmgt_income_payment_history,$incomedata );
				
				//payment success mail
				$gymname=get_option( 'gmgt_system_name' );
				$member_id=$invoicedata->supplier_name;
				$userdata=get_userdata($member_id);
				$arr['[GMGT_USERNAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$subject =get_option('payment_received_against_invoice_subject');
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message = get_option('payment_received_against_invoice_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				
				$type='income';
				MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$income_data['income_id'],$type);
				return $result;
			}
			
			$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
			$table_gmgt_membership_payment_history = $wpdb->prefix .'gmgt_membership_payment_history';
			
			$invoice_no=$this->MJ_gmgt_get_invoice_no_bymp_id($income_data['income_id']);
			
			$membership_payment_id['mp_id']=$this->MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no);
			$membershipid=$this->MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no);
			$mp_id=$this->MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no);
			if(!empty($membershipid))
			{				
				$result=$wpdb->update($table_gmgt_membership_payment, $income,$membership_payment_id);
				
				$feedata['mp_id']=$mp_id;
				$feedata['amount']=$data['amount'];
				$feedata['payment_method']=$data['payment_method'];	
				if(isset($data['trasaction_id']))
				{
					$feedata['trasaction_id']=$data['trasaction_id'] ;
				}
				$feedata['paid_by_date']=date("Y-m-d");
				$feedata['created_by']=$data['created_by'];
				
				$result=$wpdb->insert( $table_gmgt_membership_payment_history,$feedata );
				
				//payment success mail
				$gymname=get_option( 'gmgt_system_name' );
				$member_id=$this->MJ_gmgt_get_member_id_by_mp_id($mp_id);
				$userdata=get_userdata($member_id);
				$arr['[GMGT_USERNAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$subject =get_option('payment_received_against_invoice_subject');
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message = get_option('payment_received_against_invoice_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				
				$type='membership_invoice';
				MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$mp_id,$type);
				return $result;
			}	
			
			$gmgt_sales_payment_history = $wpdb->prefix. 'gmgt_sales_payment_history';
			$table_sell = $wpdb->prefix. 'gmgt_store';
			$sell_product_data=$this->MJ_gmgt_get_sell_product_id_invoice_no($invoice_no);
			
			$sell_product_id['id']=$sell_product_data->id;
			$sellid=$sell_product_data->id;
			
			if(!empty($sellid))
			{				
				$result=$wpdb->update($table_sell, $income,$sell_product_id);
				
				$saledata['sell_id']=$sell_product_data->id;
				$saledata['member_id']=$sell_product_data->member_id;
				$saledata['amount']=$data['amount'];
				$saledata['payment_method']=$data['payment_method'];	
				if(isset($data['trasaction_id']))
				{
					$saledata['trasaction_id']=$data['trasaction_id'] ;
				}
				$saledata['paid_by_date']=date("Y-m-d");
				$saledata['created_by']=$data['created_by'];
				
				$result=$wpdb->insert( $gmgt_sales_payment_history,$saledata );
				
				//payment success mail
				$gymname=get_option( 'gmgt_system_name' );
				$userdata=get_userdata($sell_product_data->member_id);
				$arr['[GMGT_USERNAME]']=$userdata->display_name;	
				$arr['[GMGT_GYM_NAME]']=$gymname;
				$subject =get_option('payment_received_against_invoice_subject');
				$sub_arr['[GMGT_GYM_NAME]']=$gymname;
				$subject = MJ_gmgt_subject_string_replacemnet($sub_arr,$subject);
				$message = get_option('payment_received_against_invoice_template');	
				$message_replacement = MJ_gmgt_string_replacemnet($arr,$message);
				$to[]=$userdata->user_email;
				
				$type='sell_invoice';
				MJ_gmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$sell_product_data->id,$type);
				return $result;
			}			
		return $result;		
	}
	//get single income invoice by mp id 
	public function MJ_gmgt_get_single_income_invoice_bymp_id($mp_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_income where invoice_id=$mp_id");
		return $result;		
	}
	//get invoice number by mp id 
	public function MJ_gmgt_get_invoice_no_bymp_id($mp_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result = $wpdb->get_row("SELECT invoice_no FROM $table_income where invoice_id=$mp_id");
		return $result->invoice_no;		
	}
	//get sell id by income id
	public function MJ_gmgt_get_sell_id_by_income_id($invoice_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$table_sell = $wpdb->prefix. 'gmgt_store';
		
		$result_invoice_no = $wpdb->get_row("SELECT invoice_no FROM $table_income where invoice_id=$invoice_id");
		$invoice_no=$result_invoice_no->invoice_no;
		if(!empty($invoice_no))
		{
			$result = $wpdb->get_row("SELECT id FROM $table_sell where invoice_no=$invoice_no");
			return $result->id;	
		}
		else
		{
			$income_data= $wpdb->get_row("SELECT entry FROM $table_income where invoice_id=$invoice_id");
			$all_entry=json_decode($income_data->entry);
							
			if(!empty($all_entry))
			{
				foreach($all_entry as $entry)
				{
					$product_name=$entry->entry;
					$amount=$entry->amount;
					
					$obj_product=new MJ_Gmgtproduct;
					$product_data = $obj_product->MJ_gmgt_get_product_by_name($product_name);									
					
					$price=$product_data->price;
					$quentity=$amount/$price;	
					
					$product_id=$product_data->id;
				}
			}
			$result = $wpdb->get_row("SELECT id FROM $table_sell where product_id=$product_id and quentity=$quentity");
			return $result->id;	
		}	
			
	}
	//get fees id by income id
	public function MJ_gmgt_get_fees_id_by_income_id($invoice_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		
		$result_invoice_no = $wpdb->get_row("SELECT invoice_no FROM $table_income where invoice_id=$invoice_id");
		$invoice_no=$result_invoice_no->invoice_no;
		
		$result = $wpdb->get_row("SELECT mp_id FROM $table_gmgt_membership_payment where invoice_no=$invoice_no");
		return $result->mp_id;		
	}
	//get membership payment id invoice no
	public function MJ_gmgt_get_membership_payment_id_invoice_no($invoice_no)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT mp_id FROM $table_gmgt_membership_payment where invoice_no=$invoice_no");
		return $result->mp_id;		
	}
	//get mebership id by mp id
	public function MJ_gmgt_get_member_id_by_mp_id($mpid)
	{
		global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT member_id FROM $table_gmgt_membership_payment where mp_id=$mpid");
		return $result->member_id;		
	}
	//get sell product id invoice no
	public function MJ_gmgt_get_sell_product_id_invoice_no($invoice_no)
	{
		global $wpdb;
		$table_sell = $wpdb->prefix. 'gmgt_store';
		$result = $wpdb->get_row("SELECT * FROM $table_sell where invoice_no=$invoice_no");
		return $result;		
	}
	//get all income data by mp id
	public function MJ_gmgt_get_all_income_data_bymp_id($mp_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_income where invoice_type='income' and invoice_id=$mp_id");
		return $result;		
	}
	//get all income data by invoice number
	public function MJ_gmgt_get_all_income_data_byinvoice_number($invoice_mumber)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_income where invoice_type='income' and invoice_no=$invoice_mumber");
		return $result;		
	}
	//update income data by mp id
	public function MJ_gmgt_update_incomedata_bymp_id($mp_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_income where invoice_type='income' and invoice_id=$mp_id");
		return $result;		
	}
	//all income data
	public function MJ_gmgt_get_all_income_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income'");
		return $result;		
	}	
	//all income data by created_by
	public function MJ_gmgt_get_all_income_data_by_created_by($user_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND create_by=$user_id");
		return $result;		
	}	
	//get all income data by member
	public function MJ_gmgt_get_all_income_data_by_member()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$member_id=get_current_user_id();

		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND supplier_name=$member_id");
		return $result;		
	}
	public function MJ_gmgt_get_member_income_data($member_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' and supplier_name=$member_id");
		return $result;		
	}	
	//delete income
	public function MJ_gmgt_delete_income($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$table_sell = $wpdb->prefix. 'gmgt_store';
		
		$invoice_no=$this->MJ_gmgt_get_invoice_no_bymp_id($income_id);
		
		$result = $wpdb->query("DELETE FROM $table_income where invoice_id= ".$income_id);
		
		$result_delete_membership_payment = $wpdb->query("DELETE FROM $table_gmgt_membership_payment where invoice_no=".$invoice_no);
		
		$result_delete_sell_product = $wpdb->query("DELETE FROM $table_sell where invoice_no=".$invoice_no);
		return $result;
	}
	//delete expense
	public function MJ_gmgt_delete_expense($expense_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result = $wpdb->query("DELETE FROM $table_income where invoice_id= ".$expense_id);
		return $result;
	}
	//get income data by income id
	public function MJ_gmgt_get_income_data($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
	
		$result = $wpdb->get_row("SELECT * FROM $table_income where invoice_id= ".$income_id);
		return $result;
	}
	//-----------Expense-----------------
	public function MJ_gmgt_add_expense($data)
	{
		
		$entry_value=$this->MJ_gmgt_get_entry_records($data);
		
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
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
			
		$incomedata['invoice_no']=$invoice_no;
			
		$incomedata['invoice_type']=$data['invoice_type'];
		$incomedata['supplier_name']=$data['supplier_name'];
		
		$incomedata['invoice_date']=MJ_gmgt_get_format_for_db($data['invoice_date']);
		
		$incomedata['payment_status']=$data['payment_status'];
		$incomedata['entry']=$entry_value;
		$incomedata['receiver_id']=get_current_user_id();
		
		if($data['action']=='edit')
		{
			$expense_dataid['invoice_id']=$data['expense_id'];
			$result=$wpdb->update( $table_income, $incomedata ,$expense_dataid);
			return $result;
		}
		else
		{
			$incomedata['create_by']=get_current_user_id();
			$result=$wpdb->insert( $table_income,$incomedata);
			return $result;
		}
	}
	//get all expense data
	public function MJ_gmgt_get_all_expense_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense'");
		return $result;		
	}
	//get all expense data by create_by
	public function MJ_gmgt_get_all_expense_data_by_created_by($user_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense' AND create_by=$user_id");
		return $result;		
	}
	//get one party income data
	public function MJ_gmgt_get_oneparty_income_data($party_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
	
		$result = $wpdb->get_results("SELECT * FROM $table_income where supplier_name= '".$party_id."' order by invoice_date desc");
		
		return $result;
	}
	//get one party income data by income id
	public function MJ_gmgt_get_oneparty_income_data_incomeid($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
	
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_id= '".$income_id."' order by invoice_date desc");
		
		return $result;
	}
//all income data
	public function MJ_gmgt_get_all_income_data_dashboard()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' order by invoice_id desc limit 3");
		return $result;		
	}
//get all income data by member dashboard
	public function MJ_gmgt_get_all_income_data_by_member_dashboard()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$member_id=get_current_user_id();

		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND supplier_name=$member_id order by invoice_id desc limit 3");
		return $result;		
	}	
	//all income data by created_by dashboard
	public function MJ_gmgt_get_all_income_data_by_created_by_dashboard($user_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND create_by=$user_id order by invoice_id desc limit 3");
		return $result;		
	}	
}
//PAYMENT CLASS END
?>