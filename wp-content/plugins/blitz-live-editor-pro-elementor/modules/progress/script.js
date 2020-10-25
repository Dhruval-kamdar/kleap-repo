!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackProgress(editor){
			canExit = false;
			var id =  a("#"+editor.id).closest('.elementor-widget-progress').attr('data-id');
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
					settingData[id]['progress'] = {};
					
				}
				settingData[id]['progress']['title'] = con1;
			});
		}
		if(a('.elementor-widget-progress').length > 0){
			a('.elementor-widget-progress .elementor-widget-container').each(function(){
				editorid = a(this).closest('.elementor-widget-progress').attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a(document).on('click','.elementor-element-'+editorid+' .elementor-progress-wrapper',function (event) {
					event.preventDefault();
					var dataid = a(this).closest('.elementor-widget-progress').attr('data-id');
					a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_progress">t</div>');
					
					var percent = a(this).find('.elementor-progress-bar').attr('data-max');
					var innertext = a(this).find('.elementor-progress-text').text();
					var displayPercentage = a(this).find('.elementor-progress-percentage').length;
					displayPercentageh = 'selected';
					if(displayPercentage > 0){
						displayPercentageh = '';
					}
					successt = infot = warningt = dangert = '';
					if(a(this).hasClass('progress-success')){
						successt = 'selected';
					}
					if(a(this).hasClass('progress-info')){
						infot = 'selected';
					}
					if(a(this).hasClass('progress-warning')){
						warningt = 'selected';
					}
					if(a(this).hasClass('progress-danger')){
						dangert = 'selected';
					}
					var htmlContent = '<div class="selectbox-icon-box2 elementor-carousel-image"><p><label>Type : </label> <select id="progress_type"><option value="info" '+infot+'>Info</option><option value="success" '+successt+'>Success</option><option value="warning" '+warningt+'>Warning</option><option value="danger" '+dangert+'>Danger</option></select></p><p><label>Percentage : </label> <input  id="percentage" type="number" min="0" max="100" step="1" value="'+percent+'" ></p><p><label>Display Percentage : </label> <select  id="displayPercentage"><option value="show">Show</option><option '+displayPercentageh+' value="hide">Hide</option></select></p><p><label>Inner Text : </label> <input  id="innertext" type="text" value="'+innertext+'" ></p></div>';
					var elemnt = a(this);
					a.tinymceInit('.hiddenmce',"","",function(editor){
						tinymce.get(editor.id).focus();
						tinymce.activeEditor.windowManager.open({
						  title: 'Progress', // The dialog's title - displayed in the dialog header
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
							   saveProgress(dataid);
							   api.close();
							},
							onClose: function (api) {
								tinymce.get(editor.id).destroy();
								a("#hiddenmce_progress").remove();
							}
						});
					})
					return false;
				})
				
				function saveProgress(dataid){
					if(!settingData[dataid]){
						settingData[dataid] = {};
						settingData[dataid]['progress'] = {};
					}
					var progress_type = a(".tox-form__group").find("#progress_type").val();
					var percentage = a(".tox-form__group").find("#percentage").val();
					var displayPercentage = a(".tox-form__group").find("#displayPercentage").val();
					var innertext = a(".tox-form__group").find("#innertext").val();
					settingData[dataid]['progress']['progress_type'] = progress_type;
					settingData[dataid]['progress']['size'] = percentage;
					settingData[dataid]['progress']['inner_text'] = innertext;
					settingData[dataid]['progress']['display_percentage'] = displayPercentage;
					
					a('.elementor-element-'+dataid).find('.elementor-progress-wrapper').removeClass('progress-success');
					a('.elementor-element-'+dataid).find('.elementor-progress-wrapper').removeClass('progress-info');
					a('.elementor-element-'+dataid).find('.elementor-progress-wrapper').removeClass('progress-warning');
					a('.elementor-element-'+dataid).find('.elementor-progress-wrapper').removeClass('progress-danger');
					a('.elementor-element-'+dataid).find('.elementor-progress-wrapper').addClass('progress-'+ progress_type);
					a('.elementor-element-'+dataid).find('.elementor-progress-text').html(innertext);
					a('.elementor-element-'+dataid).find('.elementor-progress-bar').attr('data-max',percentage);
					a('.elementor-element-'+dataid).find('.elementor-progress-bar').width(percentage+'%');
					a('.elementor-element-'+dataid).find('.elementor-progress-percentage').remove();
					if(displayPercentage == 'show'){
						a('.elementor-element-'+dataid).find('.elementor-progress-bar').append('<span class="elementor-progress-percentage">'+percentage+'%'+'</span>');
					}
				}
				
				a.tinymceInit('.elementor-element-'+editorid+'  .elementor-title',"","bold italic underline",callbackProgress);
			})
		}
	});

	//~ a(document).on("liveEditorremove",function (e) {
	//~ })
})}(jQuery);
