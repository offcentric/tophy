<?php
/********************************************************/
/* file: 		module_config.php 						*/
/* module:		CM__GALLERY								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	coniguration loader for 				*/
/*				gallery core module 					*/
/********************************************************/

$reload_cm__gallery_config = false;

// if XML has been updated, clear the session and repopulate it
if(filectime(realpath($_SESSION['cm__gallery']['config_xml_file'])) != @$_SESSION['cm__gallery']['config_xml_file_time'] || filesize(realpath($_SESSION['cm__gallery']['config_xml_file'])) != @$_SESSION['cm__gallery']['config_xml_file_size']){
	$reload_cm__gallery_config = true;
}

function load_cm__gallery_config(){
	
	$_SESSION['cm__gallery']['folder_access'] = array();
	$_SESSION['cm__gallery']['folder_access_type'] = array();
	
	/* OPEN CM__GALLERY CONFIG XML */
	$GLOBALS['config_gallery'] = new XmlFile(realpath($_SESSION['cm__gallery']['config_xml_file']));
	$config_root_node = $GLOBALS['config_gallery']->xpath->query("/config")->item(0);
	
	$_SESSION['cm__gallery']['startpage'] = getElementValue($config_root_node, "startpage", 0, "showcase");

	/* SPLASH PAGE SETTINGS */
	$_SESSION['cm__gallery']['resize_splash_images'] = getElementValue($config_root_node, "resize_splash_images", 0, "0");
	$_SESSION['cm__gallery']['splash_image_resize_w'] = getElementValue($config_root_node, "splash_image_resize_w", 0, "400");
	$_SESSION['cm__gallery']['splash_image_resize_h'] = getElementValue($config_root_node, "splash_image_resize_h", 0, "400");
	$_SESSION['cm__gallery']['slideshow_interval'] = getElementValue($config_root_node, "slideshow_interval", 0, "5");

	$_SESSION['cm__gallery']['default_display'] = getElementValue($config_root_node, "gallery_display", 0, "thumbnails");
	$_SESSION['cm__gallery']['default_thumb_ratio'] = getElementValue($config_root_node, "gallery_thumb_ratio", 0, "fixed");
	$_SESSION['cm__gallery']['thumb_w'] = getElementValue($config_root_node, "gallery_thumb_w", 0, "120");
	$_SESSION['cm__gallery']['thumb_h'] = getElementValue($config_root_node, "gallery_thumb_h", 0, "120");
	$_SESSION['cm__gallery']['strip_h'] = getElementValue($config_root_node, "gallery_strip_h", 0, "400");
	$_SESSION['cm__gallery']['full_h'] = getElementValue($config_root_node, "gallery_full_h", 0, "700");
	$_SESSION['cm__gallery']['full_w'] = getElementValue($config_root_node, "gallery_full_w", 0, "900");
	$_SESSION['cm__gallery']['enable_thickbox'] = getElementValue($config_root_node, "enable_thickbox", 0, "1");
	$_SESSION['cm__gallery']['enable_slideshow'] = getElementValue($config_root_node, "enable_slideshow", 0, "1");
	$_SESSION['cm__gallery']['enable_control_panel'] = getElementValue($config_root_node, "enable_control_panel", 0, "1");
	$_SESSION['cm__gallery']['enable_exif_display'] = getElementValue($config_root_node, "enable_exif_display", 0, "1");
	$_SESSION['cm__gallery']['enable_all_books_list'] = getElementValue($config_root_node, "enable_all_books_list", 0, "0");
	$_SESSION['cm__gallery']['enable_book_thumbs'] = getElementValue($config_root_node, "enable_book_thumbs", 0, "1");

	$_SESSION['cm__gallery']['navigation_style'] = getElementValue($config_root_node, "gallery_navigation_style", 0, "text");
	$_SESSION['cm__gallery']['navigation_image_format'] = getElementValue($config_root_node, "navigation_image_format", 0, "gif");
	$_SESSION['cm__gallery']['resize_maxage'] = getElementValue($config_root_node, "gallery_resize_maxage", 0, 7);

	$gallery_navigation_node = $GLOBALS['config_gallery']->xpath->query("navigation", $config_root_node)->item(0);

	if($config_root_node->getElementsByTagName("navigation") && $config_root_node->getElementsByTagName("navigation")->length){
        $_SESSION['cm__gallery']['menu'] = array();
        $_SESSION['cm__gallery']['menu']['multilevel'] = getAttributeValue($config_root_node, "navigation", 0, "multilevel", "false");
        $_SESSION['cm__gallery']['menu']['dropdown'] = getAttributeValue($config_root_node, "navigation", 0, "dropdown", "false");
        $_SESSION['cm__gallery']['menu']['header'] = getElementValue($gallery_navigation_node, "header", 0, "");

        $xpresult_menu = $GLOBALS['config_gallery']->xpath->query("/config/navigation/item", $config_root_node);
        for ($x = 0; $x < $xpresult_menu->length; $x++) {
            $itemnode = $xpresult_menu->item($x);
            $_SESSION['cm__gallery']['menu']['items'][$x]['linked'] = getAttributeValue($itemnode, "title", 0, "linked", "true");
            $_SESSION['cm__gallery']['menu']['items'][$x]['title'] = getElementValue($itemnode, "title", 0, "");
            $_SESSION['cm__gallery']['menu']['items'][$x]['folder'] = getElementValue($itemnode, "folder", 0, "");
            $_SESSION['cm__gallery']['menu']['items'][$x]['coverimage'] = getElementValue($itemnode, "coverimage", 0, "");
            $_SESSION['cm__gallery']['menu']['items'][$x]['submenu_collapsible'] = getAttributeValue($itemnode, "subitems", 0, "collapsible", "false");
            $_SESSION['cm__gallery']['menu']['items'][$x]['subitem'] = array();
            $xpresult_subitems = $GLOBALS['config_gallery']->xpath->query("subitems/item", $itemnode);
            for ($y = 0; $y < $xpresult_subitems->length; $y++) {
                $subitemnode = $xpresult_subitems->item($y);
                $_SESSION['cm__gallery']['menu']['items'][$x]['subitem'][$y]['title'] = getElementValue($subitemnode, "title", 0, "");
                $_SESSION['cm__gallery']['menu']['items'][$x]['subitem'][$y]['folder'] = getElementValue($subitemnode, "folder", 0, "");
                $_SESSION['cm__gallery']['menu']['items'][$x]['subitem'][$y]['coverimage'] = getElementValue($subitemnode, "coverimage", 0, "");
            }
        }
    }

	$_SESSION['cm__gallery']['config_xml_file_size'] = filesize(realpath($_SESSION['cm__gallery']['config_xml_file']));
	$_SESSION['cm__gallery']['config_xml_file_time'] = filectime(realpath($_SESSION['cm__gallery']['config_xml_file']));
}

//$reload_cm__gallery_config = true;

if($reload_cm__gallery_config){
	load_cm__gallery_config();
}

$_SESSION['cm__gallery']['book'] = "";
$_SESSION['cm__gallery']['book_title'] = "";
$_SESSION['cm__gallery']['bookpage'] = "";
$_SESSION['cm__gallery']['bookpage_title'] = "";
