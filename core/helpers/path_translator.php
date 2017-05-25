<?php
/********************************************************/
/* file: 		path_translator.php 					*/
/* module:		COMMON CODEBASE							*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:											*/
/*******************************************************/
$original_address = $_SERVER['REQUEST_URI'];

$request_theme = "";
$request_module = "";
$page_template_folder = "";
$page_template_name = "";
$request_image = "";
$request_book = "";
$request_bookpage = "";
$request_photo = "";
$request_display_mode = "";
$request_thumb_ratio = "";

$request_help_section = "";
$request_help_page = "";

$request_theme = @$_GET['t'];
$request_module = @$_GET['m'];

$imagefile = "";
$GLOBALS['filetype'] = "";
$GLOBALS['filepath'] = "";
$GLOBALS['filefound'] = false;

$_SESSION['page_modules'] = array();

/* IMAGE, SCRIPT & STYLESHEET REQUESTS [priority: local folder -> custom theme -> default theme -> module codebase -> common codebase] */
if(preg_match("~^/(" . $GLOBALS['system_pathnames']['images'] . ")/([^?]+)~i", $original_address, $matches) == 1){
	$GLOBALS['filetype'] = $matches[1];
	$GLOBALS['filepath'] = get_filepath($matches, $GLOBALS['filetype'], $request_theme, $request_module);
	if($GLOBALS['filepath'] != ""){
		write_content_type_header(substr($GLOBALS['filepath'], strrpos($GLOBALS['filepath'], ".")+1));
		$handle = fopen($GLOBALS['filepath'], "r");
		echo fread($handle, filesize($GLOBALS['filepath']));
		fclose($handle);
		$GLOBALS['filefound'] = true;
	}
}elseif(preg_match("~^/(" . $GLOBALS['system_pathnames']['scripts'] . ")/([^?]+)~i", $original_address, $matches) == 1 || preg_match("~^/(" . $GLOBALS['system_pathnames']['styles'] . ")/([^?]+)~i", $original_address, $matches) == 1){
	$GLOBALS['filetype'] = $matches[1];
	$GLOBALS['filepath'] = get_filepath($matches, $GLOBALS['filetype'], $request_theme, $request_module);
	if($GLOBALS['filepath'] != "") $GLOBALS['filefound'] = true;
}elseif(preg_match_all("~/([^/?]+)~i", $original_address, $matches) !== false){
	// HOMEPAGE
	if(preg_match("~^/?(\?.+=.+)?$~i", $original_address, $clean_address)){
		foreach($_SESSION['homepage_items'] as $item){
			$_SESSION['page_modules'][count($_SESSION['page_modules'])] = $item['module'];
			$view = $item['view'];
			$filepaths['custom_theme'] = THEMESBASEPATH . $_SESSION['theme'] . "/" . $GLOBALS['system_pathnames']['modules'] . "/" . $item['module'] . "/views/" . $view . ".php";
			$filepaths['module'] = MODULESBASEPATH . $item['module'] . "/views/" . $view . ".php";
			if(file_exists($filepaths['custom_theme'])){
				$GLOBALS['filepath'] = $filepaths['custom_theme'];
			}elseif(file_exists($filepaths['module'])){
				$GLOBALS['filepath'] = $filepaths['module'];
			}
			if($GLOBALS['filepath'] != "") $GLOBALS['filefound'] = true;
		}
	}else{
		// first let's see if the pathname corresponds to a base path of one of the active modules
		$x = 0;
		$_SESSION['module'] = null;

		foreach($_SESSION['module_paths'] as $path){
			if($path == $matches[1][0] . "/"){
				$_SESSION['page_modules'][0] = $_SESSION['modules_enabled'][$x];
				$_SESSION['module'] = $_SESSION['page_modules'][0];
				$module_path_translator = MODULESBASEPATH . $_SESSION['module'] . "/helpers/module_path_translator.php";	
				break;
			}
			$x++;
		}

		if($_SESSION['module'] != $_SESSION['cm__gallery']['module_name']){
			$_SESSION['cm__gallery']['book'] = "";
			$_SESSION['cm__gallery']['bookpage'] = "";
		}
		if(@file_exists($module_path_translator)){
			include $module_path_translator;
			$GLOBALS['filefound'] = true;
		}
		// finally check for custom pages
		if(!$GLOBALS['filefound']){
			include MODULESBASEPATH . $_SESSION['cm__pages']['module_name'] . "/helpers/module_path_translator.php";
		}
	}
}
?>
