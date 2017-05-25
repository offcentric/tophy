<?php
$admin_props_xml = $_SESSION['filepath'] . ".admin_properties";

//open admin props xml docment
$dom_props = new DOMDocument();
if(!$dom_props->load(realpath($admin_props_xml))){
	echo "error!!! xml doc invalid or not found at ";
	echo realpath($admin_props_xml);
	exit;
}
// get new xpath context
$xpath_props = new DOMXPath($dom_props);

$xpresult_admin_props = $xpath_props->query("/admin_properties")->item(0);

$_SESSION['admin_folder_name'] = getElementValue($xpresult_admin_props, "folder_name", 0, "__admin");
$_SESSION['admin_pass'] = getElementValue($xpresult_admin_props, "admin_pass", 0, "");

//open gallery config xml docment
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
if(!$dom->load(realpath($GLOBALS['config_xmlfile']))){
	echo "error!!! xml doc invalid or not found at ";
	echo realpath($GLOBALS['config_xmlfile']);
	exit;
}
$dom->formatOutput = true;
// get new xpath context
$xpath = new DOMXPath($dom);
$gallery_node = $xpath->query("gallery")->item(0);

$_SESSION['pagetype'] = "form";

$GLOBALS['errs'] = array();
$GLOBALS['general'] = array();
$GLOBALS['customization'] = array();
$GLOBALS['aliases'] = array();
$GLOBALS['books'] = array();
$GLOBALS['navigation'] = array();
$GLOBALS['metadata'] = array();

$main_logo = getElementValue($gallery_node, "logo_name", 0, "");
$logo_align = getElementValue($gallery_node, "logo_align", 0, "");

$theme = getElementValue($gallery_node, "theme", 0, "");
if(count($xpath->query("gallery/custom_styles")) > 0){
	$custom_styles_node = $xpath->query("gallery/custom_styles")->item(0);
	$custom_body_bgcolor = getElementValue($custom_styles_node, "custom_body_bgcolor", 0, "666666");
	$custom_container_bgcolor = getElementValue($custom_styles_node, "custom_container_bgcolor", 0, "fafafa");
	$custom_body_textcolor = getElementValue($custom_styles_node, "custom_body_textcolor", 0, "222222");
	$custom_link_textcolor = getElementValue($custom_styles_node, "custom_link_textcolor", 0, "003388");
	$custom_link_hover_textcolor = getElementValue($custom_styles_node, "custom_link_hover_textcolor", 0, "0000FF");
	$custom_nav_textcolor = getElementValue($custom_styles_node, "custom_nav_textcolor", 0, "003388");
	$custom_nav_hover_textcolor = getElementValue($custom_styles_node, "custom_nav_hover_textcolor", 0, "0055DD");
	$custom_nav_active_textcolor = getElementValue($custom_styles_node, "custom_nav_active_textcolor", 0, "FFFFFF");
	$custom_nav_active_bgcolor = getElementValue($custom_styles_node, "custom_nav_active_bgcolor", 0, "003388");
	$custom_bar_bgcolor = getElementValue($custom_styles_node, "custom_bar_bgcolor", 0, "003388");
	$custom_bar2_bgcolor = getElementValue($custom_styles_node, "custom_bar2_bgcolor", 0, "002277");
	$custom_bar_textcolor = getElementValue($custom_styles_node, "custom_bar_textcolor", 0, "ffffff");
	$custom_main_font_family = getElementValue($custom_styles_node, "custom_main_font_family", 0, "Arial, sans-serif");
	$custom_main_font_size = getElementValue($custom_styles_node, "custom_main_font_size", 0, "1em");
	$custom_nav_font_family = getElementValue($custom_styles_node, "custom_nav_font_family", 0, "Arial, sans-serif");
	$custom_nav_font_size = getElementValue($custom_styles_node, "custom_nav_font_size", 0, "1.3em");
}

$page_layout = getElementValue($gallery_node, "page_layout", 0, "");
$startpage = getElementValue($gallery_node, "startpage", 0, "");
$gallery_display = getElementValue($gallery_node, "gallery_display", 0, "");
$gallery_thumb_ratio = getElementValue($gallery_node, "gallery_thumb_ratio", 0, "");
$gallery_navigation_style = getElementValue($gallery_node, "gallery_navigation_style", 0, "");
$navigation_image_format = getElementValue($gallery_node, "navigation_image_format", 0, "");
?>