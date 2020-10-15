<?php 
class ThemeSettings{

	protected 	$elements = array();
	protected 	$ceshortcodeidentifier = '__cefield__';
	protected 	$ceshortcodeidentifiercust = '__cefield__';
	protected 	$currenttheme ;

		
		
	/**
	* Get option
	*/
	public function bz_ce_get_option($opt)
	{
		$theme = $this->currenttheme;
		return $theme::get_option($opt);
	}
	
			
		
	/**
	* Intitialization
	*/
	public function init($themeName)
	{		 
		add_filter('acf/settings/path',  	array($this,'bz_content_acf_settings_path'));
		add_filter('acf/settings/dir', 		array($this,'bz_content_acf_settings_dir'));
		add_action('admin_footer',			array($this,'apply_style_if_super_admin')); //Apply Style if Super Admin
		
		if ( is_multisite() && is_plugin_active_for_network('blitz-content-editor-pro/blitz-content-editor-pro.php') ) {
			$blog_id = get_current_blog_id(); //current blog ID
			if( $blog_id == 1) {
				add_shortcode( $this->ceshortcodeidentifier , array($this,'bzgetcontentSh') );
			} else {
				add_shortcode( $this->ceshortcodeidentifiercust , array($this,'bzgetcontentShCust') );
			}
		} else {
			add_shortcode( $this->ceshortcodeidentifier , array($this,'bzgetcontentSh') );
		}
		
		//added 19dec
		$this->ceadminSettings 		=  new ceadminSettings();
		$enableACF = $this->ceadminSettings->acfmenuEnabled();
		
		
		//changed 19 dec
		if ($enableACF == '1') {
			add_filter('acf/settings/show_admin', '__return_true');
		} else {
			add_filter('acf/settings/show_admin', '__return_false');
		}

	}
	
			
		
