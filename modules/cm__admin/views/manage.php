<?php
$_SESSION['pagename'] = "manage";
$_SESSION['pagetitle'] = "Journal Configuration & User Management";

if(isset($_REQUEST["user_deleted"])) $GLOBALS['messages']['notice'] = "The user has been deleted.";

include JOURNALBASEPATH . '/views/partial/start.php';
if($GLOBALS['messages']['notice'] != "") echo "<div class=\"messages\">" . $GLOBALS['messages']['notice'] . "</div>";
if($GLOBALS['messages']['error'] != "") echo "<div class=\"errors\">" . $GLOBALS['messages']['error'] . "</div>";
?>
<form action="<?php echo $_SESSION['admin_root'] ?>manage" method="post" class="validate" id="config_form" encType="multipart/form-data">
	<input type="hidden" class="hidden" name="action" value="update_config" />
<div id="manage_tabs" class="container tabcontainer tabcontainer_level0 jshidden anchorselected">
	<ul id="nav" class="tabs tabs_level0 clearfix">
		<li id="tab_global_content"><div><a href="#global_content">Global Content</a></div></li>
		<li id="tab_customization"><div><a href="#customization">Customization</a></div></li>
		<li id="tab_aliases"><div><a href="#aliases">Aliases</a></div></li>
		<li id="tab_pages"><div><a href="#pages">Pages</a></div></li>
		<li id="tab_users"><div><a href="#users">Users</a></div></li>
	</ul>
	<div id="content">
		<h2 class="jsnodisplay">Global Content</h2>
		<div id="global_content" class="tabcontainer tabcontainer_level1 tabcontent section_general clearfix">
			<ul class="tabs tabs_level1 clearfix">
				<li id="tab_general"><a href="#general">General</a></li>
				<li id="tab_metadata"><a href="#metadata">Metadata</a></li>
			</ul>
			<div id="general" class="tabcontent clearfix">
				<div class="formrow clearfix">
					<div class="errordiv"><?php if($GLOBALS['errs']['journal_name'] != "") echo "<div><p>" . $GLOBALS['errs']['journal_name'] . "</p></div>"; ?></div>
					<div class="helpicon"><a id="helplink_journal_name" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>help/admin/journal_name" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['cm__journal']['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="gallery_name">Journal Name</label>
					<input type="text" class="text_meta text" name="journal_name" id="journal_name" value="<?php echo getElementValue($config_node, "journal_name", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="errordiv"></div>
					<div class="helpicon"><a id="helplink_admin_email" href="<?php echo $_SESSION['webpath'] ?>help/admin/admin_email" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="admin_email">Admin Email</label>
					<input type="text" class="text_meta text" name="admin_email" id="admin_email" value="<?php echo getElementValue($config_node, "admin_email", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="errordiv"></div>
					<div class="helpicon"><a id="helplink_email_subject" href="<?php echo $_SESSION['webpath'] ?>help/admin/email_subject" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="email_subject">Email Subject</label>
					<input type="text" class="text_meta text" name="email_subject" id="email_subject" value="<?php echo getElementValue($config_node, "email_subject", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="helpicon"><a id="helplink_footer_content" href="<?php echo $_SESSION['webpath'] ?>help/admin/footer_content" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="footercontent">Footer content</label>
					<textarea name="footercontent" id="footercontent"><?php echo getElementValue($config_node, "footercontent", 0, "") ?></textarea>
				</div>
			</div>
			<h3 class="jsnodisplay">Metadata</h2>
			<div id="metadata" class="tabcontent clearfix">
			</div>
		</div>

		<h2 class="jsnodisplay">Customization</h2>
		<div id="customization" class="tabcontainer_level1 tabcontent section_customization clearfix">
		</div>

		<h2 class="jsnodisplay">Aliases</h2>
		<div id="aliases" class="tabcontainer_level1 tabcontent section_aliases clearfix">
		</div>
	
		<h2 class="jsnodisplay">User Management</h2>
		<div id="users" class="tabcontainer_level1 tabcontent section_users clearfix">
	<?php include JOURNALBASEPATH . '/views/partial/userlist.php'; ?>
	<a href="<?php echo $_SESSION['admin_root'] ?>add_user" id="add_user" class="button">Add new User</a>
		</div>
	</div>
</div>
</form>
<?php
include JOURNALBASEPATH . '/views/partial/footer.php';
include JOURNALBASEPATH . '/views/partial/end.php';
?>