<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['animated'])){
	$k = implode('.',$keyValue);
	if(isset($value['animated']['dynamic'])){
		if(count($value['animated']['dynamic']) > 1){
			$finalvalue = implode("\n",$value['animated']['dynamic']);
			$this->setArray($obj, $k.'.rotating_text' , $finalvalue);
		}else{
			$finalvalue = implode('',$value['animated']['dynamic']);
			$this->setArray($obj, $k.'.highlighted_text' , $finalvalue);
		}
	}
	if(isset($value['animated']['static'])){
		$ind1 = -1;
		$ind = $cntt = 0;
		foreach($value['animated']['static'] as $n=>$valuess){
			if($cntt == 0){
				$ind = $n;
			}else{
				$ind1 = $n;
			}
			$cntt++;
		}
		if(isset($value['animated']['static'][$ind])){
			$this->setArray($obj, $k.'.before_text' , $value['animated']['static'][$ind]);
		}
		if(isset($value['animated']['static'][$ind1])){
			$this->setArray($obj, $k.'.after_text' , $value['animated']['static'][$ind1]);
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
