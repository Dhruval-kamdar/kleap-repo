<?php
/** 
 * Helper functions to be used by eventon or its addons
 * front-end only
 *
 * @version 0.7
 * @updated  2.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class evo_helper{	
	public $options2;
	public function __construct(){
		$this->opt2 = get_option('evcal_options_evcal_2');
	}
	 

	// Create posts 
		function create_posts($args){
			if(!empty($args) && is_array($args)){
				$valid_type = (function_exists('post_type_exists') &&  post_type_exists($args['post_type']));

				if(!$valid_type)
					return false;

				$__post_content = !empty($_POST['post_content'])? $_POST['post_content']: 
					(!empty($args['post_content'])?$args['post_content']:false);
				$__post_content = ($__post_content)?
			        	convert_chars(stripslashes($__post_content)): '';

			    // author id
			    $current_user = wp_get_current_user();
		        $author_id =  (($current_user instanceof WP_User)) ? $current_user->ID : ( !empty($args['author_id'])? $args['author_id']:1);

			    $new_post = array(
		            'post_title'   => wp_strip_all_tags($args['post_title']),
		            'post_content' => $__post_content,
		            'post_status'  => $args['post_status'],
		            'post_type'    => $args['post_type'],
		            'post_name'    => sanitize_title($args['post_title']),
		            'post_author'  => $author_id,
		        );
			    return wp_insert_post($new_post);
			}else{
				return false;
			}
		}

		function create_custom_meta($post_id, $field, $value){
			add_post_meta($post_id, $field, $value);
		}

	// Eventon Settings helper
		function get_html($type, $args){
			switch($type){
				case 'email_preview':
					ob_start();
					echo '<div class="evo_email_preview"><p>Headers: '.$args['headers'][0].'</p>';
					echo '<p>To: '.$args['to'].'</p>';
					echo '<p>Subject: '.$args['subject'].'</p>';
					echo '<div class="evo_email_preview_body">'.$args['message'].'</div></div>';
					return ob_get_clean();
				break;
			}
		}

	// ADMIN & Frontend Helper
	// @updated 2.5.2
		public function send_email($args){
			$defaults = array(
				'html'=>'yes',
				'preview'=>'no',
				'to'=>'',
				'from'=>'',
				'from_name'=>'','from_email'=>'',
				'header'=>'',
				'subject'=>'',
				'message'=>'',
				'type'=>'',// bcc
				'attachments'=> array(),
				'return_details'=>false,
				'reply-to' => ''
			);
			$args = is_array($args)? array_merge($defaults, $args): $defaults;

			if($args['html']=='yes'){
				add_filter( 'wp_mail_content_type',array($this,'set_html_content_type'));
			}

			if(!empty($args['header'])){
				$headers = $args['header'];
			}else{
				$headers = array();
				if(empty($args['from_email'])){
					$headers[] = 'From: '.$args['from'];
				}else{
					$headers[] = 'From: '.(!empty($args['from_name'])? $args['from_name']:'') .' <'.
						$args['from_email'] . '>';
				}
			}	


			// add reply to into headers // @2.8.6
			if(!empty($args['reply-to']) && isset($args['reply-to'])){
				$headers[] = 'Reply-To: '. $args['reply-to'];
			}

			$return = '';	

			if($args['preview']=='yes'){
				$return = array(
					'to'=>$args['to'],
					'subject'=>$args['subject'],
					'message'=>$args['message'],
					'headers'=>$headers
				);
			// bcc version of things
			}else if(!empty($args['type']) && $args['type']=='bcc' ){
				if(is_array($args['to']) ){
					foreach($args['to'] as $EM){
						$headers[] = "Bcc: ".$EM;
					}
				}else{
					$headers[] = "Bcc: ".$args['to'];
				}

				$return = wp_mail($args['from'], $args['subject'], $args['message'], $headers, $args['attachments']);	
			}else{
				$return = wp_mail($args['to'], $args['subject'], $args['message'], $headers, $args['attachments']);
			}

			if($args['html']=='yes'){
				remove_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
			} 

			if($args['return_details']){
				// get the errors
				$ts_mail_errors = array();
				if(!$return){
					global $ts_mail_errors;
					global $phpmailer;

					if (!isset($ts_mail_errors)) $ts_mail_errors = array();

					if (isset($phpmailer)) {
						$ts_mail_errors[] = $phpmailer->ErrorInfo;
					}
				}
				return array('result'=>$return, 'error'=>$ts_mail_errors);
			}else{
				return $return;
			}
			
		}
		function set_html_content_type() {
			return 'text/html';
		}
		function set_charset_type() {
			return 'utf8';
		}

		// GET email body with eventon header and footer for email included
		public function get_email_body_content($message='', $outside = true){
			global $eventon;

			ob_start();
			if($outside) echo $eventon->get_email_part('header');
			echo !empty($message)? $message:'';
			if($outside) echo $eventon->get_email_part('footer');
			return ob_get_clean();
		}

	// YES NO Button
		function html_yesnobtn($args=''){
			$defaults = array(
				'id'=>'',
				'var'=>'',
				'no'=>'',
				'default'=>'',
				'input'=>false,
				'inputAttr'=>'',
				'label'=>'',
				'guide'=>'',
				'guide_position'=>'',
				'abs'=>'no',// absolute positioning of the button
				'attr'=>'', // array
				'afterstatement'=>'',
				'lang'=>'L1'
			);
			
			$args = shortcode_atts($defaults, $args);

			$_attr = $no = '';

			if(!empty($args['var'])){
				$no = ($args['var']	=='yes')? 
					 null: 
					 ( (!empty($args['default']) && $args['default']=='yes')? null:'NO');
			}else{
				$no = (!empty($args['default']) && $args['default']=='yes')? null:'NO';
			}

			if(!empty($args['attr'])){
				foreach($args['attr'] as $at=>$av){
					$_attr .= $at.'="'.$av.'" ';
				}
			}

			// input field
			$input = '';
			if($args['input']){
				$input_value = (!empty($args['var']))? 
					$args['var']: (!empty($args['default'])? $args['default']:'no');

				// Attribut values for input field
				$inputAttr = '';
				if(!empty($args['inputAttr'])){
					foreach($args['inputAttr'] as $at=>$av){
						$inputAttr .= $at.'="'.$av.'" ';
					}
				}

				// input field
				$input = "<input {$inputAttr} type='hidden' name='{$args['id']}' value='{$input_value}'/>";
			}

			$guide = '';
			if(!empty($args['guide'])){
				$guide = $this->tooltips($args['guide'], $args['guide_position']);
			}

			$label = '';
			if(!empty($args['label']))
				$label = "<label class='ajde_yn_btn_label' for='{$args['id']}'>{$args['label']}{$guide}</label>";

			$text_NO = eventon_get_custom_language($this->opt2, 'evo_lang_no', 'NO', $args['lang']);
			$text_YES = eventon_get_custom_language($this->opt2, 'evo_lang_yes', 'YES', $args['lang']);

			return '<span id="'.$args['id'].'" class="ajde_yn_btn '.($no? 'NO':null).''.(($args['abs']=='yes')? ' absolute':null).'" '.$_attr.' data-afterstatement="'.$args['afterstatement'].'"><span class="btn_inner" style=""><em class="no">'.$text_NO.'</em><span class="catchHandle"></span><em class="yes">'.$text_YES.'</em></span></span>'.$input.$label;
		}

	// tool tips
		function tooltips($content, $position='', $handleClass=false, $echo = false){
			// tool tip position
				if(!empty($position)){
					$L = ' L';
					
					if($position=='UL')
						$L = ' UL';
					if($position=='U')
						$L = ' U';
				}else{
					$L = null;
				}

			$output = "<span class='ajdeToolTip{$L} fa ". ($handleClass? 'handle':'')."' data-handle='{$handleClass}'><em>{$content}</em></span>";

			if(!$echo)
				return $output;			
			
			echo $output;
		}
		function echo_tooltips($content, $position=''){
			$this->tooltips($content, $position='',true);
		}

	// template locator
	// pass: paths array, file name, default template with full path and file
		function template_locator($paths, $file, $template){
			foreach($paths as $path){
				if(file_exists($path.$file) ){	
					$template = $path.$file;
					break;
				}
			}				
			if ( ! $template ) { 
				$template = AJDE_EVCAL_PATH . '/templates/' . $file;
			}

			return $template;
		}	
	// Humanly readable time
	// @+ 2.6.13
		function get_human_time($time){

			$output = '';
			$minFix = $hourFix = $dayFix = 0;

			$day = $time/(60*60*24); // in day
			$dayFix = floor($day);
			$dayPen = $day - $dayFix;
			if($dayPen > 0)
			{
				$hour = $dayPen*(24); // in hour (1 day = 24 hour)
				$hourFix = floor($hour);
				$hourPen = $hour - $hourFix;
				if($hourPen > 0)
				{
					$min = $hourPen*(60); // in hour (1 hour = 60 min)
					$minFix = floor($min);
					$minPen = $min - $minFix;
					if($minPen > 0)
					{
						$sec = $minPen*(60); // in sec (1 min = 60 sec)
						$secFix = floor($sec);
					}
				}
			}
			$str = "";
			if($dayFix > 0)
				$str.= $dayFix." day ";
			if($hourFix > 0)
				$str.= $hourFix." hour ";
			if($minFix > 0)
				$str.= $minFix." min ";
			//if($secFix > 0)	$str.= $secFix." sec ";
			return $str;
		}

	// Timezones
		function get_timezone_array() {
			$zones_array = array(
				"Pacific/Midway"                 => "(GMT-11:00) Midway Island, Samoa ",
				"Pacific/Pago_Pago"              => "(GMT-11:00) Pago Pago ",
				"Pacific/Honolulu"               => "(GMT-10:00) Hawaii ",
				"America/Anchorage"              => "(GMT-8:00) Alaska ",
				"America/Vancouver"              => "(GMT-7:00) Vancouver ",
				"America/Los_Angeles"            => "(GMT-7:00) Pacific Time (US and Canada) ",
				"America/Tijuana"                => "(GMT-7:00) Tijuana ",
				"America/Phoenix"                => "(GMT-7:00) Arizona ",
				"America/Edmonton"               => "(GMT-6:00) Edmonton ",
				"America/Denver"                 => "(GMT-6:00) Mountain Time (US and Canada) ",
				"America/Mazatlan"               => "(GMT-6:00) Mazatlan ",
				"America/Regina"                 => "(GMT-6:00) Saskatchewan ",
				"America/Guatemala"              => "(GMT-6:00) Guatemala ",
				"America/El_Salvador"            => "(GMT-6:00) El Salvador ",
				"America/Managua"                => "(GMT-6:00) Managua ",
				"America/Costa_Rica"             => "(GMT-6:00) Costa Rica ",
				"America/Tegucigalpa"            => "(GMT-6:00) Tegucigalpa ",
				"America/Winnipeg"               => "(GMT-5:00) Winnipeg ",
				"America/Chicago"                => "(GMT-5:00) Central Time (US and Canada) ",
				"America/Mexico_City"            => "(GMT-5:00) Mexico City ",
				"America/Panama"                 => "(GMT-5:00) Panama ",
				"America/Bogota"                 => "(GMT-5:00) Bogota ",
				"America/Lima"                   => "(GMT-5:00) Lima ",
				"America/Caracas"                => "(GMT-4:30) Caracas ",
				"America/Montreal"               => "(GMT-4:00) Montreal ",
				"America/New_York"               => "(GMT-4:00) Eastern Time (US and Canada) ",
				"America/Indianapolis"           => "(GMT-4:00) Indiana (East) ",
				"America/Puerto_Rico"            => "(GMT-4:00) Puerto Rico ",
				"America/Santiago"               => "(GMT-4:00) Santiago ",
				"America/Halifax"                => "(GMT-3:00) Halifax ",
				"America/Montevideo"             => "(GMT-3:00) Montevideo ",
				"America/Araguaina"              => "(GMT-3:00) Brasilia ",
				"America/Argentina/Buenos_Aires" => "(GMT-3:00) Buenos Aires, Georgetown ",
				"America/Sao_Paulo"              => "(GMT-3:00) Sao Paulo ",
				"Canada/Atlantic"                => "(GMT-3:00) Atlantic Time (Canada) ",
				"America/St_Johns"               => "(GMT-2:30) Newfoundland and Labrador ",
				"America/Godthab"                => "(GMT-2:00) Greenland ",
				"Atlantic/Cape_Verde"            => "(GMT-1:00) Cape Verde Islands ",
				"Atlantic/Azores"                => "(GMT+0:00) Azores ",
				"UTC"                            => "(GMT+0:00) Universal Time UTC ",
				"Etc/Greenwich"                  => "(GMT+0:00) Greenwich Mean Time ",
				"Atlantic/Reykjavik"             => "(GMT+0:00) Reykjavik ",
				"Africa/Nouakchott"              => "(GMT+0:00) Nouakchott ",
				"Europe/Dublin"                  => "(GMT+1:00) Dublin ",
				"Europe/London"                  => "(GMT+1:00) London ",
				"Europe/Lisbon"                  => "(GMT+1:00) Lisbon ",
				"Africa/Casablanca"              => "(GMT+1:00) Casablanca ",
				"Africa/Bangui"                  => "(GMT+1:00) West Central Africa ",
				"Africa/Algiers"                 => "(GMT+1:00) Algiers ",
				"Africa/Tunis"                   => "(GMT+1:00) Tunis ",
				"Europe/Belgrade"                => "(GMT+2:00) Belgrade, Bratislava, Ljubljana ",
				"CET"                            => "(GMT+2:00) Sarajevo, Skopje, Zagreb ",
				"Europe/Oslo"                    => "(GMT+2:00) Oslo ",
				"Europe/Copenhagen"              => "(GMT+2:00) Copenhagen ",
				"Europe/Brussels"                => "(GMT+2:00) Brussels ",
				"Europe/Berlin"                  => "(GMT+2:00) Amsterdam, Berlin, Rome, Stockholm, Vienna ",
				"Europe/Amsterdam"               => "(GMT+2:00) Amsterdam ",
				"Europe/Rome"                    => "(GMT+2:00) Rome ",
				"Europe/Stockholm"               => "(GMT+2:00) Stockholm ",
				"Europe/Vienna"                  => "(GMT+2:00) Vienna ",
				"Europe/Luxembourg"              => "(GMT+2:00) Luxembourg ",
				"Europe/Paris"                   => "(GMT+2:00) Paris ",
				"Europe/Zurich"                  => "(GMT+2:00) Zurich ",
				"Europe/Madrid"                  => "(GMT+2:00) Madrid ",
				"Africa/Harare"                  => "(GMT+2:00) Harare, Pretoria ",
				"Europe/Warsaw"                  => "(GMT+2:00) Warsaw ",
				"Europe/Prague"                  => "(GMT+2:00) Prague Bratislava ",
				"Europe/Budapest"                => "(GMT+2:00) Budapest ",
				"Africa/Tripoli"                 => "(GMT+2:00) Tripoli ",
				"Africa/Cairo"                   => "(GMT+2:00) Cairo ",
				"Africa/Johannesburg"            => "(GMT+2:00) Johannesburg ",
				"Europe/Helsinki"                => "(GMT+3:00) Helsinki ",
				"Africa/Nairobi"                 => "(GMT+3:00) Nairobi ",
				"Europe/Sofia"                   => "(GMT+3:00) Sofia ",
				"Europe/Istanbul"                => "(GMT+3:00) Istanbul ",
				"Europe/Athens"                  => "(GMT+3:00) Athens ",
				"Europe/Bucharest"               => "(GMT+3:00) Bucharest ",
				"Asia/Nicosia"                   => "(GMT+3:00) Nicosia ",
				"Asia/Beirut"                    => "(GMT+3:00) Beirut ",
				"Asia/Damascus"                  => "(GMT+3:00) Damascus ",
				"Asia/Jerusalem"                 => "(GMT+3:00) Jerusalem ",
				"Asia/Amman"                     => "(GMT+3:00) Amman ",
				"Europe/Moscow"                  => "(GMT+3:00) Moscow ",
				"Asia/Baghdad"                   => "(GMT+3:00) Baghdad ",
				"Asia/Kuwait"                    => "(GMT+3:00) Kuwait ",
				"Asia/Riyadh"                    => "(GMT+3:00) Riyadh ",
				"Asia/Bahrain"                   => "(GMT+3:00) Bahrain ",
				"Asia/Qatar"                     => "(GMT+3:00) Qatar ",
				"Asia/Aden"                      => "(GMT+3:00) Aden ",
				"Africa/Khartoum"                => "(GMT+3:00) Khartoum ",
				"Africa/Djibouti"                => "(GMT+3:00) Djibouti ",
				"Africa/Mogadishu"               => "(GMT+3:00) Mogadishu ",
				"Europe/Kiev"                    => "(GMT+3:00) Kiev ",
				"Asia/Dubai"                     => "(GMT+4:00) Dubai ",
				"Asia/Muscat"                    => "(GMT+4:00) Muscat ",
				"Asia/Tehran"                    => "(GMT+4:30) Tehran ",
				"Asia/Kabul"                     => "(GMT+4:30) Kabul ",
				"Asia/Baku"                      => "(GMT+5:00) Baku, Tbilisi, Yerevan ",
				"Asia/Yekaterinburg"             => "(GMT+5:00) Yekaterinburg ",
				"Asia/Tashkent"                  => "(GMT+5:00) Islamabad, Karachi, Tashkent ",
				"Asia/Calcutta"                  => "(GMT+5:30) India ",
				"Asia/Kolkata"                   => "(GMT+5:30) Mumbai, Kolkata, New Delhi ",
				"Asia/Kathmandu"                 => "(GMT+5:45) Kathmandu ",
				"Asia/Novosibirsk"               => "(GMT+6:00) Novosibirsk ",
				"Asia/Almaty"                    => "(GMT+6:00) Almaty ",
				"Asia/Dacca"                     => "(GMT+6:00) Dacca ",
				"Asia/Dhaka"                     => "(GMT+6:00) Astana, Dhaka ",
				"Asia/Krasnoyarsk"               => "(GMT+7:00) Krasnoyarsk ",
				"Asia/Bangkok"                   => "(GMT+7:00) Bangkok ",
				"Asia/Saigon"                    => "(GMT+7:00) Vietnam ",
				"Asia/Jakarta"                   => "(GMT+7:00) Jakarta ",
				"Asia/Irkutsk"                   => "(GMT+8:00) Irkutsk, Ulaanbaatar ",
				"Asia/Shanghai"                  => "(GMT+8:00) Beijing, Shanghai ",
				"Asia/Hong_Kong"                 => "(GMT+8:00) Hong Kong ",
				"Asia/Taipei"                    => "(GMT+8:00) Taipei ",
				"Asia/Kuala_Lumpur"              => "(GMT+8:00) Kuala Lumpur ",
				"Asia/Singapore"                 => "(GMT+8:00) Singapore ",
				"Australia/Perth"                => "(GMT+8:00) Perth ",
				"Asia/Yakutsk"                   => "(GMT+9:00) Yakutsk ",
				"Asia/Seoul"                     => "(GMT+9:00) Seoul ",
				"Asia/Tokyo"                     => "(GMT+9:00) Osaka, Sapporo, Tokyo ",
				"Australia/Darwin"               => "(GMT+9:30) Darwin ",
				"Australia/Adelaide"             => "(GMT+9:30) Adelaide ",
				"Asia/Vladivostok"               => "(GMT+10:00) Vladivostok ",
				"Pacific/Port_Moresby"           => "(GMT+10:00) Guam, Port Moresby ",
				"Australia/Brisbane"             => "(GMT+10:00) Brisbane ",
				"Australia/Sydney"               => "(GMT+10:00) Canberra, Melbourne, Sydney ",
				"Australia/Hobart"               => "(GMT+10:00) Hobart ",
				"Asia/Magadan"                   => "(GMT+10:00) Magadan ",
				"SST"                            => "(GMT+11:00) Solomon Islands ",
				"Pacific/Noumea"                 => "(GMT+11:00) New Caledonia ",
				"Asia/Kamchatka"                 => "(GMT+12:00) Kamchatka ",
				"Pacific/Fiji"                   => "(GMT+12:00) Fiji Islands, Marshall Islands ",
				"Pacific/Auckland"               => "(GMT+12:00) Auckland, Wellington"
			);

			return $zones_array;
		}
}