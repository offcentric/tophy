<?php
/********************************************************/
/* file: 		module.css.php 							*/
/* module:		CM__JOURNAL								*/
/* theme:		surf									*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		02/07/2011								*/
/* version:		0.1										*/
/* description:	Stylesheet for the "cm__journal" module,*/
/*				"surf" theme							*/
/********************************************************/
?>

/* FORM ELEMENTS & LAYOUT */
input, textarea{
	font:1em Verdana, sans-serif;
	background-color:#ddd;
	color:#000;
	border:1px solid #aaaaaa;
}

input.checkbox{
	background-color:transparent;
	border:0px;
	vertical-align:middle;
	margin-top:3px;
}

textarea{
	padding:5px;
	width:100%;
	height:300px;
	font-size:11px;
}

input.longtext{width:500px;}
input.shorttext{width:90px;}

input.hidden{display:none ! important;}

input.submit, input.button{
 	border:1px solid #555;
	background-color:#ff8b00;
	height:17px;
	line-height:12px;
	padding:0 5px;
	color:#fff;
}

input.submit{
	height:30px;
	font-size:1.2em;
}

#formcontainer input.submit{
	font-size:1.2em;
	font-weight:bold;
	height:30px;
	padding-bottom:2px;
	width:200px;
	display:block;
	margin:10px auto 0 auto;
}

form div.formrow{ margin:5px 0; }
form div.formrow div{ float:left; }
form div.submitrow{ margin-top:20px;}
form label{display:block; float:left; line-height:18px; width:75px; padding:0 	5px 0 0; text-align:right;}

.errordiv{
	color:#dd2222;
	font-weight:bold;
	width:100%;
	float:none ! important;
	clear:both;
}

.redborder{border:2px solid #dd2222;}

.errorheader{
	font:bold 12px "lucida grande", verdana, helvetica, arial, sans-serif;
	color:red;
	width:100%;
	text-align:center;
	margin-top:20px;
}

.highlight{background-color:yellow;}
.color{font-weight:bold;color:#ff5200;}

pre{
	font:1em Lucida Console, serif;
}

em{font-style:italic;}

.popover{
	position:relative;
	padding:10px;
	background-color:#fff;
	border:1px solid #ff8b00;
	margin:auto;
	width:300px;
	z-index:10001;
}

.overlay{
	width:100%;
	position:absolute;
	top:0px;
	left:0px;
	z-index:10000;
	filter:alpha(opacity=50);
	opacity: 0.5;
	-moz-opacity:0.5;
	background-color:#000;
}

div.messages, div.errors{
	font:bold 14px "lucida grande", verdana, helvetica, arial, sans-serif;
	text-align:center;
	line-height:25px;
	color:#fff;
	padding:10px;
}

div.messages{
	background-color:#00bb33;
}

div.bar{
	background:#ff5200;
	height:25px;
	text-align:center;
	padding-top:5px;
	color:#eee;
	font-size:10px;
}

div.bar a{
	font-weight:bold;
	color:#fff ! important;
}

/* SEARCH BAR */
div#search{
	height:25px;
	padding:8px 5px 0 5px;
}

div#search div{
	float:left;
}

div#search div.searchby_text{
	float:right;
}

div#search input{
	display:block;
	float:left;
	margin-right:4px;
	width:115px;
	height:16px;
	font-size:1.1em;
}

div#search input.submit{
	margin-left:4px;
	width:55px;
	height:20px;
	padding-bottom:2px;
	font-weight:bold;
}

div#search span{
	font-weight:bold;
	display:block;
	float:left;
	margin:0 4px;
}

div#search a{
	font-weight: bold;
	display:block;
	float:left;
}

div#resultscontainer{
	text-align:center;
}

div#results{
	line-height:20px;
	margin:0 auto;
	padding:5px 0;
	text-align:left;
}

div#results h2{
	margin-left:0;
	display:block;
	font-weight:bold;
}

div#results h2 span{
	font-style:italic;
}

div#results div{
	margin-top:16px;
}

div#results span.current_results{
	display:block;
	float:right;
	margin-top:15px;
	font-size:1.1em;
}

body.taglist div#maincontainer{
	background:#fff;
}

div#maincontainer div.contentcol{
	width:700px;
	overflow:hidden;
	float:left;
	background:#fff;
	padding-bottom:20px;
}

/* RIGHT COLUMN */
div#maincontainer div.listcol{
	float:right;
	width:200px;
	overflow:hidden;
	background:#eee;
}

