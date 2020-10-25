!function(a){a(document).ready(function(){
	function callbackToggle(editor){
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
			var parentli = a("#"+editor.id).parent();
			var indexVal = parentli.index();
			var con = editor.getContent({format: 'raw'});
			if(!settingData[id]){
				settingData[id] = {};
			}
			if(a("#"+editor.id).hasClass('elementor-tab-title')){
				if(!settingData[id]['tab_title']){
					settingData[id]['tab_title'] = {};
				}
				var con1 = a.strip_tags(con,'<strong><em><span>'); 
				settingData[id]['tab_title'][indexVal] = con1;
			}
			if(a("#"+editor.id).hasClass('elementor-tab-content')){
				if(!settingData[id]['tab_content']){
					settingData[id]['tab_content'] = {};
				}
				settingData[id]['tab_content'][indexVal] = con;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-toggle .elementor-tab-title',"","bold italic underline",callbackToggle);
		a.tinymceInit('.elementor-toggle .elementor-tab-content',"link","bold italic underline link| alignleft aligncenter alignright",callbackToggle);
		
	});
	
})}(jQuery);
