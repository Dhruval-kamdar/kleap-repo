<?php
	$date           = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
	$date_formatted = date( 'l - j M Y', strtotime($date) );
?>

<div class="wrap erp-hr-attendance-single">
    <h2>
        <?php
            $url =  ( version_compare( WPERP_VERSION, '1.4.0', '<' ) ) ? 'admin.php?page=erp-edit-attendance' : 'admin.php?page=erp-hr&section=attendance&sub-section=erp-edit-attendance';
            printf( '%s <a href="%s" class="add-new-h2">%s</a>', __( 'Attendance Record', 'erp-pro' ), admin_url( $url . '&edit_date=' ) . $date, __( 'Edit', 'erp-pro' ) );
            echo '<h4>' . $date_formatted . '</h4>';
        ?>
    </h2>

    <form method="get">
        <input type="hidden" name="page" value="erp-hr-attendance">
        <input type="hidden" name="q" value="view">
        <input type="hidden" name="id" value="<?php echo $date; ?>">

        <?php
	        $list_table = new \WeDevs\ERP\HRM\Attendance\Attendance_Single_List_Table();
	        $list_table->prepare_items();
	        $list_table->display();
        ?>
    </form>
</div>
