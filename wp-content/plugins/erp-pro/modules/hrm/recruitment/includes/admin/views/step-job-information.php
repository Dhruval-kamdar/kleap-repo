<div class="wrap uniq-wrap" id="uniq-wrap">
    <?php echo erp_rec_opening_admin_progressbar( 'job_information' ); ?>
    <?php $postid = isset( $_REQUEST['postid'] ) ? intval( $_REQUEST['postid'] ) : 0;?>
    <div id="job-information-step" class="postbox metabox-holder" style="padding-top: 0; max-width: 1060px; margin: 0 auto;">
        <h3 class="openingform_header_title hndle"><?php _e( 'Job information', 'erp-pro' ); ?></h3>
        <div class="inside" style="overflow-y: hidden;">
            <form action="<?php echo erp_rec_url( 'add-opening&action=edit&step=candidate_basic_information' ); ?>" method="post" id="job-information-step-form">
                <div class="openingform_input_wrapper">
                    <div class="erp-grid-container">
                        <div class="row">
                            <div class="col-2">
                                <label><?php _e('Department', 'erp-pro'); ?> <span class="required">*</span></label>
                            </div>
                            <div class="col-4">
                                <?php $departments = erp_hr_get_departments(array('no_object' => true));?>
                                <?php if ( $postid != 0 ) : ?>
                                    <?php $get_department = get_post_meta($postid, '_department', true);?>
                                    <select name="department" class="widefat full-regular-text">
                                        <?php foreach ($departments as $department) { ?>
                                            <option value='<?php echo $department->id; ?>'
                                                <?php if ( $get_department == $department->id ): ?> selected="selected"<?php endif; ?>>
                                                <?php echo $department->title; ?>
                                            </option>
                                        <?php }?>
                                    </select>
                                <?php else : ?>
                                    <select name="department" class="widefat full-regular-text">
                                        <?php foreach ( $departments as $department ) { ?>
                                            <option value='<?php echo $department->id; ?>'>
                                                <?php echo $department->title; ?>
                                            </option>
                                        <?php }?>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label><?php _e('Employment Type', 'erp-pro'); ?> <span class="required">*</span></label>
                            </div>
                            <div class="col-4">
                                <?php $employment_types = erp_hr_get_employee_types();?>
                                <?php if ( $postid != 0 ) : ?>
                                    <?php $get_employment_type = get_post_meta($postid, '_employment_type', true);?>
                                    <select name="employment_type" class="widefat full-regular-text">
                                        <?php foreach ($employment_types as $key => $value) { ?>
                                            <option value='<?php echo $key; ?>'
                                                <?php if ( $get_employment_type == $key ): ?> selected="selected"<?php endif; ?>>
                                                <?php echo $value; ?>
                                            </option>
                                        <?php }?>
                                    </select>
                                <?php else : ?>
                                    <select name="employment_type" class="widefat full-regular-text">
                                        <?php $employment_types = erp_hr_get_employee_types();?>
                                        <?php foreach ( $employment_types as $key => $value ) { ?>
                                            <option value='<?php echo $key; ?>'>
                                                <?php echo $value; ?>
                                            </option>
                                        <?php }?>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-4">
                                <?php $get_remote_job = get_post_meta($postid, '_remote_job', true);?>
                                <label>
                                    <input type="checkbox" name="remote_job" <?php echo ( $get_remote_job == 1 ) ? 'checked' : ''; ?> />
                                    <?php _e('Remote working is an option for this opening', 'erp-pro'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label><?php _e('Minimum Experience', 'erp-pro'); ?> <span class="required">*</span></label>
                            </div>
                            <div class="col-4">
                                <?php $minimum_experience = erp_rec_get_recruitment_minimum_experience();?>
                                <?php if ( $postid != 0 ) : ?>
                                    <?php $get_minimum_experience = get_post_meta($postid, '_minimum_experience', true);?>
                                    <select name="minimum_experience" class="widefat full-regular-text">
                                        <?php foreach ($minimum_experience as $key => $value) { ?>
                                            <option value='<?php echo $key; ?>'
                                                <?php if ( $get_minimum_experience == $key ): ?> selected="selected"<?php endif; ?>>
                                                <?php echo $value; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                <?php else : ?>
                                    <select name="minimum_experience" class="widefat full-regular-text">
                                        <?php foreach ( $minimum_experience as $key => $value ) { ?>
                                            <option value='<?php echo $key; ?>'>
                                                <?php echo $value;?>
                                            </option>
                                        <?php }?>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label><?php _e( 'Submission Deadline', 'erp-pro' ); ?></label>
                            </div>
                            <div class="col-4">
                                <?php if ( $postid != 0 ) : ?>
                                    <?php $get_expire_date = get_post_meta($postid, '_expire_date', true);?>
                                    <input class="full-regular-text" type="text" id="expire_date" name="expire_date" value="<?php echo ( $get_expire_date == "" ) ? '' : $get_expire_date;?>">
                                <?php else : ?>
                                    <input type="text" id="expire_date" name="expire_date" value="" class="full-regular-text">
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label><?php _e( 'Location', 'erp-pro' ); ?> <span class="required">*</span></label>
                            </div>
                            <div class="col-4">
                                <?php if ( $postid != 0 ) : ?>
                                    <?php $get_location = get_post_meta($postid, '_location', true);?>
                                    <input class="full-regular-text" type="text" id="glocation" name="location" value="<?php echo ( $get_location == "" ) ? '' : $get_location;?>">
                                <?php else : ?>
                                    <input class="full-regular-text" type="text" id="glocation" name="location" value="">
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label><?php _e('Number of Vacancy', 'erp-pro'); ?> <span class="required">*</span></label>
                            </div>
                            <div class="col-4">
                                <?php if ( $postid != 0 ) : ?>
                                    <?php $get_vacancy = get_post_meta($postid, '_vacancy', true);?>
                                    <input class="full-regular-text" type="text" id="vacancy" name="vacancy" placeholder="" value="<?php echo ( $get_vacancy == "" ) ? '' : $get_vacancy;?>">
                                <?php else : ?>
                                    <input class="full-regular-text" type="text" id="vacancy" name="vacancy" placeholder="" value="" maxlength="2"/>
                                <?php endif;?>
                            </div>
                        </div>

                        <!-- *** Google Job Schema Input Start *** -->
                        <div class="job_schema_wrapper">
                            <div class="row">
                                <div class="col-6">
                                    <?php _e( '<h3 class="job_schema_wrapper_title"> Job Schema Information <span style="font-size: 12px;">( Optional )</span></h3>', 'erp-pro' ); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Street Address', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <input class="full-regular-text" type="text" id="street_address" name="street_address" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Locality', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <input class="full-regular-text" type="text" id="address_locality" name="address_locality" value=""><br>
                                    <span style="font-style: italic;"><?php _e( 'A particular area of a state or country', 'erp-pro' ); ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Zip/Postal Code', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <input class="full-regular-text" type="text" id="postal_code" name="postal_code" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Country', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <select name="address_country" id="address_country" class="widefat full-regular-text">
                                        <?php
                                        $country = \WeDevs\ERP\Countries::instance();
                                        foreach( $country->get_countries() as $cnt_key => $cnt_val ) {
                                            echo '<option value="'. $cnt_key .'">'. $cnt_val .'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Salary', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <input class="full-regular-text" type="number" id="salary" name="salary" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Currency', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <select name="currency" id="currency" class="widefat full-regular-text">
                                        <?php
                                        foreach( erp_get_currency_list_with_symbol() as $cur_key => $cur_val ) {
                                            echo '<option value="'. $cur_key .'">'. $cur_val .'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <label><?php _e( 'Salary Type', 'erp-pro' ); ?></label>
                                </div>
                                <div class="col-4">
                                    <select name="salary_type" id="salary_type" class="widefat full-regular-text">
                                        <option value="HOUR">HOURLY</option>
                                        <option value="DAY">DAILY</option>
                                        <option value="WEEK">WEEKLY</option>
                                        <option value="MONTH">MONTHLY</option>
                                        <option value="YEAR">YEARLY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <?php _e( '<b style="font-style: italic">Note : These boxed information will only be used as <a target=\'_blank\' href="https://developers.google.com/search/docs/data-types/job-posting">meta information for search engines</a>. Users will never see these information. </b>', 'erp-pro' ); ?>
                                </div>
                            </div>
                        </div>
                        <!-- *** Google Job Schema Input End *** -->

                    </div>
                </div>
                <input type="hidden" name="postid" value="<?php echo $postid; ?>">
                <input type="hidden" name="hidden_job_information" value="job_information">
                <?php wp_nonce_field( 'job_information' ); ?>
                <br style="clear: both">
                <a href="<?php echo erp_rec_url( 'add-opening&action=edit&step=hiring_workflow&postid='.$postid ); ?>" class="button button-hero"><?php _e('&larr; Back', 'erp-pro');?></a>
                <input type="submit" id="job_information" name="job_information" class="button-primary button button-hero alignright" value="<?php _e( 'Next &rarr;', 'erp-pro');?>">
            </form>
        </div>
    </div>
</div>