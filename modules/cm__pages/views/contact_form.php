					<div class="contact">
<?php
if(@$_REQUEST['action'] == "contact_submit" && !@$GLOBALS['has_errs']){
	if($contact_form['success_text'] != ""){echo "<div class=\"messages\">" . $contact_form['success_text'] . "</div>";}
}else{ ?>
						<div class="messages" style="text-align:center">Get in touch:</div>
<?php
}
?>
						<form name="contact" id="contact_form" class="validate" action="<?php echo $_SESSION['webpath'] ?>contact/" method="post">
							<input type="hidden" name="action" value="contact_submit" />
							<div class="formrow clearfix">
								<div class="errordiv"><?php if(@$GLOBALS['errs']['name'] != "") echo "<div><p>" . $GLOBALS['errs']['name'] . "</p></div>"; ?></div>
								<label for="name">name:</label>
								<input type="text" class="text required" maxlength="100" id="name" name="name" value="<?php if(@$GLOBALS['has_errs']) echo $_POST["name"] ?>" />
							</div>
							<div class="formrow clearfix">
								<div class="errordiv"><?php if(@$GLOBALS['errs']['email'] != "") echo "<div><p>" . $GLOBALS['errs']['email'] . "</p></div>"; ?></div>
								<label for="email">email:</label>
								<input type="text" class="text required" maxlength="256" id="email" name="email" value="<?php if(@$GLOBALS['has_errs']) echo $_POST["email"] ?>" />
							</div>
							<div class="formrow clearfix">
								<div class="errordiv"><?php if(@$GLOBALS['errs']['message'] != "") echo "<div><p>" . $GLOBALS['errs']['message'] . "</p></div>"; ?></div>
								<label for="message">message:</label>
								<textarea id="message" name="message" rows="10" cols="30" class="required"><?php if(@$GLOBALS['has_errs']) echo $_POST["message"] ?></textarea>
							</div>
							<div class="formrow clearfix">
								<input type="submit" class="submit" id="submit" value="Send" />
							</div>
						</form>
					</div>
