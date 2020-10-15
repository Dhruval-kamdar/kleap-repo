var canExit = true,settingData = {};
!function(a){a(document).ready(function(){
	a(document).click(function(event) { 
		$target = a(event.target);
		 if(!$target.closest('.lock').length){
			 if(!$target.closest('.mce-edit-focus').length){
				 if(!$target.closest('.editimage_background_image').length){
					a('.elementor-element').removeClass('element-focus');
				}
			}
		}
	})
	
	a.tinymceInit = function tinymceInit(selector,plugins,toolbar,callback){
		a(selector).addClass('inline-editor');
		tinymce.init({
			selector: selector,
			plugins:plugins,
			menubar:false,
			toolbar: toolbar,
			inline: true,
			media_dimensions: false,
			init_instance_callback:function(editor){ 
				var closet = a("#"+editor.id).closest('.elementor-element').attr('data-id'); 
				if(a.inArray( closet, elements_data ) > -1){
					callback(editor);
				}else{
					tinymce.remove("#"+editor.id);
				}
				
			}
		});
	}

	a.resetBG = function resetBG(){
		a('.media-overlay-editor').remove();
		a('.media-overlay-edit').remove();
		a('.bgimg').attr('data-id','');
		a('.bgimg').addClass('disable');
		a('.iconChange').hide();
		a('.iconChange').attr('data-id','');
	}
	a.resetFocus =  function resetFocus(id){
		a('.elementor-element').removeClass('element-focus');
		a('.elementor-element-'+id).addClass('element-focus');
		if(a('.lock').length > 0){
			if(a('.elementor-element-'+id).hasClass('element-locked')){
				if(!a('.lock').hasClass('dashicons-lock')){
					a('.lock').removeClass('dashicons-unlock');
					a('.lock').addClass('dashicons-lock');
				}
			}else{
				if(!a('.lock').hasClass('dashicons-unlock')){
					a('.lock').addClass('dashicons-unlock');
					a('.lock').removeClass('dashicons-lock');
				}
			}
		}
	}
	
	a.showLayer =  function showLayer(){
		a('#bgOverlay').show();
	}
	a.hideLayer = function hideLayer(){
		a('#bgOverlay').hide();
	}
	a.removeEditor = function removeEditor(){
		a(".floatingbar_box").removeClass('active');
		a('body').removeClass("live-editor");
		a('.floatingbar').remove();
		a('.inline-editor').removeClass('inline-editor');
		tinymce.remove();
		canExit = true;
		settingData = {};
		a(document).trigger('liveEditorremove');
	}
	
	if(a("body .elementor").length > 0){
		var html = '<li id="wp-admin-bar-inline-editor" > <a class="ab-item live-edit iconbg"> Live Editor</a> </li>';
		a(html).insertAfter('#wp-admin-bar-root-default > li:last');
		a('<div id="bgOverlay"></div>').insertAfter("#wpadminbar");
		if(j_hidemenu == '1'){
			a('#wp-admin-bar-elementor_edit_page').remove();
			a('#wp-admin-bar-elementor_inspector').remove();
		}
	}
	if(imagesupload){
		for(x in imagesupload){
			bg = imagesupload[x];
			for(y in bg){
				a('.elementor-element-'+bg[y]).addClass('editimage_'+x);
			}
		}
	}
	a(document).on('click','.editimage__background_image , .editimage_background_image',function(e){
		e.stopPropagation();
		canExit = false;
		var id = a(this).attr('data-id');
		if(a(".floatingbar").length == 0){
			if(a('.elementor-element-'+id).hasClass('element-locked')){
				return false;
			}
		}
		a('.bgimg').attr('data-id',id);
		a('.bgimg').removeClass('disable');
	});
	a(document).on('click','.bgimg',function(){
		if(a(this).hasClass("disable")){ return false;}
		id = a(this).attr('data-id');
		imgElem = a(".elementor-element-"+id);
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
			if(imgElem.hasClass('editimage_background_image') && imgElem.hasClass('elementor-column')){
				if(!settingData[id]){
					settingData[id] = {};
				}
				settingData[id]['bg'] = con;
				settingData[id]['bgid'] = attachment.id;
				imgElem.find('.elementor-column-wrap').css('background-image','url("'+con+'")');
				//console.log(con);
				
			}else if(imgElem.hasClass('editimage__background_image')){
				if(!settingData[id]){
					settingData[id] = {};
				}
				settingData[id]['bg'] = con;
				settingData[id]['bgid'] = attachment.id;
				imgElem.find('.elementor-widget-container').css('background-image','url("'+con+'")');
				//~ console.log(con,imgElem.find('.elementor-widget-container'));
			
			}else{
				console.log('Error',imgElem);
				if(!settingData[id]){
					settingData[id] = {};
				}
				settingData[id]['bg'] = con;
				settingData[id]['bgid'] = attachment.id;
				imgElem.css('background-image','url("'+con+'")');
			}
			a.resetBG();
		});
		frame.open();
		return false;
	});
	a(document).on('click','.floatingbar .icon',function(){
		if(a(".floatingbar_box").length > 0){
			a(".floatingbar_box").toggleClass('active');
		}
	});
	a(document).on('click','.live-edit',function(){
		if(a('body').hasClass("live-editor")) {return false;}
		a('body').addClass("live-editor");
		if(a(".floatingbar_box .admin_box").length > 0){
			floathtml = '<div class="floatingbar full"><div class="inner-content"><div class="front"><div class="lock dashicons-before dashicons-unlock"></div><div class="bgimg disable dashicons-before '+j_bgimg+'"></div><div class="iconChange disable dashicons-before '+j_iconChange+'"></div><span>Live Editor</span><div class="submit">Done</div><div class="icon dashicons-before dashicons-menu-alt"></div></div><div class="back"><div class="btn discard">Discard</div><div class="btn publish">Publish</div><div class="btn cancel">Cancel</div></div></div></div>';
		}else{
			floathtml = '<div class="floatingbar"><div class="inner-content"><div class="front"><div class="bgimg disable dashicons-before '+j_bgimg+'"></div><div class="iconChange disable dashicons-before '+j_iconChange+'"></div><span>Live Editor</span><div class="submit">Done</div></div><div class="back"><div class="btn discard">Discard</div><div class="btn publish">Publish</div><div class="btn cancel">Cancel</div></div></div></div>';
		}
		
		
		a('body').append(floathtml);
		setTimeout(function (){a(".floatingbar").toggleClass('active');},500);
		if(lockelement.length > 0){
			for(x in lockelement){
				a('.elementor-element-'+lockelement[x]).addClass("element-locked");
			}
		}
		console.log('liveEditor triggered');
		a(document).trigger('liveEditor');

		
		a(document).on('click','.media-overlay-edit',function(){
			canExit = false;
			
			imgElem = a(this).parent().find('img');
			editorid = a(this).attr('rel');
			var frame = wp.media({
					title: '',
					button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			frame.on('select', () => {
				canExit = false;
				var attachment = frame.state().get('selection').first().toJSON();
				//console.log(attachment);
				//imgElem.attr('srcsetor',imgElem.attr('src'));
				imgElem.attr('src',attachment.sizes['full'].url);
				imgElem.attr('srcset','');
				imgElem.addClass('edited-img');
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
				}
				settingData[id]['image'] = con;
				settingData[id]['id'] = attachment.id;
				a('.media-overlay-editor').remove();
				a('.media-overlay-edit').remove();
				
			});
			frame.open();
			return false;
		});
		
		a(document).on('click','.media-svg-select',function(){
			a(".tox-dialog__header button.tox-button").trigger('click');
			editorid1 = a('.floatingbar .iconChange').attr('data-id');
			var indexVal = '';
			if(editorid1.indexOf('___') > 0){
				editorid11 = editorid1.split("___");
				editorid = editorid11[0];
				indexVal = editorid11[1];
			}else{
				editorid = editorid1;
			}
			var frame = wp.media({
					title: '',
					button: {
						text: 'Select'
					},
					library: {
						type: ['image/svg+xml']
					},
					states: [new wp.media.controller.Library({
					  title: '',
					  library: wp.media.query({
						type: ['image/svg+xml']
					  }),
					  multiple: false,
					  date: false
					})]
			});
			frame.on('select', () => {
				canExit = false;
				var attachment = frame.state().get('selection').first().toJSON();
				readHTMLFile = a.get(attachment.sizes['full'].url, function(data) {
					if (window.ActiveXObject) {
						var oString = data.xml; 
					} 
					else {
						var oString = (new XMLSerializer()).serializeToString(data);
					}
					if(indexVal != '') {
						a('.elementor-element-'+editorid +' [rel="'+editorid1+'"]').html(oString);
					
					}else{
					
						a('.elementor-element-'+editorid+' .elementor-icon').html(oString);
					}
				});
				var id = editorid;
				var con = attachment.sizes['full'].url;
				if(!settingData[id]){
					settingData[id] = {};
					settingData[id]['selected_icon'] = {};
				}
				if(indexVal != '') {
					if(!settingData[id]['selected_icon'][indexVal]){
						settingData[id]['selected_icon'][indexVal] = {};
					}
					settingData[id]['selected_icon'][indexVal]['url'] = con;
					settingData[id]['selected_icon'][indexVal]['id'] = attachment.id;
				}else{
					settingData[id]['selected_icon']['url'] = con;
					settingData[id]['selected_icon']['id'] = attachment.id;
				}
			});
			 // Set svg as only allowed upload extensions
			 ext ='svg';
		  var oldExtensions = _wpPluploadSettings.defaults.filters.mime_types[0].extensions;
		  frame.on('ready', function () {
			_wpPluploadSettings.defaults.filters.mime_types[0].extensions = ext;
		  });
		  frame.on('close', function () {
			// restore allowed upload extensions
			_wpPluploadSettings.defaults.filters.mime_types[0].extensions = oldExtensions;
		  });
			frame.open();
			return false;
		});
		
	});
	a(document).on('click','.live-editor .floatingbar .submit',function(){
		a('.floatingbar').addClass('back');
		return false;
	});
	a(document).on('click','.live-editor .floatingbar .back .cancel',function(){
		a('.floatingbar').removeClass('back');
		return false;
	});
	a(document).on('click','.live-editor .floatingbar .back .discard',function(){
		window.location.reload();
		return false;
	});
	a(document).on('click','.live-editor .floatingbar .back .publish',function(){
		a.showLayer();
		
		//a.hideLayer(); console.log(settingData);
		//return true;
		
		var data = {
			'action': 'save_editor',
			'settings': settingData,
			'postID': postID
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(save_ajax_url, data, function(response) {
			//alert('Got this from the server: ' + response);
			a.hideLayer();
			a.removeEditor();
		});
	});
	
	function formatState (state) {
	  if (!state.id) { return state.text; }
	  var $state = a(
	   '<span ><i class="'+ state.id +'"></i> ' + state.text + '</span>'
	  );
	  return $state;
	}
	//~ a('#selectIcon.basic-multiple-select').select2({  templateResult: formatState ,   templateSelection: formatState});
	a('.floatingbar_box .basic-multiple-select').select2();
	a('.floatingbar_box .basic-multiple-select').on("change", function (e) { 
		a.showLayer();
		var data = {
			'action': 'save_editor_permission',
			'value': a(this).val(),
			'name': a(this).attr('name'),
			'postID': postID
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		a.post(save_ajax_url, data, function(response) {
			a.hideLayer();
		});
	})
	
	a(document).on("click", '.floatingbar .lock',function (e) { 
		var len = a('.element-focus').length;
		if(len > 0){
			a.showLayer();
			var id = a('.element-focus').attr('data-id');
			var lock = 1;
			if(a('.elementor-element-'+id).hasClass('element-locked')){
				var lock = 0;
				a('.elementor-element-'+id).removeClass('element-locked');
			}else{
				a('.elementor-element-'+id).addClass('element-locked');
			}
			a.resetFocus(id);
			var data = {
				'action': 'save_editor_permission',
				'lock': lock,
				'id': id,
				'postID': postID
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			a.post(save_ajax_url, data, function(response) {
				a.hideLayer();
			});
		}
	});
	a(document).on('click','.floatingbar .iconChange',function(){
		if(a(".floatingbar").length > 0){
			
			var dataid = a(this).attr('data-id');
			if(dataid.indexOf('___') > 0){
				dataid1 = dataid.split("___");
				dataidv = dataid1[0];
				indexVal = dataid1[1];
			}else{
				dataidv = dataid;
			}
			a('.elementor-element-'+dataidv).append('<div class="hiddenmce" id="hiddenmce_2">t</div>')
			var htmlContent = a("#modal-window-id").html();
			a("body").addClass('selectbox-icon-body');
			var elemnt = a(this);
			a.tinymceInit('.hiddenmce',"","",function(editor){
				tinymce.get(editor.id).focus();
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
					   saveicon();
					   api.close();
					   a("body").removeClass('selectbox-icon-body');
					},
					onClose: function (api) {
						tinymce.get(editor.id).destroy();
						a("#hiddenmce_2").remove();
						a("body").removeClass('selectbox-icon-body');
					}
				});
				//a('#selectIcon.basic-multiple-select').select2({  templateResult: formatState ,   templateSelection: formatState});
			})
		}
	});
	a(document).on('click','.changeIcon',function(){
		var dataid = a(this).attr('rel');
		a('.floatingbar .iconChange').show();
		a('.floatingbar .iconChange').attr('data-id',dataid);
		a('.floatingbar .iconChange').trigger('click');
		return false;
	});
	
	a(document).on("keyup","#myInput", function() {
		var value = a(this).val().toLowerCase();
		var tabval = a(".tox-form__group").find("#myListTab li.active").attr('rel').toLowerCase();
		a(".tox-form__group").find("#myList li").filter(function() {
			var rel = a(this).attr('rel');
			//console.log();
			a(this).toggle((rel.toLowerCase().indexOf(value) > -1 && rel.toLowerCase().indexOf(tabval)  > -1 ));

		});
	});
	
	a(document).on("click","#myListTab li", function() {
		var value = a(this).attr('rel').toLowerCase();
		a(".tox-form__group").find("#myListTab li").removeClass('active');
		a(this).addClass('active');
		a(".tox-form__group").find(".search-box-title").html(a(this).html());
		a(".tox-form__group").find("#myInput").val('');
		a(".tox-form__group").find("#myList li").filter(function() {
			var rel = a(this).attr('rel');
			a(this).toggle(rel.toLowerCase().indexOf(value) > -1);

		});
	});
	a(document).on("click","#myList li", function() {
		a(".tox-form__group").find("#myList li").removeClass('active');
		a(this).addClass('active');
	});

	function saveicon(){
		//console.log('Testing');
		var dataid = a('.floatingbar .iconChange').attr('data-id');
		var iconClass = a(".tox-form__group").find("#myList li.active").attr('rel');
		var indexVal = '-1';
		if(dataid.indexOf('___') > 0){
			dataid1 = dataid.split("___");
			dataidv = dataid1[0];
			indexVal = dataid1[1];
			a('.elementor-element-'+dataidv +' [rel="'+dataid+'"]').html('<i aria-hidden="true" class="'+iconClass+'"></i>');
			
			if(!settingData[dataidv]){
				settingData[dataidv] = {};
			}
			if(!settingData[dataidv]['icon']){
				settingData[dataidv]['icon'] = {};
			}
			settingData[dataidv]['icon'][indexVal] = iconClass;
			
		}else{
			a('.elementor-element-'+dataidv +' .changeIcon').html('<i aria-hidden="true" class="'+iconClass+'"></i>');
			if(!settingData[dataid]){
				settingData[dataid] = {};
			}
			settingData[dataid]['icon'] = iconClass;
		}
		//tb_remove();
	}
	a(document).on('keyup','.numberOnly',function(e){
		try {
				var charCode = (e.which) ? e.which : e.keyCode;
				if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105)) {
					return true;
				}
			}
			catch(err) {
				console.log(err);
			}
			var value = a(this).val();
			a(this).val(value.substr(0,value.length-1));
	})
	a.strip_tags =  function strip_tags(input, allowed) {
		allowed = (((allowed || '') + '')
		.toLowerCase()
		.match(/<[a-z][a-z0-9]*>/g) || [])
		.join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
		var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
		commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
		return input.replace(commentsAndPhpTags, '')
		.replace(tags, function($0, $1) {
		  return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
		});
	}
	a(document).on("liveEditor",function (e) {
		a(document).on('click','.live-editor a', function(event){
			event.preventDefault();
			return false;
		})
		//~ a('.live-editor div').unbind();
		//~ a(document).on('click','.live-editor div', function(event){
			//~ event.preventDefault();
			//~ return false;
		//~ })
	})
			
})}(jQuery);

window.onbeforeunload = function(e) {
	canExit = true;
	if(Object.keys(settingData).length > 0){
		canExit = false;
	}
	if (canExit) return null;
    return 'Dialog text here.';
};

