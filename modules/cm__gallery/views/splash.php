<?php
/********************************************************/
/* file: 		splash.php 								*/
/* module:		CM__GALLERY								*/
/* theme:												*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		06/07/2011								*/
/* version:		0.1										*/
/* description:	Splash page template					*/
/********************************************************/

$_SESSION['pagetype'] = "splash";

$extra_js = "if(BrowserDetect.supported()) insertJSFile('" . $_SESSION['webpath'] . "scripts/jquery.innerfade.js?m=" . $_SESSION['cm__gallery']['module_name'] . "');\n";

$count = 0;
foreach(glob($_SESSION['filepath'] . "images/splash/*.jpg") as $file) {
	$filename = $_SESSION['webpath'] . substr($file, strlen($_SESSION['filepath']));
	if($count==0){$defaultsplash = $filename;}
	$extra_js .= "Splash.slides[" . $count . "] = new Image();\n";
	$extra_js .= "Splash.slides[" . $count . "].src = '" . $filename . "';\n";
	$count++;
}

include COREBASEPATH . "/views/partial/start.php";
?>
<div id="splash-default"><img class="default" src="<?php echo $defaultsplash ?>" alt="Splash"/></div>
<div id="splash" class="clearfix content-container"></div>
<?php
include COREBASEPATH . "/views/partial/end.php";
?>