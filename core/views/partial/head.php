<?php
global $extra_js;
?>
<head>
	<title><?php echo $_SESSION['website_name'] . " " . $_SESSION['website_subtitle'] ?></title>
	<meta http-equiv="Content-Language" content="en-us" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="Author" content="<?php echo $_SESSION['meta_author'] ?>" />
	<meta name="KeyWords" content="<?php echo $_SESSION['meta_keywords'] ?>" />
	<meta name="Description" content="<?php echo $_SESSION['meta_description'] ?>" />
	<meta name="company" content="<?php echo $_SESSION['meta_company_name'] ?>" />
	<meta name="Identifier-URL" content="http://<?php echo $_SERVER['HTTP_HOST'] ?>" />
	<meta name="Classification" content="<?php echo $_SESSION['meta_classification'] ?>" />
	<link rel="icon" href="<?php echo $_SESSION['webpath'] ?>favicon.ico" type="image/ico" />
	<link type="text/css" rel="stylesheet" href="<?php echo $_SESSION['webpath'] ?>styles/reset.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo $_SESSION['webpath'] ?>styles/global.css?t=<?php echo $_SESSION['theme'] ?>" />
<?php foreach($_SESSION['page_modules'] as $pagemodule){ ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $_SESSION['webpath'] ?>styles/module.css?t=<?php echo $_SESSION['theme'] ?>&m=<?php echo $pagemodule ?>" />
<?php } ?>
<?php if(in_array($_SESSION['cm__gallery']['module_name'], $_SESSION['modules_enabled'])){ ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $_SESSION['webpath'] ?>styles/gallery_nav.css?t=<?php echo $_SESSION['theme'] . "&m=" . $_SESSION['cm__gallery']['module_name'] ?>" />
<?php } ?>
	<script type="text/javascript">
		var menuItem = '<?php echo str_replace("'", "\'", @$_SESSION['cm__gallery']['book']) ?>';
		var galleryDisplay = '<?php echo @$_SESSION['cm__gallery']['display'] ?>';
	</script>
	<script type="text/javascript" src="<?php echo $_SESSION['webpath'] ?>scripts/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $_SESSION['webpath'] ?>scripts/json.js"></script>
	<script type="text/javascript" src="<?php echo $_SESSION['webpath'] ?>scripts/global.js?t=<?php echo $_SESSION['theme'] ?>"></script>
<?php foreach($_SESSION['page_modules'] as $pagemodule){ ?>
	<script type="text/javascript" src="<?php echo $_SESSION['webpath'] ?>scripts/module.js?t=<?php echo $_SESSION['theme'] ?>&m=<?php echo $pagemodule ?>&pagetype=<?php echo $_SESSION['pagetype'];?>&display=<?php echo  @$_SESSION[$pagemodule]['display'];?>"></script>
<?php } ?>
	<script type="text/javascript" src="<?php echo $_SESSION['webpath'] ?>scripts/unobtrude.js"></script>
<?php if($extra_js != ""){ ?>
	<script type="text/javascript">
	<?php echo $extra_js; ?>
	</script>
<?php } ?>
<?php
if(@$_GET['action'] == "edit_nav_images" && @$_GET['success'] == "true"){ ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".navrow", window.parent.document).each(function(){
		Navigation.check_edit_nav_image(this, $("select", this)[0]);
	});
})
</script>
<?php } ?>

</head>