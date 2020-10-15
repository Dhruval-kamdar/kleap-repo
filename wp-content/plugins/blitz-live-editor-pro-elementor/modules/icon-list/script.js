!function(a){a(document).ready(function(){
	function callbackIconListText(editor){
		canExit = false;
		var id = a("#"+editor.id).parent().parent().parent().parent().parent().attr('data-id');
		if(!id){
			var id = a("#"+editor.id).parent().parent().parent().parent().attr('data-id');
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
			var parentli = a("#"+editor.id).parent();
			if(!parentli.hasClass('elementor-icon-list-item')){
				parentli = a("#"+editor.id).parent().parent();
			}
			var indexVal = parentli.index();
			var con = editor.getContent({format: 'raw'});
			if(!settingData[id]){
				settingData[id] = {};
			}
			if(!settingData[id]['title']){
				settingData[id]['title'] = {};
			}
			settingData[id]['title'][indexVal] = con;
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-icon-list-text',"","bold italic underline",callbackIconListText);
		var il = 0;
		a('.elementor-icon-list-icon').each(function () {
			var dataid = a(this).parent().parent().parent().parent().parent().attr('data-id');
			if(!dataid){
				var dataid = a(this).parent().parent().parent().parent().attr('data-id');
			}
			il = il + 1;
			var parentli = a(this).parent();
			if(!parentli.hasClass('elementor-icon-list-item')){
				parentli = a(this).parent().parent();
			}
			var indexVal = parentli.index();
			if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
				a(this).addClass('changeIcon');
				a(this).attr('rel',dataid+'___'+indexVal);
				if(il == '1'){
					a('.elementor-element-'+dataid).addClass('element-border');
				}
			}
		})
		a(document).on('click','.elementor-icon-list-item',function(){
			return false;
		})
		
	});
	
})}(jQuery);
