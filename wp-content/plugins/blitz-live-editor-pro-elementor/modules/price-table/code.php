<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$fvalue = $value['icon'];
	foreach($fvalue as $sskey=>$svalue1){
		$k1 =  $k.'.features_list.'.$sskey;
		if(stristr($svalue1,"fab")){
			$library = 'fa-brands';
		}elseif(stristr($svalue1,"fas")){
			$library = 'fa-solid';
		}else{
			$library = 'fa-regular';
		}
		$this->setArray($obj, $k1.'.selected_item_icon.value' , $svalue1);
		$this->setArray($obj, $k1.'.selected_item_icon.library' , $library);
	}
}
if(isset($value['pricetable'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$pricetablevalue = $value['pricetable'];
	if(isset($pricetablevalue['heading'])){
		$this->setArray($obj, $k.'.heading' , $pricetablevalue['heading']);
	}
	if(isset($pricetablevalue['sub_heading'])){
		$this->setArray($obj, $k.'.sub_heading' , $pricetablevalue['sub_heading']);
	}
	if(isset($pricetablevalue['price'])){
		$this->setArray($obj, $k.'.price' , $pricetablevalue['price']);
	}
	if(isset($pricetablevalue['period'])){
		$this->setArray($obj, $k.'.period' , $pricetablevalue['period']);
	}
	if(isset($pricetablevalue['footer_additional_info'])){
		$this->setArray($obj, $k.'.footer_additional_info' , $pricetablevalue['footer_additional_info']);
	}
	if(isset($pricetablevalue['button_text'])){
		$this->setArray($obj, $k.'.button_text' , $pricetablevalue['button_text']);
	}
	if(isset($pricetablevalue['link'])){
		$this->setArray($obj, $k.'.link.url' , $pricetablevalue['link']);
		$this->setArray($obj, $k.'.link.is_external' , $pricetablevalue['is_external']);
	}
	
	$fvalue = $pricetablevalue['feature'];
	foreach($fvalue as $sskey=>$svalue1){
		$k1 =  $k.'.features_list.'.$sskey;
		if(isset($svalue1['title'])){
			$this->setArray($obj, $k1.'.item_text' , $svalue1['title']);
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
