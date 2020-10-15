<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['slidecal'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'slides';
	$k = implode('.',$keyValuen);
	foreach($value['slidecal'] as $indexkey => $imgegal){
		$keyind = $indexkey;
		if(isset($imgegal['image'])){
			$this->setArray($obj, $k.'.'.$keyind.'.background_image' , array('url'=>$imgegal['image'],'id'=>$imgegal['id']));
		}
		if(isset($imgegal['heading'])){
			$this->setArray($obj, $k.'.'.$keyind.'.heading' ,$imgegal['heading']);
		}
		if(isset($imgegal['description'])){
			$this->setArray($obj, $k.'.'.$keyind.'.description' ,$imgegal['description']);
		}
		if(isset($imgegal['button'])){
			$this->setArray($obj, $k.'.'.$keyind.'.button_text' ,$imgegal['button']);
		}
	}
	
	
}

