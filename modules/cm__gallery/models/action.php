<?php
if($_POST["action"] == "gallery_book_login"){
	require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/controllers/book_login_controller.php");
}

?>