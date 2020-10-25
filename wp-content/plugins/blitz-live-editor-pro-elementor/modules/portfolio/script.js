!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		if(a('.elementor-widget-portfolio').length > 0){
			a('.elementor-widget-portfolio').addClass('element-border');
			a(document).on('click','.elementor-widget-portfolio .elementor-post__thumbnail__link',function(){
				id = a(this).closest('.elementor-widget-portfolio').attr('data-id');
				if(a('.elementor-element-'+id).hasClass('element-locked')){
					return false;
				}
				imgElem = a(this).find("img");
				if(imgElem.length > 0){
					
				}else{
					return false;
				}
				
				var pid = a(this).closest('.elementor-post').attr('class');
				var classes = pid.split(" ");
				var postid = 0;
				if(classes.length > 0){
					for(x in classes){
						if(classes[x].indexOf("post-") == 0){
							var postid = classes[x].split("-")[1];
						}
					}
				
				}
				if(postid > 0){
				
				}else{
					return false;
				}
				var frame = wp.media({
						title: '',
						button: {
						text: 'Select'
					},
					multiple: false  // Set to true to allow multiple files to be selected
				});
				frame.on('select', () => {
					var attachment = frame.state().get('selection').first().toJSON();
					var con = attachment.sizes['full'].url;
					
					if(!settingData[id]){
						settingData[id] = {};
					}
					if(!settingData[id]['posts']){
						settingData[id]['posts'] = {};
					}
					if(!settingData[id]['posts'][postid]){
						settingData[id]['posts'][postid] = {};
					}
					settingData[id]['posts'][postid]['fid'] = attachment.id;
					imgElem.attr('src',attachment.sizes['full'].url);
					imgElem.attr('srcset','');
					imgElem.addClass('edited-fimg');
				
				});
				frame.open();
				return false;
			});
		}
		
		
		
	});
	
})}(jQuery);
