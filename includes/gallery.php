	<!-- gallery -->
	<div><p><em>Click on any image to open gallery.</em></p></div>
	<?php 
	function gallery($page){
	$imgs = glob('images/gallery/'.$page.'/*.jpg');	
	foreach ($imgs as $img) 
		{
			$imgpath = "images/gallery/".$page;
			$thumbpath = "images/gallery/".$page."/thumbs";
			$thumb = str_replace ($imgpath,$thumbpath,$img);
			echo("<a href='$img' class='colorbox' rel='group' title='$img'><img src='$thumb' alt='click to popup gallery' border='0' class='gallerythumbs' /></a>");
		}
	}?>