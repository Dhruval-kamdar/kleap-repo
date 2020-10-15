<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['blockquote'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$imgegal = $value['blockquote'];
	if(isset($imgegal['content'])){
		$this->setArray($obj, $k.'.blockquote_content' ,$imgegal['content']);
	}
	if(isset($imgegal['author'])){
		$this->setArray($obj, $k.'.author_name' ,$imgegal['author']);
	}
	if(isset($imgegal['tweetlabel'])){
		$this->setArray($obj, $k.'.tweet_button_label' ,$imgegal['tweetlabel']);
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
