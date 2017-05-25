<?php
/********************************************************/
/* file: 		module_config.php 						*/
/* module:		CM__PAGES								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	coniguration loader for 				*/
/*				pages core module 						*/
/********************************************************/

$reload_cm__pages_config = false;

// if XML has been updated, clear the session and repopulate it
if(filectime($_SESSION['cm__pages']['config_xml_file']) != @$_SESSION['cm__pages']['config_xml_file_time'] || filesize($_SESSION['cm__pages']['config_xml_file']) != @$_SESSION['cm__pages']['config_xml_file_size']){
	$reload_cm__pages_config = true;
}

function load_cm__pages_config(){
	/* OPEN CM__PAGES CONFIG XML */
	$GLOBALS['config_pages'] = new XmlFile(realpath($_SESSION['cm__pages']['config_xml_file']));
	$config_root_node = $GLOBALS['config_pages']->xpath->query("/config")->item(0);
	
	$_SESSION['cm__pages']['config_xml_file_size'] = filesize($_SESSION['cm__pages']['config_xml_file']);
	$_SESSION['cm__pages']['config_xml_file_time'] = filectime($_SESSION['cm__pages']['config_xml_file']);
}

//$reload_cm__gallery_config = true;

if($reload_cm__pages_config){
	load_cm__pages_config();
}
?>