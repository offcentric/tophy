<?php
class XmlFile{
	var $xmldom;
	var $xmlfile;
	var $xpath;
	
	function XmlFile($xmlfile){
		$this->xmldom = new DOMDocument();
		$this->xmlfile = $xmlfile;
		if(file_exists($this->xmlfile)){
			if(!$this->xmldom->load(realpath($this->xmlfile))){
				echo "ERROR!!! XML doc <strong>" . $this->xmlfile . "</strong> invalid!";
				die();
			}else{
				// get new xpath context
				$this->xpath = new DOMXPath($this->xmldom);
			}
		}else{
			echo "ERROR!!! XML doc <strong>" . $this->xmlfile . "</strong> not found!";
			die();			
		}
	}
}
?>