<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['image'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'image';
	$keyValuen[] = 'url';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['image']);
}

if(isset($value['id'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'image';
	$keyValuen[] = 'id';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['id']);
}
