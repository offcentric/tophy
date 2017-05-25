<?php
if($_SESSION['cm__gallery']['enable_all_books_list']){
	$list = "";
	$_SESSION['pagename'] = "book";

	foreach(glob($_SESSION['media_path'] . "*", GLOB_ONLYDIR) as $dirpath) {
		$dirname = substr($dirpath, strlen($_SESSION['media_path']));
		$book = urlencode(str_replace("&", "%26", $dirname));
		$list .= "<li><a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $book . "/\">" . ucwords($dirname) . "</a>";
		
		$intro = get_gallery_intro($book);
		if($intro != ""){
			$list .= " - " . "<span class=\"intro\">" . $intro . "</span>";
		}
		$list .= "</li>\n";
	}
	include COREBASEPATH . "/views/partial/start.php";
	?>
			<div id="main">
				<ul class="toc">
					<?php echo $list ?>
				</ul>
			</div>
	<?php
	include COREBASEPATH . "/views/partial/end.php";
}else{
	header("Location:/error-404?r=list");
}
?>