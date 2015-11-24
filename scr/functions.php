<?php 

function login($url){

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIEJAR_TEMP_PATH);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, POST_FIELDS);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	$buf = curl_exec ($ch);

	curl_close ($ch);

	return $buf;
}


function get_page($url){

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIEJAR_TEMP_PATH);
	curl_setopt($ch, CURLOPT_URL, $url);

	$buf = curl_exec ($ch);

	curl_close ($ch);

	return $buf;
}


function squeeze_plain_text($elements){
	$i=1;
	$a = array();
	foreach($elements as $element){
		if($i>NUM_TORRENTS) break;
		$a[] = $element->plaintext;
		$i++;
	}
	return $a;
}


function squeeze_href($elements){
	$i=1;
	$a = array();
	foreach($elements as $element){
		if($i>NUM_TORRENTS) break;
		$a[] = $element->href;
		$i++;
	}
	return $a;
}


function save_torrent($url, $torrent_file_name){
	$download_torrent_file_name = TORRENTS_PATH . $torrent_file_name;
	$copy_torrent_file_name = TORRENTS_PATH_COPY . $torrent_file_name;

	$res = fopen("$download_torrent_file_name", "w+");

	$ch = curl_init();


	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_FILE, $res);
	curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIEJAR_TEMP_PATH);

	$buf = curl_exec($ch);
	curl_close($ch);

	fclose($res);

	copy($download_torrent_file_name, $copy_torrent_file_name);
	//return hash_file('md5', $torrent_file_name);

}




function download_torrents($urls, $filenames){
	
	$c = 0;

	$torr_path = [];

	foreach($urls as $url){

		progress('Download torrents', '#');

		$url = htmlspecialchars_decode($url);

		$dl_path = TORRENTS_PATH . $filenames[$c];

		$res = fopen($dl_path, "w+");

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FILE, $res);
		curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIEJAR_TEMP_PATH);

		$buf = curl_exec($ch);
		curl_close($ch);

		fclose($res);

		$torr_path[] = $dl_path;	
		
		copy($dl_path, WATCH_PATH . $filenames[$c] );

		$c++;
	}

	progress_recet();	

	return $torr_path;
}



function progress( $message, $delimiter ){

	global $glob_reset_progress;
	
	if($glob_reset_progress) {
		echo $message . "\n";
		$glob_reset_progress = false;
	}
	echo $delimiter;
}


function progress_recet(){
	global $glob_reset_progress;
	$glob_reset_progress = true;
	echo "\n";
}


function downloadImages($imgs, $names = NULL){
	
	$c = 0;
	$img_path = [];

	foreach($imgs as $img){
		
		progress('Downloading images for covers:', '#');

		$img = htmlspecialchars_decode($img);
		
		if(isset($names[$c])){
				$dl_path = PARSED_IMG_PATH . $names[$c] . '.' . pathinfo($img, PATHINFO_EXTENSION);
		}else{
				$dl_path = PARSED_IMG_PATH . basename($img);
		}

		$res = fopen($dl_path, "w+");

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $img);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FILE, $res);
		curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIEJAR_TEMP_PATH);

		$buf = curl_exec($ch);
		curl_close($ch);

		fclose($res);

		$c++;
	}

	progress_recet();
	echo "\n";
	return get_dir_as_array(PARSED_IMG_PATH);
}



function get_dir_as_array($path){
	return array_diff(scandir($path), array('.', '..'));
}



function get_counter($i){

   if ($i<10) $counter="000".$i;
	   elseif ($i<100) $counter="00".$i;
	     elseif ($i<1000) $counter="0".$i;

	 return $counter;
}



function save_torrent_files($title, $tags, $url, $file_size){ 

 	global $i;
	
	$filename = preg_replace('/[^a-z0-9\._]/i', '-', $title);
	$filename = preg_replace('/-+/i', '-', $filename);
	$filename = trim($filename, '-') . '.torrent';

	$md5 = md5($filename);

	$filename = /*get_counter(++$i) . '_' . */$md5 . '_' . $filename;

 	$tags = trim($tags);
 	$tags = preg_replace('/ /', ', ', $tags);
 	$tags = preg_replace('/\./', ' ', $tags);


 	$url = htmlspecialchars_decode($url);
 	save_torrent($url, $filename);

 	return array(	'title'		  => $title, 
								'file_name'  => $filename,
 								'tags'      => $tags, 
 								'url'       => $url,
 								'md5'       => $md5,
								'file_size' => $file_size
 							); 
}


function save_parsed_to_db($row){
	global $db;
	$db->appendTo( 'torrents', $row);
}


function get_md5_from_filename($filename){
	return substr($filename, 0, 31);
}


function upload_images($source, $dest){

	$images = array();

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_URL, IMG_UPLOADER_PATH);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	foreach($source as $img){
	
		progress("Uploading images and adding info to DB:", '#');
	
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('cover' => $img, 'path' => $dest));
		$images[] = json_decode(curl_exec($ch));
	}
	
	curl_close($ch);
	return $images;
}


