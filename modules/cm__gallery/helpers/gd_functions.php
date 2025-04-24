<?php
function resizeimg($name,$filename,$new_w,$new_h,$ratio){
	if(file_exists($name)){
		$fileext = substr($name, strpos($name, "."));
		if (preg_match('/jpg|jpeg/i',$fileext)){
			$src_img = imagecreatefromjpeg($name);
		}
		if (preg_match('/png/i',$fileext)){
			$src_img=imagecreatefrompng($name);
		}
		$old_w = imageSX($src_img);
		$old_h = imageSY($src_img);
		$source_w = $old_w;
		$source_h = $old_h;
		$source_ratio = $source_w / $source_h;
		$target_ratio = $new_w / $new_h;
		if($ratio == "fixed"){
			if($source_ratio > $target_ratio){ // if source image is wider than the thumbnail should be
				$source_w = $source_h * $target_ratio;
			}elseif($source_ratio < $target_ratio){ // if source image is taller than the thumbnail should be
				$source_h = $source_w / $target_ratio;
			}
			$resize_h = $new_h;
			$resize_w = $new_w;
		}else if($ratio == "constrain_h"){ //retain original aspect ratio, constant height
			$resize_h = $new_h;
			$resize_w = $source_w/($old_h/$new_h);
		}else if($ratio == "constrain_w"){ //retain original aspect ratio, constant width
			$resize_w = $new_w;
			$resize_h = $source_h/($old_w/$new_w);
		}else if($ratio == "constrain_both"){ // constrain the thumbnails using both the witdth and height specified in the gallery_conf.xml so that thumbs never exceed either dimension.
			if ($old_w > $old_h) {
				$resize_w = $new_w;
				$resize_h = $resize_w / $source_ratio;
			}else if ($old_w < $old_h) {
				$resize_h=$new_h;
				$resize_w = $resize_h * $source_ratio;
			}else{
				if($source_ratio < $target_ratio){
					$resize_h=$new_h;
					$resize_w = $resize_h * $source_ratio;
				}else{
					$resize_w = $new_w;
					$resize_h = $resize_w / $source_ratio;
				}
			}
		}else{
			$resize_w = $_SESSION['container_width'] - 100;
			$resize_h = $resize_w / $source_ratio;
		}

        $source_w = (int)$source_w;
        $source_h = (int)$source_h;
        $resize_w = (int)$resize_w;
        $resize_h = (int)$resize_h;

		$source_x = (int)(($old_w - $source_w)/2);
		$source_y = (int)(($old_h - $source_h)/8);
		$dst_img = ImageCreateTrueColor($resize_w,$resize_h);
		imagecopyresampled($dst_img,$src_img,0,0,$source_x,$source_y,$resize_w,$resize_h,$source_w,$source_h);

		if (preg_match("/png/i",$fileext)){
			imagepng($dst_img,$filename); 
		} else {
		    imagejpeg($dst_img,$filename,90);
		}
		imagedestroy($dst_img); 
		imagedestroy($src_img); 
	}else{
		log_error($name . "does not exist! No thumbnail could be created.");
	}
}
