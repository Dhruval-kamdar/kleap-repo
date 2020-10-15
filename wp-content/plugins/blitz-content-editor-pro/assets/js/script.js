jQuery(document).ready(function(){

//add repeater
jQuery("#repeater").createRepeater({
   showFirstItemToDefault: true,
});
   
   
//Remove ROW CEP Options
jQuery(".repeater-remove-btn .remove-btn").click(function(e) {
var rowid = jQuery(this).attr('data-id');	
		
  jQuery.ajax({
  type:"POST",
  url: "admin-ajax.php",
  data: {
      'delId' : rowid,
      'action' : 'delete_row'
  },
  success:function(data){
  //~ console.log('success');
  },
  error: function(errorThrown){
  //~ console.log('error');
  }
  
});
jQuery(this).parents('.items').remove(); 
});


//main create page layout
jQuery(' .post-type-layouts .acf-flexible-content .values .layout[data-layout="sitepageBlock"]').each(function(counter, obj) {
		
//collapse initially
jQuery(this).addClass('-collapsed');

});

//main create page layout
jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"]').each(function(counter, obj) {
	

//contentBlock
var contentBlock = jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="contentBlock"]').length;
var ceLength = contentBlock-1;
updateShortcode('contentBlock',ceLength,counter);

//richtextBlock
var richtextBlock = jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="richtextBlock"]').length;
var rcLength = richtextBlock-1;
updateShortcode('richtextBlock',rcLength,counter);

//spintaxBlock
var spintaxBlock = jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="spintaxBlock"]').length;
var spLength = spintaxBlock-1;
updateShortcode('spintaxBlock',spLength,counter);

//mediaBlock
var mediaBlock = jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="mediaBlock"]').length;
var mdLength = mediaBlock-1;
updateShortcode('mediaBlock',mdLength,counter);

//statBlock
var statBlock = jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="statBlock"]').length;
var stLength = statBlock-1;
updateShortcode('statBlock',stLength,counter);

//testimonialBlock
var testimonialBlock = jQuery('.acf-flexible-content .values .layout[data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="testimonialBlock"]').length;
var tesLength = testimonialBlock-1;
updateShortcode('testimonialBlock',tesLength,counter);

});

function updateShortcode(blockName,blockLength,counter) {
		
		var pageCount = counter;
		
		//~ jQuery('.acf-flexible-content .values .layout[data-layout="'+blockName+'"]').each(function (c, obj) {

			var ce_shrtcode = '';
			var row_shrtcode = '';
			var result1 = '';
			var type = '';
			var crow = '';
			var ceRowLength = '';
			
			var blockDataId =  jQuery(this).attr('data-id');
			var current_url      = window.location.href;
			
			//~ console.log('.acf-flexible-content .values .layout[data-layout="'+blockName+'"] .acf-table .acf-row');
			
			row_shrtcode  = jQuery('.acf-flexible-content .values .layout[data-id='+pageCount+'][data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="'+blockName+'"] .acf-table .acf-row').length;
			ceRowLength = row_shrtcode-1;
			
			
			if( ceRowLength > 0) { 
				
				for(var crow = 0; crow <= ceRowLength; crow++ ) {
												
					jQuery('.acf-flexible-content .values .layout[data-id='+pageCount+'][data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="'+blockName+'"] .acf-table .acf-row[data-id="'+crow+'"] .acf-label p.description small').each(function () {
					

					ce_shrtcode  =  jQuery(this).html();
					result1 = ce_shrtcode.split(' ');
					
					//type
					if (result1[2].indexOf("_") >= 0) {
					type = result1[2];
					} else {
					type = result1[2]+'_'+crow;
					}
										
					var typeNew = type.split('_');
					var typeNew1 = typeNew[0].split('=');
					
					//~ console.log('typenew:'+typeNew1[1]);
					
					if (~current_url.indexOf("page=content-pro-settings")) {   //if single site
						
						if(typeNew1[1] == 'medimage' || typeNew1[1] == 'contentimage' || typeNew1[1] == 'contentimage1' || typeNew1[1] == 'contentimage2' || typeNew1[1] == 'contentimage3' || typeNew1[1] == 'testiimage') {
							
							jQuery(this).html(result1[0]+' page='+ pageCount+' '+type+' '+result1[3] +' '+result1[4]); //update shortcode
							
						} else {
														
							jQuery(this).html(result1[0]+' page='+ pageCount+' '+type+' '+result1[3]); //update shortcode

						}
				
					} else {
							
					   row1 = result1[2].split('_');	
					   jQuery(this).html(result1[0]+' page='+ pageCount+' '+type+' '+result1[3]); //update shortcode
					
					}
					
			
					});
				}
				
			} else {					
					
					jQuery('.acf-flexible-content .values .layout[data-id='+pageCount+'][data-layout="sitepageBlock"] .acf-field-create-layout .values .layout[data-layout="'+blockName+'"] .acf-table .acf-row .acf-label p.description small').each(function () {
					
					ce_shrtcode  =  jQuery(this).html();
					result1 = ce_shrtcode.split(' ');
					
					//type
					if (result1[2].indexOf("_") >= 0) {
					type = result1[2]+'_'+blockLength;
					} else {
					type = result1[2];
					}
					
															
					var typeNew = type.split('_');
					var typeNew1 = typeNew[0].split('=');
					
					if(typeNew1[1] == 'medimage' || typeNew1[1] == 'contentimage' || typeNew1[1] == 'contentimage1' || typeNew1[1] == 'contentimage2' || typeNew1[1] == 'contentimage3' || typeNew1[1] == 'testiimage') {
							jQuery(this).html(result1[0]+' page='+ pageCount+' '+type+' '+result1[3]+' '+result1[4]); //update shortcode
							
					} else {
							jQuery(this).html(result1[0]+' page='+ pageCount+' '+type+' '+result1[3]); //update shortcode

					}
					
					
					});
			}
			
		//~ });
		


}

