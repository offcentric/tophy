<?php
/********************************************************/
/* file: 		module_path_translator.php 				*/
/* module:		cm__gallery								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	resolves all paths within the 			*/
/*				Gallery core module						*/
/********************************************************/

if(count($matches[1]) > 1){
	$_SESSION['cm__gallery']['book'] = urldecode($matches[1][1]);
	if(count($matches[1]) > 2){
		if(preg_match("~^(thumbnails|strip|full)$~i", $matches[1][2]) != 1){
			if(preg_match("~^photo\:([^/]+)/?$~i", $matches[1][2], $submatches) == 1){
				$page_template_name = "photo";
				$request_photo = $submatches[1];
			}else{
				$page_template_name = "page";
				$_SESSION['cm__gallery']['bookpage'] = urldecode($matches[1][2]);
				if(count($matches[1]) > 3){
					if(preg_match("~^(thumbnails|strip|full)$~i", $matches[1][3]) != 1){
						if(preg_match("~^photo\:([^/]+)/?$~i", $matches[1][3], $submatches) == 1){
							$request_photo = $submatches[1];
						}
					}else{
						$request_display_mode = $matches[1][3];
						if(count($matches[1]) > 4){
							$request_thumb_ratio = $matches[1][4];
						}							
					}
				}
			}
		}else{
			$page_template_name = "book";
			$request_display_mode = $matches[1][2];
			if(count($matches[1]) > 3){
				$request_thumb_ratio = $matches[1][3];
			}
		}
	}else{
		if($matches[1][1] == "list"){
			$page_template_name = "list";
		}else{
			$page_template_name = "book";
		}
	}
}else{
	// show startpage
	$page_template_name = $_SESSION['cm__gallery']['startpage'];
}

if($request_display_mode && in_array($request_display_mode, array('strip','thumbnails','full')))
	$_SESSION['cm__gallery']['display'] = $request_display_mode;

if(@!$_SESSION['cm__gallery']['display'])
	$_SESSION['cm__gallery']['display'] = $_SESSION['cm__gallery']['default_display'];

if($request_thumb_ratio)
	$_SESSION['cm__gallery']['thumb_ratio'] = $request_thumb_ratio;

if(@!$_SESSION['cm__gallery']['thumb_ratio'])
	$_SESSION['cm__gallery']['thumb_ratio'] = $_SESSION['cm__gallery']['default_thumb_ratio'];




if($_SESSION['cm__gallery']['display'] != "thumbnails") $_SESSION['cm__gallery']['thumb_ratio'] = "";

if($page_template_name != ""){
	$filepaths['custom_theme'] = THEMESBASEPATH . $_SESSION['theme'] . "/" . $GLOBALS['system_pathnames']['modules'] . "/cm__gallery/views/" . $page_template_name . ".php";
	$filepaths['module'] = MODULESBASEPATH . "/cm__gallery/views/" . $page_template_name . ".php";
	if(file_exists($filepaths['custom_theme'])){
		$GLOBALS['filepath'] = $filepaths['custom_theme'];
	}elseif(file_exists($filepaths['module'])){
		$GLOBALS['filepath'] = $filepaths['module'];
	}
	if($GLOBALS['filepath'] != "") $GLOBALS['filefound'] = true;
}
