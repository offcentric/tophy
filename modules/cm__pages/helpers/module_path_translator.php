<?php
/********************************************************/
/* file: 		module_path_translator.php 				*/
/* module:		CM__PAGES								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:											*/
/*******************************************************/

$_SESSION['module'] = "cm__pages";
$GLOBALS['filepath'] = get_page($original_address);
if($GLOBALS['filepath'] != "") $GLOBALS['filefound'] = true;
?>