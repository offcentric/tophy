<?php
$_SESSION['pagetype'] = "confirm";
$_SESSION['pagetitle'] = "Delete comment";

include JOURNALBASEPATH . '/views/partial/start.php';
?>
			<div id="maincontainer" class="entry clearfix">
				<div class="title"><h2>Delete Comment</h2></div>
				<div class="confirm_form clearfix">
					<p>You are about to delete a comment<br /><br/ >by: <strong><?php echo $postedby ?></strong><br />posted on: <?php echo date('j F Y',$timestamp) ?></p>
					<h3><?php echo $content ?></h3>
					<p>Are you sure you want to delete this comment?</p>
					<form id="delete_yes" action="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $_GET["id"] ?>" method="post">
						<input type="hidden" name="action" value="delete_comment" />
						<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
						<input type="hidden" name="comment_id" value="<?php echo $_GET["comment_id"] ?>" />
						<input type="submit" class="submit" value="Yes" />
					</form>

					<form id="delete_no" action="<?php echo $_SERVER['HTTP_REFERER'] ?>" method="post">
						<input type="submit" class="submit" value="No" />
					</form>
				</div>
				<div class="bar"></div>
<?php include JOURNALBASEPATH . '/views/partial/end.php';?>
