!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		function callbackShareButtons(editor){
			canExit = false;
			var id =  a("#"+editor.id).closest('.elementor-widget-share-buttons').attr('data-id');
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
				indexval = a("#"+editor.id).closest('.elementor-grid-item').index();
				indexval1 = parseInt(indexval) + 1;
				var con = editor.getContent({format: 'raw'});
				var con1 = a.strip_tags(con,'<strong><em><span><a>');
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['sharebuttons'] = {};
					
				}
				if(!settingData[id]['sharebuttons'][indexval]){
					settingData[id]['sharebuttons'][indexval] = {};
				}
				if( a("#"+editor.id).hasClass('elementor-share-btn__title')){
					settingData[id]['sharebuttons'][indexval]['title'] = con1;
				}
				
			});
		}
		if(a('.elementor-widget-share-buttons .elementor-grid').length > 0){
			a('.elementor-widget-share-buttons .elementor-grid').each(function(){
				editorid = a(this).closest('.elementor-widget-share-buttons').attr('data-id');
				if(a(".floatingbar_box .admin_box").length == 0){
					if(a('.elementor-element-'+editorid).hasClass('element-locked')){
						return false;
					}
				}
				a('.elementor-element-'+editorid + ' .elementor-share-btn').addClass('elementor-share-btnn');
				a('.elementor-element-'+editorid + ' .elementor-share-btnn').removeClass('elementor-share-btn');
				a('.elementor-element-'+editorid+' .elementor-grid .elementor-grid-item').each(function () {
					var indexval = a(this).index();
					gridhtml = a(this).html();
					a(this).html('');
					a(this).html(gridhtml);
				});
				a(document).on('click','.elementor-element-'+editorid+' .elementor-grid .elementor-share-btn__icon',function (event) {
					event.preventDefault();
					var dataid = a(this).closest('.elementor-widget-share-buttons').attr('data-id');
					var  indexVal = a(this).closest('.elementor-grid-item').index();

					a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_share-buttons">t</div>');
					
					var htmlContent = '<div class="selectbox-icon-box2 elementor-carousel-image"><p><label>Button : </label> <select id="elementor-control-default-sharebutton"><option value="facebook">Facebook</option><option value="twitter">Twitter</option><option value="google">Google+</option><option value="linkedin">LinkedIn</option><option value="pinterest">Pinterest</option><option value="reddit">Reddit</option><option value="vk">VK</option><option value="odnoklassniki">OK</option><option value="tumblr">Tumblr</option><option value="delicious">Delicious</option><option value="digg">Digg</option><option value="skype">Skype</option><option value="stumbleupon">StumbleUpon</option><option value="telegram">Telegram</option><option value="pocket">Pocket</option><option value="xing">XING</option><option value="whatsapp">WhatsApp</option><option value="email">Email</option><option value="print">Print</option></select></p></div>';
					
					
					var elemnt = a(this);
					a.tinymceInit('.hiddenmce',"","",function(editor){
						tinymce.get(editor.id).focus();
						tinymce.activeEditor.windowManager.open({
						  title: 'Change Button', // The dialog's title - displayed in the dialog header
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
							   saveShareButton(dataid,indexVal);
							   api.close();
							},
							onClose: function (api) {
								tinymce.get(editor.id).destroy();
								a("#hiddenmce_share-buttons").remove();
							}
						});
					})
					return false;
				})
				var buttonelements = {
											'facebook':['Facebook','fab fa-facebook'],
											'twitter':['Twitter','fab fa-twitter'],
											'google':['Google+','fab fa-google-plus'],
											'linkedin':['LinkedIn','fab fa-linkedin'],
											'pinterest':['Pinterest','fab fa-pinterest'],
											'reddit':['Reddit','fab fa-reddit'],
											'vk':['VK','fab fa-vk'],
											'odnoklassniki':['OK','fab fa-odnoklassniki'],
											'tumblr':['Tumblr','fab fa-tumblr'],
											'delicious':['Delicious','fab fa-delicious'],
											'digg':['Digg','fab fa-digg'],
											'skype':['Skype','fab fa-skype'],
											'stumbleupon':['StumbleUpon','fab fa-stumbleupon'],
											'telegram':['Telegram','fab fa-telegram'],
											'pocket':['Pocket','fab fa-pocket'],
											'xing':['XING','fab fa-xing'],
											'whatsapp':['WhatsApp','fab fa-whatsapp'],
											'email':['Email','fa fa-envelope'],
											'print':['Print','fa  fa-print'],
											
										};
				function saveShareButton(dataid,indexval){
					indexval1 = parseInt(indexval) + 1;
					if(!settingData[dataid]){
						settingData[dataid] = {};
						settingData[dataid]['sharebuttons'] = {};
					}
					if(!settingData[dataid]['sharebuttons'][indexval]){
						settingData[dataid]['sharebuttons'][indexval] = {};
					}
					var sharebuttonval = a(".tox-form__group").find("#elementor-control-default-sharebutton").val();
					
					var gridhtml ='<div class="elementor-share-btnn elementor-share-btn_'+sharebuttonval+'"><span class="elementor-share-btn__icon"><i aria-hidden="true" class="'+buttonelements[sharebuttonval][1]+'"></i><span class="elementor-screen-only">Share on '+sharebuttonval+'</span></span><div class="elementor-share-btn__text"><span class="elementor-share-btn__title">'+buttonelements[sharebuttonval][0]+'</span></div></div>';
					
					a('.elementor-element-'+dataid).find('.elementor-grid-item:nth-child('+(indexval1)+')').html(gridhtml);
					settingData[dataid]['sharebuttons'][indexval]['button'] = sharebuttonval;
					a.tinymceInit('.elementor-element-'+dataid+' .elementor-share-btn_'+sharebuttonval+'  .elementor-share-btn__title',"","bold italic underline",callbackShareButtons);
					
				}
				
				
				a.tinymceInit('.elementor-element-'+editorid+'  .elementor-share-btn__title',"","bold italic underline",callbackShareButtons);
			})
		}
	});

	a(document).on("liveEditorremove",function (e) {
		if(a('.elementor-widget-share-buttons .elementor-grid').length > 0) {
			window.location.reload();
			return false;
		}
	})
})}(jQuery);
