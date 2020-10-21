var _wasPageCleanedUp = false;
var _wastourstepSaved = false;
var bztsw_mode1 = false;
var bztsw_mode2 = false;
var flipCounter;
var bztsw_counter;
var componentBody;
var bztsw_selectedComponent = false;
var bztsw_uname = "";

jQuery.noConflict();
jQuery(document).ready(function() {

	//Add Tour
	jQuery('#bztsw_onDashboard').change(bztswchangeOnDashboard);
    jQuery('#bztsw_begin').change(bztswstartCheck);
    bztswstartCheck();
	
	
    //Add Step
    jQuery('#bztsw_stepTy').change(bztswtypeCheck);
    bztswtypeCheck();
    jQuery('#bztsw_stepAction').change(bztswactionCheck);
    bztswactionCheck();
    jQuery('#bztsw_item_overlay').change(bztswoverlayCheck);
    bztswoverlayCheck();
    jQuery('#bztsw_tourID').change(bztswstepCheck);
    bztswstepCheck();
    jQuery('#bztsw_stepConbtn').keyup(bztswbtnsCheck);
    jQuery('#bztsw_stepStopbtn').keyup(bztswbtnsCheck);


    jQuery('.bzstarttour').click(function(t) {
        t.preventDefault();
        bztsw_initFinaltours();
    });

    
    jQuery('td.view.column-view a').click(function(t) {
        t.preventDefault();
        var helperId = jQuery(this).attr('data-id');
        var currenthelper = bztsw_getTourByID(helperId);
        if (currenthelper.bztsw_begin == 'click') {
			if(tourblogid == 1) {
				bztsw_beginTour(bztsw_getTourByID(helperId));
			} else {
				bztsw_beginTour(bztsw_getTourByID(helperId));
				//~ jQuery(currenthelper.bztsw_domComponent).click();
			}
        } else {
            bztsw_initFinaltours(helperId);
        }
    });


    //click element
    jQuery('body *').click(bztsw_stepClick);
	
    //Save form as draft 
    var currenturl = window.location.href;	
    if ((currenturl.indexOf('bztsw-step-add') > 0 && currenturl.indexOf('step=') < 0) || (currenturl.indexOf('bztsw-tour-add') > 0 && currenturl.indexOf('tour=') < 0)) {
		
		if(!_wastourstepSaved) {
			if( !jQuery('form#bztsw_add_tour input.button-primary').attr('clicked') ) {
				var mypageurl = currenturl.split('page=');
				jQuery(window).bind('beforeunload', function(){
					 pageCleanup(mypageurl);
				 });
			}
		}
    }

    //check for each helper
    jQuery.each(tours, function(i) {

        var initialhelper = tours[i];
        
        if (initialhelper.bztsw_begin == 'click') {
            if (jQuery(initialhelper.bztsw_domComponent).length > 0) {
                jQuery(initialhelper.bztsw_domComponent).attr('data-helper', initialhelper.bztsw_id);
                jQuery(initialhelper.bztsw_domComponent).click(function(e) {
                    e.preventDefault();
                    bztsw_beginTour(bztsw_getTourByID(jQuery(this).attr('data-helper')));
                });
            }
        }
    });      
    
    console.log('settings::'+globaltoursettings[0].bztsw_titlecolor);
    
    
	if(jQuery('#bztsw_add_step #bztsw_overrideSettings').val() == 1){
		
		console.log('sdfsd');
				if(jQuery('#bztsw_stepTy').val() == 'tooltip') {
					jQuery('.tooltip_typography').show();
				} else {
					jQuery('.dialog_typography').show();
				}
    }
	
	jQuery("#bztsw_add_step #bztsw_overrideSettings").click(function() {
            var checked = jQuery(this).is(':checked');
            if (checked) {
				if(jQuery('#bztsw_stepTy').val() == 'tooltip') {
					jQuery('.tooltip_typography').show();
				} else {
					jQuery('.dialog_typography').show();
				}
            } else {
				if(jQuery('#bztsw_stepTy').val() == 'tooltip') {
					jQuery('.tooltip_typography').hide();
				} else {
					jQuery('.dialog_typography').hide();
				}
			}
            
    });
        

});

jQuery(window).load(function () {
	bztsw_initFinaltours(localStorage.getItem('helper'));
});

		
function pageCleanup(mypageurl) {

 if (!_wasPageCleanedUp) {
	 
	if(mypageurl[1] == 'bztsw-tour-add' ) {
		bztsw_save_tours_drafts(this);   //save tours as drafts
		_wasPageCleanedUp = true;
	} else if (mypageurl[1] = 'bztsw-step-add') {
		bztsw_save_steps_drafts(this); //save steps as drafts
		_wasPageCleanedUp = true;
	}
 }
 
}
		
