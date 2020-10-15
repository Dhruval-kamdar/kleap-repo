<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['reviews'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'slides';
	$k = implode('.',$keyValuen);
	foreach($value['reviews'] as $indexkey => $imgegal){
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


if(isset($value['icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'slides';
	$k = implode('.',$keyValuen);
	$fvalue = $value['icon'];
	foreach($fvalue as $key=>$svalue){
		if(stristr($svalue,"fab")){
			$library = 'fa-brands';
		}elseif(stristr($svalue,"fas")){
			$library = 'fa-solid';
		}else{
			$library = 'fa-regular';
		}
		$k1 =  $k.'.'.$key;
		$this->setArray($obj, $k1.'.selected_social_icon' , array('value'=>$svalue,'library'=>$library));
	}
}

if(isset($value['selected_icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'slides';
	$k = implode('.',$keyValuen);
	$fvalue = $value['selected_icon'];
	foreach($fvalue as $key=>$svalue1){
		$svalue = $svalue1['url'];
		$k1 =  $k.'.'.$key;
		$this->setArray($obj, $k1.'.selected_social_icon' , array('value'=>array('url'=>$svalue1['url'],'id'=>$svalue1['id']),'library'=>'svg'));
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
