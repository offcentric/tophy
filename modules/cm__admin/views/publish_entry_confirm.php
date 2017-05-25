<?php
if($GLOBALS['entry']->published || ($_SESSION['cm__admin']['privileges'] < 4 && $GLOBALS['entry']->posted_by != $_SESSION['user'])){
	header("Location:" . $_SESSION['journal_webpath_public'] . "401");
}else{
	$_SESSION['pagename'] = "confirm";
	$_SESSION['pagetitle'] = "Publish entry";

	include JOURNALBASEPATH . '/views/partial/start.php';
?>
			<div id="maincontainer" class="entry clearfix">
				<div class="title"><h2>Publish Journal Entry</h2></div>
				<div class="confirm_form clearfix">
					<p>You are about to publish:</p>
					<h3><?php echo $GLOBALS['entry']->title ?> <small>(<?php echo date('j F Y',$GLOBALS['entry']->timestamp) ?>)</small></h3>
					<p>Are you sure you want to publish this journal entry?</p>
					<form id="delete_yes" action="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $GLOBALS['entry']->id ?>" method="post">
						<input type="hidden" name="action" value="publish_entry" />
						<input type="hidden" name="id" value="<?php echo $GLOBALS['entry']->id ?>" />
						<input type="submit" class="submit" value="Yes" />
					</form>

					<form id="delete_no" action="<?php echo $_SERVER['HTTP_REFERER'] ?>" method="post">
						<input type="submit" class="submit" value="No" />
					</form>
				</div>
				<div class="bar"></div>
<?php 
	include JOURNALBASEPATH . '/views/partial/end.php';
}
?>
