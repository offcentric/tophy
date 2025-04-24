<?php
/********************************************************/
/* file: 		showcase.php 							*/
/* module:		CM__GALLERY								*/
/* theme:												*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		06/07/2011								*/
/* version:		0.1										*/
/* description:	Showcase page output					*/
/********************************************************/

$_SESSION['pagename'] = "";
$_SESSION['pagetype'] = "showcase";

include COREBASEPATH . "/views/partial/start.php";

$resize_path = $_SESSION['path_db'] . "__showcase";
$resize_webpath = $_SESSION['webpath_db'] . "__showcase";
$images_path = $_SESSION['media_path'] . @$book . "/";

if(!is_dir($_SESSION['path_db'])){
	mkdir($_SESSION['path_db']);
}
if(!is_dir($_SESSION['path_db'] . "__showcase/")){
	mkdir($_SESSION['path_db'] . "__showcase/");
}

$item_content['counter'] = 0;
$item_content['system_thumbnail_height'] = $_SESSION['cm__gallery']['thumb_w'];
$item_content['system_thumbnail_height'] = $_SESSION['cm__gallery']['thumb_h'];
$item_content['system_transparent_img'] = $GLOBALS['system_pathnames']['transparent_img'];

$showcase_html_template = get_html_template("showcase", $_SESSION['cm__gallery']['module_name']);
preg_match("~^(.*)\{\{item_start\}\}(.+)\{\{item_end\}\}(.*)$~s", $showcase_html_template, $showcase_html_components);

echo $showcase_html_components[1];

foreach($_SESSION['cm__gallery']['menu']['items'] as $menuitem){
	$folder = $menuitem['folder'];
	$item_content['title'] =  $menuitem['title'];
	$coverimage = $menuitem['coverimage'];
	$submenu_collapsible = $menuitem['submenu_collapsible'];

	if(count($menuitem['subitem']) > 0){
		foreach($menuitem['subitem'] as $subitem){
			$item_content['counter']++;
			$subitem_folder = "/" . $subitem['folder'];
			$coverimage = $subitem['coverimage'];
			$coverimage_path = $_SESSION['media_path'] . $folder . $subitem_folder . "/" . $coverimage;
			$timediff =  time() - (filectime($coverimage_path));
			$daysdiff = $timediff/3600/24;
			if(!file_exists($resize_path . "/" . $item_content['counter'] . ".jpg") || $daysdiff > $_SESSION['cm__gallery']['resize_maxage']){
				resizeimg($coverimage_path, $resize_path . "/" . $item_content['counter'] . ".jpg", $_SESSION['cm__gallery']['thumb_w'], $_SESSION['cm__gallery']['thumb_h'], "fixed");
			}
			$item_content['link'] = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $folder . $subitem_folder;
			$item_content['subtitle'] = "<br />" . $subitem['title'];
			$item_content['image'] = $resize_webpath . "/" . $item_content['counter'] . ".jpg";
			echo replace_template_placeholders($showcase_html_components[2], $item_content);
		}
	}else{
		$item_content['counter']++;
		$coverimage_path = $_SESSION['media_path'] . $folder . "/" . $coverimage;	
		if(!file_exists($resize_path . "/" . $item_content['counter'] . ".jpg") || $daysdiff > $_SESSION['cm__gallery']['resize_maxage']){
			resizeimg($coverimage_path, $resize_path . "/" . $item_content['counter'] . ".jpg", $_SESSION['cm__gallery']['thumb_w'], $_SESSION['cm__gallery']['thumb_h'], "fixed");
		}
		$item_content['link'] = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $folder;
		$item_content['subtitle'] = "";
		$item_content['image'] = $resize_webpath . "/" . $item_content['counter'] . ".jpg";
		echo replace_template_placeholders($showcase_html_components[2], $item_content);

	}
	
}

echo $showcase_html_components[3];
?>

<?php include COREBASEPATH . "/views/partial/end.php"; ?>
