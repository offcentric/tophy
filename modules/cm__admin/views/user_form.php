<?php
$_SESSION['pagetype'] = "form";

if($_SESSION['cm__admin']['privileges'] > 1){
	if($_SESSION['cm__admin']['privileges'] < 3 && $GLOBALS['user']->name != $_SESSION['user'] || ($GLOBALS['user']->name == "admin" && $_SESSION['user'] != "admin")){
		header("Location:" . $_SESSION['journal_webpath_public']. "401");
		exit;
	}
}

if(isset($_REQUEST["newuser"])){
	$_SESSION['pagetitle'] = "Add new user";
	$form_action = "add_user";
}else{
	$_SESSION['pagetitle'] = "Edit user";
	$form_action = "edit_user";
}

include JOURNALBASEPATH . '/views/partial/start.php';
if($GLOBALS['messages']['notice'] != "") echo "<div class=\"messages\">" . $GLOBALS['messages']['notice'] . "</div>";
if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";
?>

<div class="title"><h2><?php echo  $_SESSION['pagetitle']; if(!isset($_REQUEST["newuser"])) echo " : " . $GLOBALS['user']->name ?></h2></div>
<form class="validate" id="user_form" name="user_form" action="<?php echo $_SESSION['admin_root'] . $form_action; if(!isset($_REQUEST["newuser"]) && $_REQUEST["action"] != "add_user") echo "/" . $GLOBALS['user']->name ?>" method="post">
	<input type="hidden" name="action" value="<?php echo $form_action ?>" />
	<input type="hidden" name="id" value="<?php echo $GLOBALS['user']->id ?>" />
	<?php if($form_action == "add_user"){ ?>
		<div class="formrow clearfix">
			<div class="errordiv clearfix"><?php if($GLOBALS['errs']['user'] != "") echo "<div><p>" . $GLOBALS['errs']['user'] . "</p></div>"; ?></div>
			<label for="user">Username<span class="color">*</span></label>
			<input type="text" class="text" name="user" maxlength="12" value="<?php echo $GLOBALS['user']->name ?>" />
		</div>
		<div class="formrow clearfix">
			<div class="errordiv clearfix"><?php if($GLOBALS['errs']['password'] != "") echo "<div><p>" . $GLOBALS['errs']['password'] . "</p></div>"; ?></div>
			<label for="user">Password<span class="color">*</span></label>
			<input type="password" class="text" maxlength="15" name="password" />
		</div>
		<div class="formrow clearfix">
			<div class="errordiv clearfix"><?php if($GLOBALS['errs']['password_confirm'] != "") echo "<div><p>" . $GLOBALS['errs']['password_confirm'] . "</p></div>"; ?></div>
			<label for="user">Password again<span class="color">*</span></label>
			<input type="password" class="text" maxlength="15" name="password_confirm" />
		</div>
	<?php } ?>
	<div class="formrow clearfix">
		<div class="errordiv clearfix"><?php if($GLOBALS['errs']['email'] != "") echo "<div><p>" . $GLOBALS['errs']['email'] . "</p></div>"; ?></div>
		<label for="email">Email address<span class="color">*</span></label>
		<input type="text" class="text" name="email" value="<?php echo $GLOBALS['user']->email ?>" <?php if($GLOBALS['user']->name == "admin" && $_SESSION['user'] != "admin") echo " disabled=\"disabled\""; ?>/>
	</div>
	<div class="formrow clearfix">
		<div class="errordiv clearfix"><?php if($GLOBALS['errs']['timezone'] != "") echo "<div><p>" . $GLOBALS['errs']['timezone'] . "</p></div>"; ?></div>
		<label for="email">Timezone<span class="color">*</span></label>
		<select name="timezone">
		<?php echo get_timezone_list($GLOBALS['user']->timezone) ?>
		</select>
	</div>
<?php if($_SESSION['cm__admin']['privileges'] > 2){ ?>
	<div class="formrow clearfix">
		<label for="is_active">Account active</label>
		<input type="checkbox" class="checkbox"<?php if($GLOBALS['user']->name == "admin") echo " disabled=\"disabled\""; ?> name="is_active"<?php if($GLOBALS['user']->is_active) echo " checked=\"checked\""; ?> />
	</div>
	<div class="formrow clearfix">
		<label for="is_admin">Admin</label>
		<input type="checkbox" class="checkbox"<?php if($GLOBALS['user']->name == "admin") echo " disabled=\"disabled\""; ?>  name="is_admin"<?php if($GLOBALS['user']->is_admin) echo " checked=\"checked\""; ?>" />
	</div>
<?php } ?>
	<div class="submitrow clearfix">
		<input type="submit" class="submit" value="Save Changes" />
	</div>
</form>

<?php if(!isset($_GET["minimal"])) include JOURNALBASEPATH . '/views/partial/footer.php'; ?>
<?php include JOURNALBASEPATH . '/views/partial/end.php'; ?>