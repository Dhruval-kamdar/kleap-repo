<?php

namespace FSPoster\App\Pages\Schedules\Views;

use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'MODAL' ) or exit;
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script async src="<?php echo Pages::asset( 'Schedules', 'js/fsp-schedule-add.js' ); ?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo Pages::asset( 'Schedules', 'css/fsp-schedule-add.css' ); ?>">

<?php if ( ! ( isset ( $fsp_params[ 'parameters' ][ 'not_include_js' ] ) && $fsp_params[ 'parameters' ][ 'not_include_js' ] === TRUE ) ) { ?>
	<script async src="<?php echo Pages::asset( 'Base', 'js/fsp-metabox.js' ); ?>"></script>
	<link rel="stylesheet" href="<?php echo Pages::asset( 'Base', 'css/fsp-metabox.css' ); ?>">
<?php } ?>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-plus"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo $fsp_params[ 'parameters' ][ 'title' ]; ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body schedule_popup">
	<input type="hidden" id="fspKeepLogs" value="<?php echo Helper::getOption( 'keep_logs', 1 ) == 1 ? 'on' : 'off'; ?>">
	<input type="hidden" id="fspScheduleID" value="<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ] ) ? $fsp_params[ 'parameters' ][ 'info' ][ 'id' ] : 0; ?>">
	<div class="fsp-modal-tabs">
		<?php if ( ! ( isset( $fsp_params[ 'is_native' ] ) && $fsp_params[ 'is_native' ] === TRUE ) ) { ?>
			<div class="fsp-modal-tab" data-step="1">
				<span><?php echo fsp__( '1' ); ?></span><?php echo fsp__( 'Basic data' ); ?>
			</div>
			<div class="fsp-modal-tab" data-step="2">
				<span><?php echo fsp__( '2' ); ?></span><?php echo fsp__( 'Filters' ); ?>
			</div>
			<div class="fsp-modal-tab" data-step="3">
				<span><?php echo fsp__( '3' ); ?></span><?php echo fsp__( 'Accounts' ); ?>
			</div>
			<div class="fsp-modal-tab" data-step="4">
				<span><?php echo fsp__( '4' ); ?></span><?php echo fsp__( 'Custom messages' ); ?>
			</div>
		<?php } else { ?>
			<div class="fsp-modal-tab" data-step="3">
				<span><?php echo fsp__( '1' ); ?></span><?php echo fsp__( 'Accounts' ); ?>
			</div>
			<div class="fsp-modal-tab" data-step="4">
				<span><?php echo fsp__( '2' ); ?></span><?php echo fsp__( 'Custom messages' ); ?>
			</div>
		<?php } ?>
	</div>
	<?php if ( ! ( isset( $fsp_params[ 'is_native' ] ) && $fsp_params[ 'is_native' ] === TRUE ) ) { ?>
		<div id="fspModalStep_1" class="fsp-modal-step">
			<div class="fsp-form-group">
				<label><?php echo fsp__( 'Name' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'Add a name for your schedule to recognize it in your schedule list.' ); ?>"></i></label>
				<input autocomplete="off" class="fsp-form-input schedule_input_title" value="<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'title' ] ) ? esc_html( $fsp_params[ 'parameters' ][ 'info' ][ 'title' ] ) : ( isset( $fsp_params[ 'parameters' ][ 'name' ] ) ? esc_html( $fsp_params[ 'parameters' ][ 'name' ] ) : '' ); ?>" placeholder="<?php echo fsp__( 'Enter a name' ); ?>">
			</div>
			<div class="fsp-form-group">
				<label><?php echo fsp__( 'Start date & time' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'When the schedule will start?' ); ?>"></i></label>
				<div class="fsp-modal-row">
					<div class="fsp-modal-col">
						<input type="text" autocomplete="off" class="fsp-form-input schedule_input_start_date" placeholder="<?php echo fsp__( 'Select date' ); ?>" value="<?php echo Date::datee( isset( $fsp_params[ 'parameters' ][ 'info' ][ 'start_date' ] ) ? $fsp_params[ 'parameters' ][ 'info' ][ 'start_date' ] : 'now' ); ?>">
						<input type="time" autocomplete="off" class="fsp-form-input schedule_input_start_time" placeholder="<?php echo fsp__( 'Select time' ); ?>" value="<?php echo Date::time( isset( $fsp_params[ 'parameters' ][ 'info' ][ 'share_time' ] ) ? $fsp_params[ 'parameters' ][ 'info' ][ 'share_time' ] : 'now' ); ?>">
					</div>
					<div class="fsp-modal-col">
						<?php echo fsp__( 'Local time: %s', [ Date::dateTime() ] ); ?>
					</div>
				</div>
			</div>
			<div id="fspScheduleHowShareRow" class="fsp-form-group <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] > 1 ? 'fsp-hide' : ''; ?>">
				<label><?php echo fsp__( 'How you want to share' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'Define to share a single post repeatedly or once' ); ?>"></i></label>
				<select class="fsp-form-select post_freq">
					<option value="once" selected><?php echo fsp__( 'Share once' ); ?></option>
					<option value="repeat"><?php echo fsp__( 'Share repeatedly' ); ?></option>
				</select>
			</div>
			<div id="fspSchedulePostEveryRow" class="fsp-form-group">
				<label><?php echo fsp__( 'Post every' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'The interval between posts' ); ?>"></i></label>
				<div class="fsp-modal-row">
					<div class="fsp-modal-col">
						<input type="number" class="fsp-form-input interval" min="1" max="1000" step="1" value="<?php echo( isset( $fsp_params[ 'parameters' ][ 'info' ][ 'interval' ] ) ? $fsp_params[ 'parameters' ][ 'info' ][ 'interval' ] : '1' ); ?>">
						<select class="fsp-form-select interval_type">
							<option value="60"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'interval_type' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'interval_type' ] == '60' ? ' selected' : ''; ?>><?php echo fsp__( 'Hour' ); ?></option>
							<option value="1"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'interval_type' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'interval_type' ] == '1' ? ' selected' : ''; ?>><?php echo fsp__( 'Minute' ); ?></option>
							<option value="1440"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'interval_type' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'interval_type' ] == '1440' ? ' selected' : ''; ?>><?php echo fsp__( 'Day' ); ?></option>
						</select>
					</div>
					<div class="fsp-modal-col"></div>
				</div>
			</div>
			<div class="fsp-form-group">
				<div class="fsp-form-checkbox-group">
					<input id="fspScheduleSetSleepTime" type="checkbox" class="fsp-form-checkbox schedule_set_sleep_time" <?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_start' ] ) && ! empty( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_start' ] ) ? ' checked' : ''; ?>>
					<label for="fspScheduleSetSleepTime">
						<?php echo fsp__( 'Set a sleep timer' ); ?>
					</label>
					<span class="fsp-tooltip" data-title="<?php echo fsp__( 'You can set a sleep timer in your schedule. The plugin won\'t share any post during the sleep time.' ); ?>"><i class="far fa-question-circle"></i></span>
				</div>
				<div id="fspScheduleSetSleepTimeContainer" class="fsp-modal-row fsp-hide">
					<div class="fsp-modal-col">
						<input type="time" autocomplete="off" class="fsp-form-input schedule_input_sleep_time_start" value="<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_start' ] ) && ! empty( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_start' ] ) ? Date::time( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_start' ] ) : ''; ?>">
						<input type="time" autocomplete="off" class="fsp-form-input schedule_input_sleep_time_end" value="<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_end' ] ) && ! empty( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_end' ] ) ? Date::time( $fsp_params[ 'parameters' ][ 'info' ][ 'sleep_time_end' ] ) : ''; ?>">
					</div>
					<div class="fsp-modal-col"></div>
				</div>
			</div>
			<div id="fspScheduleOrderPostsRow" class="fsp-form-group <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] == 1 ? 'fsp-hide' : ''; ?>">
				<label><?php echo fsp__( 'Order posts by' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'Method for selecting posts.' ); ?>"></i></label>
				<select class="fsp-form-select post_sort">
					<option value="random2" <?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] === 'random2' ? ' selected' : ''; ?>><?php echo fsp__( 'Randomly ( without duplicates )' ); ?></option>
					<option value="random" <?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] === 'random' ? ' selected' : ''; ?>><?php echo fsp__( 'Randomly' ); ?></option>
					<option value="old_first" <?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] === 'old_first' ? ' selected' : ''; ?>><?php echo fsp__( 'Old posts first' ); ?></option>
					<option value="new_first" <?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_sort' ] === 'new_first' ? ' selected' : ''; ?>><?php echo fsp__( 'New posts first' ); ?></option>
				</select>
			</div>
		</div>
		<div id="fspModalStep_2" class="fsp-modal-step">
			<div class="fsp-form-group <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] > 0 ? 'fsp-hide' : ''; ?>">
				<label><?php echo fsp__( 'By the published time of the posts' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'Select posts that are published in a specific time.' ); ?>"></i></label>
				<select class="fsp-form-select schedule_input_post_date_filter">
					<option value="all"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'all' ? ' selected' : ''; ?>><?php echo fsp__( 'All times' ); ?></option>
					<option value="this_week"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'this_week' ? ' selected' : ''; ?>><?php echo fsp__( 'This week' ); ?></option>
					<option value="previously_week"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'previously_week' ? ' selected' : ''; ?>><?php echo fsp__( 'Previous week' ); ?></option>
					<option value="this_month"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'this_month' ? ' selected' : ''; ?>><?php echo fsp__( 'This month' ); ?></option>
					<option value="previously_month"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'previously_month' ? ' selected' : ''; ?>><?php echo fsp__( 'Previous month' ); ?></option>
					<option value="this_year"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'this_year' ? ' selected' : ''; ?>><?php echo fsp__( 'This year' ); ?></option>
					<option value="last_30_days"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'last_30_days' ? ' selected' : ''; ?>><?php echo fsp__( 'Last 30 days' ); ?></option>
					<option value="last_60_days"<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_date_filter' ] === 'last_60_days' ? ' selected' : ''; ?>><?php echo fsp__( 'Last 60 days' ); ?></option>
				</select>
			</div>
			<div class="fsp-form-group <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] > 0 ? 'fsp-hide' : ''; ?>">
				<label><?php echo fsp__( 'By post type' ); ?>&emsp;<i class="far fa-question-circle fsp-tooltip" data-title="<?php echo fsp__( 'You can select new post types in [ FS Poster > Settings > Share post types ].' ); ?>"></i></label>
				<select class="fsp-form-select schedule_input_post_type_filter">
					<?php
					foreach ( $fsp_params[ 'parameters' ][ 'postTypes' ] as $post_type )
					{
						echo '<option value="' . esc_html( $post_type ) . '"' . ( isset( $fsp_params[ 'parameters' ][ 'info' ][ 'post_type_filter' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'post_type_filter' ] == esc_html( $post_type ) ? ' selected' : '' ) . '>' . esc_html( ucfirst( $post_type ) ) . '</option>';
					}
					?>
				</select>
			</div>
			<div id="fspScheduleOutOfStockRow" class="fsp-form-checkbox-group <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] == 1 ? 'fsp-hide' : ''; ?>">
				<input id="fspScheduleOutOfStock" type="checkbox" class="fsp-form-checkbox schedule_dont_post_out_of_stock_products" <?php echo isset( $fsp_params[ 'parameters' ][ 'info' ][ 'dont_post_out_of_stock_products' ] ) && $fsp_params[ 'parameters' ][ 'info' ][ 'dont_post_out_of_stock_products' ] == 1 ? 'checked' : '' ?>>
				<label for="fspScheduleOutOfStock">
					<?php echo fsp__( 'Don\'t post products that are out of stock' ) ?>
				</label>
			</div>
			<div class="fsp-form-group <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] > 0 ? ' fsp-hide' : ''; ?>">
				<label><?php echo fsp__( 'By the post category and tag' ); ?></label>
				<select class="fsp-form-input schedule_input_category_filter select2-init"></select>
			</div>
			<div class="fsp-form-group">
				<label><?php echo fsp__( 'Specific Post ID(s) (separate by comma)' ); ?></label>
				<input autocomplete="off" class="fsp-form-input schedule_input_post_ids" value="<?php echo isset( $fsp_params[ 'parameters' ][ 'info' ] ) ? $fsp_params[ 'parameters' ][ 'info' ][ 'save_post_ids' ] : $fsp_params[ 'parameters' ][ 'post_ids' ]; ?>"<?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] > 0 ? 'disabled' : '' ?>>
			</div>
		</div>
	<?php } ?>
	<div id="fspModalStep_3" class="fsp-modal-step">
		<div class="fsp-metabox fsp-is-mini">
			<div class="fsp-card-body">
				<input type="hidden" name="share_checked" value="on">
				<div id="fspMetaboxShareContainer">
					<div class="fsp-metabox-tabs">
						<div data-tab="all" class="fsp-metabox-tab fsp-is-active">all</div>
						<div data-tab="fb" class="fsp-metabox-tab"><i class="fab fa-facebook"></i></div>
						<div data-tab="twitter" class="fsp-metabox-tab"><i class="fab fa-twitter"></i></div>
						<div data-tab="instagram" class="fsp-metabox-tab"><i class="fab fa-instagram"></i></div>
						<div data-tab="linkedin" class="fsp-metabox-tab"><i class="fab fa-linkedin"></i></div>
						<div data-tab="vk" class="fsp-metabox-tab"><i class="fab fa-vk"></i></div>
						<div data-tab="pinterest" class="fsp-metabox-tab"><i class="fab fa-pinterest"></i></div>
						<div data-tab="reddit" class="fsp-metabox-tab"><i class="fab fa-reddit"></i></div>
						<div data-tab="tumblr" class="fsp-metabox-tab"><i class="fab fa-tumblr"></i></div>
						<div data-tab="ok" class="fsp-metabox-tab"><i class="fab fa-odnoklassniki"></i></div>
						<div data-tab="google_b" class="fsp-metabox-tab"><i class="fab fa-google"></i></div>
						<div data-tab="telegram" class="fsp-metabox-tab"><i class="fab fa-telegram"></i></div>
						<div data-tab="medium" class="fsp-metabox-tab"><i class="fab fa-medium"></i></div>
						<div data-tab="wordpress" class="fsp-metabox-tab"><i class="fab fa-wordpress"></i></div>
					</div>
					<div class="fsp-metabox-accounts">
						<div class="fsp-metabox-accounts-empty">
							<?php echo fsp__( 'Please select an account.' ); ?>
						</div>
						<?php foreach ( $fsp_params[ 'parameters' ][ 'activeNodes' ] as $node_info )
						{
							$coverPhoto = Helper::profilePic( $node_info );

							if ( $node_info[ 'filter_type' ] === 'no' )
							{
								$titleText = '';
							}
							else
							{
								$titleText = ( $node_info[ 'filter_type' ] == 'in' ? fsp__( 'Share only the posts of the selected categories: ' ) : fsp__( 'Do not share the posts of the selected categories: ' ) );
								$titleText .= str_replace( ',', ', ', $node_info[ 'categories_name' ] );
							} ?>

							<div data-driver="<?php echo $node_info[ 'driver' ]; ?>" class="fsp-metabox-account">
								<input type="hidden" name="share_on_nodes[]" value="<?php echo $node_info[ 'driver' ] . ':' . $node_info[ 'node_type' ] . ':' . $node_info[ 'id' ]; ?>">
								<div class="fsp-metabox-account-image">
									<img src="<?php echo $coverPhoto; ?>" onerror="FSPoster.no_photo( this );">
								</div>
								<div class="fsp-metabox-account-label">
									<a href="<?php echo Helper::profileLink( $node_info ); ?>" class="fsp-metabox-account-text">
										<?php echo esc_html( $node_info[ 'name' ] ); ?>
									</a>
									<div class="fsp-metabox-account-subtext">
										<i class="<?php echo Helper::socialIcon( $node_info[ 'driver' ] ); ?>"></i>&nbsp;<?php echo ucfirst( $node_info[ 'driver' ] ); ?>&nbsp;>&nbsp;<?php echo esc_html( $node_info[ 'node_type' ] ); ?>&nbsp;<?php echo empty( $titleText ) ? '' : '<i class="fas fa-filter fsp-tooltip" data-title="' . $titleText . '" ></i>'; ?>
									</div>
								</div>
								<div class="fsp-metabox-account-remove">
									<i class="fas fa-times"></i>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="fsp-card-footer fsp-is-right">
				<button type="button" class="fsp-button fsp-is-gray fsp-metabox-add"><?php echo fsp__( 'ADD' ); ?></button>
				<button type="button" class="fsp-button fsp-is-red fsp-metabox-clear"><?php echo fsp__( 'CLEAR' ); ?></button>
			</div>
		</div>
	</div>
	<div id="fspModalStep_4" class="fsp-modal-step">
		<div class="fsp-custom-messages-container">
			<div class="fsp-card-body">
				<div class="fsp-custom-messages-tabs">
					<div data-tab="fb" class="fsp-custom-messages-tab"><i class="fab fa-facebook"></i></div>
					<div data-tab="twitter" class="fsp-custom-messages-tab"><i class="fab fa-twitter"></i></div>
					<div data-tab="instagram" class="fsp-custom-messages-tab"><i class="fab fa-instagram"></i></div>
					<div data-tab="linkedin" class="fsp-custom-messages-tab"><i class="fab fa-linkedin"></i></div>
					<div data-tab="vk" class="fsp-custom-messages-tab"><i class="fab fa-vk"></i></div>
					<div data-tab="pinterest" class="fsp-custom-messages-tab"><i class="fab fa-pinterest"></i></div>
					<div data-tab="reddit" class="fsp-custom-messages-tab"><i class="fab fa-reddit"></i></div>
					<div data-tab="tumblr" class="fsp-custom-messages-tab"><i class="fab fa-tumblr"></i></div>
					<div data-tab="ok" class="fsp-custom-messages-tab"><i class="fab fa-odnoklassniki"></i></div>
					<div data-tab="google_b" class="fsp-custom-messages-tab"><i class="fab fa-google"></i></div>
					<div data-tab="telegram" class="fsp-custom-messages-tab"><i class="fab fa-telegram"></i></div>
					<div data-tab="medium" class="fsp-custom-messages-tab"><i class="fab fa-medium"></i></div>
					<div data-tab="wordpress" class="fsp-custom-messages-tab"><i class="fab fa-wordpress"></i></div>
				</div>
				<div id="fspCustomMessages" class="fsp-custom-messages">
					<div data-driver="fb">
						<div class="fsp-custom-post">
							<textarea data-sn-id="fb" name="fs_post_text_message_fb" class="fsp-form-textarea" rows="3" maxlength="2000"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'fb' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="twitter">
						<div class="fsp-custom-post">
							<textarea data-sn-id="twitter" name="fs_post_text_message_twitter" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'twitter' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="instagram">
						<div class="fsp-custom-post">
							<textarea data-sn-id="instagram" name="fs_post_text_message_instagram" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'instagram' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
						<div class="fsp-custom-post">
							<textarea data-sn-id="instagram_h" name="fs_post_text_message_instagram_h" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'instagram_h' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="linkedin">
						<div class="fsp-custom-post">
							<textarea data-sn-id="linkedin" name="fs_post_text_message_linkedin" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'linkedin' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="vk">
						<div class="fsp-custom-post">
							<textarea data-sn-id="vk" name="fs_post_text_message_vk" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'vk' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="pinterest">
						<div class="fsp-custom-post">
							<textarea data-sn-id="pinterest" name="fs_post_text_message_pinterest" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'pinterest' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="reddit">
						<div class="fsp-custom-post">
							<textarea data-sn-id="reddit" name="fs_post_text_message_reddit" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'reddit' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="tumblr">
						<div class="fsp-custom-post">
							<textarea data-sn-id="tumblr" name="fs_post_text_message_tumblr" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'tumblr' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="ok">
						<div class="fsp-custom-post">
							<textarea data-sn-id="ok" name="fs_post_text_message_ok" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'ok' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="google_b">
						<div class="fsp-custom-post">
							<textarea data-sn-id="google_b" name="fs_post_text_message_google_b" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'google_b' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="telegram">
						<div class="fsp-custom-post">
							<textarea data-sn-id="telegram" name="fs_post_text_message_telegram" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'telegram' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="medium">
						<div class="fsp-custom-post">
							<textarea data-sn-id="medium" name="fs_post_text_message_medium" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'medium' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key='<img src="{featured_image_url}">'>
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key='<a href="{short_link}">{short_link}</a>'>
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key='<a href="{link}">{link}</a>'>
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
					<div data-driver="wordpress">
						<div class="fsp-custom-post">
							<textarea data-sn-id="wordpress" name="fs_post_excerpt_wordpress" class="fsp-form-textarea"><?php echo esc_html( $fsp_params[ 'parameters' ][ 'customMessages' ][ 'wordpress' ] ); ?></textarea>
							<div class="fsp-custom-post-buttons">
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
									{ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
									{PRODUCT_REGULAR_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
									{AUTHOR}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post author name' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
									{CONTENT_SHORT_40}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
									{TITLE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post title' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key='<img src="{featured_image_url}">'>
									{FEATURED_IMAGE_URL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Featured image URL' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
									{TAGS}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Tags' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
									{PRODUCT_SALE_PRICE}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'WooCommerce - product sale price' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
									{CONTENT_FULL}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post full content' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key='<a href="{short_link}">{short_link}</a>'>
									{SHORT_LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post short link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
									{EXCERPT}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post excerpt' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
									{CATEGORIES}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post Categories' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
									{UNIQ_ID}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Unique ID' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
									{CF_KEY}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Custom fields. Replace KEY with the custom field name.' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key='<a href="{link}">{link}</a>'>
									{LINK}
									<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo fsp__( 'Post link' ); ?>"></i>
								</button>
								<button type="button" class="fsp-button fsp-is-red fsp-clear-button fsp-tooltip" data-title="<?php echo fsp__( 'Click to clear the textbox' ); ?>">
									<?php echo fsp__( 'CLEAR' ); ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="fsp-modal-subfooter schedule_popup <?php echo isset( $fsp_params[ 'parameters' ][ 'post_ids_count' ] ) && $fsp_params[ 'parameters' ][ 'post_ids_count' ] == 1 ? 'fsp-hide' : ''; ?>">
	<?php echo fsp__( 'Posts matching your filters:' ); ?>&nbsp;<span class="schedule_matches_count">...</span>
</div>
<div class="fsp-modal-footer schedule_popup">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo fsp__( 'CANCEL' ); ?></button>
	<button id="fspScheduleSaveBtn" data-info="<?php echo esc_html( json_encode( $fsp_params[ 'parameters' ][ 'info' ] ) ); ?>" class="fsp-button <?php echo ! ( isset( $fsp_params[ 'is_native' ] ) && $fsp_params[ 'is_native' ] === TRUE ) ? 'schedule_save_btn' : 'wp_native_schedule_save_btn'; ?>"><?php echo $fsp_params[ 'parameters' ][ 'btn_title' ]; ?></button>
</div>