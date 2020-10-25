<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['testimonialCarousel'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'slides';
	$k = implode('.',$keyValuen);
	foreach($value['testimonialCarousel'] as $indexkey => $imgegal){
		$keyind = $indexkey;
		if(isset($imgegal['image'])){
			$this->setArray($obj, $k.'.'.$keyind.'.image' , array('url'=>$imgegal['image'],'id'=>$imgegal['id']));
		}
		if(isset($imgegal['title'])){
			$this->setArray($obj, $k.'.'.$keyind.'.title' ,$imgegal['title']);
		}
		if(isset($imgegal['content'])){
			$this->setArray($obj, $k.'.'.$keyind.'.content' ,$imgegal['content']);
		}
		if(isset($imgegal['name'])){
			$this->setArray($obj, $k.'.'.$keyind.'.name' ,$imgegal['name']);
		}
	}
	
	
}

