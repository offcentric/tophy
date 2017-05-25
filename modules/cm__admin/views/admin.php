<?php 
if($_REQUEST['json_response'] == "true"){
	$i = 0;
	echo "{";
	if($GLOBALS['success']){
		echo "\"status\": \"saved\"";
	}
	echo "\"errs\":[";
	foreach($GLOBALS['errs'] as $k=>$v){
		$i++;
		echo "\"" . $k . "=" . $v . "\"";
		if($i < count($GLOBALS['errs']))
		echo ",";
	}
	echo "]";
	echo "}";
}else{
	if(!$_GET["embed"]){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
		include COREBASEPATH . "/views/partial/head.php";
	}
	
	if($_REQUEST[action] == "edit_nav_images" || $_REQUEST[action] == "upload_nav_images" || $_REQUEST[action] == "delete_nav_image" ){
		$book = $_REQUEST[book];

		function get_image_src($book, $suffix, $navigation_image_format){
			return $GLOBALS['path_to_root'] . "images/nav/" . strtolower($book). $suffix . "." . $navigation_image_format;
		}

/***********************************************/
/* BEGIN EDIT NAVIGATION IMAGES IFRAME CONTENT */
/***********************************************/
		if(!$_GET["embed"]){
?>
<body class="iframe">
<?php
		}
?>
	<form class="sub_form" action="." encType="multipart/form-data" method="post">
	<div class="edit_nav_image_container clearfix">
		<h3 class="colorbar">Edit navigation image(s) for Book "<?php echo $book ?>"</h3>
<?php		
		if($GLOBALS['errs']['nav_image'] != ""){
?>
					<span class="smallerror"><?php echo $GLOBALS['errs']['nav_image'] ?></span>
<?php 		
		}
?>
		<div class="images clearfix">
			<div class="image">
				<h4>Default state</h4>
				<span class="img">
					<?php if(file_exists($GLOBALS['path_to_root'] . "images/nav/" . strtolower($book) . "." . $navigation_image_format)){?>
					<img class="nav_image" src="<?php echo get_image_src($book, "", $navigation_image_format); ?>" alt="<?php echo $title; ?>" />
					<a class="delete_link" href="?action=delete_nav_image&amp;state=&amp;book=<?php echo urlencode($book) ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/delete.png" alt="Delete this navigation image" title="Delete this navigation image" /></a>
					<?php } ?>
				</span>
				<input type="file" class="file" name="image" />
			</div>
			<div class="image">
				<h4>Rollover state (optional)</h4>
				<span class="img">
					<?php if(file_exists($GLOBALS['path_to_root'] . "images/nav/" . strtolower($book). "_on" . "." . $navigation_image_format)){?>
					<img class="nav_image" src="<?php echo get_image_src($book, "_on", $navigation_image_format); ?>" alt="<?php echo $title; ?>" />
					<a class="delete_link" href="?action=delete_nav_image&amp;state=_on&amp;book=<?php echo urlencode($book) ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/delete.png" alt="Delete this navigation image" title="Delete this navigation image" /></a>
					<?php }?>
				</span>
				<input type="file" class="file" name="image_on" />
			</div>
			<div class="submitrow">
				<input class="submit" type="submit" value="Upload Image(s)" />
				<input class="reset jsnodisplay" type="reset" value="Reset" />
			</div>
		</div>
		<input type="hidden" class="hidden" name="navigation_image_format" id="navigation_image_format" value="<?php echo $navigation_image_format ?>" />
		<input type="hidden" class="hidden" name="action" value="upload_nav_images" />
		<input type="hidden" class="hidden" name="book" value="<?php echo $book ?>" />
	</div>
<?php
/*********************************************/
/* END EDIT NAVIGATION IMAGES IFRAME CONTENT */
/*********************************************/
	}elseif($_REQUEST[action] =="edit_splash_images" || $_REQUEST[action] =="remove_splash_image" || $_REQUEST[action] == "move_splash_image"){
/**************************************/
/* BEGIN SPLASH IMAGES IFRAME CONTENT */
/**************************************/
		if(!$_GET["embed"]){
?>
<body class="edit_splash_images jsoverflowhidden iframe">
<?php		} ?>
	<form class="sub_form" action="." encType="multipart/form-data" method="post">
	<input type="hidden" class="hidden" name="action" value="edit_splash_images" />
	<div id="splash_image_container" class="container">
		<div id="splash_images">
<?php
		$index = 0;
		$splash_images = glob($GLOBALS['path_to_root'] . "images/splash/splashimage_*");
		if($_GET[action] == "new_splash_image"){$xtra = 1; }else{ $xtra = 0;}
		foreach($splash_images as $file) {
			$filename = substr($file,strrpos($file, "/")+1);
			$thumb = $_SESSION['webpath_db'] . "__splash/" . substr($file, strpos($file, "splashimage_"));
			$prev_index = substr($file, strrpos($file, "_")+1, 2) - 1;
			$next_index = substr($file, strrpos($file, "_")+1, 2) + 1;
		
			if(strlen($prev_index) == 1 ) $prev_index = "0" . $prev_index;
			if(strlen($next_index) == 1 ) $next_index = "0" . $next_index;

			$prev_image = "";
			$next_image = "";
			if(sizeof(glob($GLOBALS['path_to_root'] . "images/splash/splashimage_" . $prev_index . "*")) > 0){
				$prev_image = current(glob($GLOBALS['path_to_root'] . "images/splash/splashimage_" . $prev_index . "*"));
			}
			if(sizeof(glob($GLOBALS['path_to_root'] . "images/splash/splashimage_" . $next_index . "*")) > 0){
				$next_image = current(glob($GLOBALS['path_to_root'] . "images/splash/splashimage_" . $next_index . "*"));
			}
?>
			<div class="splash_image clearfix">
				<a href="<?php echo $_SESSION['webpath'] . "images/splash/" , $filename ?>" class="thickbox"><img class="splash_thumb" src="<?php echo $thumb; ?>?t=<?php echo generate_random_string(16); ?>" /></a>
				<span class="icons clearfix">
					<span class="move moveup">
						<?php if($index > 0){?><a href="./?action=move_splash_image&amp;file=<?php echo $filename ?>&amp;new_file=<?php echo substr($prev_image,strrpos($prev_image, "/")+1) ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/up.png" alt="Move this splash image up"  title="Move this splash image up" /></a><?php }else{?>&nbsp;<?php } ?>
					</span>
					<span class="move movedown">
						<?php if($index < (sizeof($splash_images)-1+$xtra)){ ?><a href="./?action=move_splash_image&amp;file=<?php echo $filename ?>&amp;new_file=<?php echo substr($next_image,strrpos($next_image, "/")+1) ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/down.png" alt="Move this splash image down" title="Move this splash image down"/></a><?php }else{?>&nbsp;<?php } ?>

					</span>
					<span class="remove"><a class="remove_link" href="./?action=remove_splash_image&amp;file=<?php echo $filename ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/delete.png" alt="Remove this splash image" title="Remove this splash image" /></a></span>
				</span>
			</div>
<?php
			$index++;
		}
?>
		</div>
		<div id="add_splashimage_row" class="colorbar clearfix jspositionrelative jstop-0 jsmargintop-5">
			<label for="new_splash_image">Add splash image&nbsp;&nbsp;</label>
			<input type="file" class="file" name="new_splash_image" id="new_splash_image" />
			<input type="checkbox" class="checkbox" name="resize_splash_images" id="resize_splash_images"<?php if(getElementValue($gallery_node, "resize_splash_images", 0, "") == "1") echo " checked=\"true\"";?> />
			<label for="resize_splash_images" class="smalllabel">resize</label>
			<span id="splash_image_resize_fields" class="jsnodisplay">
				<span class="smalllabel">W:</span><input class="text_number text" type="text" id="splash_image_resize_w" name="splash_image_resize_w" value="<?php echo getElementValue($gallery_node, "splash_image_resize_w", 0, "800") ?>" />
				<span class="smalllabel">H:</span><input class="text_number text" type="text" id="splash_image_resize_h" name="splash_image_resize_h" value="<?php echo getElementValue($gallery_node, "splash_image_resize_h", 0, "400") ?>" />
			</span>
			<input type="submit" class="submit submit2" value="Upload image" title="Upload image" />
		</div>
	</div>
<?php
/*************************************/
/* END SPLASH IMAGES IFRAME CONTENT */
/*************************************/
	}else{
		if(!$_GET["embed"]){
?>
<body>
<?php		} ?>
	<form action="./" method="post" class="validate" id="config_form" encType="multipart/form-data">
	<input type="hidden" class="hidden" name="action" value="update_all" />

	<div id="header" class="thickbar jspositionfixed">
		<h1><?php echo $_SESSION['website_name'] ?> Configuration</h1>
		<input class="submit" type="submit" value="Save settings" />
	</div>
	<div id="admin_page" class="container tabcontainer tabcontainer_level0 jshidden stickyselected jspaddingtop-50">
		<ul id="nav" class="jspositionfixed tabs tabs_level0 clearfix">
			<li id="tab_general"><div><a href="#general">General Info & Text</a></div></li>
			<li id="tab_customization"><div><a href="#customization">Customization</a></div></li>
			<li id="tab_aliases"><div><a href="#aliases">Aliases</a></div></li>
			<li id="tab_books"><div><a href="#books">Books</a></div></li>
			<li id="tab_navigation"><div><a href="#navigation">Navigation</a></div></li>
			<li id="tab_metadata"><div><a href="#metadata">Metadata</a></div></li>
		</ul>
		<div class="jspaddingtop-50">
			<h2 class="jsnodisplay">General Site Info</h2>
			<div id="general" class="tabcontainer_level1 tabcontent section_general clearfix">
				<div class="formrow clearfix">
					<div class="errordiv"><?php if($GLOBALS['errs']['gallery_name'] != "") echo "<div><p>" . $GLOBALS['errs']['gallery_name'] . "</p></div>"; ?></div>
					<div class="helpicon"><a id="helplink_gallery_name" href="<?php echo $_SESSION['webpath'] ?>help/admin/gallery_name" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="gallery_name">Gallery Name</label>
					<input type="text" class="text_meta text" name="gallery_name" id="gallery_name" value="<?php echo getElementValue($gallery_node, "gallery_name", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="errordiv"></div>
					<div class="helpicon"><a id="helplink_admin_email" href="<?php echo $_SESSION['webpath'] ?>help/admin/admin_email" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="admin_email">Admin Email</label>
					<input type="text" class="text_meta text" name="admin_email" id="admin_email" value="<?php echo getElementValue($gallery_node, "admin_email", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="errordiv"></div>
					<div class="helpicon"><a id="helplink_email_subject" href="<?php echo $_SESSION['webpath'] ?>help/admin/email_subject" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="email_subject">Email Subject</label>
					<input type="text" class="text_meta text" name="email_subject" id="email_subject" value="<?php echo getElementValue($gallery_node, "email_subject", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="helpicon"><a id="helplink_footer_content" href="<?php echo $_SESSION['webpath'] ?>help/admin/footer_content" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="footercontent">Footer content</label>
					<textarea name="footercontent" id="footercontent"><?php echo getElementValue($gallery_node, "footercontent", 0, "") ?></textarea>
				</div>
			</div>

			<h2 class="jsnodisplay">Customization</h2>
			<div id="customization" class="tabcontainer_level1 tabcontent section_customization clearfix">
				<div class="formrow clearfix">
				<div class="errordiv"><?php if($GLOBALS['errs']['gallery_logo'] != "") echo "<div><p>" . $GLOBALS['errs']['gallery_logo'] . "</p></div>"; ?></div>
					<div class="helpicon"><a id="helplink_main_logo" href="<?php echo $_SESSION['webpath'] ?>help/admin/main_logo" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<div id="main_logo_row" class="clearfix">
						<label for="logo">Main logo</label>
						<span class="logo">
							<span class="clearfix">
							<?php if($main_logo != "" && file_exists($GLOBALS['path_to_root'] . "images/" . $main_logo)){?>
								<img class="logo_image" src="<?php echo $_SESSION['webpath'] ?>images/<?php echo $main_logo ?>?t=<?php echo time(); ?>" alt="Main Logo" title="Main Logo" />
								<a class="remove_link submit button" id="remove_main_logo" href="./?action=remove_logo"><span>Remove main logo</span><img src="<?php echo $_SESSION['webpath'] ?>images/admin/delete.png" alt="Remove logo image"  title="Remove logo image" /></a>
							<?php }else{ ?>
								<img class="logo_image" src="<?php echo $_SESSION['webpath'] ?>images/admin/no_image.gif" alt="Main Logo" title="Main Logo" />
							<?php } ?>
								<input type="file" class="file" name="gallery_logo" id="gallery_logo" />
							</span>
						<?php if($GLOBALS['errs']['logo'] != ""){ ?>
							<span class="smallerror"><?php echo $GLOBALS['errs']['logo'] ?></span>
						<?php } ?>
							Logo alignment:&nbsp;&nbsp;
							<select name="logo_align">
								<option value="left"<?php if($logo_align == "left") echo " selected=\"selected\"";?>>Left</option>
								<option value="center"<?php if($logo_align == "center") echo " selected=\"selected\"";?>>Center</option>
								<option value="right"<?php if($logo_align == "right") echo " selected=\"selected\"";?>>Right</option>
							</select>
						</span>
					</div>
				</div>
				<div class="formrow clearfix">
					<div class="helpicon"><a id="helplink_theme" href="<?php echo $_SESSION['webpath'] ?>help/admin/theme" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="theme">Theme</label>
					<select id="theme_select" name="theme">
						<option value=""<?php if($theme == "") echo " selected=\"selected\"";?>>Default</option>
						<option value="is"<?php if($theme == "is") echo " selected=\"selected\"";?>>International Superstar</option>
						<option value="mibi"<?php if($theme == "mibi") echo " selected=\"selected\"";?>>Mibi</option>
						<option value="custom"<?php if($theme == "custom") echo " selected=\"selected\"";?>>Custom</option>
					</select>
				</div>
				<div id="custom_styles" class="formrow clearfix">
					<div id="custom_colors">
						<label for="custom_colors">Custom colors</label>
						<div class="row clearfix">
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_body_bgcolor">Body background:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_body_bgcolor" id="custom_body_bgcolor" value="<?php echo $custom_body_bgcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_body_bgcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_container_bgcolor">Page background:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_container_bgcolor" id="custom_container_bgcolor" value="<?php echo $custom_container_bgcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_container_bgcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_body_textcolor">Main text:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_body_textcolor" id="custom_body_textcolor" value="<?php echo $custom_body_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_body_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_link_textcolor">Main link:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_link_textcolor" id="custom_link_textcolor" value="<?php echo $custom_link_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_link_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_link_hover_textcolor">Main link hover:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_link_hover_textcolor" id="custom_link_hover_textcolor" value="<?php echo $custom_link_hover_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_link_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_nav_textcolor">Nav menu text:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_nav_textcolor" id="custom_nav_textcolor" value="<?php echo $custom_nav_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_nav_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_nav_hover_textcolor">Nav menu hover text:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_nav_hover_textcolor" id="custom_nav_hover_textcolor" value="<?php echo $custom_nav_hover_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_nav_hover_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_nav_active_textcolor">Nav menu active text:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_nav_active_textcolor" id="custom_nav_active_textcolor" value="<?php echo $custom_nav_active_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_nav_active_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_nav_active_bgcolor">Nav menu active background:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_nav_active_bgcolor" id="custom_nav_active_bgcolor" value="<?php echo $custom_nav_active_bgcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_nav_active_bgcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_bar_bgcolor">Footer background:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_bar_bgcolor" id="custom_bar_bgcolor" value="<?php echo $custom_bar_bgcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_bar_bgcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_bar2_bgcolor">Footer 2nd background:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_bar2_bgcolor" id="custom_bar2_bgcolor" value="<?php echo $custom_bar2_bgcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_bar2_bgcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_bar_textcolor">Footer text:&nbsp;&nbsp;#</label>
								<input type="text" class="text text_hex" maxlength="6" name="custom_bar_textcolor" id="custom_bar_textcolor" value="<?php echo $custom_bar_textcolor ?>" />
								<div class="colorpicker_container"><div style="background-color:#<?php echo $custom_bar_textcolor ?>"></div></div>
								<div class="errordiv smallerror"></div>
							</div>
						</div>
					</div>
					<div id="custom_fonts">
						<label for="custom_fonts">Fonts</label>
						<div class="row clearfix">
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_main_font_family">Main font family:&nbsp;&nbsp;</label>
								<select name="custom_main_font_family">
									<?php echo display_font_family_select($custom_main_font_family) ?>
								</select>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_main_font_size">Main font size:&nbsp;&nbsp;</label>
								<select name="custom_main_font_size" id="custom_main_font_size">
									<?php echo display_font_size_select($custom_main_font_size) ?>
								</select>
							</div>
						</div>
						<div class="row clearfix">
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_nav_font_family">Nav font family:&nbsp;&nbsp;</label>
								<select name="custom_nav_font_family">
									<?php echo display_font_family_select($custom_nav_font_family) ?>
								</select>
							</div>
							<div class="custom_style item clearfix">
								<label class="smalllabel" for="custom_nav_font_size">Nav font size:&nbsp;&nbsp;</label>
								<select name="custom_nav_font_size" id="custom_nav_font_size">
									<?php echo display_font_size_select($custom_nav_font_size) ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="formrow clearfix">
					<div class="helpicon"><a id="helplink_page_layout" href="<?php echo $_SESSION['webpath'] ?>help/admin/page_layout" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="page_layout">Page layout</label>
					<select name="page_layout" id="page_layout">
						<option value="800"<?php if($page_layout == "800") echo " selected=\"selected\"";?>>Fixed width - narrow (800px)</option>
						<option value="900"<?php if($page_layout == "900") echo " selected=\"selected\"";?>>Fixed width - medium (900px)</option>
						<option value="1000"<?php if($page_layout == "1000") echo " selected=\"selected\"";?>>Fixed width - wide (1000px)</option>
						<option value="full"<?php if($page_layout == "full") echo " selected=\"selected\"";?>>Full width (100%)</option>
					</select>					
				</div>
				<div class="startpage formrow clearfix">
					<div class="helpicon"><a id="helplink_startpage" href="<?php echo $_SESSION['webpath'] ?>help/admin/startpage" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<div class="errordiv"><?php if($GLOBALS['errs']['new_splash_image'] != "") echo "<div><p>" . $GLOBALS['errs']['new_splash_image'] . "</p></div>"; ?></div>
					<div class="clearfix">
						<label for="startpage">Start Page</label>
						<select name="startpage" id="startpage">
							<option value="splash"<?php if($startpage == "splash") echo " selected=\"selected\"";?>>Splash Page (slideshow)</option>
							<option value="list"<?php if($startpage == "list") echo " selected=\"selected\"";?>>List all Books</option>
							<option value="book:"<?php if(strpos($startpage, "book:") === 0) echo " selected=\"selected\"";?>>Show Book</option>
						</select>
						<span id="bookselect" class="jsnodisplay nowrap">
							<span>book:</span>
							<select name="startbook">
								<?php display_folder_select(substr($startpage, 5)); ?>
							</select>
						</span>
						<span id="splashpage_interval" class="nowrap jsnodisplay">
							<span>slide interval:</span><input class="text_number text" type="text" name="slideshow_interval" value="<?php echo getElementValue($gallery_node, "slideshow_interval", 0, "") ?>" /><span>s</span>
						</span>
					</div>
<?php 	if($startpage == "splash"){ ?>
					<div id="edit_splashpages_container" class="jsheightauto"><p>Loading...</p><iframe frameborder="0" src="./?action=edit_splash_images"></iframe></div>
<?php 	} ?>
				</div>
				<div class="formrow clearfix">
					<div class="helpicon"><a id="helplink_diplay_mode" href="<?php echo $_SESSION['webpath'] ?>help/admin/display_mode" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="gallery_display">Default display mode</label>
					<select id="gallery_display" name="gallery_display">
						<option value="thumbnails"<?php if($gallery_display == "thumbnails") echo " selected=\"selected\"";?>>Thumbnails</option>
						<option value="strip"<?php if($gallery_display == "strip") echo " selected=\"selected\"";?>>Horizontal Strip</option>
					</select>
					<span id="thumbnail_ratio_select" class="jsnodisplay nowrap">
						<span>Thumbnail ratio:</span>
						<select name="gallery_thumb_ratio">
							<option value="fixed"<?php if($gallery_thumb_ratio == "fixed") echo " selected=\"selected\"";?>>Fixed</option>
							<option value="constrain_h"<?php if($gallery_thumb_ratio == "constrain_h") echo " selected=\"selected\"";?>>Constrain Height</option>
							<option value="constrain_w"<?php if($gallery_thumb_ratio == "constrain_w") echo " selected=\"selected\"";?>>Constrain Width</option>
							<option value="constrain_both"<?php if($gallery_thumb_ratio == "constrain_both") echo " selected=\"selected\"";?>>Constrain Both</option>
						</select>
					</span>
				</div>
				<div class="formrow clearfix" id="thumbnail_dimensions">
					<div class="helpicon"><a id="helplink_thumb_dimensions" href="<?php echo $_SESSION['webpath'] ?>help/admin/thumb_dimensions" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<div class="errordiv"></div>
					<label for="gallery_thumb">Thumbnail dimensions</label>
					<span class="smalllabel">W:</span><input type="text" class="text_number text" name="gallery_thumb_w" value="<?php echo getElementValue($gallery_node, "gallery_thumb_w", 0, "") ?>" />
					<span class="smalllabel">H:</span><input type="text" class="text_number text" name="gallery_thumb_h" value="<?php echo getElementValue($gallery_node, "gallery_thumb_h", 0, "") ?>" />
				</div>
				<div class="formrow clearfix" id="strip_dimensions">
					<div class="errordiv"></div>
					<div class="helpicon"><a id="helplink_strip_dimensions" href="<?php echo $_SESSION['webpath'] ?>help/admin/strip_dimensions" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
					<label for="gallery_strip">Strip dimensions</label>
					<span class="smalllabel">H:</span><input type="text" class="text_number text" name="gallery_strip_h" value="<?php echo getElementValue($gallery_node, "gallery_strip_h", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<div class="halfrow clearfix">
						<div class="helpicon"><a id="helplink_thickbox" href="<?php echo $_SESSION['webpath'] ?>help/admin/thickbox" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
						<label for="enable_thickbox">Enable thickbox</label>
						<input class="checkbox" type="checkbox" id="enable_thickbox" name="enable_thickbox"<?php if(getElementValue($gallery_node, "enable_thickbox", 0, "") == "1") echo " checked=\"true\"";?>/>
					</div>
					<div class="halfrow clearfix">
						<div class="helpicon"><a id="helplink_slideshow" href="<?php echo $_SESSION['webpath'] ?>help/admin/slideshow" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
						<label for="enable_slideshow">Enable slideshow</label>
						<input class="checkbox" type="checkbox" id="enable_slideshow" name="enable_slideshow"<?php if(getElementValue($gallery_node, "enable_slideshow", 0, "") == "1") echo " checked=\"true\"";?>/>
					</div>
				</div>
				<div class="formrow clearfix">
					<div class="halfrow clearfix">
						<div class="helpicon"><a id="helplink_control_panel" href="<?php echo $_SESSION['webpath'] ?>help/admin/control_panel" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
						<label for="enable_control_panel">Enable display control panel</label>
						<input class="checkbox" type="checkbox" id="enable_control_panel" name="enable_control_panel"<?php if(getElementValue($gallery_node, "enable_control_panel", 0, "") == "1") echo " checked=\"true\"";?>/>
					</div>
					<div class="halfrow clearfix">
						<div class="helpicon"><a id="helplink_exif" href="<?php echo $_SESSION['webpath'] ?>help/admin/exif" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
						<label for="enable_exif_display">Display EXIF info</label>
						<input class="checkbox" type="checkbox" id="enable_exif_display" name="enable_exif_display"<?php if(getElementValue($gallery_node, "enable_exif_display", 0, "") == "1") echo " checked=\"true\"";?>/>
					</div>
				</div>
				<div class="formrow clearfix">
					<div class="halfrow clearfix">
						<div class="helpicon"><a id="helplink_book_listing" href="<?php echo $_SESSION['webpath'] ?>help/admin/book_listing" target="_new"><img alt="Help" title="Help" src="<?php echo $_SESSION['webpath'] ?>images/admin/help.png" /></a></div>
						<label for="enable_all_books_list">Enable full book listing</label>
						<input class="checkbox" type="checkbox" id="enable_all_books_list" name="enable_all_books_list"<?php if(getElementValue($gallery_node, "enable_all_books_list", 0, "") == "1") echo " checked=\"true\"";?>/>
					</div>
					<div class="halfrow clearfix">
					</div>
				</div>
			</div>
		
			<h2 class="jsnodisplay">Aliases</h2>
			<div id="aliases" class="tabcontainer_level1 tabcontent section_aliases">
				<div id="aliases_container">
<?php
		$xpresult = $xpath->query("/	gallery/aliases/alias");
		if($_GET[action] == "new_alias" || $xpresult->length == 0){$xtra = 1; }else{ $xtra = 0;}
		for($x=0;$x<$xpresult->length+$xtra;$x++){
			if($x < $xpresult->length && $xpresult->length > 0){
				$domain = getElementValue($xpresult->item($x), "domain", 0, "");
				$gallery_webpath = getElementValue($xpresult->item($x), "gallery_webpath", 0, "");
				$media_path = getElementValue($xpresult->item($x), "media_path", 0, "");
				$media_url = getElementValue($xpresult->item($x), "media_url", 0, "");		
			}else{
				$domain = "";
				$gallery_webpath = "";
				$media_path = "";
				$media_url = "";
			}
?>
					<div class="form_section alias_entry clearfix" id="alias<?php echo $x ?>">
						<div class="colorbar subsection_header clearfix">
							<h3>Alias <span class="alias_counter"><?php echo $x+1 ?></span></h3>
							<span class="remove">
								<a class="remove_link" href="./?action=remove_alias&alias=<?php echo $x ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/delete.png" alt="Remove this alias"  title="Remove this alias" /></a>
							</span>
						</div>
						<div class="formrow clearfix">
							<div class="errordiv"></div>
							<label for="alias[<?php echo $x ?>][domain]">Domain</label>
							<input type="text" class="text_aliases text" name="alias[<?php echo $x ?>][domain]" value="<?php echo $domain ?>" />
						</div>
						<div class="formrow clearfix">
							<div class="errordiv"></div>
							<label for="alias[<?php echo $x ?>][gallery_webpath]">Application webpath</label>
							<input type="text" class="text_aliases text" name="alias[<?php echo $x ?>][gallery_webpath]" value="<?php echo $gallery_webpath ?>" />
						</div>
						<div class="formrow clearfix">
							<div class="errordiv"></div>
							<label for="alias[<?php echo $x ?>][media_path]">Gallery Filepath</label>
							<input type="text" class="text_aliases text" name="alias[<?php echo $x ?>][media_path]" value="<?php echo $media_path ?>" />
						</div>
						<div class="formrow clearfix">
							<div class="errordiv"></div>
							<label for="alias[<?php echo $x ?>][media_url]">Gallery URL</label>
							<input type="text" class="text_aliases text" name="alias[<?php echo $x ?>][media_url]" value="<?php echo $media_url ?>" />
						</div>
					</div>
<?php
			}
?>
				</div>
				<a class="submit button" id="add_alias" href="./?action=new_alias"><span>Add new alias</span><img src="<?php echo $_SESSION['webpath'] ?>images/admin/add.png" alt="Add new alias"  title="Add new alias" /></a>
		
			</div>
<?php // BEGIN BOOKS ?>
			<h2 class="jsnodisplay">Books</h2>
			<div id="books" class="tabcontainer_level1 tabcontent section_books clearfix">
			</div>
<?php // BEGIN NAVIGATION ?>
			<h2 class="jsnodisplay">Navigation</h2>
			<div id="navigation" class="tabcontainer_level1 tabcontent section_navigation clearfix">
				<div id="gallery_navigation_style_row" class="formrow clearfix">
					<label for="gallery_navigation_style">Navigation style</label>
					<select id="gallery_navigation_style" name="gallery_navigation_style">
						<option value="text"<?php if($galelry_navigation_style == "text") echo " selected=\"selected\"";?>>Text</option>
						<option value="images"<?php if($gallery_navigation_style == "images") echo " selected=\"selected\"";?>>Images</option>
					</select>
					<div id="nav_image_format_select" class="jsnodisplay clearfix">
						<label for="navigation_image_format">Navigation image format:&nbsp;</label>
						<select name="navigation_image_format">
							<option value="png"<?php if($navigation_image_format == "png") echo " selected=\"selected\"";?>>PNG</option>
							<option value="gif"<?php if($navigation_image_format == "gif") echo " selected=\"selected\"";?>>GIF</option>
							<option value="jpg"<?php if($navigation_image_format == "jpg") echo " selected=\"selected\"";?>>JPG</option>
						</select>
					</div>
				</div>
<?php		if(sizeof(glob($_SESSION['media_path'] . "*", GLOB_ONLYDIR)) == 0){ ?>
<p class="error">Either you have not added an Alias yet to represnt the current domain (<?php echo $_SERVER['HTTP_HOST']?>), or the value you entered for "Gallery Filepath" is incorrect, or there are no subfolders present in your gallery filepath. You must correct this before you can add any Books to the navigation menu.</p>
<?php		}else{ ?>
				<div id="navigation_container" class="form_section clearfix">
					<div class="subsection_header">
						<span class="title">Book Title</span>
						<span class="folder">Folder Name</span>
						<span class="icons">
							<span class="move">Move</span>
							<span class="delete">Delete</span>
							<span class="image">Image</span>
						</span>
					</div>
<?php			$xpresult = $xpath->query("gallery/navigation/item");
				if($_GET[action] == "new_nav_item" || $xpresult->length == 0){$xtra = 1; }else{ $xtra = 0;}
				for($x=0;$x<$xpresult->length+$xtra;$x++){
					if($x < $xpresult->length && $xpresult->length > 0){
						$title = getElementValue($xpresult->item($x), "title", 0, "");
						$folder = getElementValue($xpresult->item($x), "folder", 0, "");
					}else{
						$title = "";
						$folder = "";
					}
					$nav_image = $GLOBALS['path_to_root'] . "images/nav/" . strtolower($folder);
					$image_action = "add";
					if(file_exists($nav_image . "." . $_SESSION['cm__gallery']['navigation_image_format'])){ $image_action = "edit"; }
?>
					<div id="navrow_<?php echo $x ?>" class="navrow formrow clearfix">
						<div class="errordiv"></div>
						<input type="text" class="text_navigation text" name="nav_item[<?php echo $x ?>][title]" value="<?php echo $title ?>" />
						<select class="folder_books" name="nav_item[<?php echo $x ?>][folder]">
							<?php display_folder_select($folder); ?>
						</select>
						<span class="icons">
							<span class="move moveup">
								<?php if($x > 0){?><a href="./?action=move_nav_item&amp;book=<?php echo $x ?>&amp;direction=up"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/up.png" alt="Move this navigation item up"  title="Move this navigation item up"></a><?php }else{?>&nbsp;<?php } ?>
							</span>
							<span class="move movedown">
								<?php if($x < ($xpresult->length-1+$xtra)){ ?><a href="./?action=move_nav&amp;book=<?php echo $x ?>&amp;direction=down"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/down.png" alt="Move this navigation item down" title="Move this navigation item down"></a><?php }else{?>&nbsp;<?php } ?>
							</span>
							<span class="remove"><a class="remove_link" href="./?action=delete_nav&amp;book=<?php echo $x ?>"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/delete.png" alt="Remove this item from navigation menu" title="Remove this item from navigation menu" /></a></span>
							<span class="edit_nav_image"><a href="./?action=edit_nav_images&amp;book=<?php echo urlencode($folder) ?>" target="_new"><img src="<?php echo $_SESSION['webpath'] ?>images/admin/<?php echo $image_action ?>_image.png" alt="<?php echo ucfirst($image_action)?> Navigation Image" title="<?php echo ucfirst($image_action)?> Navigation Image" /></a></span>
						</span>
					</div>
<?php
				}
?>
				</div>
				<div class="formrow clearfix">
					<a class="submit button" id="add_nav_item" href="./?action=new_nav_item"><span>Add book to navigation</span><img src="<?php echo $_SESSION['webpath'] ?>images/admin/add.png" alt="Add book to navigation menu"  title="Add book to navigation menu" /></a>
				</div>
<?php			
			}
?>
			</div>
		
			<h2 class="jsnodisplay">Metadata</h2>
			<div id="metadata" class="tabcontainer_level1 tabcontent section_metadata clearfix">
<?php		
		$xpresult = $xpath->query("gallery/metadata");
		$metadata_node = $xpresult->item(0);
?>
				<div class="formrow clearfix">
					<label for="meta_author">Author</label>
					<input type="text" class="text_meta text" name="meta_author" value="<?php echo getElementValue($metadata_node, "meta_author", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<label for="meta_company_name">Company Name</label>
					<input type="text" class="text_meta text" name="meta_company_name" value="<?php echo getElementValue($metadata_node, "meta_company_name", 0, "") ?>" />
				</div>
				<div class="formrow clearfix">
					<label for="meta_keywords">Keywords</label>
					<textarea name="meta_keywords"><?php echo getElementValue($metadata_node, "meta_keywords", 0, "") ?></textarea>
				</div>
				<div class="formrow clearfix">
					<label for="meta_description">Description</label>
					<textarea name="meta_description"><?php echo getElementValue($metadata_node, "meta_description", 0, "") ?></textarea>
				</div>
				<div class="formrow clearfix">
					<label for="meta_classification">Classification</label>
					<textarea name="meta_classification"><?php echo getElementValue($metadata_node, "meta_classification", 0, "") ?></textarea>
				</div>
			</div>
			<div id="bottom_bar" class="thickbar">
				<input class="submit" type="submit" value="Save settings" />
			</div>
		</div>
	</div>
<?php
	}
?>
	</form>
<?php
	if(!$_GET["embed"]){
?>
</body>
</html>

<script type="text/javascript">
<?php 
		if($_GET[action] != "edit_nav_image" && $_GET[action] != "edit_splash_images"){
?>
form_id = "config_form";
function read_form_data(){
	Formpage.form_fields[form_id] = new Array();
//	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "gallery_name", label: 'Gallery name', type: 'text', data_type: 'text', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "admin_email", label: 'Admin email', type: 'text', data_type: 'email', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "email_subject", label: 'Email subject', type: 'text', data_type: 'text', min_occurs: 1, max_occurs: 1};

	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_body_bgcolor", label: 'Body background color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_container_bgcolor", label: 'Page background color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_body_textcolor", label: 'Main text color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_link_textcolor", label: 'Main link color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_link_hover_textcolor", label: 'Main link hover color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_nav_textcolor", label: 'Nav menu text color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_nav_hover_textcolor", label: 'Nav menu hover text color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_nav_active_textcolor", label: 'Nav menu active text color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_nav_active_bgcolor", label: 'Nav menu active bg color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_bar_bgcolor", label: 'Footer background color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_bar2_bgcolor", label: 'Footer 2nd background color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "custom_bar_textcolor", label: 'Footer text color', type: 'text', data_type: 'hex', min_occurs: 1, max_occurs: 1};

	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "slideshow_interval", label: 'Slideshow interval', type: 'text', data_type: 'number', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "startbook", label: 'Starting book', type: 'select', data_type: 'text', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "gallery_thumb_w", label: 'Thumbnail width', type: 'text', data_type: 'number', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "gallery_thumb_h", label: 'Thumbnail height', type: 'text', data_type: 'number', min_occurs: 1, max_occurs: 1};
	Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "gallery_strip_h", label: 'Strip height', type: 'text', data_type: 'number', min_occurs: 1, max_occurs: 1};
	$(".navrow").each(function(i, el){
		Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "nav_item[" + i + "][title]", label: 'Book title', type: 'text', data_type: 'text', min_occurs: 1, max_occurs: 1};	
		Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "nav_item[" + i + "][folder]", label: 'Folder name', type: 'select', data_type: 'text', min_occurs: 1, max_occurs: 1};
	});
	$(".alias_entry").each(function(i, t){
		Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "alias[" + i + "][domain]", label: 'Alias Domain', type: 'text', data_type: 'URL', min_occurs: 1, max_occurs: 1};	
		Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "alias[" + i + "][gallery_webpath]", label: 'Path from root', type: 'text', data_type: 'dirpath_nix', min_occurs: 1, max_occurs: 1};	
		Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "alias[" + i + "][media_path]", label: 'Gallery Filepath', type: 'text', data_type: 'dirpath_<?php echo $_SESSION['server_OS'] ?>', min_occurs: 1, max_occurs: 1};	
		Formpage.form_fields[form_id][Formpage.form_fields[form_id].length] = {name: "alias[" + i + "][media_url]", label: 'Gallery URL', type: 'text', data_type: 'URL', min_occurs: 1, max_occurs: 1};	
	});
}
read_form_data();
<?php 	}?>
</script>
<?php
	}
}
?>