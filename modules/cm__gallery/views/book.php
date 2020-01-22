<?php
$list = "";
$dircount = 0;
$pagecount = 0;
$suffix = '';
$html = '';
$book_html = '';
$default_coverimage = "__coverimage.jpg";
$image_file_pattern = '*.[Jj][Pp][Gg]';

$_SESSION['cm__gallery']['bookpage'] = "";
$_SESSION['cm__gallery']['bookpage_title'] = "";

if($_SESSION['cm__gallery']['display'] == "thumbnails"){
	$suffix = "_" . $_SESSION['cm__gallery']['thumb_ratio'];
}

if($_SESSION['cm__gallery']['book'] != ""){
	$resize_path_part  = "__" . $_SESSION['cm__gallery']['display'] . "/". $_SESSION['cm__gallery']['book'] . "/";
	$resize_path = $_SESSION['path_db'] . $resize_path_part;
	$resize_webpath = $_SESSION['webpath_db'] . $resize_path_part;

	if(!is_dir($_SESSION['path_db'])){ mkdir($_SESSION['path_db']);}
	if(!is_dir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'])){ mkdir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display']);}
	if(!is_dir($resize_path)){ mkdir($resize_path);}

	$d = $_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/";
	
	require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/access_check.php");
	check_access($d);
	
	if(@$_SESSION['cm__gallery']['folder_access'][$d] == 'granted' || $_SESSION['cm__gallery']['folder_access_type'][$d] == 'public'){
		$pattern_img = "*.jpg";
		//if there are JPGs found in this book directory, display thumbnails
		if(sizeof(glob($d . $pattern_img)) > 0){
			$html_template = get_html_template("page", $_SESSION['cm__gallery']['module_name']);
			preg_match("~\{\{" . $_SESSION['cm__gallery']['display'] . "_start\}\}(.*)\{\{item_start\}\}(.+)\{\{item_end\}\}(.*)\{\{" . $_SESSION['cm__gallery']['display'] . "_end\}\}~s", $html_template, $item_html_components);
		
			// get intro text from intro.txt file in the book directory,
			$item_content['intro'] = nl2br(get_gallery_intro(null));
			$item_content['paging_previous'] = "";
			$item_content['paging_next'] = "";

			$html = replace_template_placeholders($item_html_components[1], $item_content);
			$html .= get_gallery_html(null, $item_html_components[2]);
			$html .= replace_template_placeholders($item_html_components[3], $item_content);
			$_SESSION['pagetype'] = "page";
		}else{
		
	//if there are no JPGs found in this book directory, display table of contents
			if(sizeof(glob($d . "*", GLOB_ONLYDIR)) > 0){
				$html_template = get_html_template("book", $_SESSION['cm__gallery']['module_name']);
				preg_match("~^(.*)\{\{item_start\}\}(.+)\{\{item_end\}\}(.*)$~s", $html_template, $book_html_components);
			
				foreach(glob($d . "*", GLOB_ONLYDIR) as $pagespath) {
					$pagedir = substr($pagespath, strlen($d));
					//if there is a leading integer in the folder name, strip it from the name
					if(intval($pagedir) != 0 && strpos($pagedir, "_") > 0){
						$p = substr($pagedir, strpos($pagedir, "_")+1);
					}else{
						$p = $pagedir;				
					}
					$item_content['text'] = str_replace("_", " ", $p);
					$item_content['link'] = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . urlencode($_SESSION['cm__gallery']['book']) . "/" . urlencode($pagedir);
					if(is_dir($pagespath)){
						if($_SESSION['cm__gallery']['enable_book_thumbs']){
							// if __coverimage.jpg doesn't exist in the page folder, just grab the 1st JPEG.
							if(!file_exists($pagespath . "/" . $default_coverimage)){
								$page_images = glob($pagespath . "/" . $image_file_pattern, GLOB_BRACE);
								if(count($page_images)>0){
									$coverimage = substr($page_images[0], strlen($pagespath));
								}else{
									// NO PHOTOS IN PAGE FOLDER! NOW WHAT?
									//include get_page("404");
									$coverimage = '';							
								}
							}else{
								$coverimage = $default_coverimage;
							}
							if($coverimage != ''){
								$coverimage_file_path = $pagespath . "/" . $coverimage;
								$coverimage_thumb = substr($coverimage, 0, strpos($coverimage, ".jpg")) . $suffix . ".jpg";
								$coverimage_thumb_folder_path = $resize_path . $pagedir . "/";
								$coverimage_thumb_file_path = $coverimage_thumb_folder_path . $coverimage_thumb;
								$coverimage_thumb_folder_webpath = $resize_webpath . $pagedir . "/";

								if(!is_dir($coverimage_thumb_folder_path)){ mkdir($coverimage_thumb_folder_path);}
								if(file_exists($coverimage_thumb_file_path)){
									$timediff =  time() - (filectime($coverimage_thumb_file_path));
									$daysdiff = $timediff/3600/24;
								}
								if(!file_exists($coverimage_thumb_file_path || $daysdiff > $_SESSION['cm__gallery']['resize_maxage'])){
									resizeimg($coverimage_file_path, $coverimage_thumb_file_path, $_SESSION['cm__gallery']['thumb_w'], $_SESSION['cm__gallery']['thumb_h'], "fixed");
								}
								$item_content['image'] =  $coverimage_thumb_folder_webpath . $coverimage_thumb;
							}else{
								$item_content['image'] = '';
							}
							preg_match("~\{\{image_item_start\}\}(.*)\{\{image_item_end\}\}~s", $html_template, $item_html_components);
						}else{
							$item_content['image'] =  "";
							preg_match("~\{\{text_item_start\}\}(.*)\{\{text_item_end\}\}~s", $html_template, $item_html_components);
						}
						$book_html .= replace_template_placeholders($item_html_components[1], $item_content);
						$dircount++;
						if($dircount==1)$firstdir = urlencode(urlencode($pagedir)) ;
					}
				}
				$_SESSION['pagename'] = "book";
				$html = $book_html_components[1] . $book_html . $book_html_components[3];
				// if there is only 1 page directory in this book (i.e. 01) then redirect to it
				if($dircount==1 && $pagecount==0){
					$redirect_url = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] . "/" . $firstdir. "/";
					if(!empty($_GET["display"])) $redirect_url .= $_GET["display"] . "/";
					if(!empty($_GET["thumbratio"])) $redirect_url .= $_GET["thumbratio"] . "/";
					header("Location: " . $redirect_url);
				}else{
				
				}
			}
		}
	}else{
		include('book_login.php');
	}
	
	if($_SESSION['cm__gallery']['book'] != ""){
		$_SESSION['pagename'] = $_SESSION['cm__gallery']['bookpage'];
		$_SESSION['pagetype'] = "book";

		include COREBASEPATH . "/views/partial/start.php";
		echo $html;
		include COREBASEPATH . "/views/partial/end.php";
	}
}

