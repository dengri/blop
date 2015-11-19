<?php

include '/blop/scr/classes/Folder.class.php';
include '/blop/scr/classes/Database.class.php';
use Sitecontent\Folder;
use Database\Database;

//Initialize Folder objects for directories containing images 
//to upload
$img_cov = new Folder\Folder(PARSED_IMG_PATH);

$curl_img = $img_cov->as_post_fields_curl_array();
$curl_img['scr_size'] = 'img_cov';

$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_URL, 'http://javdeluxe.com/uploader.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_img);

echo "Uploading large screenshots\n";
echo ".......................................\n";
$img_cov_resp = json_decode(curl_exec($ch));

var_dump($img_cov_resp);
