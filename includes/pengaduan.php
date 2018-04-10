<?php

ob_start();
session_start();

include ('../config.php');

/* Silahkan buat dulu tabel pengaduan. Jalankan diterminal atau PHPMyAdmin. */
/*
CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/

$message = $_POST['message'];
 
if($message != "") {
 $sql = "INSERT INTO pengaduan VALUES('','$datetime', '$_SESSION[username]', '$message')";
 query($sql);
}
 
$sql = "SELECT * FROM pengaduan ORDER BY id ASC LIMIT 100";
$result = query($sql);
 
while($row = fetch_array($result))
 echo $row['username'].": ".$row['message']."\n";
 
 
?>
