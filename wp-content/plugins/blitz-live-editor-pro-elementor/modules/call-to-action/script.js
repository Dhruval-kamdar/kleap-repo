!function(a){a(document).ready(function(){
	function callbackCallToActionText(editor){
		canExit = false;
		var id =  a("#"+editor.id).closest('.elementor-widget-call-to-action').attr('data-id');
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
			if( a("#"+editor.id).hasClass('elementor-cta__button')){
				if(!settingData[id]){
					settingData[id] = {};
				}
				if(!settingData[id]['calltoaction']){
					settingData[id]['calltoaction'] = {};
				}
				settingData[id]['calltoaction']['link'] = a("#"+editor.id).attr('href');
				ourl = '';
				if(a("#"+editor.id).attr('target')){
					if(a("#"+editor.id).attr('target') == '_blank'){
						ourl = 'on';
					}
				}
				settingData[id]['calltoaction']['is_external'] = ourl;
			}
		});
		
		editor.on('change', function(){
			var con = editor.getContent({format: 'raw'});
			var con1 = a.strip_tags(con,'<strong><em><span><a>'); 
			if(!settingData[id]){
				settingData[id] = {};
			}
			if(!settingData[id]['calltoaction']){
				settingData[id]['calltoaction'] = {};
			}
				
			if( a("#"+editor.id).hasClass('elementor-cta__title')){
				settingData[id]['calltoaction']['title'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-cta__description')){
				settingData[id]['calltoaction']['description'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-cta__button')){
				settingData[id]['calltoaction']['button'] = con1;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-call-to-action').length > 0){
			a('.elementor-widget-call-to-action').each(function(){
				var dataid = a(this).attr('data-id');
				if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
					if(a(this).find('.elementor-icon').length > 0){
						a(this).find('.elementor-icon').addClass('changeIcon');
						a(this).find('.elementor-icon').addClass('element-border');
						a(this).find('.elementor-icon').attr('rel',dataid);
					}
					if(a(this).find('.elementor-cta__bg').length > 0){
						a(this).find('.elementor-cta__bg').addClass('changeImage');
					}
					if(a(this).find('.elementor-cta__image').length > 0){
						a(this).find('.elementor-cta__image').addClass('changeImage');
					}
					if(a(this).hasClass('elementor-cta--skin-cover')){
						a(this).append('<div class="coverImageChange"></div>')
					}
					
				}
			
			})
		}
		a.tinymceInit('.elementor-widget-call-to-action .elementor-cta__title,.elementor-widget-call-to-action .elementor-cta__description',"","bold italic underline",callbackCallToActionText);
		a.tinymceInit('.elementor-widget-call-to-action .elementor-cta__button',"link","bold italic underline link",callbackCallToActionText);
		
		a(document).on('click','.elementor-widget-call-to-action .coverImageChange',function(){
			editorid = a(this).closest('.elementor-widget-call-to-action').attr('data-id');
			a('.elementor-element-'+editorid).find('.changeImage').trigger('click');
		})
		a(document).on('click','.elementor-widget-call-to-action .changeImage',function(){
			editorid = a(this).closest('.elementor-widget-call-to-action').attr('data-id');
			if(a.inArray( editorid, elements_data ) > -1){
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a.resetFocus(editorid);
			}
			mainElem = a(this);
			if(a(this).find('img').length > 0){
				imgElem = a(this).find('img');
			}
			var frame = wp.media({
					title: '',
					button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			frame.on('select', () => {
				var attachment = frame.state().get('selection').first().toJSON();
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
				}
				if(!settingData[id]['calltoaction']){
					settingData[id]['calltoaction'] = {};
				}
				
				if(mainElem.find('img').length > 0){
					imgElem.attr('src',attachment.sizes['full'].url);
					imgElem.attr('srcset','');
					imgElem.addClass('edited-imgcall');
					indexval = 'image';
					settingData[id]['calltoaction'][indexval] = {};
					settingData[id]['calltoaction'][indexval]['image'] = con;
					settingData[id]['calltoaction'][indexval]['id'] = attachment.id;
				}else{
					mainElem.attr('style','background-image:url('+attachment.sizes['full'].url+');');
					indexval = 'bgimage';
					settingData[id]['calltoaction'][indexval] = {};
					settingData[id]['calltoaction'][indexval]['image'] = con;
					settingData[id]['calltoaction'][indexval]['id'] = attachment.id;
				}
			});
			frame.open();
			return false;
			
		});
		
	});
			
		a(document).on("liveEditorremove",function (e) {
			if(a('.elementor-widget-call-to-action').length > 0){
				window.location.reload();
				return false;
			}
		});
	
})}(jQuery);
