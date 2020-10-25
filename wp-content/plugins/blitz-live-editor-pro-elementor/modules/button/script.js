!function(a){a(document).ready(function(){
	function callbackButton(editor){
		canExit = false;
		var id = a("#"+editor.id).parent().parent().parent().attr('data-id');
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
		editor.on('NodeChange', function (e) {
			if(a('.elementor-element-'+id).find('.elementor-button-link').length > 0){
				if(!settingData[id]){
					settingData[id] = {};
				}
				settingData[id]['url'] = a('.elementor-element-'+id).find('.elementor-button-link').attr('href');
				ourl = '';
				if(a('.elementor-element-'+id).find('.elementor-button-link').attr('target')){
					if(a('.elementor-element-'+id).find('.elementor-button-link').attr('target') == '_blank'){
						ourl = 'on';
					}
					
				}
				settingData[id]['ourl'] = ourl;
			}
		});
		editor.on('change', function(){
			var con = a("#"+editor.id+' .elementor-button-text').html();
			if(!settingData[id]){
				settingData[id] = {};
			}
			settingData[id]['title'] = con;
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-button-link',"link","bold italic underline link",callbackButton);
	});
	
})}(jQuery);
