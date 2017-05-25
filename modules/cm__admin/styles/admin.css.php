/**************************/
/***** GLOBAL ELEMENTS ****/
/**************************/
body{
	font-size:15px ! important;
	margin:0;
	padding:0;
}

a{
	font-weight:bold;
	text-decoration:none;
}

h1{
	display:block;
	float:left;
	font-size:1.5em;
	line-height:50px;
	padding-left:10px;
	width:600;
	overflow:hidden;
}

h2{
	padding:0;
	margin:0;
	cursor:pointer;
	overflow:hidden;
	height:30px;
	line-height:30px;
	padding-left:10px;
	border-bottom:2px solid #fff;
	width:890px;
	margin-top:20px;
}

h3{float:left;}
p{padding:5px;}

input{
	display:block;
	float:left;
	padding:2px 2px 0 2px;
	line-height:20px;
	height:20px;
}

input, textarea, select{
	font-family:Arial, sans-serif ! important;
	margin:5px;
}

input.hidden{display:none ! important;}

input.text_meta{width:300px;}
input.text_number{height:19px;width:29px;margin-top:6px;}
input.text_hex{width:50px;margin-top:10px;}

textarea{
	margin:5px;
	width:500px;
	height:88px;
	padding:2px 1px;
}

input.checkbox{border:none; display:block;float:left;margin-top:10px;}
input.file{background-color:#fff;height:22px;padding:0;margin:5px 0 0 0;font-size:0.8em ! important;}
input.submit, input.reset{
	display:block;
	float:right;
	margin:7px 8px 7px 0;
	height:35px;
	line-height:35px;
	font-size:1.1em ! important;
	font-weight:bold ! important;
	cursor:pointer;
}

input.submit:disabled{
	filter:alpha(opacity=50);
	-moz-opacity: 0.5;
	opacity: 0.5;
}

select{
	font-size:0.9em;
	margin: 6px 10px 4px 0;
}

label{
	display:block;
	float:left;
	width:190px;
	line-height:35px;
	margin:0 10px 0 5px;
	text-align:right;
}

/**************************/
/**** RESUSABLE STYLES ****/
/**************************/
div.helpicon{
	float:left;
	margin-top:5px;
	cursor:pointer;
}

.errordiv div, p.error{
	width:850px ! important;
}

p.note{
	background:#B0AE41;
	color:#000;
	font-style:italic;
}

div.popover{
	color:#eee;
	background-color:#333;
	border:1px solid #eee;
	padding:10px;
	font-size:0.9em;
}

div.tabcontent{
	margin: 10px 10px 0 10px;
}

div.formrow{
	width:880px;
	border-bottom:1px solid #444;
	float:left;
	margin-bottom:-1px;
}

div.formrow .halfrow{
	float:left;
	width:50%;
}

label.smalllabel{
	width:30px;
	line-height:32px ! important;
	margin:0 ! important;
}

.smalllabel{
	display:block;
	float:left;
	padding:0;
	line-height:35px;
	text-align:right;
}

a.button{
	display:block;
	width:220px;
	height:20px;
	margin:10px 0;
	padding:5px;
	line-height:20px;
	border:1px solid;
	background-repeat:no-repeat;
	cursor:pointer ! important;
}

a.button span{
	display:block;
	float:left;
}

a.button img{
	display:block;
	float:right;
}

a.button:active span{
	margin-top:1px;
}

span.disabled{
	display:block;
	background-color:#666;
	position:absolute;
	filter:alpha(opacity=50);
	-moz-opacity: 0.5;
	opacity: 0.5;
}

div.subsection_header{
	font-weight:bold;
	line-height:30px;
	padding:0 0 0 8px;
	width:872px;
	height:30px;
	margin-top:10px;
}

/**************************/
/***** GENERAL LAYOUT *****/
/**************************/
form#config_form{
	width:900px;
	margin:0 auto;
	position:relative;
/*	font-size:0.8em ! important; */
	font-family: Arial, Helvetica, sans-serif ! important;
}

form.sub_form{
/*	font-size:0.8em ! important;	*/
}

#header{
	width:900px;
	z-index:3;
	height:50px;
	overflow:hidden;
}

#bottom_bar{
	border-top:2px solid #ddd;
	height:50px;
	width:900px;
	margin-top:5px;
}

#header input.submit, #bottom_bar input.submit{
	width:150px;
	background:url("<?php echo $_SESSION['webpath'] ?>images/admin/save.png") no-repeat 120px 8px;
	height:35px;
}

