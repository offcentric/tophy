<?php
$_SESSION['pagetype'] = "form";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
			include COREBASEPATH . "/views/partial/head.php";
?>
<body class="" id="">
	<div class="container">
	<?php if($_SESSION['admin_pass'] == ""){ ?>
		<p>You have not yet set an administrator password. You must set a password in order to use the Tophy admin panel.</p>
	<?php } ?>
		<p>Please choose a password between 6 and 12 characters.</p>
		<form action="" method="post" id="set_password" class="validate clearfix">
		<?php if($_SESSION['admin_pass'] != ""){ ?>
			<fieldset class="formrow">
				<div class="errordiv"><?php if($GLOBALS['errs']['password_old'] != "") echo "<div><p>" . $GLOBALS['errs']['password_old'] . "</p></div>"; ?></div>
				<label for="password_old">Current Password:</label>
				<input type="password" name="password_old" />
			</fieldset>		
		<?php } ?>
			<fieldset class="formrow">
				<div class="errordiv"><?php if($GLOBALS['errs']['password'] != "") echo "<div><p>" . $GLOBALS['errs']['password'] . "</p></div>"; ?></div>
				<label for="password"><?php if($_SESSION['admin_pass'] != "") echo "New "; ?>Password:</label>
				<input type="password" name="password" />
			</fieldset>
			<fieldset class="formrow">
				<div class="errordiv"><?php if($GLOBALS['errs']['password_confirm'] != "") echo "<div><p>" . $GLOBALS['errs']['password_confirm'] . "</p></div>"; ?></div>
				<label for="password_confirm">Confirm <?php if($_SESSION['admin_pass'] != "") echo "New "; ?>Password:</label>
				<input type="password" name="password_confirm" />
			</fieldset>
			<div class="submitrow">
				<input type="submit" class="submit" value="Save" />
			</div>
			<input type="hidden" name="action" value="update_password" /> 
		</form>
	</div>
</body>
</html>

<script type="text/javascript">
form_id = "set_password";
function read_form_data(){
	Formpage.form_fields[form_id] = new Array();
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "password", label: 'Password', type: 'password', data_type: 'password', min_occurs: 1, min_length: 6, max_length: 12};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "password_confirm", label: 'Password confirmation', type: 'password', data_type: 'password', min_occurs: 1, min_length: 6, max_length: 12};
}
read_form_data();
</script>