<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['title'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'text';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['title']);
}

if(isset($value['ourl'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'link';
	$keyValuen[] = 'ourl';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['ourl']);
}

if(isset($value['url'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'link';
	$keyValuen[] = 'url';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['url']);
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