function bztsw_saveSettings(e) { //save settings action

    var error = false;
    jQuery('#bztsw_bgcolor').removeClass('field-error');
    if (jQuery("#bztsw_bgcolor").val().length != 7) {
        error = true;
        jQuery('#bztsw_bgcolor').addClass('field-error');
    }
    jQuery('#bztsw_titlecolor').removeClass('field-error');
    if (jQuery("#bztsw_titlecolor").val().length != 7) {
        error = true;
        jQuery('#bztsw_titlecolor').addClass('field-error');
    }
    if (!error) {
        jQuery("#bztsw_response").hide();
        var data = {
            action: "bztsw_settings_save"
        };
        jQuery('#form_settings input, #form_settings select, #form_settings textarea').each(function() {
            if (jQuery(this).attr('name')) {
                eval('data.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
            }
        });
        
        jQuery.post(ajaxurl, data, function(response) {
            jQuery("#bztsw_response").html('<div id="message" class="updated"><p><strong>Settings saved</strong>.</p></div>');
            setTimeout(function() {
                document.location.href = toururl+'wp-admin/admin.php?page=bztsw_settings';
            }, 250);
        });
    }
}


function bztswstepCheck() {
    if (jQuery('#bztsw_pageurl').val() == "") {
        var page = jQuery('#bztsw_tourID :selected').data('page');
        jQuery('#bztsw_pageurl').val(page);
    }
    //jQuery('#bztsw_onDashboard').val(jQuery('#bztsw_tourID :selected').data('admin'));
}


function bztswtypeCheck() {
    var type = jQuery('#bztsw_stepTy').val();
    if (type == 'tooltip') {
        //~ jQuery('.only_dialog').hide();
        jQuery('.only_tooltip').show();
        jQuery('.only_tooltip.content').hide();
    } else if (type == 'dialog') {
        jQuery('#bztsw_stepDlySrt').val(0);
        jQuery('.only_dialog').show();
        jQuery('.only_tooltip').hide();
        bztswbtnsCheck();
    } else {
        jQuery('#bztsw_stepDlySrt').val(0);
        jQuery('.only_dialog').hide();
        jQuery('.only_dialog.text').show();
        jQuery('.only_tooltip').hide();
        jQuery('#bztsw_stepCont_tooltip').parent().parent().show();
        jQuery('#bztsw_item_overlay').parent().parent().hide();
        jQuery('#bztsw_item_overlay').val('1');
        jQuery('#bztsw_stepDly').parent().parent().show();
        jQuery('#bztsw_stepAction').parent().parent().hide();
        jQuery('#bztsw_stepAction').val('delay');
    }
}

function bztswbtnsCheck() {
    if ((jQuery('#bztsw_stepTy').val() == 'dialog') && (jQuery('#bztsw_stepConbtn').val().length > 0 || jQuery('#bztsw_stepStopbtn').val().length > 0)) {
        jQuery('#bztsw_stepAction').val('click');
        jQuery('#bztsw_stepAction').parent().parent().hide();
        jQuery('#bztsw_stepDly').parent().parent().hide();
    } else if (jQuery('#bztsw_stepTy').val() == 'dialog') {
        jQuery('#bztsw_stepAction').val('delay');
        jQuery('#bztsw_stepDly').parent().parent().hide();
    }
}

function bztswactionCheck() {
    if (jQuery('#bztsw_stepAction').val() == 'click' && jQuery('#bztsw_stepTy').val() != 'text') {
        jQuery('#bztsw_stepDly').parent().parent().hide();
    } else {
        jQuery('#bztsw_stepDly').parent().parent().show();
    }
}

function bztswoverlayCheck() {
    if (jQuery('#bztsw_item_overlay').val() == '1') {
        jQuery('#bztsw_item_closeHelperBtn').parent().parent().show();
    } else {
        jQuery('#bztsw_item_closeHelperBtn').parent().parent().hide();
    }
}

function bztsw_save_steps(e) {

    var error = false;

    jQuery('#bztsw_domComponent').prev().prev('span').css({
        color: '#000'
    });
    jQuery('#bztsw_domComponent').removeClass('field-error');
    jQuery('#bztsw_title').removeClass('field-error');
    if (jQuery("#bztsw_title").val().length < 3) {
        error = true;
        jQuery('#bztsw_title').addClass('field-error');
    }
    if (jQuery('#bztsw_stepTy').val() == 'tooltip' && jQuery('#bztsw_domComponent').val().length < 2) {
        error = true;
        jQuery('#bztsw_domComponent').prev().prev('span').css({
            color: 'red'
        });
        jQuery('#bztsw_domComponent').addClass('field-error');
    }

    if (!error) {
		
		jQuery(window).unbind( "beforeunload");

        jQuery("#bztsw_response").hide();
        var data = {
            action: "bztsw_step_save"
        };
        jQuery('#bztsw_add_step input, #bztsw_add_step select, #bztsw_add_step textarea').each(function() {
            if (jQuery(this).attr('name')) {
                if (jQuery(this).attr('name') != "bztsw_stepCont_tooltip" && jQuery(this).attr('name') != 'bztsw_onDashboard') {
                    eval('data.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
                }
            }
        });
        var editor = tinyMCE.get('bztsw_stepCont');
        if (editor) {
            data.bztsw_stepCont = editor.getContent();
        } else {
            data.bztsw_stepCont = jQuery('#bztsw_stepCont').val();
        }
        if (jQuery('#bztsw_stepTy').val() == 'text') {
            data.bztsw_stepCont = editor.getContent();
        }
        
        //check if checkbox checked 
        if(jQuery('#bztsw_overrideSettings').prop("checked") == true){
                data.bztsw_overrideSettings = '1';
        }
        else if(jQuery('#bztsw_overrideSettings').prop("checked") == false){
                data.bztsw_overrideSettings = '0';
        }
        
        if (jQuery('#bztsw_stepTy').val() == 'tooltip' &&  jQuery('#bztsw_overrideSettings').prop("checked") == true ) {
			
			jQuery('.tooltip_typography').show();
			
		} else if (jQuery('#bztsw_stepTy').val() == 'dialog' &&  jQuery('#bztsw_overrideSettings').prop("checked") == true ) {
			
			jQuery('.dialog_typography').show();
		}
        
        data.bztsw_isdraft = '0';

        jQuery.post(ajaxurl, data, function(response) {
            jQuery("#bztsw_response").html('<div id="bztsw_message" class="updated"><p><strong>Step saved</strong>.</p></div>');
            jQuery("#bztsw_response").fadeIn(250);
            _wastourstepSaved = true;
            document.location.href = toururl+'wp-admin/admin.php?page=bztsw-steps';
        });
    }
}



function bztsw_save_steps_drafts(e) {

    var error = false;

    jQuery('#bztsw_domComponent').prev().prev('span').css({
        color: '#000'
    });
    jQuery('#bztsw_domComponent').removeClass('field-error');
    jQuery('#bztsw_title').removeClass('field-error');
    if (jQuery("#bztsw_title").val().length < 3) {
        error = true;
        jQuery('#bztsw_title').addClass('field-error');
    }

    if (!error) {
        jQuery("#bztsw_response").hide();
        var data = {
            action: "bztsw_step_save"
        };
        jQuery('#bztsw_add_step input, #bztsw_add_step select, #bztsw_add_step textarea').each(function() {
            if (jQuery(this).attr('name')) {
                if (jQuery(this).attr('name') != "bztsw_stepCont_tooltip" && jQuery(this).attr('name') != 'bztsw_onDashboard') {
                    eval('data.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
                }
            }
        });
        var editor = tinyMCE.get('bztsw_stepCont');
        if (editor) {
            data.bztsw_stepCont = editor.getContent();
        } else {
            data.bztsw_stepCont = jQuery('#bztsw_stepCont').val();
        }
        if (jQuery('#bztsw_stepTy').val() == 'text') {
            data.bztsw_stepCont = editor.getContent();
        }

		data.bztsw_isDraft = '1';
		
        jQuery.post(ajaxurl, data, function(response) {
            jQuery("#bztsw_response").html('<div id="bztsw_message" class="updated"><p><strong>Step saved as draft.</strong>.</p></div>');
            jQuery("#bztsw_response").fadeIn(250);
            document.location.href = '#wpwrap';
            document.location.href = toururl+'wp-admin/admin.php?page=bztsw-steps';

        });
    }
}

function bztswchangeOnDashboard() {
	
    if (jQuery('#bztsw_onDashboard').val() == '1') {
        jQuery('#bztsw_add_tour #bztsw_pageurl').val(document.location.href.substr(0, document.location.href.lastIndexOf('/')));
    } else {
        jQuery('#bztsw_add_tour #bztsw_pageurl').val('');
    }
}

function bztswstartCheck() {
    if (jQuery('#bztsw_begin').val() == 'click') {
        jQuery('#bztsw_domComponent').parent().parent().show();
        jQuery('#bztsw_onceTime').parent().parent().hide();
    } else {
        jQuery('#bztsw_domComponent').parent().parent().hide();
        jQuery('#bztsw_onceTime').parent().parent().show();
    }
}

function bztsw_save_tours(e) {
    var error = false;
    jQuery('#bztsw_title').removeClass('field-error');
    if (jQuery("#bztsw_title").val().length < 3) {
        error = true;
        jQuery('#bztsw_title').addClass('field-error');
    }
    if (!error) {
		
		jQuery('form#bztsw_add_tour input.button-primary').attr('clicked', true);
		
		jQuery(window).unbind( "beforeunload");

        jQuery("#bztsw_response").hide();
        var data = {
            action: "bztsw_tour_save"
        };
        jQuery('#bztsw_add_tour input, #bztsw_add_tour select').each(function() {
            if (jQuery(this).attr('name') ) {
                eval('data.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
            }
        });

        if (jQuery('input[name=bztsw_defaultTour]').is(':checked')) {
            data.bztsw_defaultTour = '1';
        } else {
            data.bztsw_defaultTour = '0';
        }
		
		data.bztsw_isdraft = '0';
		
        if (jQuery('#bztsw_id').val() > 0) {
            eval('localStorage.removeItem(' + jQuery('#bztsw_id').val() + ');');
        }

        _wastourstepSaved = true;

        jQuery.post(ajaxurl, data, function(response) {
            jQuery("#bztsw_response").html('<div id="bztsw_message" class="updated"><p>Tour <strong>saved</strong>.</p></div>');
            jQuery("#bztsw_response").fadeIn(250);
            jQuery('#bztsw_id').val(response);            
            document.location.href = toururl+'wp-admin/admin.php?page=bztsw-tours';
        });
        
        return false;

    }
}


function bztsw_save_tours_drafts(e) {
    var error = false;
    jQuery('#bztsw_title').removeClass('field-error');
    if (jQuery("#bztsw_title").val().length < 3) {
        error = true;
        jQuery('#bztsw_title').addClass('field-error');
    }
    if (!error) {
        jQuery("#bztsw_response").hide();
        var data = {
            action: "bztsw_tour_save"
        };
        jQuery('#bztsw_add_tour input, #bztsw_add_tour select').each(function() {
            if (jQuery(this).attr('name') ) {
                eval('data.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
            }
        });

        if (jQuery('input[name=bztsw_defaultTour]').is(':checked')) {
            data.bztsw_defaultTour = '1';
        } else {
            data.bztsw_defaultTour = '0';
        }

        if (jQuery('#bztsw_id').val() > 0) {
            eval('localStorage.removeItem(' + jQuery('#bztsw_id').val() + ');');
        }
        
        data.bztsw_isDraft = '1';
        
        jQuery.post(ajaxurl, data, function(response) {
            jQuery("#bztsw_response").html('<div id="bztsw_message" class="updated"><p>Tour <strong>saved as draft</strong>.</p></div>');
            jQuery("#bztsw_response").fadeIn(250);
            jQuery('#bztsw_id').val(response);
            document.location.href = toururl+'wp-admin/admin.php?page=bztsw-tours';
        });

    }
}

function bztsw_Iframe() {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

function bztsw_initFinaltours(helperId) {

    if (!helperId) {
        helperId = 0;
    };

    // componentBody = ((jQuery.browser.chrome) || (jQuery.browser.safari)) ? document.body : document.documentElement;
    componentBody = 'html,body';
    if (window.parent && (window.parent.document.getElementById('bztsw_selectDomFrame'))) {} else {
        if (bztsw_Iframe() && jQuery('.estimationForm_frameSC', window.parent.document).length > 0) {} else {
            bztsw_initTours(helperId);
        }
    }
    jQuery('body *').click(bztsw_stepClick);
    jQuery(window).resize(function() {
        jQuery('#bztsw_overlay').attr({
            width: jQuery(document).outerWidth(),
            height: jQuery(document).outerHeight()
        }).css({
            width: jQuery(document).outerWidth(),
            height: jQuery(document).outerHeight()
        });
    });
}

function bztsw_stepClick(e, mode) {

    if (bztsw_mode1) {
        var self;
        if (!mode) {
            self = this; 
            e.preventDefault();
        } else {
            self = e;
        }
        if (jQuery(self).is('option')) {

        } else {
            if (jQuery(self).children().length == 0 || jQuery(self).is('img') || jQuery(self).is('a') || jQuery(self).is('button') || jQuery(self).is('select') || jQuery(self).is('iframe') || jQuery(self).is('.mce-tinymce')) {
                bztsw_selectedComponent = true;

                if (jQuery(self).is('a') && jQuery(self).find('img').length > 0) {
                    jQuery(self).find('img').addClass('bztsw_selectedComponent');
                    jQuery('.bztsw_selectedComponent').not(jQuery(self).find('img')).removeClass('bztsw_selectedComponent');
                    
                    //~ console.log('self::'+self);
                    
                    window.parent.bztsw_stepSelected(jQuery(self).find('img').get(0));
                } else {
                    jQuery(self).addClass('bztsw_selectedComponent');
                    jQuery('.bztsw_selectedComponent').not(jQuery(self)).removeClass('bztsw_selectedComponent');
                    
                    //~ console.log('self:::'+self);
                    
                    window.parent.bztsw_stepSelected(self);
                }

            }
        }
    }
}

function bztsw_ifSomeParent($el, rep) {
    if (!rep) {
        var rep = false;
    }
    if ($el.closest('.x-sidebar').length > 0) {
        rep = true;
    }
    try {
        if ($el.parent().length > 0 && $el.parent().css('position') == "fixed") {
            rep = true;
        }
    } catch (e) {

    }

    if (!rep && $el.parent().length > 0) {
        rep = bztsw_ifSomeParent($el.parent(), rep);
    }
    return rep;
}

function bztsw_initTours(helperID) {

    if (!helperID) {
        helperID = 0;
    };

    //~ var newHelperID = helperID - 1;
	
	var newHelperID = tours.map(function(o) { return o.bztsw_id; }).indexOf(helperID);
	
	if( newHelperID >= 0 )  {
	
			var chkHelper = false;
			var chkHelperExist = false;
			if (localStorage.getItem('helper')) {
				var helper = tours[newHelperID];
				var url = document.location.href;
				if (url.indexOf('#') > 0) {
					url = document.location.href.substr(0, document.location.href.lastIndexOf('#'));
				}
				if (toururl.substr(toururl.length - 1, 1) != '/') {
					toururl += '/';
				}
				if (bztsw_uname == "") {
					url = url.replace('USERNAME', bztsw_uname);
				}
				if ((parseInt(helper.bztsw_id) == parseInt(localStorage.getItem('helper'))) && (helper.items[parseInt(localStorage.getItem('itemIndex'))]) && (helper.items[parseInt(localStorage.getItem('itemIndex'))].bztsw_pageurl == "" || toururl + helper.items[parseInt(localStorage.getItem('itemIndex'))].bztsw_pageurl == url)) {
					chkHelperExist = helper;
				}
			}
			if (chkHelperExist) {
				chkHelper = true;
				bztsw_beginItem(chkHelperExist, parseInt(localStorage.getItem('itemIndex')));
			}

			var helper = tours[newHelperID];
			var url = document.location.href;
			if (url.indexOf('#') > 0) {
				url = document.location.href.substr(0, document.location.href.lastIndexOf('#'));
			}
			if (url.indexOf('/index.php') > 0) {
				url = url.substr(0, url.lastIndexOf('/'));
			}
			if (helper.bztsw_pageurl.indexOf('index.php') > 0) {
				helper.bztsw_pageurl = helper.bztsw_pageurl.substr(0, helper.bztsw_pageurl.lastIndexOf('/'));
			}

			if (helper.bztsw_pageurl.indexOf('/wp-admin') > 0) {
				helper.bztsw_pageurl = helper.bztsw_pageurl;
			} else {
				helper.bztsw_pageurl = toururl + helper.bztsw_pageurl;
			}

			if (bztsw_uname == "") {
				helper.bztsw_pageurl = helper.bztsw_pageurl.replace('USERNAME', bztsw_uname);
			}


			if (helper.bztsw_pageurl == toururl || helper.bztsw_pageurl == url || helper.bztsw_pageurl + '/' == url || helper.bztsw_pageurl == url + '/') {

				if ((helper.bztsw_onDashboard == 1 && document.location.href.indexOf('wp-admin') < 0) || (helper.bztsw_onDashboard == 0 && document.location.href.indexOf('wp-admin') > 0)) {} else {
					if (helper.bztsw_begin == 'auto' && !chkHelperExist) {
						if (helper.items.length > 0) {
							if (helper.bztsw_onceTime == 1) {
								eval("if(!localStorage.getItem('once" + helper.bztsw_id + "')){ localStorage.setItem('once" + helper.bztsw_id + "','1');chkHelper = true;bztsw_beginTour(helper); }");
							} else {
								chkHelper = true;
								bztsw_beginTour(helper);
							}
						}
					} else {
						if (jQuery(helper.bztsw_domComponent).length > 0) {
							jQuery(helper.bztsw_domComponent).attr('data-helper', helper.bztsw_id);
							jQuery(helper.bztsw_domComponent).click(function(e) {
								e.preventDefault();
								bztsw_beginTour(bztsw_getTourByID(jQuery(this).attr('data-helper')));
							});
						}
					}
				}

			}
			if (!chkHelper) {
				localStorage.removeItem('helper');
				localStorage.removeItem('itemIndex');
			}
			
	}

}

function bztsw_getTourByID(id) {
    var rep = false;
    jQuery.each(tours, function(i) {
        var helper = tours[i];
        if (helper.bztsw_id == id) {
            rep = helper;
        }
    });
    return rep;
}

function bztsw_beginTour(helper) {
    localStorage.setItem('helper', helper.bztsw_id);
    localStorage.setItem('itemIndex', 0);
    bztsw_beginItem(helper, 0);
}

function bztsw_buildOverlay($item, helper) {
    jQuery('body').append('<canvas id="bztsw_overlay"></canvas>');
    jQuery('#bztsw_overlay').attr({
        width: jQuery(document).outerWidth(),
        height: jQuery(document).outerHeight()
    }).css({
        width: jQuery(document).outerWidth(),
        height: jQuery(document).outerHeight()
    });
    var $closeHelperBtn = jQuery('<a href="javascript:" id="bztsw_closeHelperBtn">Close</a>');

    $closeHelperBtn.click(function() {
		
		console.log('clicked');
		
        var helper = bztsw_getTourByID(localStorage.getItem('helper'));
        bztsw_stopTour(jQuery('.bztsw_item'), helper);
    });
    $closeHelperBtn.hide();
    jQuery('body').append($closeHelperBtn);
}

function bztsw_ifCanvas() {
    var elem = document.createElement('canvas');
    return !!(elem.getContext && elem.getContext('2d'));
}

function bztsw_buttonClassName(key){
	
	var className;
	
	if(key == 'small' ) {
		className = 'small_btn';
	} else if(key == 'medium' ) {
		className = 'medium_btn';
	} else if(key == 'large' ) {
		className = 'large_btn';
	}
	return className;
}

var bztsw_initialOverflow = 'auto';

function bztsw_beginItem(helper, index) {
	
	
	if ( helper.bztsw_isActive != '1') {
    var item = helper.items[index];
	
	console.log('itemurl:'+item.bztsw_pageurl);
	console.log('toururl:'+helper.bztsw_pageurl);
	
	console.log('settingsAccessedornot::'+globaltoursettings[0].bztsw_titlecolor);
	
    //~ if(item.bztsw_pageurl != helper.bztsw_pageurl) {
	
		//~ window.location = item.bztsw_pageurl;
		//~ bztsw_beginItem(helper,index);            

		//~ return false;

	//~ }
    
    var $item = false;
    localStorage.setItem('itemIndex', index);
        if (item.bztsw_pageurl != "" && toururl + item.bztsw_pageurl != document.location.href && toururl + item.bztsw_pageurl != document.location.href + '/' && toururl + item.bztsw_pageurl + '/' != document.location.href && toururl + item.bztsw_pageurl + '#' != document.location.href) {
        var chkNoChange = false;
        var defUrl = document.location.href;
        if (defUrl.indexOf('#') > -1) {
            defUrl = defUrl.substr(0, defUrl.length - defUrl.indexOf('#'));
            defUrl = defUrl.replace('#', '');
        }
        var newUrl = item.bztsw_pageurl;
        if (item.bztsw_pageurl.indexOf('#') > -1) {
            newUrl = item.bztsw_pageurl.substr(0, item.bztsw_pageurl.indexOf('#'));
            newUrl = newUrl.replace('#', '');
        }
        if (toururl + newUrl == defUrl) {
            chkNoChange = true;
            document.location.href = toururl + item.bztsw_pageurl;
            setTimeout(function () {
                bztsw_startItem(helper, index);
            }, 1000);
        }


        if (!chkNoChange) {			
            document.location.href = toururl + item.bztsw_pageurl;
        }
    } else {

    bztsw_initialOverflow = jQuery('body').css('overflowY');
    jQuery('body').css({
        overflowY: 'hidden'
    });
    if (item.bztsw_stepTy == 'tooltip' && item.bztsw_domComponent != "" && jQuery(item.bztsw_domComponent).length == 0) {		
        bztsw_nextStep($item, helper, index);
    } else {
        jQuery('#bztsw_overlay,#bztsw_closeHelperBtn').fadeOut(1000);
        if (jQuery('#bztsw_overlay').length == 0) {
            setTimeout(function() {
                bztsw_buildOverlay();
            }, item.bztsw_stepDlySrt * 1000);
        }
        if (item.bztsw_item_overlay == 1 && item.bztsw_item_closeHelperBtn == 1) {
            setTimeout(function() {
                jQuery('#bztsw_closeHelperBtn').delay(400).fadeIn(1000);
            }, item.bztsw_stepDlySrt * 1000);
        }

        setTimeout(function() {
            if (bztsw_ifCanvas()) {
                var ctx = jQuery('#bztsw_overlay').get(0).getContext('2d');
                ctx.clearRect(0, 0, jQuery('#bztsw_overlay').width(), jQuery('#bztsw_overlay').height());
                ctx.globalCompositeOperation = "source-over";
                if (item.bztsw_stepTy == 'tooltip' && item.bztsw_domComponent != "" && jQuery(item.bztsw_domComponent).length > 0) {
                    ctx.fillStyle = "#FFFFFF";
                    ctx.globalCompositeOperation = "source-over";
                    if (bztsw_ifSomeParent(jQuery(item.bztsw_domComponent)) || jQuery(item.bztsw_domComponent).css('position') == 'fixed') {
                        ctx.fillRect(jQuery(item.bztsw_domComponent).offset().left - 5, jQuery(item.bztsw_domComponent).offset().top - 5 - jQuery(item.bztsw_domComponent).scrollTop(), jQuery(item.bztsw_domComponent).outerWidth() + 10, jQuery(item.bztsw_domComponent).outerHeight() + 10);
                    } else {
                        ctx.fillRect(jQuery(item.bztsw_domComponent).offset().left - 5, jQuery(item.bztsw_domComponent).offset().top - 5, jQuery(item.bztsw_domComponent).outerWidth() + 10, jQuery(item.bztsw_domComponent).outerHeight() + 10);
                    }
                    ctx.globalCompositeOperation = "source-out";
                } else {
                    jQuery('#bztsw_overlay').css({
                        backgroundColor: '#000000',
                        display: 'inline'
                    });
                }
                ctx.fillStyle = "#000000";
                ctx.fillRect(0, 0, jQuery('#bztsw_overlay').width(), jQuery('#bztsw_overlay').height());

                jQuery(window).resize(function() {
                    if (item.bztsw_stepTy == 'tooltip' && item.bztsw_domComponent != "" && jQuery(item.bztsw_domComponent).length > 0) {
                        ctx.fillStyle = "#FFFFFF";
                        ctx.globalCompositeOperation = "source-over";
                        if (bztsw_ifSomeParent(jQuery(item.bztsw_domComponent)) || jQuery(item.bztsw_domComponent).css('position') == 'fixed') {
                            ctx.fillRect(jQuery(item.bztsw_domComponent).offset().left - 5, jQuery(item.bztsw_domComponent).offset().top - 5 - jQuery(item.bztsw_domComponent).scrollTop(), jQuery(item.bztsw_domComponent).outerWidth() + 10, jQuery(item.bztsw_domComponent).outerHeight() + 10);
                        } else {
                            ctx.fillRect(jQuery(item.bztsw_domComponent).offset().left - 5, jQuery(item.bztsw_domComponent).offset().top - 5, jQuery(item.bztsw_domComponent).outerWidth() + 10, jQuery(item.bztsw_domComponent).outerHeight() + 10);
                        }
                        ctx.globalCompositeOperation = "source-out";
                        ctx.fillStyle = "#000000";
                        ctx.fillRect(0, 0, jQuery('#bztsw_overlay').width(), jQuery('#bztsw_overlay').height());
                    }
                });
            }
        }, item.bztsw_stepDlySrt * 1000);
        jQuery('html,body').css({
            overflowY: bztsw_initialOverflow
        });

        if (item.bztsw_stepTy == 'tooltip' && item.bztsw_domComponent != "" && jQuery(item.bztsw_domComponent).length > 0) {
            if (item.bztsw_item_overlay == 1) {
                setTimeout(function() {
                    jQuery('#bztsw_overlay').fadeIn(1000);
                }, item.bztsw_stepDlySrt * 1000);
            }
            if (bztsw_ifSomeParent(jQuery(item.bztsw_domComponent)) || jQuery(item.bztsw_domComponent).css('position') == 'fixed') {
                jQuery(componentBody).animate({
                    scrollTop: 0
                }, 500);
                jQuery('html,body').css({
                    overflowY: 'hidden'
                });
            } else {
                jQuery(componentBody).animate({
                    scrollTop: jQuery(item.bztsw_domComponent).offset().top - 200
                }, 500);
            }

            $container = jQuery('<div class="bztsw_container">&nbsp;</div>');
            jQuery(window).resize(function() {
                $container.css({
                    position: 'absolute',
                    zIndex: 999999,
                    left: jQuery(item.bztsw_domComponent).offset().left,
                    top: jQuery(item.bztsw_domComponent).offset().top,
                    width: jQuery(item.bztsw_domComponent).outerWidth(),
                    height: jQuery(item.bztsw_domComponent).outerHeight()

                });

            });


            setTimeout(function() {
                $container.css({
                    position: 'absolute',
                    backgroundColor: 'transparent',
                    zIndex: 999999,
                    left: jQuery(item.bztsw_domComponent).offset().left,
                    top: jQuery(item.bztsw_domComponent).offset().top,
                    width: jQuery(item.bztsw_domComponent).outerWidth(),
                    height: jQuery(item.bztsw_domComponent).outerHeight()
                });
            }, item.bztsw_stepDlySrt * 1000);

            if (item.bztsw_stepAction == 'click') {
                $container.css({
                    'cursor': 'pointer'
                });
                $container.click(function() {
                    jQuery(item.bztsw_domComponent).trigger('click-bztsw');
                });
            }
            jQuery('body').append($container);
        } else {
            if (item.bztsw_overlay == 1) {
                setTimeout(function() {
                    jQuery('#bztsw_overlay').fadeIn(1000);
                }, item.bztsw_stepDlySrt * 1000);
            }
        }

		console.log('itemID::'+item.bztsw_id);   //item ID
		
        if (item.bztsw_stepTy == 'tooltip') {
			
			
			if(item.bztsw_overrideSettings == 1) {

				var btn1Size = item.bztsw_tooltip_btnTSize; //tooltip button1 type s/m/l
				var btn2Size = item.bztsw_tooltip_stop_btnTSize  //tooltip button2 type s/m/l 
				
				var btn1Class = bztsw_buttonClassName(btn1Size);  
				var btn2Class = bztsw_buttonClassName(btn2Size);			

			} else {
				
				var btn1Size = globaltoursettings[0].bztsw_btnTSize; //tooltip button1 type s/m/l
				var btn2Size = globaltoursettings[0].bztsw_stop_btnTSize; //tooltip button2 type s/m/l 
				
				var btn1Class = bztsw_buttonClassName(btn1Size);  
				var btn2Class = bztsw_buttonClassName(btn2Size);
			
			}
			
            $item = jQuery('<div class="bztsw_tooltip" data-position="' + item.bztsw_tooltipPos + '"></div>');
            outerClass = $item.addClass('tourBox_'+item.bztsw_id);  //add class
            $itemContainer = jQuery('<div class="bztsw_tooltip_container"><div class="bztsw_tooltip_text"><h3>' + item.bztsw_title + '</h3></div></div>');
            $item.append($itemContainer);
            item.bztsw_stepCont = item.bztsw_stepCont.replace("\n", "<br/>");
            $itemContainer.append('<div class="bztsw_content">' + item.bztsw_stepCont + '</div>');
            $item.prepend('<div class="bztsw_arrow"></div>');

            if (item.bztsw_stepConbtn != '') {
                $btn = jQuery('<a href="javascript:"  class="bztsw_button bztsw_continue '+btn1Class+'">' + item.bztsw_stepConbtn + '</a>');
                $btn.click(function() {
                    bztsw_nextStep($item, helper, index);
                });
                $item.append('<div class="bztsw_btns" style="text-align:center;"></div>');
                $item.children('.bztsw_btns').append($btn);
            }
            
            if (item.bztsw_stepStopbtn != "") {
                $btnS = jQuery('<a href="javascript:"  class="bztsw_button bztsw_button_stop '+btn2Class+'">' + item.bztsw_stepStopbtn + '</a>');
                $btnS.click(function() {
                    bztsw_stopTour($item, helper);
                });
                $item.children('.bztsw_btns').append($btnS);
            }

            $item.children('.bztsw_btns').append('<div class="clear"></div>');

            //close Helper Button
            if (item.bztsw_item_closeHelperBtn != '' && item.bztsw_item_closeHelperBtn == '1') {
                var $closeHelperBtn = jQuery('<a href="javascript:" id="bztsw_closeHelperBtn">Close</a>');
                $closeHelperBtn.click(function() {
                    var helper = bztsw_getTourByID(localStorage.getItem('helper'));
                    bztsw_stopTour(jQuery('.bztsw_item'), helper);
                });
                $closeHelperBtn.hide();
                $item.children('.bztsw_btns').append($closeHelperBtn);
                //$item.append($closeHelperBtn);
            }

            if (item.bztsw_domComponent && jQuery(item.bztsw_domComponent).length > 0) {
                jQuery(window).resize(function() {
                    if (jQuery(item.bztsw_domComponent).length > 0) {
                        $item.css({
                            left: jQuery(item.bztsw_domComponent).offset().left,
                            top: jQuery(item.bztsw_domComponent).offset().top
                        });
                    }
                });
                $item.css({
                    left: jQuery(item.bztsw_domComponent).offset().left,
                    top: jQuery(item.bztsw_domComponent).offset().top
                });
                jQuery('body').append($item);
                if (item.bztsw_tooltipPos == 'top') {
                    jQuery(window).resize(function() {
                        var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                        if (left < 0) {
                            left = 0;
                        }
                        if (left + $item.outerWidth() > jQuery(window).width()) {
                            left = 0;
                        }
                        $item.css({
                            top: parseInt($item.css('top')) - ($item.height() + 20),
                            left: left
                        });
                    });
                    var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                    if (left < 0) {
                        left = 0;
                    }
                    if (left + $item.outerWidth() > jQuery(window).width()) {
                        left = 0;
                    }
                    setTimeout(function() {
                        floatTooltip($item);
                    }, item.bztsw_stepDlySrt * 1000);
                    setTimeout(function() {
                        $item.css({
                            left: jQuery(item.bztsw_domComponent).offset().left,
                            top: jQuery(item.bztsw_domComponent).offset().top
                        });
                        $item.css({
                            top: parseInt($item.css('top')) - ($item.height() + 20),
                            left: left
                        });
                        $item.fadeIn(500);
                    }, item.bztsw_stepDlySrt * 1000);


                } else if (item.bztsw_tooltipPos == 'bottom') {
                    jQuery(window).resize(function() {
                        var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                        if (left < 0) {
                            left = 0;
                        }
                        if (left + $item.outerWidth() > jQuery(window).width()) {
                            left = 0;
                        }
                        $item.css({
                            top: parseInt($item.css('top')) + jQuery(item.bztsw_domComponent).outerHeight() + 20,
                            left: left
                        });
                    });
                    var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                    if (left < 0) {
                        left = 0;
                    }
                    if (left + $item.outerWidth() > jQuery(window).width()) {
                        left = 0;
                    }
                    setTimeout(function() {
                        floatTooltip($item);
                    }, item.bztsw_stepDlySrt * 1000);

                    setTimeout(function() {
                        $item.css({
                            left: jQuery(item.bztsw_domComponent).offset().left,
                            top: jQuery(item.bztsw_domComponent).offset().top
                        });
                        $item.css({
                            top: parseInt($item.css('top')) + jQuery(item.bztsw_domComponent).outerHeight() + 20,
                            left: left
                        });
                        $item.fadeIn(500);
                    }, item.bztsw_stepDlySrt * 1000);
                    
                } else if (item.bztsw_tooltipPos == 'right') {
					
                    jQuery(window).resize(function() {
                        var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                        if (left < 0) {
                            left = 0;
                        }
                        if (left + $item.outerWidth() > jQuery(window).width()) {
                            left = 0;
                        }
                        $item.css({
                            top: parseInt($item.css('top')) + jQuery(item.bztsw_domComponent).outerHeight(),
                            left: left
                        });
                    });
                    var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                    if (left < 0) {
                        left = 0;
                    }
                    if (left + $item.outerWidth() > jQuery(window).width()) {
                        left = 0;
                    }
                    setTimeout(function() {
                        floatTooltip($item);
                    }, item.bztsw_stepDlySrt * 1000);
                    
                    console.log( 'sadss::'+ jQuery(item.bztsw_domComponent).outerHeight() );
                    
                    setTimeout(function() {
                        $item.css({
                            top: jQuery(item.bztsw_domComponent).offset().top,
                            left: jQuery(item.bztsw_domComponent).offset().left + jQuery(item.bztsw_domComponent).outerWidth() +20,

                        });
                        $item.fadeIn(500);
                    }, item.bztsw_stepDlySrt * 1000);
                    
                } else if (item.bztsw_tooltipPos == 'left') {
					
                    jQuery(window).resize(function() {
                        var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                        if (left < 0) {
                            left = 0;
                        }
                        if (left + $item.outerWidth() > jQuery(window).width()) {
                            left = 0;
                        }
                        $item.css({
                            top: parseInt($item.css('top')) + jQuery(item.bztsw_domComponent).outerHeight(),
                            left: left
                        });
                    });
                    var left = parseInt($item.css('left')) + jQuery(item.bztsw_domComponent).outerWidth() / 2 - ($item.width() / 2);
                    if (left < 0) {
                        left = 0;
                    }
                    if (left + $item.outerWidth() > jQuery(window).width()) {
                        left = 0;
                    }
                    setTimeout(function() {
                        floatTooltip($item);
                    }, item.bztsw_stepDlySrt * 1000);
					
					jQuery('.bztsw_tooltip[data-position=left] .bztsw_arrow').css({'left':jQuery(item.bztsw_domComponent).offset().left + 10});
					
					console.log('leftofset::'+jQuery(item.bztsw_domComponent).offset().left);
					console.log('leftofsetwidth::'+jQuery(item.bztsw_domComponent).outerWidth());
					
                    setTimeout(function() {
						//~ $item.css({
                            //~ left: jQuery(item.bztsw_domComponent).offset().left,
                            //~ top: jQuery(item.bztsw_domComponent).offset().top
                        //~ });
                        $item.css({
                            top: parseInt($item.css('top')),
                            left: -jQuery(item.bztsw_domComponent).outerWidth() /2 + (jQuery(item.bztsw_domComponent).offset().left - jQuery(item.bztsw_domComponent).outerWidth() ),

                        });
                        $item.fadeIn(500);
                    }, item.bztsw_stepDlySrt * 1000);
                }

            }
        } else if (item.bztsw_stepTy == 'dialog') {
			
			if(item.bztsw_overrideSettings == 1) {

				var btn1Size = item.bztsw_dialog_btnTSize; //dialog button1 type s/m/l
				var btn2Size = item.bztsw_dialog_stop_btnTSize  //dialog button2 type s/m/l 
				
				var btn1Class = bztsw_buttonClassName(btn1Size);  
				var btn2Class = bztsw_buttonClassName(btn2Size);			

			} else {
				
				var btn1Size = globaltoursettings[0].bztsw_dia_btnTSize; //dialog button1 type s/m/l
				var btn2Size = globaltoursettings[0].bztsw_dia_stop_btnTSize; //dialog button2 type s/m/l 
				
				var btn1Class = bztsw_buttonClassName(btn1Size);  
				var btn2Class = bztsw_buttonClassName(btn2Size);
			
			}
			
			
            $item = jQuery('<div class="bztsw_dialog"></div>');
			outerClass = $item.addClass('tourBox_'+item.bztsw_id);  //add class
            $close = jQuery('<a href="javascript:" class="bztsw_close fui-cross"></a>');
            $close.click(function() {
                bztsw_stopTour($item, helper);
            });
            $item.prepend($close);
            $item.append('<h3>' + item.bztsw_title + '</h3>');
            $item.append('<div class="bztsw_content">' + item.bztsw_stepCont + '</div>');
            $btn = jQuery('<a href="javascript:"  class="bztsw_button bztsw_continue '+btn1Class+'">' + item.bztsw_stepConbtn + '</a>');
            $btn.click(function() {
                bztsw_nextStep($item, helper, index);
            });
            $item.append('<div class="bztsw_btns" style="text-align:center;"></div>');
            $item.children('.bztsw_btns').append($btn);
            if (item.bztsw_stepStopbtn != "") {
                $btnS = jQuery('<a href="javascript:"  class="bztsw_button bztsw_button_stop '+btn2Class+'">' + item.bztsw_stepStopbtn + '</a>');
                $btnS.click(function() {
                    bztsw_stopTour($item, helper);
                });
                $item.children('.bztsw_btns').append($btnS);
            }

            $item.children('.bztsw_btns').append('<div class="clear"></div>');

            //close Helper Button
            if (item.bztsw_item_closeHelperBtn != '' && item.bztsw_item_closeHelperBtn == '1') {
                var $closeHelperBtn = jQuery('<a href="javascript:" id="bztsw_closeHelperBtn">Close</a>');
                $closeHelperBtn.click(function() {
                    var helper = bztsw_getTourByID(localStorage.getItem('helper'));
                    bztsw_stopTour(jQuery('.bztsw_item'), helper);
                });
                $closeHelperBtn.hide();
                //$item.append($closeHelperBtn);
                $item.children('.bztsw_btns').append($closeHelperBtn);
            }

            jQuery('body').append($item);
            $item.css({
                opacity: 0
            });
            $item.show();
            jQuery(window).resize(function() {
                $item.css({
                    marginLeft: 0 - $item.outerWidth() / 2,
                    marginTop: 0 - $item.outerHeight() / 2
                });
            });
            $item.css({
                left: '50%',
                top: -500,
                opacity: 0,
                marginLeft: 0 - $item.outerWidth() / 2,
                marginTop: 0 - $item.outerHeight() / 2
            });
            $item.animate({
                top: '50%',
                opacity: 1
            }, 500);
        } else if (item.bztsw_stepTy == 'text') {
            $item = jQuery('<div class="bztsw_text"></div>');
            $item.addClass('tourBox_'+item.bztsw_id);
            $title = jQuery('<h2>' + item.bztsw_title + '</h2>');
            $content = jQuery('<div>' + item.bztsw_stepCont + '</div>');
            $item.append($title);
            $item.append($content);

            $item.append('<div class="clear"></div>');
            //close Helper Button
            if (item.bztsw_item_closeHelperBtn != '' && item.bztsw_item_closeHelperBtn == '1') {
                var $closeHelperBtn = jQuery('<a href="javascript:" id="bztsw_closeHelperBtn">Close</a>');
                $closeHelperBtn.click(function() {
                    var helper = bztsw_getTourByID(localStorage.getItem('helper'));
                    bztsw_stopTour(jQuery('.bztsw_item'), helper);
                });
                $closeHelperBtn.hide();
                $item.append($closeHelperBtn);

            }

            jQuery('body').append($item);
            $item.css({
                marginTop: 0 - $item.height() / 2
            });
            $item.hide();
            $item.fadeIn(500);
            $title.hide().delay(1000).fadeIn(1000);
            $content.hide().delay(2000).fadeIn(1000);
            setTimeout(function() {
                jQuery('#bztsw_overlay').animate({
                    opacity: 0.9
                }, 1000);
            }, 1000);


        }
        $item.addClass('bztsw_item');
        if (item.bztsw_stepAction == 'click') {
            jQuery(item.bztsw_domComponent).unbind('click-bztsw');
            jQuery(item.bztsw_domComponent).bind('click-bztsw', function() {
                bztsw_nextStep($item, helper, index);
                jQuery(item.bztsw_domComponent).unbind('click-bztsw');
            });
            jQuery(item.bztsw_domComponent).click(function() {
                jQuery(this).trigger("click-bztsw");
            });
        } else {
            bztsw_counter = setTimeout(function() {
                bztsw_nextStep($item, helper, index);
            }, (item.bztsw_stepDly * 1000) + (item.bztsw_stepDlySrt * 1000));
        }
    }
    }
	}
}

function floatTooltip($item) {
    if ($item.length > 0) {
        if ($item.is('[data-position="bottom"]')) {
            setTimeout(function() {
                $item.animate({
                    top: $item.position().top + 20
                }, 200, function() {
                    $item.animate({
                        top: $item.position().top - 20
                    }, 200, function() {
                        $item.animate({
                            top: $item.position().top + 20
                        }, 200, function() {
                            $item.animate({
                                top: $item.position().top - 20
                            }, 200, function() {
                                $item.animate({
                                    top: $item.position().top + 20
                                }, 200, function() {
                                    $item.animate({
                                        top: $item.position().top - 20
                                    }, 200);
                                    flipCounter = setTimeout(function() {
                                        floatTooltip($item);
                                    }, 3000);
                                });
                            });
                        });
                    });
                });
            });
       } else if ($item.is('[data-position="right"]')) {
            setTimeout(function() {
                $item.animate({
                    top: $item.position().top
                }, 200, function() {
                    $item.animate({
                        top: $item.position().top
                    }, 200, function() {
                        $item.animate({
                            top: $item.position().top
                        }, 200, function() {
                            $item.animate({
                                top: $item.position().top
                            }, 200, function() {
                                $item.animate({
                                    top: $item.position().top
                                }, 200, function() {
                                    $item.animate({
                                        top: $item.position().top
                                    }, 200);
                                    flipCounter = setTimeout(function() {
                                        floatTooltip($item);
                                    }, 3000);
                                });
                            });
                        });
                    });
                });
            });
       } else if ($item.is('[data-position="left"]')) {
            setTimeout(function() {
                $item.animate({
                    top: $item.position().top
                }, 200, function() {
                    $item.animate({
                        top: $item.position().top
                    }, 200, function() {
                        $item.animate({
                            top: $item.position().top
                        }, 200, function() {
                            $item.animate({
                                top: $item.position().top
                            }, 200, function() {
                                $item.animate({
                                    top: $item.position().top
                                }, 200, function() {
                                    $item.animate({
                                        top: $item.position().top
                                    }, 200);
                                    flipCounter = setTimeout(function() {
                                        floatTooltip($item);
                                    }, 3000);
                                });
                            });
                        });
                    });
                });
            });
        } else if ($item.is('[data-position="top"]')) {
            setTimeout(function() {
                $item.animate({
                    top: $item.position().top - 20
                }, 200, function() {
                    $item.animate({
                        top: $item.position().top + 20
                    }, 200, function() {
                        $item.animate({
                            top: $item.position().top - 20
                        }, 200, function() {
                            $item.animate({
                                top: $item.position().top + 20
                            }, 200, function() {
                                $item.animate({
                                    top: $item.position().top - 20
                                }, 200, function() {
                                    $item.animate({
                                        top: $item.position().top + 20
                                    }, 200);
                                    flipCounter = setTimeout(function() {
                                        floatTooltip($item);
                                    }, 3000);
                                });
                            });
                        });
                    });
                });
            });
        }
    } else {
        clearTimer(flipCounter);
    }

}

function bztsw_buildModeofSelection(mode) {
    bztsw_mode1 = mode;
    if (mode) {
        bztsw_selectedComponent = false;
    }
}

function bztsw_nextStep($item, helper, index) {
	
	if ($item) {
        if (bztsw_counter) {
            clearTimeout(bztsw_counter);
        }
        if ($item.is('.bztsw_dialog')) {
            $item.animate({
                top: -500,
                opacity: 0
            }, 800);
        } else {
            $item.fadeOut(1000);
        }
        setTimeout(function() {
            $item.remove();
        }, 1500);
    }
    jQuery('#bztsw_closeHelperBtn').fadeOut(500);
    jQuery('#bztsw_overlay').delay(200).fadeOut(700);
    setTimeout(function() {
        jQuery('html,body').css({
            overflowY: bztsw_initialOverflow
        });
        jQuery('.bztsw_container').remove();
        jQuery('#bztsw_overlay,#bztsw_closeHelperBtn').remove();
    }, 750);
    var i = index + 1;
    if (helper.items[i]) {		
        localStorage.setItem('itemIndex', i);
        setTimeout(function() {
            bztsw_beginItem(helper, i);
        }, 800);
    } else {
        localStorage.removeItem('helper');
        localStorage.removeItem('itemIndex');
    }
}

function bztsw_stopTour($item, helper) {
    localStorage.removeItem('helper');
    localStorage.removeItem('itemIndex');

    if (bztsw_counter) {
        clearTimeout(bztsw_counter);
    }
    if ($item.is('.bztsw_dialog')) {
        $item.animate({
            top: -500,
            opacity: 0
        }, 500);
    } else {
        $item.fadeOut(500);
    }
    setTimeout(function() {
        $item.remove();
        jQuery('html,body').css({
            overflowY: bztsw_initialOverflow
        });
    }, 1500);
    jQuery('#bztsw_closeHelperBtn').fadeOut(500);
    jQuery('#bztsw_overlay').fadeOut(1000);
    setTimeout(function() {
        jQuery('.bztsw_container').remove();
        jQuery('#bztsw_overlay,#bztsw_closeHelperBtn').remove();
        localStorage.removeItem('helper');
        localStorage.removeItem('itemIndex');
    }, 1100);
}
