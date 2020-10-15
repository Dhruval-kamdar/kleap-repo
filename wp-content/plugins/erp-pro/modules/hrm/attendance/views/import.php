<div class="wrap" id="erp-attendance-import">
    <h1><?php _e('Import Attendance', 'erp-pro');?></h1>

    <form action="" method="post" enctype="multipart/form-data" id="attendance_import_form">

        <table class="form-table">
            <tbody>
                <tr class="row-type">
                    <th scope="row">
                        <label for="type"><?php _e('Type', 'erp-pro');?></label>
                    </th>
                    <td>
                        <select name="type" id="type">
                            <option value="csv">CSV</option>
                        </select>
                    </td>
                </tr>
                <tr class="row-attendance-file-upload">
                    <th scope="row">
                        <label for="attendance-file-upload"><?php _e('Select File', 'erp-pro');?></label>
                    </th>
                    <td>
                        <input type="file" name="attendance-file-upload" id="attendance-file-upload"?>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('erp-attendance-import-nonce');?>
        <?php submit_button(__('Import', 'erp-pro'), 'primary', 'submit_import');?>

    </form>

    <?php include_once WPERP_ATTEND_VIEWS . '/export.php'; ?>
</div>

<?php

if ( !isset($_REQUEST['submit_export'])) {
    return;
}

// Import
if (isset($_REQUEST['submit_import'])) {

    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'erp-attendance-import-nonce')) {
        die('You are no allowed');
    }

    $array_csv_content = [];

    if (isset($_FILES['attendance-file-upload']['tmp_name']) && is_uploaded_file($_FILES['attendance-file-upload']['tmp_name'])) {

        if (!('text/csv' === $_FILES['attendance-file-upload']['type'] || 'application/vnd.ms-excel' === $_FILES['attendance-file-upload']['type'])) {
            echo '<div class="notice notice-error"><p>';
            printf(esc_attr__('File type not supported', 'erp-pro'));
            echo '</p></div>';
            wp_die();
        }

        $csv_content = file_get_contents($_FILES['attendance-file-upload']['tmp_name']);

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $csv_content) as $line) {

            if ($line) {
                $array_csv_content[] = $line;
            }
        }

        unset($array_csv_content[0]);

        $employees  = new \WeDevs\ERP\HRM\Attendance\Models\Employee();
        $attendance = new \WeDevs\ERP\HRM\Attendance\Models\Attendance();

        foreach ($array_csv_content as $item) {

            $item = explode(',', $item);

            $employee_id = isset($item[0]) && $item[0] ? $item[0] : 0;
            $date        = isset($item[1]) && $item[1] ? $item[1] : null;
            $checkin     = isset($item[2]) && $item[2] ? $item[2] : null;
            $checkout    = isset($item[3]) && $item[3] ? $item[3] : null;
            $shift_title = isset($item[4]) && $item[4] ? $item[4] : null;

            $user = $employees->where('employee_id', $employee_id)->first();

            if (!empty($user->user_id)) {
                $shift_single = null;

                if (isset($user->user_id) && $user->user_id) {
                    $shift_single = $attendance->where('date', $date)->where('user_id', $user->user_id)->where('shift_title', $shift_title)->first();
                }

                if (!empty($shift_single)) {
                    $shift_id = $shift_single->id;

                } else {
                    $office_time = erp_att_get_office_time();
                    $shift_id    = erp_att_insert_new_shift($shift_title, $date, $office_time['starts'], $office_time['ends'], $user->user_id);
                }

                if ($shift_id) {
                    erp_att_insert_attendance($shift_id, 'yes', $checkin, $checkout);
                }
            }

        }
    }
}
