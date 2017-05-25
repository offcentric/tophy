<?php
$_SESSION['pagename'] = "confirm";
$_SESSION['pagetitle'] = "Delete entry";

include JOURNALBASEPATH . '/views/partial/start.php';
?>
			<div id="maincontainer" class="entry clearfix">
				<div class="title"><h2>Delete Journal Entry</h2></div>
				<div class="confirm_form clearfix">
					<p>You are about to delete:</p>
					<h3><?php echo $GLOBALS['entry']->title ?> <small>(<?php echo date('j F Y',$GLOBALS['entry']->timestamp) ?>)</small></h3>
					<p>Are you sure you want to delete this journal entry?</p>
					<form id="delete_yes" action="<?php echo $_SESSION['cm__journal']['webpath'] ?>" method="post">
						<input type="hidden" name="action" value="delete_entry" />
						<input type="hidden" name="id" value="<?php echo $GLOBALS['entry']->id ?>" />
						<input type="submit" class="submit" value="Yes" />
					</form>

					<form id="delete_no" action="<?php echo $_SERVER['HTTP_REFERER'] ?>" method="post">
						<input type="submit" class="submit" value="No" />
					</form>
				</div>
				<div class="bar"></div>
<?php include JOURNALBASEPATH . '/views/partial/end.php';?>
