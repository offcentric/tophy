<?php
/********************************************************/
/* file: 		global_functions.php 					*/
/* module:		COMMON CODEBASE							*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		05/07/2011								*/
/* version:		0.1										*/
/* description:	functions used across various modules 	*/
/********************************************************/

function get_filepath($matches, $filetype, $request_theme, $request_module){
	$filepaths = array("local" => "", "custom_theme" => "", "default_theme" => "", "module" => "", "common" => "");
	
	$filepaths['local'] = $matches[1] . "/" . $matches[2];		
	$filepaths['default_theme'] = THEMESBASEPATH . $GLOBALS['system_pathnames']['default_theme'] .  "/". $matches[1] . "/" . $matches[2];
	$filepaths['common'] = COREBASEPATH . $matches[1] . "/" . $matches[2];

	if($request_module != ""){
		$filepaths['local'] = $matches[1] . "/". $GLOBALS['system_pathnames']['modules'] . "/" . $request_module . "/" . $matches[2];
		$filepaths['default_theme'] = THEMESBASEPATH . $GLOBALS['system_pathnames']['default_theme'] .  "/". $matches[1] . "/"  . $GLOBALS['system_pathnames']['modules'] . "/" . $request_module . "/" . $matches[2];
		$filepaths['module'] = MODULESBASEPATH . $request_module . "/". $matches[1] . "/" . $matches[2];
	}
	
	if($request_theme != ""){
		$filepaths['local'] = $matches[1] . "/" . $GLOBALS['system_pathnames']['themes'] . "/" . $request_theme .  "/" . $matches[2];
		$filepaths['custom_theme'] = THEMESBASEPATH . $request_theme .  "/". $matches[1] . "/" . $matches[2];
		if($request_module != ""){
			$filepaths['local'] = $matches[1] . "/" . $GLOBALS['system_pathnames']['themes'] . "/" . $request_theme .  "/" . $GLOBALS['system_pathnames']['modules'] . "/" . $request_module . "/" . $matches[2];
			$filepaths['custom_theme'] = THEMESBASEPATH . $request_theme .  "/". $matches[1] . "/"  . $GLOBALS['system_pathnames']['modules'] . "/" . $request_module . "/" . $matches[2];
		}
	}

	$file = null;

	foreach($filepaths as $path){
		if($path != ""){
			if(file_exists($path)){
				$file = $path;
				break;
			}else{
				if(($filetype == $GLOBALS['system_pathnames']['scripts'] || $filetype = $GLOBALS['system_pathnames']['styles']) && file_exists($path . ".php")){
					$file = $path . ".php";
					break;
				}
			}
		}
	}
	return $file;
}

function write_content_type_header($type){
	switch($type){
		case 'scripts':
			header('Content-Type: text/javascript');
			break;
		case 'styles':
			header('Content-Type: text/css');
			break;
		case 'gif':
			header('Content-Type: image/gif');
			break;
		case 'png':
			header('Content-Type: image/png');
			break;
		case 'jpg':
		case 'jpeg':
			header('Content-Type: image/jpeg');
			break;
	}
}

function get_html_template($template_name, $module){
	$template_content = "";

	if(isset($module) && $module != ""){
		$html_template_path['module'] = MODULESBASEPATH . $module . "/views/html_templates/" . $template_name . ".html";
		$html_template_path['theme'] = THEMESBASEPATH . $_SESSION['theme'] . "/html_templates/" . $GLOBALS['system_pathnames']['modules'] . "/" . $module . "/" . $template_name . ".html";
		$html_template_path['local'] = $_SESSION['filepath'] . "html_templates/" . $GLOBALS['system_pathnames']['modules'] . "/" . $module . "/" . $template_name . ".html";
	}else{
		$html_template_path['common'] = COREBASEPATH . "views/html_templates/" . $template_name . ".html";
		$html_template_path['theme'] = THEMESBASEPATH . $_SESSION['theme'] . "/html_templates/" . $template_name . ".html";
		$html_template_path['local'] = $_SESSION['filepath'] . "html_templates/" . $template_name . ".html";		
	}

	if(file_exists($html_template_path['local'])){
		$template_handle = fopen($html_template_path['local'], "r");
		$template_content = fread($template_handle, filesize($html_template_path['local']));
		fclose($template_handle);
	}elseif(file_exists($html_template_path['theme'])){
		$template_handle = fopen($html_template_path['theme'], "r");
		$template_content = fread($template_handle, filesize($html_template_path['theme']));
		fclose($template_handle);
	}else{
		if(isset($module) && $module != ""){
			if(file_exists($html_template_path['module'])){
				$template_handle = fopen($html_template_path['module'], "r");
				$template_content = fread($template_handle, filesize($html_template_path['module']));
				fclose($template_handle);
			}
		}else{
			if(file_exists($html_template_path['common'])){
				$template_handle = fopen($html_template_path['common'], "r");
				$template_content = fread($template_handle, filesize($html_template_path['common']));
				fclose($template_handle);
			}
		}
	}
	
	return $template_content;
}

