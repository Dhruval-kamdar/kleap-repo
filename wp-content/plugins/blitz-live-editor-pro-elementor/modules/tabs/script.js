!function(a){a(document).ready(function(){
	function callbackTabs(editor){
		canExit = false;
		var id = a("#"+editor.id).closest('.elementor-widget-tabs').attr('data-id');
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
			if(a("#"+editor.id).parent().hasClass('elementor-tab-title')){
				var indexVal = a("#"+editor.id).closest('.elementor-tab-title').attr('data-tab') - 1;
				if(!settingData[id]['tab_title']){
					settingData[id]['tab_title'] = {};
				}
				var con1 = a.strip_tags(con,'<strong><em><span>'); 
				settingData[id]['tab_title'][indexVal] = con1;
			}
			if(a("#"+editor.id).hasClass('elementor-tab-content')){
				var indexVal = a("#"+editor.id).closest('.elementor-tab-content').attr('data-tab') - 1;
				if(!settingData[id]['tab_content']){
					settingData[id]['tab_content'] = {};
				}
				settingData[id]['tab_content'][indexVal] = con;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-tabs').length > 0){
			a('.elementor-widget-tabs .elementor-tabs .elementor-tab-content').each(function(){
				a(this).find('img').removeAttr('srcset');
			})
			a.tinymceInit('.elementor-tabs .elementor-tab-title a',"","bold italic underline",callbackTabs);
			a.tinymceInit('.elementor-tabs .elementor-tab-content',"link image","bold italic underline link| alignleft aligncenter alignright | image",callbackTabs);
		}
		
	});
	
})}(jQuery);
