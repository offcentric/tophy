<?php
/********************************************************/
/* file: 		module.php 								*/
/* module:		CM__ADMIN								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	content loader for 						*/
/*				admin core module	 					*/
/********************************************************/


if($_SESSION['cm__admin']['privileges'] < 1) $_SESSION['cm__admin']['privileges'] = 1;
if($_SESSION['cm__admin']['privileges'] > 1){
	if($_GET["admin"]){
		if(!isset($_SESSION['admin_root']) || $_SESSION['admin_root'] == ""){
			$_SESSION['admin_root'] = $_SESSION['cm__journal']['webpath'] . $GLOBALS['admin_path'] . "/";
		}
		$_SESSION['cm__journal']['webpath'] = $_SESSION['admin_root'];
	}else{
		$_SESSION['cm__journal']['webpath'] = $_SESSION['journal_webpath_public'];
	}
}

?>