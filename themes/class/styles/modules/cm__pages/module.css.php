<?php
/********************************************************/
/* file: 		module.css.php 							*/
/* module:		CM__PAGES								*/
/* theme:		surf									*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Stylesheet for the "cm__pages" module,	*/
/*				"surf" theme							*/
/********************************************************/
?>
/******************************************/
/********** PAGES CORE MODULE ***********/
/******************************************/

/* PAGE */
div.intro{width:80%;margin:10px auto;text-align:center;font-size:0.8em;	line-height:0.8em;color:#bbb;}


/* CONTACT PAGE */
div.contact{margin:25px auto 20px auto ! important; width:500px;padding:0 20px;}
div.contact form{text-align:left; margin:auto;}
div.contact form input.text, div.contact form textarea{margin-bottom:2px; width:266px; background-color:#f3f3f3; border:0; display:block; float:left;line-height:18px;}
div.contact form input.text{height:18px; padding:2px 2px 0 2px; line-height:25px;}
div.contact form textarea{height:100px; padding:0 2px;}
div.contact div.formrow{padding:10px 0 0 0;}
div.contact form label{display:block; float:left; height:16px; width:100px; padding:2px 5px 2px 0; text-align:right;color:#444; font-weight:bold;}
div.contact form input.submit{color:#9F6024; display:block; float:none; margin-left:180px; margin-right:0px; height:30px; width:80px;}
div.contact form input.submit:hover{color:#000;}

div.contact p{font-size:11px;}
div.contact .messages{margin-bottom:20px;}


/* GENERIC CONTENT PAGE */
div.content{margin:40px auto;width:400px;}
div.content h1{display:block;font-size:1.6em;margin:0 0 10px 0;background-color:none;border-bottom:1px solid #9F6024;text-align:left;}
div.content h2{margin:20px 0 10px 0;padding:3px 0;font-size:1.3em;border-bottom:1px solid #550088;border-bottom:1px solid #9F6024;}
div.content p{font-size:0.8em;}


/* DETAIL PAGE */
div.detail {width:800px; margin:25px auto 0 auto;padding:20px 20px 0 20px;}
div.detail div{padding:0 50px;width:800px;margin:auto;padding:0;}
div.detail p{text-align:justify;margin-bottom:20px;font-size:1.1em;line-height:1.5em;letter-spacing:0.01em;}
div.detail img.floatright{margin-left:20px;}
div.detail img.floatleft{margin-right:20px;}


/* ABOUT */
p.cc_icons{text-align:center ! important;}
p.cc_icons img{display:inline-block; margin:0 40px;}

/* ERROR PAGES */
body#error div#main{padding:40px 0 100px;}
body#error h1{ font-size:2em ! important;border-bottom:none;}
body#error h2{ background:transparent; margin-top:10px;}
body#error p{ margin-top:20px; font-size:1em ! important;}
body#error div.content{width:400px;}