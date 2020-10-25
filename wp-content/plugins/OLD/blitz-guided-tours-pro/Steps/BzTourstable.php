<?php

namespace BZ_SGN_WZ\Steps;
use BZ_SGN_WZ\Steps\WP_List_Table;

class BzTourstable extends WP_List_Table {

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
		
		$columns = array('id' => __('ID','bztsw'), 'title' => __('Tours','bztsw'),'steps' => __('Steps','bztsw'), 'view' => __('Start Tour','bztsw'), 'order' => __('Order','bztsw'), 'remove' => __('Actions','bztsw'));
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array('id','order');
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
        $table_name = $wpdb->prefix . "bztsw_tours";
        $rows = $wpdb->get_results("SELECT * FROM $table_name ORDER BY bztsw_id ASC");

        $data = array();
        foreach ($rows as $row) {
            $data[] = array('id' => $row->bztsw_id, 'title' => $row->bztsw_title,  'admin' => $row->bztsw_onDashboard, 'draft' => $row->bztsw_isDraft,'active' => $row->bztsw_isActive, 'remove' => '');
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
    public function column_default($item, $column_name) {
		
		if( is_multisite() ) {
			
				if( get_current_blog_id() == '1' ) {
				switch ($column_name) {
						case 'title':
							if($item['draft'] == '1') {
								return '<a href="admin.php?page=bztsw-tour-add&tour=' . $item['id'] . '">' . $item[$column_name] . ' (Draft)</a>';
							} else {
								return '<a href="admin.php?page=bztsw-tour-add&tour=' . $item['id'] . '">' . $item[$column_name] . '</a>';
							}
							break;

						case 'steps' :
							return '<a href="admin.php?page=bztsw-steps&tour='.$item['id'].'" title="View steps">View steps</a>';
							break;
							
						case 'view' :
							if($item['draft'] != '1') {
								return '<a data-id="'.$item['id'].'" href="admin.php?page=bztsw-tours&view=' . $item['id'] . '">Start Tour</a>';
							}
							break;
							
						case 'remove' :
							if($item['active'] != '1') {
								return '<a href="admin.php?page=bztsw-tours&turnoff=' . $item['id'] . '">Turn off</a>, <a href="admin.php?page=bztsw-tours&remove=' . $item['id'] . '"> Delete</a>,<a href="admin.php?page=bztsw-tours&duplicate=' . $item['id'] . '"> Duplicate</a>';
							} else {
								return '<a href="admin.php?page=bztsw-tours&turnon=' . $item['id'] . '">Turn On</a>, <a href="admin.php?page=bztsw-tours&remove=' . $item['id'] . '"> Delete</a>,<a href="admin.php?page=bztsw-tours&duplicate=' . $item['id'] . '"> Duplicate</a>';
							}
							break;
							
						case 'id' :
						case 'order' :
							return $item[$column_name];

						default :
							return print_r($item, true);
					}
				} else {
					
					if( $item['admin'] != '0' && $item['active'] != '1' ) {
						
						switch ($column_name) {
							case 'title':
								return '<a href="#">' . $item[$column_name] . '</a><br/><div class="row-actions"></div>';
								break;

							case 'view' :
								return '<a data-id="'.$item['id'].'" href="admin.php?page=bztsw-tours&view=' . $item['id'] . '">Start Tour</a>';
								break;
							case 'id' :
							case 'order' :
								return $item[$column_name];

							default :
								//~ return print_r($item, true);
						}
					}
				}
				
			} else {
		
			switch ($column_name) {
				
					case 'title' :
					if($item['draft'] == '1') {
						return '<a href="admin.php?page=bztsw-tour-add&tour=' . $item['id'] . '">' . $item[$column_name] . ' (Draft)</a>';
					} else {
						return '<a href="admin.php?page=bztsw-tour-add&tour=' . $item['id'] . '">' . $item[$column_name] . '</a>';
					}
					break;

					case 'steps' :
						return '<a href="admin.php?page=bztsw-steps&tour='.$item['id'].'" title="View steps">View steps</a>';
					break;
						
					case 'view' :
						if($item['draft'] != '1') {
							return '<a data-id="'.$item['id'].'" href="admin.php?page=bztsw-tours&view=' . $item['id'] . '">Start Tour</a>';
						}
					break;
					
					case 'remove' :
					    return '<a href="admin.php?page=bztsw-tours&turnoff=' . $item['id'] . '">Turn off</a>, <a href="admin.php?page=bztsw-tours&remove=' . $item['id'] . '"> Delete</a>, <a href="admin.php?page=bztsw-tours&duplicate=' . $item['id'] . '"> Duplicate</a>';
						break;
					case 'id' :
					case 'order' :
						return $item[$column_name];

					default :
						//~ return print_r($item, true);
				}
	}

}


}


