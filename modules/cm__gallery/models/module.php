<?php
/********************************************************/
/* file: 		module.php 								*/
/* module:		CM__GALLERY								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	content loader for 						*/
/*				gallery core module 					*/
/********************************************************/

require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/gallery_functions.php");
require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/gd_functions.php");
require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/pjmt/EXIF.php");

if(isset($_POST['action'])){
	require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/models/action.php");
}

?>