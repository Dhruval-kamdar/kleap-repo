<?php
namespace BZ_RCP\Questions;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

use WP_List_Table;

class BzQuestionstable extends WP_List_Table {
        
    public $ID;
    public $qTypes = array('mcq' => 'Multiple Choice Question', 'true_false' => 'True/False', 'star_rating' => 'Star Rating', 'message_box' => 'Message Box');
    
	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */
	public function prepare_items() {
		
		$columns = $this -> get_columns();
		$hidden = $this -> get_hidden_columns();
		$sortable = $this -> get_sortable_columns();
		$data = $this->table_data();
		
		/** Process bulk action */
        $this->process_bulk_action();
        
		$this -> _column_headers = array($columns, $hidden, $sortable);
		
		$per_page = $this->get_items_per_page('bzrcp_questions_per_page', 10);
		$current_page = $this->get_pagenum();
		$total_items = count($data);

		// only ncessary because we have sample data
		$found_data = array_slice($data,(($current_page-1)*$per_page),$per_page);

		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page                     //WE have to determine how many items to show on a page
		) );
        
		$this->items = $found_data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns() {
		$columns = array( 'cb' => '<input type="checkbox" />','question' => __('Question','bzrcp'),'question_type'=>__('Question Type','bzrcp'));
		return $columns;
	}



	public function column_cb($item) {
			return sprintf(
				'<input type="checkbox" name="del-question[]" value="%s" />', $item['id']
			);    
		}
    
	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
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
	 * Get the table data
	 *
	 * @return Array
	 */
	 private function table_data() {
		global $wpdb;
		$table_name = $wpdb -> prefix . "bzrcp_questions";
		$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
        $do_search1 = ( $search ) ? sprintf(" WHERE question LIKE '%%%s%%' ", $search ) : '';
        if( isset ($do_search1) ) {
			$rows =  $wpdb -> get_results("SELECT * FROM $table_name".$do_search1);
		} else {
		$rows =  $wpdb -> get_results("SELECT * FROM $table_name".$do_search1);	
		}
		$data = array();
		foreach ($rows as $row) {
			$qTy = $this->qTypes[$row->question_type];
			$questionTitle = '<a href="?page='.$_REQUEST['page'].'&action=edit&question='.$row->id.'">'.stripslashes($row->question).'</a>';
			$data[] = array('id'=>$row->id,'question'=>$questionTitle,'question_type'=>$qTy,'question_order'=>$row->question_order);
		}
		return $data;
	}
	
	 

	// Used to display the value of the id column
	public function column_id($item) {
		return $item['id'];
	}



	// Used to display the value of the id column	
	public function get_bulk_actions() {
		  $actions = array(
			'del-question'    => 'Delete'
		  );
		  return $actions;
	}
	
	
	public function process_bulk_action() {

        global $wpdb;

        $action = $this->current_action();
        if( 'delete'===$action ) {
          wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'del-question' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'del-question' )
        ) {

            $delete_ids = esc_sql( $_POST['del-question'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                
				$wpdb->delete(
					"{$wpdb->prefix}bzrcp_questions",
					array( 'id' => $id ),
					array( '%d' )
				);
        
            }
            wp_redirect( admin_url('admin.php?page=bzrcp-questions&status=deleted') );
        }
    } 
	
	
	
	public function column_question($item) {
	  $actions = array(
				'edit'      => sprintf('<a href="?page=%s&action=%s&question=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
				'delete'    => sprintf('<a href="?page=%s&action=%s&question=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
			);

	  return sprintf('%1$s %2$s', $item['question'], $this->row_actions($actions) );
	}

	
	/**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name ) {
		switch( $column_name ) {
        	case 'title':
        	case 'question':
        	case 'question_type':
        	case 'question_order':
			case 'id':
            case 'image':
            case 'order':
            case 'group':
            case 'price':
			case 'type':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    

}
?>
