/*
DROP DATABASE `sitecontent`;
CREATE DATABASE `sitecontent` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
*/
USE `sitecontent`;
/*
CREATE TABLE IF NOT EXISTS `torrents`(
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(225) DEFAULT NULL,
`tags` varchar(500) DEFAULT NULL,
`url` varchar(255) DEFAULT NULL,
`md5` varchar(50) DEFAULT NULL,
`file_name` varchar(255) DEFAULT NULL,
`file_size` varchar(10) DEFAULT NULL,
PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
*/
/*INSERT INTO `products` (`sku`,`name`,`img`,`price`,`paypal`) VALUES(NULL, 'Logo shirt, Blue', 'img\logo-shirt-blue.jpg', '12.07', '1451345345');*/
/*
DROP TABLE `videos`;
CREATE TABLE IF NOT EXISTs `videos`(
`id` int(11) AUTO_INCREMENT NOT NULL,
`torrent_id` int(11) DEFAULT NULL,
`video` varchar(225) DEFAULT NULL,
PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;

*/
select distinct 
				t.title, 
				t.tags, 
				l.url, 
				s.url, 
				m.info, 
				k.video_url 
				from img_large as l, 
						 img_small as s, 
						 torrents as t, 
						 videos as v, 
						 mediainfo as m,
						 video_urls as k 
				where v.id = l.video_id 
				and v.id = s.video_id 
				and v.id = m.video_id 
				and t.id = v.torrent_id
				and k.torrent_id = t.id\G
/*
DROP TABLE `images`;
CREATE TABLE IF NOT EXISTS `img_large`(
`id` int(11) AUTO_INCREMENT NOT NULL,
`video_id` int(11) DEFAULT NULL,
`url` varchar(225) DEFAULT NULL,
PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `img_small`(
`id` int(11) AUTO_INCREMENT NOT NULL,
`video_id` int(11) DEFAULT NULL,
`url` varchar(225) DEFAULT NULL,
PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `mediainfo`(
`id` int(11) AUTO_INCREMENT NOT NULL,
`video_id` int(11) DEFAULT NULL,
`info` varchar(225) DEFAULT NULL,
PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `video_urls`(
`id` int(11) AUTO_INCREMENT NOT NULL,
`torrent_id` int(11) DEFAULT NULL,
`video_url` varchar(225) DEFAULT NULL,
PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
*/
