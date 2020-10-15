!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackStarRating(editor){
			canExit = false;
			var id =  a("#"+editor.id).closest('.elementor-widget-star-rating').attr('data-id');
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
				var con = editor.getContent({format: 'raw'});
				var con1 = a.strip_tags(con,'<strong><em><span><a>');
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['starrating'] = {};
					
				}
				settingData[id]['starrating']['title'] = con1;
			});
		}
		if(a('.elementor-widget-star-rating .elementor-star-rating__wrapper').length > 0){
			a('.elementor-widget-star-rating .elementor-star-rating__wrapper').each(function(){
				editorid = a(this).closest('.elementor-widget-star-rating').attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a(document).on('click','.elementor-element-'+editorid+' .elementor-star-rating',function (event) {
					event.preventDefault();
					var dataid = a(this).closest('.elementor-widget-star-rating').attr('data-id');
					a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_star-rating">t</div>');
					
					var ratingvalue = a(this).attr('title');
					ratingvalue1 = ratingvalue.split('/');
					ratingScale = 5;
					ratingScale5 = 'selected="selected"';
					ratingScale10 = '';
					if(ratingScale != parseInt(ratingvalue1[1])){
						ratingScale = 10;
						ratingScale10 = 'selected="selected"';
						ratingScale5 = '';
					}
					iconRatingf = 'selected="selected"';
					iconRatingu = '';
					if(!a('.elementor-element-'+dataid).hasClass('elementor--star-style-star_fontawesome')){
						iconRatingu = 'selected="selected"';
						iconRatingf = '';
					}
					var alignr = alignl = alignc = alignj = '';
					if(a('.elementor-element-'+dataid).hasClass('elementor-star-rating--align-right')){
						alignr ='selected="selected"';
					}
					if(a('.elementor-element-'+dataid).hasClass('elementor-star-rating--align-center')){
						alignc ='selected="selected"';
					}
					if(a('.elementor-element-'+dataid).hasClass('elementor-star-rating--align-left')){
						alignl ='selected="selected"';
					}
					if(a('.elementor-element-'+dataid).hasClass('elementor-star-rating--align-justify')){
						alignj ='selected="selected"';
					}
					starStyles = '';
					starStyleo = 'selected="selected"';
					htmlstar = a('.elementor-element-'+dataid).find('.elementor-star-empty').html();
					if(htmlstar == '' || htmlstar == '★'){
						starStyleo = '';
						starStyles = 'selected="selected"';
					}
					var htmlContent = '<div class="selectbox-icon-box2 elementor-carousel-image"><p><label>Rating Scale : </label> <select id="ratingScale"><option value="5" '+ratingScale5+'>0-5</option><option value="10" '+ratingScale10+'>0-10</option></select></p><p><label>Rating : </label> <input  id="rating" type="number" min="0" max="10" step="0.1" value="'+ratingvalue1[0]+'" ></p><p><label>Icon : </label> <select '+iconRatingf+' id="iconRating"><option value="star_fontawesome">Font Awesome</option><option '+iconRatingu+' value="star_unicode">Unicode</option></select></p><p><label>Unmarked Style : </label> <select id="star_style"><option '+starStyleo+' value="outline">Outline</option><option '+starStyles+' value="solid">Solid</option></select></p><p><label>Alignment : </label> <select id="alignment"><option '+alignl+' value="left">Left</option><option '+alignc+' value="center">Center</option><option value="right" '+alignr+'>Right</option><option  '+alignj+' value="justify">Justified</option></select></p></div>';
					
					
					var elemnt = a(this);
					a.tinymceInit('.hiddenmce',"","",function(editor){
						tinymce.get(editor.id).focus();
						tinymce.activeEditor.windowManager.open({
						  title: 'Star Rating', // The dialog's title - displayed in the dialog header
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
							   saveStarRating(dataid);
							   api.close();
							},
							onClose: function (api) {
								tinymce.get(editor.id).destroy();
								a("#hiddenmce_star-rating").remove();
							}
						});
					})
					return false;
				})
				
				function saveStarRating(dataid){
					if(!settingData[dataid]){
						settingData[dataid] = {};
						settingData[dataid]['starrating'] = {};
					}
					var rating_scale = a(".tox-form__group").find("#ratingScale").val();
					var rating = a(".tox-form__group").find("#rating").val();
					var iconRating = a(".tox-form__group").find("#iconRating").val();
					var star_style = a(".tox-form__group").find("#star_style").val();
					var alignment = a(".tox-form__group").find("#alignment").val();
					settingData[dataid]['starrating']['rating_scale'] = rating_scale;
					settingData[dataid]['starrating']['rating'] = rating;
					settingData[dataid]['starrating']['align'] = alignment;
					settingData[dataid]['starrating']['star_style'] = iconRating;
					settingData[dataid]['starrating']['unmarked_star_style'] = star_style;
					
					a('.elementor-element-'+dataid).removeClass('elementor-star-rating--align-right');
					a('.elementor-element-'+dataid).removeClass('elementor-star-rating--align-left');
					a('.elementor-element-'+dataid).removeClass('elementor-star-rating--align-center');
					a('.elementor-element-'+dataid).removeClass('elementor-star-rating--align-justify');
					a('.elementor-element-'+dataid).removeClass('elementor--star-style-star_unicode');
					a('.elementor-element-'+dataid).removeClass('elementor--star-style-star_fontawesome');
					a('.elementor-element-'+dataid).addClass('elementor--star-style-'+ iconRating);
					a('.elementor-element-'+dataid).addClass('elementor-star-rating--align-'+alignment);
					title = rating+'/'+rating_scale;
					a('.elementor-element-'+dataid).find('.elementor-star-rating').attr('title',title);
					htmlicon = '';
					icont = '&#9734;';
					if(star_style == 'outline' && iconRating == 'star_fontawesome'){
						icont = '&#xE933;';
					}
					if(star_style == 'solid' && iconRating == 'star_fontawesome'){
						icont = '&#xE934;';
					}
					if(star_style == 'solid' && iconRating == 'star_unicode'){
						icont = '&#9733;';
					}
					rating2 = '';
					if(rating.indexOf('.') > 0){
						rating1 = rating.split('.');
						rating2 = parseInt(rating1[1]);
					}
					for(i=0;i<rating_scale;i++){
						if(i < parseInt(rating)){
							htmlicon = htmlicon + '<i class="elementor-star-full">'+icont+'</i>';
						}else if(i == parseInt(rating)){
							htmlicon = htmlicon + '<i class="elementor-star-'+parseInt(rating2)+'">'+icont+'</i>';
						}else{
							htmlicon = htmlicon + '<i class="elementor-star-empty">'+icont+'</i>';
						}
					}
					a('.elementor-element-'+dataid).find('.elementor-star-rating').html(htmlicon);
				}
				
				a.tinymceInit('.elementor-element-'+editorid+'  .elementor-star-rating__title',"","bold italic underline",callbackStarRating);
			})
		}
	});

	//~ a(document).on("liveEditorremove",function (e) {
	//~ })
})}(jQuery);
