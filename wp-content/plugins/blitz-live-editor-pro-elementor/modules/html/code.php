<?php
// Silence is golden.
$keyValue[] = 'settings';
if(isset($value['html'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'html';
	$k = implode('.',$keyValuen);
	$preValue = $x['settings']['html'];
	$key = $this->shortce($preValue);
	if($key[0] != ''){
		$update = 0;
		$fvalue = $value['html'];
		$fvalue = str_replace('<p class="description"><small>','',$fvalue);
		$fvalue = str_replace('</small></p>','',$fvalue);
		if($key[2] =='1'){
			update_option('options_'.$key[0],$fvalue);
			
			if(is_array($key[1])){
				$shortCode = '[shortce ';
				foreach($key[1] as $skey=>$svalue){
					$shortCode .= $skey.'='.$svalue.' ';
				}
				$shortCode .= ']';
				$this->setArray($obj, $k , $shortCode);
			}
		}
		if($key[2] =='2'){
			$rowID  = explode('_',$key[1]['item']); //item ID
			$row_ID  = $rowID[1]; //item ID
			$postIdS  = $rowID[0]; //post ID
			update_post_meta( $postIdS, $key[0],$fvalue);
			if(is_array($key[1])){
				$shortCode = '[shortcontent ';
				foreach($key[1] as $skey=>$svalue){
					$shortCode .= $skey.'='.$svalue.' ';
				}
				$shortCode .= ']';
				$this->setArray($obj, $k , $shortCode);
			}
		}
		
	}else{
		$this->setArray($obj, $k , $value['html']);
	}
}

if(isset($value['bg'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = '_background_image';
	$keyValuen[] = 'url';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['bg']);
}

if(isset($value['bgid'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = '_background_image';
	$keyValuen[] = 'id';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['bgid']);
}
