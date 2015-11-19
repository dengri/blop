<?php
define ('DOMAIN_NAME', 'javdeluxe.com');
define ('UPLOAD_PATH', '/wp-content/uploads/jav/');


$upload_path = dirname(__FILE__) . UPLOAD_PATH;

$screens_dir = $_POST['scr_size'];

$upload_path .= $screens_dir;

$img_urls = array();

foreach($_FILES as $file){
	$temp_path = $file['tmp_name'];
	$dest_path = $upload_path . $file['name'];

	if (move_uploaded_file($temp_path, $dest_path)){

		$img_urls[] = 'http://' . basename( dirname(__FILE__) ) . UPLOAD_PATH . $screens_dir . $file['name'];


	}else{
		echo "======== Error! File {$file['name']} hasen't been uploade! ========\n";	
	}
}

echo json_encode($img_urls);
