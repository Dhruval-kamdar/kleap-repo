<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['googlemap'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k.'.address' , $value['googlemap']['map_location']);
	$this->setArray($obj, $k.'.zoom.size' , $value['googlemap']['map_zoom']);
	$this->setArray($obj, $k.'.height.size' , $value['googlemap']['map_height']);
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
