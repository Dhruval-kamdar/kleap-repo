<?php 	
//TAX CLASS START  
class MJ_Gmgttax
{		
	//ADD TAX DATA
	public function MJ_gmgt_add_taxes($data)
	{		
		global $wpdb;
		$table_gmgt_taxes=$wpdb->prefix .'gmgt_taxes';
		$taxdata['tax_title']=MJ_gmgt_strip_tags_and_stripslashes($data['tax_title']);
		$taxdata['tax_value']=$data['tax_value'];		
		$taxdata['created_date']=date("Y-m-d");				
		
		if($data['action']=='edit')
		{	
			$taxid['tax_id']=$data['tax_id'];
			$result=$wpdb->update( $table_gmgt_taxes, $taxdata ,$taxid);		
			return $result;
		}
		else
		{						
			$result=$wpdb->insert( $table_gmgt_taxes,$taxdata);					
			return $result;		
		}
	}
	//get all taxes
	public function MJ_gmgt_get_all_taxes()
	{  
		global $wpdb;
		$table_gmgt_taxes=$wpdb->prefix .'gmgt_taxes';
	
		$result = $wpdb->get_results("SELECT * FROM $table_gmgt_taxes");
		return $result;	
	}	
	//delete taxes
	public function MJ_gmgt_delete_taxes($id)
	{
		global $wpdb;
		$table_gmgt_taxes=$wpdb->prefix .'gmgt_taxes';
		$result = $wpdb->query("DELETE FROM $table_gmgt_taxes where tax_id= ".$id);
		return $result;
	}
	//get single tax data
	public function MJ_gmgt_get_single_tax_data($tax_id)
	{
		global $wpdb;
		$table_gmgt_taxes=$wpdb->prefix .'gmgt_taxes';
	
		$result = $wpdb->get_row("SELECT * FROM $table_gmgt_taxes where tax_id= ".$tax_id);
		return $result;
	}
}
//TAX CLASS END  
?>