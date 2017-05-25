<?php
/********************************************************/
/* file: 		journal_functions.php 					*/
/* module:		CM_JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		05/07/2011								*/
/* version:		0.1										*/
/* description:	functions used in Journal core module 	*/
/********************************************************/

function encodeContent($content){
	
	$patterns[0] = '/(<)(object width="[0-9]+" height="[0-9]+")(><)(param.* value="http:\/\/www\.youtube\.com[^"]+")(><)(\/param)((><)(param[^>]+)(><)(\/param))*(><)(embed src="http:\/\/www\.youtube\.com[^"]+"[^>]+)(><)(\/embed)(><)(\/object)(>)/';

	if(preg_match($patterns[0], $content, $matches) > 0){
		foreach($matches as $match){
			$content = str_ireplace("<", "[", $content);			
			$content = str_ireplace(">", "]", $content);			
		}
	}
	
	$content = str_ireplace("&", "&amp;", $content);
	$content = str_ireplace("<", "&lt;", $content);
	$content = str_ireplace(">", "&gt;", $content);
	$content = str_ireplace("[a href", "<a href", $content);
	$content = str_ireplace("[/a]", "</a>", $content);
	$content = str_ireplace("[img src", "<img src", $content);
	$content = str_ireplace("[quote]", "<quote>", $content);
	$content = str_ireplace("[/quote]", "</quote>", $content);
	$content = str_ireplace("[strike]", "<strike>", $content);
	$content = str_ireplace("[/strike]", "</strike>", $content);
	$content = str_ireplace("[pre]", "<pre>", $content);
	$content = str_ireplace("[/pre]", "</pre>", $content);
	$content = str_ireplace("[strong]", "<strong>", $content);
	$content = str_ireplace("[/strong]", "</strong>", $content);
	$content = str_ireplace("[small]", "<small>", $content);
	$content = str_ireplace("[/small]", "</small>", $content);
	$content = str_ireplace("[em]", "<em>", $content);
	$content = str_ireplace("[/em]", "</em>", $content);
	$content = str_ireplace("\r\n", "<br />\n", $content);
	$content = str_ireplace("[object", "<object", $content);
	$content = str_ireplace("[/object]", "</object>", $content);
	$content = str_ireplace("[embed", "<embed", $content);
	$content = str_ireplace("[/embed]", "</embed>", $content);
	$content = str_ireplace("[param", "<param", $content);
	$content = str_ireplace("[/param]", "</param>", $content);
	$content = str_ireplace("[br]", "<br />", $content);
	$content = str_ireplace("[br /]", "<br />", $content);
	$content = str_ireplace("[br/]", "<br />", $content);
	
	$patterns[0] = '/\[imageblock src="([^"]*)" caption="([^"]*)"\s?\/?\]/i';
	$replacements[0] = '<div class="imageblock clearfix"><div class="inside"><img src="$1" /><div>$2</div></div></div>';
	$patterns[1] = '/\[imageblock float="left" src="([^"]*)" caption="([^"]*)"\s?\/?\]/i';
	$replacements[1] = '<div class="imageblock left"><div class="inside"><img src="$1" /><div>$2</div></div></div>';
	$patterns[2] = '/\[imageblock float="right" src="([^"]*)" caption="([^"]*)"\s?\/?\]/i';
	$replacements[2] = '<div class="imageblock right"><div class="inside"><img src="$1" /><div>$2</div></div></div>';
	$patterns[3] = '/\[flash id="([^"]*)" swf="([^"]*)" width="([^"]*)" height="([^"]*)" flashvars="([^"]*)"\s?\/?\]/i';
	$replacements[3] = '<span id="$1" style="display:block;width:$3px;height:$4px;margin:auto"><span id="$1_embed"></span></span><script type="text/javascript">var flashvars = {$5}; var params = {allowScriptAccess: "always",wmode: "transparent"}; var attributes = {id: "$1",name: "$1"}; swfobject.embedSWF("$2", "$1_embed", "$3", "$4", "9.0.0", false, flashvars, params, attributes);</script>';
	$content = preg_replace($patterns, $replacements, $content);

	$content = str_ireplace("\"]", "\">", $content);
	$content = str_ireplace("\"/]", "\"/>", $content);
	$content = str_ireplace("\" /]", "\" />", $content);

//	$content = str_ireplace("<a href=\"".$_SESSION['journal_url'], "<a href=\"/", $content);	
//	$content = str_ireplace("<img src=\"".$_SESSION['journal_url'], "<img src=\"/", $content);	
	return $content;
}

