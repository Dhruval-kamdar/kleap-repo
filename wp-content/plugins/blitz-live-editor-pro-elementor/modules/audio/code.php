<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['audio'])){
	$keyValuen = array();
	$keyValuen = $keyValue;
	$k = implode('.',$keyValuen);
	$imgegal = $value['audio'];
	$audio = array();
	
	if(isset($imgegal['link'])){
		$audio['link'] = array('url'=>$imgegal['link'],'is_external'=>'','nofollow'=>'');
	}
	if(isset($imgegal['visual'])){
		$audio['visual'] = 'yes';
	}
	if(isset($imgegal['sc_auto_play'])){
		$audio['sc_auto_play'] = '';
	}
	if(isset($imgegal['sc_buying'])){
		$audio['sc_buying'] = '';
	}
	if(isset($imgegal['sc_liking'])){
		$audio['sc_liking'] = '';
	}
	if(isset($imgegal['sc_download'])){
		$audio['sc_download'] = '';
	}
	if(isset($imgegal['sc_show_artwork'])){
		$audio['sc_show_artwork'] = '';
	}
	if(isset($imgegal['sc_sharing'])){
		$audio['sc_sharing'] = '';
	}
	if(isset($imgegal['sc_show_comments'])){
		$audio['sc_show_comments'] = '';
	}
	if(isset($imgegal['sc_show_playcount'])){
		$audio['sc_show_playcount'] = '';
	}
	if(isset($imgegal['sc_show_user'])){
		$audio['sc_show_user'] = '';
	}

	$this->setArray($obj, $k ,$audio);
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
