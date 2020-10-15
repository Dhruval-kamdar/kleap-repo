!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		
		function callbackSlides(editor){
			canExit = false;
			var id =  a("#"+editor.id).closest('.elementor-widget-slides').attr('data-id');
			//a('.elementor-element-'+id).addClass('element-border');
			
			if(a(".floatingbar_box .admin_box").length == 0){
				if(a('.elementor-element-'+id).hasClass('element-locked')){
					tinymce.remove("#"+editor.id);
				}
			}
			editor.on('click', function(){
				a.resetBG();
				a.resetFocus(id);
			});
			editor.on('input', function(){
				indexval = a("#"+editor.id).closest('.swiper-slide').index();
				indexval1 = parseInt(indexval) + 1;
				var con = editor.getContent({format: 'raw'});
				var con1 = a.strip_tags(con,'<strong><em><span>');
				//console.log(con1);
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['slidecal'] = {};
					
				}
				if(!settingData[id]['slidecal'][indexval]){
					settingData[id]['slidecal'][indexval] = {};
				}
				if( a("#"+editor.id).hasClass('elementor-slide-heading')){
					settingData[id]['slidecal'][indexval]['heading'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-slide-description')){
					settingData[id]['slidecal'][indexval]['description'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-slide-button')){
					settingData[id]['slidecal'][indexval]['button'] = con1;
				}
			});
		}
		
		if(a('.elementor-slides-wrapper').length > 0) {
			a('.elementor-widget-slides .elementor-swiper').each(function(){
				editorid = a(this).closest('.elementor-widget-slides').attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				newSlides = a(this).find(".swiper-wrapper").children('.swiper-slide').clone(true);
				a(this).find(".elementor-main-swiper").remove();
				a(this).append('<div class="elementor-main-swiper" ><div class="swiper-wrapper elementor-slides" ></div></div>');
				swiperWrapper = a(this).find(".swiper-wrapper");
				swiperWrapper.empty().append(newSlides);
				a(this).find(".swiper-slide-shadow-left").remove();
				a(this).find(".swiper-slide-shadow-right").remove();
				a(this).find(".swiper-slide-duplicate").remove();
				a(this).addClass('element-border');
				a('.elementor-widget-slides .swiper-wrapper .swiper-slide').each(function(){
					a(this).find('.swiper-slide-contents').append('<span class="editimage-carousel-slide">Edit Image</span>');
				})
				
			})
			a.tinymceInit('.elementor-widget-slides  .elementor-slide-heading,.elementor-widget-slides  .elementor-slide-description,.elementor-widget-slides  .elementor-slide-button',"","bold italic underline",callbackSlides);
		}
		a(document).on('click','.elementor-widget-slides .editimage-carousel-slide',function(){
			editorid = a(this).closest('.elementor-widget-slides').attr('data-id');
			if(a.inArray( editorid, elements_data ) > -1){
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a.resetFocus(editorid);
			}
			
			imgElem = a(this).closest('.swiper-slide').find('.swiper-slide-bg');
			indexval = a(this).closest('.swiper-slide').index();
			var frame = wp.media({
					title: '',
					button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			frame.on('select', () => {
				var attachment = frame.state().get('selection').first().toJSON();
				imgElem.css('background-image','url('+attachment.sizes['full'].url+')');
				imgElem.addClass('edited-slidecal');
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['slidecal'] = {};
					
				}
				if(!settingData[id]['slidecal'][indexval]){
					settingData[id]['slidecal'][indexval] = {};
				}
				settingData[id]['slidecal'][indexval]['image'] = con;
				settingData[id]['slidecal'][indexval]['id'] = attachment.id;
			});
			frame.open();
			return false;
			
		});
	});
	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-widget-slides ').length > 0) {
			window.location.reload();
			return false;
		}
		
	})
})}(jQuery);
