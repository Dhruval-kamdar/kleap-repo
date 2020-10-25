<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['imagegal'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'wp_gallery';
	$k = implode('.',$keyValuen);
	$oldurls = array();
	foreach($x['settings']['wp_gallery'] as $indexkey => $ovalue){
		$oldurls[$indexkey] = $ovalue['url'];
	}
	foreach($value['imagegal'] as $imgegal){
		$keyind = array_search($imgegal['old'],$oldurls);
		$this->setArray($obj, $k.'.'.$keyind.'.url' , $imgegal['image']);
		$this->setArray($obj, $k.'.'.$keyind.'.id' , $imgegal['id']);
	}
	
	
}

