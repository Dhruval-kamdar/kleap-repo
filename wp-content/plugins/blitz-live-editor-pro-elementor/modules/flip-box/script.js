!function(a){a(document).ready(function(){
	function callbackFlipBoxText(editor){
		canExit = false;
		var id =  a("#"+editor.id).closest('.elementor-widget-flip-box').attr('data-id');
		a("#"+editor.id).addClass('element-border');

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
			if( a("#"+editor.id).hasClass('elementor-flip-box__button')){
				if(!settingData[id]){
					settingData[id] = {};
				}
				if(!settingData[id]['flipbox']){
					settingData[id]['flipbox'] = {};
				}
				settingData[id]['flipbox']['link'] = a("#"+editor.id).attr('href');
				ourl = '';
				if(a("#"+editor.id).attr('target')){
					if(a("#"+editor.id).attr('target') == '_blank'){
						ourl = 'on';
					}
				}
				settingData[id]['flipbox']['is_external'] = ourl;
			}
		});
		
		editor.on('change', function(){
			var con = editor.getContent({format: 'raw'});
			var con1 = a.strip_tags(con,'<strong><em><span><a>'); 
			if(!settingData[id]){
				settingData[id] = {};
			}
			if(!settingData[id]['flipbox']){
				settingData[id]['flipbox'] = {};
			}
			if(a("#"+editor.id).closest(".elementor-flip-box__front").length > 0){
				
				if( a("#"+editor.id).hasClass('elementor-flip-box__layer__title')){
					settingData[id]['flipbox']['title_text_a'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-flip-box__layer__description')){
					settingData[id]['flipbox']['description_text_a'] = con1;
				}
			}else{
				if( a("#"+editor.id).hasClass('elementor-flip-box__layer__title')){
					settingData[id]['flipbox']['title_text_b'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-flip-box__layer__description')){
					settingData[id]['flipbox']['description_text_b'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-flip-box__button')){
					settingData[id]['flipbox']['button_text'] = con1;
				}
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-flip-box').length > 0){
			a('.elementor-widget-flip-box').each(function(){
				var dataid = a(this).attr('data-id');
				if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
					var backtop = a(this).find('.elementor-flip-box__front').height();
					var backtop1 = a(this).find('.elementor-flip-box__back').height();
					a(this).find('.elementor-flip-box__back').css('top',backtop);
					a(this).find('.elementor-flip-box__back').css('transform','none');
					a(this).find('.elementor-flip-box__front').css('transform','none');
					a(this).css('height',backtop1+backtop);
					a(this).find('.elementor-icon').addClass('changeIcon');
					a(this).find('.elementor-icon').addClass('element-border');
					a(this).find('.elementor-icon').attr('rel',dataid);
				
				}
			
			})
		}
		a.tinymceInit('.elementor-widget-flip-box .elementor-flip-box__layer__title,.elementor-widget-flip-box .elementor-flip-box__layer__description',"","bold italic underline",callbackFlipBoxText);
		a.tinymceInit('.elementor-widget-flip-box .elementor-flip-box__button',"link","bold italic underline link",callbackFlipBoxText);
		
	});
			
		a(document).on("liveEditorremove",function (e) {
			if(a('.elementor-widget-flip-box').length > 0){
				a('.elementor-widget-flip-box').each(function(){
					var dataid = a(this).attr('data-id');
					if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
						a(this).find('.elementor-flip-box__back').attr('style','');
						a(this).find('.elementor-flip-box__front').attr('style','');
						a(this).attr('style','');
						a(this).find('.elementor-icon').removeClass('changeIcon');
						a(this).find('.elementor-icon').removeClass('element-border');
						a(this).find('.elementor-icon').attr('rel','');
					}
				
				})
			}
		});
	
})}(jQuery);