//~ jQuery('.acf-fields .layout[data-layout="sitepageBlock"] .acf-field-sitepage-blockname input, .acf-fields .layout.-collapsed[data-layout="sitepageBlock"] .acf-field-sitepage-blockname input').each(function() {
	
	//~ var pageName = jQuery(this).val();
	//~ var pageId = jQuery(this).attr('id');
	
	//~ var pageid1 = pageId.split('acf-field_create_site_layout-');
	//~ var pageid2 = pageid1[1].split('-');
	
	//~ var html1 =jQuery('.acf-fields .layout[data-layout="sitepageBlock"][data-id="'+pageid2[0]+'"] .acf-fc-layout-handle').html();
	//~ var html2 =html1.split('</span>');
	//~ var newHtml = html2[0]+"</span>"+pageName;
	//~ jQuery('.acf-fields .layout[data-layout="sitepageBlock"][data-id="'+pageid2[0]+'"] .acf-fc-layout-handle').first().html(newHtml);
	
//~ });


jQuery(document).on( 'keyup','.acf-fields .layout[data-layout="sitepageBlock"] .acf-field-sitepage-blockname input', function( event ){	
	
	var pageName = jQuery(this).val();
	var pageId = jQuery(this).attr('id');
	
	//~ console.log('pid:'+pageId);
	
	var pageid1 = pageId.split('acf-field_create_site_layout-');
	var pageid2 = pageid1[1].split('-');
	
	var html1 =jQuery('.acf-fields .layout[data-layout="sitepageBlock"][data-id="'+pageid2[0]+'"] .acf-fc-layout-handle').html();
	var html2 =html1.split('</span>');
	var newHtml = html2[0]+"</span>"+pageName;
	jQuery('.acf-fields .layout[data-layout="sitepageBlock"][data-id="'+pageid2[0]+'"] .acf-fc-layout-handle').first().html(newHtml);
	
});


//~ jQuery(document).on( 'click','.acf-fields .layout[data-layout="sitepageBlock"]', function( event ){
			
			//~ var pageId = jQuery(this).find('.acf-field-sitepage-blockname input').attr('id');
			
			//~ var pageName = jQuery(this).find('.acf-field-sitepage-blockname input').val();
			
			//~ console.log('ss:'+pageId);
			
			//~ var pageid1 = pageId.split('acf-field_create_site_layout-');
			//~ var pageid2 = pageid1[1].split('-');
			
			//~ var html1 =jQuery(this).find('.acf-fc-layout-handle').html();
			//~ var html2 =html1.split('</span>');
			//~ var newHtml = html2[0]+"</span>"+pageName;
			
			//~ jQuery(this).find('.acf-fc-layout-handle').first().html(newHtml);
			
//~ });

//~ jQuery(document).on( 'change','.acf-field[data-name="shDisable_richtext"] input[type="checkbox"]', function( event ){
	
	//~ console.log('ssd');

	 //~ if(jQuery(this).is(':checked')) {
	
		//~ jQuery('.acf-field[data-name="richtext"] p.description').show();
	
	 //~ } else {
		 
        //~ jQuery('.acf-fields[data-name="richtext"] p.description').hide();
        
     //~ }
     
//~ });



//OnReady show/hide shortcodes of fields