div#maincontainer div.listcol ul.entrylist{
	border:1px solid #ddd;
	border-right:none;
	padding:10px 0;
	margin:0 0 20px 10px;
}

div#maincontainer div.listcol ul.entrylist li{
	list-style:none;
	line-height:18px;
	padding-left:5px;
	margin-left:5px;
}

div#maincontainer div.listcol li.selected{
	background-color:#ff8b00;
	font-weight:bold;
	color:#fff;
}

div#maincontainer div.listcol h3{
	font:bold 1.1em "lucida grande", verdana, helvetica, arial, sans-serif;
	margin:10px 0 0 10px;
	padding-left:5px;
	line-height:22px;
	background-color:#ddd;
}

div#maincontainer div.listcol ul.entrylist h4{
	padding:0;
	margin:10px 10px 0 10px;
	border-top:1px solid #ccc;
}

div#maincontainer div.listcol ul.entrylist li a{
	font-weight:normal;
}

div#maincontainer div.listcol ul.taglist{
	border:1px solid #ddd;
	border-right:none;
	padding:10px 0;
	margin:0 0 20px 10px;
}

div#maincontainer div.listcol ul.taglist li{
	list-style:none;
	line-height:18px;
	padding-left:5px;
	margin-left:5px;
}

div#maincontainer div.listcol ul.taglist li.alltags{
	font-weight:bold;
	margin-top:15px;
}

div#maincontainer div.listcol ul.linkage{
	border:1px solid #ddd;
	border-right:none;
	margin:0 0 20px 10px;
	padding:10px;
	line-height:18px;
	list-style:none;
}

/* MAIN CONTENT AREA */
ul.journal li{
	clear:both;
	list-style:none;
}

ul.journal li.altrow{
/*	background-color:#f2f2f2; */
}

ul.journal li div.entry{
	margin:10px 25px;
	padding-bottom:10px;
	border-bottom:1px solid #bbb;
}

ul.journal li div.entry div.title{
	padding:0;
}

ul.journal li div.entry h3{
	line-height:25px;
}

ul.journal li div.entry div.entrymain{
	text-align:left;
	padding:0;
	vertical-align:top;
}

div.entrycontent{
	margin-top:15px;
	font-size:1.1em;
}

ul.journal li div.entry div.entrycontent{
	padding:0;
}

div.title{
	padding-left:25px;
	margin-top:20px;
}
div.subheader{padding-left:25px;}

div#maincontainer div.contentcol .entrydetail{
	padding:0 25px;
}

quote{
	display:block;
	border:1px solid #ddd;
	background:#eee;
	padding:10px;
}

div.imageblock{
	text-align:center;
}

div.imageblock div.inside{
	background:#ddd;
	padding:10px;
	float:left;
}

div.left{
	float:left;
	margin-right:10px;
}

div.right{
	float:right;
	margin-left:10px;
}

div.imageblock img{
}

div.imageblock div.inside div{
	margin-top:5px;
	font-weight:bold;
	font-size:0.9em;
}

div#maincontainer div.contentcol div.entryheaders{
	background-color:#ddd;
	border:1px solid #ccc;
	margin-top:10px;
	padding:5px;
}

div#maincontainer div.contentcol div.entryheaders a{
	text-decoration:none;
	border-bottom:1px #ff5200 dotted;
}

ul.journal li div.entry div.commentcount{
	text-align:right;
	font-size:9px;
	margin-top:10px;
}

/* COMMENTS */
div.comments{
	width:480px;
	margin:20px auto;
	border:1px solid #eeeeee;
}

div.comments .comment{
	padding:10px;
	border-bottom:1px dotted #dddddd;
}

div.comments h4{
	margin:0;
	padding:10px;
	font-size:1.2em;
	color:#fff;
	background-color:#FF8B00;
}

div.comments .postedby{
	font-style:italic;
	display:block;
}

div.comments p{
	margin:0;
	float:left;
}

div.comments #commentform{
	width:300px;
	margin:10px auto;
}

div.comments div.messages{
	font-size:1em;
	line-height:18px;
	height:auto;
	padding:10px 0;
}

div.comments #commentform div.formrow div{
	float:left;
}

div.comments #commentform .errordiv div{ width:252px ! important; margin-bottom:2px;}

div.comments #commentform label{
	width:50px;
	text-align:right;
	padding-right:10px;
	float:left;
}

div.comments #commentform div.formrow input{
	width:210px;
}

