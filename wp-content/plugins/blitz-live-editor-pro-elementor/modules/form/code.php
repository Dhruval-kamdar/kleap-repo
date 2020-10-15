<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['form'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	if(isset($value['form']['email_to']) && trim($value['form']['email_to']) != ''){
		$this->setArray($obj, $k.'.email_to' , $value['form']['email_to']);
	}
	if(isset($value['form']['email_from']) && trim($value['form']['email_from']) != ''){
		$this->setArray($obj, $k.'.email_from' , $value['form']['email_from']);
	}
	if(isset($value['form']['email_from_name']) && trim($value['form']['email_from_name']) != ''){
		$this->setArray($obj, $k.'.email_from_name' , $value['form']['email_from_name']);
	}
	if(isset($value['form']['email_to_2']) && trim($value['form']['email_to_2']) != ''){
		$this->setArray($obj, $k.'.email_to_2' , $value['form']['email_to_2']);
	}
	if(isset($value['form']['email_from_2']) && trim($value['form']['email_from_2']) != ''){
		$this->setArray($obj, $k.'.email_from_2' , $value['form']['email_from_2']);
	}
	if(isset($value['form']['email_from_name_2']) && trim($value['form']['email_from_name_2']) != ''){
		$this->setArray($obj, $k.'.email_from_name_2' , $value['form']['email_from_name_2']);
	}
}
