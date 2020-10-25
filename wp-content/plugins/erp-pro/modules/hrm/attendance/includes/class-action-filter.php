<?php

namespace WeDevs\ERP\HRM\Attendance;

use WeDevs\ERP\AddonTask;

class ActionFilter {


    public $addon_task;

    public function __construct() {
        $this->addon_task = new AddonTask();
        $this->action_hook();
    }

    public function action_hook () {
        add_action( 'after_calling_erp_hr_holiday_create_hook_callback', [ $this, 'modify_date_shift_for_holiday_create' ], 10, 2 );
        add_action( 'after_calling_erp_hr_holiday_delete_hook_callback', [ $this, 'modify_date_shift_for_holiday_delete' ], 10, 2 );
        add_action( 'after_calling_erp_hr_leave_request_approved_hook_callback', [ $this, 'modify_date_shift_for_leave_approve' ], 10, 4 );
        add_action( 'after_calling_erp_hr_leave_request_pending_hook_callback', [ $this, 'modify_date_shift_for_leave_pending' ], 10, 3 );
    }

    public function modify_date_shift_for_holiday_create ( $results_prev, $results_now ) {
        if ( empty( $results_prev ) && ! empty( $results_now ) ) {
            $holidays = wp_list_pluck( $results_now, 'date' );
            foreach ( $holidays as $holiday ) {
                $this->addon_task->make_query( 'delete', 'erp_attendance_date_shift', [ 'where' => [ 'date' => $holiday ] ]);
            }
        }
        if ( ! empty( $results_prev ) && ! empty( $results_now ) ) {

            $results_now_data = "'" . implode( "','", wp_list_pluck( $results_now, 'date' ) ) . "'" ;
            $previous_working_date = $this->addon_task->make_query( 'raw', '',[ 'sql' => function( $wpdb ) use ( $results_now_data ) {
                return "SELECT * FROM {$wpdb->prefix}erp_attendance_date_shift WHERE date IN ( {$results_now_data} ) GROUP BY user_id, shift_id";
            } ] );

            if ( ! empty( $results_prev ) ){
                foreach ( $results_prev as $rsltp ) {
                    foreach ( $previous_working_date as $pwd ) {
                        unset( $pwd->id );
                        $pwd->date = $rsltp->date;
                        $pwd->start_time = $rsltp->date. ' ' . date("H:i:s", strtotime($pwd->start_time));
                        $pwd->end_time    = date('Y-m-d', strtotime('+1 day', strtotime($rsltp->date))). ' ' . date("H:i:s", strtotime($pwd->start_time));
                        $this->addon_task->make_query( 'insert', 'erp_attendance_date_shift', [ 'data' => ( array ) $pwd ] );
                    }
                }
                $this->addon_task->make_query( 'raw', '', [ 'sql' => function ( $wpdb ) use ( $results_now_data ) {
                    return "DELETE FROM {$wpdb->prefix}erp_attendance_date_shift WHERE date IN ( {$results_now_data} )";
                } ] );
            }
        }
    }

    public function modify_date_shift_for_holiday_delete ( $id, $result ) {

    }

    public function modify_date_shift_for_leave_approve ( $result, $data, $id, $request ) {
        foreach ( $data as $dt ) {
            $this->addon_task->make_query( 'delete', 'erp_attendance_date_shift', [ 'where' => [ 'user_id' => $data['user_id'], 'date' => $data['date'] ] ]);
        }
    }

    public function modify_date_shift_for_leave_pending ( $results, $id, $request ) {
        //console_log($results);
        //console_log($id);
    }




}