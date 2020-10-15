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

if(isset($value['title'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'title_text';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['title']);
}


if(isset($value['editor'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'description_text';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['editor']);
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
