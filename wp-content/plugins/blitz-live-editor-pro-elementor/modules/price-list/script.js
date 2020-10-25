!function(a){a(document).ready(function(){
	function callbackPriceListText(editor){
		canExit = false;
		var id =  a("#"+editor.id).closest('.elementor-widget-price-list').attr('data-id');
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
		editor.on('change', function(){
			var con = editor.getContent({format: 'raw'});
			var con1 = a.strip_tags(con,'<strong><em><span><a>'); 
			if(!settingData[id]){
				settingData[id] = {};
			}
			if(!settingData[id]['pricelist']){
				settingData[id]['pricelist'] = {};
			}
			var indexvalue = a("#"+editor.id).closest('li').index();
			if(!settingData[id]['pricelist'][indexvalue]){
				settingData[id]['pricelist'][indexvalue] = {};
			}
			
			if( a("#"+editor.id).hasClass('elementor-price-list-title')){
				settingData[id]['pricelist'][indexvalue]['title'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-list-price')){
				settingData[id]['pricelist'][indexvalue]['price'] = con1;
			}
			if( a("#"+editor.id).hasClass('elementor-price-list-description')){
				settingData[id]['pricelist'][indexvalue]['description'] = con1;
			}
		});
	}
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-widget-price-list .elementor-price-list-title',"","bold italic underline",callbackPriceListText);
		a.tinymceInit('.elementor-widget-price-list .elementor-price-list-price',"","bold italic underline",callbackPriceListText);
		a.tinymceInit('.elementor-widget-price-list .elementor-price-list-description',"","bold italic underline",callbackPriceListText);
		
		a(document).on('click','.elementor-widget-price-list .elementor-price-list-image',function(){
			editorid = a(this).closest('.elementor-widget-price-list').attr('data-id');
			if(a.inArray( editorid, elements_data ) > -1){
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a.resetFocus(editorid);
			}
			
			imgElem = a(this).find('img');
			indexval = a(this).closest('li').index();
			var frame = wp.media({
					title: '',
					button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			frame.on('select', () => {
				var attachment = frame.state().get('selection').first().toJSON();
				imgElem.attr('src',attachment.sizes['full'].url);
				imgElem.attr('srcset','');
				imgElem.addClass('edited-imggal');
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
				}
				if(!settingData[id]['pricelist']){
					settingData[id]['pricelist'] = {};
				}
				if(!settingData[id]['pricelist'][indexval]){
					settingData[id]['pricelist'][indexval] = {};
				}
				settingData[id]['pricelist'][indexval] = {};
				settingData[id]['pricelist'][indexval]['image'] = con;
				settingData[id]['pricelist'][indexval]['id'] = attachment.id;
			});
			frame.open();
			return false;
			
		});
		
	});
	
})}(jQuery);
