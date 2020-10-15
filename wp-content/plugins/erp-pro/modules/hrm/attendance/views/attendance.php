<div class="wrap erp-hr-attendance">
    <h2>
        <?php _e( 'Attendance', 'erp-pro' ); ?> <a href="<?php echo erp_attendance_url( 'erp-new-attendance' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'erp-pro' ); ?></a>
    </h2>

    <form method="get">
        <?php if( version_compare( WPERP_VERSION, '1.4.0', '<' ) ): ?>
            <input type="hidden" name="page" value="erp-hr-attendance">
        <?php else: ?>
            <input type="hidden" name="page" value="erp-hr">
            <input type="hidden" name="section" value="attendance">
            <input type="hidden" name="sub-section" value="attendance">
        <?php endif;?>

        <?php
        $attendance = new \WeDevs\ERP\HRM\Attendance\Attendance_List_Table();
        $attendance->prepare_items();
        $attendance->display();
        ?>
    </form>
</div>