div#container{
	width:900px;
	margin:0 auto;
}

ul#nav{
	z-index:3;
	font-size:1.2em ! important;
	font-family:Arial, Heveltica, sans-serif;
	width:890px;
	padding-left:10px;
}

h2#header_general{
	margin-top:0px;
}

/* EDIT SPLASH IMAGE IFRAME */
/* EDIT NAV IMAGE IFRAME */
div.edit_nav_image_row, div#edit_splashpages_container{
	position:relative;
	float:none ! important;
	margin:40px 0 10px 0;
	font-weight:bold;
	border:0;
}

div.edit_nav_image_row iframe, div#edit_splashpages_container iframe{
	position:absolute;
	z-index:1;
	top:0;
	border:0;
	margin:0;
	overflow-x:hidden;
}

/**************************/
/* GALLERY CUSTOMIZATION **/
/**************************/
div#main_logo_row{
	margin-top:5px;
}

div.formrow span.logo{
	display:block;
	float:left;
	width:520px;
	padding-bottom:2px;
}

div.formrow span.logo img.logo_image{
	width:250px;
	display:block;
	float:left;
	border:1px solid #333;
	padding:15px;
	margin-bottom:5px;
}

div.formrow span.logo a{
	display:block;
	margin:5px 0 0 10px;
	float:left;
}

div.formrow span.logo input.file{ clear:left;}

input.clear_button{display:block ! important;float:left ! important;margin-top:5px;height:22px;padding-top:0;line-height:17px; font-size:0.8em;}

div.formrow span.logo span{
	display:block;
	width:600px;
	padding-bottom:5px;
}

a#remove_main_logo {width:170px;}
a#remove_main_logo span{width:auto;}

div#custom_styles{width:100%;clear:both;}
div#custom_styles div.row{width:100%;}
div#custom_styles div.custom_style{width:293px;float:left;}
div#custom_styles label{width:100%;text-align:left;}
div#custom_styles label.smalllabel{padding:4px 1px 0 0; width:208px;text-align:right;margin:0; font-size:0.9em;}
div#custom_styles div.colorpicker_container{width:25px; float:right;}
div#custom_styles div.colorpicker_container div{width:16px; height:16px; margin-top:10px;border:1px solid #999;}
div#custom_styles input.text_hex{font-size:0.8em;width:44px;height:14px;margin-left:0;}
div#custom_styles .errordiv{clear:both;}
div#custom_styles .errordiv{padding:0 3px;}
div#custom_fonts div.custom_style{width:50%;}
div#custom_fonts label{padding-top:0 ! important;}
div#custom_fonts select{display:block;float:left; height:20px;}

div.startpage{
	float:left;
	width:100%;
}

div.startpage select{
	display:block;
	float:left;
	margin:6px 15px 0 0;
}

span#splashpage_interval span, span#bookselect span{
	display:block;
	float:left;
	line-height:32px;
	margin-right:3px;
}

span#splashpage_interval input{
	display:block;
	float:left;
}

/* EDIT SPLASH IMAGES */
div#edit_splashpages_container{
	margin:0 0 5px 10px;
	height:277px;
	width:880px;
	margin-top:8px;
}

div#edit_splashpages_container iframe{
	width:880px ! important;
	height:282px;
}

div#edit_splashpages_container p{line-height:50px;padding-left:30px;}

div#splash_image_container{
	position:relative;
	width:100%;
}

body.edit_splash_images {
	overflow-x:hidden;
	padding-bottom:40px;
}

div#splash_images{
	width:100%;
	margin-top:-8px;
	z-index:1;
	overflow:auto;
}

div#splash_images div.splash_image{
	border:1px solid #666;
	position:relative;
	width:161px;
	height:107px;
	float:left;
	padding-top:5px;
	text-align:center;
	margin:8px 4px 0 4px;
	background: url('<?php echo $_SESSION['webpath'] ?>images/admin/loading-small.png') no-repeat 72px 32px;
	overflow:hidden;
}

div#splash_images img.splash_thumb{
	height:70px;
	margin-bottom:10px;
	padding:0 29px;
}

div#splash_images span.icons{
	display:block;
	width:90px;
	height:20px;
	margin:auto;
	position:absolute;
	bottom:8px;
	left:39px;
}

div#splash_images span.icons span{display:block; float:left;width:24px;text-align:center;}
div#splash_images span.icons span.remove{width:42px;text-align:right;}

