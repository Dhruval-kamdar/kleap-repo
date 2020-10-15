!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		a(document).on('click','.editimage_image .elementor-image a,.editimage_image .elementor-image',function(){
			canExit = false;
			a.resetBG();
			editorid = a(this).closest('.elementor-widget-image').attr('data-id');
			if(a.inArray( editorid, elements_data ) > -1){
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a(this).append('<div class="media-overlay-editor"></div><div class="media-overlay-edit" rel="'+editorid+'" >Edit</div>');
				
				a(this).removeClass('mce-edit-focus');
				a(this).addClass('mce-edit-focus');
				
				a.resetFocus(editorid);
			}
		});
	});

})}(jQuery);
