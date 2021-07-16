<?php

$keogramfile = "keogram.jpg";
$sourcedir = "./temp/";

$font = './arial.ttf';

print("Listing files...\n");
$files = Array();
$d = dir($sourcedir);
while (false !== ($entry = $d->read())) {
	if ($entry!="." && $entry!=".." && strpos($entry, ".jpg")!==false) {
		$files[] = $entry;
	}
}
$d->close();

sort($files);

if (count($files)>0) {
	print("Loading files...\n");
	$imagestats = getimagesize($sourcedir.$files[0]);
	$height = $imagestats[1];

	$keogram = imagecreate(count($files), $height+50);
	//$keogram = imagecreate($imagestats[0], $height+50);
	$white = imagecolorallocate($keogram, 255, 255, 255);
	$grey = imagecolorallocate($keogram, 128, 128, 128);
	$black = imagecolorallocate($keogram, 0, 0, 0);
	imagefilledrectangle($keogram, 0, $height, $imagestats[0], 50, $white);
	imagettftext($keogram, 20, 0, 0, $height+40, $grey, $font, "Keogram ".date("d-M-Y"));

	$oldfiletime = 0;
	$col = 0;
	foreach($files as $file) {
		$filetime = filemtime($sourcedir.$file);
		$image = imagecreatefromjpeg($sourcedir.$file);
		imagecopy($keogram, $image, $col, 0, $imagestats[0]/2, 0, 1, $imagestats[1]);
		imagedestroy($image);
		if (date("H", $filetime) != date("H", $oldfiletime)) {
			imagettftext($keogram, 8, 0, $col, $height+15, $grey, $font, date("H", $filetime));
		}
		$oldfiletime = $filetime;
		$col++;
	}
	print("Generating keogram...\n");
	imagejpeg($keogram, $keogramfile);
	imagedestroy($keogram);
}

?>
