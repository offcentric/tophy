<?php
/********************************************************/
/* file: 		module_path_translator.php 				*/
/* module:		cm__journal								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		05/07/2011								*/
/* version:		0.1										*/
/* description:	resolves all paths within the 			*/
/*				Journal core module						*/
/********************************************************/

if(count($matches[1]) > 1){
	switch($matches[1][1]){
		case "page":
			$GLOBALS['cm__journal']['display'] = "listing";
			$GLOBALS['cm__journal']['page'] = $matches[1][2];
			break;
		case "tag":
			$GLOBALS['cm__journal']['display'] = "listing";
			$GLOBALS['cm__journal']['filter'] = "tag";
			$GLOBALS['cm__journal']['tag'] = $matches[1][2];
			if(count($matches[1] > 3))
				$GLOBALS['cm__journal']['page'] = $matches[1][3];
			break;
		case "date":
		// RewriteRule ^date/([0-9]{4})/([0-9]{1,2})/?$ gate.php?template_folder=views&template=main&model=listing&display=listing&filter=month&y=$1&m=$2 [L,QSA]
		// RewriteRule ^date/([0-9]{4})/([0-9]{1,2})/page/([0-9]+)/?$ gate.php?template_folder=views&template=main&model=listing&display=listing&filter=month&y=$1&m=$2&page=$3 [L,QSA]
			break;
		case "archive":
		// RewriteRule ^archive/?$ gate.php?template_folder=views&template=main&model=listing&display=listing&filter=archive [L,QSA]
		// RewriteRule ^archive/page/([0-9]+)/?$ gate.php?template_folder=views&template=main&model=listing&display=listing&filter=archive&page=$1 [L,QSA]
			break;
		case "search":
		// RewriteRule ^search/?$ gate.php?template_folder=views&template=main&display=search&action=search [L,QSA]
		// RewriteRule ^search/([^/]+)/?$ gate.php?template_folder=views&template=main&model=listing&display=listing&searchstring=$1 [L,QSA]
		// RewriteRule ^search/([^/]+)/page/([0-9]+)/?$ gate.php?template_folder=views&template=main&model=listing&display=listing&searchstring=$1&page=$2 [L,QSA]
			break;
		case "entry":
			$GLOBALS['cm__journal']['display'] = "entry";
			$GLOBALS['cm__journal']['entry_id'] = $matches[1][2];
			break;
		case "alltags":
			$GLOBALS['cm__journal']['display'] = "taglist";
			$_SESSION['pagetitle'] = "All tags";
			if(count($matches[1] > 2)){
				$GLOBALS['cm__journal']['tags']['sortby'] = $matches[1][2];			
			}else{
				$GLOBALS['cm__journal']['tags']['sortby'] = "name";
			}
			break;
		case "user":
		// RewriteRule ^user/([^/]*)/?$ gate.php?template_folder=views&template=main&display=listing&model=listing&filter=user_poster&user=$1 [L,QSA]
		// RewriteRule ^user/([^/]*)/created/?$ gate.php?template_folder=views&template=main&display=listing&model=listing&filter=user_poster&user=$1 [L,QSA]
		// RewriteRule ^user/([^/]*)/edited/?$ gate.php?template_folder=views&template=main&display=listing&model=listing&filter=user_editor&user=$1 [L,QSA]
			break;
		case "allusers":
		// RewriteRule ^allusers/?$ gate.php?template_folder=views&template=allusers&model=userlist [L,QSA]
			break;
		default:
			break;
	}
}else{
	/* START PAGE - Show default display mode */
	$GLOBALS['cm__journal']['display'] = $_SESSION['cm__journal']['startpage'];
}

$filepaths[page_template][custom_theme] = THEMESBASEPATH . $_SESSION['theme'] . "/" . $GLOBALS['system_pathnames']['modules'] . "/" . $_SESSION['cm__journal']['module_name'] . "/views/main.php";
$filepaths[page_template][module] = MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/views/main.php";
$filepaths[page_template][common] = COREBASEPATH . "views/main.php";

if(file_exists($filepaths[page_template][custom_theme])){
	$GLOBALS['filepath'] = $filepaths[page_template][custom_theme];
}elseif(file_exists($filepaths[page_template][module])){
	$GLOBALS['filepath'] = $filepaths[page_template][module];
}elseif(file_exists($filepaths[page_template][common])){
	$GLOBALS['filepath'] = $filepaths[page_template][common];
}

if($GLOBALS['filepath'] != ""){
	$GLOBALS['filefound'] = true;
}
?>