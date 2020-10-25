!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		
		a('.elementor-widget-form').each(function () {
			var dataid = a(this).attr('data-id');
			//if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
				a('.elementor-element-'+dataid).addClass('element-border');
				a('.elementor-element-'+dataid).append('<div class="form-edit"> </div>')
			//}
		})
		
		a(document).on('click','.form-edit',function (event) {
			event.preventDefault();
			var dataid = a(this).parent().attr('data-id');
			a.resetFocus(dataid);
			if(a('.elementor-element-'+dataid).hasClass('element-locked')){
				return false;
			}
			a('.elementor-element-'+dataid).append('<div class="hiddenmce" id="hiddenmce_form">t</div>');
			var email_to = email_from = email_from_name = '';
			if(elements_form_data[dataid]){
				var dataelement = elements_form_data[dataid];
				email_to  = dataelement['email_to'];
				email_from  = dataelement['email_from'];
				email_from_name  = dataelement['email_from_name'];
				email_to_2  = dataelement['email_to_2'];
				email_from_2  = dataelement['email_from_2'];
				email_from_name_2  = dataelement['email_from_name_2'];
			}
			var htmlContent = '<div class="customform"><div><h4>Email </h4> <label>To</label> <input type="text" id="email_to" value="'+email_to+'"/></div> <br /><div><label>From Email</label> <input type="text" id="email_from" value="'+email_from+'"/></div> <br /><div><label>From Name</label> <input type="text" id="email_from_name" value="'+email_from_name+'"/></div><div><h4>Email 2 </h4> <label>To</label> <input type="text" id="email_to_2" value="'+email_to_2+'"/></div> <br /><div><label>From Email</label> <input type="text" id="email_from_2" value="'+email_from_2+'"/></div> <br /><div><label>From Name</label> <input type="text" id="email_from_name_2" value="'+email_from_name_2+'"/></div> </div>';
			a.tinymceInit('#hiddenmce_form',"","",function(editor){
				tinymce.get(editor.id).focus();
				tinymce.activeEditor.windowManager.open({
				  title: 'Change Form Settings', // The dialog's title - displayed in the dialog header
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
						var email_to = a(".tox-form__group").find("#email_to").val();
						var email_from = a(".tox-form__group").find("#email_from").val();
						var email_from_name = a(".tox-form__group").find("#email_from_name").val();
						var email_to_2 = a(".tox-form__group").find("#email_to_2").val();
						var email_from_2 = a(".tox-form__group").find("#email_from_2").val();
						var email_from_name_2 = a(".tox-form__group").find("#email_from_name_2").val();
						if(!settingData[dataid]){
							settingData[dataid] = {};
							settingData[dataid]['form'] = {};
						}
						settingData[dataid]['form']['email_to'] = email_to;
						settingData[dataid]['form']['email_from'] = email_from;
						settingData[dataid]['form']['email_from_name'] = email_from_name;
						settingData[dataid]['form']['email_to_2'] = email_to_2;
						settingData[dataid]['form']['email_from_2'] = email_from_2;
						settingData[dataid]['form']['email_from_name_2'] = email_from_name_2;
						api.close();
					},
					onClose: function (api) {
						tinymce.get(editor.id).destroy();
						a("#hiddenmce_form").remove();
					}
				});
			})
			return false;
		})
	});
	
})}(jQuery);
