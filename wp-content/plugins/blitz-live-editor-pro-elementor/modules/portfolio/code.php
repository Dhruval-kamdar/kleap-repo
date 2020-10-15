<?php
// Silence is golden.
$keyValue[] = 'settings';

if(isset($value['posts'])){
	foreach($value['posts'] as $indexkey => $postval){
		
		$my_post = array('ID' => $indexkey);
		if(isset($postval['fid'])){
			set_post_thumbnail($indexkey,$postval['fid']);
		}
		// Update the post into the database
		$post_id = wp_update_post( $my_post, true );
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
