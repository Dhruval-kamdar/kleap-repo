function openPage(pageName,elmnt,color) {

	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("ee-mb-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
	
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
    }
	
    document.getElementById(pageName).style.display = "block";
    
	if(elmnt != ''){
		elmnt.style.backgroundColor = color;
	}else{
		var button = document.querySelector("button[data-page='"+pageName+"']");
		if(button){
			button.style.backgroundColor = color;
		}
	}
}

jQuery(document).ready(function(){
    /*@ Tab active deactive start */
    jQuery('.ee-mb-tab-contain .tablink').click(function(){
        jQuery(this).siblings('.tablink').removeClass('tab-Selected');
        jQuery(this).addClass('tab-Selected');
        var tmp = jQuery(this).data('page');
        window.location.hash = tmp;
        openPage(tmp, '', 'white');
    });

    if(window.location.hash){
        var tmp = window.location.hash.substr(1);
        openPage(tmp, '', 'white');
    }else{
        jQuery('.ee-mb-tab-contain .tablink:first').trigger( "click" );
    }
    /*@ Tab ends here */

    /*@ Enable color picker */
    jQuery('.es_color_field').wpColorPicker();
    
    /*@ Cookie overlay section hide/show */
    jQuery(document).on('change','.chk_cookie_overlay',function(){
        jQuery('tr.overlay_options').slideToggle();
    });

    jQuery(document).on('change','.chk_cookie_enable',function(){
        jQuery('div.cookie_options').slideToggle();
    });

    /*@ Custom fields for event CPT start */
    jQuery('.es_date_picker').datepicker({
        dateFormat : 'yy-mm-dd'
    });

    jQuery('.es_time_picker').timepicker({
        dropdown: true,
        scrollbar: true
    });

    if(jQuery('#_ee_mb_all_day_event').length > 0 && jQuery('#_ee_mb_all_day_event').is(':checked')){
        jQuery('.es_time_picker').parents('tr').slideUp('fast');
    }

    jQuery('#_ee_mb_all_day_event').on('change',function(){
        jQuery('#_ee_mb_start_time').val('12:00am');
        jQuery('#_ee_mb_end_time').val('11:59pm');
        jQuery('.es_time_picker').parents('tr').slideToggle('slow');
    });

    if(jQuery('#_ee_mb_external_url_chk').length > 0 && jQuery('#_ee_mb_external_url_chk').is(':checked')){
        jQuery('#_ee_mb_event_page_link').parents('tr').slideUp('fast');
    }else{
        jQuery('#_ee_mb_event_external_link').parents('tr').hide('fast');
    }

    jQuery('#_ee_mb_external_url_chk').on('change',function(){
        jQuery('#_ee_mb_event_page_link').parents('tr').slideToggle('fast');
        jQuery('#_ee_mb_event_external_link').parents('tr').slideToggle('fast');
    });
    /*@ Custom fields Ends */
});
