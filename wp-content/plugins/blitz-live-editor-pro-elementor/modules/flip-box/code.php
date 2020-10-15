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
	echo $fvalue.'----'.$library;
	$this->setArray($obj, $k1 ,array('value'=> $fvalue,'library'=> $library));

}
if(isset($value['flipbox'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$pricetablevalue = $value['flipbox'];
	if(isset($pricetablevalue['title_text_a'])){
		$this->setArray($obj, $k.'.title_text_a' , $pricetablevalue['title_text_a']);
	}
	if(isset($pricetablevalue['description_text_a'])){
		$this->setArray($obj, $k.'.description_text_a' , $pricetablevalue['description_text_a']);
	}
	if(isset($pricetablevalue['title_text_b'])){
		$this->setArray($obj, $k.'.title_text_b' , $pricetablevalue['title_text_b']);
	}
	if(isset($pricetablevalue['description_text_b'])){
		$this->setArray($obj, $k.'.description_text_b' , $pricetablevalue['description_text_b']);
	}
	if(isset($pricetablevalue['button_text'])){
		$this->setArray($obj, $k.'.button_text' , $pricetablevalue['button_text']);
	}
	if(isset($pricetablevalue['link'])){
		$this->setArray($obj, $k.'.link.url' , $pricetablevalue['link']);
		$this->setArray($obj, $k.'.link.is_external' , $pricetablevalue['is_external']);
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