function replace_template_placeholders($template_html, $item_content){
	$output = $template_html;
	foreach($item_content as $data){
		$output = str_replace("{{" . key($item_content) . "}}", $data, $output);		
		next($item_content);
	}
	return $output;
}

function get_image_type($image){
	
	$image_format = "";
	switch(exif_imagetype($image['tmp_name'])){
		case 1:
			return "gif";
			break;
		case 2:
			return "jpg";
			break;
		case 3:
			return "png";
			break;
		case 4:
			return "swf";
			break;
		case 5:
			return "psd";
			break;
		case 6:
			return "bmp";
			break;
		case 7:
		case 8:
			return "tif";
			break;
		default:
			return "UNKNOWN";
			break;
	}
}


function sanitize_input($text){
	$text = str_ireplace("href=\"javascript\"", "href", $text);
	$text = str_ireplace("href=javascript", "href", $text);
	$text = str_ireplace("href='javascript'", "href", $text);
	$text = str_ireplace("<script", "", $text);
	$text = str_ireplace("</script>", "", $text);
	$text = str_ireplace("onload", "on-load", $text);
	$text = str_ireplace("onunload", "on-unload", $text);
	$text = str_ireplace("onmousedown", "on-mouse-down", $text);
	$text = str_ireplace("onmouseup", "on-mouse-up", $text);
	$text = str_ireplace("onmouseover", "on-mouse-over", $text);
	$text = str_ireplace("onmouseout", "on-mouse-out", $text);
	$text = str_ireplace("onmousemomve", "on-mouse-move", $text);
	$text = str_ireplace("onclick", "on-click", $text);
	$text = str_ireplace("onfocus", "on-focus", $text);
	$text = str_ireplace("onblur", "on-blur", $text);
	$text = str_ireplace("onchange", "on-change", $text);
	return $text;
}

function encrypt_password($pass, $salt){
	return hash("sha256", hash("md5", $pass . $salt));
}

function parse_fraction($numerator, $denominator){
	if(($numerator % 10 == 0) && ($denominator % 10 == 0)){
		$numerator /= 10;
		$denominator /= 10;
	}
	if(($denominator % 10 == 0 && $numerator > $denominator) || $denominator == 1){
		return $numerator/$denominator; 
		
	}else{
		return $numerator."/".$denominator;		
	}
}

function display_folder_select($selected_value){
	echo  "			<option value=\"\"> -- Select folder -- </option>";
	foreach(glob($_SESSION['media_path'] . "*", GLOB_ONLYDIR) as $dirpath) {
		$dirname = substr($dirpath, strlen($_SESSION['media_path']));
		$book = str_replace("&", "%26", $dirname);
		if($dirname == $selected_value){$selected = " selected=\"selected\"";}else{$selected = "";}
		echo "			<option value=\"" . $book . "\"" . $selected . ">" . ucwords($dirname) . "</option>";
	}	
}

