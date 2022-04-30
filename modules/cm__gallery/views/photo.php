<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/pjmt/EXIF.php");
$_SESSION['pagename'] = "photo";

$book = stripslashes($_GET["book"]);
$page = $_GET["p"];

$images_path = $_SESSION['media_path'] . $book . "/";
$images_webpath = $_SESSION['media_webpath'] . $book . "/";
if(preg_match("/https?:\/\//", $images_webpath, $matches) === false){
	$images_webpath = "http://" . $images_webpath;
}
if($page != ""){
	$images_path .= $page . "/";
	$images_webpath .= $page . "/";	
}
$file = stripslashes($images_path . $_GET["photo"]);
$filename = substr($file, strlen($images_path));

$title = str_replace(".jpg", "", $filename);
$title = str_replace("_", " ", $title);
$title = preg_replace("/([0-9]+\s)([a-zA-Z0-9\'\"\s]+)/", "$2", $title);
if($_SESSION['cm__gallery']['enable_exif_display']){
	$exif = get_EXIF_JPEG($file);
	//print_r($exif);
	$exif_date = get_EXIF_tag_value($exif, "Date and Time");
	$exif_shutterspeed = get_EXIF_tag_value($exif, "Exposure Time");
	$exif_aperture = get_EXIF_tag_value($exif, "Aperture F Number");
	$exif_make = get_EXIF_tag_value($exif, "Make (Manufacturer)");
	$exif_model = get_EXIF_tag_value($exif, "Model");
	$exif_flash = get_EXIF_tag_text_value($exif, "Flash");
	$exif_iso = get_EXIF_tag_text_value($exif, "ISO Speed Ratings");
	$exif_focal_lenth =  get_EXIF_tag_value($exif, "FocalLength");
	if($exif_date != ""){
		$exif_date =  substr($exif_date,8,2) . "/" . substr($exif_date,5,2) . "/" . substr($exif_date,0,4);
		$exif_details = add_info_to_panel("Date", $exif_date);
		$exif_details .= add_info_to_panel("Camera", $exif_model);	
		if(substr($exif_shutterspeed, strpos($exif_shutterspeed, "/")+1, 10) != 0){
			$calculated_shutter_speed = substr($exif_shutterspeed, 0, strpos($exif_shutterspeed, "/"))/(substr($exif_shutterspeed, strpos($exif_shutterspeed, "/")+1, 10));
			if($calculated_shutter_speed > 1){
				$exif_shutterspeed = $calculated_shutter_speed;
			}else if(substr($exif_shutterspeed, 0, strpos($exif_shutterspeed, "/")) == "10"){
				$exif_shutterspeed = (substr($exif_shutterspeed, 0, strpos($exif_shutterspeed, "/"))/10) . "/" . (substr($exif_shutterspeed, strpos($exif_shutterspeed, "/")+1, 10)/10);
			}
		}
		if($exif_shutterspeed != "" && $exif_aperture != "")$exif_details .= add_info_to_panel("Exposure",  $exif_shutterspeed . "s at f/" . $exif_aperture);
		else if($exif_aperture != "")$exif_details .= add_info_to_panel("Aperture", "f/" . $exif_aperture);
		else if($exif_shutterspeed != "")$exif_details .= add_info_to_panel("Exposure", $exif_shutterspeed . "s");			
		else $exif_details .= add_info_to_panel("Exposure", "");	
		//if($exif_flash != "") $exif_details .= add_info_to_panel("Flash", $exif_flash);	
		if($exif_iso != "")	$exif_details .= add_info_to_panel("ISO Rating", $exif_iso);
		if($exif_focal_lenth != "") $exif_details .= add_info_to_panel("Focal Length", $exif_focal_lenth."mm");	
		else $exif_details .= add_info_to_panel("Focal Length", $exif_focal_lenth);	
	}
}
?>

<?php include COREBASEPATH . "/views/partial/head.php"; ?>

<body id="photopage">
	<div id="image_container"><a href="<?php echo $images_webpath . $filename ?>"><img src="<?php echo $images_webpath . $filename ?>" title="<?php echo $title ?>" alt="<?php echo title ?>" /></a></div>
	<div id="title_bar"><a href="<?php echo $images_webpath . $filename ?>"><?php echo $title ?></a><br />
	<div id="exif_panel">
		<?php echo $exif_details ?>
	</div>
</body>
</html>