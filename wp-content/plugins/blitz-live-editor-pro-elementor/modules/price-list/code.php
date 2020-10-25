<?php
// Silence is golden.
$keyValue[] = 'settings';


if(isset($value['pricelist'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'price_list';
	$k = implode('.',$keyValuen);
	$fvalue = $value['pricelist'];
	foreach($fvalue as $sskey=>$svalue1){
		$k1 =  $k.'.'.$sskey;
		if(isset($svalue1['title'])){
			$this->setArray($obj, $k1.'.title' , $svalue1['title']);
		}
		if(isset($svalue1['description'])){
			$this->setArray($obj, $k1.'.item_description' , $svalue1['description']);
		}
		if(isset($svalue1['price'])){
			$this->setArray($obj, $k1.'.price' , $svalue1['price']);
		}
		if(isset($svalue1['image'])){
			$this->setArray($obj, $k1.'.image.url' , $svalue1['image']);
			$this->setArray($obj, $k1.'.image.id' , $svalue1['id']);
		}
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
