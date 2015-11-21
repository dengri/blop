<?php namespace Database;

class Database{

	private $con;

	function __construct($db, $login, $password){
	
		try{
		
			$this->con = new \PDO("mysql:host=localhost;dbname=$db", $login, $password);
			
			$this->con->exec("SET NAMES 'utf8'");
			$this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		
		}catch(\PDOException $e){
		
			echo $e->getMessage();
			die('Something is wrong!');
		
		}
	}


	function getConnection(){
		return $this->con;	
	}





	function appendTo($table_name, $fields){

		\extract($fields);

		switch($table_name){

		case 'torrents':
			$qstring = "INSERT INTO torrents (title, tags, url, md5, file_name, file_size) 
									VALUES ('$title', '$tags', '$url', '$md5', '$file_name', '$file_size')";
		break;
		
		case 'videos':
			$qstring = "INSERT INTO videos (video, torrent_id) 
									VALUES ('$video', '$torrent_id')";
		break;
		
		case 'img_large':
			$qstring = "INSERT INTO img_large (video_id, url) 
									VALUES ('$video_id', '$url')";
		break;

		case 'img_small':
			$qstring = "INSERT INTO img_small (video_id, url) 
									VALUES ('$video_id', '$url')";
		break;
		
		case 'mediainfo':
			$qstring = "INSERT INTO mediainfo (video_id, info) 
									VALUES ('$video_id', '$info')";
		break;

		case 'video_urls':
			$qstring = "INSERT INTO video_urls (torrent_id, video_id, video_url) 
									VALUES ('$torrent_id', '$video_id', '$video_url')";
		break;

		}	

		return $this->con->exec($qstring);	
	}



	function updateDB($table_name, $fields){
		\extract($fields);

		switch($table_name){
			case 'torrents':	
				$condition = \pathinfo($cover, \PATHINFO_FILENAME);
				$qstring = "UPDATE torrents SET cover='$cover' WHERE file_name LIKE '$condition%'";
			break;
		}
		return $this->con->exec($qstring);
	}




	function getUploadedData(){
			

		$qstring = "drop table if exists video_parts_combined_temp";
		$this->con->exec($qstring);

		$qstring = "create temporary table video_parts_combined_temp
										select torrent_id, video_id, 
													 group_concat(video_url order by substring_index(video_url, '.', -2) separator '\n') as all_parts_urls
										from video_urls group by video_id";
		$this->con->exec($qstring);

		$qstring = "CREATE TEMPORARY TABLE data_per_video
									SELECT v.torrent_id, v.all_parts_urls AS `k2s_urls`, m.info AS `mediainfo`, il.url AS `img_large`, ism.url AS `img_small` 
											FROM video_parts_combined_temp AS v 
											LEFT JOIN mediainfo AS m 
												ON v.video_id = m.video_id 
											LEFT JOIN img_large AS il 
												ON v.video_id = il.video_id
											LEFT JOIN img_small AS ism
												ON v.video_id = ism.video_id"; 
		$this->con->exec($qstring);

		$qstring = "SELECT 	t.title, t.tags, 
											 	GROUP_CONCAT(dpv.img_large SEPARATOR '\n') AS img_large, 
											 	GROUP_CONCAT(dpv.img_small SEPARATOR '\n') AS img_small, 
											 	GROUP_CONCAT(dpv.mediainfo SEPARATOR '\n') AS mediainfo, 
											 	GROUP_CONCAT(dpv.k2s_urls SEPARATOR '\n')  AS k2s_urls 
											FROM torrents AS t
											LEFT JOIN data_per_video AS dpv 
												ON t.id = dpv.torrent_id
											GROUP BY dpv.torrent_id";



		//$qstring = "select * from data_per_video";
		$result = $this->con->query($qstring);

		return $result->fetchAll(\PDO::FETCH_ASSOC);
	}



	function getTorrID($path){
			
		$md5 = hash_file('md5', $path);

		$qstring = "SELECT id FROM torrents WHERE md5='$md5'";
		$result = $this->con->query($qstring);

		return $result->fetch(\PDO::FETCH_ASSOC)['id']; 
	}



	function getTorrIDfromFilename($filename)	{

		if(strpos($filename, 'rar')){
		  $filename =	preg_replace('/\.part.+/i', '', $filename);
		}else{
			$filename = substr($filename, 0, strlen($filename)-4);
		}

		$qstring = "SELECT torrent_id FROM videos WHERE video LIKE '$filename%'";
		$result = $this->con->query($qstring);
		return $result->fetch(\PDO::FETCH_ASSOC)['torrent_id']; 
	}


	function getVideoID($file_name){
		if(strpos($file_name, 'jpg')){
			$file_name = basename(preg_replace('/\_s.jpg/i', '', $file_name));
		}else{
			$file_name = explode('.', $file_name)[0];
		}	

		$qstring = "SELECT id FROM videos WHERE video LIKE '$file_name%'";
		$result = $this->con->query($qstring);
		return $result->fetch(\PDO::FETCH_ASSOC)['id']; 
	}


}


