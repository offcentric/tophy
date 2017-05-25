<?php
/********************************************************/
/* file: 		listing.class.php 						*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		06/07/2011								*/
/* version:		0.1										*/
/* description:	Listing class definition				*/
/********************************************************/

class Listing{
	var $sortorder;
	var $lookup_year, $lookup_month;
	var $pagenumber;
	var $startpage;
	var $nodeset;
	var $monthname, $month, $year;
	var $haspreviouspage, $hasnextpage;
	var $searchstring;
	var $xpresult;
	var $total_results;
	var $page_start, $page_end;
	var $filter;
	var $publish_filter;
	var $and_publish_filter;
	
	function Listing($filter){
		$this->filter = $filter;
		
		if($this->filter == ""){ 
			if(isset($GLOBALS['cm__journal']['filter'])){
				$this->filter = $GLOBALS['cm__journal']['filter'];
			}else{
				$this->filter = "";
			}
		}

		if(isset($_SESSION['cm__journal']['sortorder'])){
			$this->sortorder = $_SESSION['cm__journal']['sortorder'];
		}else{
			$this->sortorder = 'asc';
		}

		if(!empty($_GET["y"])){
			$this->lookup_year = $_GET["y"];
		}else{
			$this->lookup_year = date("Y");
		}

		if(!empty($_GET["m"])){
			$this->lookup_month = $_GET["m"];
		}else{
			$this->lookup_month = date("n");
		}

		if(isset($GLOBALS['cm__journal']['page'])){
			$this->pagenumber = intval($GLOBALS['cm__journal']['page']);
		}else{
			$this->pagenumber = 1;
		}
		
		if(($_SESSION['cm__admin']['privileges'] > 1 && $_GET["admin"]) || !$_SESSION['cm__admin']['enable_publishing']){
			$this->publish_filter = "";
			$this->and_publish_filter = "";
		}else{
			$this->publish_filter = "[@published=1]";
			$this->and_publish_filter = " and @published=1";
		}
		$this->searchstring = $_REQUEST["searchstring"];

		//if we are on startpage, show 5 newest entries
		if($this->searchstring == "" && $_GET["tag"] == "" && empty($_GET["from"])){
			$this->startpage = true;
		}

		$this->nodeset = new mdasort;
		$this->nodeset->aSortkeys = array("sortby"=>"timestamp", "sortorder"=>"desc");
		$this->nodeset->aData = array();

		if($this->filter == "" || $this->filter == "month"){
			if($this->searchstring == "" && $GLOBALS['cm__journal']['tag'] == ""){
				@$this->getListing();
			}elseif(strlen($this->searchstring) >= 3){
				$this->getListing("search");
			}elseif($this->searchstring != "" && strlen($this->searchstring < 3)){
				//if search string is shorter than 3 chars, don't do a damn thing for now
			}
		}else{
			if($this->filter == "tag"){
				$this->getListing("tag");
			}elseif($this->filter == "user_poster"){
				$this->getListing("user_poster");
			}elseif($this->filter == "user_editor"){
				$this->getListing("user_editor");
			}else{
			}
		}
	}
	
	function getListing($type){
		$this->nodeset->aData = (array) null;
		if($type == "tag"){
			$this->getListingByTag();
		}elseif($type == "search"){
			$this->getListingBySearchResults();			
		}elseif($type == "user_poster"){
			$this->getListingByUser("posted_by");	
		}elseif($type == "user_editor"){
			$this->getListingByUser("last_editor");			
		}else{
			$this->getListingSorted();			
		}
		$this->nodeset->sort();
	}
	
	function getListingSorted(){
		if($this->filter == "month"){
			// show all post for a certain month
			if(intval($lookup_month) == 12){
				$next_month = 1;
				$next_year = $this->lookup_year + 1;
			}else{
				$next_month = $this->lookup_month + 1;
				$next_year = $this->lookup_year;
			}
			$from_date = intval(encodeDate("1/". $this->lookup_month . "/" . $this->lookup_year));
			$to_date = intval(encodeDate("0/".$next_month . "/" . $next_year));
		}elseif($_GET["filter"] == "archive"){
			$from_date = 0;
			if($_SESSION['cm__journal']['maxmonths_browse'] > 12){
				$to_year = date("Y", time()) - floor($_SESSION['cm__journal']['maxmonths_browse'] / 12);
				$to_month = date("m", time()) - $_SESSION['cm__journal']['maxmonths_browse'] % 12;
			}else{
				$to_year = date("Y", time());
				$to_month = date("m", time()) - $_SESSION['cm__journal']['maxmonths_browse'];
			}
			if($to_month < 1){
				$to_month += 12;
				$to_year--;
			}
			$to_date = intval(encodeDate("0/".$to_month . "/" . $to_year));
		}else{
			$from_date = 0;
			$to_date = time();
		}
		$this->xpresult = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[timestamp>" . $from_date . " and timestamp<" . $to_date . $this->and_publish_filter . "]");			
		$this->monthname = date("F", $from_date);
		$this->month = date("n", $from_date);
		$this->year = date("Y", $from_date);
		
		$this->getPagedListing();
	}

	function getListingBySearchResults(){
		//display search results
		$this->xpresult = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[contains(translate(content,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '".strtolower($this->searchstring)."')" . $this->and_publish_filter . "]");
		$this->total_results = $this->xpresult->length;
		$this->getPagedListing();
	}
	
	function getListingByTag(){
		//display posts with a certain tag
		$this->xpresult = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry" . $this->publish_filter . "/tags/tag[translate(.,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') = '". strtolower(urldecode($GLOBALS['cm__journal']['tag'])) . "']/../..");
		$this->total_results = $this->xpresult->length;
		$this->getPagedListing();
	}

	function getListingByUser($list){
		// show all posts from a specific user
		$this->xpresult = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry" . $this->publish_filter . "/" . $list. "[translate(.,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') = '". strtolower($_GET["user"]) . "']/..");
		$this->total_results = $this->xpresult->length;
		$this->getPagedListing();
	}

	function getPagedListing(){
		$end = $this->xpresult->length - ($_SESSION['cm__journal']['entries_per_page'] * ($this->pagenumber-1));
		$start = $end - $_SESSION['cm__journal']['entries_per_page'];
		$this->hasnextpage = true;
		$this->haspreviouspage = true;
		$this->page_start = ($_SESSION['cm__journal']['entries_per_page'] * ($this->pagenumber-1)) + 1;
		$this->page_end = $this->pagenumber * $_SESSION['cm__journal']['entries_per_page'];
		if($end == $this->xpresult->length) $this->haspreviouspage = false;
		if($start <= 0){ $start = 0; $this->hasnextpage = false;}
		for($i = $start; $i < $end; $i++){
			$this->nodeset->aData[$i] = $this->xpresult->item($i);
		}		
	}
}
?>