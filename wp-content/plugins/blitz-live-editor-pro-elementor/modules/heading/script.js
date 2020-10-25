!function(a){a(document).ready(function(){
	function callbackHeading(editor){
		canExit = false;
		var id = a("#"+editor.id).parent().parent().attr('data-id');
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
			settingData[id]['title'] = con;
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-heading-title',"link","bold italic underline link",callbackHeading);
	});
	
})}(jQuery);
