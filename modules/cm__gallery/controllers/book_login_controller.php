<?php
if($_SESSION['cm__gallery']['bookpage'] != ''){
	$d = $_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . '/' . $_SESSION['cm__gallery']['bookpage'] . "/" ;
}else{
	$d = $_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . '/' ;
}

$pattern_xml = sql_regcase("access_*.xml");
$files = glob($d . $pattern_xml);

if($_SESSION['cm__gallery']['folder_access'][$files[0]] == ''){

	$access_xmlfile = new XmlFile(realpath($files[0]));
	$access_root_node = $access_xmlfile->xpath->query("/access")->item(0);
	$pass = getElementValue($access_root_node, 'pass', 0, '');
	if(md5($_POST['pass']) == $pass){
		$_SESSION['cm__gallery']['folder_access'][$d] = 'granted';
		$_SESSION['cm__gallery']['login_error'] = '';
	}else{
		$_SESSION['cm__gallery']['folder_access'][$d] = 'denied';
		$_SESSION['cm__gallery']['login_error'] = 'Incorrect Password. Try again.';
	}
}else{
	echo "ERROR! Access XML not found!";
}

?>