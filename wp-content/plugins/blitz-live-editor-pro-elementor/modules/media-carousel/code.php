<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['mediaCarousel'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'slides';
	$k = implode('.',$keyValuen);
	foreach($value['mediaCarousel'] as $indexkey => $imgegal){
		$keyind = $indexkey;
		if(isset($imgegal['image'])){
			$this->setArray($obj, $k.'.'.$keyind.'.image' , array('url'=>$imgegal['image'],'id'=>$imgegal['id']));
		}
		if(isset($imgegal['url'])){
			
			$this->setArray($obj, $k.'.'.$keyind.'.image_link_to_type' ,'custom');
			$this->setArray($obj, $k.'.'.$keyind.'.image_link_to' ,array('url'=>$imgegal['url'],'is_external'=>'','nofollow'=>''));
		}
		if(isset($imgegal['vurl'])){
			$this->setArray($obj, $k.'.'.$keyind.'.video' ,array('url'=>$imgegal['vurl'],'is_external'=>'','nofollow'=>''));
		}
	}
	
	
}

