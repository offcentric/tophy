<?php
class Userlist{
	var $usersxml;
	var $xmldom;
	var $xpath;
	var $xpresult;
	
	function Userlist(){
		$this->usersxml = "xml/users.xml";
		$this->xmldom = new DOMDocument();
		if(!$this->xmldom->load(realpath($this->usersxml))){
			echo "error!!! xml doc invalid or not found at ";
			echo realpath($this->usersxml);
			exit;
		}else{
			// get new xpath context
			$this->xpath = new DOMXPath($this->xmldom);
			$this->getListing();
		}		
	}

	function getListing(){
		$this->xpresult = $this->xpath->query("/users/user");		
	}
}
?>