<?php
/********************************************************/
/* file: 		module.css.php 							*/
/* module:		CM__GALLERY								*/
/* theme:		surf									*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Stylesheet for the Gallery				*/
/*				core module, "surf" theme				*/
/********************************************************/
?>
/******************************************/
/********** GALLERY CORE MODULE ***********/
/******************************************/

/******************************************/
/* SPLASH PAGE */
div#splash{height:400px; text-align:center; margin:20px auto;}
div#splash-default{position:absolute; width:<?php echo $_SESSION['container_width']; ?>; height:400px; text-align:center; margin:20px auto;}

/*****************************/
/**** SHOWCASE COMPONENT ****/
body.showcase div.showcase_container{
	margin-top:20px;
	width:920px;
	margin:auto;
	text-align:center;
}

div.showcase_container div{
	display:inline-block;
	margin:10px;
}

body.showcase a.clickarea{
	position:absolute;
	z-index:8;
	width:<?php echo $_SESSION['cm__gallery']['thumb_w'] ?>px;
	height:<?php echo $_SESSION['cm__gallery']['thumb_h'] ?>px;
	text-align:left;
}

body.showcase a.clickarea img{
	position:absolute;
	z-index:99;
}


body.showcase span.showcase_text{
	text-align:center;
	position:absolute;
	z-index:1;
	padding:2px 0px;
	margin-top:166px;
	font-size:0.9em;
	font-weight:bold;
	line-height:15px;
	width:<?php echo $_SESSION['cm__gallery']['thumb_w'] ?>px;
	background:#444;	
/*	background:#fff url("<?php echo $_SESSION['webpath'] ?>images/bg.png"); */
	color:#fff;
}


body.showcase div#showcase_text_container{
	width:<?php echo $_SESSION['cm__gallery']['thumb_w'] ?>px;
	height:<?php echo $_SESSION['cm__gallery']['thumb_h'] ?>px;
	position:absolute;
	margin-left:378px;
	margin-top:229px;
}

body.showcase div.showcase div a{
	text-decoration:none ! important;
}
/**** END SHOWCASE COMPONENT ****/
/********************************/

/* BOOK PAGE */
ul.toc{margin:20px auto; width:400px;}
ul.toc li{margin:5px 0;font:1.1em Verdana, sans-serif;line-height:20px ! important;}
ul.toc li span.intro{font-size:0.9em;}
ul.toc li a{ text-decoration:none ! important;}
ul.toc li a:hover,ul.toc li a:active{ text-decoration:underline ! important;}


/* PHOTO PAGE */
body#photopage{font:1em helvetica, "lucida grande", verdana, helvetica, arial, sans-serif; background-color:#fff; color:#333; padding-bottom:10px; text-align:center;}
body#photopage a:link, body#photopage a:visited{color:#000;}
body#photopage a:hover{color:#44ff11;}

body#photopage h1{display:none;}
body#photopage div#image_container{display:inline;}
body#photopage div#image_container img{margin-top:10px;}

body#photopage div#exif_panel{width:340px; margin:auto; margin-top:5px; overflow:hidden; font-size:0.95em; font-size:0.7em; padding:5px;}

body#photopage div.exif_info_column{float:left;height:15px;margin:2px 0;width:332px;white-space:nowrap;overflow:hidden;}
body#photopage div.exif_info_column div.left_column{height:15px; width:80px; line-height:15px; float:left; text-align:right; padding:0 5px; overflow:hidden;background-color:#222;}
body#photopage div.exif_info_column div.right_column{width:220px; line-height:15px; float:left; padding:0 5px; overflow:hidden; text-align:left;background-color:#444;}
body#photopage div#title_bar{font-weight:bold; padding:0 5px; margin:5px 0;}

body#photopage div#title_bar{
	height:20px;
	line-height:20px;
	background-color:#222;
}

/* THUMBNAIL DISPLAY MODE */
/********************************/
body.display_thumbnails div.pagecontainer{
	white-space:nowrap;	
	width:100%;
}

body.display_thumbnails div.pagecontainer div.bookpage{
	text-align:center;
	white-space:normal;
}

body.display_thumbnails div.pagecontainer div.bookpage li{
	width: <?php echo $_SESSION['cm__gallery']['thumb_w'] + 10 ?>px ! important;
	display:inline-block;
	margin:20px;
}

