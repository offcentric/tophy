<?php
/********************************************************/
/* file: 		entry.class.php 						*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		07/07/2011								*/
/* version:		0.1										*/
/* description:	Definition of Entry class 				*/
/********************************************************/

class Entry{
	var $id;
	var $currentnode;
	var $previous_title, $next_title;
	var $previous_date, $next_date;
	var $previous_id, $next_id;
	var $published;
	var $timestamp;
	var $title;
	var $mood;
	var $listening;
	var $reading;
	var $watching;
	var $content;
	var $comments;
	var $taglinks;
	var $tagcount;
	var $currenttags;
	
	function Entry(){
		$this->id = $GLOBALS['cm__journal']['entry_id'];
		if($this->id != ""){
			$this->getCurrentNode($this->id);
		}
	}
	
	function getCurrentNode($id){
		if(($_SESSION['cm__admin']['privileges'] > 1 && $_GET["admin"]) || !$_SESSION['cm__admin']['enable_publishing']){
			$this->currentnode = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@id=" . $id ."]")->item(0);
		}else{
			$this->currentnode = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@id=" . $id ." and @published=1]")->item(0);				
		}
		$this->getNodeData();
	}
	
	function getPreviousNode(){
		if(($_SESSION['cm__admin']['privileges'] > 1 && $_GET["admin"]) || !$_SESSION['cm__admin']['enable_publishing']){
			$previous = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@id=" . $this->id . "]/preceding-sibling::entry[1]");
		}else{
			$previous = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@id=" . $this->id . "]/preceding-sibling::entry[@published='1'][1]");
		}
		if($previous->length > 0){
			$previousnode = $previous->item(0);
			$this->previous_title = getElementValue($previousnode, "title", 0, "");
			if($this->previous_title == ""){
				$this->previous_date = getElementValue($previousnode, "timestamp", 0, "");
				$this->previous_title = date('j F Y', $this->previous_date);
			}
			$this->previous_id = $previousnode->getAttribute("id");
		}
	}

	function getNextNode(){
		if(($_SESSION['cm__admin']['privileges'] > 1 && $_GET["admin"]) || !$_SESSION['cm__admin']['enable_publishing']){
			$next = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@id=" . $this->id . "]/following-sibling::entry[1]");
		}else{
			$next = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[@id=" . $this->id . "]/following-sibling::entry[@published='1'][1]");
		}
		if($next->length > 0){
			$nextnode = $next->item(0);
			$this->next_title = getElementValue($nextnode, "title", 0, "");
			if($this->next_title == ""){
				$this->next_date = getElementValue($nextnode, "timestamp", 0, "");
				$this->next_title = date('j F Y', $this->next_date);
			}
			$this->next_id = $nextnode->getAttribute("id");
		}
	}
	
	function getNodeData(){
		//get entry data
		if($this->currentnode){
			$this->timestamp = getElementValue($this->currentnode, "timestamp", 0, "");
			$this->published = $this->currentnode->getAttribute("published");
			$this->posted_by = getElementValue($this->currentnode, "posted_by", 0, "");
			$this->last_editor = getElementValue($this->currentnode, "last_editor", 0, "");
			$this->title = stripslashes(getElementValue($this->currentnode, "title", 0, ""));
			$this->mood = stripslashes(getElementValue($this->currentnode, "mood", 0, ""));
			$this->listening = stripslashes(getElementValue($this->currentnode, "listening", 0, ""));
			$this->reading = stripslashes(getElementValue($this->currentnode, "reading", 0, ""));
			$this->watching = stripslashes(getElementValue($this->currentnode, "watching", 0, ""));
			$this->content = stripslashes(getElementValue($this->currentnode, "content", 0, ""));
			$this->content = utf8_decode($this->content);
			$tagnodes = $GLOBALS['cm__journal_xml']->xpath->query("tags/tag", $this->currentnode);

			if($_GET["template"] == "main"){
				$this->comments = $this->currentnode->getElementsByTagName("comment");
				$tagcount = 1;
				$this->taglinks = "";

				foreach($tagnodes as $tag){
					$this->taglinks .= "<a href=\"" . $_SESSION['cm__journal']['webpath'] . "tag/" . trim($tag->nodeValue) . "\">" . trim($tag->nodeValue) . "</a>";
					if($tagcount < $tagnodes->length) $this->taglinks .= ", ";
					$tagcount++;
				}

			}else{
				if(!$_GET["newentry"]){

					$this->currenttags = array();
					foreach($tagnodes as $tag){
						array_unshift($GLOBALS['tags']->taglist, $tag->nodeValue);	
						$this->currenttags[] = $tag->nodeValue;
					}

					$GLOBALS['tags']->makeTaglistUnique();
				}
			}
		}
	}
}
?>