!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-image-carousel').length > 0) {
			a('.elementor-widget-image-carousel .swiper-container').each(function(){
				editorid = a(this).closest('.elementor-widget-image-carousel').attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				newSlides = a(this).find(".swiper-wrapper").children('.swiper-slide').clone(true);
				a(this).find(".elementor-main-swiper").remove();
				a(this).append('<div class="elementor-main-swiper" ><div class="swiper-wrapper" ></div></div>');
				swiperWrapper = a(this).find(".swiper-wrapper");
				swiperWrapper.empty().append(newSlides);
				a(this).find(".swiper-slide-shadow-left").remove();
				a(this).find(".swiper-slide-shadow-right").remove();
				a(this).find(".swiper-slide-duplicate").remove();
				a(this).addClass('element-border');
				a('.elementor-widget-image-carousel .swiper-wrapper .swiper-slide').each(function(){
					//a(this).addClass('element-border');
				})
				mySwiper = new Swiper('.elementor-widget-image-carousel .elementor-main-swiper', {});
				mySwiper.destroy(true,true);
				
			})
			
			a('.elementor-widget-image-carousel').addClass('element-border');
			//var slidenum = a('.elementor-widget-image-carousel .elementor-image-carousel > div').length;
			//a('.elementor-widget-image-carousel .elementor-image-carousel ').css("width",(slidenum * 180))
		}
		a(document).on('click','.live-editor .elementor-widget-image-carousel .swiper-slide',function(){
			editorid = a(this).closest('.elementor-widget-image-carousel').attr('data-id');
			if(a.inArray( editorid, elements_data ) > -1){
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a.resetFocus(editorid);
			}
			
			imgElem = a(this).find('img');
			indexval = a(this).index();
			var frame = wp.media({
					title: '',
					button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			frame.on('select', () => {
				var attachment = frame.state().get('selection').first().toJSON();
				//oldurl = getoldurlCro(imgElem);
				imgElem.attr('src',attachment.sizes['full'].url);
				imgElem.attr('srcset','');
				imgElem.addClass('edited-imagecal');
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['imagecal'] = {};
					
				}
				settingData[id]['imagecal'][indexval] = {};
				settingData[id]['imagecal'][indexval]['image'] = con;
				settingData[id]['imagecal'][indexval]['id'] = attachment.id;
				//settingData[id]['imagecal'][indexval]['old'] = oldurl; 
			});
			frame.open();
			return false;
			
		});
	});
	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-image-carousel').length > 0) {
			window.location.reload();
			return false;
		}
		
	})
})}(jQuery);