body.display_thumbnails div.pagecontainer div.bookpage li a{
	text-decoration:none ! important;
}

body.display_thumbnails div.pagecontainer div.bookpage img{
	margin: 10px;
}

body.display_thumbnails div.pagecontainer div.back{
	float:left;
	margin-left:40px;
}

body.display_thumbnails div.pagecontainer div.next{
	float:right;
	margin-right:40px;
}

/* THICKBOX */

/* ----------------------------------------------------------------------------------------------------------------*/
/* ---------->>> global settings needed for thickbox <<<-----------------------------------------------------------*/
/* ----------------------------------------------------------------------------------------------------------------*/
*{padding: 0; margin: 0;}

/* ----------------------------------------------------------------------------------------------------------------*/
/* ---------->>> thickbox specific link and font settings <<<------------------------------------------------------*/
/* ----------------------------------------------------------------------------------------------------------------*/
#TB_window {
	font: 12px Arial, Helvetica, sans-serif;
	color: #ccc;
}


#TB_secondLine {
	font: 10px Arial, Helvetica, sans-serif;
	color:#ccc;
	padding:0 0 0 15px;
	display:inline;
	white-space:nowrap;
	line-height:14px;
}

/* ----------------------------------------------------------------------------------------------------------------*/
/* ---------->>> thickbox settings <<<-----------------------------------------------------------------------------*/
/* ----------------------------------------------------------------------------------------------------------------*/
#TB_overlay {
	position: fixed;
	z-index:100;
	top: 0px;
	left:50%;
	width:1000px ! important;
	height:100%;
	margin-left:-500px;
	margin-top:0px;
	filter:alpha(opacity=100);
	-moz-opacity: 1;
	opacity: 1;
}

#TB_overlay_inner{
	background:#fff url("<?php echo $_SESSION['webpath'] ?>images/bg.png");
	width:1000px;
	height:100%;
	margin:-13px auto 0 auto;
}

.TB_overlayMacFFBGHack {
	background-color:#181818;
	filter:alpha(opacity=70);
	-moz-opacity: 0.7;
	opacity: 0.7;
}

.TB_overlayBG {
	background-color:#fff;
	filter:alpha(opacity=70);
	-moz-opacity: 0.7;
	opacity: 0.7;
}

#TB_window {
	position: fixed;
	z-index: 102;
	color:#ddd;
	display:none;
	text-align:left;
	top:50%;
	left:50%;
	margin-top:40px;
}

#TB_window a:link {color: #ccc; text-decoration:none ! important;}
#TB_window a:visited {color: #ccc;}
#TB_window a:hover {color: #9F6024;}
#TB_window a:active {color: #9F6024;}
#TB_window a:focus{color: #9F6024;}

#TB_window img#TB_Image {
	display:block;
	margin:0;
/*	border: 1px solid #286500;*/
}


#TB_caption{
	display:none;
}

#TB_closeWindow{
	display:none;
}

#TB_title{
	background-color:#e8e8e8;
	height:27px;
}

#TB_load{
	position: fixed;
	display:none;
	height:64px;
	width:64px;
	z-index:103;
	top: 50%;
	left: 50%;
	margin: -32px 0 0 -32px; /* -height/2 0 0 -width/2 */
}

#TB_HideSelect{
	z-index:99;
	position:fixed;
	top: 0;
	left: 0;
	background-color:#fff;
	border:none;
	filter:alpha(opacity=0);
	-moz-opacity: 0;
	opacity: 0;
	height:100%;
	width:100%;
}

* html #TB_HideSelect { /* ie6 hack */
     position: absolute;
     height: expression(document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + 'px');
}

#TB_iframeContent{
	clear:both;
	border:none;
	margin-bottom:-1px;
	margin-top:1px;
	_margin-bottom:1px;
}

#TB_prevlink{
	top:0px;
	position:absolute;
	width:56px;
	height:30px;
	filter:alpha(opacity=50);
	-moz-opacity: 0.5;
	opacity: 0.5;
}

#TB_nextlink{
	top:0px;
	right:0px;
	position:absolute;
	height:30px;
	width:56px;
	filter:alpha(opacity=50);
	-moz-opacity: 0.5;
	opacity: 0.5;
	text-align:right;
}

#TB_prevlink div, #TB_nextlink div{
		height:100%;
		width:56px;	
}

