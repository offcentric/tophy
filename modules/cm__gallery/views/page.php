<?php
$_SESSION['pagetype'] = "page";
$book = "";
$page = "";
$page_html = "";
$photo_count = 1;
$daysdiff = 0;

$book = $_SESSION['cm__gallery']['book'];
$page = $_SESSION['cm__gallery']['bookpage'];

$page = urldecode(urldecode($page));
if($page == ""){
	$page = "01";	
}

$_SESSION['pagename'] = str_replace(" ", "_", $book) . "_" . str_replace(" ", "_", $page);
$pagepath = $_SESSION['media_path'] . $book. '/' . $page . '/';
if(file_exists($pagepath)){
	require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/access_check.php");
	check_access($pagepath);
	
	if(@$_SESSION['cm__gallery']['folder_access'][$pagepath] == 'granted' || @$_SESSION['cm__gallery']['folder_access_type'][$pagepath] == 'public'){

		if(file_exists($_SESSION['media_path'] . $book . "/_book_conf.php")){
			include $_SESSION['media_path'] . $book . "/_book_conf.php";
		}
		if($book != ""){
			$html_template = get_html_template("page", $_SESSION['cm__gallery']['module_name']);
			$re_paging = "";
			preg_match("~{\{" . $_SESSION['cm__gallery']['display'] . "_start\}\}(.*)\{\{item_start\}\}(.+)\{\{item_end\}\}(.*)\{\{" . $_SESSION['cm__gallery']['display'] . "_end\}\}~s", $html_template, $item_html_components);
		
			//Intro
			$item_content['intro'] = nl2br(get_gallery_intro($page));

			//PAGING LINKS
			preg_match("~\{\{paging_start\}\}(.*)\{\{paging_previous_start\}\}(.+)\{\{paging_previous_end\}\}(.*)\{\{paging_next_start\}\}(.+)\{\{paging_next_end\}\}(.*)\{\{paging_end\}\}~s", $html_template, $paging_html_components);
			$item_content['paging_previous'] = get_previous_link($page, $paging_html_components[2]);
			$item_content['paging_next'] = get_next_link($page, $paging_html_components[4]);
		
			$page_html = replace_template_placeholders($item_html_components[1], $item_content);
			if($page != null){
				$gallery_html = get_gallery_html($page, $item_html_components[2]);
			}

			$page_html .= $gallery_html;
			$page_html .= replace_template_placeholders($item_html_components[3], $item_content);
		}else{
			$page_html = "book not selected!";
		}
		include COREBASEPATH . "/views/partial/start.php";
		echo $page_html;
		include COREBASEPATH . "/views/partial/end.php";
	}else{
		include('book_login.php');
	}
}else{
	include get_page("404");
}
