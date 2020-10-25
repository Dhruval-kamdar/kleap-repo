<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['starrating'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$imgegal = $value['starrating'];
	if(isset($imgegal['rating_scale'])){
		$this->setArray($obj, $k.'.rating_scale' ,$imgegal['rating_scale']);
	}
	if(isset($imgegal['rating'])){
		$this->setArray($obj, $k.'.rating' ,$imgegal['rating']);
	}
	if(isset($imgegal['title'])){
		$this->setArray($obj, $k.'.title' ,$imgegal['title']);
	}
	if(isset($imgegal['align'])){
		$this->setArray($obj, $k.'.align' ,$imgegal['align']);
	}
	if(isset($imgegal['star_style'])){
		$this->setArray($obj, $k.'.star_style' ,$imgegal['star_style']);
	}
	if(isset($imgegal['unmarked_star_style'])){
		$this->setArray($obj, $k.'.unmarked_star_style' ,$imgegal['unmarked_star_style']);
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
