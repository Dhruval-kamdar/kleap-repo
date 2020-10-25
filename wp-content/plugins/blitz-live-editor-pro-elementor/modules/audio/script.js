!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-audio').length > 0){
			var geturlParam = function(name,url){
				var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
				if (results==null) {
				   return false;
				}
				return decodeURI(results[1]) || 0;
			}

			a('.elementor-widget-audio .elementor-soundcloud-wrapper').each(function(){
				editorid = a(this).closest('.elementor-widget-audio').attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a(this).append('<div class="overlay-audio"></div>');
				a('.elementor-element-'+editorid).addClass('element-border');
				a(document).on('click','.elementor-element-'+editorid+' .elementor-soundcloud-wrapper .overlay-audio',function (event) {
					event.preventDefault();
					var dataid = a(this).closest('.elementor-widget-audio').attr('data-id');
					a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_audio">t</div>');
					var visualf =  auto_playf = buyingf = likingf = downloadf = sharingf = show_commentsf = show_playcountf = show_artworkf = show_userf ='';
					var srcUrl = a(this).closest('.elementor-widget-audio').find('iframe').attr('src');
					var visual = geturlParam('visual',srcUrl);
					var url = geturlParam('url',srcUrl);
					var auto_play = geturlParam('auto_play',srcUrl);
					var buying = geturlParam('buying',srcUrl);
					var liking = geturlParam('liking',srcUrl);
					var download = geturlParam('download',srcUrl);
					var sharing = geturlParam('sharing',srcUrl);
					var show_comments = geturlParam('show_comments',srcUrl);
					var show_playcount = geturlParam('show_playcount',srcUrl);
					var show_user = geturlParam('show_user',srcUrl);
					var show_artwork = geturlParam('show_artwork',srcUrl);
					if(visual != 'true'){
						visualf = 'selected';
					}
					if(auto_play != 'true'){
						auto_playf = 'selected';
					}
					if(buying != 'true'){
						buyingf = 'selected';
					}
					if(liking != 'true'){
						likingf = 'selected';
					}
					if(download != 'true'){
						downloadf = 'selected';
					}
					if(sharing != 'true'){
						sharingf = 'selected';
					}
					if(show_comments != 'true'){
						show_commentsf = 'selected';
					}
					if(show_playcount != 'true'){
						show_playcountf = 'selected';
					}
					if(show_user != 'true'){
						show_userf = 'selected';
					}
					if(show_artwork != 'true'){
						show_artworkf = 'selected';
					}
					var url = elements_audio_data[dataid];
					console.log(url);
					var htmlContent = '<div class="selectbox-icon-box2 elementor-audio"><p class="full"><label style="width:10%;">Src : </label> <input  id="srcUrl" type="text" value="'+url+'" style="width:80%;"></p><p><label>Visual Player : </label> <select  id="visual"><option value="true">Show</option><option '+visualf+' value="false">Hide</option></select></p><p><label>Autoplay : </label> <select  id="auto_play"><option value="yes">Show</option><option '+auto_playf+' value="false">Hide</option></select></p><p><label>Buy Button : </label> <select  id="buying"><option value="true">Show</option><option '+buyingf+' value="false">Hide</option></select></p><p><label>Like Button : </label> <select  id="liking"><option value="true">Show</option><option '+likingf+' value="false">Hide</option></select></p><p><label>Download Button : </label> <select  id="download"><option value="true">Show</option><option '+visualf+' value="false">Hide</option></select></p><p><label>Artwork : </label> <select  id="show_artwork"><option value="true">Show</option><option '+show_artworkf+' value="false">Hide</option></select></p><p><label>Share Button : </label> <select  id="sharing"><option value="true">Show</option><option '+sharingf+' value="false">Hide</option></select></p><p><label>Comments : </label> <select  id="show_comments"><option value="true">Show</option><option '+show_commentsf+' value="false">Hide</option></select></p><p><label>Play Counts : </label> <select  id="show_playcount"><option value="true">Show</option><option '+show_playcountf+' value="false">Hide</option></select></p><p><label>Username : </label> <select  id="show_user"><option value="true">Show</option><option '+show_userf+' value="false">Hide</option></select></p></div>';
					var elemnt = a(this);
					a.tinymceInit('.hiddenmce',"","",function(editor){
						tinymce.get(editor.id).focus();
						tinymce.activeEditor.windowManager.open({
						  title: 'Sound Cloud', // The dialog's title - displayed in the dialog header
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
							   saveAudio(dataid);
							   api.close();
							},
							onClose: function (api) {
								tinymce.get(editor.id).destroy();
								a("#hiddenmce_audio").remove();
							}
						});
					})
					return false;
				})
				
				function saveAudio(dataid){
					if(!settingData[dataid]){
						settingData[dataid] = {};
						settingData[dataid]['audio'] = {};
					}
					var srcUrl = a(".tox-form__group").find("#srcUrl").val();
					var visual = a(".tox-form__group").find("#visual").val();
					var auto_play = a(".tox-form__group").find("#auto_play").val();
					var show_artwork = a(".tox-form__group").find("#show_artwork").val();
					var buying = a(".tox-form__group").find("#buying").val();
					var liking = a(".tox-form__group").find("#liking").val();
					var download = a(".tox-form__group").find("#download").val();
					var sharing = a(".tox-form__group").find("#sharing").val();
					var show_comments = a(".tox-form__group").find("#show_comments").val();
					var show_playcount = a(".tox-form__group").find("#show_playcount").val();
					var show_user = a(".tox-form__group").find("#show_user").val();
					
					
					settingData[dataid]['audio']['link'] = srcUrl;
					if(visual == 'true'){
						settingData[dataid]['audio']['visual'] = visual;
					}
					if(auto_play != 'true'){
						settingData[dataid]['audio']['sc_auto_play'] = auto_play;
					}
					if(buying != 'true'){
						settingData[dataid]['audio']['sc_buying'] = buying;
					}
					if(liking != 'true'){
						settingData[dataid]['audio']['sc_liking'] = liking;
					}
					if(download != 'true'){
						settingData[dataid]['audio']['sc_download'] = download;
					}
					if(show_artwork != 'true'){
						settingData[dataid]['audio']['sc_show_artwork'] = show_artwork;
					}
					if(sharing != 'true'){
						settingData[dataid]['audio']['sc_sharing'] = sharing;
					}
					if(show_comments != 'true'){
						settingData[dataid]['audio']['sc_show_comments'] = show_comments;
					}
					if(show_playcount != 'true'){
						settingData[dataid]['audio']['sc_show_playcount'] = show_playcount;
					}
					if(show_user != 'true'){
						settingData[dataid]['audio']['sc_show_user'] = show_user;
					}
				}
			})
		}
	});

	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-widget-audio').length > 0) {
			window.location.reload();
			return false;
		}
	})
})}(jQuery);
