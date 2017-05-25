<?php
/********************************************************/
/* file: 		content_loader.php 						*/
/* module:		COMMON CODEBASE							*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		06/07/2011								*/
/* version:		0.1										*/
/* description:	general content loader					*/
/********************************************************/

foreach($_SESSION['modules_enabled'] as $module){
	$GLOBALS[$module . "_xml"] = new XmlFile(realpath($_SESSION[$module]['content_xml_file']));
	// for Pages Core Module we need to load in the permalinks of the custom pages so that they can be used in the path translator
	if($module == $_SESSION['cm__pages']['module_name']){
		
		$pagesnode = $GLOBALS['cm__pages_xml']->xpath->query("/pages")->item(0);
		$xpresult_pages = $GLOBALS['cm__pages_xml']->xpath->query("/pages/page");
		for($x=0;$x<$xpresult_pages->length;$x++){
			$pagenode = $xpresult_pages->item($x);
			$_SESSION['pages'][$x]['id'] = getAttributeValue($pagesnode, "page", $x, "id", "");
			$_SESSION['pages'][$x]['page_template'] = getElementValue($pagenode, "page_template", 0, "detail");
			$_SESSION['pages'][$x]['page_template_module'] = getElementValue($pagenode, "page_template_module", 0, "");
			$_SESSION['pages'][$x]['title'] = getElementValue($pagenode, "title", 0, "detail");
			$_SESSION['pages'][$x]['permalink'] = getElementValue($pagenode, "permalink", 0, "detail");
		}
	}
}

?>