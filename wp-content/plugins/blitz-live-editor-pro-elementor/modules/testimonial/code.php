<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['image'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'testimonial_image';
	$keyValuen[] = 'url';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['image']);
}

if(isset($value['id'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'testimonial_image';
	$keyValuen[] = 'id';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['id']);
}

if(isset($value['testimonial_name'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'testimonial_name';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['testimonial_name']);
}


if(isset($value['testimonial_content'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'testimonial_content';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['testimonial_content']);
}


if(isset($value['testimonial_job'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'testimonial_job';
	$k = implode('.',$keyValuen);
	$this->setArray($obj, $k , $value['testimonial_job']);
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
