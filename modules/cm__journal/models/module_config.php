<?php
/********************************************************/
/* file: 		module_config.php 						*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		05/07/2011								*/
/* version:		0.1										*/
/* description:	coniguration loader for 				*/
/*				Journal core module 					*/
/********************************************************/

$reload_cm__journal_config = false;

// if XML has been updated, clear the session and repopulate it
if(filectime($_SESSION['cm__journal']['config_xml_file']) != @$_SESSION['cm__journal']['config_xml_file_time'] || filesize($_SESSION['cm__journal']['config_xml_file']) != @$_SESSION['cm__journal']['config_xml_file_size']){
	$reload_cm__journal_config = true;
}

function load_cm__journal_config(){
	/* OPEN CM__JOURNAL CONFIG XML */
	$GLOBALS['config_journal'] = new XmlFile(realpath($_SESSION['cm__journal']['config_xml_file']));
	$config_root_node = $GLOBALS['config_journal']->xpath->query("/config")->item(0);

	$_SESSION['cm__journal']['startpage'] = getElementValue($config_root_node, "startpage", 0, "listing");

	$_SESSION['cm__journal']['max_comment_length'] = getElementValue($config_root_node, "max_comment_length", 0, 3000);
	$_SESSION['cm__journal']['maxmonths_browse'] = getElementValue($config_root_node, "maxmonths_browse", 0, 24);
	$_SESSION['cm__journal']['entries_per_page'] = getElementValue($config_root_node, "entries_per_page", 0, 5);
	$_SESSION['cm__journal']['multiuser'] = getElementValue($config_root_node, "multiuser", 0, "0");
	$_SESSION['cm__journal']['show_comment_emails'] = getElementValue($config_root_node, "show_comment_emails", 0, "0");
	$_SESSION['cm__journal']['enable_publishing'] = getElementValue($config_root_node, "enable_publishing", 0, "1");

	$_SESSION['cm__journal']['showmood'] = getElementValue($config_root_node, "showmood", 0, "0");
	$_SESSION['cm__journal']['showlistening'] = getElementValue($config_root_node, "showlistening", 0, "0");
	$_SESSION['cm__journal']['showreading'] = getElementValue($config_root_node, "showreading", 0, "0");
	$_SESSION['cm__journal']['showwatching'] = getElementValue($config_root_node, "showwatching", 0, "0");
	$_SESSION['cm__journal']['showtags'] = getElementValue($config_root_node, "showtags", 0, "0");

		
	$_SESSION['cm__journal']['config_xml_file_size'] = filesize($_SESSION['cm__journal']['config_xml_file']);
	$_SESSION['cm__journal']['config_xml_file_time'] = filectime($_SESSION['cm__journal']['config_xml_file']);
}

//$reload_cm__journal_config = true;

if($reload_cm__journal_config){
	load_cm__journal_config();
}

?>