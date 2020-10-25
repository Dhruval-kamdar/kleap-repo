!function(a){a(document).ready(function(){
	function callbackPriceTableText(editor){
		canExit = false;
		var id =  a("#"+editor.id).closest('.elementor-widget-price-table').attr('data-id');
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
			if( a("#"+editor.id).hasClass('elementor-price-table__button')){
				if(!settingData[id]){
					settingData[id] = {};
				}
				if(!settingData[id]['pricetable']){
					settingData[id]['pricetable'] = {};
				}
				settingData[id]['pricetable']['link'] = a("#"+editor.id).attr('href');
				ourl = '';
				if(a("#"+editor.id).attr('target')){
					if(a("#"+editor.id).attr('target') == '_blank'){
						ourl = 'on';
					}
				}
				settingData[id]['pricetable']['is_external'] = ourl;
			}
		});
		
		editor.on('change', function(){
			var con = editor.getContent({format: 'raw'});
			var con1 = a.strip_tags(con,'<strong><em><span><a>'); 
			if(!settingData[id]){
				settingData[id] = {};
			}
			if(!settingData[id]['pricetable']){
				settingData[id]['pricetable'] = {};
			}
			
			if( a("#"+editor.id).hasClass('elementor-price-table__heading')){
				settingData[id]['pricetable']['heading'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-table__subheading')){
				settingData[id]['pricetable']['sub_heading'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-table__integer-part')){
				settingData[id]['pricetable']['price'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-table__period')){
				settingData[id]['pricetable']['period'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-table__additional_info')){
				settingData[id]['pricetable']['footer_additional_info'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-table__button')){
				settingData[id]['pricetable']['button_text'] = con1;
			}
			if( a("#"+editor.id).parent().hasClass('elementor-price-table__feature-inner')){
				var indexvalue = a("#"+editor.id).closest('li').index();
				if(!settingData[id]['pricetable']['feature']){
					settingData[id]['pricetable']['feature'] = {};
				}
				if(!settingData[id]['pricetable']['feature'][indexvalue]){
					settingData[id]['pricetable']['feature'][indexvalue] = {};
				}
				settingData[id]['pricetable']['feature'][indexvalue]['title'] = con1;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-widget-price-table .elementor-price-table__heading,.elementor-widget-price-table .elementor-price-table__subheading,.elementor-widget-price-table .elementor-price-table__period,.elementor-widget-price-table .elementor-price-table__additional_info,.elementor-widget-price-table .elementor-price-table__features-list .elementor-price-table__feature-inner span,.elementor-widget-price-table .elementor-price-table__integer-part',"","bold italic underline",callbackPriceTableText);
		a.tinymceInit('.elementor-widget-price-table .elementor-price-table__button',"link","bold italic underline link",callbackPriceTableText);
		
		a('.elementor-widget-price-table').each(function () {
			if( a(this).find('.elementor-price-table__integer-part').length > 0){
				var priceval = a(this).find('.elementor-price-table__integer-part').html() + '.' +a(this).find('.elementor-price-table__fractional-part').html();
				a(this).find('.elementor-price-table__integer-part').html(priceval);
				a(this).find('.elementor-price-table__after-price').hide();
			}
		})
		var il = 0;
		a('.elementor-price-table__features-list li').each(function () {
			var dataid = a(this).closest('.elementor-widget-price-table').attr('data-id');
			il = il + 1;
			var indexVal = a(this).index();
			if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
				iconclass = a(this).find('i').attr('class');
				a(this).find('i').remove();
				a( '<div class="icon-pricetable"><i class="'+iconclass+'" ></i></div>' ).insertBefore( a(this).find('span'));
				
				a(this).find('.icon-pricetable').addClass('changeIcon');
				a(this).find('.icon-pricetable').attr('rel',dataid+'___'+indexVal);
				//~ if(il == '1'){
					//~ a('.elementor-element-'+dataid).addClass('element-border');
				//~ }
			}
		})
		
	});
	a(document).on("liveEditorremove",function (e) {
		a('.elementor-widget-price-table').each(function () {
			if( a(this).find('.elementor-widget-price-table .elementor-price-table__integer-part').length > 0){
				var priceval = a(this).find('.elementor-price-table__integer-part').html();
				if(priceval.indexOf(".") > -1){
					priceval1 = priceval.split('.');
					a(this).find('.elementor-price-table__integer-part').html(priceval1[0]);
					a(this).find('.elementor-price-table__fractional-part').html(priceval1[1]);
					a(this).find('.elementor-price-table__after-price').show();
				}
			}
		})
	});
	
})}(jQuery);
