<?php
// Silence is golden.
$keyValue[] = 'settings';
if(isset($value['social'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'social_icon_list';
	$k = implode('.',$keyValuen);
	$fvalue = $value['social'];
	foreach($fvalue as $key=>$svalue){
		$k1 =  $k.'.'.$key.'.social_icon.value';
		$this->setArray($obj, $k1 , $svalue);
		$k1 =  $k.'.'.$key.'.social_icon.library';
		if(stristr($svalue,"fab")){
			$library = 'fa-brands';
		}elseif(stristr($svalue,"fas")){
			$library = 'fa-solid';
		}else{
			$library = 'fa-regular';
		}
		$this->setArray($obj, $k1 , $library);
	}
}

if(isset($value['socialurl'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'social_icon_list';
	$k = implode('.',$keyValuen);
	$fvalue = $value['socialurl'];
	foreach($fvalue as $key=>$svalue){
		$k1 =  $k.'.'.$key.'.link.url';
		echo $svalue;
		$this->setArray($obj, $k1 , $svalue);
	}
}

if(isset($value['socialtarget'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'social_icon_list';
	$k = implode('.',$keyValuen);
	$fvalue = $value['socialtarget'];
	foreach($fvalue as $key=>$svalue){
		$k1 =  $k.'.'.$key.'.link.is_external';
		$this->setArray($obj, $k1 , $svalue);
	}
}

if(isset($value['bgid'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = '_background_image';
	$keyValuen[] = 'id';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['bgid']);
}
if(isset($value['bg'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = '_background_image';
	$keyValuen[] = 'url';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['bg']);
}