div.comments #commentform input.text{	
	width:200px;
}

div.comments #commentform input.submit{
	width:272px;
	height:22px;
	padding-bottom:3px;
	font-size:1.1em;
}

div.comments #commentform textarea{
	width:260px;
	height:100px;
}

/* PAGING */
a.button{
	display:block;
	border:1px solid #555;
	height:18px;
	line-height:18px;
	text-decoration:none;
	color:#fff ! important;
	background:#ff8b00;
	margin:auto;
	text-align:center;
	padding:0 5px;
}


div.pagelinks{
	height:20px;
	width:100%;
	margin-top:10px;
}

div.pagelinks a{
	display:block;
	font-size:1.2em;
	height:25px;
	line-height:24px;
}

div.pagelinks a.previous{
	float:left;
	margin-left:25px;
}

div.pagelinks a.next{
	float:right;
	margin-right:25px;
}

/* TAGLIST */
body.taglist div.sortby{
	margin:10px 25px;
	font-weight:bold;
	font-size:1.1em;
}

body.taglist div.sortby a{
	margin:0 10px;
}

body.taglist div.sortby strong{
	margin:0 10px;
}

body.taglist ul.tags{
	margin:0 25px 20px 25px;
	list-style:none;
}

body.taglist ul.tags li{
	width:210px;
	float:left;
	line-height:20px;
}

/* FORM PAGES */

div.errordiv{
	padding-top:0 ! important;
	display:block;
	float:left;
}

.errordiv div, p.error{
	font-weight:bold;
	padding:0 10px;
	line-height:22px;
	background-color:red;
	color:white;
	font-size:0.9em;
}

p.smallerror, .smallerror div{
	font-size:0.8em;
	line-height:14px;
	background-color:red;
	color:white;
	width:255px ! important;	
}

span.smallerror{
	left:0px;
	line-height:14px;
	background-color:red;
	color:white;
	font-size:0.9em;
	display:block;
}


/* CONTACT PAGE */
body.contact div#maincontainer{background-image:url(null);}
div.contact{width:360px; margin:auto; padding:30px 0 20px 0;font-size:1.3em;}
div.contact form{text-align:left; width:353px; margin:auto;}
div.contact form input.text, div.contact form textarea{margin-bottom:2px; width:266px; display:block; float:left;}
div.contact form textarea{margin-top:0;}
div.contact form input.text{padding:0 2px; line-height:28px;}
div.contact form textarea{height:200px; padding:0 2px;font-size:1em;}
div.contact div.formrow{padding:10px 0 0 0;}
div.contact form input.submit{display:block; float:right; margin-right:0px; width:100px;}
div.contact p{font-size:0.9em;}
div.contact .messages{padding:50px 0 70px 0; background:transparent; color:#22bb22;}
div.contact .errordiv div, div.contact p.error{ width:332px ! important; margin-bottom:2px;}

/* ERROR PAGES */
body.error div#main{height:400px;}
body.error div#main p{font-size:1.2em;margin:10px 20px;}

/* USERLIST */
table.userlist{width:600px; margin:20px auto;}
table.userlist th{font-weight:bold; border-bottom:1px solid #999;}
table.userlist th, table.userlist td{padding:5px;}
table.userlist .numposts{width:70px;text-align:center;}

/* TAGOMETER */
<?php
$startcolour = "fe0001";
$endcolour = "0133b4"; 
$steps = 15;

$startred_dec = hexdec(substr($startcolour,0,2));
$startgreen_dec = hexdec(substr($startcolour,2,2));
$startblue_dec = hexdec(substr($startcolour,4,2));

$endred_dec = hexdec(substr($endcolour,0,2));
$endgreen_dec = hexdec(substr($endcolour,2,2));
$endblue_dec = hexdec(substr($endcolour,4,2));

$increment_red = ($endred_dec - $startred_dec)/$steps;
$increment_green = ($endgreen_dec - $startgreen_dec)/$steps;
$increment_blue = ($endblue_dec - $startblue_dec)/$steps;

for($i=0;$i<=$steps;$i++){
	$currentred = intval($startred_dec + ($increment_red * $i));
	$currentgreen = intval($startgreen_dec + ($increment_green * $i));
	$currentblue = intval($startblue_dec + ($increment_blue * $i));
	
	echo "li.tag" . ($i+1) . " a{ color:#". sprintf("%02X", $currentred) . sprintf("%02X",$currentgreen) . sprintf("%02X",$currentblue) . " ! important; }\n";

}
?>
