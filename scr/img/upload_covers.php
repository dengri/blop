<?php

include '/blop/scr/classes/Folder.class.php';
include '/blop/scr/classes/Database.class.php';
include '/blop/scr/setup.php';
include '/blop/scr/functions.php';
use Sitecontent\Folder\Folder;
use Database\Database;

//Initialize Folder object for directory containing images 
//to upload
$img_cov = new Folder(PARSED_IMG_PATH);

//Get cover images from source directory and prepare them for upload with cURL
$curl_img = $img_cov->as_post_fields_curl_array();

$covers = array();
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_URL, 'http://javdeluxe.com/uploader.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

foreach($curl_img as $img){

	progress("Uploading images and adding info to DB:", '#');

	curl_setopt($ch, CURLOPT_POSTFIELDS, array('cover' => $img, 'path' => 'img_cov'));
	$covers[] = json_decode(curl_exec($ch));
}

var_dump($covers);

$db = new Database('sitecontent', 'root', 'qxwv35azsc');
//---------------------------------------------------
//			Get video_ids from videos table
//---------------------------------------------------
foreach ($covers as $img_url){
	$db->updateDB('torrents', array('cover' => $img_url[0]));
}
 
curl_close($ch);

