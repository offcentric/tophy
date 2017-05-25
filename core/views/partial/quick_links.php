<div id="quick_links">
	<ul>
<?php
	foreach($_SESSION['quicklinks']['items'] as $quicklink){
		if($quicklink['link'] == substr($_SERVER['REQUEST_URI'],0, strlen($quicklink['link']))){
			$classname = " class=\"on\"";
		}else{
			$classname = "";
		}
		if($quicklink['type'] == 'text'){
			$quicklink_html = "<span>" . $quicklink['title'] . "</span>";
		}else{
			$quicklink_html = "<img src=\"" . $quicklink['img'] . "\" alt=\"" . $quicklink['title'] . "\" title=\"" . $quicklink['title'] . "\" />";
		}
?>
		<li<?php echo $classname ?>><a href="<?php echo $quicklink['link'] ?>"><?php echo $quicklink_html ?></a></li>
<?php } ?>
	</ul>
</div>
