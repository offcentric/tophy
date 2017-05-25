<?php
/********************************************************/
/* file: 		gallery_nav.css.php 					*/
/* module:		CM__GALLERY								*/
/* theme:		surf									*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Stylesheet for the navigation of the 	*/
/*				"cm__pages" module, "surf" theme		*/
/********************************************************/
?>

/* MAIN NAV */

/* HEADER */
h3.nav_header{
	display:block;
	background-color:#ccc;
	height:25px;
	margin-top:0;
}

h3.nav_header a{
	color:#fff;
	font-size:1em;
	font-weight:bold;
	text-decoration:none;
	line-height:20px;
	display:inline-block;
	position:absolute;
	z-index:1;
}

h3.nav_header img {
float: left;
margin-top: 2px;
margin-left: 10px;
}
	
h3.nav_header span{
	float:left;
	margin-left:5px;
	margin-top:3px;
}

ul#nav{
	padding-top:10px; 
	clear:both; 
	position:absolute;
	z-index:10;
	top:115px;
	text-align:left;
	font-family:Verdana, Helvetica, Arial, sans-serif;
	margin-left:10px;
	margin-right:auto;
}

ul#nav li{display:block; margin-right:4px; padding:7px 3px;}
ul#nav li img{margin-bottom:-2px;}
ul#nav li a, ul.nav li a:visited{text-decoration:none; padding:0 7px 2px 7px;margin-bottom:-1px;}

/* LEVEL 0 */
ul#nav li.level_0{
	display:block;
	border-bottom:1px solid #fff;
	background-color:#eee;
	padding:5px;
	font-size:0.9em;
}

ul#nav li.level_0:hover{
	background-color:#999 ! important;
	color:#fff;
}

ul#nav li.level_0:hover a{
	color:#fff;
}

ul#nav li a{
	display:block;
	padding-left:0px;
}	

ul#nav li a:hover{color:#0075b2;}

.item_on{
	color:#fff ! important;
	background-color:#ccc ! important;
}

.item_on a{color:#fff ! important;}


/* LEVEL 1 */
ul#nav li.item_on li.level_1 a{
	background-color:#ccc ! important;
}

ul#nav li.level_0:hover li a{ background-color:#999 ! important; }
ul#nav li.level_0:hover li a:hover{ background-color:#666 ! important;}

ul.subnav{margin-top:5px;}

ul.subnav li{
	font-size:0.9em;
	padding:0 ! important;
}

ul.subnav li.subitem_on{
	background-color:#0075b2 ! important;
	font-weight:bold;
}

ul.subnav li:hover a:hover{background-color:#666 ! important;}
ul.subnav li.subitem_on:hover{ background-color:#0075b2 ! important; }

ul.subnav li a{
	margin: 2px 0 2px 3px;
	padding:2px 0 2px 6px ! important;
	background-color:#eee ! important;
}

ul.subnav li.subitem_on a{background-color:#ccc ! important;}
