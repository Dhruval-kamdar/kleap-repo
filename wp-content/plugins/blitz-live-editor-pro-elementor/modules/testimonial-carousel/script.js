!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		
		function callbackTestimonialCarousel(editor){
			canExit = false;
			var id =  a("#"+editor.id).closest('.elementor-widget-testimonial-carousel').attr('data-id');
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
				indexval = a("#"+editor.id).closest('.swiper-slide').index();
				indexval1 = parseInt(indexval) + 1;
				var con = editor.getContent({format: 'raw'});
				var con1 = a.strip_tags(con,'<strong><em><span><a>');
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['testimonialCarousel'] = {};
					
				}
				if(!settingData[id]['testimonialCarousel'][indexval]){
					settingData[id]['testimonialCarousel'][indexval] = {};
				}
				if( a("#"+editor.id).hasClass('elementor-testimonial__text')){
					settingData[id]['testimonialCarousel'][indexval]['content'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-testimonial__title')){
					settingData[id]['testimonialCarousel'][indexval]['title'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-testimonial__name')){
					settingData[id]['testimonialCarousel'][indexval]['name'] = con1;
				}
			});
		}
		if(a('.elementor-widget-testimonial-carousel .elementor-main-swiper').length > 0){
			a('.elementor-widget-testimonial-carousel .elementor-swiper').each(function(){
				editorid = a(this).closest('.elementor-widget-testimonial-carousel').attr('data-id');
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
				a('.elementor-widget-testimonial-carousel .swiper-wrapper .swiper-slide').each(function(){
					//a(this).addClass('element-border');
				})
				mySwiper = new Swiper('.elementor-widget-testimonial-carousel .elementor-main-swiper', {});
				mySwiper.destroy(true,true);
				
				a.tinymceInit('.elementor-widget-testimonial-carousel  .elementor-testimonial__text,.elementor-widget-testimonial-carousel  .elementor-testimonial__name,.elementor-widget-testimonial-carousel  .elementor-testimonial__title',"","bold italic underline",callbackTestimonialCarousel);
			})
			a(document).on('click','.live-editor .elementor-widget-testimonial-carousel  .elementor-testimonial__image',function(){
				editorid = a(this).closest('.elementor-widget-testimonial-carousel').attr('data-id');
				indexval = a(this).closest('.swiper-slide').index();
				indexval1 = parseInt(indexval) + 1;
				imgElem = a('.elementor-element-'+editorid).find('.swiper-slide:nth-child('+(indexval1)+') .elementor-testimonial__image img');
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
					var id = editorid;
					var con = attachment.sizes['full'].url;
					if(!settingData[id]){
						settingData[id] = {};
						settingData[id]['testimonialCarousel'] = {};
					}
					if(!settingData[id]['testimonialCarousel'][indexval]){
						settingData[id]['testimonialCarousel'][indexval] = {};
					}
					settingData[id]['testimonialCarousel'][indexval]['image'] = con;
					settingData[id]['testimonialCarousel'][indexval]['id'] = attachment.id;
				});
				frame.open();
				return false;
			});
		}
	});
	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-widget-testimonial-carousel .elementor-main-swiper').length > 0) {
			window.location.reload();
			return false;
		}
	})
	
	
})}(jQuery);
