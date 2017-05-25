<?php
if($_SESSION['cm__admin']['privileges'] > 1){
	if($_SESSION['cm__admin']['privileges'] < 3 && $GLOBALS['user']->name != $_SESSION['user'] || ($GLOBALS['user']->name == "admin" && $_SESSION['user'] != "admin")){
		header("Location:" . $_SESSION['journal_webpath_public']. "401");
		exit;
	}
}

$_SESSION['pagename'] = "";
$_SESSION['pagetitle'] = "Change password";

include JOURNALBASEPATH . '/views/partial/start.php';

if($_SESSION['user'] == "admin" && isset($_GET["initial_set"]))
	$GLOBALS['messages']['error'] = "You are logged in as admin with the default password. You must change your password before you can continue.";

if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";


if($_REQUEST["action"] == "change_password" && !$GLOBALS['has_errs']){ ?>
<br /><br />
<div class="messages"><?php echo $GLOBALS['messages']['notice'] ?></div>
<br /><br />
<?php }else{ ?>
<div class="title"><h2>Update password for <?php echo $GLOBALS['user']->name ?></h2></div>
<form id="change_password" name="change_password" class="validate clearfix" action="<?php echo $_SESSION['admin_root'] ?>change_password" method="post">
	<input type="hidden" name="action" value="change_password" />
	<input type="hidden" name="name" value="<?php echo $GLOBALS['user']->name ?>" />
	<div class="formrow clearfix">
		<div class="errordiv"><?php if($GLOBALS['errs']['new_password'] != "") echo "<div><p>" . $GLOBALS['errs']['new_password'] . "</p></div>"; ?></div>
		<label for="new_password">New password</label>
		<input type="password" class="password" maxlength="15" name="new_password" />
	</div>
	<div class="formrow clearfix">
		<div class="errordiv"><?php if($GLOBALS['errs']['new_password_confirm'] != "") echo "<div><p>" . $GLOBALS['errs']['new_password_confirm'] . "</p></div>"; ?></div>
		<label for="new_password_confirm">New password again</label>
		<input type="password" class="password" maxlength="15" name="new_password_confirm" />
	</div>
	<div class="formrow clearfix">
		<input type="submit" class="submit" value="Change password" />
	</div>
</form>
<?php } ?>
<?php if(!isset($_GET["minimal"])) include JOURNALBASEPATH . '/views/partial/footer.php'; ?>
<?php include JOURNALBASEPATH . '/views/partial/end.php'; ?>