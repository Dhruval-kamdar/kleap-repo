<?php   
//DASHBOARD CLASS START 
class MJ_Gmgtdashboard
{	
	//count membership
	public function MJ_gmgt_count_membership()
	{
		global $wpdb;
		$table_gmgt_membershiptype = $wpdb->prefix . "gmgt_membershiptype";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_gmgt_membershiptype");
		return $results;	
	}
	//get membership list
	public function MJ_gmgt_get_membership_list($limit = 5)
	{
		global $wpdb;
		$table_gmgt_membershiptype = $wpdb->prefix . "gmgt_membershiptype";
		$results=$wpdb->get_results("SELECT * FROM $table_gmgt_membershiptype limit 0,$limit");
		return $results;	
	}
	//count group
	public function MJ_gmgt_count_group()
	{
		global $wpdb;
		$table_gmgt_groups = $wpdb->prefix . "gmgt_groups";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_gmgt_groups");
		return $results;	
	}
	//get group list
	public function MJ_gmgt_get_grouplist($limit = 5)
	{
		global $wpdb;
		$table_gmgt_groups = $wpdb->prefix . "gmgt_groups";
		$results=$wpdb->get_results("SELECT * FROM $table_gmgt_groups limit 0,$limit");
		return $results;	
	}
	//count class
	public function MJ_gmgt_count_class()
	{
		global $wpdb;
		$table_gmgt_class_schedule = $wpdb->prefix . "gmgt_class_schedule";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_gmgt_class_schedule");
		return $results;	
	}	 
	//count reservation
	public function MJ_gmgt_count_reservation()
	{
		global $wpdb;
		$table_gmgt_reservation = $wpdb->prefix . "gmgt_reservation";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_gmgt_reservation");
		return $results;	
	}	 
	//count reservation
	public function MJ_gmgt_count_Products()
	{
		global $wpdb;
		$table_gmgt_product = $wpdb->prefix . "gmgt_product";
		$results=$wpdb->get_var("SELECT count(*) FROM $table_gmgt_product");
		return $results;	
	}
	public function today_presents()
	{
		global $wpdb;
		$table_gmgt_attendence = $wpdb->prefix . "gmgt_attendence";
		$curr_date=date("Y-m-d");
		return $result=$wpdb->get_var("SELECT COUNT(*) FROM $table_gmgt_attendence WHERE attendence_date='$curr_date' and status='Present'");
		
	}	
}
//DASHBOARD CLASS END
?>