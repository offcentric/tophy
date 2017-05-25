<?php
/********************************************************/
/* file: 		main.php 								*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		07/07/2011								*/
/* version:		0.1										*/
/* description:	Generates HTML to show a single 		*/
/*				journal entry							*/
/********************************************************/

include COREBASEPATH . "views/partial/start.php";
?>

			<div id="maincontainer" class="clearfix">
				<div class="listcol clearfix">
<?php include MODULESBASEPATH . "cm__journal/views/sidebar.php"; ?>
				</div>
				<div class="contentcol clearfix">
<?php include MODULESBASEPATH . "cm__journal/views/maincontent.php"; ?>
				</div>
			</div>
<?php include COREBASEPATH . "views/partial/end.php"; ?>