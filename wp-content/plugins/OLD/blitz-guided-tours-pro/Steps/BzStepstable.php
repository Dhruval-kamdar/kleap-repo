<?php

namespace BZ_SGN_WZ\Steps;
use BZ_SGN_WZ\Steps\WP_List_Table;

class BzStepstable extends WP_List_Table {
        
    public $tourID;
    
	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */
	public function prepare_items() {
		$columns = $this -> get_columns();
		$hidden = $this -> get_hidden_columns();
		$sortable = $this -> get_sortable_columns();

		$data = $this -> table_data();

		$this -> _column_headers = array($columns, $hidden, $sortable);
		$this -> items = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns() {
		$columns = array('id' =>  __('ID','bztsw'), 'title' => __('Title','bztsw'),'step'=>__('Tour','bztsw'),'order'=>__('Order','bztsw'),'tourID'=>__('Step','bztsw'),'type'=>__('Type','bztsw'),'remove'=>__('Actions','bztsw'));

		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array('id','tourID');
		return null;
	}

	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns() {
		return null;
	}
	
	/**
	 * Get steps names
	 *
	 * @return Array
	 */
	 private function getStepsNames() {
	 	global $wpdb;
		$table_name = $wpdb -> prefix . "bztsw_tours";
		$rows = $wpdb -> get_results("SELECT * FROM $table_name");

		$data = array();
		foreach ($rows as $row) {
			$data[] = array('id'=>$row->bztsw_id,'title'=>$row->bztsw_title);
		}
		return $data;
	 }

	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	 private function table_data() {
		global $wpdb;
        $tourID = $this->tourID;
		$steps = $this->getStepsNames();
		$table_name = $wpdb -> prefix . "bztsw_steps";
                if ($tourID>0){
                    $rows = $wpdb -> get_results("SELECT * FROM $table_name WHERE bztsw_tourID=$tourID ORDER BY bztsw_steporder ASC");                    
                }else {
                    $rows = $wpdb -> get_results("SELECT * FROM $table_name ORDER BY bztsw_tourID ASC, bztsw_steporder ASC");                    
                }

		$data = array();
		foreach ($rows as $row) {
			$step_name = "";
			foreach ($steps as $step) {
				if ($step['id'] == $row->bztsw_tourID){
					$step_name = $step['title'];
				}
			}
			$data[] = array('id'=>$row->bztsw_id,'tourID'=>$row->bztsw_tourID,'step'=>$step_name,'title'=>$row->bztsw_title,'order'=>$row->bztsw_steporder,'type'=>$row->bztsw_stepTy,'remove'=>'');
		}
		return $data;
	}
	

	// Used to display the value of the id column
	public function column_id($item) {
		return $item['id'];
	}
	
	/**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
        	case 'title':
				return '<a href="admin.php?page=bztsw-step-add&step='.$item['id'].'">'.$item[$column_name].'</a>';
				break;
        	case 'remove' :
				return '<a href="admin.php?page=bztsw-steps&remove='.$item['id'].'">Delete</a>, <a href="admin.php?page=bztsw-steps&duplicate='.$item['id'].'"> Duplicate</a>';
				break;
            case 'id':
            case 'image':
            case 'order':
            case 'group':
            case 'price':
            case 'tourID':
            case 'step':
            case 'type':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

}
?>
