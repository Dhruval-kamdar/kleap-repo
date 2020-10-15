<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['imagecal'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'carousel';
	$k = implode('.',$keyValuen);
	$oldurls = array();

	foreach($value['imagecal'] as $indexkey => $imgegal){
		$keyind = $indexkey;
		$this->setArray($obj, $k.'.'.$keyind.'.url' , $imgegal['image']);
		$this->setArray($obj, $k.'.'.$keyind.'.id' , $imgegal['id']);
	}
	
	
}

