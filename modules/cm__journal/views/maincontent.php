<?php
/********************************************************/
/* file: 		maincontent.php 						*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		07/07/2011								*/
/* version:		0.1										*/
/* description:	Generates HTML to show a the main 		*/
/*				content column							*/
/********************************************************/

if($GLOBALS['cm__journal']['filter'] == "month"){?>
	<div class="title"><h2><?php echo $GLOBALS['cm__journal_listing']->monthname ?> <?php echo $GLOBALS['cm__journal_listing']->year ?></h2></div>
<?php }else if($GLOBALS['cm__journal']['filter'] == "archive"){?>
	<div class="title"><h2>Older Entries</h2></div>
<?php }

if($_REQUEST["message"] == "entry_added") $GLOBALS['messages']['notice'] .= "Your journal entry has been successfully added.";
if($_REQUEST["message"] == "entry_edited") $GLOBALS['messages']['notice'] .= "Your journal entry has been successfully edited.";
if(isset($_GET["nocookies"])) $GLOBALS['messages']['error'] .= "You must have cookies enabled for " . $_SESSION['journal_url'] . " in order to access the admin page.";

if($GLOBALS['messages']['notice'] != "") echo "<div class=\"messages\">" . $GLOBALS['messages']['notice'] . "</div>";
if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";

include MODULESBASEPATH . "cm__journal/views/" . $GLOBALS['cm__journal']['display'] . ".php";

if($GLOBALS['cm__journal']['display'] == "listing" && count($GLOBALS['cm__journal_listing']->nodeset->aData) == 0 && $_REQUEST["action"] != "delete_entry"){ ?>
	<div class="errorheader">There are no results to display!</div>
<?php } ?>
