!function(a){a(document).ready(function(){
	function callbackImageBoxText(editor){
		canExit = false;
		if( a("#"+editor.id).hasClass('elementor-testimonial-content')){
			var id = a("#"+editor.id).parent().parent().parent().attr('data-id');
		}else{
			var id = a("#"+editor.id).parent().parent().parent().parent().parent().parent().attr('data-id');
		}
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
			if( a("#"+editor.id).hasClass('elementor-testimonial-name')){
				var con1 = a.strip_tags(con,'<strong><em><span><a>'); 
				settingData[id]['testimonial_name'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-testimonial-job')){
				var con1 = a.strip_tags(con,'<strong><em><span><a>'); 
				settingData[id]['testimonial_job'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-testimonial-content')){
				settingData[id]['testimonial_content'] = con;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-testimonial-name',"link","bold italic underline link",callbackImageBoxText);
		a.tinymceInit('.elementor-testimonial-job',"link","bold italic underline link",callbackImageBoxText);
		a.tinymceInit('.elementor-testimonial-content',"link","bold italic underline link| alignleft aligncenter alignright",callbackImageBoxText);

		a(document).on('click','.elementor-testimonial-image',function(){
			canExit = false;
			a.resetBG();
			editorid = a(this).parent().parent().parent().parent().parent().attr('data-id');
			if(a(".floatingbar_box .admin_box").length == 0){
				if(a('.elementor-element-'+editorid).hasClass('element-locked')){
					return false;
				}
			}
			a(this).append('<div class="media-overlay-editor"></div><div class="media-overlay-edit" rel="'+editorid+'">Edit</div>');
			a(this).removeClass('mce-edit-focus');
			a(this).addClass('mce-edit-focus');
			a.resetFocus(editorid);
		});
	});
	
})}(jQuery);
