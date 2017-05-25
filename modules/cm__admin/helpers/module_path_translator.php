<?php

if($page_template_name == "help"){
	if(count($matches[1]) > 2){
		$request_help_section = $matches[1][1];
		$request_help_page = $matches[1][2];
	}else{
		$request_help_section = "general";
		$request_help_page = $matches[1][1];
	}
}


?>