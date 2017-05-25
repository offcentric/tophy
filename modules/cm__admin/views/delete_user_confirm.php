<?php
$_SESSION['pagetype'] = "confirm";
$_SESSION['pagetitle'] = "Delete user";

include JOURNALBASEPATH . '/views/partial/start.php';
if($_REQUEST["action"] == "delete_user" && !$GLOBALS['has_errs']){
	if($GLOBALS['messages']['notice'] != "") echo "<div class=\"messages\">" . $GLOBALS['messages']['notice'] . "</div>";
	if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";
}else{ ?>
			<div id="maincontainer" class="entry clearfix">
				<div class="title"><h2>Delete User</h2></div>
				<div class="confirm_form clearfix">
					<p>You are about to delete the user <strong><?php echo $GLOBALS['user']->name ?></strong></p>
					<p>Are you sure you want to delete this user?</p>
					<form id="delete_yes" action="<?php echo $_SESSION['admin_root'] ?>delete_user/<?php echo $_GET["user"] ?>" method="post">
						<input type="hidden" name="action" value="delete_user" />
						<input type="hidden" name="user" value="<?php echo $GLOBALS['user']->name ?>" />
						<input type="hidden" name="id" value="<?php echo $GLOBALS['user']->id ?>" />
						<input type="submit" class="submit" value="Yes" />
					</form>

					<form id="delete_no" action="<?php echo $_SERVER['HTTP_REFERER'] ?>" method="post">
						<input type="submit" class="submit" value="No" />
					</form>
				</div>
<?php } ?>
				<div class="bar"></div>
<?php include JOURNALBASEPATH . '/views/partial/end.php';?>