	/** 
	* Shortcode method for Developers of single site
	*/
	public function bzgetcontentSh( $atts, $content = "",$postID) {		
		
		$type = $atts['type'];  //field type
		$rowID  = explode('_',$atts['item']); //item ID
		$row_ID  = $rowID[1]; //item ID
		$postId  = $rowID[0]; //post ID
		
		$pageId = $atts['page'];  //pageId

		$isImg = 1;
		if(isset($atts['isImg'])){
			$isImg = $atts['isImg'];
		}
		
		$type1 = explode('_',$type);
		$fieldType = $type1[0];
		$fieldBlockID = $type1[1];

		
		$pageID = get_the_ID();
		$classes = get_body_class();
		$innerFlexContent = 'create_site_layout_'.$pageId.'_create_layout'; //inner layout Key
		$blocks = get_post_meta( $postId, $innerFlexContent , true );
		
		//~ $blocks = get_field($innerFlexContent,$postId); //layout Arr
		
		if(is_array($blocks)) {
			$innerblocks = $blocks;
		} else {
			$innerblocks = unserialize($blocks);
		}
					
		$keys_of_duplicated = array();
		$array_keys = array();
		
		$shortcodeData='';
				
		if(!empty($innerblocks)) {
			foreach($innerblocks as $key => $value) {
				$array_keys = array_keys($innerblocks, $value);

				if(count($array_keys) > 1) {
					foreach($array_keys as $key_registered) {
						if(!in_array($key_registered,  $keys_of_duplicated)) {
							 $keys_of_duplicated[] = $key_registered;
						}
					}
				}
				
				}
		

		
		if($fieldType == 'richtext') {
			
			$repeaterField = 'add_rich_text_area';
			$array_keys = array_keys($innerblocks, 'richtextBlock');
			count($array_keys);
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('richtextBlock', $innerblocks);
			}
					
		} else if ($fieldType == 'paragraph') {
			
			$repeaterField = 'add_paragraph';
			$array_keys = array_keys($innerblocks, 'spintaxBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('spintaxBlock', $innerblocks);
			}
			
		}  else if ($fieldType == 'stattitle') {
			
			$repeaterField = 'add_stat';
			$array_keys = array_keys($innerblocks, 'statBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('statBlock', $innerblocks);
			}
			
		}  else if ($fieldType == 'statnum') {
			
			$repeaterField = 'add_stat';
			$array_keys = array_keys($innerblocks, 'statBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('statBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'medimage') {
			
			$repeaterField = 'add_media';
			$array_keys = array_keys($innerblocks, 'mediaBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('mediaBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
		
		} else if ($fieldType == 'contenttext') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'buttontitle1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'buttontitle2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'buttontitle3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'testiname') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'testijob') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'testiicon') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}

		} else if ($fieldType == 'testiimage') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}

		} else if ($fieldType == 'testicontent') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}
		}
				
		$sh_key = 'create_site_layout_'.$pageId.'_create_layout_'.$key.'_'.$repeaterField.'_'.$row_ID.'_'.$fieldType;		
		
		if($fieldType == 'medimage' || $fieldType == 'contentimage' || $fieldType == 'contentimage1' || $fieldType == 'contentimage2' || $fieldType == 'contentimage3' || $fieldType == 'testiimage') {
		
			$imageID = get_field($sh_key, $postId);
			
			if(is_array($imageID)){
				$imageID1 = $imageID['ID'];
			}else{
				$imageID1 =$imageID;
			}
			
			$widgetClasses = array('Widget_Cep_Counter','Widget_Cep_Eael_Dual_Color_Header','Widget_Cep_Flip_Box','Widget_Cep_Icon_Box','Widget_Cep_Icon','Widget_Cep_Image_Box','Widget_Cep_Image','Widget_Cep_Eael_Info_Box','Widget_Cep_Eael_Progress_Bar','Widget_Cep_Progress','Widget_Cep_Testimonial');
			
			$backtrace = debug_backtrace(); // to check from where the class is called
			
			
			//~ $trace = debug_backtrace();
			//~ $caller = $trace[4];
			
			//~ print_r($caller);
			//~ echo "Called by {$caller['function']}";

			if(isset($backtrace[4]['class']) && in_array($backtrace[4]['class'], $widgetClasses)){
				
				$shortcodeData = $imageID1;
				
			//~ } else if( in_array('fl-builder', $classes) ) {    //when beaver widget is activated
				
				//~ $shortcodeData = wp_get_attachment_url($imageID1);
				//~ $shortcodeData = $imageID1;
				
			}	else{
				
				$shortcodeData = '<img src="'.wp_get_attachment_url($imageID1).'" />';
			}
		
		} else if ( $fieldType == 'contenticon' || $fieldType == 'contenticon1' || $fieldType == 'contenticon2' || $fieldType == 'contenticon3' || $fieldType == 'testiicon' ) {  //font Awesome Icon
			
			$shortcodeData = get_post_meta( $postId, $sh_key , true );	
			
		} else if ( $fieldType == 'paragraph' ) {  //spintax
			
			$spinTax = get_field($sh_key, $postId);
			$shortcodeData =  $this->spintaxProcess($spinTax);
			
		} else {
			$shortcodeData = get_field($sh_key, $postId);
		}
		
	} else {
		$shortcodeData = $shortcodeData;
	}
	return $shortcodeData;
		
	}
	
	
	
	/**
	* Shortcode method for Site owners
	*/
	public function bzgetcontentShCust( $atts, $content = "") {		
		
		$blog_id = get_current_blog_id(); //current blog ID

		$type = $atts['type'];  //field type
		$row_ID  = $atts['item']; //item ID
		$pageId = $atts['page'];  //pageId
		$isImg = 1;
		if(isset($atts['isImg'])){
			$isImg = $atts['isImg'];
		}
		
		$type1 = explode('_',$type);
		$fieldType = $type1[0];
		$fieldBlockID = $type1[1];

		
		switch_to_blog($blog_id);
		$pageID = get_the_ID();
		$classes = get_body_class();
		
		$innerFlexContent = 'create_site_layout_'.$pageId.'_create_layout'; //inner layout Key
		
		$blocks = get_option('options_'.$innerFlexContent); //layout Arr
		
		if(is_array($blocks)) {
			$innerblocks = $blocks;
		} else {
			$innerblocks = unserialize($blocks);
		}
					
		$keys_of_duplicated = array();
		$array_keys = array();
		
		$shortcodeData='';
				
		if(!empty($innerblocks)) {
			foreach($innerblocks as $key => $value) {
				$array_keys = array_keys($innerblocks, $value);

				if(count($array_keys) > 1) {
					foreach($array_keys as $key_registered) {
						if(!in_array($key_registered,  $keys_of_duplicated)) {
							 $keys_of_duplicated[] = $key_registered;
						}
					}
				}
				
				}
		

		
		if($fieldType == 'richtext') {
			
			$repeaterField = 'add_rich_text_area';
			$array_keys = array_keys($innerblocks, 'richtextBlock');
			count($array_keys);
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('richtextBlock', $innerblocks);
			}
					
		} else if ($fieldType == 'paragraph') {
			
			$repeaterField = 'add_paragraph';
			$array_keys = array_keys($innerblocks, 'spintaxBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('spintaxBlock', $innerblocks);
			}
			
		}  else if ($fieldType == 'stattitle') {
			
			$repeaterField = 'add_stat';
			$array_keys = array_keys($innerblocks, 'statBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('statBlock', $innerblocks);
			}
			
		}  else if ($fieldType == 'statnum') {
			
			$repeaterField = 'add_stat';
			$array_keys = array_keys($innerblocks, 'statBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('statBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'medimage') {
			
			$repeaterField = 'add_media';
			$array_keys = array_keys($innerblocks, 'mediaBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('mediaBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenticon3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
		
		} else if ($fieldType == 'contenttext') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contenttitle3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'buttontitle1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'buttontitle2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'buttontitle3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage1') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage2') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'contentimage3') {
			
			$repeaterField = 'add_content';
			$array_keys = array_keys($innerblocks, 'contentBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('contentBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'testiname') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'testijob') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}
			
		} else if ($fieldType == 'testiicon') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}

		} else if ($fieldType == 'testiimage') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}

		} else if ($fieldType == 'testicontent') {
			
			$repeaterField = 'add_testimonial';
			$array_keys = array_keys($innerblocks, 'testimonialBlock');
			if(count($array_keys) > 1) {
	   			$key = $keys_of_duplicated[$fieldBlockID];
			} else {
				$key = array_search('testimonialBlock', $innerblocks);
			}
		}
				
		$sh_key = 'create_site_layout_'.$pageId.'_create_layout_'.$key.'_'.$repeaterField.'_'.$row_ID.'_'.$fieldType;		
		
		if($fieldType == 'medimage' || $fieldType == 'contentimage' || $fieldType == 'contentimage1' || $fieldType == 'contentimage2' || $fieldType == 'contentimage3' || $fieldType == 'testiimage') {
		
			$imageID = get_option('options_'.$sh_key);
			
			if(is_array($imageID)){
				$imageID1 = $imageID['ID'];
			}else{
				$imageID1 =$imageID;
			}
			
			$widgetClasses = array('Widget_Cep_Counter','Widget_Cep_Eael_Dual_Color_Header','Widget_Cep_Flip_Box','Widget_Cep_Icon_Box','Widget_Cep_Icon','Widget_Cep_Image_Box','Widget_Cep_Image','Widget_Cep_Eael_Info_Box','Widget_Cep_Eael_Progress_Bar','Widget_Cep_Progress','Widget_Cep_Testimonial');

			$backtrace = debug_backtrace(); // to check from where the class is called
			if(isset($backtrace[4]['class']) && in_array($backtrace[4]['class'], $widgetClasses)){
				$shortcodeData = $imageID1;
			}else{
				$shortcodeData = '<img src="'.wp_get_attachment_url($imageID1).'" />';
			}
		
		} else if ( $fieldType == 'contenticon' || $fieldType == 'contenticon1' || $fieldType == 'contenticon2' || $fieldType == 'contenticon3' || $fieldType == 'testiicon' ) {  //font Awesome Icon
			
			$shortcodeData = get_option('options_'.$sh_key);			
			
		} else if ( $fieldType == 'paragraph' ) {  //spintax
			
			$spinTax = get_option('options_'.$sh_key);
			$shortcodeData =  $this->spintaxProcess($spinTax);
			
		} else {
			$shortcodeData = get_option('options_'.$sh_key);
		}
		
	} else {
		$shortcodeData = $shortcodeData;
	}
	return $shortcodeData;
		restore_current_blog();
		
	}


			
		
	/**
	* Get ACF Field data of Desired Layout
	*/
	public function getData($postID) {
		
		switch_to_blog(1);	
		$fieldData = get_post_meta( $postID);
		if ( ! empty( $fieldData ) ) {
			$fieldDataArr = $fieldData;
		}
		restore_current_blog();
		
		return $fieldDataArr;
	}
	
				
		
	/**
	* Get Layout ID
	*/
	public function getLayoutID($cat)
	{		
			global $blog_id;
			if( $blog_id != 1) {
			switch_to_blog(1);
			$args = array(
					'post_type' => 'layouts',
					'posts_per_page' => '1',
					'orderby' => 'date',
					'order' => 'DESC',
					'meta_key' => 'layout_category',
					'post_status'  => 'publish',
					'meta_query'=> array( 
						'key' => 'layout_category', 
						'value' => $cat ,
						'compare' => 'LIKE'
					)
			);
			$layouts = get_posts( $args );
				
			foreach($layouts as $layout) { 
				$layoutID = $layout->ID;
			}
			restore_current_blog();	
			}
			if (isset($layoutID)) {
			return $layoutID;
			}
	}
	
	
		
	/**
	* ACF init method initialization
	*/
	public function bz_content_acf_settings_path( $path ) {		
	    $path = plugin_dir_path(__FILE__) . '/acf/';
	    return $path;		    
	}

	
		
	/**
	* ACF init method initialization
	*/
	public function bz_content_acf_settings_dir( $dir ) {
	    $dir = plugin_dir_url(__FILE__) . '/acf/';
	    return $dir;		    
	}
	
		
		
	/**
	* Spintax shortcode process
	*/
	public function spintaxProcess($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'spintaxReplace'),
            $text
        );
    }
    
    		
		
	/**
	* Spintax shortcode replace text
	*/
    public function spintaxReplace($text)
    {
        $text = $this->spintaxProcess($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
    
    
        		
	/**
	* Loads style for options of site owner end
	*/
    public function loadOptionsStyles($blogid,$type){
		
		$styles='';
		$scripts='';
		$styles .= "\n".'<style type="text/css">';	
		if($type == 'shortcode') {
			$styles .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-fields .acf-field p.description{ display:block; }';
		} else {
			$styles .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-actions .acf-button, .toplevel_page_content-pro-settings .acf-field-flexible-content .acf-fc-layout-controls { visibility:visible !important;}';
			$styles .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-repeater .acf-row-handle a.acf-icon.-plus.small.acf-js-tooltip {display:block !important;}';
			$styles .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-repeater .acf-row-handle a.acf-icon.-minus.small.acf-js-tooltip {display:block !important;}';
			$styles .= '.toplevel_page_content-pro-settings .acf-flexible-content .layout .acf-fc-layout-controls .acf-icon.-plus {display:block !important;}';
			$styles .= '.toplevel_page_content-pro-settings .acf-flexible-content .layout .acf-fc-layout-controls .acf-icon.-minus {display:block !important;}';
		}
		$styles .= '</style>';
		
		$scripts .= '<script type="text/javascript">
		
			jQuery(".toplevel_page_content-pro-settings .acf-field input[type=checkbox]").each(function(counter, obj) {
				
				jQuery(this).attr("checked", true);

			});

			
		</script>';
		
        add_action('admin_footer',
                   function() use ( $styles ) {
                       $this->applyOptionsStyles( $styles ); });
                       
        add_action('admin_footer',
                   function() use ( $scripts ) {
                       $this->applyOptionsStyles( $scripts ); });
                       
	}
    
    
        		
	/**
	* Loads style for options of site owner end
	*/
    public function loadOptionsStyles1($blogid,$type){
		
		$scripts='';
		if($type == 'shortcode') {
			$scripts .= '<script type="text/javascript">
				jQuery(".toplevel_page_content-pro-settings .acf-field input[type=checkbox]").each(function(counter, obj) {
					jQuery(this).attr("checked", false);
				});
			</script>';
		}

        add_action('admin_footer',
                   function() use ( $scripts ) {
                       $this->applyOptionsStyles( $scripts ); });
                       
	}
	
	
	/**
	* Apply style for options of site owner end
	*/
	public function applyOptionsStyles( $args ) {
		
		if(!is_super_admin()){
			echo $args;
		}
	}
	
	
	/**
	* Apply style for options for both site owner end admin
	*/
	public function applyLayoutStyles( $args ) {
		echo $args;
	}
	
	
	
	/**
	 * Apply style for client page
	*/
	public function apply_style_if_super_admin() {
		
		if(is_super_admin()){
			
			$styles1='';
			$styles1 .= "\n".'<style type="text/css">';	
			$styles1 .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-fields .acf-field p.description{ display:block; }';
			$styles1 .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-actions .acf-button, .toplevel_page_content-pro-settings .acf-field-flexible-content .acf-fc-layout-controls { visibility:visible !important;}';
			$styles1 .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-repeater .acf-row-handle a.acf-icon.-plus.small.acf-js-tooltip {display:block !important;}';
			$styles1 .= '.toplevel_page_content-pro-settings .acf-field-flexible-content .acf-repeater .acf-row-handle a.acf-icon.-minus.small.acf-js-tooltip {display:block !important;}';
			$styles1 .= '.toplevel_page_content-pro-settings .acf-flexible-content .layout .acf-fc-layout-controls .acf-icon.-plus {display:block !important;}';
			$styles1 .= '.toplevel_page_content-pro-settings .acf-flexible-content .layout .acf-fc-layout-controls .acf-icon.-minus {display:block !important;}';
			$styles1 .= '</style>';
		}
		
		echo $styles1;
		
	}
	

}
