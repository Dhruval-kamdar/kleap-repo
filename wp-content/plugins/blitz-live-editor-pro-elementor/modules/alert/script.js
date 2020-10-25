!function(a){a(document).ready(function(){
	function callbackAlert(editor){
		canExit = false;
		var id = a("#"+editor.id).closest('.elementor-widget-alert').attr('data-id');
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
			if(a("#"+editor.id).hasClass('elementor-alert-title')){
				var con1 = a.strip_tags(con,'<strong><em><span>'); 
				settingData[id]['title'] = con1;
			}
			if(a("#"+editor.id).hasClass('elementor-alert-description')){
				settingData[id]['description'] = con;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-alert').length > 0){
			a.tinymceInit('.elementor-alert .elementor-alert-title',"","bold italic underline",callbackAlert);
			a.tinymceInit('.elementor-alert .elementor-alert-description',"link","bold italic underline link| alignleft aligncenter alignright",callbackAlert);
		}
		
	});
	
})}(jQuery);