div#splash_images input.file{
	margin-top:10px;
	clear:both;
}

div#add_splashimage_row{
	position:fixed;
	top:240px;
	width:100%;
	height:40px;
	z-index:2;
	padding-top:1px;
}

div#add_splashimage_row span{
	whitespace:no-wrap;
}

div#add_splashimage_row label{
	padding:0;
	margin:0 0 0 10px;
	line-height:40px ! important;
	height:15px;
	width:140px;
}
div#add_splashimage_row input.file{margin-top:9px;}
div#add_splashimage_row input.clear_button{margin-top:10px;}
div#add_splashimage_row label.smalllabel{width:60px;text-align:left;}
div#add_splashimage_row span.smalllabel{margin:2px 0;}
div#add_splashimage_row input.text_number{
	margin:7px 5px 0 2px;
}

div#add_splashimage_row input.checkbox{margin:12px 5px 0 15px;}

div#add_splashimage_row .submit{
	background-image:url("<?php echo $_SESSION['webpath'] ?>images/admin/upload.png");
	background-position:120px 4px;
	line-height:20px;
	width:140px;
	margin:4px 10px 0 0;
	float:right;
}

div#add_splashimage_row input.submit{height:32px;width:150px;}

select#gallery_display{display:block;float:left;}
span#thumbnail_ratio_select span{display:block;float:left;line-height:35px;}
span#thumbnail_ratio_select select{display:block;float:left;}

/**************************/
/****** NAVIGATION ********/
/**************************/
div#navigation_container div.subsection_header span{
	display:block;
	float:left;
}

div#gallery_navigation_style_row{height:35px;}
select#gallery_navigation_style{display:block;float:left;}
div#nav_image_format_select{width:300px;float:left;}
div#nav_image_format_select label{display:block; float:left;}
div#nav_image_format_select select{display:block; float:left;}

div#navigation_container div.subsection_header span.title{width:270px;}
div#navigation_container div.subsection_header span.folder{width:330px;}

div#navigation_container div.subsection_header span.move{width:70px;}
div#navigation_container div.subsection_header span.delete{width:60px;}
div#navigation_container div.subsection_header span.image{width:55px;}

div.navrow span.move{ width:28px;}
div.navrow span.remove{width:70px;}
div.navrow span.edit_nav_image{width:50px;}

div#navigation_container span.icons{float:right ! important;}
div#navigation_container span.icons span{text-align:center;}
input.text_navigation{width:240px;margin:9px 20px 0 0;}

div.navrow select{
	display:block;
	float:left;
	margin-top:9px;
}

div.navrow span{
	display:block;
	float:left;
	height:35px;
	padding-top:5px;
 	text-align:center; 
}

.addremove{
	text-align:center;
}

span.move a, span.edit_nav_image img{
	cursor:pointer ! important;
}

/* EDIT NAV IMAGES */
div.edit_nav_image_container{
	width:880px;
	position:absolute;
}

div.edit_nav_image_container h3 {
	float:none;
	line-height:20px;
	padding:5px;
	margin-bottom:10px;
	height:20px;
}

div.edit_nav_image_container div.submitrow{
	position:relative;
	float:right;
	margin-top:25px;
	padding:0;
	height:40px;
	text-align:center;
}

div.edit_nav_image_container div.submitrow input, div.edit_nav_image_container div.submitrow a.submit{
	width:160px;
	height:30px;
	line-height:30px;
	display:float;
	float:left ! important;
	margin:2px 5px 0 0;
	background-position:135px 5px;
	padding:0 0 0 5px ! important;
}
div.edit_nav_image_container div.submitrow input.submit, div.edit_nav_image_container div.submitrow a.submit{
	background-image:url("<?php echo $_SESSION['webpath'] ?>images/admin/save.png");
}

div.edit_nav_image_container div.submitrow input{
	font-size:1em ! important;
}

div.edit_nav_image_container div.submitrow a.submit{
	width:155px;
}

div.edit_nav_image_container div.submitrow a.submit span{
	padding-left:5px;
}

div.edit_nav_image_container div.submitrow input, div.edit_nav_image_container div.submitrow a.button{
	padding:0;
}

div.edit_nav_image_container div.submitrow a.submit span{padding-top:0;}

div.edit_nav_image_container p{line-height:40px;padding-left:10px;}
div.edit_nav_image_container div{border:0;}

div.edit_nav_image_container div.close a{
	background:url("<?php echo $_SESSION['webpath'] ?>images/admin/close.png") no-repeat 5px 5px;
	width:30px;
	height:30px;
	cursor:pointer;
}

