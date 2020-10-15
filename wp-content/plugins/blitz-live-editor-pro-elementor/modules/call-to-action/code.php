<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$fvalue = $value['icon'];
	$k1 =  $k.'.selected_icon';
	if(stristr($fvalue,"fab")){
		$library = 'fa-brands';
	}elseif(stristr($fvalue,"fas")){
		$library = 'fa-solid';
	}else{
		$library = 'fa-regular';
	}
	$this->setArray($obj, $k1 ,array('value'=> $fvalue,'library'=> $library));

}
if(isset($value['calltoaction'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$pricetablevalue = $value['calltoaction'];
	
	if(isset($pricetablevalue['title'])){
		$this->setArray($obj, $k.'.title' , $pricetablevalue['title']);
	}
	if(isset($pricetablevalue['description'])){
		$this->setArray($obj, $k.'.description' , $pricetablevalue['description']);
	}
	if(isset($pricetablevalue['button'])){
		$this->setArray($obj, $k.'.button' , $pricetablevalue['button']);
	}
	if(isset($pricetablevalue['link'])){
		$this->setArray($obj, $k.'.link.url' , $pricetablevalue['link']);
		$this->setArray($obj, $k.'.link.is_external' , $pricetablevalue['is_external']);
	}
	if(isset($pricetablevalue['bgimage'])){
		$this->setArray($obj, $k.'.bg_image.url' , $pricetablevalue['bgimage']['image']);
		$this->setArray($obj, $k.'.bg_image.id' , $pricetablevalue['bgimage']['id']);
	}
	if(isset($pricetablevalue['image'])){
		$this->setArray($obj, $k.'.graphic_image.url' , $pricetablevalue['image']['image']);
		$this->setArray($obj, $k.'.graphic_image.id' , $pricetablevalue['image']['id']);
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
