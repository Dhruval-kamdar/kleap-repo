<div id="basic-payroll-info-wrapper" class="wrap basic-payroll-info">

    <div class="basic-info">
        <div class="postbox leads-actions">
            <div class="handlediv" title="Click to toggle" aria-expanded="true">
                <br>
            </div>
            <h3 class="hndle">
                <span><?php _e( 'Todo List', 'erp-payroll' );?></span>
                <span class="spinner"></span>
            </h3>
            <div class="inside todo-table-container">
                <?php $todo_list = get_todos();?>
                <table class="wp-list-table widefat fixed striped users todo-table">
                    <tr>
                        <th><?php _e( 'To-Do', 'erp-pro' );?></th>
                        <th><?php _e( 'To-Do End Date', 'erp-pro' );?></th>
                        <th><?php _e( 'To-Do Creator', 'erp-pro' );?></th>
                        <th><?php _e( 'To-Do Created Date', 'erp-pro' );?></th>
                    </tr>
                    <?php if ( count( $todo_list ) > 0 ) : ?>
                        <?php foreach ( $todo_list as $tlist ) :?>
                            <tr>
                                <td><?php echo $tlist['title'];?></td>
                                <td><?php echo $tlist['deadline_date'];?></td>
                                <td><?php echo $tlist['display_name'];?></td>
                                <td><?php echo $tlist['created_at'];?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else : ?>
                        <tr>
                            <td><?php _e( 'No to-do found!', 'erp-pro' );?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

</div>