div.edit_nav_image_row{
	width:870px;
	height:150px;	
}

div.edit_nav_image_container div.image{
	text-align:center;
	float:left;
	width:350px;
}

div.edit_nav_image_container h4{
	font-weight:bold;
	text-align:center;
	margin-bottom:5px;
}

div.edit_nav_image_container span.img{
	border:1px solid #333;
	padding:5px;
	display:block;
	height:25px;
	margin:0 5px;
	float:none;
}

div.edit_nav_image_container span.img img.nav_image{
	height:22px;
	display:block;
	float:left;
}

div.edit_nav_image_container span.img a{
	display:block;
	float:right;
}

div.edit_nav_image_container div.image input{
	margin:auto;
	float:none;
}

div.edit_nav_image_container div.image input.file{
	margin:10px 2px 0 10px;
	background-color:#fff;
	float:left;
}

div.edit_nav_image_container div.image a.delete_link{cursor:pointer;}

div.edit_nav_image_container div.image input.clear_button{width:70px;margin-top:10px;}

/**************************/
/******** ALIASES *********/
/**************************/
input.text_aliases{width:300px;}

div.alias_entry .remove{
	margin:4px 8px 0 0;
	float:right;
}

/**************************/
/****** HELP POPOVERS******/
/**************************/
div#popover-gallery_name{}
div#popover-admin_email{}
div#popover-admin_email{}
div#popover-admin_email{}


/**************************/
/******** WIDGETS *********/
/**************************/
/* TABS */
div.tabcontainer ul.tabs {border-bottom:2px solid #ccc;height:33px;margin-top:0 ! important;}
div.tabcontainer ul.tabs li {display:block; float:left; height:33px; cursor:pointer; cursor:hand; padding:0 ! important; margin:0 ! important;}
div.tabcontainer ul.tabs li div { background:   }
div.tabcontainer ul.tabs li a {display:block; padding:10px 4px 5px 4px ! important; text-align:center; text-decoration:none; font-weight:bold; font-size:0.9em;}

li#tab_general{width:190px;}
li#tab_customization{width:160px;}
li#tab_aliases{width:130px;}
li#tab_books{width:120px;}
li#tab_navigation{width:140px;}
li#tab_metadata{width:140px;}

/* COLOR PICKER */
.colorpicker{
	-moz-user-select:none;
	-khtml-user-select:none;
	user-select:none;
	width:200px;
	height:170px;
	float:right;
//	position:relative;
	z-index:3;
	overflow:hidden;
	background-color:#888;
	padding:5px;
}
.colorpicker .header{background-color:#666;}

.plugHEX { float:left; position:relative; top:-1px; }
.close {
	float:right; 
	cursor: pointer;
	margin: 0 8px;
	-moz-user-select:none;
	-khtml-user-select:none;
	user-select:none;
}

.plugHEX:hover, #plugCLOSE:hover { color:#ffd000;  }
.plugCUR { float:left; width:10px; height:10px; font-size:1px; background:#fff; margin-right:3px; border:1px solid #fff; }

.SV {
	background:#FF0000 url("<?php echo $_SESSION['webpath'] ?>images/admin/SatVal.png");
	_background:#FF0000;
	position:relative;
	cursor:crosshair; 
	float:left;
	height:166px;
	width:167px;
	_width: 166px;
	margin:3px 10px 0 0;
	-moz-user-select: none; -khtml-user-select: none; user-select: none;
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="<?php echo $_SESSION['webpath'] ?>images/admin/SatVal.png", sizingMethod="scale");	
}

.SVslide {
	background:url("<?php echo $_SESSION['webpath'] ?>images/admin/slide.gif");
	height:9px;
	width:9px;
	position:absolute;
	top:-4px;
	left:-4px;	
	font-size:1px;
	line-height:1px;
}

.H {
	cursor:crosshair;
	float:left;
	height:154px;
	width:19px;
	position:relative;
	padding:0;
	top:7px;
}

.Hslide {
	background:url("<?php echo $_SESSION['webpath'] ?>images/admin/slideHue.gif");
	height:5px;
	width:33px;
	position:absolute;
	top:-7px;
	left:-8px;
	_font-size:1px;
	line-height: 1px;
}
.Hmodel { position:relative; top:-5px; }
.Hmodel div {height:1px; width:19px; font-size:1px; line-height:1px; margin: 0; padding:0; }