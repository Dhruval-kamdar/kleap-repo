!function(a){a(document).ready(function(){
		a("#thrive_plan_button-desc").click(function(){
					str = a("#thrive_plan_button").val();
					html = a(a.parseHTML( str ));
					datathrivecartaccount = html.attr('data-thrivecart-account');
					if(datathrivecartaccount){
						datathrivecartaccount = html.attr('data-thrivecart-account');
						datathrivecartproduct = html.attr('data-thrivecart-product');
						datathrivecartquietbranding = html.attr('data-thrivecart-quietbranding');
						datathrivecartclass = html.attr('class');
						buttonlabel = html.html();
						
						a("#thrive_plan_account").val(datathrivecartaccount);
						//a("#thrive_plan_product").val(datathrivecartproduct);
						a("#thrive_plan_quietbranding").val(datathrivecartquietbranding);
						a("#thrive_plan_class").val(datathrivecartclass);
						a("#thrive_title").val(buttonlabel);
				}
		});
		a("#thrive_plan_button").val('');
})}(jQuery);
