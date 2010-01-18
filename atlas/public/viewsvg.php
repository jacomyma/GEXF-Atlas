<?php
	header("Content-type: image/svg+xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">';
	
	echo str_replace('<svg', '<svg xmlns="http://www.w3.org/2000/svg" xml:space="default"', str_replace('\"', '"', $_POST['svg']));
?>