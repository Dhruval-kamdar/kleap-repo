<?php
// Silence is golden.
$keyValue[] = 'settings';


if(isset($value['starting_number'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'starting_number';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['starting_number']);
}

if(isset($value['ending_number'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'ending_number';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['ending_number']);
}

if(isset($value['title'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'title';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['title']);
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
