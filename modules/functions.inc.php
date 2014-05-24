<?php

# global functions

function GET($index) {
	if (isset($_GET[$index]))
		return $_GET[$index];
	return null;
}

function validate_get($value, $type) {
	switch($type) {
		case 'host':
			if (!preg_match('/^[\d\w\W]+$/u', $value))
				return NULL;
		break;
		case 'plugin':
		case 'type':
			if (!preg_match('/^\w+$/u', $value))
				return NULL;
		break;
		case 'pinstance':
		case 'tinstance':
			if (!preg_match('/^[\d\w-]+$/u', $value))
				return NULL;
		break;
	}

	return $value;
}

function validateRRDPath($base, $path) {
	$base=preg_replace('{/$}','',$base);
	if (is_link($base)) {
		$base=realpath($base);
	}

	$realpath = realpath(sprintf('%s/%s', $base, $path));

	if (strpos($realpath, $base) === false)
		return false;

	if (strpos($realpath, $base) !== 0)
		return false;

	if (!preg_match('/\.rrd$/', $realpath))
		return false;

	return $realpath;
}

function crc32hex($str) {
	return sprintf("%x",crc32($str));
}

function error_image($text="[ERROR] Permission denied") {
	global $CONFIG;
	$width=$CONFIG['width']+98;
	$height=$CONFIG['height']+72;

	header("Content-Type: image/png");
	// Création de l'image
	$im = imagecreatetruecolor($width, $height);
	
	// Création de quelques couleurs
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);

	imagefilledrectangle($im, 0, 0, $width, $height, $grey);
	imagefilledrectangle($im, 1, 1, ($width-2), ($height-2), $white);
	
	// Remplacez le chemin par votre propre chemin de police
	$font = DIR_FSROOT.'/fonts/UbuntuMono-R.ttf';
	
	// Ajout du texte
	imagettftext($im, 12, 0, 10, 20, $black, $font, $text);
	
	// Utiliser imagepng() donnera un texte plus claire,
	// comparé à l'utilisation de la fonction imagejpeg()
	imagepng($im);
	imagedestroy($im);
	exit;
}

function is_blank($value) {
	return empty($value) && !is_numeric($value);
}

function sort_plugins($hostpath, $plugins, $filters) {
	$plugins_ordered = array();
	$i=0;
	foreach ($plugins as $plugin) {
		foreach ($filters as $filter) {
			$myregex='#^('.$hostpath.'/)('.$filter->plugin.')(?:\-('.$filter->plugin_instance.'))?/('.$filter->type.')(?:\-('.$filter->type_instance.'))?\.rrd#';
			if (preg_match($myregex, $plugin)) {
				$plugins_ordered[$i]['index']=$filter->plugin_order;
				$plugins_ordered[$i]['content']=$plugin;
				break;
			}
		}
		if (empty($plugins_ordered[$i]['index'])) {
			$plugins_ordered[$i]['index']=99999;
			$plugins_ordered[$i]['content']=$plugin;
		}
		$i++;
	}
	asort($plugins_ordered);
	return $plugins_ordered;
}

function json_clean_decode($json_file, $assoc = true, $depth = 512, $options = 0) {
	// load file
	$json = utf8_encode(file_get_contents($json_file));

    // search and remove comments like /* */ and //
    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
   
    if(version_compare(phpversion(), '5.4.0', '>=')) {
        $json = json_decode($json, $assoc, $depth, $options);
    }
    elseif(version_compare(phpversion(), '5.3.0', '>=')) {
        $json = json_decode($json, $assoc, $depth);
    }
    else {
        $json = json_decode($json, $assoc);
    }

    return $json;
}

