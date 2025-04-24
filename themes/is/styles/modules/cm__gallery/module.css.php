<?php
/********************************************************/
/* file: 		module.css.php 							*/
/* module:		CM__GALLERY								*/
/* theme:		international superstar					*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Stylesheet for the Gallery core			*/
/*				module, "is" theme						*/
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
ul.toc{margin:20px auto; width:100%; display:flex; flex-direction:row; justify-content: space-between;flex-wrap: wrap; justify-content: flex-start;
	align-items: flex-start;}
ul.toc li{margin:10px 40px;font:1.1em Verdana, sans-serif;line-height:20px ! important; width: 400px; display:block; text-align:left;}
ul.toc li a span{display:block;}
ul.toc li span.intro{font-size:0.9em;}
ul.toc li a{ text-decoration:none ! important;}
ul.toc li a:hover,ul.toc li a:active{ text-decoration:underline ! important;}

body.display_thumbnails div.pagecontainer div.bookpage{
	text-align:center;
	white-space:normal;
	padding-top:15px;
}

body.display_thumbnails div.item{ position: relative; display: inline-block; margin:10px;}
body.display_thumbnails div.item .exif_info{
	background: rgba(0,0,0,0.5);
	font-size: 10px;
	padding:5px 0;
	position: absolute;
	width: 100%;
	bottom: 0px;
	visibility: hidden;
}

body.display_thumbnails div.item:hover .exif_info{
	visibility: visible ! important;
}

body.display_thumbnails div.item .exif_info .left_column{text-align:right; float:left; width:70px; padding-right: 5px;}
body.display_thumbnails div.item .exif_info .right_column{text-align:left; float:left;}


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


/* PHOTO PAGE */
body#photopage{font:1em helvetica, "lucida grande", verdana, helvetica, arial, sans-serif; background-color:#181818; color:#fff; padding-bottom:10px; text-align:center;}
body#photopage a:link, body#photopage a:visited{color:#fff;}
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


/* PAGE */
div.intro{
	font-size:0.8em;
	line-height:0.8em;
	color:#bbb;
}

/* BOOK / PAGE LOGIN FORM */
form#book_login{
    width:400px;
    margin:20px auto;
}

form#book_login div.formrow{
    padding:10px 0;
    text-align:center;
}

form#book_login div.formrow label{
    display:block;
    margin-bottom:10px;
}

div.login_error{
    width:400px;
    text-align:center;
    margin:20px auto;
    color:#cc0000;
    font-weight:bold;
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
	background-color:#181818;
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
	background-color:#181818;
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
#TB_window a:hover {color: #0075b2;}
#TB_window a:active {color: #0075b2;}
#TB_window a:focus{color: #0075b2;}

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

.nav_buttons{
	width:100px;
	margin:auto;
	position:relative;
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

#TB_prevlink div{background:#181818 url("<?php echo $_SESSION['webpath'] ?>themes/<?php echo strtolower($_SESSION['theme']) ?>/images/thickbox/back.gif") no-repeat;}
#TB_nextlink div{background:#181818 url("<?php echo $_SESSION['webpath'] ?>themes/<?php echo strtolower($_SESSION['theme']) ?>/images/thickbox/next.gif") no-repeat;}
#TB_escapelink{margin:15px auto;text-align:center;}
#TB_escapelink a{text-decoration:none; cursor:pointer;}


/* CUSTOMIZED THICKBOX */
#TB_window a:link {color: #ccc;}
#TB_window a:visited {color: #ccc;}
#TB_window a:hover {color: #3b9600;}
#TB_window a:active {color: #3b9600;}
#TB_window a:focus{color: #3b9600;}