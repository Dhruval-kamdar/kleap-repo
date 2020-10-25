function bztsw_selectTarget() {

    if (jQuery('#bztsw_add_step input[name=bztsw_pageurl]').length > 0 && jQuery('#bztsw_add_step input[name=bztsw_pageurl]').val().length > 3) {
        if (jQuery('#bztsw_add_step input[name=bztsw_pageurl]').val().indexOf('http') < 0) {
            frontpageurl = frontpageurl + jQuery('#bztsw_add_step input[name=bztsw_pageurl]').val();
        } else {
            frontpageurl = jQuery('#bztsw_add_step input[name=bztsw_pageurl]').val();
        }
    }
    
    if (jQuery('#bztsw_add_tour input[name=bztsw_pageurl]').length > 0 && jQuery('#bztsw_add_tour input[name=bztsw_pageurl]').val().length > 3) {
        if (jQuery('#bztsw_add_tour input[name=bztsw_pageurl]').val().indexOf('http') < 0) {
            frontpageurl = frontpageurl + jQuery('#bztsw_add_tour input[name=bztsw_pageurl]').val();
        } else {
            frontpageurl = jQuery('#bztsw_add_tour input[name=bztsw_pageurl]').val();
        }
    }

    if (jQuery('#bztsw_onDashboard').val() == '1') {
        frontpageurl = dashboardurl;
    }
	
    $frame = jQuery('<iframe id="bztsw_selectIframe" src="' + frontpageurl + '"></iframe>');
    jQuery('body').append($frame);
    $panel = jQuery('<div id="bztsw_componentPanel"></div>');
    jQuery('body').append($panel);
    $panel.html('Loading ...');
    jQuery("#bztsw_selectIframe").load(function() {
    bztsw_dilogSelectorText();
	});
}

var bztsw_modeSelection = false;

function bztsw_choosenElement() {
    //~ jQuery("#bztsw_selectIframe").load(function() {
        jQuery('#bztsw_selectIframe').get(0).contentWindow.bztsw_buildModeofSelection(true);
        $panel = jQuery('#bztsw_componentPanel');
        $panel.html('<p>Select Component</p>');
    //~ });
}

function bztsw_stepSelected(el) {
    $panel = jQuery('#bztsw_componentPanel');
    $panel.html('<h3>Component selected</h3>');

    var elementIdentified = getUrl(el);
    $panel.append('<p>Is it the one you Selected ?</p>');
    $panel.append('<p><a href="javascript:" class="button-primary" onclick="bztsw_finalizeSelectedComponent(\'' + elementIdentified + '\');">Yes</a>' +
        '<a href="javascript:" class="button-secondary" onclick="bztsw_choosenElement();">No</a></p>');
}

function identifyComponent(el) {
    var identification = "";
    if (jQuery(el).attr('id')) {
        identification = jQuery(el).attr('id');
    } else {
        identification = getUrl(el);
    }
    return identification;
}

function getUrl(el) {
    var path = '';
    if (jQuery(el).length > 0 && typeof(jQuery(el).prop('tagName')) != "undefined") {
        if (!jQuery(el).attr('id') || jQuery(el).attr('id').substr(0, 9) == 'ultimate-') {
            path = '>' + jQuery(el).prop('tagName') + ':nth-child(' + (jQuery(el).index() + 1) + ')' + path;
            path = getUrl(jQuery(el).parent()) + path;
        } else {
            path += '#' + jQuery(el).attr('id');
        }
    }
    return path;
}

function bztsw_dilogSelectorText() {
    $panel = jQuery('#bztsw_componentPanel');
    $panel.html('<h3>Select a static Component</h3>');
    $panel.append('<p><a href="javascript:" class="button-primary" onclick="bztsw_choosenElement();">Choose</a></p>');
}

function bztsw_finalizeSelectedComponent(path) {
    var page = jQuery('#bztsw_selectIframe').get(0).contentWindow.document.location.href;
    if (page.substr(page.length - 2, 2) == '//') {
        page = page.substr(0, page.length - 1);
    }
    jQuery('input[name=bztsw_pageurl]').val(page);
    jQuery('#bztsw_domComponent').val(path);
    jQuery('#bztsw_domComponent').parent().children('span').html('Element selected');
    jQuery('#bztsw_selectIframe,#bztsw_componentPanel').remove();
}
