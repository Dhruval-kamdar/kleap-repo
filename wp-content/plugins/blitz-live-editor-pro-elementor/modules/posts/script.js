!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackPostTextEditor(editor){
			canExit = false;
			var id = a("#"+editor.id).closest('.elementor-widget-posts').attr('data-id');
			a("#"+editor.id).addClass('element-border');
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
				var con = editor.getContent({format: 'text'});
				var pid = a("#"+editor.id).closest('.elementor-post').attr('class');
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
					if(!settingData[id]){
						settingData[id] = {};
					}
					if(!settingData[id]['posts']){
						settingData[id]['posts'] = {};
					}
					if(!settingData[id]['posts'][postid]){
						settingData[id]['posts'][postid] = {};
					}
					if(a("#"+editor.id).closest('.elementor-post__title').length > 0){
						settingData[id]['posts'][postid]['title'] = con;
					}else{
						settingData[id]['posts'][postid]['excerpt'] = con;
					}
				}
			});
		}
		
		if(a('.elementor-widget-posts').length > 0){
			a('.elementor-widget-posts').addClass('element-border');
			a.tinymceInit('.elementor-widget-posts .elementor-post__title,.elementor-widget-posts .elementor-post__excerpt',"","",callbackPostTextEditor);
			
			a(document).on('click','.elementor-widget-posts .elementor-post__thumbnail__link',function(){
				id = a(this).closest('.elementor-widget-posts').attr('data-id');
				if(a('.elementor-element-'+id).hasClass('element-locked')){
					return false;
				}
				imgElem = a(this).find("img");
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
