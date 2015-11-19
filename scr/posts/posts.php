<?php
require '/blop/scr/classes/Database.class.php';
use Database\Database;

$db =new Database('sitecontent', 'root', 'qxwv35azsc');

$data = $db->getUploadedData();

foreach($data as $d){
	$title = $d['title'];
	$tags = $d['tags'];


	$img_large = $d['img_large'];
	$imgs_large = explode("\n", $img_large);	
	$new_urls = array();
	foreach($imgs_large as $img_large){

		$replacements = array(
													0 => '/http:\/\/javdeluxe.com\/wp-content\/uploads\/jav\/img_large\//i',
												  1 => '/\_s.jpg/i'
												);
		$new_urls[] = "<a href='$img_large'><img src='$img_large' alt='" . preg_replace( $replacements, '', $img_large) . "'></a>";
}
	$img_large = implode("\n", $new_urls);


	$img_small = $d['img_small'];
	$imgs_small = explode("\n", $img_small);	
	$new_urls = array();
	foreach($imgs_small as $img_small){

		$replacements = array(
													0 => '/http:\/\/javdeluxe.com\/wp-content\/uploads\/jav\/img_large\//i',
												  1 => '/\_s.jpg/i'
												);
		$new_urls[] = "<a href='$img_small'><img src='$img_small' alt='" . preg_replace( $replacements, '', $img_small ) . "'></a>";
}
	$img_small = implode("\n", $new_urls);




	$mediainfo = $d['mediainfo'];

	$url = $d['k2s_urls'];

	$urls = explode("\n", $url);	
	
	$new_urls = array();
	foreach($urls as $url){
		$new_urls[] = "<a target='_blank' href='$url'>" . preg_replace( '/http:\/\/k2s\.cc\/file\/.+\//i', '', $url ) . "</a><br>";
	}
	$url = implode("\n", $new_urls);

	echo $title . "\n" . $tags . "\n" .$img_large . "\n" .$img_small . "\n" .$mediainfo . "\n" .$url . "\n\n\n";
}

//var_dump($data);

