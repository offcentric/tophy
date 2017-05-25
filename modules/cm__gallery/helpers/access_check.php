<?php
function check_access($d){
	$pattern_xml = "access_*\.xml";
	if(@$_SESSION['cm__gallery']['folder_access_type'][$d] != 'public' && @$_SESSION['cm__gallery']['folder_access'][$d] != 'granted'){

		// first look for XML access file to see if this book requires authentication
	
		if(sizeof(glob($d . $pattern_xml)) == 1){
			$files = glob($d . $pattern_xml);
	
			if($_SESSION['cm__gallery']['folder_access'][$files[0]] == ''){
				$access_xmlfile = new XmlFile(realpath($files[0]));
				$access_root_node = $access_xmlfile->xpath->query("/access")->item(0);
	//			if($access_root_node->length > 0){
					$pass = getElementValue($access_root_node, 'pass', 0, '');

					if($pass != ''){
						$_SESSION['cm__gallery']['folder_access_type'][$d] = 'private';
					}else{
						$_SESSION['cm__gallery']['folder_access_type'][$d] = 'public';				
					}

	//			}else{
					// ERROR: malformed access XML file
	//			}
	
			}else{
				// user already logged into this folder this session
			}
		}else{
			$_SESSION['cm__gallery']['folder_access_type'][$d] = 'public';
		}
	}
}
?>