<?php
include './config/config.php';

if ($file = validateRRDPath($CONFIG['datadir'], $_SERVER['PATH_INFO'])) {
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($file));
	header("Expires: " .date(DATE_RFC822,strtotime($CONFIG['cache']." seconds")));
	if(ob_get_length()) ob_clean();
	flush();
	readfile($file);
} else {
	header('HTTP/1.0 403 Forbidden');
}
