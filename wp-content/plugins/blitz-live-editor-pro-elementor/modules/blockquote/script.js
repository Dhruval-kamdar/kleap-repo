!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackBlockquote(editor){
			canExit = false;
			var id =  a("#"+editor.id).closest('.elementor-widget-blockquote').attr('data-id');
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
					settingData[id]['blockquote'] = {};
					
				}
				
				if( a("#"+editor.id).hasClass('elementor-blockquote__content')){
					settingData[id]['blockquote']['content'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-blockquote__author')){
					settingData[id]['blockquote']['author'] = con1;
				}
				if( a("#"+editor.id).hasClass('elementor-blockquote__tweet-label')){
					settingData[id]['blockquote']['tweetlabel'] = con1;
				}
				
				
			});
		}
		if(a('.elementor-widget-blockquote').length > 0){
			a('.elementor-widget-blockquote').each(function(){
				editorid = a(this).attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a.tinymceInit('.elementor-element-'+editorid+'  .elementor-blockquote__content,.elementor-element-'+editorid+'  .elementor-blockquote__author,.elementor-element-'+editorid+'  .elementor-blockquote__tweet-label',"","bold italic underline",callbackBlockquote);
			})
		}
	});

	//~ a(document).on("liveEditorremove",function (e) {
	//~ })
})}(jQuery);
