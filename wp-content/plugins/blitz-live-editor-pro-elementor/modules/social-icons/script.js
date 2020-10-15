!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function formatStateSocial (state) {
		  if (!state.id) { return state.text; }
		  var $state = $(
		   '<span ><i class="'+ state.id +'"></i> ' + state.text + '</span>'
		  );
		  return $state;
		}
		function savesocialicon(dataid,indexVal){
			//var iconClass = a(".tox-form__group").find("#selectIconSocial").val();
			var iconClass1 = a(".tox-form__group").find("#myList li.active").attr('rel');
			var iconClass = iconClass1.replace(/ recom-ed/g, ""); 
			var iconClass2 = iconClass1.replace(/fas/g, ""); 
			var iconClass3 = iconClass2.replace(/fab/g, "");
			var iconClass4 = iconClass3.replace(/far/g, "");
			var iconUrl = a(".tox-form__group").find("#selectSocialUrl").val();
			var iconTarget = a(".tox-form__group").find("#selectSocialSep").is(":checked");
			
			if (typeof indexVal != "undefined") {
				var indexVal1 = parseInt(indexVal) + 1;
				elemt = a('.elementor-element-'+dataid +' .elementor-social-icons-wrapper a:nth-of-type('+indexVal1+')');
				var res = iconClass4.replace(/fa-/g, "elementor-social-icon-"); 
				oldclass= elemt.find('i').attr('class');
				var oldclass = oldclass.replace(/fas/g, ""); 
				var oldclass = oldclass.replace(/fab/g, "");
				var oldclass = oldclass.replace(/far/g, "");
				var res1 = oldclass.replace(/fa-/g, "elementor-social-icon-"); 
				elemt.removeClass(res1);
				elemt.addClass(res);
				elemt.find('i').attr('class',iconClass);
				
				if(!settingData[dataid]){
					settingData[dataid] = {};
				}
				if(!settingData[dataid]['social']){
					settingData[dataid]['social'] = {};
				}
				settingData[dataid]['social'][indexVal] = iconClass;
				
				if(!settingData[dataid]['socialurl']){
					settingData[dataid]['socialurl'] = {};
				}
				settingData[dataid]['socialurl'][indexVal] = iconUrl;
				
				if(!settingData[dataid]['socialtarget']){
					settingData[dataid]['socialtarget'] = {};
				}
				if(iconTarget){
					settingData[dataid]['socialtarget'][indexVal] = true;
				}else{
					settingData[dataid]['socialtarget'][indexVal] = '';
				}
				
			}else{
				console.log('Share icon check ',dataid);
			}
		}
		a(document).on('change','#selectIconSocial',function(){
			var iconClass = a(this).val();
			a('.tox-form__group .selectIcons span i').attr('class',iconClass);
		});
		a('.elementor-widget-social-icons').each(function(){
			var dataid = a(this).attr('data-id');
			if(a('.elementor-element-'+dataid).hasClass('element-locked')){
				return false;
			}
			a('.elementor-element-'+dataid).addClass('element-border');
		})
		a(document).on('click','.elementor-social-icon',function (event) {
			event.preventDefault();
			var dataid = a(this).closest('.elementor-widget-social-icons').attr('data-id');
			var  indexVal = a(this).index();
			if(a('.elementor-element-'+dataid).hasClass('element-locked')){
				return false;
			}
						
			//a('.elementor-element-'+dataid).addClass('element-border');
			var url = a(this).attr('href');
			var target = a(this).attr('target');
			var iclass= a(this).find('i').attr('class');
			a("#modal-window-socialIconSelect").find(".myList li").removeClass('active');
			a("#modal-window-socialIconSelect").find(".myList i[class='"+iclass+"']").parent().parent().addClass('active');
			if(url){
				a("#modal-window-socialIconSelect").find("#selectSocialUrl").val(url);
			}
			if(target){
				if(target == "_blank"){
					a("#modal-window-socialIconSelect").find("#selectSocialSep").attr('checked','checked');
				}
			}
			
			a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_3">t</div>');
			
			var htmlContent = a("#modal-window-socialIconSelect").html();
			var elemnt = a(this);
			a.tinymceInit('.hiddenmce',"","",function(editor){
				tinymce.get(editor.id).focus();
				a("body").addClass('selectbox-icon-body');
				tinymce.activeEditor.windowManager.open({
				  title: 'Icon Library', // The dialog's title - displayed in the dialog header
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
					   savesocialicon(dataid,indexVal);
					   api.close();
					   a("body").removeClass('selectbox-icon-body');
					},
					onClose: function (api) {
						tinymce.get(editor.id).destroy();
						a("#hiddenmce_3").remove();
					}
				});
				a(".selectIcons-main-social .myListTab .active").trigger('click');
			})
			
			return false;
		})
	});
	
})}(jQuery);
