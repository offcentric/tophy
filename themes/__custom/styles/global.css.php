/******************************************/
/************ GLOBAL ELEMENTS *************/
/******************************************/
body{
	font-size:<?php echo $_SESSION['custom_main_font_size']; ?>;
	font-family:<?php echo $_SESSION['custom_main_font_family']; ?>;
	background-color:#<?php echo $_SESSION['custom_body_bgcolor'] ?>;
	color:#<?php echo $_SESSION['custom_body_textcolor'] ?>;
}
body.iframe{background-color:#<?php echo $_SESSION['custom_container_bgcolor'] ?>; ! important;}

h1{color:#<?php echo $_SESSION['custom_bar_textcolor'] ?>;}
h2{color:#000; background:#ccc;}
a:link, a:visited{color:#<?php echo $_SESSION['custom_link_textcolor'] ?>;}
a:hover{color:#<?php echo $_SESSION['custom_link_hover_textcolor'] ?>;text-decoration:none;}
input, textarea{font:0.9em black "lucida grande", Verdana, sans-serif;}
input.text, textarea{border:1px solid #ccc;}
input.submit, input.reset{background-color:#<?php echo $_SESSION['custom_bar_bgcolor'] ?>; color:#<?php echo $_SESSION['custom_bar_textcolor'] ?>; border:1px solid #aaa;}
iframe{background-color:#<?php echo $_SESSION['custom_container_bgcolor'] ?>;}

div.container{background-color:#<?php echo $_SESSION['custom_container_bgcolor'] ?>;}

div.thickbar{
	background-color:#<?php echo $_SESSION['custom_bar2_bgcolor'] ?>;
}

div.navbar{
	height:5px;
	background-color:#<?php echo $_SESSION['custom_bar_bgcolor'] ?>;
	border-bottom:1px solid <?php echo $_SESSION['custom_bar2_bgcolor'] ?>;
}

.colorbar{
	background-color:#<?php echo $_SESSION['custom_bar_bgcolor'] ?>;
	color:#<?php echo $_SESSION['custom_bar_textcolor'] ?>;
}

/* MAIN NAV */
ul#nav{
	font-size:<?php echo $_SESSION['custom_nav_font_size'] ?>;
	font-family:<?php echo $_SESSION['custom_nav_font_family'] ?>;
	border-bottom-color:#<?php echo $_SESSION['custom_nav_active_bgcolor'] ?>;
	background-color:#<?php echo $_SESSION['custom_container_bgcolor'] ?>;
}

.item_on{
	color:#<?php echo $_SESSION['custom_nav_active_textcolor'] ?>;
	background-color:#<?php echo $_SESSION['custom_nav_active_bgcolor'] ?>;
}

ul#nav li a{color:#<?php echo $_SESSION['custom_nav_textcolor'] ?>;}
ul#nav li a:hover{color:#<?php echo $_SESSION['custom_nav_hover_textcolor'] ?>; }
.item_on a{color:#<?php echo $_SESSION['custom_nav_active_textcolor'] ?>;}
ul#nav li.item_on a:hover{color:#<?php echo $_SESSION['custom_nav_active_textcolor'] ?>;}
ul#nav li.item_over{}


/* FOOTER */
div#footer{
	font-size:0.7em;
	height:24px;
	line-height:24px;
	border-top:1px solid <?php echo $_SESSION['custom_bar2_bgcolor'] ?>;
}

div.colorbar a{color:#fff;}
div.colorbar a:hover{text-decoration:underline;}

/* PAGE */
div.intro{
	font-size:0.8em;
	line-height:0.8em;
	color:#<?php echo $_SESSION['custom_body_textcolor'] ?>;
}

/* CUSTOMIZED THICKBOX */
#TB_window a:link {color: #ccc;}
#TB_window a:visited {color: #ccc;}
#TB_window a:hover {color: <?php echo $_SESSION['custom_link_textcolor'] ?>;}
#TB_window a:active {color: <?php echo $_SESSION['custom_link_textcolor'] ?>;}
#TB_window a:focus{color: <?php echo $_SESSION['custom_link_textcolor'] ?>;}

/* ABOUT PAGE */
div.detail h2{border-bottom:1px solid #0033aa;}
div.detail p{font-size:0.8em;line-height:1.5em;letter-spacing:0.07em;}

/* CONTACT PAGE */
div.contact form input.text, div.contact form textarea{
	background-color:#f3f3f3;
	border:1px solid <?php echo $_SESSION['custom_bar_bgcolor'] ?>;
}

div.contact form label{
	background-color:#<?php echo $_SESSION['custom_bar_bgcolor'] ?>;
	color:#fff;
	font:13px bold Arial, sans-serif;
}

/* GENERIC CONTENT PAGE */
div.content h1{font-size:1.6em;color:#<?php echo $_SESSION['custom_bar_bgcolor'] ?>;}
div.content h2{font-size:1.3em;border-bottom:1px solid <?php echo $_SESSION['custom_bar2_bgcolor'] ?>;}
div.content p{font-size:0.8em;}

/* PHOTO PAGE */
body#photopage{
	font:1em helvetica, "lucida grande", verdana, helvetica, arial, sans-serif;
	background-color:#181818;
	color:#fff;
}

body#photopage a:link, body#photopage a:visited{color:#fff;}
body#photopage a:hover{color:#<?php echo $_SESSION['custom_link_textcolor'] ?>;}

body#photopage div.exif_info_column div.left_column{background-color:#222;}
body#photopage div.exif_info_column div.right_column{background-color:#444;}

body#photopage div#title_bar{
	height:20px;
	line-height:20px;
	background-color:#222;
}