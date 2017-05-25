<?php
class Tags{
	var $alltags = array();
	var $taglist;
	
	function getAllTags(){
		if((@$_SESSION['cm__admin']['privileges'] > 1 && @$_GET["admin"]) || !@$_SESSION['cm__admin']['enable_publishing']){
			$xpresulttags = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry/tags/tag");
		}else{
			$xpresulttags = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@published=1]/tags/tag");
		}
		foreach($xpresulttags as $tag){
			$tagexists = false;
			$tagcount = sizeof($this->alltags);
			for($j=0;$j<sizeof($this->alltags);$j++){
				if(strtolower($this->alltags[$j]['tagname'])==strtolower($tag->nodeValue)){
					$this->alltags[$j]['count']++;
					$this->alltags[$j]['id'] = $tag->parentNode->parentNode->getAttribute("id");
					$tagexists = true;
					break;
				}
			}
			if(!$tagexists){
				$this->alltags[$tagcount]['id'] = $tag->parentNode->parentNode->getAttribute("id");
				$this->alltags[$tagcount]['count'] = 1;			
				$this->alltags[$tagcount]['tagname'] = strtolower($tag->nodeValue);
			}
		}

		$tagsid = array();
		$tagsname = array();
		$tagscount = array();

		foreach ($this->alltags as $key => $row) {
			$tagsid[$key]  = $row['id'];
			$tagsname[$key]  = $row['tagname'];
			$tagscount[$key]  = $row['count'];
		}

		if(@$GLOBALS['cm__journal']['display'] == "taglist"){
			if($GLOBALS['cm__journal']['tags']['sortby'] == "name"){
				array_multisort($tagsname, SORT_ASC, $this->alltags);
			}else if($GLOBALS['cm__journal']['tags']['sortby'] == "popular"){
				array_multisort($tagscount, SORT_DESC, $this->alltags);		
			}else if($GLOBALS['cm__journal']['tags']['sortby'] == "recent"){
				array_multisort($tagsid, SORT_DESC, $this->alltags);
			}
		}else{
			array_multisort($tagscount, SORT_DESC, $this->alltags);	
		}

		$this->taglist = array();
		foreach($this->alltags as $tag){ $this->taglist[] = $tag['tagname']; }
	}

	function makeTaglistUnique(){
		$this->taglist = array_unique($this->taglist);
	}
}
?>