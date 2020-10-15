<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['progress'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$imgegal = $value['progress'];
	if(isset($imgegal['progress_type'])){
		$this->setArray($obj, $k.'.progress_type' ,$imgegal['progress_type']);
	}
	if(isset($imgegal['title'])){
		$this->setArray($obj, $k.'.title' ,$imgegal['title']);
	}
	if(isset($imgegal['inner_text'])){
		$this->setArray($obj, $k.'.inner_text' ,$imgegal['inner_text']);
	}
	if(isset($imgegal['display_percentage'])){
		$this->setArray($obj, $k.'.display_percentage' ,$imgegal['display_percentage']);
	}
	if(isset($imgegal['size'])){
		$this->setArray($obj, $k.'.percent.size' ,$imgegal['size']);
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
