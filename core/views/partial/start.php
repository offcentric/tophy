<?php
if(@$title==""){
	$title = $_SESSION['website_name'];
}
if(@$_SESSION['pagetype']==""){
	$_SESSION['pagetype'] = "main";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php include "head.php"; ?>
<body<?php if($_SESSION['pagename'] != ""){ echo " id=\"" . $_SESSION['pagename'] . "\"";} ?> class="<?php echo $_SESSION['pagetype'];?><?php if(@$_SESSION['cm__gallery']['display'] != "") echo " display_" . $_SESSION['cm__gallery']['display'];?>">
	<div id="outercontainer">
		<div class="container" id="container">
		<?php
		if($_SESSION['cm__gallery']['enable_control_panel']){
			include "control_panel.php";
		}?>
			<?php include "quick_links.php"; ?>

<?php if(file_exists($_SESSION['filepath']."images/".@$_SESSION['main_logo'])){?>
			<div class="header clearfix">
				<a href="<?php echo $_SESSION['webpath'] ?>" title="<?php echo $_SESSION['website_name'] ?>">
				<h1><?php echo $_SESSION['website_name'] ?></h1>
<?php if($_SESSION['website_subtitle'] != ""){ ?>
				<h2><?php echo $_SESSION['website_subtitle'] ?></h2>
<?php } ?>				
				<img id="mainlogo" src="<?php echo $_SESSION['webpath'] ?>images/themes/<?php echo $_SESSION['theme'] ?>/global/logo.<?php echo $_SESSION['logo_format'] ?>" alt="<?php echo $_SESSION['website_name'] ?>" />
				</a>
			</div>
<?php }
	if(in_array($_SESSION['cm__gallery']['module_name'], $_SESSION['modules_enabled'])){
?>
		<?php include MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/views/gallery_nav.php"; ?>
<?php } ?>
			<div class="navbar"></div>
