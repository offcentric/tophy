<?php
$SESSION["pagename"] = "userlist";
$_SESSION['pagetitle'] = "All users";

include JOURNALBASEPATH . '/views/partial/start.php';
include JOURNALBASEPATH . '/views/partial/userlist.php';
include JOURNALBASEPATH . '/views/partial/footer.php';
include JOURNALBASEPATH . '/views/partial/end.php';
?>