var ischecked= jQuery('.acf-field[data-name="shDisable_richtext"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="richtext"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="richtext"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_spintax"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="paragraph"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="paragraph"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_media"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="medimage"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="medimage"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_statTitle"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="stattitle"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="stattitle"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_statNum"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="statnum"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="statnum"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_contenttitle"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenttitle"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenttitle"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_contenticon"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenticon"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenticon"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_contentimage"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contentimage"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contentimage"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_contenttext"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenttext"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenttext"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_testiname"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="testiname"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="testiname"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_testijob"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="testijob"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="testijob"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_testiicon"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="testiicon"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="testiicon"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_testiimage"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="testiimage"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="testiimage"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_testicontent"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="testicontent"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="testicontent"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_titles"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenttitle1"] p.description,.acf-field[data-name="contenttitle2"] p.description,.acf-field[data-name="contenttitle3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenttitle1"] p.description,.acf-field[data-name="contenttitle2"] p.description,.acf-field[data-name="contenttitle3"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_buttons"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="buttontitle1"] p.description,.acf-field[data-name="buttontitle2"] p.description,.acf-field[data-name="buttontitle3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="buttontitle1"] p.description,.acf-field[data-name="buttontitle2"] p.description,.acf-field[data-name="buttontitle3"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_images"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contentimage1"] p.description,.acf-field[data-name="contentimage2"] p.description,.acf-field[data-name="contentimage3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contentimage1"] p.description,.acf-field[data-name="contentimage2"] p.description,.acf-field[data-name="contentimage3"] p.description').show();
}
var ischecked= jQuery('.acf-field[data-name="shDisable_icons"] input[type="checkbox"]').is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenticon1"] p.description,.acf-field[data-name="contenticon2"] p.description,.acf-field[data-name="contenticon3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenticon1"] p.description,.acf-field[data-name="contenticon2"] p.description,.acf-field[data-name="contenticon3"] p.description').show();
}
	
	


//Onclick show/hide shortcodes of fields

jQuery('.acf-field[data-name="shDisable_richtext"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="richtext"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="richtext"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_spintax"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="paragraph"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="paragraph"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_media"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="medimage"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="medimage"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_statTitle"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="stattitle"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="stattitle"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_statNum"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="statnum"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="statnum"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_contenttitle"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="contenttitle"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="contenttitle"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_contenticon"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="contenticon"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="contenticon"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_contentimage"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="contentimage"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="contentimage"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_contenttext"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="contenttext"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="contenttext"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_testiname"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="testiname"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="testiname"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_testijob"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="testijob"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="testijob"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_testiicon"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="testiicon"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="testiicon"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_testiimage"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="testiimage"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="testiimage"] p.description').show();
	}
}); 
jQuery('.acf-field[data-name="shDisable_testicontent"] input[type="checkbox"]').change(function() {
    var ischecked= jQuery(this).is(':checked');
    if(!ischecked){
		jQuery('.acf-field[data-name="testicontent"] p.description').hide();
	} else {
		jQuery('.acf-field[data-name="testicontent"] p.description').show();
	}
}); 

jQuery('.acf-field[data-name="shDisable_titles"] input[type="checkbox"]').change(function() {
var ischecked= jQuery(this).is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenttitle1"] p.description,.acf-field[data-name="contenttitle2"] p.description,.acf-field[data-name="contenttitle3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenttitle1"] p.description,.acf-field[data-name="contenttitle2"] p.description,.acf-field[data-name="contenttitle3"] p.description').show();
}
});

jQuery('.acf-field[data-name="shDisable_buttons"] input[type="checkbox"]').change(function() {
var ischecked= jQuery(this).is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="buttontitle1"] p.description,.acf-field[data-name="buttontitle2"] p.description,.acf-field[data-name="buttontitle3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="buttontitle1"] p.description,.acf-field[data-name="buttontitle2"] p.description,.acf-field[data-name="buttontitle3"] p.description').show();
}
});

jQuery('.acf-field[data-name="shDisable_images"] input[type="checkbox"]').change(function() {
var ischecked= jQuery(this).is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contentimage1"] p.description,.acf-field[data-name="contentimage2"] p.description,.acf-field[data-name="contentimage3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contentimage1"] p.description,.acf-field[data-name="contentimage2"] p.description,.acf-field[data-name="contentimage3"] p.description').show();
}
});

jQuery('.acf-field[data-name="shDisable_icons"] input[type="checkbox"]').change(function() {
var ischecked= jQuery(this).is(':checked');
if(!ischecked){
		jQuery('.acf-field[data-name="contenticon1"] p.description,.acf-field[data-name="contenticon2"] p.description,.acf-field[data-name="contenticon3"] p.description').hide();
} else {
		jQuery('.acf-field[data-name="contenticon1"] p.description,.acf-field[data-name="contenticon2"] p.description,.acf-field[data-name="contenticon3"] p.description').show();
}
});





});
