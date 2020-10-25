!function(a){a(document).ready(function(){
	function callbackImageBoxText(editor){
		canExit = false;
		var id = a("#"+editor.id).parent().parent().parent().parent().attr('data-id');
		a('.elementor-element-'+id).addClass('element-border');
		
		if(a(".floatingbar_box .admin_box").length == 0){
			if(a('.elementor-element-'+id).hasClass('element-locked')){
				tinymce.remove("#"+editor.id);
			}
		}
		editor.on('click', function(){
			a.resetBG();
			a.resetFocus(id);
		});
		editor.on('change', function(){
			var con = editor.getContent({format: 'raw'});
			if(!settingData[id]){
				settingData[id] = {};
			}
			if( a("#"+editor.id).hasClass('elementor-image-box-title')){
				settingData[id]['title'] = con;
			}else{
				settingData[id]['editor'] = con;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-image-box-title',"link","bold italic underline link",callbackImageBoxText);
		a.tinymceInit('.elementor-image-box-description',"link","bold italic underline link| alignleft aligncenter alignright",callbackImageBoxText);
		
		a(document).on('click','.elementor-image-box-img',function(){
			canExit = false;
			a.resetBG();
			editorid = a(this).parent().parent().parent().attr('data-id');
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
