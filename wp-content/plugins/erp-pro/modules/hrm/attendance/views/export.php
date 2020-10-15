<div class="export-section">
    <h1><?php _e('Export Attendance', 'erp-pro');?></h1>

    <form action="" method="post" id="attendance_export_form">
        <?php wp_nonce_field('erp-attendance-export-nonce');?>
        <?php submit_button(__('Export', 'erp-pro'), 'primary', 'submit_export');?>
    </form>
</div>
