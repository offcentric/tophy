<?php
header("Pragma: no-cache");
header("Cache: no-cahce");

//if($_SESSION['admin_pass'] == "" || isset($_GET["set_password"])){
//	$_SESSION['pagename'] = "admin_set_password";
//	include GALLERYBASEPATH. "views/admin_set_password.php";	
//}else{
	if($_REQUEST["action"] != ""){		
		include(COREBASEPATH . "controllers/update_config.php");
		load_session_vars();
	}
	$_SESSION['pagename'] = "admin";
	include MODULESBASEPATH. "cm__admin/views/admin.php";
//}
?>