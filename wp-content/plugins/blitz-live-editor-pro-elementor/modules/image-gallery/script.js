!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		//a('.elementor-image-carousel').slick('unslick');
		//a('.elementor-widget-image-carousel').destroy();
		a('.elementor-widget-image-gallery').addClass('element-border');
		a('.elementor-widget-image-gallery .gallery-item').each(function(){
			a(this).find('.gallery-icon').append('<div class="editimage-gallery"></div>');
		})
		a(document).on('click','.elementor-widget-image-gallery .editimage-gallery',function(){
			editorid = a(this).closest('.elementor-widget-image-gallery').attr('data-id');
			if(a.inArray( editorid, elements_data ) > -1){
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a.resetFocus(editorid);
			}
			
			imgElem = a(this).parent().find('img');
			indexval = a(this).closest('.gallery-item').index();
			var frame = wp.media({
					title: '',
					button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			frame.on('select', () => {
				var attachment = frame.state().get('selection').first().toJSON();
				oldurl = getoldurl(imgElem);
				imgElem.attr('src',attachment.sizes['full'].url);
				imgElem.attr('srcset','');
				imgElem.addClass('edited-imggal');
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['imagegal'] = {};
					
				}
				settingData[id]['imagegal'][indexval] = {};
				settingData[id]['imagegal'][indexval]['image'] = con;
				settingData[id]['imagegal'][indexval]['id'] = attachment.id;
				settingData[id]['imagegal'][indexval]['old'] = oldurl;
			});
			frame.open();
			return false;
			
		});
		function getoldurl(imgElem){
			var src = imgElem.attr('src');
			var height = imgElem.attr('height');
			var width = imgElem.attr('width');
			rr = '-'+width+'x'+height;
			if(src.indexOf(rr) > -1){
				src = src.replace(rr,'')
			}
			return src;
		}
	});

})}(jQuery);
