!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		a('.elementor-widget-google_maps').addClass('edit_google_maps element-border');
		a('.elementor-widget-google_maps').append('<div class="map_edit" ></div>');
	});
	
	a(document).on('click','.edit_google_maps .map_edit',function (event) {
		event.preventDefault();
		var dataid = a(this).closest('.elementor-widget-google_maps').attr('data-id');
		var  indexVal = a(this).index();
		a.resetFocus(dataid);
		if(a('.elementor-element-'+dataid).hasClass('element-locked')){
			return false;
		}
		var map_location = a('.elementor-element-'+dataid).find('iframe').attr('aria-label');
		
		map_zoom = 10;
		var map_zoom1= a('.elementor-element-'+dataid).find('iframe').attr('src');
		try{
			map_zoom2 = map_zoom1.split("&");
			for(x in map_zoom2){
				if(map_zoom2[x].indexOf("z=") > -1 ){
					map_zoom3 = map_zoom2[x].split("=");
					map_zoom = map_zoom3[1];
				}
			}
		}catch(err){}
		var map_height = a('.elementor-element-'+dataid).find('iframe').height();
		a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_map">t</div>');
		var htmlContent = a("#modal-window-googlemap").html();
		var elemnt = a(this);
		a.tinymceInit('.hiddenmce',"","",function(editor){
			tinymce.get(editor.id).focus();
			tinymce.activeEditor.windowManager.open({
			  title: 'Map Setting', // The dialog's title - displayed in the dialog header
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
				   saveMap(dataid);
				   api.close();
				},
				onClose: function (api) {
					tinymce.get(editor.id).destroy();
					a("#hiddenmce_map").remove();
				}
			});
		})
		
		setTimeout(function(){
			a(".tox-form__group").find("#map_location").val(map_location);
			a(".tox-form__group").find("#map_zoom").val(map_zoom);
			a(".tox-form__group").find("#map_height").val(map_height);
		},300);
		
		return false;
	})
	function saveMap(dataid){
		map_location = a(".tox-form__group").find("#map_location").val();
		map_zoom = a(".tox-form__group").find("#map_zoom").val();
		map_height = a(".tox-form__group").find("#map_height").val();
		src = encodeURI("https://maps.google.com/maps?q="+map_location+"&t=m&z="+map_zoom+"&output=embed&iwloc=near");
		a('.elementor-element-'+dataid).find('iframe').attr('aria-label',map_location);
		a('.elementor-element-'+dataid).find('iframe').attr('src',src);
		a('.elementor-element-'+dataid).find('iframe').height(map_height);
		if(!settingData[dataid]){
			settingData[dataid] = {};
		}
		if(!settingData[dataid]['googlemap']){
			settingData[dataid]['googlemap'] = {};
		}
		settingData[dataid]['googlemap']['map_location'] = map_location;
		settingData[dataid]['googlemap']['map_zoom'] = map_zoom;
		settingData[dataid]['googlemap']['map_height'] = map_height;
	}
})}(jQuery);
