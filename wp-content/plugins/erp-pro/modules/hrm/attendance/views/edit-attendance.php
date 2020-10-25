<div class="wrap">
    <h2><?php _e( 'Edit Attendance', 'erp-pro' ); ?></h2>

    <form id="erp-edit-attendance" @submit="submitForm" v-cloak>
        <div style="display: flex; justify-content: space-between; width: 100%; margin: 5px 0;">
            <div>
                <?php printf('<b><span>%s</span><span>%s</span></b>', _e( 'Date: ', 'erp-pro' ), $_REQUEST['edit_date'] ); ?>
            </div>
            <div>
                <label><input @click="makeAllPresent" v-model="allPresent" type="radio"><?php _e( 'Set all present', 'erp-pro' ); ?></label>
                <label><input @click="makeAllAbsent" v-model="allAbsent" type="radio"><?php _e( 'Set all absent', 'erp-pro' ); ?></label>
            </div>
            <div>
                <?php _e( 'Search', 'erp-pro' ); ?> <input name="query" v-model="searchQuery">
            </div>
        </div>

        <table class="widefat striped">
            <thead>
            <tr>
                <th><?php _e( 'Employee ID', 'erp-pro' ); ?></th>
                <th><?php _e( 'Employee Name', 'erp-pro' ); ?></th>
                <?php if(is_shift_enabled()) { ?>
                <th><?php _e( 'Shift', 'erp-pro' ); ?></th>
                <?php } ?>
                <th><?php _e( 'Status', 'erp-pro' ); ?></th>
                <th><?php _e( 'Checkin', 'erp-pro' ); ?></th>
                <th><?php _e( 'Checkout', 'erp-pro' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="att in attendance | filterBy searchQuery">
                <td>{{att.employee_id}}</td>
                <td>{{att.employee_name}}</td>
                <?php if(is_shift_enabled()) { ?>
                <td>{{att.shift}}</td>
                <?php } ?>
                <td>
                    <label><input v-model="att.present" value="yes" type="radio">Present</label>
                    <label><input v-model="att.present" value="no" type="radio">Absent</label>
                </td>
                <td>
                    <input style="width: 86px;" v-model="att.checkin" type="text" :disabled="'yes' == att.present ? false : true" v-erp-timepicker>
                </td>
                <td>
                    <input style="width: 86px;" v-model="att.checkout" type="text" :disabled="'yes' == att.present ? false : true" v-erp-timepicker>
                </td>
            </tr>
            </tbody>
        </table>

        <p>
            <button class="button-primary" type="submit">
                <?php _e( 'Save Changes', 'erp-pro' ); ?>
            </button>  <span class="spinner" style="float: none;"></span>
        </p>
    </form>
</div>
