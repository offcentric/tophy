<?php
$_SESSION['pagename'] = "login";
$_SESSION['pagetitle'] = "Login";

if(!$_SESSION['multiuser']){
	$GLOBALS['messages']['notice'] = "Login as admin";
}

include JOURNALBASEPATH . 'views/partial/start.php';
if($GLOBALS['messages']['notice'] != "") echo "<div class=\"messages\">" . $GLOBALS['messages']['notice'] . "</div>";
if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";
?>
			<div id="maincontainer" class="clearfix">
				<form id="login" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
					<input type="hidden" name="action" value="login" />
					<input type="hidden" name="from_page" value="<?php echo $_REQUEST["r"]  ?>" />
					<div class="errordiv">
				<?php
					if($GLOBALS['errs']['login'] != "")
						echo "<div><p>" . $GLOBALS['errs']['login'] . "</p></div>";
				?>
					</div>
<?php if($_SESSION['multiuser']){ ?>
					<div class="formrow clearfix">
						<label for="name">username</label>
						<input type="text" class="text" name="name" />
					</div>
<?php }else{ ?>
					<input type="hidden" name="name" value="admin" />
<?php } ?>
					<div class="formrow clearfix">
						<label for="pass">password</label>
						<input type="password" class="text" name="pass" />
					</div>
					<div class="formrow clearfix">
						<input type="submit" class="submit" value="Login" name="Login" />
					</div>
				</form>
			</div>
<?php
include JOURNALBASEPATH . 'views/partial/footer.php';
include JOURNALBASEPATH . 'views/partial/end.php';
?>
