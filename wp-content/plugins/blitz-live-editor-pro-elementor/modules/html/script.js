!function(a){a(document).ready(function(){
	function callbackHtml(editor){
		canExit = false;
		var id = a("#"+editor.id).closest('.elementor-widget-html').attr('data-id');
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
			settingData[id]['html'] = con;
		});
	}
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-html').length > 0){
			a.tinymceInit('.elementor-widget-html .elementor-widget-container',"link","bold italic underline link| alignleft aligncenter alignright",callbackHtml);
		}
		
	});
	
})}(jQuery);
