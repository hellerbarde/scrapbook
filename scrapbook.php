<?php
/* ****************************** */
/* Scrapbook script in PHP        */
/* scrapbook                      */
/* copyright 2011 by Philip Stark */
/* ****************************** */
include 'settings.php';


$StartAt = time() + microtime();


function listScraps($scrapdir){

	$dirhandle = dir($scrapdir);
	$files = array();

	echo "\n<div class=\"scrap_list_wrapper\">";

	$i = 0;
	while (false !== ($file = $dirhandle->read())) {
		if ($file !== ".." && $file !== "."){
			$files[$i] = $file;
			$i=$i+1;
		}
	}
	echo <<<INPUT
<div class="scrap_wrapper2">
	<form name="input" action="index.php" method="post">
		Scrap:
		<input type="text" name="write" />
		<input type="submit" value="Submit" />
	</form>
</div>
INPUT;


	$scraps = array();
	sort($files);
	$files = array_reverse($files);
	for ($i=0; $i < count($files); $i=$i+1){
		$filename = $files[$i];
		$curr_file = file($scrapdir."/".$filename);
		$timestamp = rtrim($curr_file[0]);
		$scraps[$i] = "";
		for ($j=1; $j < count($curr_file); $j=$j+1){
			$scraps[$i] = $scraps[$i] . $curr_file[$j];
		}
		$scraps[$i] = array("time" => $timestamp, "scrap" => $scraps[$i]);
	}

	for ($i=0; $i < count($scraps); $i=$i+1){
		$scraps[$i]["scrap"] = str_replace("\n", "<br/>", $scraps[$i]["scrap"]);
		$scraps[$i]["scrap"] = preg_replace("/(https?:\/\/[^ \n]+)/", "<a href=\"$1\">$1</a>", $scraps[$i]["scrap"]);
		$rowcol = ($i%2)+1;
		$date = date("d.m.Y H:i", $scraps[$i]["time"]);
		echo <<<SCRAP
<div class="scrap_wrapper$rowcol">
	<div class="scrap_date">
		<a href="delete.php?del="$scraps[$i]["time"]">[delete]</a> - $date
	</div>;
	<div class="scrap_content">
		$scraps[$i]["scrap"]
	</div>
</div>
SCRAP;
	}
	echo "</div>";
}

function writeScrap($scrap, $scrapdir){
	$time = time();
	$filehandle = fopen($scrapdir."/".$time, 'w');
	return fwrite($filehandle, $time."\n".$scrap);
}

if ($_POST["write"]){
	$scrap = htmlentities(str_replace("\\\"", "\"", $_POST["write"]));
	if (writeScrap($scrap, $scrapdir))
		echo "success".$_POST["write"];
	else
		echo "fail";
	listScraps($scrapdir);
}
else{
	listScraps($scrapdir);
}

$EndAt = time()+microtime();
$TimeElapsed = round($EndAt - $StartAt,4);
echo "loaded in ".$TimeElapsed." s";
?>
