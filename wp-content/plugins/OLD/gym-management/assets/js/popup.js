jQuery(document).ready(function($) {	
//Category Add and Remove
  $("body").on("click", "#addremove", function(event)
   {
	   
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var model  = $(this).attr('model') ;
		$("#myModal_add_staff_member").css("cssText", "overflow: hidden !important;");
		$("#myModal_add_membership").css("cssText", "overflow: hidden !important;");	
	
	   var curr_data = {
	 					action: 'MJ_gmgt_add_or_remove_category',
	 					model : model,
	 					dataType: 'json'
	 					};	
										
	 					$.post(gmgt.ajax, curr_data, function(response) { 
						
							$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
							return true; 					
	 					});	
	
  });

   $("body").on("click", ".close-btn", function(){	
		$( ".category_list" ).empty();
		$('.popup-bg').hide(); // hide the overlay
		
		}); 
	
		$("body").on("click", ".close-btn.activity_category", function(){		
		
			$( ".category_list" ).empty();
			$("#myModal_add_staff_member").css("cssText", "overflow: scroll;display:block;");
			$(".myModal_add_staff_member12").css("cssText", "overflow: scroll;display:block;");
			
			$('.popup-bg').hide(); // hide the overlay
		});		
		$("body").on("click", ".close-btn.role_type", function(){		
		
			$( ".category_list" ).empty();
			$("#myModal_add_staff_member").css("cssText", "overflow: scroll;display:block;");
			
			$('.popup-bg').hide(); // hide the overlay
		});
		$("body").on("click", ".close-btn.membership_category,.close-btn.installment_plan", function(){		
		
			$( ".category_list" ).empty();
			
			$("#myModal_add_membership").css("cssText", "overflow: scroll;display:block;");
			
			$('.popup-bg').hide(); // hide the overlay
		
		});	
		 $("body").on("click", "#add_membership_btn", function(){		
			$("#myModal_add_membership").css("cssText", "overflow: scroll;display:block;");
			
		 });
		 $("body").on("click", "#add_staff_btn", function(){		
			$("#myModal_add_staff_member").css("cssText", "overflow: scroll;display:block;");
		
		 });
 
  
  $("body").on("click", ".btn-delete-cat", function()
  {		
		var cat_id  = $(this).attr('id') ;	
		var model  = $(this).attr('model') ;
		
		if(confirm("Are you sure want to delete this record?"))
		{
			var curr_data = {
					action: 'MJ_gmgt_remove_category',
					model : model,
					cat_id:cat_id,			
					dataType: 'json'
					};
					
					$.post(gmgt.ajax, curr_data, function(response) 
					{						
						$('#cat-'+cat_id).hide();						
						$("#"+model).find('option[value='+cat_id+']').remove();		
						if(model == 'activity_category')
						{
							$('#specialization').find('option[value='+cat_id+']').remove();		
							$('#specialization').multiselect('rebuild');				
						}
						return true;				
					});			
		}
	});
	
	
  $("body").on("click", "#btn-add-cat", function(){	
        
		var category_name  = $('#category_name').val() ;
		var model  = $(this).attr('model');
	
		var valid = jQuery('#category_form').validationEngine('validate');
		if (valid == true) 
		{		
			var curr_data = {
					action: 'MJ_gmgt_add_category',
					model : model,
					category_name: category_name,			
					dataType: 'json'
					};
										
					$.post(gmgt.ajax, curr_data, function(response) {
						
						var json_obj = $.parseJSON(response);//parse JSON	
                        if(json_obj[2]=="1")
						{
							$('.category_listbox .table').append(json_obj[0]);
							$('#category_name').val("");
							$('#'+model).append(json_obj[1]);
							if(model == 'activity_category')
							{
								$('#specialization').append(json_obj[1]);
								$('#specialization').multiselect('rebuild');
							}							
						}
						else {
							
							alert(json_obj[3]);
						}
						
						
						return false;					
					});	
		}		
	});
 
  //End category Add Remove 
  $("#class_id").change(function(){
		$('#member_list').html('');
		var selection = $("#class_id").val();
		var optionval = $(this);
			var curr_data = {
					action: 'MJ_gmgt_load_user',
					class_list: $("#class_id").val(),			
					dataType: 'json'
					};
					$.post(gmgt.ajax, curr_data, function(response) {						
					$('#member_list').append(response);	
					});
						
					
	});
//-----------load activity by category-------------
	$("#act_cat_id").change(function(){
		$('#activity_list').html('');
		var selection = $("#act_cat_id").val();
		var optionval = $(this);
			var curr_data = {
					action: 'MJ_gmgt_load_activity',
					activity_list: selection,			
					dataType: 'json'
					};
					$.post(gmgt.ajax, curr_data, function(response) {
						
					$('#activity_list').append(response);	
					});
						
					
	});

  //----------view Invoice popup--------------------
	$("body").on("click", ".show-invoice-popup", function(event)
	{

	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
	  var invoice_type  = $(this).attr('invoice_type');
	  
		
	   var curr_data = {
	 					action: 'MJ_gmgt_invoice_view',
	 					idtest: idtest,
	 					invoice_type: invoice_type,
	 					dataType: 'json'
	 					};	 	
												
	 					$.post(gmgt.ajax, curr_data, function(response) { 	
	 							 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.invoice_data').html(response);	
						return true; 					
	 					});	
	
  });
  jQuery("body").on("click", ".view-nutrition", function(event)
  {
	  var nutrition_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  
	   var curr_data = {
	 					action: 'MJ_gmgt_nutrition_schedule_view',
	 					nutrition_id: nutrition_id,			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(gmgt.ajax, curr_data, function(response) {
	 						
	 						
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
	 						return true;
	 						
	 					
	 					
	 					});	
	});  
	//-----------Display measurement by workout-------------
	$("#workout_id").change(function(){
		$('#workout_mesurement').html('');
		var selection = $("#workout_id").val();
		//alert(selection);
		var optionval = $(this);
			var curr_data = {
					action: 'MJ_gmgt_load_workout_measurement',
					workout_id: selection,			
					dataType: 'json'
					};
					$.post(gmgt.ajax, curr_data, function(response) 
					{						
						$('#workout_mesurement').text(response);	
					});
	});

	jQuery("body").on("click", ".view_details_popup", function(event)
	{
		  var record_id = $(this).attr('id');
		  var type = $(this).attr('type');
		 
		  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = $(document).height(); //grab the height of the page
		  var scrollTop = $(window).scrollTop();
		   var curr_data = {
		 					action: 'MJ_gmgt_view_details_popup',
		 					record_id: record_id,			
		 					type: type,			
		 					dataType: 'json'
		 					};
		 					//alert('hello');
		 					$.post(gmgt.ajax, curr_data, function(response) {
							 /*  alert(response);
								return false; */ 
		 						$('.popup-bg').show().css({'height' : docHeight});
								$('.category_list').html(response);	
		 						return true;
		 						
		 					});	
	}); 
	 
	jQuery("body").on("change", ".activity_check", function(event)
	{	
		if($(this).is(":checked"))
		{				
			id = $(this).attr('id');
				
			string = '';
			
			string += '<div class="achilactiveadd col-sm-3"><lable class="col-sm-4">'+ language_translate.sets_lable +'</lable><input type="number" class="validate[required]] col-sm-7" min="0" onKeyPress="if(this.value.length==3) return false;"  name = "sets_' + id + '" id = "sets_' + id + '" placeholder="'+ language_translate.sets_lable +'"></div>';
			string += '<div class="achilactiveadd col-sm-3"><lable class="col-sm-4">'+ language_translate.reps_lable +'</lable> <input type="number" class="validate[required] col-sm-7" min="0" onKeyPress="if(this.value.length==3) return false;" name = "reps_' + id + '" id = "reps_' + id + '" placeholder="'+ language_translate.reps_lable +'"></div>';
			string += '<div class="achilactiveadd col-sm-3"><lable class="col-sm-4">'+ language_translate.kg_lable +'</lable><input type="number" class="validate[required] col-sm-7" min="0" onKeyPress="if(this.value.length==6) return false;" step="0.01" name = "kg_' + id + '" id = "kg_' + id + '" placeholder="'+ language_translate.kg_lable +'"></div>';
			string += '<div class="achilactiveadd col-sm-3"><lable class="col-sm-5">'+ language_translate.rest_time_lable +'</lable><input type="number" class="validate[required] col-sm-7" min="0" onKeyPress="if(this.value.length==3) return false;" name = "time_' + id + '" id = "time_' + id + '" placeholder="'+ language_translate.min_lable +'"></div>';
			
			$("#reps_sets_"+id).html(string);
			 
		}
		else
		{			
			 id = $(this).attr('id');
				
				$("#reps_sets_"+id).html('');
		}
	 });
	 function add_day(day,id)
	 {
		 var string = '';
		 string = '<span id="'+id+'">'+day+'</span>,';
		 string += '<input type="hidden" name="day[day]['+day+']" value="'+day+'">';
		 return string;
	 }
	 function add_activity(activity,id)
	 {
		 var string = '';
		 var sets = '';
		 var reps = '';
		 sets = $("#sets_"+id).val();
		 reps = $("#reps_"+id).val();
		 kg = $("#kg_"+id).val();
		 time = $("#time_"+id).val();
		 
		 string += '<div class="form-group"><label class="col-md-2 control-label" style="font-weight: bold;">'+activity+' : </label>';
		 string += '<div class="col-md-10" style="padding-top: 7px;"><span id="sets_'+id+'"> '+ language_translate.sets_lable +' '+sets+', </span>';
		 string += '<span id="reps_'+id+'"> '+ language_translate.reps_lable +' '+reps+', </span>';
		 string += '<span id="kg_'+id+'"> '+ language_translate.kg_lable +' '+kg+', </span>';
		 string += '<span id="time_'+id+'"> '+ language_translate.rest_time_lable +' '+time+'.</span></div></div>';
		 
		 string += '<input type="hidden" name="sets[]" value="'+sets+'">';
		 string += '<input type="hidden" name="reps[]" value="'+reps+'">';
		 string += '<input type="hidden" name="kg[]" value="'+kg+'">';
		 string += '<input type="hidden" name="time[]" value="'+time+'">';
		 string += '<input type="hidden" name="activity[]" value="'+activity+'">';
		
		 return string;
	 }
	function workout_list(day,activity,id,response)
	{
		var string = '';
				
		string += "<div class='activity nutrisition_activity_box col-md-offset-2 col-md-10' style='padding: 0px;' id='block_"+id+"'>";	
			string += "<div class='form-group nutrition_head'>";
				string += '<div class="col-md-10" style="font-weight: bold;"> '+ language_translate.assigned_workout_lable +' </div>';
				string += "<div id='"+id+"' class='removethis col-md-2'><span class='badge badge-delete pull-right'>X</span></div>";
			string += '</div>';	
			string += "<div class='form-group'>";
				string += '<label class="col-md-2 control-label" style="font-weight: bold;">'+ language_translate.days_lable +' :</label>';		
				string += '<div class="col-md-10" style="padding-top: 7px;">'+day+'</div>';
				
			string += '</div>';
			string += "<div class='form-group' style='padding-bottom: 7px;'>";
			string += activity;
			string += '</div>';
			string += "<div class='form-group'>";
				string += '<div class="col-md-offset-2 col-md-8">'+ response+'</div>';
			string += '</div>';	
			
		string += "</div'>";  
		
		return string;
	}
	 jQuery("body").on("click", ".removethis", function(event){
		
		 var chkID = $(this).attr("id");
		 $("#block_"+chkID).remove();
	 });
	 jQuery("body").on("click", ".removeworkout", function(event){
			if(confirm("Are you sure you want to delete this?"))
			{
				var chkID = $(this).attr("id");
		
			 var curr_data = {
						action: 'MJ_gmgt_delete_workout',
						workout_id: chkID,			
						dataType: 'json'
						};
						
						$.post(gmgt.ajax, curr_data, function(response) {						
											
							 $(".workout_"+chkID).remove();
							
							return false;
							
						});	
				}
		 });
	 jQuery("body").on("click", "#add_workouttype", function(event)
	 {
		var valid = jQuery('#workouttype_form').validationEngine('validate');
		if (valid == true) 
		{
			var checkedday = $('input[name="day[]"]:checked').length;
			var checkedavtivity_id = $('input[name="avtivity_id[]"]:checked').length;
			
			if (checkedday>0 && checkedavtivity_id>0)
			{		
				 $("#display_rout_list").html('');
				 var count = $("#display_rout_list div").length;	
				
				 var day = '';
				 var activity = '';
				 var check_val = '';
				 jsonObj1 = [];
				 jsonObj2 = [];
				 jsonObj = [];
				
				 $(":checkbox:checked").each(function(o){
					
					  var chkID = $(this).attr("id");
					  var check_val = $(this).attr("data-val");
					  
					  if(check_val == 'day')
					  {
						 
						  day += add_day(chkID,chkID);
						  item = {}
							item ["day_name"] =chkID;
						   

							jsonObj1.push(item);
							
					  }
					  if(check_val == 'activity')
					  {
						  activity_name = $(this).attr("activity_title");
						  item = {};
						  var sets = $("#sets_"+chkID).val();
						  var reps = $("#reps_"+chkID).val();
						  var kg = $("#kg_"+chkID).val();
						  var time = $("#time_"+chkID).val();
						  
							item ["activity"] = {"activity":activity_name,"sets":$("#sets_"+chkID).val(),"reps":$("#reps_"+chkID).val(),"kg":$("#kg_"+chkID).val(),"time":$("#time_"+chkID).val()};
						  activity += add_activity(activity_name,chkID);
						 
						   

							jsonObj2.push(item);
					  }
					
					  $(this).prop('checked', true);
					 
					
					  /* ... */
					  jsonObj = {"days":jsonObj1,"activity":jsonObj2};
					});
				
					
				 var curr_data = {
							action: 'MJ_gmgt_add_workout',
							data_array: jsonObj,			
							dataType: 'json'
							};
							
							$.post(gmgt.ajax, curr_data, function(response) {
								
								 var list_workout =  workout_list(day,activity,count,response);
									
									
									$("#display_rout_list").append(list_workout);
								 
								return false;
								
							});	
			}
		}
					
	}); 
	 //Nutrition code
	 
	 $(".nutrition_check").change(function(){
			
			id = $(this).attr('id');					
			
		 if($(this).is(":checked"))
		{			 
			
			 id = $(this).attr('id');
				
			 string = '';
			string += '<div class="nutrition_add "><textarea class="form-control validate[required,custom[address_description_validation]] description_details" maxlength="150" name="'+id+'" id="valtxt_'+id+'"></textarea></div>';
			$("#txt_"+id).html(string);
			 
		}
		 else
		{
		
			 id = $(this).attr('id');
			
			 string = '';
				$("#txt_"+id).html(string);
		}
	 });
	 function add_nutrition(activity,id)
	 {
		 var string = '';
		 var sets = '';
		 var reps = '';
		 var nutrition = '';
		 //comment this line for validation time issue.
		 nutrition = $("#valtxt_"+id).val();
		var result = ''; 
		while (nutrition.length > 0) 
		{ 
	       result += nutrition.substring(0, 60) + '\n'; 
		   nutrition = nutrition.substring(60); 
	    }
		 string += "<div class='form-group'>";
				string += '<label id="'+id+'" class="col-md-4 control-label nutrition_title" style="font-weight: bold;">'+activity+'</label>';		
				string += '<div class="col-md-8 nutrition_value" id="value_'+id+'" style="padding-top: 7px;">'+result+'</div>';				
			string += '</div>';
		 
		 return string;
	 }
	function nutrition_list(day,activity,id,response)
	{
		var string = '';
		string += "<div class='activity nutrisition_activity_box col-md-offset-2 col-md-8' style='padding: 0px;' id='block_"+id+"'>";	
			string += "<div class='form-group nutrition_head'>";
				string += '<div class="col-md-10" style="font-weight: bold;"> '+ language_translate.nutrition_schedule_details_lable +'</div>';
				string += "<div id='"+id+"' class='removethis col-md-2'><span class='badge badge-delete pull-right'>X</span></div>";
			string += '</div>';	
			string += "<div class='form-group'>";
				string += '<label class="col-md-4 control-label" style="font-weight: bold;">'+ language_translate.days_lable +' :</label>';		
				string += '<div class="col-md-8" style="padding-top: 7px;">'+day+'</div>';
				
			string += '</div>';
			string += "<div class='form-group' style='padding-bottom: 7px;'>";
			string += activity;
			string += '</div>';
			string += "<div class='form-group'>";
				string += '<div class="col-md-offset-2 col-md-8">'+ response+'</div>';
			string += '</div>';	
			
		string += "</div'>";  
		return string;
	}
	jQuery("body").on("click", "#add_nutrition", function(event)
	{
		var valid = jQuery('#nutrition_form').validationEngine('validate');
		if (valid == true) 
		{
			var checkedday = $('input[name="day[]"]:checked').length;
			var checkedavtivity_id = $('input[name="avtivity_id[]"]:checked').length;
			
			if (checkedday>0 && checkedavtivity_id>0)
			{			
				var count = $("#display_nutrition_list div").length;	
				
				var day = '';
				var activity = '';
				var check_val = '';
				jsonObj1 = [];
				jsonObj2 = [];
				jsonObj = [];
				
				$(":checkbox:checked").each(function(o)
				{			
					  var chkID = $(this).attr("id");
					  var check_val = $(this).attr("data-val");
						
					  if(check_val == 'day')
					  {						 
						  day += add_day(chkID,chkID);
						  item = {}
							item ["day_name"] =chkID;		       

							jsonObj1.push(item);							
					  }
					  if(check_val == 'nutrition_time')
					  {
						  activity_name = $(this).attr("id");
						if(activity_name == 'dinner')
						{
							activity_name = ''+ language_translate.dinner_lable +' :';
						}
						if(activity_name == 'breakfast')
						{
							activity_name = ''+ language_translate.breakfast_lable +' :';
						}
						if(activity_name == 'lunch')
						{
							activity_name = ''+ language_translate.lunch_lable +' :';
						}
						  item = {};				  
							item ["activity"] = {"activity":activity_name,"value":$("#valtxt_"+chkID).val()};
						  activity += add_nutrition(activity_name,chkID);
						  
							jsonObj2.push(item);
							
					  }
					  $(this).prop('checked', false);					 
					 
					  /* ... */
					  jsonObj = {"days":jsonObj1,"activity":jsonObj2};
					});
				 
				 var curr_data = {
							action: 'MJ_gmgt_add_nutrition',
							data_array: jsonObj,			
							dataType: 'json'
							};					
							$.post(gmgt.ajax, curr_data, function(response) {
								
								 var list_workout =  nutrition_list(day,activity,count,response);						 
								 $("#display_nutrition_list").append(list_workout);						
								 $('.description_details').val('');						 
								$(".description_details").css("display", "none");
								return false;
								
							});	
							return false;
			}
		}
	}); 
	 
	 jQuery("body").on("click", ".removenutrition", function(event){
			if(confirm("Are you sure you want to delete this?"))
				{
			 var chkID = $(this).attr("id");
			
			 var curr_data = {
						action: 'MJ_gmgt_delete_nutrition',
						workout_id: chkID,			
						dataType: 'json'
						};
						
						$.post(gmgt.ajax, curr_data, function(response) {
							$(".workout_"+chkID).remove();
							
							return false;
							
						});	
				}
		 });
	//--------display today workouts---------------	
	 jQuery("body").on("changeDate", "#record_date", function(event){
	
		var selection = $("#record_date").val();
		 var uid = $('#member_list').val();
		
		var optionval = $(this);
			var curr_data = {
					action: 'MJ_gmgt_today_workouts',
					record_date: selection,			
					uid: uid,			
					dataType: 'json'
					};
					$.post(gmgt.ajax, curr_data, function(response) {
					
					$('.workout_area').html(response);	
					});						
					
	});
	 
	 $("body").on("click", ".view-measurement-popup", function(event){
			

		  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = $(document).height(); //grab the height of the page
		  var scrollTop = $(window).scrollTop();
		  var user_id  = $(this).attr('data-val');
		
		   var curr_data = {
		 					action: 'MJ_gmgt_measurement_view',
		 					user_id: user_id,		 					
		 					dataType: 'json'
		 					};	 	
										
		 					$.post(gmgt.ajax, curr_data, function(response) { 	
		 						
		 					$('.popup-bg').show().css({'height' : docHeight});							
							$('.invoice_data').html(response);	
							return true; 					
		 					});	
		
	  });
	 
	 $("body").on("click", ".measurement_delete", function(event){
			

		  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = $(document).height(); //grab the height of the page
		  var scrollTop = $(window).scrollTop();
		  var measurement_id  = $(this).attr('data-val');
		 
		 
		  if(confirm('Do you really want to delete this record?'))
			  {
		   var curr_data = {
		 					action: 'MJ_gmgt_measurement_delete',
		 					measurement_id: measurement_id,		 					
		 					dataType: 'json'
		 					};	 	
										
		 					$.post(gmgt.ajax, curr_data, function(response) { 	
		 					
		 						 $("tr#row_"+measurement_id).remove();
							return true; 					
		 					});
			  }
		
	  });	 
	 jQuery("body").on("changeDate", "#begin_date", function(event)
	 {
		
		var start_date = $("#begin_date").val();
		 var membership_id = $('#membership_id').val();
		 
		 $('#end_date').val("Loading....");
		
		var optionval = $(this);
			var curr_data = {
					action: 'MJ_gmgt_load_enddate',
					start_date: start_date,			
					membership_id: membership_id,			
					dataType: 'json'
					};
					$.post(gmgt.ajax, curr_data, function(response) {
					
						$('#end_date').val(response);
						$('#end_date').attr('readonly', 'true');					
					});
						
	});
	
	$(".payment_membership_detail").change(function(){
		
		var membership_id = $(this).val();
		 $('#total_amount').val("Loading....");
		
			var optionval = $(this);
			var curr_data = {
					action: 'MJ_gmgt_paymentdetail_bymembership',
					membership_id: membership_id,			
					dataType: 'json'
					};
					$.post(gmgt.ajax, curr_data, function(response) {
						
						 payment_data = $.parseJSON(response);
										
					$("#begin_date").val('');
					$("#end_date").val('');
					$("#total_amount").val(payment_data.price);
				
					});
						
					
	});
	//Payment Module pop up
	 $("body").on("click", ".show-payment-popup", function(event){
				

			  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			  var docHeight = $(document).height(); //grab the height of the page
			  var scrollTop = $(window).scrollTop();
			  var idtest  = $(this).attr('idtest');
			  var view_type  = $(this).attr('view_type');
			  var due_amount  = $(this).attr('due_amount');	
			  var member_id  = $(this).attr('member_id');	
			
			   var curr_data = {
			 					action: 'MJ_gmgt_member_add_payment',
			 					idtest: idtest,
			 					view_type: view_type,
								due_amount: due_amount,
								member_id: member_id,
			 					dataType: 'json'
			 					};	 	
												
			 					$.post(gmgt.ajax, curr_data, function(response) { 	
			 							 
			 					$('.popup-bg').show().css({'height' : docHeight});							
								$('.invoice_data').html(response);	
								return true; 					
			 					});	
			
		  });
	$("body").on("click", ".show-view-payment-popup", function(event){
				

			  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			  var docHeight = $(document).height(); //grab the height of the page
			  var scrollTop = $(window).scrollTop();
			  var idtest  = $(this).attr('idtest');
			  var view_type  = $(this).attr('view_type');			  
			  		  
				  var curr_data = {
			 					action: 'MJ_gmgt_member_view_paymenthistory',
			 					idtest: idtest,
			 					view_type1: view_type,
			 					dataType: 'json'
			 					};	 	
													
			 					$.post(gmgt.ajax, curr_data, function(response) { 	
			 															
			 					$('.popup-bg').show().css({'height' : docHeight});							
								$('.invoice_data').html(response);	
								return true; 					
			 					});	
			
		  });
	var membertype=$("#member_type").val();	  
	if(membertype=='Prospect'){
			$('#non_prospect_area').hide();	
		}
		else
		{
			$('#non_prospect_area').show();	
		}
	$("body").on("change","#member_type", function(){
		var optionval = $(this).val();
		if(optionval=='Prospect'){
			$('#non_prospect_area').hide();	
		}
		else
		{
			$('#non_prospect_area').show();	
		}
	});
		  
	/*---------Verify licence key-----------------*/
	$("body").on("click", "#varify_key", function(event){
	$(".gmgt_ajax-img").show();
	$(".page-inner").css("opacity","0.5");
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		var res_json;
	  var licence_key = $('#licence_key').val();
	  var enter_email = $('#enter_email').val();
	
	   var curr_data = {
	 		action: 'MJ_gmgt_verify_pkey',
	 		licence_key : licence_key,
	 		enter_email : enter_email,
	 		dataType: 'json'
	 	};	
		
		$.post(gmgt.ajax, curr_data, function(response) { 						
	 		res_json = JSON.parse(response);
			$('#message').html(res_json.message);
				$("#message").css("display","block");
				$(".gmgt_ajax-img").hide();
				$(".page-inner").css("opacity","1");
				if(res_json.gmgt_verify == '0')
				{
					window.location.href = res_json.location_url;
				}
				return true; 					
	 		});		
	});

//for membership update

$("body").on("change", ".tog ", function(event){	
	event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	var res_json;
	var timeperiod = $(this).val();
		
	if(timeperiod=='unlimited'){		
		$('#on_of_member_box').empty();		
		$('#member_limit').empty();		
	}
	else
	{		
		var curr_data = {
			action: 'MJ_gmgt_timeperiod_for_class_member',
			timeperiod : timeperiod,	 	
			dataType: 'json'
		 };	
											
		$.post(gmgt.ajax, curr_data, function(response) {		 	
			$('#member_limit').html(response);	
			return true; 					
		});	
	}
});





$("body").on("change", ".classis_limit ", function(event){	
	event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	var res_json;
	var timeperiod = $(this).val();

	if(timeperiod=='unlimited'){
		$('#on_of_classis_box').empty();
		$('#classis_limit').empty();
	}
	else
	{
		var curr_data = {
			action: 'MJ_gmgt_timeperiod_for_class_number',
			timeperiod : timeperiod,	 	
			dataType: 'json'
		};	
										
		$.post(gmgt.ajax, curr_data, function(response) {		 	
			$('#classis_limit').html(response);			
			return true; 					
		});
	}
		
});



$("body").on("change", "#membership_id ", function(event){		
	event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	var res_json;
	var membership_id = $(this).val();
	var membership_hidden = $('.membership_hidden').val();
	var categCheck = jQuery('.classis_ids').multiselect();	
	if(membership_id!="")
	{		
		var curr_data = {
			action: 'MJ_gmgt_get_class_id_by_membership',
			membership_id : membership_id,	 	
			membership_hidden : membership_hidden,	 	
			dataType: 'json'
		};	
											
		$.post(gmgt.ajax, curr_data, function(response) 
		{			
			if(response == 1)
			{				
				alert(language_translate.membership_member_limit_alert);
								
				$('#membership_id').val('');	
				$('#classis_id').html('');		
				categCheck.multiselect('rebuild');						
			}
			else
			{					
				$('#classis_id').html('');	
				$('#classis_id').html(response);	
				categCheck.multiselect('rebuild');		
			}
			return true; 					
		});
	}
	else
	{
		$('#classis_id').html('');	
		categCheck.multiselect('rebuild');		
		return true; 
	}
});


$("body").on("change", "#membership_id ", function(event){		
	event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	var res_json;
	var membership_id = $(this).val();	
	if(membership_id!=""){
	var curr_data = {
		action: 'MJ_gmgt_check_membership_limit_status',
		membership_id : membership_id,	 	
		dataType: 'json'
	};	
										
	$.post(gmgt.ajax, curr_data, function(response) {			
		$('#no_of_class').html(response);			
	});
	}
});

		
	$("#myModal_add_staff_member").scroll(function(){
		$('.dropdown-menu.datepicker').hide();
	});
	
 //count total in store product.
 $("body").on('focus','.total_amount', function (event) {	

	$( this ).blur();
	
		var curr_data = {
	 					action: 'MJ_gmgt_count_store_total',
	 					discount_amount: $('.discount_amount').val(),			
	 					//end: $('.end').val(),			
	 					quantity: $('.quantity').val(),			
	 					Product: $('.Product').val(),			
	 					tax : $('.Tax ').val(),			
	 					dataType: 'json'
	 					};
	 					$.post(gmgt.ajax, curr_data, function(response) {
						$('.total_amount').val(response);	
	 						return true;
					});	
		 
		 return false;
	});  
	
	$("body").on('keyup', 'input.quantity', function(ev) 	
	{
			var row_no = $(this).attr('row');
		
			var product_id=$('.product_id'+row_no).val();
			var quantity=$('.quantity'+row_no).val();
			
			var curr_data = {
					action: 'MJ_gmgt_check_product_stock',		
					product_id:product_id,
					quantity:quantity,
					row_no:row_no,					
					dataType: 'json'
					
					};
					
					$.post(gmgt.ajax, curr_data, function(response)
					{	
						if(response == '')
						{
							return true;	
						}
						else
						{
							var row_no = response;
							$('.quantity'+row_no).val('');
							//alert('Product out of stock');
							alert(language_translate.product_out_of_stock_alert);
							return false;
						}							
					});		 
	});
	$("body").on("change", "#product_id ", function(event)
	{
		var row_no = $(this).attr('row');
		$('.quantity'+row_no).val('');
		return false;			
	});
			
	$("body").on("change", ".notice_for ", function(event)
	{		
		var notice_for = $(this).val();
		if(notice_for == 'member')
		{
			$(".class_div").css("display", "block");
		}
		else
		{
			$(".class_div").css("display", "none");
		}
		return false;			
	});
	$("body").on("change", ".message_to ", function(event)
	{
		var message_to = $(this).val();
		
		if(message_to == 'member')
		{
			 $('#class_list').prop('selectedIndex',0);
			$(".display_class_css").css("display", "block");
		}
		else
		{
			 $('#class_list').prop('selectedIndex',0);
			$(".display_class_css").css("display", "none");
		}		
		return false;			
	});
	// member filter alert message
	$('body').on('click','.member_filter',function()
	{
		var membertype=$('#member_type').val();
		if(membertype == '')
		{
			//alert('please select at least one member type');	
			alert(language_translate.select_one_membership_alert);
			return false;				
		}		
		
	});		
	//fronted side image upload then preview hide or show
	jQuery(".image_upload_change").change(function()
	{
		var image_preview_url=$(this).val();
		if(image_preview_url == '')
		{
			$(".image_preview_css").css("display", "block");
		}
		else
		{
			$(".image_preview_css").css("display", "none");
		}		
	});		
	$("body").on("change", "#payment_method ", function(event)
	{
		var payment_method = $(this).val();
		
		if(payment_method == 'Cheque' || payment_method == 'Bank Transfer')
		{
			$(".payment_description").css("display", "block");
		}
		else
		{
			$(".payment_description").css("display", "none");
		}		
		return false;			
	});
	//activity category list from activity category type in membership
	jQuery("body").on("change", ".activity_category_list", function(event)
	{ 		
		var action_membership=$('.action_membership').val();
		var membership_id_activity=$('.membership_id_activity').val();
		
		var selected_activity_category_list = [];
        $('.activity_category_list :selected').each(function(i, selected) 
		{ 
            selected_activity_category_list[i] = $(selected).val();
        });
			
		var curr_data = {
					action: 'MJ_gmgt_get_activity_from_category_type',
					selected_activity_category_list: selected_activity_category_list,					
					action_membership: action_membership,					
					membership_id_activity: membership_id_activity,					
					dataType: 'json'
					};	 	
										
					$.post(gmgt.ajax, curr_data, function(response)
					{ 							
						var json_obj = $.parseJSON(response);//parse JSON	
						$('.activity_list_from_category_type').html('');	
						$('.activity_list_from_category_type').append(json_obj);	

						jQuery('.activity_list_from_category_type').multiselect('rebuild');			 	
						
						return true; 					
			});	
				
	});
	// activity category onchange to  specialization staff member list in activity
	jQuery("body").on("change", ".activity_cat_to_staff", function(event)
	{ 
		var activity_category=$(this).val();
				
		var curr_data = {
					action: 'MJ_gmgt_get_staff_member_list_by_specilization_category_type',
					activity_category: activity_category,														
					dataType: 'json'
					};	 	
										
					$.post(gmgt.ajax, curr_data, function(response)
					{ 							
						var json_obj = $.parseJSON(response);//parse JSON	
						$('.category_to_staff_list').html('');	
						$('.category_to_staff_list').append(json_obj);	

						return true; 					
			});	
	});
	//Get member Current membership  Activity list  in Assign Workout//
	jQuery("body").on("change", ".assigned_workout_member_id", function(event)
	{ 
		var member_id=$(this).val();
			
		var curr_data = {
					action: 'MJ_gmgt_get_member_current_membership_activity_list',
					member_id: member_id,														
					dataType: 'json'
					};	 	
										
					$.post(gmgt.ajax, curr_data, function(response)
					{ 		
						var json_obj = $.parseJSON(response);//parse JSON	
						$('.member_workout_activity').html('');	
						$('.member_workout_activity').append(json_obj);	

						return true; 					
			});	
	});
	//Event And task display model
  $("body").on("click", ".show_task_event", function(event)
  {
	
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var id  = $(this).attr('id') ;
	  var model  = $(this).attr('model') ;
	 /*  alert(id);
	alert(model);
	return false;   */ 
	   var curr_data = {
	 					action: 'MJ_gmgt_show_event_task',
	 					id : id,
	 					model : model,
	 					dataType: 'json'
	 					};	
										
	 					$.post(gmgt.ajax, curr_data, function(response) { 	
							/*   alert(response);
							return false;   */
							$('.popup-bg').show().css({'height' : docHeight});
							$('.task_event_list').html(response);	
												
							return true; 					
						});		 
	});
	$("body").on("click", ".event_close-btn", function()
	{		
		$('.popup-bg').hide(); // hide the overlay
	}); 
});