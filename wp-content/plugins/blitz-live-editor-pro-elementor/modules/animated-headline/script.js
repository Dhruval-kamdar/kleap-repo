!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackAnimatedTextEditor(editor){
			canExit = false;
			var id = a("#"+editor.id).closest('.elementor-widget-animated-headline').attr('data-id');
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

			editor.on('keyup', function(){
				var con = editor.getContent({format: 'text'});
				if(!settingData[id]){
					settingData[id] = {};
				}
				if(!settingData[id]['animated']){
					settingData[id]['animated'] = {};
				}
				var pid = a("#"+editor.id).closest('.elementor-headline-dynamic-wrapper-edit').length;
				if(pid > 0){
					if(!settingData[id]['animated']['dynamic']){
						settingData[id]['animated']['dynamic'] = {};
					}
					spanlen = a("#"+editor.id).index();
					settingData[id]['animated']['dynamic'][spanlen] = con;
				}else{
					if(!settingData[id]['animated']['static']){
						settingData[id]['animated']['static'] = {};
					}
					spanlen = a("#"+editor.id).index();
					settingData[id]['animated']['static'][spanlen] = con;
				}
			});
		}
		
		if(a('.elementor-widget-animated-headline').length > 0){
			a('.elementor-widget-animated-headline').each(function(){
				var spanhtml = '<span class="elementor-headline-dynamic-wrapper-edit">';
				a(this).find('.elementor-headline-dynamic-wrapper > span').each(function(){
					var text1 = a(this).text();
					spanhtml = spanhtml + '<span class="elementor-headline-dynamic-text">'+text1+'</span>';
				})
				a( spanhtml+'</span>' ).insertBefore( a(this).find('.elementor-headline-dynamic-wrapper') );
				a(this).find('.elementor-headline-dynamic-wrapper').hide();
			})
			a.tinymceInit('.elementor-widget-animated-headline  .elementor-headline-dynamic-wrapper-edit  .elementor-headline-dynamic-text ,.elementor-widget-animated-headline  .elementor-headline-plain-text',"","",callbackAnimatedTextEditor);
		}
	});
	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-widget-animated-headline').length > 0){
			window.location.reload();
			return false;
		}
		
	});
	
})}(jQuery);
