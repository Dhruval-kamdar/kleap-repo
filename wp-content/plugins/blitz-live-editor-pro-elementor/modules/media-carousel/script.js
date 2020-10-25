!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-media-carousel .elementor-main-swiper').length > 0){
			a('.elementor-widget-media-carousel .elementor-swiper').each(function(){
				editorid = a(this).closest('.elementor-widget-media-carousel').attr('data-id');
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
				a('.elementor-widget-media-carousel .swiper-wrapper .swiper-slide').each(function(){
					a(this).addClass('element-border');
					if(a(this).find("a").length > 0){
						hrefhtml = a(this).find("a").attr("href");
						vhrefhtml = a(this).find("a").attr("data-elementor-lightbox-video");
						if (typeof vhrefhtml === 'undefined') {
							vhrefhtml = '';
						}
						htmlnew = a(this).find("a").html();
						a(this).append(htmlnew);
						a(this).find(".elementor-carousel-image").attr("href",hrefhtml);
						a(this).find(".elementor-carousel-image").attr("vhref",vhrefhtml);
						a(this).find("a").remove();
						
					}else{
						a(this).find(".elementor-carousel-image").attr("href",'');
						a(this).find(".elementor-carousel-image").attr("vhref",'');
					}
				})
				mySwiper = new Swiper('.elementor-widget-media-carousel .elementor-main-swiper', {});
				mySwiper.destroy(true,true);
			})
			a(document).on('click','.elementor-widget-media-carousel .elementor-main-swiper .swiper-slide',function (event) {
				event.preventDefault();
				var dataid = a(this).closest('.elementor-widget-media-carousel').attr('data-id');
				var  indexVal = a(this).index();
				if(a('.elementor-element-'+dataid).hasClass('element-locked')){
					return false;
				}
				videolink = a(this).find('.elementor-carousel-image').attr('vhref');
				var url = a(this).find('.elementor-carousel-image').attr('href');
				htmlContent = '<div class="selectbox-icon-box2 elementor-carousel-image"><p><label>Url : </label> <input id="ewmc_url"  value="'+url+'" name="ewmc_url" type="text" placeholder=""></p>';
				if(videolink != ''){
					htmlContent += '<p><label>Video Link : </label> <input id="ewmc_vurl"  name="ewmc_vurl" value="'+videolink+'" type="text" placeholder=""></p>';
				}
				a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_ewmc">t</div>');
				htmlContent += '<p class="btn-popup"><button data-id="'+dataid+'" data-index ="'+indexVal+'">Change Image</button> </p></div>';
				var elemnt = a(this);
				a.tinymceInit('.hiddenmce',"","",function(editor){
					tinymce.get(editor.id).focus();
					
					tinymce.activeEditor.windowManager.open({
					  title: 'Media Carousel', // The dialog's title - displayed in the dialog header
					  body: {
						type: 'panel', // The root body type - a Panel or TabPanel
						items: [ // A list of panel components
						  {
							type: 'htmlpanel', // A HTML panel component
							html: htmlContent
						  }
						]
					  },
					  buttons: [ // A list of footer buttons
						{
						  type: 'custom',
						  text: 'Select',
						}
					  ],
					   onAction: function (api) {
						   saveMediaCarousel(dataid,indexVal);
						   api.close();
						},
						onClose: function (api) {
							tinymce.get(editor.id).destroy();
							a("#hiddenmce_ewmc").remove();
						}
					});
				})
				
				return false;
			})
			function saveMediaCarousel(dataid,indexval){
				indexval1 = parseInt(indexval) + 1;
				if(!settingData[dataid]){
					settingData[dataid] = {};
					settingData[dataid]['mediaCarousel'] = {};
				}
				if(!settingData[dataid]['mediaCarousel'][indexval]){
					settingData[dataid]['mediaCarousel'][indexval] = {};
				}
				var ewmc_url = a(".tox-form__group").find("#ewmc_url").val();
				settingData[dataid]['mediaCarousel'][indexval]['url'] = ewmc_url;
				a('.elementor-element-'+editorid).find('.swiper-slide:nth-child('+(indexval1)+') .elementor-carousel-image').attr('href',ewmc_url);
				if( a(".tox-form__group").find("#ewmc_url").length > 0){
					var ewmc_vurl = a(".tox-form__group").find("#ewmc_vurl").val();
					settingData[dataid]['mediaCarousel'][indexval]['vurl'] = ewmc_vurl;
					
					a('.elementor-element-'+editorid).find('.swiper-slide:nth-child('+(indexval1)+') .elementor-carousel-image').attr('vhref',ewmc_vurl);
				}
				
				var url = a(this).find('.elementor-carousel-image').attr('href');
				
			}
			a(document).on('click','.live-editor .tox-form__group .selectbox-icon-box2.elementor-carousel-image p button',function(){
				editorid = a(this).attr('data-id');
				indexval = a(this).attr('data-index');
				tinymce.get('hiddenmce_ewmc').destroy();
				a("#hiddenmce_ewmc").remove();
				indexval1 = parseInt(indexval) + 1;
				imgElem = a('.elementor-element-'+editorid).find('.swiper-slide:nth-child('+(indexval1)+') .elementor-carousel-image');
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
					imgElem.addClass('edited-mediaCarousel');
					var id = editorid;
					var con = attachment.sizes['full'].url;
					if(!settingData[id]){
						settingData[id] = {};
						settingData[id]['mediaCarousel'] = {};
						
					}
					if(!settingData[id]['mediaCarousel'][indexval]){
						settingData[id]['mediaCarousel'][indexval] = {};
					}
					settingData[id]['mediaCarousel'][indexval]['image'] = con;
					settingData[id]['mediaCarousel'][indexval]['id'] = attachment.id;
				});
				frame.open();
				return false;
			});
		}
	});
	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-widget-media-carousel .elementor-main-swiper').length > 0) {
			window.location.reload();
			return false;
		}
	})
	
	
})}(jQuery);