#TB_prevlink div{background:#222 url("<?php echo $_SESSION['webpath'] ?>themes/<?php echo strtolower($_SESSION['theme']) ?>/images/thickbox/back.gif") no-repeat;}
#TB_nextlink div{background:#222 url("<?php echo $_SESSION['webpath'] ?>themes/<?php echo strtolower($_SESSION['theme']) ?>/images/thickbox/next.gif") no-repeat;}
#TB_escapelink{margin:15px auto;text-align:center;}
#TB_escapelink a{text-decoration:none; cursor:pointer;}

/* EXIF INFO PANEL */
body.display_thumbnails span#exif_panel_clickzone{width:55px;}
body.display_thumbnails span.exif_info{display:none;}

body.display_thumbnails div#exif_panel_container{
	width:240px;
	position:absolute;
	left:10px;
	margin:-108px auto 0 auto;
	filter:alpha(opacity=85); 
	-moz-opacity: 0.85; 
	opacity: 0.85;
}

body.display_thumbnails div#exif_panel{background-color:#000;overflow:hidden;padding-bottom:2px;}
body.display_thumbnails div#exif_panel_content{font-size:0.95em;margin:4px;}
body.display_thumbnails #exif_panel_clickzone img{height:16px;margin-left:5px;}
body.display_thumbnails #exif_panel_clickzone img#exif_button{height:16px;margin-left:5px;cursor:pointer;}

body.display_thumbnails div.exif_info_column{float:left;background-color:#444;height:15px;margin:2px 0;width:232px;white-space:nowrap;overflow:hidden;}
body.display_thumbnails div.exif_info_column div.left_column{height:15px;width:80px;line-height:15px;float:left; background-color:#222;text-align:right; padding:0 5px;overflow:hidden;}
body.display_thumbnails div.exif_info_column div.right_column{width:120px;line-height:15px;float:left;padding:0 5px;overflow:hidden;}

body.display_thumbnails div.nav_buttons{width:950px;margin:40px auto;height:40px;}

body.display_thumbnails .gallery_navlink{
	filter:alpha(opacity=100);
	-moz-opacity: 1;
	opacity: 1;
	float:left;
	width:475px ! important;
	position:relative ! important;
	z-index:10;
}

body.display_thumbnails .gallery_navlink div{background:none ! important;}

body.display_thumbnails .gallery_navlink span{
	font-size:3em ! important;
	vertical-align:bottom;
	text-align:center;
	cursor:pointer;
	height:20px;
	color:#aaa;
}

/* STRIP DISPLAY MODE */
/********************************/
body.display_strip .bookpage{
	width:100%;
	overflow-x:auto;
	overflow-y:hidden;
	white-space: nowrap;
	text-align:left;
	height:<?php echo $_SESSION['cm__gallery']['strip_h'] + 35 ?>px;
	float:left;
}
body.display_strip .bookpage .inner{
	margin:0 0 0 -30px;
}

body.display_strip .bookpage img{
	margin: 0 0 0 30px;
}

body.display_strip div.pagecontainer div.next{
	display:inline;
	margin:0 10px;
}

body.display_strip div.pagecontainer div.back{
	display:inline;
	margin:0 -10px 0 0;
}

body.display_strip div.pagecontainer div.next img{
	height:282px;
	margin:55px 0;
}

body.display_strip div.pagecontainer div.back img{
	height:282px;
	margin:55px 0 55px 40px;
}

body.display_strip div.pagecontainer div.back img{margin-right:-10px;}


/* FULL DISPLAY MODE */
/********************************/
body.display_full div.bookpage{
	width:100%;
	text-align:center;
}

body.display_full div.bookpage .inner{
	width:<?php echo $_SESSION['cm__gallery']['full_w'] ?>px;
	margin:auto;
	text-align:center;
}

body.display_full div.bookpage img{
	display:block-inline;
	padding:20px ! important;
}

body.display_full div.pagecontainer div.next{
	display:inline;
	margin:0 10px;
}

body.display_full div.pagecontainer div.back{
	display:inline;
	margin:0 -10px 0 0;
}

body.display_full div.pagecontainer div.next img{
	height:282px;
	margin:55px 0;
}

body.display_full div.pagecontainer div.back img{
	height:282px;
	margin:55px 0 55px 40px;
}

body.display_full div.pagecontainer div.back img{margin-right:-10px;}

