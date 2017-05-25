<?php
class User{
	var $dataxml;
	var $xmldom;
	var $xpath;
	var $currentnode;
	var $id;
	var $name;
	var $password;
	var $salt;
	var $email;
	var $is_active;
	var $is_admin;
	
	function User(){
		$this->dataxml = "xml/users.xml";
		$this->xmldom = new DOMDocument();
		if(!$this->xmldom->load(realpath($this->dataxml))){
			echo "error!!! xml doc invalid or not found at ";
			echo realpath($this->dataxml);
			exit;
		}else{
			// get new xpath context
			$this->xpath = new DOMXPath($this->xmldom);
		}
		if(isset($_REQUEST["user"])){
			$this->name = $_REQUEST["user"];
		}elseif(isset($_REQUEST["name"])){
			$this->name = $_REQUEST["name"];
		}else{
			if(!isset($_REQUEST["newuser"]))
				$this->name = $_SESSION['user'];
		}

		if(!isset($_REQUEST["newuser"])){
			if($this->name != ""){
				$this->xpresult = $this->xpath->query("/users/user[name='" . $this->name . "']");	
				if($this->xpresult->length == 0){
					$GLOBALS['messages']['error'] = "User " . $this->name . " not found.<br />";
				}else{
					$this->currentnode = $this->xpresult->item(0);
					$this->getNodeData();
				}
			}else{
				$GLOBALS['messages']['error'] = "No username given.<br />";
			}
		}else{
			$this->is_admin = $_POST["is_admin"];
			$this->email = $_POST["email"];
			$this->is_active = $_POST["is_active"];
		}
	}
	
	function getNodeData(){
		$this->id = $this->currentnode->getAttribute("id");
		$this->name = getElementValue($this->currentnode, "name", 0, "");
		$this->password = getElementValue($this->currentnode, "pass", 0, "");
		$this->salt = getElementValue($this->currentnode, "salt", 0, "");
		$this->is_admin = (boolean) $this->currentnode->getAttribute("admin");
		$this->email = getElementValue($this->currentnode, "email", 0, "");
		$this->date_joined = getElementValue($this->currentnode, "date_joined", 0, "");
		$this->timezone = getElementValue($this->currentnode, "timezone", 0, "");
		$this->is_active = (boolean) getElementValue($this->currentnode, "active", 0, "");
	}

	function getUserNodeById($id){
		$this->currentnode = $this->xpath->query("/users/user[@id=" . $id ."]")->item(0);				
		$this->getNodeData();
	}

}
?>