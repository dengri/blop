<?php

//-----------------
//  Constants
//-----------------
$glob_reset_progress = true;



//-----------------
//  Constants
//-----------------

//--- Server ---//
//Number of torrents to parse
define('NUM_TORRENTS', 5);
//Where to store torrent files
define('TORRENTS_PATH', '/blop/tor/');
//Watch folder
define('WATCH_PATH', '/blop/watch/');
//Path to a folder where to save covers prepared for upload
define('SERVER_COVERS_PATH', '/blop/upl/img_cov/');
//Path to the img_large folder
define('SERVER_IMG_LARGE_PATH', '/blop/upl/img_large/');
//Path to the img_large folder
define('SERVER_IMG_SMALL_PATH', '/blop/upl/img_small/');
//Path to videos to be renamed
define('VIDEOS_SRC_PATH', '/blop/vids/');
//Path to videos to be uploaded
define('VIDEOS_DEST_PATH', '/blop/upl/vids/');
//Url of the page to be barsed
define('URL_TO_PARSE', 'http://www.empornium.me/torrents.php?filter_cat[5]=1');
//Login and password of the torrent tracker site
define('POST_FIELDS', 'username=sergemitrof&password=qxwv35azsc&keeplogged=1&login=Login');
//Path to the file were video technical info will be saved
define('MEDIAINFO_PATH', '/blop/upl/inf/filesinfo.txt');

//--- Site ---//
//Path to image uploader
define('IMG_UPLOADER_PATH', 'http://javdeluxe.com/uploader.php');
//Path to the covers folder
define('SITE_COVERS_PATH', 'img_cov');
//Path to the covers folder
define('SITE_IMG_LARGE_PATH', 'img_large');
//Path to the covers folder
define('SITE_IMG_SMALL_PATH', 'img_small');


//-----------------
//  Temp. files
//-----------------
define('COOKIEJAR_TEMP_PATH', '/blop/scr/temp/cookiejar');
define('PARSED_PAGE_TEMP_PATH', '/blop/scr/temp/page.html');


