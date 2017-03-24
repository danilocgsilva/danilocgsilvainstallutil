<?php
header("Content-Type: text/plain; charset=utf8");

function debugres($res) {
	return "\n -- " . $res . " -- \n";
}

function loop_through_result($array_result, $prefix) {
	echo "loop_through_result: \n";
	echo debugres(gettype($array_result));
	echo debugres($prefix);

	foreach($array_result as $result) {
		$dir = file_or_dir($result);
		if ($dir) {
			GLOBAL $base_dir;
			GLOBAL $ch;
			$outer_dir = $result->name . "/";
			$new_url_to_fecth = $base_dir . $outer_dir;
			
			echo debugres($new_url_to_fecth);
			
			$dir = curl_fetch_result($ch, $new_url_to_fecth);
			loop_through_result($dir, $outer_dir);
			//echo "\nloop vivo";
		} else {
			echo $prefix . $result->name . "\n";
		}
	}
}

function file_or_dir($entry) {
	$switch_entry = $entry->type;
	switch ($switch_entry) {
		case "file":
			return 0;
			break;
		case "dir":
			return 1;
			break;
		default:
			return 2;
	}
}

function curl_fetch_result($ch, $url) {
	curl_setopt($ch, CURLOPT_URL, $url);
	$conteudo = curl_exec($ch);
	return json_decode($conteudo);
}

$base_dir = 'https://api.github.com/repos/danilocgsilva/backupsite/contents/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

$dir = curl_fetch_result($ch, $base_dir);

loop_through_result($dir, 'programfiles');

echo 'I am alive!';