<?php
/********************************************************/
/* file: 		module.php 								*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	content loader for 						*/
/*				journal core module 					*/
/********************************************************/

require(COREBASEPATH . "helpers/sort_functions.php");
require(COREBASEPATH . "helpers/date_functions.php");
include MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/helpers/journal_functions.php";

if(!empty($_GET["sortby"])){
	$sortby = $_GET["sortby"];
}else{
	$sortby = "name";
}

$_SESSION['pagetitle'] = "";
$_SESSION['pagename'] = "listing";

 if(@$_GET['display'] == "entry"){
	$_SESSION['pagename'] = "entry";
	if($GLOBALS['entry']->title != "")
		$_SESSION['pagetitle'] = $GLOBALS['entry']->title;
	else
	$_SESSION['pagetitle'] = date('j F Y',$GLOBALS['entry']->timestamp);	
}elseif(@$_GET["filter"] == "month"){
	$_SESSION['pagetitle'] = $GLOBALS['cm__journal_listing']->monthname . " " . $GLOBALS['cm__journal_listing']->year;
}elseif(@$_GET["filter"] == "archive"){
	$_SESSION['pagetitle'] = "Archive";
}elseif(@$_GET["tag"] != ""){
	$_SESSION['pagetitle'] = $_GET["tag"];
}elseif(@$_GET["searchstring"] != ""){
	$_SESSION['pagetitle'] = $_GET["searchstring"];
}
if(@$_GET['page'] != ""){
	$_SESSION['pagetitle'] .= " (page " . $_GET["page"] . ")";	
}

$_SESSION['pagetype'] = @$GLOBALS['cm__journal']['display'];
$GLOBALS['messages'] = array();
$GLOBALS['messages']['notice'] = "";
$GLOBALS['messages']['error'] = "";
$GLOBALS['errs'] = array();

$GLOBALS['scriptpath_without_page'] = $_SERVER['REQUEST_URI'];
if(strpos($GLOBALS['scriptpath_without_page'], "page/") !== false) $GLOBALS['scriptpath_without_page'] = substr($GLOBALS['scriptpath_without_page'], 0, strpos($GLOBALS['scriptpath_without_page'], "page/"));
if(substr($GLOBALS['scriptpath_without_page'], strlen($GLOBALS['scriptpath_without_page'])-1) != "/") $GLOBALS['scriptpath_without_page'] .= "/";

if(@$_GET["admin"]){
	require(MODULESBASEPATH . $_SESSION['am__admin']['module_name'] . "/models/admin.php");
}

require(MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/models/tags.class.php");
$GLOBALS['tags'] = new Tags;
$GLOBALS['tags']->getAllTags();

if(isset($GLOBALS['cm__journal']['display'])){
	if($GLOBALS['cm__journal']['display'] == "listing"){
		require(MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/models/listing.class.php");
		$GLOBALS['cm__journal_listing'] = new Listing("");
	
		if($_GET["filter"] == "user_poster" || $_GET["filter"] == "user_editor"){
			require(MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/models/user.class.php");			
			$GLOBALS['user'] = new User;
		}
	}elseif($GLOBALS['cm__journal']['display'] == "entry"){
		require(COREBASEPATH . "models/entry.class.php");
		$GLOBALS['entry'] = new Entry;
		if($_GET["display"] == "entry"){
			$GLOBALS['entry']->getPreviousNode();
			$GLOBALS['entry']->getNextNode();		
		}
	}elseif($GLOBALS['cm__journal']['display'] == "comment"){
		require(MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/models/comment.php");	
	}elseif($GLOBALS['cm__journal']['display'] == "userlist"){
		require(MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/models/userlist.class.php");
		$GLOBALS['userlist'] = new Userlist;
	}elseif($GLOBALS['cm__journal']['display'] == "user"){
		require(MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/models/user.class.php");
		$GLOBALS['user'] = new User;
	}
}
?>