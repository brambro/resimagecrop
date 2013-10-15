<?php
// resimagecrop - version 0.0.1
// RESS based solution for cropping images for responsive design
// Most definitely a work in progress!

// Author - Ian Devlin
// Twitter - @iandevlin
// Web - iandevlin.com

// $image - the original image
// $x - the x position to begin the crop from (percentage)
// $y - the y position to begin the crop from (percentage)
// $w - the width of the amount to crop (pixels)
// $h - the height of the amount to crop (pixels)
// $sc - the scale factor (decimal)
//
// For example
// resimagecrop.php?image=img/image-to-use.jpg&x=15&y=20&w=550&h=450&sc=0.5
// begins the crop of img/image-to-use.jpg at 15% from the top and 20% from the left and will crop a width of 550x450 and then scale it by 0.5 (resulting image is 275x225)
//

// Collect parameters
$img = getParam('image');
$x = intval(getParam('x'));
$y = intval(getParam('y'));
$w = intval(getParam('w'));
$h = intval(getParam('h'));
$sc = getParam('sc');
if ($img) {
	// Get a handle to the original image
	$i = imagecreatefromjpeg($img);	
	// Get the dimensions of the original image
	$size = getimagesize($img);
	$origWidth = intval($size[0]);
	$origHeight = intval($size[1]);
	// Set the correct header
	header("Content-Type: image/jpg");
	// If x, y, w, and h parameters have been passed...
	if ($x && $y && $w && $h) {
		// Work out the x and y co-ordinates of the original image where the crop is to begin
		$cx = ($origWidth * $x) / 100;
		$cy = ($origHeight * $y) / 100;
		// Create a new image with the required width and height
		$ci = imagecreatetruecolor($w, $h);
		// Crop the image
		imagecopy($ci, $i, 0, 0, $cx, $cy, $origWidth, $origHeight);
		$i = $ci;
	}
	// If scaling is required...
	if ($sc) {
		if (!$w) $w = $origWidth;
		if (!$h) $h = $origHeight;
		// Define the width and height of the new scaled image
		$scw = $w * $sc;
		$sch = $h * $sc;
		// Scale the image
		$sci = imagecreatetruecolor($scw, $sch);
		imagecopyresampled($sci, isset($ci) ? $ci : $i, 0, 0, 0, 0, $scw, $sch, $w, $h);
		$i = $sci;
	}
	// Finish
	imagejpeg($i);
}

// Extracts parameters
function getParam($name) {
	if (isset($_GET[$name])) return htmlspecialchars($_GET[$name]);
	return '';
}