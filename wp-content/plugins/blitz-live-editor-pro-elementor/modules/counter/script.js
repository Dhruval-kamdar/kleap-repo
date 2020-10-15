!function(a){a(document).ready(function(){
	function callbackImageBoxText(editor){
		canExit = false;
		var id = a("#"+editor.id).parent().parent().parent().attr('data-id');
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
			if(!settingData[id]){
				settingData[id] = {};
			}
			settingData[id]['title'] = con;
		});
	}
	a('.elementor-counter-number').each(function(){
		a(this).attr('data-from-value',a(this).html());
	})
	a(document).on("liveEditor",function (e) {
		a.tinymceInit('.elementor-counter-title',"","bold italic underline",callbackImageBoxText);
		
		a(document).on('click','.elementor-counter-number',function(){
			if(a(".floatingbar_box .admin_box").length > 0){
				var id = a(this).parent().parent().parent().parent().attr('data-id');
				a('.elementor-element-'+id).append('<div class="hiddenmce" id="hiddenmce_1">t</div>')
				var elemnt = a(this);
				a.tinymceInit('.hiddenmce',"","",function(editor){
					tinymce.get(editor.id).focus();
					fromValue = elemnt.attr('data-from-value');
					toValue = elemnt.attr('data-to-value');
					tinymce.activeEditor.windowManager.open({
					  title: 'Update Count', // The dialog's title - displayed in the dialog header
					  body: {
						type: 'panel', // The root body type - a Panel or TabPanel
						items: [ // A list of panel components
						  {
							type: 'htmlpanel', // A HTML panel component
							html: '<div class="counterBox"> <input type="text"  id="fromValue" class="tox-textfield numberOnly" placeholder="from" value="'+fromValue+'" /> <br /><input type="text"  id="toValue" placeholder="to" class="tox-textfield numberOnly"  value="'+toValue+'"  /> </div>'
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
							fromValue = parseInt(a(".tox-form__group").find("#fromValue").val());
							toValue = parseInt(a(".tox-form__group").find("#toValue").val());
							
							if(isNaN(fromValue)){
								alert("Numeric Value required");
								return false;
							}
							if(isNaN(toValue)){
								alert("Numeric Value required");
								return false;
							}
							if(!settingData[id]){
								settingData[id] = {};
							}
							settingData[id]['starting_number'] = fromValue;
							settingData[id]['ending_number'] = toValue;
							toValue1 = addCommas(toValue,elemnt.attr('data-delimiter'));
							elemnt.text(toValue1);
							api.close();
						},
						onClose: function (api) {
							tinymce.get(editor.id).destroy();
							a("#hiddenmce_1").remove();
						}
					});
				});
				
				
				
			}
			return false;
		})
	});
	function addCommas(nStr,sep) {
		sep = sep || ",";
		nStr += '';
		var x = nStr.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + sep + '$2');
		}
		return x1 + x2;
	}
	
})}(jQuery);