function decodeContent($content){
	$patterns[0] = '/<div class="imageblock clearfix"><div class="inside"><img src="([^"]+)" \/><div>([^"]+)<\/div><\/div><\/div>/i';
	$replacements[0] = '[imageblock src="$1" caption="$2" /]';
	$patterns[1] = '/<div class="imageblock left"><div class="inside"><img src="([^"]+)" \/><div>([^"]+)<\/div><\/div><\/div>/i';
	$replacements[1] = '[imageblock float="left" src="$1" caption="$2" /]';
	$patterns[2] = '/<div class="imageblock right"><div class="inside"><img src="([^"]+)" \/><div>([^"]+)<\/div><\/div><\/div>/i';
	$replacements[2] = '[imageblock float="right" src="$1" caption="$2" /]';
	$patterns[3] = '/<span id="([^"]*)" style="display:block;width:([0-9]+)px;height:([0-9]+)px;margin:auto"><span id="[^"]+"><\/span><\/span><script type="text\/javascript">var flashvars = {([^}]+)}; var params = {allowScriptAccess: "always",wmode: "transparent"}; var attributes = {id: "[^"]+",name: "[^"]+"}; swfobject\.embedSWF\("([^"]+)", "[^"]+", "[0-9]+", "[0-9]+", "9\.0\.0", false, flashvars, params, attributes\);<\/script>/i';
	$replacements[3] = '[flash id="$1" swf="$5" width="$2" height="$3" flashvars="$4" /]';

	$content = preg_replace($patterns, $replacements, $content);

	$content = str_replace("\n", "", $content);
	$content = str_replace("<br />", "\n", $content);
	$content = str_replace("<pre>", "[pre]", $content);
	$content = str_replace("</pre>", "[/pre]", $content);
	$content = str_replace("<strike>", "[strike]", $content);
	$content = str_replace("</strike>", "[/strike]", $content);
	$content = str_replace("<strong>", "[strong]", $content);
	$content = str_replace("</strong>", "[/strong]", $content);
	$content = str_replace("<small>", "[small]", $content);
	$content = str_replace("</small>", "[/small]", $content);
	$content = str_replace("<em>", "[em]", $content);
	$content = str_replace("</em>", "[/em]", $content);
	$content = str_replace("<img src", "[img src", $content);
	$content = str_ireplace("<quote>", "[quote]", $content);
	$content = str_ireplace("</quote>", "[/quote]", $content);
	$content = str_replace("<a href", "[a href", $content);
	$content = str_replace("</a>", "[/a]", $content);
	$content = str_replace("&amp;", "&", $content);
	$content = str_replace("&lt;", "<", $content);
	$content = str_replace("&gt;", ">", $content);
	$content = str_ireplace("<object", "[object", $content);
	$content = str_ireplace("</object>", "[/object]", $content);
	$content = str_ireplace("<embed", "[embed", $content);
	$content = str_ireplace("</embed>", "[/embed]", $content);
	$content = str_ireplace("<param", "[param", $content);
	$content = str_ireplace("</param>", "[/param]", $content);
	$content = str_ireplace("<br />", "[br /]", $content);
	$content = str_ireplace("\">", "\"]", $content);
	$content = str_ireplace("\"/>", "\"/]", $content);
	$content = str_ireplace("\" />", "\" /]", $content);
	
	return $content;
}

function killSpammers(){
	//check if IP is not banned
	$xmlfile_banned = $_SESSION['journal_filepath'] . "/xml/banned.xml";
	$dom_banned = new DOMDocument("1.0");
	$kill = false;
	
	if(!$dom_banned->load(realpath($xmlfile_banned))){
		echo "error!!! xml doc invalid or not found at ";
		echo realpath($xmlfile_banned);
		exit;
	}else{
		$xpath_banned = new DOMXPath($dom_banned);
		$xpresult = $xpath_banned->query("/banned/IP");
		for($i = 0; $i < $xpresult->length; $i++){
			if($_SERVER['REMOTE_ADDR'] == $xpresult->item($i)->textContent){
				$kill = true;
				break;
			}
		}
		if(!$kill){
			//check if username is banned
			$xpresult = $xpath_banned->query("/banned/user");
			for($i = 0; $i < $xpresult->length; $i++){
				if(strpos(strtolower($_POST["name"]), strtolower($xpresult->item($i)->textContent)) !== false){
					$kill = true;
					break;
				}
			}
		}
		if(!$kill){
			// check for spam content in the comment
			$xpresult = $xpath_banned->query("/banned/text");
			for($i = 0; $i < $xpresult->length; $i++){
				if(strpos(strtolower($_POST["content"]), strtolower($xpresult->item($i)->textContent)) !== false){
					$kill = true;
					break;
				}
			}
		}
	}
	return $kill;
}

function createTitleLink($title){
	return substr(strtolower(str_replace("#", "", str_replace("?", "", str_replace(" ", "-", $title)))),0,100);
}
?>