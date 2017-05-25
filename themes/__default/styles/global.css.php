<?php
/********************************************************/
/* file: 		global.css.php 							*/
/* module:		COMMON CODEBASE							*/
/* theme:		__default								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Main stylesheet for the default theme	*/
/*******************************************************/
?>

/******************************************/
/************ GLOBAL ELEMENTS *************/
/******************************************/

body{}

input.submit, input.reset{font-weight:bold;background-repeat:no-repeat;text-align:left; line-height:20px; padding:0 0 0 5px;}
input.hidden{display:none;}
/******************************************/
/************ REUSABLE STYLES *************/
/******************************************/

.clearfix:after {
    content: "."; 
    display: block; 
    height: 0; 
    clear: both; 
    visibility: hidden;
}

/* Hides from IE-mac \*/
* html .clearfix {height: 1%;}
/* End hide from IE-mac */

.red{
	color:red;
	font-size:0.8em;
	display:block;
	padding-bottom:5px;
}

.messages{font-weight:bold;font-size:1.2em;}

.nodisplay{display:none ! important;}
.transparent{filter:alpha(opacity=0);-moz-opacity: 0;opacity: 0;}
.nowrap{white-space:nowrap;}
.floatleft{float:left;}
.floatright{float:right;}
.centered{margin:auto;}

/******************************************/
/*********** LAYOUT DECLARAIONS ***********/
/******************************************/

/* GLOBAL */
div#container{width:<?php echo $_SESSION['container_width']; ?>;}
div.container{margin:auto;}
div.header{padding:10px 10px 0 10px; text-align:<?php echo $_SESSION['logo_align']; ?>;}
div.navbar{margin-top:2px; overflow:hidden;}
div#main{margin:auto;}

/* MAIN NAV */
ul#nav{padding-top:10px; clear:both; margin-left:auto; margin-right:auto; text-align:center;}
ul#nav li{display:inline; margin-right:4px; padding:7px 3px;}
ul#nav li img{margin-bottom:-2px;}
ul#nav li a, ul.nav li a:visited{text-decoration:none; padding:0 7px 2px 7px;margin-bottom:-1px;}

/* QUICK LINKS */
div#quick_links{float:right;margin-top:35px;background-color:#f8f8f8; height:30px;border:1px solid #eee;}
div#quick_links ul{margin:8px 20px;}
div#quick_links ul li{float:left;}
div#quick_links ul li span{letter-spacing:0.2em;margin:0 20px; line-height:15px;}
div#quick_links ul li img{margin:0 5px;}
div#quick_links ul li a{text-decoration:none;}
div#quick_links li.on{font-weight:bold;}

/* FOOTER */
div#footer{margin-top:10px; text-align:center;width:100%; font-size:0.8em;}
div#footer span{white-space:nowrap;}div#footer a{font-weight:bold;}
div#footer a:hover{text-decoration:underline;}

/* SPLASH PAGE */
div#splash{height:400px; text-align:center; margin:20px auto;}
div#splash-default{position:absolute; width:<?php echo $_SESSION['container_width']; ?>; height:400px; text-align:center; margin:20px auto;}

/* PAGE */
div.intro{width:80%;margin:10px auto;text-align:center;}

/* CONTACT PAGE */
div.contact{width:auto; margin:0 auto 20px 0; padding:30px 0 20px 0;}
div.contact form{text-align:left; margin:auto;}
div.contact form input.text, div.contact form textarea{margin-bottom:2px; width:266px; background-color:#f3f3f3; border:0; display:block; float:left;}
div.contact form input.text{height:23px; padding:2px 2px 0 2px; line-height:25px;}
div.contact form textarea{height:200px; padding:0 2px;}
div.contact div.formrow{padding:10px 0 0 0;}
div.contact form label{display:block; float:left; height:20px; width:75px; padding:5px 5px 0 0; text-align:right;}
div.contact form input.submit{display:block; float:right; margin-right:0px; height:30px; width:100px;}
div.contact p{font-size:11px;}

/* TABLE OF CONTENTS PAGE */
ul.toc{margin:20px;}
ul.toc li{margin:5px;font-size:0.9em;}
ul.toc li span.intro{font-size:0.9em;}

/* GENERIC CONTENT PAGE */
div.content{margin:40px auto;width:400px;}
div.content h1{display:block;}
div.content h2{padding:3px 0;margin-bottom:3px;}

/* DETAIL PAGE */
div.detail {width:800px; margin:0px auto 0 auto;}
div.detail div{padding:0 50px;}
div.detail h2{font-weight:bold;margin:30px 0 10px 0;padding-bottom:3px;}
div.detail p{text-align:left;margin-bottom:20px;}
div.detail p{text-align:justify;}
div.detail img.floatright{margin-left:20px;}
div.detail img.floatleft{margin-right:20px;}

/* ABOUT */
p.cc_icons{text-align:center ! important;}
p.cc_icons img{display:inline-block; margin:0 40px;}


/* PHOTO PAGE */
body#photopage{padding-bottom:10px; text-align:center;}
body#photopage h1{display:none;}
body#photopage div#image_container{display:inline;}
body#photopage div#image_container img{margin-top:10px;}

body#photopage div#exif_panel{width:340px; margin:auto; margin-top:5px; overflow:hidden; font-size:0.95em; font-size:0.7em; padding:5px;}

body#photopage div.exif_info_column{float:left;height:15px;margin:2px 0;width:332px;white-space:nowrap;overflow:hidden;}
body#photopage div.exif_info_column div.left_column{height:15px; width:80px; line-height:15px; float:left; text-align:right; padding:0 5px; overflow:hidden;}
body#photopage div.exif_info_column div.right_column{width:220px; line-height:15px; float:left; padding:0 5px; overflow:hidden; text-align:left;}
body#photopage div#title_bar{font-weight:bold; padding:0 5px; margin:5px 0;}

/* CONTROL PANEL */
div#control_panel div a{font-weight:bold;text-decoration:none;margin:0 5px;}
div#control_panel_container{height:120px;top:10px;left:0;width:152px;position:absolute;z-index:0;}
div#control_panel{padding:5px 0; border:1px solid #666;border-left:0;overflow:hidden;height:120px;width:130px;font:normal 11px helvetica, "lucida grande", verdana, helvetica, arial, sans-serif ! important;letter-spacing:auto ! important;background:#333;}
div#control_panel div.line{border-bottom:2px solid #bbb;font-weight:bold;line-height:15px;padding:0 5px;}
div#control_panel div.links{display:block;margin:0 0 7px; 0;line-height:18px;}
div#control_panel div.links div{margin:0 ! important;padding:0 5px;}
div#control_panel div.links div a{display:block;}
div#control_panel div.links div a:hover{color:#fff;}
div#control_panel div.links div:hover{background-color:#aaa;}
div#control_panel div div.item_on{display:block;font-weight:bold;background-color:#666 ! important;padding:0 10px;}

div#control_panel_clickzone{width:20px;float:left;cursor:pointer;margin-top:-1px;}

/* ERROR PAGES */
body#error h1{ font-size:2em ! important;}
body#error h2{ background:transparent; margin-top:10px;}
body#error p{ margin-top:20px; font-size:1em ! important;}