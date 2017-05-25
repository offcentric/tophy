<?php
$menu_html = "<ul id=\"nav\" class=\"clearfix ";
if($_SESSION['cm__gallery']['menu']['dropdown'] == "true") $menu_html .= " dropdown";
$menu_html .= "\">";
$x = 0;
foreach($_SESSION['cm__gallery']['menu']['items'] as $menuitem) {
	$x++;
	$folder = $menuitem['folder'];
	$title =  $menuitem['title'];
	$linked = $menuitem['linked'];
	$submenu_collapsible = $menuitem['submenu_collapsible'];
	if($folder==@$_SESSION['cm__gallery']['book']){
		$_SESSION['cm__gallery']['book_title'] = $title;
		$rolloverstate="_on";
		$item_linkclass='';
		if($_SESSION['cm__gallery']['navigation_style']!="images")
			$item_linkclass = " item_on";
	}else{
		$rolloverstate="";
		$item_linkclass = "";
	}
	$display = "";
	$ratio = "";
	if(!empty($_SESSION['gallery_display']) && $_SESSION['gallery_display'] != $_SESSION['default_gallery_display']){
		$display = "/" . $_SESSION['gallery_display'];
	}
	if(!empty($_SESSION['gallery_thumb_ratio']) && $_SESSION['gallery_thumb_ratio'] != $_SESSION['default_gallery_thumb_ratio']){
		$ratio =  "/" . $_SESSION['gallery_thumb_ratio'] ;
		$display = "/" . $_SESSION['gallery_display'];
	}
	$link_open = "";
	$link_close = "";
	if($linked == "false" && $submenu_collapsible == "true"){
		$link_open = "<a href=\"#\">";
		$link_close = "</a>";
	}
	if($linked != "false"){
		$link_open = "<a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $folder . $display . $ratio . "\" title=\"" . $title ."\">";
		$link_close = "</a>";
	}
	$nav_image = $_SESSION['webpath'] . "images/nav/" . strtolower($folder);

	if($x == count($_SESSION['cm__gallery']['menu']['items'])) $last = ' last';
	else $last = '';

	$menu_html .= "<li class=\"level_0" . $item_linkclass . $last ."\">" . $link_open . "<span>";
	if($_SESSION['cm__gallery']['navigation_style']=="images"){
		$menu_html .= "<img class=\"rollover\" src=\"" . $nav_image . $rolloverstate . "." . $_SESSION['cm__gallery']['navigation_image_format'] . "?t=" . $_SESSION['theme'] . "\" alt=\"" . $title . "\" />";
	}else{
		$menu_html .= $title;
	}
	$menu_html .= "</span>" . $link_close . "\n";
	
	if(count($menuitem['subitem']) > 0){
		$y = 0;
		$menu_html .= "<ul class=\"subnav\">\n";

		foreach($menuitem['subitem'] as $subitem){
			$y++;
			$subitem_title = $subitem['title'];
			$subitem_folder = "/" . $subitem['folder'];
			$sublink_open = "<a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $folder . $subitem_folder . $display . $ratio . "\" title=\"" . $title ."\">";
			if($folder==@$_SESSION['cm__gallery']['book'] && $subitem['folder']==$_SESSION['cm__gallery']['bookpage']){
				$_SESSION['cm__gallery']['bookpage_title'] = $subitem_title;
				$subitem_linkclass = " subitem_on";
			}else{
				$subitem_linkclass = "";
			}
			if($y == count($menuitem['subitem'])) $last = ' last';
			else $last = '';

			$menu_html .= "<li class=\"level_1" . $subitem_linkclass . $last . "\">" . $sublink_open . "<span>" . $subitem_title . "</span></a></li>\n";
		}
		$menu_html .= "</ul>\n";
	}
	$menu_html .= "</li>\n";
}
$menu_html .= "</ul>\n";
?>
<div id="nav_container">
<?php
	//if the bookpage is not defined the <navigation> node in gallery_conf.xml then it doesn't have a title. Let's just make one out of the folder name
	if($_SESSION['cm__gallery']['menu']['header'] != ""){ $menu_header = $_SESSION['cm__gallery']['menu']['header'];}

	if(@$_SESSION['module'] == $_SESSION['cm__gallery']['module_name']){
		if(@$_SESSION['cm__gallery']['bookpage'] != "" && @$_SESSION['cm__gallery']['bookpage_title'] == ""){
			$_SESSION['cm__gallery']['bookpage_title'] = urldecode($_SESSION['cm__gallery']['bookpage']);
		}
	
		if($_SESSION['cm__gallery']['book_title'] != ""){
			$menu_header = "// " . $_SESSION['cm__gallery']['book_title'];
			if($_SESSION['cm__gallery']['bookpage_title'] != ""){ $menu_header .= ": " . $_SESSION['cm__gallery']['bookpage_title']; }
		}
	}
	$dropdown_arrow = "<img src=\"" . $_SESSION['webpath'] . "images/nav/menu_arrow.png?m=" . @$_SESSION['module'] . "&t=" . $_SESSION['theme'] . "\" alt=\"click to expand navigation menu\" title=\"click to expand navigation menu\" />\n";
	if($_SESSION['cm__gallery']['menu']['dropdown'] == "true"){
		echo "<h3 class=\"nav_header\"><a href=\"#\">" . $dropdown_arrow . "<span>" . $menu_header . "</span></a></h3>\n";
	}else{
	}
	echo $menu_html;
?>
</div>