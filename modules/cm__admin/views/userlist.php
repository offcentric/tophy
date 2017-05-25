<table class="userlist">
	<tr>
		<th>Username</th>
		<th>User since</th>
		<th class="numposts"># posts</th>
<?php if($_SESSION['cm__admin']['privileges'] > 1){ ?>
		<th>Admin</th>
		<th>Active</th>
		<th class="changepass">Change password</th>
<?php } ?>
<?php if($_SESSION['cm__admin']['privileges'] > 2){ ?>
		<th class="delete">Delete User</th>
<?php } ?>
	</tr>
<?php
for($x=0;$x<$GLOBALS['userlist']->xpresult->length;$x++){
	$usernode = $GLOBALS['userlist']->xpresult->item($x);
	$name = getElementValue($usernode, "name", 0, "");
	$user_since = date( "d/m/Y", intval(getElementValue($usernode, "date_joined", 0, "")));
	$active = getElementValue($usernode, "active", 0, "");
	if($active) $active = "yes"; else $active = "no";
	$admin = getAttributeValue($usernode->parentNode, "user", $x, "admin", "");
	if($admin) $admin = "yes"; else $admin = "no";
	$xpresult_user = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry/posted_by[translate(.,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') = '". strtolower($name) . "']/..");
?>
	<tr<?php if($name == $_SESSION['user']) echo " class=\"strong\"";?>>
<?php if(($_SESSION['cm__admin']['privileges'] > 2 || $name == $_SESSION['user']) && ($name != "admin" || $_SESSION['user'] == "admin")){ ?>
		<td><a href="<?php echo $_SESSION['cm__journal']['webpath']?>edit_user/<?php echo $name ?>"><?php echo $name ?></a></td>
<?php }else{ ?>
		<td><?php echo $name ?></td>
<?php } ?>
		<td><?php echo $user_since ?></td>
		<td class="numposts"><a href="<?php echo $_SESSION['cm__journal']['webpath']?>user/<?php echo $name ?>"><?php echo $xpresult_user->length ?></a></td>
<?php if($_SESSION['cm__admin']['privileges'] > 1){ ?>
		<td><?php echo $admin ?></td>
		<td><?php echo $active ?></td>
<?php } ?>
<?php if(($_SESSION['cm__admin']['privileges'] > 2 || $name == $_SESSION['user']) && ($name != "admin" || $_SESSION['user'] == "admin")){ ?>
		<td class="changepass"><a href="<?php echo $_SESSION['admin_root'] ?>change_password/?user=<?php echo $name ?>" target="_new">Change</a></td>
<?php } ?>
<?php if($_SESSION['cm__admin']['privileges'] > 2){ ?>
		<td class="delete"><?php if($name != "admin" && $name != $_SESSION['user']){ ?><a href="<?php echo $_SESSION['admin_root'] ?>delete_user/<?php echo $name ?>">Delete</a><?php } ?></td>
<?php } ?>
	</tr>
<?php } ?>
</table>
