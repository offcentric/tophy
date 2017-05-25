<?php
$_SESSION['pagename'] = "";

if($_SESSION['cm__admin']['privileges'] > 1){
	if(!$_REQUEST["newentry"] && $_SESSION['cm__admin']['privileges'] < 3 && $GLOBALS['entry']->posted_by != $_SESSION['user']){
		header("Location:" . $_SESSION['journal_webpath_public']. "401");
		exit;
	}
}else{
	header("Location:" . $_SESSION['journal_webpath_public']. "401");	
}

if($_REQUEST["action"] = "add_entry"){
	$form_action = $_SESSION['admin_root'] . "entry/new";
	$_SESSION['pagetitle'] = "Add new entry";
}elseif($_REQUEST["action"] = "edit_entry"){
	$form_action = $_SESSION['admin_root']. "entry/edit/" . $_GET["id"];
	$_SESSION['pagetitle'] = "Edit entry";
}


include JOURNALBASEPATH . '/views/partial/start.php';
if($GLOBALS['messages']['notice'] != "") echo "<div class=\"messages\">" . $GLOBALS['messages']['notice'] . "</div>";
if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";
?>
			<div id="maincontainer" class="entry">
				<div class="title"><h2><?php if($_REQUEST["newentry"]) echo "Add"; else echo "Edit"; ?> Journal Entry</h2></div>
				<div id="formcontainer">
					<form name="entry_form" id="entry_form" class="validate" action="<?php echo $form_action ?>" method="post">
<?php if(!$_GET["newentry"]){ ?>
						<strong>Originally posted: <?php echo date('j F Y', $GLOBALS['entry']->timestamp) ?>, <?php echo date('H:i', $GLOBALS['entry']->timestamp) ?></strong><br />
						<input type="checkbox" class="checkbox" name="update_date" /> update the date of this entry
<?php } ?>
						<div class="formrow clearfix">
<?php /*							<div class="errordiv"><?php if($GLOBALS['errs']['title'] != "") echo "<div><p>" . $GLOBALS['errs']['title'] . "</p></div>"; ?></div>  */ ?>
							<label for="title">title:</label>
							<div><input type="text" class="longtext" name="title" id="title" value="<?php echo utf8_decode(stripslashes($GLOBALS['entry']->title)) ?>" /></div>
						</div>
<?php if($_SESSION['showtags']){ ?>
						<div class="formrow clearfix">
							<label for="tags">tags:</label>
<?php /*							<div><input type="text" class="longtext" name="tags" value="<?php echo utf8_decode(stripslashes($GLOBALS['entry']->tags)) ?>" /></div>  */ ?>
								<div class="tags clearfix">
									<span><input type="text" class="shorttext" name="tags[]" /></span>
									<span><input type="text" class="shorttext" name="tags[]" /></span>
									<span><input type="text" class="shorttext" name="tags[]" /></span>
									<span><input type="text" class="shorttext" name="tags[]" /></span>
<?php $tagcount = 0;
	foreach($GLOBALS['tags']->taglist as $tag){
		if($tag != ""){
			$tagcount++;
			$checked = "";
			if(!$_GET["newentry"]){ if(in_array($tag, $GLOBALS['entry']->currenttags)) $checked = "checked=\"checked\""; }
?>
									<span><label><?php echo $tag ?></label><input type="checkbox" class="checkbox" name="tags[]" value="<?php echo $tag ?>" <?php echo $checked?>/></span>
<?php
		}
		if($tagcount >= 8) break;
 	}
?>
								</div>
						</div>
<?php } ?>
						<div class="formrow clearfix">
						<?php if($_SESSION['showmood']){ ?>
							<label for="mood">mood:</label>
							<div><input type="text" class="text" name="mood" value="<?php echo utf8_decode(stripslashes($GLOBALS['entry']->mood)) ?>" /></div>
						<?php } ?>
						<?php if($_SESSION['showlistening']){ ?>
							<label for="listening">listening to:</label>
							<div><input type="text" class="text" name="listening" value="<?php echo utf8_decode(stripslashes($GLOBALS['entry']->listening)) ?>" /></div>
						<?php } ?>
						</div>
						<div class="formrow clearfix">
						<?php if($_SESSION['showreading']){ ?>
							<label for="reading">reading:</label>
							<div><input type="text" class="text" name="reading" value="<?php echo utf8_decode(stripslashes($GLOBALS['entry']->reading)) ?>" /></div>
						<?php } ?>
						<?php if($_SESSION['showwatching']){ ?>
							<label for="watching">watching:</label>
							<div><input type="text" class="text" name="watching" value="<?php echo utf8_decode(stripslashes($GLOBALS['entry']->watching)) ?>" /></div>
						<?php } ?>
						</div>
						<div class="formrow clearfix">
							<div class="errordiv"><?php if($GLOBALS['errs']['content'] != "") echo "<div><p>" . $GLOBALS['errs']['content'] . "</p></div>"; ?></div>
							<textarea name="content"><?php echo stripslashes(decodeContent($GLOBALS['entry']->content)) ?></textarea>
							<div id="toolbar" class="clearfix">
								<span id="toolbarbuttons"></span>
								<span id="toolbarlinks">
									<a class="helplink" href="<?php echo $_SESSION['admin_root'] ?>upload" target="_new"><img src="<?php echo $_SESSION['uploader_webpath'] ?>images/arrow_black.gif" /></a>&nbsp;<a class="helplink" href="<?php echo $_SESSION['uploader_webpath'] ?>" target="_new"><strong>Upload Images</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a class="helplink" href="<?php echo $_SESSION['admin_root'] ?>help/entry_form" target="_new"><img src="<?php echo $_SESSION['uploader_webpath'] ?>images/arrow_black.gif" /></a>&nbsp;<a class="helplink" href="<?php echo $_SESSION['admin_root'] ?>help/entry_form" target="_new"><strong>Help</strong></a>
								</span>
							</div>
						</div>

						<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
						<input type="hidden" name="action" value="<?php if($_GET["newentry"]) echo "add_entry"; else echo "edit_entry" ?>" />
						<input type="submit" class="submit" value="<?php if($_GET["newentry"]) echo "Add"; else echo "Edit"; ?> Entry" />
					</form>
				</div>
			</div>
			<div class="bar"></div>
<?php include JOURNALBASEPATH . '/views/partial/end.php';?>
