!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		
		a('.elementor-widget-video').each(function () {
			var dataid = a(this).attr('data-id');
			//if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
				a('.elementor-element-'+dataid).addClass('element-border');
				a('.elementor-element-'+dataid).append('<div class="video-edit">Edit</div>')
			//}
		})
		
		a(document).on('click','.elementor-widget-video',function (event) {
			event.preventDefault();
			var dataid = a(this).attr('data-id');
			a.resetFocus(dataid);
			if(a('.elementor-element-'+dataid).hasClass('element-locked')){
				return false;
			}
			a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_video">t</div>');
			var elemnt = a(this);
			video_url  = a(this).find('.elementor-video-iframe').attr('src');
			var htmlContent = '<div><label></label> <input type="text" id="video_url" value="'+video_url+'"/></div>';
			a.tinymceInit('#hiddenmce_video',"","",function(editor){
				tinymce.get(editor.id).focus();
				tinymce.activeEditor.windowManager.open({
				  title: 'Change Url', // The dialog's title - displayed in the dialog header
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
					  text: 'Update',
					}
				  ],
				   onAction: function (api) {
						var video_url = a(".tox-form__group").find("#video_url").val();
						if(video_url == ''){
							video_url = '#';
						}
						if(!settingData[dataid]){
							settingData[dataid] = {};
						}
						settingData[dataid]['url'] = video_url;
						id = convertUrlEmbed(video_url);
						if(id == ''){
							id = video_url;
						}
						elemnt.find('.elementor-video-iframe').attr('src',id);
						api.close();
					},
					onClose: function (api) {
						tinymce.get(editor.id).destroy();
						a("#hiddenmce_video").remove();
					}
				});
			})
			return false;
		})
		
		function convertUrlEmbed(url){
			var newval = newval1 = embeurl = '';
			if(url.indexOf('youtu') > 0){
				if (newval = url.match(/(\?|&)v=([^&#]+)/)) {
					newval1 = newval.pop();
				} else if (newval = url.match(/(\.be\/)+([^\/]+)/)) {
					newval1 = newval.pop();
				} else if (newval = url.match(/(\embed\/)+([^\/]+)/)) {
					newval1 = newval.pop().replace('?rel=0','');
				}
				embeurl = 'https://www.youtube.com/embed/'+newval1+'?feature=oembed&start&end&wmode=opaque&loop=0&controls=1&mute=0&rel=0&modestbranding=0';
			}
			if(url.indexOf('vimeo') > 0){
				var firstPart = url.split('?')[0].split("/");
				var vid = firstPart[firstPart.length - 1];
				embeurl = 'https://player.vimeo.com/video/'+vid+'#t=0';
			}
			if(url.indexOf('dailymotion') > 0){
				var newval1 = getDailyMotionId(url);
				embeurl = 'https://dailymotion.com/embed/video/'+newval1;
			}
			return embeurl;
		}
		
		function getDailyMotionId(url) {
			var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
			if (m !== null) {
				if(m[4] !== undefined) {
					return m[4];
				}
				return m[2];
			}
			return null;
		}
	});
	
})}(jQuery);
