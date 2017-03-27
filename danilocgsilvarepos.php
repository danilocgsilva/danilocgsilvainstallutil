<?php
header("Content-Type: text/plain; charset=utf8");

function debugres($res) {
	return "\n -- " . $res . " -- \n";
}

function loop_through_result($array_result, $prefix) {

	echo debugres($prefix);

	foreach($array_result as $result) {
		$dir = file_or_dir($result);
		if ($dir) {
			$outer_dir = "/" . $result->name;
			$new_url_to_fecth = $prefix . $outer_dir;
			$json_manip = curl_fetch_result($new_url_to_fecth);
			$object_response = json_decode($json_manip);
			loop_through_result($object_response, $new_url_to_fecth);
		} else {
			echo "É arquivo: " . $prefix . "/" . $result->name . "\n";
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

/**
 * @return {json}
 */
function curl_fetch_result($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$conteudo = curl_exec($ch);
	// echo "Erro no curl: " . curl_error($ch) . "\n";
	return $conteudo;
}

$base_dir = 'https://api.github.com/repos/danilocgsilva/backupsite/contents';
$json_fetched = curl_fetch_result($base_dir);
$json_manupulable = json_decode($json_fetched);
loop_through_result($json_manupulable, $base_dir);

echo 'I am alive!';