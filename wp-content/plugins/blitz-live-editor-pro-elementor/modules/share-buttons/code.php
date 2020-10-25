<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['sharebuttons'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'share_buttons';
	$k = implode('.',$keyValuen);
	foreach($value['sharebuttons'] as $indexkey => $imgegal){
		$keyind = $indexkey;
		if(isset($imgegal['button'])){
			$this->setArray($obj, $k.'.'.$keyind.'.button' ,$imgegal['button']);
		}
		if(isset($imgegal['title'])){
			$this->setArray($obj, $k.'.'.$keyind.'.text' ,$imgegal['title']);
		}
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
