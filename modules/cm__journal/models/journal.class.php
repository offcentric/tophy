<?php
/********************************************************/
/* file: 		journal.class.php 						*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Main Journal class to contain			*/
/*				DOM object for journal XML 				*/
/********************************************************/

class Journal{
	var $dataxml;
	var $xmldom;
	var $xpath;

	function setXML(){
		$this->xmldom = new DOMDocument();
		$this->dataxml = $_SESSION['cm__journal']['content_xml_file'];
		if(file_exists(realpath($this->dataxml))){
			if(!$this->xmldom->load(realpath($this->dataxml))){
				echo "ERROR!!! XML doc <strong>" . $this->dataxml . "</strong> invalid!";
				die();
			}else{
				// get new xpath context
				$this->xpath = new DOMXPath($this->xmldom);
			}
		}else{
			echo "ERROR!!! XML doc <strong>" . $this->dataxml . "</strong> not found!";
			die();			
		}
	}
}

?>