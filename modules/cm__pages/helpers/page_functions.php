<?php
/********************************************************/
/* file: 		page_functions.php 						*/
/* module:		CM__PAGES								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	functions specific for 					*/
/*				pages core module	 					*/
/********************************************************/

function get_page($address){
	$_SESSION['page_modules'][0] = $_SESSION['cm__pages']['module_name'];
	$_SESSION['page_template'] = "";
	$_SESSION['page_template_module'] = "";
	$_SESSION['pagename'] = "";
	$_SESSION['custom_page_id'] = "";
	
	if(!isset($address) || $address == ""){
		$address = $_SERVER['REQUEST_URI'];
	}

	if(strpos($address, "?") !== false){
		$address = substr($address, 0, strpos($address, "?"));
	}
	if(substr($address, strlen($address)-1) == "/"){
		$address = substr($address, 0, strlen($address)-1);
	}
	if(substr($address, 0, 1) != "/"){
		$address = "/" . $address;
	}
	foreach($_SESSION['pages'] as $page){
		$permalink = $page['permalink'];
		if(substr($permalink, strlen($permalink)-1) == "/"){
			$permalink = substr($permalink, 0, strlen($permalink)-1);
		}
		if($permalink == $address){
			$_SESSION['custom_page_id'] = $page['id'];
			$GLOBALS['pagenode'] = $GLOBALS['cm__pages_xml']->xpath->query("/pages/page[@id=" . $_SESSION['custom_page_id'] . "]")->item(0);
			$_SESSION['pagename'] = get_page_name();
			$_SESSION['page_template'] = $page['page_template'];
			$_SESSION['page_template_module'] = $page['page_template_module'];
			break;
		}
	}

	if(in_array($_SESSION['page_template_module'], $_SESSION['modules_enabled'])){
		$template_path = MODULESBASEPATH . $_SESSION['page_template_module'] . "/views/" . $_SESSION['page_template'] . ".php";
	}else{
		$template_path = COREBASEPATH . "views/" . $_SESSION['page_template'] . ".php";
	}
	if(file_exists($template_path)){
		return $template_path;
		$GLOBALS['filefound'] = true;
	}elseif($address != "/404"){ //Just so we don't ever get an infinite loop here, don't ever call a 404 on a missing 404 page.
		$GLOBALS['filefound'] = false;
	}
}

function get_page_title(){
	return getElementValue($GLOBALS['pagenode'], "title", 0, "");
}
function get_page_name(){
	return getElementValue($GLOBALS['pagenode'], "pagename", 0, "");
}

function get_page_presentations(){
	$xpresult_presentations = $GLOBALS['cm__pages_xml']->xpath->query("/pages/page[@id=" . $_SESSION['custom_page_id'] . "]/presentations/presentation");

	$x = 0;
	foreach($xpresult_presentations as $presentation){
		$template = getElementValue($presentation, "template", 0, "detail");
		$xpresult_content_items = $GLOBALS['cm__pages_xml']->xpath->query("content_items/item", $presentation);

		$y = 0;

		foreach($xpresult_content_items as $content_item){
			$name = getElementValue($content_item, "name", 0, "");
			${$template}[$name] = getElementValue($content_item, "value", 0, "");
			$y++;
		}
		
		$modelpath = "";
		$templatepath = "";

		if(file_exists(COREBASEPATH . "models/" . $template . ".php")){
			$modelpath = COREBASEPATH . "models/" . $template . ".php";
		}
		if(file_exists(MODULESBASEPATH . $_SESSION['cm__pages']['module_name'] . "/models/" . $template . ".php")){
			$modelpath = MODULESBASEPATH . $_SESSION['cm__pages']['module_name'] . "/models/" . $template . ".php";			
		}

		if(file_exists(COREBASEPATH . "views/partial/" . $template . ".php")){
			$templatepath = COREBASEPATH . "views/partial/" . $template . ".php";
		}
		if(file_exists(MODULESBASEPATH . $_SESSION['cm__pages']['module_name'] . "/views/" . $template . ".php")){
			$templatepath = MODULESBASEPATH . $_SESSION['cm__pages']['module_name'] . "/views/" . $template . ".php";			
		}
		if(file_exists(THEMESBASEPATH . "views/modules/cm__pages/" . $template . ".php")){
			$templatepath = THEMESBASEPATH . "views/modules/cm__pages/" . $template . ".php";			
		}
		
		if($modelpath != "") include $modelpath;
		if($templatepath != "") include $templatepath;
		$x++;
	}
}
?>