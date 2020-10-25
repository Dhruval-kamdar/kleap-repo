<?php
// Silence is golden.
$keyValue[] = 'settings';


if(isset($value['title'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'icon_list';
	$k = implode('.',$keyValuen);
	$fvalue = $value['title'];
	foreach($fvalue as $key=>$svalue){
		$k1 =  $k.'.'.$key.'.text';
		$this->setArray($obj, $k1 , $svalue);
	}
}

if(isset($value['icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'icon_list';
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
		$this->setArray($obj, $k1.'.selected_icon' , array('value'=>$svalue,'library'=>$library));
		$this->setArray($obj, $k1.'.icon' , '-121||');
	}
}

if(isset($value['selected_icon'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$keyValuen[] = 'icon_list';
	$k = implode('.',$keyValuen);
	$fvalue = $value['selected_icon'];
	foreach($fvalue as $key=>$svalue1){
		$svalue = $svalue1['url'];
		$k1 =  $k.'.'.$key;
		$this->setArray($obj, $k1.'.selected_icon' , array('value'=>array('url'=>$svalue1['url'],'id'=>$svalue1['id']),'library'=>'svg'));
		$this->setArray($obj, $k1.'.icon' , '-121||');
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
