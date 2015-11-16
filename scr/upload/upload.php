<?php
include "/blop/scr/classes/k2sUpload.class.php";
include "/blop/scr/classes/Folder.class.php";
include "/blop/scr/classes/Database.class.php";

use Database\Database;

$upl = new k2sUpload('cf54d698b50ae');
$db = new Database('sitecontent', 'root', 'qxwv35azsc');

//$dir = new Folder(VIDEOS_DEST_PATH);
//$upl->multyUploadDir('/blop/scr/k2s/upl/', 2);
$upl->createFolder();
$upl->upload('/blop/upl/vids/', 5);


$files_multydim_array = $upl->getFilesList($upl->uploadFolder['id'], 1000, 0, [], 'any')['files'];
$files = array();

foreach($files_multydim_array as $file){
	$files[$file['name']] = 'http://k2s.cc/file/' . $file['id'] . '/' . $file['name'];
}

foreach($files as $filename => $url){

	$torr_id = $db->getTorrIDfromFilename($filename);
	$db->appendTo('video_urls', array('torrent_id' => $torr_id, 'video_url' => $url));

	var_dump($torr_id);
}
