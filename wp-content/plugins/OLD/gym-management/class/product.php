<?php 	  
//PRODUCT CLASS START  
class MJ_Gmgtproduct
{	
	//ADD PRODUCT DATA
	public function MJ_gmgt_add_product($data,$product_image_url)
	{		
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
		
		$productdata['product_name']=MJ_gmgt_strip_tags_and_stripslashes($data['product_name']);
		$productdata['price']=$data['product_price'];
		$productdata['quentity']=$data['quentity'];
		$productdata['created_date']=date("Y-m-d");
		$productdata['created_by']=get_current_user_id();	
		$productdata['sku_number']=$data['sku_number'];
		$productdata['product_cat_id']=$data['product_category'];
		if(isset($data['manufacture_company_name']))
		$productdata['manufacture_company_name']=MJ_gmgt_strip_tags_and_stripslashes($data['manufacture_company_name']);
		if(!empty($data['manufacture_date']))
		{
			$productdata['manufacture_date']=MJ_gmgt_get_format_for_db($data['manufacture_date']);
		}
		else
		{
			$productdata['manufacture_date']=null;
		}	
		$productdata['product_description']=MJ_gmgt_strip_tags_and_stripslashes($data['product_description']);	

		$productdata['product_specification']=MJ_gmgt_strip_tags_and_stripslashes($data['product_specification']);	
	
		$productdata['product_image']=$product_image_url;
	
		if($data['action']=='edit')
		{
			$productid['id']=$data['product_id'];
			$result=$wpdb->update( $table_product, $productdata ,$productid);
			return $result;
		}
		else
		{
			
			$result=$wpdb->insert( $table_product, $productdata );
			
			if($result)
				$result=$wpdb->insert_id;
			return $result;
		}	
	}
	//get all product
	public function MJ_gmgt_get_all_product()
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_results("SELECT * FROM $table_product");
		return $result;	
	}
	//get all product by created by
	public function MJ_gmgt_get_all_product_by_created_by($user_id)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_results("SELECT * FROM $table_product where created_by=$user_id");
		return $result;	
	}
	//get single product
	public function MJ_gmgt_get_single_product($id)
	{		
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
		$result = $wpdb->get_row("SELECT * FROM $table_product where id=".$id);
		return $result;
	}
	
	//get all product by product name
	public function MJ_gmgt_get_all_product_by_name($product_name)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_results("SELECT * FROM $table_product where product_name='$product_name'");
		return $result;	
	}
	//get all product by product name count
	public function MJ_gmgt_get_all_product_by_name_count($product_name,$product_id)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_var("SELECT COUNT(*) FROM $table_product where product_name='$product_name' AND id!='$product_id'");
		return $result;	
	}
	
	//get all product by SKU Number
	public function MJ_gmgt_get_all_product_by_sku_number($sku_number)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_results("SELECT * FROM $table_product where sku_number='$sku_number'");
		return $result;	
	}	
	//get all product by SKU Number count
	public function MJ_gmgt_get_all_product_by_sku_number_count($sku_number,$product_id)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_var("SELECT COUNT(*) FROM $table_product where sku_number='$sku_number' AND id!='$product_id'");
		return $result;	
	}	
	//get all product by name and SKU Number
	public function MJ_gmgt_get_all_product_by_name_and_sku_number($product_name,$sku_number)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_results("SELECT * FROM $table_product where product_name='$product_name' AND sku_number='$sku_number'");
		return $result;	
	}	
	//get all product by name and SKU Number Count
	public function MJ_gmgt_get_all_product_by_name_and_sku_number_Count($product_name,$sku_number,$product_id)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_var("SELECT COUNT(*) FROM $table_product where product_name='$product_name' AND sku_number='$sku_number' AND id!='$product_id'");
		return $result;	
	}	
	//delete product
	public function MJ_gmgt_delete_product($id)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
		$result = $wpdb->query("DELETE FROM $table_product where id= ".$id);
		return $result;
	}	
	//get  product by product name
	public function MJ_gmgt_get_product_by_name($product_name)
	{
		global $wpdb;
		$table_product = $wpdb->prefix. 'gmgt_product';
	
		$result = $wpdb->get_row("SELECT * FROM $table_product where product_name='$product_name'");
		return $result;	
	}
}
//PRODUCT CLASS END
?>