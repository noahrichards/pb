<?php

function drawRating($rating, $max, $type) {
	if($type == 0)
	{
		$image = imagecreatetruecolor(102,15);
		$back = ImageColorAllocate($image,250,250,250);
		$border = ImageColorAllocate($image,0,0,0);
		$fill = ImageColorAllocate($image,0,235,0);
		ImageFilledRectangle($image,0,0,101,14,$back);
		ImageFilledRectangle($image,1,1,$rating/$max * 100,14,$fill);
		ImageRectangle($image,0,0,101,14,$border);
		$textcolor = imagecolorallocate($image, 0, 0, 0);

		imagestring($image, 5, 35, 0, ($rating/$max * 100).'%', $textcolor);	
	}
	else
	{
		if($rating > $max) $rating = $max;
		
		$image = imagecreatetruecolor(10 * ($rating + 2) + 2,15);
		
		$back = ImageColorAllocate($image,250,250,250);
		$border = ImageColorAllocate($image,0,0,0);
		$fill = ImageColorAllocate($image,235,0,0);
		ImageFilledRectangle($image,0,0,10 * ($rating + 2) + 1,14,$back);
		ImageFilledRectangle($image,1,1,10 * $rating,14,$fill);
		ImageRectangle($image,0,0,10 * ($rating ) + 1,14,$border);
		$textcolor = imagecolorallocate($image, 0, 0, 0);

		imagestring($image, 5, 10 * ($rating + 1), 0, $rating, $textcolor);
	}
		

	imagepng($image);
	imagedestroy($image);
}


Header("Content-type: image/png");

$rating = (isset($_GET['rating'])) ? $_GET['rating'] : 0;

if($_GET['type'] == 'priority')
	drawRating($rating, 10, 1);	
else
	drawRating($rating, 100, 0);

?>