<?php
/********************************************************/
/* file: 		global.css.php 							*/
/* module:		COMMON CODEBASE							*/
/* theme:		class									*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Main stylesheet for the "class" theme	*/
/********************************************************/
?>
/******************************************/
/************** CLASS THEME ***************/
/******************************************/

/************ GLOBAL ELEMENTS *************/
body{
	font-family:"lucida grande", verdana, helvetica, arial, sans-serif;
	background:#f8f8f8 url(/images/global/bg_wood.jpg?t=class);
	color:#444444;
	font-size:12px;
}


body.iframe{background-color:#efeeef;}
iframe{background-color:#efeeef;}

h1, h2, h3{font-family: "lucida grande", verdana, helvetica, arial, sans-serif; color:#222;font-weight:normal;}
h1{font-size:2.3em;}
h2{font-size:2em;}
h3{font-size:1.3em;font-weight:bold;}

strong{font-weight:bold;}
hr{background-color:#9F6024;color:#9F6024;height:1px;border:none;}
a,a:link, a:visited{color:#666666;}
a:hover{color:#9F6024;text-decoration:none;}
a.button:hover, a.button:active{color:#222;}

/* FORM ELEMENTS */
input.submit, input.reset{font-weight:bold;background-repeat:no-repeat;text-align:left; line-height:20px; padding:0 0 0 5px;}
input.hidden{display:none;}
input, textarea{font:0.9em black "lucida grande", Verdana, sans-serif;}
input.text, textarea{border:1px solid #ccc;}
.submit, input.reset{background:none ! important; color:#333; border:none; font-size:1.1em;}
.submit2{background-color:#175400 ! important;}
/******************************************/

/************ REUSABLE STYLES *************/
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

div.thickbar{background-color:#175400;}

div.colorbar{background-color:#286500;color:#666;}
div.colorbar a{color:#666;}
div.colorbar a:hover{text-decoration:underline;}
/******************************************/

/*********** LAYOUT DECLARAIONS ***********/
/* GLOBAL */
div#outercontainer{	width:<?php echo $_SESSION['container_width']; ?>; margin:auto;}
div#container{width:<?php echo $_SESSION['container_width']; ?>; background:#fff url("<?php echo $_SESSION['webpath'] ?>images/global/bg.png?t=<?php echo strtolower($_SESSION['theme']) ?>");margin:auto;}

div.header{padding:10px 10px 0 10px; text-align:<?php echo $_SESSION['logo_align']; ?>;height:90px;}
div.header a{text-decoration:none;display:inline-block;text-align:center;margin:10px 0 0 25px;}
div.header h1{display:none;}
div.header h2{display:none;}

div.navbar{display:none;}

div#main{margin:auto;}


/* QUICK LINKS */
div#quick_links{float:right;margin-top:35px;background-color:#f8f8f8; height:30px;border:1px solid #eee;}
div#quick_links ul{margin:8px 20px;}
div#quick_links ul li{float:left;}
div#quick_links ul li span{letter-spacing:0.2em;margin:0 20px; line-height:15px;}
div#quick_links ul li img{margin:0 5px;}
div#quick_links ul li a{text-decoration:none;}
div#quick_links li.on{font-weight:bold;}

/* FOOTER */
div#footer{
	line-height:34px;
	background-color:#eee;
	color:#666;
	overflow:hidden;
	margin-top:10px; 
	padding:10px 0;
	text-align:center;
	width:100%;
}

div#footer span{white-space:nowrap;}div#footer a{font-weight:bold;}
div#footer a:hover{text-decoration:underline;}


/* CONTROL PANEL */
div#control_panel div a{font-weight:bold;text-decoration:none;margin:0 5px;}
div#control_panel_container{height:120px;top:10px;left:0;width:152px;position:absolute;z-index:0;}
div#control_panel{
	font:normal 11px helvetica, "lucida grande", verdana, helvetica, arial, sans-serif ! important;
	padding:5px 0; 
	border:1px solid #666;
	border-left:0;
	overflow:hidden;
	width:130px;
	height:auto;
	letter-spacing:auto ! important;
	background-color:#eee;
	color:#000;
	filter:alpha(opacity=70);
	-moz-opacity: 0.7;
	opacity: 0.7;	
}

div#control_panel div.line{border-bottom:2px solid #bbb;font-weight:bold;line-height:15px;padding:0 5px;}
div#control_panel div.links{display:block;margin:0 0 7px; 0;line-height:18px;}
div#control_panel div.links div{margin:0 ! important;padding:0 5px;}
div#control_panel div.links div a{display:block;}
div#control_panel div.links div a:hover{color:#fff;}
div#control_panel div.links div:hover{background-color:#aaa;}
div#control_panel div div.item_on{display:block;font-weight:bold;background-color:#666 ! important;padding:0 10px;}

div#control_panel_clickzone{width:20px;float:left;cursor:pointer;margin-top:-1px;}

div#control_panel span.line{margin:0 3px 3px 3px; border-bottom:2px solid #9F6024;}
div#control_panel span.links{display:block;height:auto;margin:3px;}
