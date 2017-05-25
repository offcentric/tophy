<?php
$_SESSION['pagetype'] = "default";

include COREBASEPATH . "/views/partial/start.php";
?>
	<div id="main">
<?php get_page_presentations() ?>
	</div>
<?php
include COREBASEPATH . "/views/partial/end.php";
?>