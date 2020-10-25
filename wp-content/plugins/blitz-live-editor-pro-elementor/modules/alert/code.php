<?php
// Silence is golden.
$keyValue[] = 'settings';
if(isset($value['title'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'alert_title';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['title']);
}

if(isset($value['description'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'alert_description';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['description']);
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
