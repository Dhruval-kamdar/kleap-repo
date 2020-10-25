jQuery(document).ready(function($) {

    var modals = $('.modal.multiStep');

    modals.each(function(idx, modal) {
		
		var $modal = $(modal);
        var $bodies = $modal.find('div.modal-body');
        var total_num_steps = $bodies.length;
        var $progress = $modal.find('.m-progress');
        var $progress_bar = $modal.find('.m-progress-bar');
        var $progress_stats = $modal.find('.m-progress-stats');
        var $progress_current = $modal.find('.m-progress-current');
        var $progress_total = $modal.find('.m-progress-total');
        var $progress_complete  = $modal.find('.m-progress-complete');
        var reset_on_close = $modal.attr('reset-on-close') === 'true';

        function reset() {
            $modal.find('.step').hide();
            $modal.find('[data-step]').hide();
        }

        function completeSteps() {
            $progress_stats.hide();
            $progress_complete.show();
            $modal.find('.progress-text').animate({
                top: '-2em'
            });
            $modal.find('.complete-indicator').animate({
                top: '-2em'
            });
            $progress_bar.addClass('completed');
        }

        function getPercentComplete(current_step, total_steps) {
            return Math.min(current_step / total_steps * 100, 100) + '%';
        }

        function updateProgress(current, total) {
            $progress_bar.animate({
                width: getPercentComplete(current, total)
            });
            if (current - 1 >= total_num_steps) {
                completeSteps();
            } else {
                $progress_current.text(current);
            }

            $progress.find('[data-progress]').each(function() {
                var dp = $(this);
                if (dp.data().progress <= current - 1) {
                    dp.addClass('completed');
                } else {
                    dp.removeClass('completed');
                }
            });
        }

        function goToStep(step) {
            reset();
            var to_show = $modal.find('.step-' + step);
            if (to_show.length === 0) {
                // at the last step, nothing else to show
                return;
            }
            to_show.show();
            var current = parseInt(step, 10);
            updateProgress(current, total_num_steps);
            //~ findFirstFocusableInput(to_show).focus();
        }

        function findFirstFocusableInput(parent) {
            var candidates = [parent.find('input'), parent.find('select'),
                              parent.find('textarea'),parent.find('button')],
                winner = parent;
            $.each(candidates, function() {
                if (this.length > 0) {
                    winner = this[0];
                    return false;
                }
            });
            return $(winner);
        }

        function bindEventsToModal($modal) {
            var data_steps = [];
            $('[data-step]').each(function() {
                var step = $(this).data().step;
                //~ console.log('sss::'+step);
                if (step && $.inArray(step, data_steps) === -1) {
                    data_steps.push(step);
                }
            });

            $.each(data_steps, function(i, v) {
                window.addEventListener('next.m.' + v, function (evt) {
                    goToStep(evt.detail.step);
                }, false);
            });
        }

        function initialize() {
            reset();
            updateProgress(1, total_num_steps);
            $modal.find('.step-1').show();
            $progress_complete.hide();
            $progress_total.text(total_num_steps);
            bindEventsToModal($modal, total_num_steps);
            $modal.data({
                total_num_steps: $bodies.length,
            });
            if (reset_on_close){
                //Bootstrap 2.3.2
                $modal.on('hidden', function () {
                    reset();
                    $modal.find('.step-1').show();
                })
                //Bootstrap 3
                $modal.on('hidden.bs.modal', function () {
                    reset();
                    $modal.find('.step-1').show();
                })
            }
        }
        initialize();
    })
    
    $('.rating :radio').change(function() {
	  //~ console.log('New star rating: ' + this.value);
	  $('#selectedRating').html(this.value);
	});
	
	$('.bzrcp_ques_main #bzrcp_quesType').change(function(){
		
		var qtype = $(this).val();
		
		if( qtype == 'star_rating' || qtype == 'true_false' || qtype == 'message_box' ) {
			$('.bzrcp_ques_main #bzrcp_main_quesOptions').hide();
			$('.bzrcp_ques_main #bzrcp_main_quesOptions1').show();
		} else {
			$('.bzrcp_ques_main #bzrcp_main_quesOptions').show();
			$('.bzrcp_ques_main #bzrcp_main_quesOptions1').hide();
		}
	});
	
	
	//Run popup on click of Delete account & remove subscription link
	$('#wp-ultimo-actions #wu-account-actions li a ').click(function (event) {
		event.preventDefault();
		//~ alert('popup here');
        $('#bzrcp_feedmodal').modal('show');
    });
   
   
   
   
	// Add Answers & redirect urls
    $(document).on('click', '.btn-add', function(e)	{
		
		e.preventDefault();
		var controlForm = $('#answersUrls:first'),
		currentEntry = $(this).parents('.entry:first'),
		newEntry = $(currentEntry.clone()).appendTo(controlForm);
		newEntry.find('input').val('');
		controlForm.find('.entry:not(:last) .btn-add')
		  .removeClass('btn-add').addClass('btn-remove')
		  .removeClass('btn-success').addClass('btn-danger')
		  .html('<span class="glyphicon glyphicon-minus"></span>');
		  
	}).on('click', '.btn-remove', function(e) {
		  e.preventDefault();
		  $(this).parents('.entry:first').remove();
		  return false;
	});
	
		
	sendEvent = function(type, sel, step, btn, qid, qtype) {
		
		if( type == 'submit' ){
			
			var modalId = sel;
			var modalData = jQuery(modalId).serialize();   //formData   
			
			var href1 = $('.skipFeedback a').attr('href');

			//post values to be updated
				jQuery.ajax({
					type: "POST",
					url: bzrcp_ajaxurl,
					data: { 
						'action': 'bzrcp_save_modalData', 
						'modalData': modalData
					},
					success: function(msg){
						
						//~ console.log('done:'+msg);
						if( msg == 1 ) {
							location.href = href1; //redirect to
						}
						
					}
				});
			
			return false;
			
			
		} else {
			
				if(btn == 'bzrcp_nextBtn') {
							
					var currentStep = step -1 ;
					
					var quesType = $('.modal-body.step-'+currentStep).attr('data-type');

					if( quesType == 'mcq' || quesType == 'true_false' ) {
						
						var answer = $('.modal-body.step-'+currentStep+' input:checked').val();
						var redirecturl = $('.modal-body.step-'+currentStep+' input:checked').attr('data-url');
						var redirectques = $('.modal-body.step-'+currentStep+' input:checked').attr('data-relqid');
						
					} else if ( quesType == 'message_box' ) {
						
						var answer = $('.modal-body.step-'+currentStep+' textarea').val();
						var redirecturl = $('.modal-body.step-'+currentStep+' textarea').attr('data-url');
						var redirectques = $('.modal-body.step-'+currentStep+' textarea').attr('data-relqid');
						
					} else if ( quesType == 'star_rating' ) {
						var answer = $('.modal-body.step-'+currentStep+' .rating #selectedRating').html();
						var redirecturl = $('.modal-body.step-'+currentStep+' .rating input').attr('data-url');
						var redirectques = $('.modal-body.step-'+currentStep+' .rating input').attr('data-relqid');
					}
					
					
					if( (typeof answer !== "undefined") && answer != '' ) {
				
						if( (typeof redirectques !== "undefined") && redirectques != '' ) {
							
							var relatedDataStep = $('.modal.multiStep #q'+redirectques ).attr('data-step');
							var nextStepIdObj = $('.modal.multiStep .modal-body.step-'+step).attr('id');
							
							//change ID class of Related next question
							$('.modal.multiStep #q'+redirectques ).removeAttr('data-step');
							$('.modal.multiStep #q'+redirectques ).attr('data-step',step);
							$('.modal.multiStep #q'+redirectques ).removeClass('step-'+relatedDataStep);
							$('.modal.multiStep #q'+redirectques ).addClass('step-'+step);
							
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button').removeAttr('data-step');
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button').attr('data-step',step);
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button').removeClass('step-'+relatedDataStep);
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button').addClass('step-'+step);
							
							var nextStep = parseInt(step)+1;
							var backStep = parseInt(step)-1;
							
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button').removeAttr('onclick');
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button#bzrcp_nextBtn').attr('onclick','sendEvent("next","#bzrcp_feedmodal","'+nextStep+'","bzrcp_nextBtn","'+qid+'","'+quesType+'")');
							$('.modal.multiStep .modal-footer #q'+redirectques+ ' button#bzrcp_backBtn').attr('onclick','sendEvent("back","#bzrcp_feedmodal","'+step+'","bzrcp_backBtn","'+qid+'","'+quesType+'")');

							
							//Remove steps
							$('.modal.multiStep #'+nextStepIdObj).removeAttr('data-step');
							$('.modal.multiStep #'+nextStepIdObj).attr('data-step',relatedDataStep);
							$('.modal.multiStep #'+nextStepIdObj).removeClass('step-'+step);
							$('.modal.multiStep #'+nextStepIdObj ).addClass('step-'+relatedDataStep);
														
							$('.modal.multiStep .modal-footer #'+nextStepIdObj+ ' button').removeAttr('data-step');
							$('.modal.multiStep .modal-footer #'+nextStepIdObj+ ' button').attr('data-step',relatedDataStep);
							$('.modal.multiStep .modal-footer #'+nextStepIdObj+ ' button').removeClass('step-'+step);
							$('.modal.multiStep .modal-footer #'+nextStepIdObj+ ' button').addClass('step-'+relatedDataStep);
							
							var nextSteprel = parseInt(relatedDataStep)+1;
							var backSteprel = parseInt(relatedDataStep)-1;
							
							$('.modal.multiStep .modal-footer #q'+nextStepIdObj+ ' button').removeAttr('onclick');
							$('.modal.multiStep .modal-footer #q'+nextStepIdObj+ ' button#bzrcp_nextBtn').attr('onclick','sendEvent("next","#bzrcp_feedmodal","'+nextSteprel+'","bzrcp_nextBtn","'+qid+'","'+quesType+'")');
							$('.modal.multiStep .modal-footer #q'+nextStepIdObj+ ' button#bzrcp_backBtn').attr('onclick','sendEvent("back","#bzrcp_feedmodal","'+relatedDataStep+'","bzrcp_backBtn","'+qid+'","'+quesType+'")');
							
							//Goto next step
							var sel_event = new CustomEvent('next.m.' + step, {detail: {step: step}});
							window.dispatchEvent(sel_event);
							
						} else if( (typeof redirecturl !== "undefined") && redirecturl != '' ) {
							var validUrl = getValidUrl(redirecturl);
							//~ console.log(validUrl);
							
							//save to db
							var modalId = sel;
							var modalData = jQuery(modalId).serialize();   //formData   

							//post values to be updated
								jQuery.ajax({
									type: "POST",
									url: bzrcp_ajaxurl,
									data: { 
										'action': 'bzrcp_save_modalData', 
										'modalData': modalData
									},
									success: function(msg){
										
										if( msg == 1 || msg == 0 ) {
											location.href = validUrl; //redirect to
										}
									}
								});
			
						} else {
							location.href = bzrcp_removeLink; //redirect to						
						}
						
					} else {
						//~ location.href = bzrcp_removeLink; //redirect to	
					}
					
				} else {
						step = step -1 ;
						var sel_event = new CustomEvent('next.m.' + step, {detail: {step: step}});
						window.dispatchEvent(sel_event);
				}
		}

		
	}
	
	
	//when modal closed in between save the data
	$('.modal.multiStep').on('hide.bs.modal', function () { 
       	
		var modalData = jQuery("form#bzrcp_feedmodal").serialize();   //formData 
		
		//post values to be updated
		jQuery.ajax({
			
			type: "POST",
			url: bzrcp_ajaxurl,
			data: { 
				'action': 'bzrcp_save_modalData', 
				'modalData': modalData
			},
			success: function(msg){
				if( msg == 1 ) {
					//~ console.log('done:'+msg);
				}
			}
		});
		
		//reset everything when popup closed
		 $(this)
		.find("input,textarea,select")
		   .val('')
		   .end()
		.find("input[type=checkbox], input[type=radio]")
		   .prop("checked", "")
		   .end();
		   
         $(this).find('.step').hide();
         $(this).find('[data-step]').hide();
         $(this).find('.step-1').show();
         
								
	});  
	
	
	//save data when skip feed is clicked
	$('.skipFeedback a').on('click', function (e) { 
		
		e.preventDefault();
		var href = $(this).attr('href');
		
		var modalData = jQuery("#bzrcp_feedmodal").serialize();   //formData 
		//post values to be updated
		jQuery.ajax({
			
			type: "POST",
			url: bzrcp_ajaxurl,
			data: { 
				'action': 'bzrcp_save_modalData', 
				'modalData': modalData
			},
			success: function(msg){
				//~ console.log(msg);
				if( msg == 1 ) {
					location.href = href; //redirect to
				}
			}
		});
								
	});  
	
	


	//get valid URL
	getValidUrl = (url) => {
				
		  let newUrl = window.decodeURIComponent(url);
   		  //~ console.log('validurk:::'+newUrl);

		  newUrl = newUrl
			.trim()
			.replace(/\s/g, '');
		  if (/^(:\/\/)/.test(newUrl)) {
			return 'http'+newUrl;
		  }
		  if (!/^(f|ht)tps?:\/\//i.test(newUrl)) {
			return 'http://'+newUrl;
		  }
		  return newUrl;
	}
	
	
	//keyup function
	$(document).on('keyup', '.bzrcp_ques_main .bzrcp_related_ques, .bzrcp_ques_main .bzrcp_related_ques1', function () {
		
			var searchKey = $(this).val();
			$(this).addClass('autoSearch');
			$(this).next().addClass('autoSearch');
			$(this).next().next().addClass('autoSearch');
			$.ajax({
				type: 'POST',
				url: bzrcp_ajaxurl,
				data: { 
					'action': 'bzrcp_search_questions', 
					'searchKey': searchKey
				},
				success: function(response){
					//~ console.log(response);
					if(response.error){
						$('.list-gpfrm.autoSearch').hide();
					} else {
						$('.list-gpfrm.autoSearch').show().html(response);
					}
				}
			});
	});



	//fill the input
	$(document).on('click', '.bzrcp_ques_main .list-gpfrm.autoSearch .list-gpfrm-list', function(e){
			e.preventDefault();
			$('.list-gpfrm.autoSearch').hide();
			var title = $(this).attr('data-title');
			var qid = $(this).attr('id');
			$('.bzrcp_ques_main .bzrcp_related_ques.autoSearch').val(title);
			$('.bzrcp_ques_main .bzrcp_related_ques_id.autoSearch').attr('data-id',qid);
			$('.bzrcp_ques_main .bzrcp_related_ques_id.autoSearch').val(qid);
			$('.bzrcp_ques_main .bzrcp_related_ques').removeClass('autoSearch');
			$('.bzrcp_ques_main .bzrcp_related_ques_id').removeClass('autoSearch');
			$('.bzrcp_ques_main .list-gpfrm').removeClass('autoSearch');
	});
	
	//fill the input
	$(document).on('click', '.bzrcp_ques_main .list-gpfrm.autoSearch .list-gpfrm-list', function(e){
			e.preventDefault();
			$('.list-gpfrm.autoSearch').hide();
			var title = $(this).attr('data-title');
			var qid = $(this).attr('id');
			$('.bzrcp_ques_main .bzrcp_related_ques1.autoSearch').val(title);
			$('.bzrcp_ques_main .bzrcp_related_ques_id1.autoSearch').attr('data-id',qid);
			$('.bzrcp_ques_main .bzrcp_related_ques_id1.autoSearch').val(qid);
			$('.bzrcp_ques_main .bzrcp_related_ques1').removeClass('autoSearch');
			$('.bzrcp_ques_main .bzrcp_related_ques_id1').removeClass('autoSearch');
			$('.bzrcp_ques_main .list-gpfrm').removeClass('autoSearch');
	});
								
       
});
