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
			$qstring = "INSERT INTO video_urls (torrent_id, video_url) 
									VALUES ('$torrent_id', '$video_url')";
		break;

		}	

		return $this->con->exec($qstring);	
	}



	function getTorrID($path){
			
		$md5 = hash_file('md5', $path);

		$qstring = "SELECT id FROM torrents WHERE md5='$md5'";
		$result = $this->con->query($qstring);

		return $result->fetch(\PDO::FETCH_ASSOC)['id']; 
	}


	function getTorrIDfromFilename($filename)	{

		$filename = substr($filename, 0, strlen($filename)-4);
		$qstring = "SELECT torrent_id FROM videos WHERE video LIKE '$filename%'";
		$result = $this->con->query($qstring);
		return $result->fetch(\PDO::FETCH_ASSOC)['torrent_id']; 
	}


	function getVideoID($file_name){
		if(strpos($file_name, 'jpg'))
			$file_name = basename(preg_replace('/\_s.jpg/i', '', $file_name));
	
		$qstring = "SELECT id FROM videos WHERE video LIKE '$file_name%'";
		$result = $this->con->query($qstring);
		return $result->fetch(\PDO::FETCH_ASSOC)['id']; 
	}


}


