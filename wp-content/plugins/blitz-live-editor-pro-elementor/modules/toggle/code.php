<?php
// Silence is golden.
$keyValue[] = 'settings';
if(isset($value['tab_title'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'tabs';
	$k = implode('.',$keyValuen);
	$fvalueM = $value['tab_title'];
	foreach($fvalueM as $skey=>$subValue){
		$k1 =  $k.'.'.$skey.'.tab_title';
		$preValue = $x['settings']['tabs'][$skey]['tab_title'];
		$key = $this->shortce($preValue);
		if($key[0] != ''){
			$update = 0;
			$fvalue = str_replace('<p class="description"><small>','',$subValue);
			$fvalue = str_replace('</small></p>','',$fvalue);
			if($key[2] =='1'){
				update_option('options_'.$key[0],$fvalue);
				
				if(is_array($key[1])){
					$shortCode = '[shortce ';
					foreach($key[1] as $skey=>$svalue){
						$shortCode .= $skey.'='.$svalue.' ';
					}
					$shortCode .= ']';
					$this->setArray($obj, $k1 , $shortCode);
				}
			}
			if($key[2] =='2'){
				$rowID  = explode('_',$key[1]['item']); //item ID
				$row_ID  = $rowID[1]; //item ID
				$postIdS  = $rowID[0]; //post ID
				update_post_meta( $postIdS, $key[0],$subValue);
				if(is_array($key[1])){
					$shortCode = '[shortcontent ';
					foreach($key[1] as $skey=>$svalue){
						$shortCode .= $skey.'='.$svalue.' ';
					}
					$shortCode .= ']';
					$this->setArray($obj, $k1 , $shortCode);
				}
			}
		
		}else{
			$this->setArray($obj, $k1 , $subValue);
		}
	}
}

if(isset($value['tab_content'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'tabs';
	$k = implode('.',$keyValuen);
	$fvalueM = $value['tab_content'];
	foreach($fvalueM as $skey=>$subValue){
		$k1 =  $k.'.'.$skey.'.tab_content';
		$preValue = $x['settings']['tabs'][$skey]['tab_content'];
		$key = $this->shortce($preValue);
		if($key[0] != ''){
			$update = 0;
			$fvalue = str_replace('<p class="description"><small>','',$subValue);
			$fvalue = str_replace('</small></p>','',$fvalue);
			if($key[2] =='1'){
				update_option('options_'.$key[0],$fvalue);
				
				if(is_array($key[1])){
					$shortCode = '[shortce ';
					foreach($key[1] as $skey=>$svalue){
						$shortCode .= $skey.'='.$svalue.' ';
					}
					$shortCode .= ']';
					$this->setArray($obj, $k1 , $shortCode);
				}
			}
			if($key[2] =='2'){
				$rowID  = explode('_',$key[1]['item']); //item ID
				$row_ID  = $rowID[1]; //item ID
				$postIdS  = $rowID[0]; //post ID
				update_post_meta( $postIdS, $key[0],$subValue);
				if(is_array($key[1])){
					$shortCode = '[shortcontent ';
					foreach($key[1] as $skey=>$svalue){
						$shortCode .= $skey.'='.$svalue.' ';
					}
					$shortCode .= ']';
					$this->setArray($obj, $k1 , $shortCode);
				}
			}
		
		}else{
			$this->setArray($obj, $k1 , $subValue);
		}
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
