<?php
/********************************************************/
/* file: 		book_login.php 							*/
/* module:		CM__GALLERY								*/
/* theme:												*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		28/11/2011								*/
/* version:		0.1										*/
/* description:	Log into private folder					*/
/********************************************************/

session_start();
$_SESSION['pagename'] = "";
$_SESSION['pagetype'] = "book";

include COREBASEPATH . "/views/partial/start.php";

if($_SESSION['cm__gallery']['login_error'] != '') echo '<div class="login_error">' . $_SESSION['cm__gallery']['login_error'] . '</div>';
echo get_html_template('book_login', $_SESSION['cm__gallery']['module_name']);

include COREBASEPATH . "/views/partial/end.php"; ?>
