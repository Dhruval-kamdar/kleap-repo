<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['url'])){
	if(strstr($value['url'],'youtu')){
		$keystr = 'youtube_url';
	}elseif(strstr($value['url'],'vimeo')){
		$keystr = 'vimeo_url';
	}elseif(strstr($value['url'],'dailymotion')){
		$keystr = 'dailymotion_url';
	}else{
		$keystr = 'external_url';
	}
	if($keystr == 'external_url'){
		$keyValuen = array();
		$keyValuen = $keyValue;
		$k = implode('.',$keyValuen);
		$this->setArray($obj, $k.'.video_type' , 'hosted');
		$this->setArray($obj, $k.'.insert_url' , 'yes');
		$this->setArray($obj, $k.'.external_url.url' , $value['url']);
		
	}else{
		$keyValuen = array();
		$keyValuen = $keyValue;
		$keyValuen[] = $keystr;
		$k = implode('.',$keyValuen);
		$this->setArray($obj, $k , $value['url']);
	}
}
