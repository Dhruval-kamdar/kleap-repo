!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackNavTextEditor(editor){
			canExit = false;
			var id = a("#"+editor.id).closest('.elementor-widget-nav-menu').attr('data-id');
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
				var pid = a("#"+editor.id).closest('.menu-item').attr('class');
				var classes = pid.split(" ");
				var postid = 0;
				if(classes.length > 0){
					for(x in classes){
						if(classes[x].indexOf("menu-item-") == 0){
							var postid1 = classes[x].split("menu-item-")[1];
							if(parseInt(postid1) > 0){
								var postid = parseInt(postid1);
							}
						}
					}
				
				}
				a("#"+editor.id).closest('.menu-item').find('a').text(con);
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
					settingData[id]['posts'][postid]['title'] = con;
				}
			});
		}
		
		if(a('.elementor-widget-nav-menu').length > 0){
			//a('.elementor-widget-nav-menu').addClass('element-border');
			a('.elementor-widget-nav-menu  .elementor-nav-menu .menu-item').each(function(){
				var text1 = a(this).text();
				a(this).find('a').hide();
				a(this).append('<span class="elementor-item-span">'+text1+'</span>');
				
			})
			a.tinymceInit('.elementor-widget-nav-menu  .elementor-nav-menu .menu-item span',"","",callbackNavTextEditor);
		}
	});
	a(document).on("liveEditorremove",function (e) {
		a('.elementor-widget-nav-menu  .elementor-nav-menu .menu-item').each(function(){
			a(this).find('a').show();
			a(this).find('span').remove();
		})
	});
	
})}(jQuery);
