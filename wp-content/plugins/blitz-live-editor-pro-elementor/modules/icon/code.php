<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$varray['icon'] = $value['icon'];
	if(stristr($varray['icon'],"fab")){
		$library = 'fa-brands';
	}elseif(stristr($varray['icon'],"fas")){
		$library = 'fa-solid';
	}else{
		$library = 'fa-regular';
	}
	$this->setArray($obj, $k.'.selected_icon' , array('value'=>$varray['icon'],'library'=>$library));
	$this->setArray($obj, $k.'.icon' , '-121||');
}

if(isset($value['selected_icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$varray['selected_icon']['value'] = array('url'=>$value['selected_icon']['url'],'id'=>$value['selected_icon']['id']);
	$varray['selected_icon']['library'] = 'svg'; 
	$this->setArray($obj, $k, $varray);
	$this->setArray($obj, $k.'.icon' , '-121||');
}
