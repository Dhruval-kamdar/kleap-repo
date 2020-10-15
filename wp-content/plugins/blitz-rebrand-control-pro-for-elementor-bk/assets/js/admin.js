;(function($) {
	$(document).ready(function() {
		
		
		// Color Picker.
		if ( 'undefined' !== typeof $.fn.wpColorPicker ) {
			// Add Color Picker to all inputs that have 'dm-color-picker' class.
			$( '.el-blitz-color-picker' ).wpColorPicker();
			
		}
		
	});
	
	$('.el-blitz-setting-tabs').on('click', '.el-blitz-tab', function(e) {
		e.preventDefault();

		var id = $(this).attr('href');
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$('.el-blitz-setting-tabs-content .el-blitz-setting-tab-content').removeClass('active');
		$('.el-blitz-setting-tabs-content').find(id).addClass('active');

		var formAction = $('#el-blitz-settings-form').attr('action').split('#')[0];
		formAction += '#!' + id.split('#')[1];

		location.href = formAction;

		$('#el-blitz-settings-form').attr('action', formAction);
	});

	$(window).load(function() {
		if ( location.hash ) {
			var activeTab = '#' + location.hash.split('#!')[1];

			if ( $('.el-blitz-setting-tabs-content').find(activeTab).length > 0 ) {
				$('.el-blitz-setting-tabs .el-blitz-tab').removeClass('active');
				$('.el-blitz-setting-tabs [href="'+activeTab+'"]').addClass('active');
				$('.el-blitz-setting-tabs-content .el-blitz-setting-tab-content').removeClass('active');
				$('.el-blitz-setting-tabs-content').find(activeTab).addClass('active');
			}
		}
	});
		
})(jQuery);
