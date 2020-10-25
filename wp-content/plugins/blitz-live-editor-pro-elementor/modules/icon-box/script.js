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
			if( a("#"+editor.id).hasClass('elementor-icon-box-title')){
				settingData[id]['title'] = con;
			}else{
				settingData[id]['editor'] = con;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-icon-box-title',"link","bold italic underline link",callbackImageBoxText);
		a.tinymceInit('.elementor-icon-box-description',"link","bold italic underline link| alignleft aligncenter alignright",callbackImageBoxText);
		a('.elementor-icon-box-icon .elementor-icon').each(function () {
			var dataid = a(this).parent().parent().parent().parent().attr('data-id');
			if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
				a(this).addClass('changeIcon');
				a(this).attr('rel',dataid);
			}
		})
	});
	
})}(jQuery);
