<div class="wrap">
    <h2><?php _e( 'Manage Shifts', 'erp-pro' ); ?></h2>

    <?php
        if( !is_shift_enabled() ) {
            _e( '<p>Shift support is currently disabled.</p>', 'erp-pro' );
            // printf('%s <a href="%s">%s</a>', __( 'To enable please go to', 'erp-pro' ), admin_url( 'admin.php?page=erp-settings&tab=erp-hr&section=attendance' ), __( 'Shift Settings', 'erp-pro' ) );
            printf( __( 'To enable please go to <a href="%s">Shift Settings</a>', 'erp-pro' ), admin_url( 'admin.php?page=erp-settings&tab=erp-hr&section=attendance' ) );
            wp_die();
        }
    ?>
    <div class="notice notice-success" id="notice-shift-save-success">
        <p>
            <?php esc_attr_e( 'Saved Changes', 'erp-pro' ); ?>
        </p>
    </div>

    <div class="notice notice-success" id="notice-shift-copied-success">
        <p>
            <?php esc_attr_e( 'Shifts copied to next week successfully', 'erp-pro' ); ?>
        </p>
    </div>

    <div id="save-changes-container">
        <div class="spinner"></div>
        <button id="save-changes" class="button button-primary"><?php _e( 'Save Changes', 'erp-pro' ); ?></button>
    </div>

    <div id="shift-list" v-cloak>
        <p class="shift-list-header"><?php _e( 'Shift List', 'erp-pro' ); ?></p>
        <div id="shifts-main-container" >
            <div id="shifts-list-container">
                <div v-dragable-for="element in shifts" options='{"group":{ "name":"people", "pull":"clone", "put":false }}'>
                    <div v-if="element.edit == true" class="shift-items-container-edit">
                        <div class="shift-edit-title-container">
                            <input v-model="element.copy.shift_title" type="text" class="shift-title-input" placeholder="<?php _e( 'Shift Title', 'erp-pro' ); ?>"> <button @click="editDone(element)" class="shift-done-button button-primary"><?php _e( 'Done', 'erp-pro' ); ?></button>
                        </div>
                        <div class="shift-time-container">
                            <div>
                                <input class="shift-checkin-input" v-model="element.copy.shift_start_time" placeholder="<?php _e( 'Start', 'erp-pro' ); ?>" type="text" v-erp-timepicker>
                                <input class="shift-checkout-input" v-model="element.copy.shift_end_time" placeholder="<?php _e( 'End', 'erp-pro' ); ?>" type="text" v-erp-timepicker>
                            </div>
                            <Button @click="editCancel(element)" class="button-secondary"><?php _e( 'Cancel', 'erp-pro' ); ?></Button>
                        </div>
                    </div>
                    <div v-if="element.edit == false" class="shift-items-container-default">
                        <span class="shift-list-title">{{element.shift_title}}</span>
                        <span class="shift-list-time">{{element.shift_start_time}} - {{element.shift_end_time}}</span>
                        <div class="shift-buttons-container">
                            <button title="Edit" @click="editOpen(element)" class="btn"><span class="shift-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></button>&nbsp;
                            <button title="Remove" @click="deleteShift(element)" class="btn"><span class="shift-delete"><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shift-add-button" @click="addNewShift">
                <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                <span class="new-shift-text"><?php _e( 'New Shift', 'erp-pro' ); ?></span>
            </div>
        </div>
    </div>

    <div id="erp-att-shifts" v-cloak>
        <div style="clear: both">

                <div><p class="assign-shifts-header"><?php _e( 'Assign Shifts', 'erp-pro' ); ?></p></div>
                <div class="assign-shifts-action-container">
                    <div class="bulk-action-container">
                        <select v-model="bulkAction">
                            <option value="-1"><?php _e( 'Bulk Action', 'erp-pro' ); ?></option>
                            <option value="delete"><?php _e( 'Delete Shifts', 'erp-pro' ); ?></option>
                        </select>
                        <button @click="triggerBulkAction" class="button-secondary"><?php _e( 'Apply', 'erp-pro' ); ?></button>
                    </div>
                    <div class="prev-next-container">
                        <input class="assign-shift-search-input" placeholder="<?php _e( 'Search', 'erp-pro' ); ?>" type="text" v-model="searchQuery">&nbsp;
                        <button @click="getPreviousWeek(employees[0].dates[0].date)" class="button"><span>< </span><?php _e( 'Prev', 'erp-pro'); ?></button>&nbsp;<button @click="getNextWeek(employees[0].dates[0].date)" class="button"><?php _e( 'Next', 'erp-pro' ); ?><span> ></span></button>
                    </div>
                </div>
            <div>
                <table v-if="isReady" class="widefat striped" id="employee-assign-shift">
                    <thead>
                        <tr>
                            <td><input @click="toggleAllSelect" v-model="selectAll" id="universal-check-all" type="checkbox"></td>
                            <td><span class="assign-shift-employee"><?php _e( 'Employee Name', 'erp-pro'); ?></span></td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[0].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Monday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[1].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Tuesday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[2].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Wednesday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[3].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Thursday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[4].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Friday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[5].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Saturday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span class="assign-shift-thead-container">
                                    <span class="assign-shift-date">{{employees[0].dates[6].date}}</span><span class="assign-shift-weekdays"><?php _e( 'Sunday', 'erp-pro'); ?></span>
                                </span>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(i, element) in employees | filterBy searchQuery">
                            <td>
                                <input v-model="element.selected" class="employee-select-all" type="checkbox">
                            </td>
                            <td>
                                <b><p class="assign-shift-employee-name">{{element.employee_name}}</p></b>
                                <span title="Copy Shift to Next Week" @click="copyShifts(element)" class="erp-tips copy-shift"><i class="fa fa-files-o" aria-hidden="true"></i></span>

                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf0) in element.dates[0].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf0.shift_title}}<span @click="deleteShift(sf0, 0, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf0.shift_start_time | shorttime}} - {{sf0.shift_end_time | shorttime}}</div>
                                </div>
                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf1) in element.dates[1].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf1.shift_title}}<span @click="deleteShift(sf1, 1, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf1.shift_start_time | shorttime}} - {{sf1.shift_end_time | shorttime}}</div>
                                </div>
                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf2) in element.dates[2].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf2.shift_title}}<span @click="deleteShift(sf2, 2, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf2.shift_start_time | shorttime}} - {{sf2.shift_end_time | shorttime}}</div>
                                </div>
                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf3) in element.dates[3].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf3.shift_title}}<span @click="deleteShift(sf3, 3, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf3.shift_start_time | shorttime}} - {{sf3.shift_end_time | shorttime}}</div>
                                </div>
                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf4) in element.dates[4].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf4.shift_title}}<span  @click="deleteShift(sf4, 4, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf4.shift_start_time | shorttime}} - {{sf4.shift_end_time | shorttime}}</div>
                                </div>
                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf5) in element.dates[5].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf5.shift_title}}<span  @click="deleteShift(sf5, 5, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf5.shift_start_time | shorttime}} - {{sf5.shift_end_time | shorttime}}</div>
                                </div>
                            </td>

                            <td class="daily-shift-list">
                                <div class="daily-shift-single" v-dragable-for="(index, sf6) in element.dates[6].shifts" :options="{group:{name:'people',pull:false}}">
                                    <div class="daily-shift-title">{{sf6.shift_title}}<span  @click="deleteShift(sf6, 6, i)" class="daily-shift-delete"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                    <div>{{sf6.shift_start_time | shorttime}} - {{sf6.shift_end_time | shorttime}}</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h4 v-else>Please wait, Loading...</h4>
            </div>
        </div>
    </div>
</div>
