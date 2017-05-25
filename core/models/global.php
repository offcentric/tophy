<?php
/********************************************************/
/* file: 		global.php 								*/
/* module:		COMMON CODEBASE							*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		06/07/2011								*/
/* version:		0.1										*/
/* description:	declaring data objects for active 		*/
/*				modules									*/
/********************************************************/

foreach($_SESSION['module_content_include_files'] as $module_content_include_file){
	include $module_content_include_file;
}
?>