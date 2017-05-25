<?php
$_SESSION['pagename'] = "help";

//open xml docment, throw exception on fail
$helpxml = COREBASEPATH . "/xml/help.xml";

$helpdom = new DOMDocument();
$helpdom->preserveWhiteSpace = false;
if(!$helpdom->load(realpath($helpxml))){
	echo "error!!! xml doc invalid or not found at ";
	echo realpath($helpxml);
	exit;
}else{
	$helpdom->formatOutput = true;
	// get new xpath context
	$helpxpath = new DOMXPath($helpdom);
	if(!$_GET["embed"]){ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include COREBASEPATH . "/views/partial/head.php"; ?>

<body class="help">
<?php
	}
?>

	<div class="helpcontent">
<?php
	if($request_help_page != ""){
		echo $helpxpath->query("/content/section[@name='" . $request_help_section . "']/page[@name='".$request_help_page ."']")->item(0)->nodeValue;
	}else{
		foreach($helpxpath->query("/content/section[@name='" . $request_help_section . "']/page") as $pagenode){
			if($pagenode->childNodes->item(0)->nodeType=="4"){
				echo $pagenode->childNodes->item(0)->nodeValue;
				echo "<hr />";
			}
		}
	}
?>
	</div>
<?php
	if(!$_GET["embed"]){
?>
</body>
</html>
<?php
	}
}
?>