function display_font_family_select($selected_value){
	$fonts = array(
		array("value" => "Arial, Helvetica, sans-serif", "label" => "Arial / Helvetica"),
		array("value" => "'Arial Black', sans-serif", "label" => "Arial Black"),
		array("value" => "'Arial Narrow', sans-serif", "label" => "Arial Narrow"),
		array("value" => "'Courier New', serif", "label" => "Courier New"),
		array("value" => "Futura, sans-serif", "label" => "Futura"),
		array("value" => "Garamond, serif", "label" => "Garamond"),
		array("value" => "Georgia, serif", "label" => "Georgia"),
		array("value" => "Impact, sans-serif", "label" => "Impact"),
		array("value" => "'Lucida Console', serif", "label" => "Lucida Console"),
		array("value" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif", "label" => "Lucida Sans / Lucida Grande"),
		array("value" => "Tahoma, Geneva, sans-serif", "label" => "Tahoma / Geneva"),
		array("value" => "Times, Times New Roman, serif", "label" => "Times"),
		array("value" => "'Trebuchet MS', sans-serif", "label" => "Trebuchet MS"),
		array("value" => "Univers, sans-serif", "label" => "Univers"),
		array("value" => "Verdana, sans-serif", "label" => "Verdana"),
	);
		foreach($fonts as $font){
			if(strtolower($font['value']) == strtolower($selected_value)){$selected = " selected=\"selected\"";}else{$selected = "";}
			echo "		<option value=\"" . $font['value'] . "\" style=\"font:12px " . $font['value'] . "\"" . $selected . ">" . $font['label'] . "</option>\n";
		}
}

function display_font_size_select($selected_value){
	$out = "";
	$out .= "<option value=\"0.7em\" style=\"font-size:1em\""; if($selected_value=="0.7em"){$out .= " selected=\"selected\"";} $out .= ">Smallest</option>\n";
	$out .= "<option value=\"0.8em\" style=\"font-size:1.1em\""; if($selected_value=="0.8em"){$out .= " selected=\"selected\"";} $out .= ">Smaller</option>\n";
	$out .= "<option value=\"0.9em\" style=\"font-size:1.2em\""; if($selected_value=="0.9em"){$out .= " selected=\"selected\"";} $out .= ">Small</option>\n";
	$out .= "<option value=\"1em\" style=\"font-size:1.3em\""; if($selected_value=="1em"){$out .= " selected=\"selected\"";} $out .= ">Normal</option>\n";
	$out .= "<option value=\"1.1em\" style=\"font-size:1.4em\""; if($selected_value=="1.1em"){$out .= " selected=\"selected\"";} $out .= ">Large</option>\n";
	$out .= "<option value=\"1.2em\" style=\"font-size:1.5em\""; if($selected_value=="1.2em"){$out .= " selected=\"selected\"";} $out .= ">Larger</option>\n";
	$out .= "<option value=\"1.3em\" style=\"font-size:1.6em\""; if($selected_value=="1.3em"){$out .= " selected=\"selected\"";} $out .= ">Largest</option>\n";
	echo $out;
}

function checkbox_to_boolean($checkbox_value){
	if($checkbox_value == "on"){
		return 1;
	}else{
		return 0;
	}
}

function generate_random_string($length = 8){
	$password = "";
	$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 

	$i = 0;    
	while ($i < $length) { 
    	$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		$password .= $char;
		$i++;
  }
  return $password;
}

function get_index($filename){
	return substr($filename, strrpos($filename, "_")+1, 2);
}

function log_error($message){
	$error_file = "errors.txt";
	$fh = fopen($error_file, "a");
	$backtrace = debug_backtrace();
	$trace_function = $backtrace[1]["function"];
	$trace_line = $backtrace[1]["line"];
	$trace_file = $backtrace[1]["file"];
	fwrite($fh, date("c", time()) . " :: " . "Error in function \"" . $trace_function . "()\", " . $trace_file . " at line " . $trace_line . ": " . $message . "\n");
	fclose($fh);
}

function cleanComment($comment){
	$comment = stripslashes($comment);
	$comment = utf8_decode($comment);
	$comment = sanitize_input($comment);
	$comment = str_replace("<a href", "<a target=\"_blank\" href", $comment);
//	$comment = str_replace("\n", "<br />", $comment);
	return $comment;
}

function get_mime_type($filename) {
	$fn = strtolower(basename($filename));
	if(preg_match("/^.*\.ai$/",$fn)) {
		return("application/illustrator"); }
	if(preg_match("/^.*\.bin$/",$fn)) {
		return("application/octet-stream"); }
	if(preg_match("/^.*\.pdf$/",$fn)) {
		return("application/pdf"); }
	if(preg_match("/^.*\.ps$/",$fn)) {
		return("application/postscript"); }
	if(preg_match("/^.*\.rtf$/",$fn)) {
		return("application/rtf"); }
	if(preg_match("/^.*\.sit$/",$fn)) {
		return("application/stuffit"); }
	if(preg_match("/^.*\.flv$/",$fn)) {
		return("application/x-flash-video"); }
	if(preg_match("/^.*\.xul$/",$fn)) {
		return("application/vnd.mozilla.xul+xml"); }
	if(preg_match("/^.*\.mdb$/",$fn)) {
		return("application/vnd.ms-access"); }
	if(preg_match("/^.*\.xls$/",$fn)) {
		return("application/vnd.ms-excel"); }
	if(preg_match("/^.*\.ppt$/",$fn)) {
		return("application/vnd.ms-powerpoint"); }
	if(preg_match("/^.*\.pps$/",$fn)) {
		return("application/vnd.ms-powerpoint"); }
	if(preg_match("/^.*\.doc$/",$fn)) {
		return("application/msword"); }
	if(preg_match("/^.*\.7z$/",$fn)) {
		return("application/x-7z-compressed"); }
	if(preg_match("/^.*\.cue$/",$fn)) {
		return("application/x-cue"); }
	if(preg_match("/^.*\.torrent$/",$fn)) {
		return("application/x-bittorrent"); }
	if(preg_match("/^.*\.blender$/",$fn)) {
		return("application/x-blender"); }
	if(preg_match("/^.*\.blend$/",$fn)) {
		return("application/x-blender"); }
	if(preg_match("/^.*\.BLEND$/",$fn)) {
		return("application/x-blender"); }
	if(preg_match("/^.*\.dvi\.bz2$/",$fn)) {
		return("application/x-bzdvi"); }
	if(preg_match("/^.*\.bz$/",$fn)) {
		return("application/x-bzip"); }
	if(preg_match("/^.*\.bz2$/",$fn)) {
		return("application/x-bzip"); }
	if(preg_match("/^.*\.tar\.bz$/",$fn)) {
		return("application/x-bzip-compressed-tar"); }
	if(preg_match("/^.*\.tar\.bz2$/",$fn)) {
		return("application/x-bzip-compressed-tar"); }
	if(preg_match("/^.*\.tbz$/",$fn)) {
		return("application/x-bzip-compressed-tar"); }
	if(preg_match("/^.*\.tbz2$/",$fn)) {
		return("application/x-bzip-compressed-tar"); }
	if(preg_match("/^.*\.pdf\.bz2$/",$fn)) {
		return("application/x-bzpdf"); }
	if(preg_match("/^.*\.ps\.bz2$/",$fn)) {
		return("application/x-bzpostscript"); }
	if(preg_match("/^.*\.iso$/",$fn)) {
		return("application/x-cd-image"); }
	if(preg_match("/^.*\.chm$/",$fn)) {
		return("application/x-chm"); }
	if(preg_match("/^.*\.tar\.gz$/",$fn)) {
		return("application/x-compressed-tar"); }
	if(preg_match("/^.*\.tgz$/",$fn)) {
		return("application/x-compressed-tar"); }
	if(preg_match("/^.*\.ui$/",$fn)) {
		return("application/x-designer"); }
	if(preg_match("/^.*\.dvi$/",$fn)) {
		return("application/x-dvi"); }
	if(preg_match("/^.*\.exe$/",$fn)) {
		return("application/x-executable"); }
	if(preg_match("/^.*\.pfa$/",$fn)) {
		return("application/x-font-type1"); }
	if(preg_match("/^.*\.pfb$/",$fn)) {
		return("application/x-font-type1"); }
	if(preg_match("/^.*\.gsf$/",$fn)) {
		return("application/x-font-type1"); }
	if(preg_match("/^.*\.afm$/",$fn)) {
		return("application/x-font-afm"); }
	if(preg_match("/^.*\.bdf$/",$fn)) {
		return("application/x-font-bdf"); }
	if(preg_match("/^.*\.psf$/",$fn)) {
		return("application/x-font-linux-psf"); }
	if(preg_match("/^.*\.psf\.gz$/",$fn)) {
		return("application/x-gz-font-linux-psf"); }
	if(preg_match("/^.*\.pcf$/",$fn)) {
		return("application/x-font-pcf"); }
	if(preg_match("/^.*\.pcf\.Z$/",$fn)) {
		return("application/x-font-pcf"); }
	if(preg_match("/^.*\.pcf\.gz$/",$fn)) {
		return("application/x-font-pcf"); }
	if(preg_match("/^.*\.spd$/",$fn)) {
		return("application/x-font-speedo"); }
	if(preg_match("/^.*\.ttf$/",$fn)) {
		return("application/x-font-ttf"); }
	if(preg_match("/^.*\.ttc$/",$fn)) {
		return("application/x-font-ttf"); }
	if(preg_match("/^.*\.gz$/",$fn)) {
		return("application/x-gzip"); }
	if(preg_match("/^.*\.pdf\.gz$/",$fn)) {
		return("application/x-gzpdf"); }
	if(preg_match("/^.*\.jar$/",$fn)) {
		return("application/x-java-archive"); }
	if(preg_match("/^.*\.class$/",$fn)) {
		return("application/x-java"); }
	if(preg_match("/^.*\.jnlp$/",$fn)) {
		return("application/x-java-jnlp-file"); }
	if(preg_match("/^.*\.js$/",$fn)) {
		return("application/javascript"); }
	if(preg_match("/^.*\.mkv$/",$fn)) {
		return("video/x-matroska"); }
	if(preg_match("/^.*\.mka$/",$fn)) {
		return("audio/x-matroska"); }
	if(preg_match("/^.*\.mif$/",$fn)) {
		return("application/x-mif"); }
	if(preg_match("/^.*\.exe$/",$fn)) {
		return("application/x-ms-dos-executable"); }
	if(preg_match("/^.*\.wri$/",$fn)) {
		return("application/x-mswrite"); }
	if(preg_match("/^.*\.perl$/",$fn)) {
		return("application/x-perl"); }
	if(preg_match("/^.*\.php$/",$fn)) {
		return("application/x-php"); }
	if(preg_match("/^.*\.rar$/",$fn)) {
		return("application/x-rar"); }
	if(preg_match("/^.*\.rb$/",$fn)) {
		return("application/x-ruby"); }
	if(preg_match("/^.*\.swf$/",$fn)) {
		return("application/x-shockwave-flash"); }
	if(preg_match("/^.*\.srt$/",$fn)) {
		return("application/x-subrip"); }
	if(preg_match("/^.*\.sub$/",$fn)) {
		return("text/x-microdvd"); }
	if(preg_match("/^.*\.sub$/",$fn)) {
		return("text/x-mpsub"); }
	if(preg_match("/^.*\.tar$/",$fn)) {
		return("application/x-tar"); }
	if(preg_match("/^.*\.gtar$/",$fn)) {
		return("application/x-tar"); }
	if(preg_match("/^.*\.tar\.Z$/",$fn)) {
		return("application/x-tarz"); }
	if(preg_match("/^.*\.theme$/",$fn)) {
		return("application/x-theme"); }
	if(preg_match("/^.*\.bak$/",$fn)) {
		return("application/x-trash"); }
	if(preg_match("/^.*\.old$/",$fn)) {
		return("application/x-trash"); }
	if(preg_match("/^.*\.sik$/",$fn)) {
		return("application/x-trash"); }
	if(preg_match("/^.*\.xhtml$/",$fn)) {
		return("application/xhtml+xml"); }
	if(preg_match("/^.*\.zip$/",$fn)) {
		return("application/zip"); }
	if(preg_match("/^.*\.au$/",$fn)) {
		return("audio/basic"); }
	if(preg_match("/^.*\.snd$/",$fn)) {
		return("audio/basic"); }
	if(preg_match("/^.*\.sid$/",$fn)) {
		return("audio/prs.sid"); }
	if(preg_match("/^.*\.aiff$/",$fn)) {
		return("audio/x-aiff"); }
	if(preg_match("/^.*\.aif$/",$fn)) {
		return("audio/x-aiff"); }
	if(preg_match("/^.*\.flac$/",$fn)) {
		return("audio/x-flac"); }
	if(preg_match("/^.*\.mid$/",$fn)) {
		return("audio/midi"); }
	if(preg_match("/^.*\.midi$/",$fn)) {
		return("audio/midi"); }
	if(preg_match("/^.*\.m4a$/",$fn)) {
		return("audio/mp4"); }
	if(preg_match("/^.*\.aac$/",$fn)) {
		return("audio/mp4"); }
	if(preg_match("/^.*\.mp4$/",$fn)) {
		return("video/mp4"); }
	if(preg_match("/^.*\.m4v$/",$fn)) {
		return("video/mp4"); }
	if(preg_match("/^.*\.3gp$/",$fn)) {
		return("video/3gpp"); }
	if(preg_match("/^.*\.3gpp$/",$fn)) {
		return("video/3gpp"); }
	if(preg_match("/^.*\.mod$/",$fn)) {
		return("audio/x-mod"); }
	if(preg_match("/^.*\.mp3$/",$fn)) {
		return("audio/mpeg"); }
	if(preg_match("/^.*\.mpga$/",$fn)) {
		return("audio/mpeg"); }
	if(preg_match("/^.*\.m3u$/",$fn)) {
		return("audio/x-mpegurl"); }
	if(preg_match("/^.*\.mpeg$/",$fn)) {
		return("video/mpeg"); }
	if(preg_match("/^.*\.mpg$/",$fn)) {
		return("video/mpeg"); }
	if(preg_match("/^.*\.mp2$/",$fn)) {
		return("video/mpeg"); }
	if(preg_match("/^.*\.mpe$/",$fn)) {
		return("video/mpeg"); }
	if(preg_match("/^.*\.vob$/",$fn)) {
		return("video/mpeg"); }
	if(preg_match("/^.*\.qt$/",$fn)) {
		return("video/quicktime"); }
	if(preg_match("/^.*\.mov$/",$fn)) {
		return("video/quicktime"); }
	if(preg_match("/^.*\.qtvr$/",$fn)) {
		return("video/quicktime"); }
	if(preg_match("/^.*\.qtif$/",$fn)) {
		return("image/x-quicktime"); }
	if(preg_match("/^.*\.qif$/",$fn)) {
		return("image/x-quicktime"); }
	if(preg_match("/^.*\.asf$/",$fn)) {
		return("video/x-ms-asf"); }
	if(preg_match("/^.*\.wmv$/",$fn)) {
		return("video/x-ms-wmv"); }
	if(preg_match("/^.*\.avi$/",$fn)) {
		return("video/x-msvideo"); }
	if(preg_match("/^.*\.divx$/",$fn)) {
		return("video/x-msvideo"); }
	if(preg_match("/^.*\.vlc$/",$fn)) {
		return("audio/x-mpegurl"); }
	if(preg_match("/^.*\.asx$/",$fn)) {
		return("audio/x-ms-asx"); }
	if(preg_match("/^.*\.wmx$/",$fn)) {
		return("audio/x-ms-asx"); }
	if(preg_match("/^.*\.wma$/",$fn)) {
		return("audio/x-ms-wma"); }
	if(preg_match("/^.*\.pls$/",$fn)) {
		return("audio/x-scpls"); }
	if(preg_match("/^.*\.wav$/",$fn)) {
		return("audio/x-wav"); }
	if(preg_match("/^.*\.bmp$/",$fn)) {
		return("image/bmp"); }
	if(preg_match("/^.*\.wbmp$/",$fn)) {
		return("image/vnd.wap.wbmp"); }
	if(preg_match("/^.*\.gif$/",$fn)) {
		return("image/gif"); }
	if(preg_match("/^.*\.jpeg$/",$fn)) {
		return("image/jpeg"); }
	if(preg_match("/^.*\.jpg$/",$fn)) {
		return("image/jpeg"); }
	if(preg_match("/^.*\.jpe$/",$fn)) {
		return("image/jpeg"); }
	if(preg_match("/^.*\.pict$/",$fn)) {
		return("image/x-pict"); }
	if(preg_match("/^.*\.png$/",$fn)) {
		return("image/png"); }
	if(preg_match("/^.*\.svg$/",$fn)) {
		return("image/svg+xml"); }
	if(preg_match("/^.*\.svgz$/",$fn)) {
		return("image/svg+xml-compressed"); }
	if(preg_match("/^.*\.tif$/",$fn)) {
		return("image/tiff"); }
	if(preg_match("/^.*\.tiff$/",$fn)) {
		return("image/tiff"); }
	if(preg_match("/^.*\.3ds$/",$fn)) {
		return("image/x-3ds"); }
	if(preg_match("/^.*\.eps$/",$fn)) {
		return("image/x-eps"); }
	if(preg_match("/^.*\.ico$/",$fn)) {
		return("image/x-ico"); }
	if(preg_match("/^.*\.icns$/",$fn)) {
		return("image/x-icns"); }
	if(preg_match("/^.*\.pbm$/",$fn)) {
		return("image/x-portable-bitmap"); }
	if(preg_match("/^.*\.pgm$/",$fn)) {
		return("image/x-portable-graymap"); }
	if(preg_match("/^.*\.ppm$/",$fn)) {
		return("image/x-portable-pixmap"); }
	if(preg_match("/^.*\.psd$/",$fn)) {
		return("image/x-psd"); }
	if(preg_match("/^.*\.rgb$/",$fn)) {
		return("image/x-rgb"); }
	if(preg_match("/^.*\.sgi$/",$fn)) {
		return("image/x-sgi"); }
	if(preg_match("/^.*\.xbm$/",$fn)) {
		return("image/x-xbitmap"); }
	if(preg_match("/^.*\.css$/",$fn)) {
		return("text/css"); }
	if(preg_match("/^.*\.txt$/",$fn)) {
		return("text/plain"); }
	if(preg_match("/^.*\.asc$/",$fn)) {
		return("text/plain"); }
	if(preg_match("/^.*\.rdf$/",$fn)) {
		return("text/rdf"); }
	if(preg_match("/^.*\.rss$/",$fn)) {
		return("application/rss+xml"); }
	if(preg_match("/^.*\.atom$/",$fn)) {
		return("application/atom+xml"); }
	if(preg_match("/^.*\.sgml$/",$fn)) {
		return("text/sgml"); }
	if(preg_match("/^.*\.csv$/",$fn)) {
		return("text/csv"); }
	if(preg_match("/^COPYING$/",$fn)) {
		return("text/x-copying"); }
	if(preg_match("/^CREDITS$/",$fn)) {
		return("text/x-credits"); }
	if(preg_match("/^.*\.dtd$/",$fn)) {
		return("text/x-dtd"); }
	if(preg_match("/^.*\.html$/",$fn)) {
		return("text/html"); }
	if(preg_match("/^.*\.htm$/",$fn)) {
		return("text/html"); }
	if(preg_match("/^.*\.gvp$/",$fn)) {
		return("text/x-google-video-pointer"); }
	if(preg_match("/^INSTALL$/",$fn)) {
		return("text/x-install"); }
	if(preg_match("/^.*\.java$/",$fn)) {
		return("text/x-java"); }
	if(preg_match("/^.*\.log$/",$fn)) {
		return("text/x-log"); }
	if(preg_match("/^[Mm]akefile$/",$fn)) {
		return("text/x-makefile"); }
	if(preg_match("/^GNUmakefile$/",$fn)) {
		return("text/x-makefile"); }
	if(preg_match("/^.*\.diff$/",$fn)) {
		return("text/x-patch"); }
	if(preg_match("/^.*\.patch$/",$fn)) {
		return("text/x-patch"); }
	if(preg_match("/^.*\.py$/",$fn)) {
		return("text/x-python"); }
	if(preg_match("/^README*$/",$fn)) {
		return("text/x-readme"); }
	if(preg_match("/^.*\.nfo$/",$fn)) {
		return("text/x-readme"); }
	if(preg_match("/^.*\.spec$/",$fn)) {
		return("text/x-rpm-spec"); }
	if(preg_match("/^.*\.scm$/",$fn)) {
		return("text/x-scheme"); }
	if(preg_match("/^.*\.sql$/",$fn)) {
		return("text/x-sql"); }
	if(preg_match("/^.*\.tcl$/",$fn)) {
		return("text/x-tcl"); }
	if(preg_match("/^.*\.url$/",$fn)) {
		return("text/x-uri"); }
	if(preg_match("/^.*\.xmi$/",$fn)) {
		return("text/x-xmi"); }
	if(preg_match("/^.*\.xml$/",$fn)) {
		return("application/xml"); }
	if(preg_match("/^.*\.xsl$/",$fn)) {
		return("application/xml"); }
	if(preg_match("/^.*\.xslt$/",$fn)) {
		return("application/xml"); }
	if(preg_match("/^.*\.xbl$/",$fn)) {
		return("application/xml"); }
	return("binary/octet-stream");
}

function hash_password($plaintext){
    return hash('sha256',trim($plaintext) . 'some5alt1inyourW0und!');
}
