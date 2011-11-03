<?php
/* ****************************** */
/* Scrapbook script in PHP        */
/* scrapbook                      */
/* copyright 2011 by Philip Stark */
/* ****************************** */
include 'settings.php';

function deleteScrap($scrapid, $scrapdir){
	$scrapid = str_replace("/", "", $scrapid);
	return file_exists($scrapdir."/".$scrapid) && unlink($scrapdir."/".$scrapid);
}

if ($_GET["del"]){
	$scrapid = $_GET["del"];
	if (deleteScrap($scrapid, $scrapdir)){
		include 'index.php';
		echo "successfully deleted";
	}
	else{
		include 'index.php';
		echo "there was an error with this scrap: ".$scrapid;
	}
}
else{
	echo "there was an error!";
}
?>
