/************************************************************/
/****************** GALLERY CORE MODULE *********************/
/************************************************************/

var Showcase = {
	show_label : function(){
		$("span#showcase_text_" + this.rel).fadeTo("fast", 0.7);
	},
	
	hide_label : function(){
		$("span#showcase_text_" + this.rel).fadeTo("fast", 0);
		$(this).dequeue();
	},

	init : function(){
		$("body.showcase span.showcase_text").addClass("transparent");
//		$("body.showcase span.showcase_text").appendTo($("div#showcase_text_container"));		
		$("body.showcase a.clickarea").bind("mouseover", Showcase.show_label);
		$("body.showcase a.clickarea").bind("mouseout", Showcase.hide_label);
		$("body.showcase a.imagelink img").unwrap();
	}
}

var Splash = {
	slides : new Array(),
	max_height : 0,
	
	empty : function(){
	},
	
	init : function(){
		$("#splash img.default").css("left", ($("#splash").innerWidth() - $("#splash-default img.default").width())/2 + "px");
		if(this.slides.length >0){
			$(this.slides).each(function(){
				var img = document.createElement("img");
				img.src = this.src;
				$(img).css("left", ($("#splash").innerWidth() - img.width)/2 + "px");
				$("#splash").append(img);
				if(img.height > Splash.max_height)
					Splash.max_height = img.height;
			});
			setTimeout('$("#splash-default").remove()',100);
			$("#splash").innerfade({speed: 500, timeout: <?php echo ($_SESSION['cm__gallery']['slideshow_interval'] * 1000);?>, containerheight: Splash.max_height + 'px'});	
		}
	}
}

var Thumbnails = {
	nav_links_flashed : false,
	
	init : function(){
	},
	
	fadeout : function(el){
		$("div", el).animate({opacity:"0"},{queue:false,duration:200});
	},

	fadein : function(el){
		$("div", el).animate({opacity:"1.0"},{queue:false,duration:200});
	}
	
};


function cm__gallery_init(){
	Showcase.init();
	Splash.empty();
}

function cm__gallery_init_post(){
	Splash.init();
}

if(BrowserDetect.supported()){
	$(document).ready(cm__gallery_init);
	window.onload = cm__gallery_init_post;
}


/************************************************************/
/**********************THICKBOX******************************/
/************************************************************/

/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 * Heavily customized by Mark Mulder for advanced image popover support, fixed for use with jQuery 1.3+
*/
		  
var tb_pathToImage = "<?php echo $_SESSION['webpath'] ?>images/thickbox/loading_animation.gif?m=<?php echo @$_SESSION['module'] ?>";
var TB_IsImage = false;
var TB_die = false;

/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/

//on page load call tb_init
$(document).ready(function(){
	tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
	imgLoader = new Image();// preload image
	imgLoader.src = tb_pathToImage;
	
	load_image();
});

//add thickbox to href & area elements that have a class of .thickbox
function tb_init(domChunk){
	$(domChunk).click(function(){
		var t = this.title || this.name || null;
		var a = this.href || this.alt;
		var g = this.rel || false;
		var i = this.id || null;
		tb_show(t,a,g,i,false);
		TB_die = false;
		this.blur();
		return false;
	});
}

function tb_show(caption, url, imageGroup, linkId, exifOn) {//function called when the user clicks on a thickbox link
	try {
		// if thickbox is called from within an iframe, get reference to the parent window object
		if(window.self == window.parent){
			context = document;
		}else{
			context = window.parent.document;
		}
		if (typeof document.body.style.maxHeight === "undefined") {//if IE 6
			$("body", context).css({height: "100%", width: "100%"});
			$("html", context).css("overflow","hidden");
			if (document.getElementById("TB_HideSelect") === null) {//iframe to hide select elements in ie6
				$("body", context).append("<iframe id='TB_HideSelect'></iframe><div id='TB_overlay'><div id='TB_escapelink'><a hef='#'>&lt;&lt; back to thumbnail view (or press <strong>esc</strong>)</a></div></div><div id='TB_window'></div>");
				$("#TB_escapelink", context).click(tb_remove);
			}
		}else{//all others
			if(document.getElementById("TB_overlay") === null){
				$("body", context).append("<div id='TB_overlay'><div id='TB_overlay_inner'><div id='TB_escapelink'><a hef='#'>&lt;&lt; back to thumbnail view (or press <strong>esc</strong>)</a></div></div></div><div id='TB_window'></div>");
				$("#TB_escapelink a", context).bind("click", tb_remove);
			}
		}
		
		if(tb_detectMacXFF()){
			$("#TB_overlay", context).addClass("TB_overlayMacFFBGHack");//use png overlay so hide flash
		}else{
			$("#TB_overlay", context).addClass("TB_overlayBG");//use background and opacity
		}
		
		if(caption===null){caption="";}
		$("body", context).append("<div id='TB_load'><img src='"+imgLoader.src+"' /></div>");//add loader to the page
		$('#TB_load', context).show();//show loader
		
		var baseURL;
	   if(url.indexOf("?")!==-1){ //ff there is a query string involved
			baseURL = url.substr(0, url.indexOf("?"));
	   }else{ 
	   		baseURL = url;
	   }
	   
	   var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
	   var urlType = baseURL.toLowerCase().match(urlString);
	
		if(urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp'){//code to show images
			TB_IsImage =  true;
			TB_PrevCaption = "";
			TB_PrevURL = "";
			TB_HasNext = false;
			TB_NextCaption = "";
			TB_NextURL = "";
			TB_HasPrev = false;
			TB_imageCount = "";
			TB_FoundURL = false;
			TB_CurrentImage = 0;
			imageGroup = imageGroup.replace("'","");
			if(imageGroup){
				TB_TempArray = $("a[rel=\""+imageGroup+"\"]").get();
				for (TB_Counter = 0; ((TB_Counter < TB_TempArray.length) && !TB_HasNext); TB_Counter++) {
					var urlTypeTemp = TB_TempArray[TB_Counter].href.toLowerCase().match(urlString);
						if (!(TB_TempArray[TB_Counter].href == url)) {						
							if (TB_FoundURL) {
								TB_NextCaption = TB_TempArray[TB_Counter].title;
								TB_NextURL = TB_TempArray[TB_Counter].href;
								TB_HasNext = true;
								TB_NextId = TB_TempArray[TB_Counter].id;
							} else {
								TB_PrevCaption = TB_TempArray[TB_Counter].title;
								TB_PrevURL = TB_TempArray[TB_Counter].href;
								TB_PrevId = TB_TempArray[TB_Counter].id;
								TB_HasPrev = true;
							}
						} else {
							TB_FoundURL = true;
							TB_imageCount = "Image " + (TB_Counter + 1) +" of "+ (TB_TempArray.length);											
							TB_CurrentImage = TB_Counter;
						}
				}
			}

			imgPreloader = new Image();
			imgPreloader.onload = function(){
					if(!TB_die){
					imgPreloader.onload = null;
				
					// Resizing large images - orginal by Christian Montoya edited by me.
					var pagesize = tb_getPageSize();
					var x = pagesize[0] - 150;
					var y = pagesize[1] - 80;
					var imageWidth = imgPreloader.width;
					var imageHeight = imgPreloader.height;
					if (imageWidth > x) {
						imageHeight = imageHeight * (x / imageWidth); 
						imageWidth = x; 
						if (imageHeight > y) { 
							imageWidth = imageWidth * (y / imageHeight); 
							imageHeight = y; 
						}
					} else if (imageHeight > y) { 
						imageWidth = imageWidth * (y / imageHeight); 
						imageHeight = y; 
						if (imageWidth > x) { 
							imageHeight = imageHeight * (x / imageWidth); 
							imageWidth = x;
						}
					}
					// End Resizing		
			
					TB_WIDTH = parseInt(imageWidth);
					TB_HEIGHT = imageHeight;
					window.imageWidth = imageWidth;
					window.imageHeight = imageHeight;

					var exifData = "";
					if($("span#exif_container_" + linkId).length>0)
						exifData = $("span#exif_container_" + linkId).html();

					if(exifData != ""){
						exifButton = "&nbsp;&nbsp;<span id='exif_panel_clickzone'><img id='exif_button' src='<?php echo $_SESSION['webpath'] ?>images/thickbox/button_details.gif' alt='show EXIF details' title='show EXIF details' /></span>";
						exifPanel = "<div id='exif_panel' class='jshidden'><div id='exif_panel_content'>" + exifData + "</div></div>";
					}else{
						exifButton = "<span id='exif_panel_clickzone'><img src='<?php echo $_SESSION['webpath'] ?>images/thickbox/t.gif' alt='' title='' /></span>";
						exifPanel = "";
					}
					var filename = url;
					while(filename.indexOf("/")!=-1){
						filename = filename.substring(filename.indexOf("/")+1);
					}
					var TB_nav = "";
					if(imageGroup)
						TB_nav = "<div class=\"nav_buttons\"><div class=\"gallery_navlink\" id=\"TB_prevlink\"><span title=\"previous photo (or press &larr;)\" alt=\"previous photo (or press &larr;)\" ><img src='<?php echo $_SESSION['webpath'] ?>images/thickbox/prev.png?m=<?php echo $_SESSION['cm__gallery']['module_name'] ?>&t=<?php echo $_SESSION['theme'] ?>' alt='previous' /></span><div></div></div><div class=\"gallery_navlink\" id=\"TB_nextlink\"><span alt=\"next photo (or press &rarr;)\" title=\"next photo (or press &rarr;)\" ><img src='<?php echo $_SESSION['webpath'] ?>images/thickbox/next.png?m=<?php echo $_SESSION['cm__gallery']['module_name'] ?>&t=<?php echo $_SESSION['theme'] ?>' alt='next' /></span><div></div></div></div>";
					var TB_html = "<a href=\"\" id=\"TB_ImageOff\" title=\"click to close (or press Esc)\" alt=\"click to close (or press Esc)\"><img id=\"TB_Image\" src=\""+url+"\" width=\""+imageWidth+"\" height=\""+imageHeight+"\" alt=\""+caption+"\"/></a>";
					var TB_bottombar = "";
					if(caption != "")
						TB_bottombar = "<div id=\"TB_caption\"><span id=\"title\"><a title=\"Click to view in larger size\" alt=\"Click to view in larger size\" href=\"<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . stripslashes(@$_GET['b']) ?><?php if(@$_GET['p'] != ""){echo "/".@$_GET['p'];}  ?>/photo:" + filename + "\">"+caption+"</a></span>"+exifButton+"<div id=\"TB_secondLine\">&nbsp;&nbsp;</div></div><div id=\"TB_closeWindow\"><a href=\"#\" id=\"TB_closeWindowButton\" alt=\"click to close (or press Esc)\" title=\"click to close (or press Esc)\"><img src=\"<?php echo $_SESSION['webpath'] ?>images/thickbox/button_close.gif\" alt=\"click to close (or press Esc)\" title=\"click to close (or press Esc)\" /></a></div><div id=\"exif_panel_container\">" + exifPanel + "</div>";

					$("#TB_window", context).append(TB_html + TB_bottombar);
					$("#TB_overlay_inner", context).append(TB_nav);
					$("#TB_closeWindowButton").click(tb_remove);
					var panel_mouseover = false;
					var showExif = false;
					if(exifData != ""){
						var click_image = new Image();
						click_image.src = "<?php echo $_SESSION['webpath'] ?>images/thickbox/button_details.gif";
						var click_image_on = new Image();
						click_image_on.src = "<?php echo $_SESSION['webpath'] ?>images/thickbox/button_details_on.gif";
						var click_image_over = new Image();
						click_image_over.src = "<?php echo $_SESSION['webpath'] ?>images/thickbox/button_details_over.gif";
						var close_image = new Image();
						close_image.src = "<?php echo $_SESSION['webpath'] ?>images/thickbox/button_close.gif";
						var close_image_over = new Image();
						close_image_over.src = "<?php echo $_SESSION['webpath'] ?>images/thickbox/button_close_over.gif";
						function toggleExif(){
							if(!showExif){
								$("#TB_window #exif_panel_container").css("marginTop", "-" + ($("#TB_window #exif_panel_container .exif_info_column").length * 20 + 8) + "px");
								$("#exif_panel").removeClass("jshidden");
								$("#exif_panel_clickzone img").attr("title", "hide EXIF details");
								$("#exif_panel_clickzone img").attr("alt", "hide EXIF details");
								$("#exif_panel_clickzone img")[0].src = click_image_on.src;
								showExif = true;
							}else{
								$("#exif_panel").addClass("jshidden");
								$("#exif_panel_clickzone img").attr("title", "show EXIF details");
								$("#exif_panel_clickzone img").attr("alt", "show EXIF details"); 
								$("#exif_panel_clickzone img")[0].src = click_image.src;
								showExif = false;
							}
						}
						if(exifOn) toggleExif();
						$("#exif_panel_clickzone").click(toggleExif);
						$("#exif_panel_clickzone").mouseover(function(){if(!showExif){$("#exif_panel_clickzone img")[0].src = click_image_over.src;}})
						$("#exif_panel_clickzone").mouseout(function(){if(!showExif){$("#exif_panel_clickzone img")[0].src = click_image.src;}})
						$("#TB_closeWindowButton").mouseover(function(){$("#TB_closeWindowButton img")[0].src = close_image_over.src;})
						$("#TB_closeWindowButton").mouseout(function(){$("#TB_closeWindowButton img")[0].src = close_image.src;})
					}else{
						var showExif = exifOn;
					}
					// append URL with anchor
					window.location.href = window.location.href.substr(0,window.location.href.indexOf("#")) + "#" + filename;

					if(imageGroup){
						$(".gallery_navlink").css("height",(TB_HEIGHT-20)+"px");
						$(".gallery_navlink div").css("backgroundPosition","10px " + (TB_HEIGHT/2-150)+"px");

						/*
						$(".gallery_navlink").each(function(i, el){
							if(!Thumbnails.nav_links_flashed){
								$("div", el).animate({opacity:"0"},{duration:1000});
							}else{
								$("div", el).css("opacity", "0");
							}
						});
						Thumbnails.nav_links_flashed = true;
					
						$(".gallery_navlink").each(function(i, el){
							if(!(TB_CurrentImage==0 && el.id.indexOf("prev")!=-1) && !(TB_CurrentImage==TB_TempArray.length-1 && el.id.indexOf("next")!=-1)){
								$(el).css("cursor", "pointer");
								$(el).bind("mouseover", function(){Thumbnails.fadein(el)});
								$(el).bind("mouseout", function(){Thumbnails.fadeout(el)});
							}else{
								$(el).css("display", "none");						
							}
						});
						*/
						if (TB_HasPrev) {
							function goPrev(){
								if($(document, context).unbind("click",goPrev)){$(document, context).unbind("click",goPrev);}
								$("#TB_window", context).remove();
	//							$("#TB_escapelink", context).remove();
								$(".nav_buttons", context).remove();
								$("body", context).append("<div id='TB_window'></div>");
								tb_show(TB_PrevCaption, TB_PrevURL, imageGroup, TB_PrevId, showExif);
								return false;	
							}
							var prevlink = $("#TB_prevlink")[0];
							var prevlink_img = $("#TB_prevlink img")[0];
							prevlink_img.onload = function(){
								$("#TB_prevlink")[0].style.top = ((window.imageHeight/2) - this.height/2)+'px';
							}
							prevlink.style.left = -(imageWidth/2)+'px';
							$("#TB_prevlink span", context).click(goPrev);
						}else{
							$("#TB_prevlink span", context).remove();
						}
			
						if (TB_HasNext) {		
							function goNext(){
								$("#TB_window", context).remove();
	//							$("#TB_escapelink", context).remove();
								$(".nav_buttons", context).remove();
								$("body", context).append("<div id='TB_window'></div>");
								tb_show(TB_NextCaption, TB_NextURL, imageGroup, TB_NextId, showExif);				
								return false;	
							}
							var nextlink = $("#TB_nextlink")[0];
							nextlink.style.right = -(imageWidth/2)+'px';
							var nextlink_img = $("#TB_nextlink img")[0];
							nextlink_img.onload = function(){
								$("#TB_nextlink")[0].style.top = ((window.imageHeight/2) - this.height/2)+'px';
							}
							$("#TB_nextlink span", context).click(goNext);
						}else{
							$("#TB_nextlink span", context).remove();
						}
					}
					document.onkeydown = function(e){ 	
						if (e == null) { // ie
							keycode = event.keyCode;
						} else { // mozilla
							keycode = e.which;
						}
						if(keycode == 27){ // close
							tb_remove();
						} else if(keycode == 190 || keycode == 39){ // display previous image
							if(TB_HasNext){
								document.onkeydown = "";
								goNext();
							}
						} else if(keycode == 188  || keycode == 37){ // display next image
							if(TB_HasPrev){
								document.onkeydown = "";
								goPrev();
							}
						}	
					};
					Thumbnails.init();
					tb_position();
					$(".pagecontainer").hide();
					$("#TB_load", context).remove();
					$("#TB_ImageOff", context).click(tb_remove);
					$("#TB_window", context).css({display:"block"}); //for safari using css instead of show
				}
			};
			imgPreloader.src = url;
			
		}else{//code to show html
			var queryString = url.replace(/^[^\?]+\??/,'');
			var params = tb_parseQuery( queryString );
			TB_WIDTH = (params['width']*1) + 30 || 630; //defaults to 630 if no paramaters were added to URL
			TB_HEIGHT = (params['height']*1) + 40 || 440; //defaults to 440 if no paramaters were added to URL
			ajaxContentW = TB_WIDTH - 30;
			ajaxContentH = TB_HEIGHT - 45;

			if(url.indexOf('TB_iframe') != -1){// either iframe or ajax window    
				urlNoQuery = url.split('TB_');
				$("#TB_iframeContent").remove();
				if(params['modal'] != "true"){//iframe no modal
					$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='Close'>close</a> or Esc Key</div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' > </iframe>");
				}else{//iframe modal
					$("#TB_overlay").unbind();
					$("#TB_window").append("<iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;'> </iframe>");
				}
			}else{// not an iframe, ajax
				if($("#TB_window").css("display") != "block"){
					if(params['modal'] != "true"){//ajax no modal
						$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'>close</a> or Esc Key</div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>");
					}else{//ajax modal
						$("#TB_overlay").unbind();
						$("#TB_window").append("<div id='TB_ajaxContent' class='TB_modal' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>");  
					}
				}else{//this means the window is already up, we are just loading new content via ajax
					$("#TB_ajaxContent")[0].style.width = ajaxContentW +"px";
					$("#TB_ajaxContent")[0].style.height = ajaxContentH +"px";
					$("#TB_ajaxContent")[0].scrollTop = 0;
					$("#TB_ajaxWindowTitle").html(caption);
				}
			}

			$("#TB_closeWindowButton").click(tb_remove);

			if(url.indexOf('TB_inline') != -1){  
				$("#TB_ajaxContent").append($('#' + params['inlineId']).children());
				$("#TB_window").unload(function () {
					$('#' + params['inlineId']).append( $("#TB_ajaxContent").children() ); // move elements back when you're finished
				});
				tb_position();
				$("#TB_load").remove();
				$("#TB_window").css({display:"block"}); 
			}else if(url.indexOf('TB_iframe') != -1){
				tb_position();
				if($.browser.safari){//safari needs help because it will not fire iframe onload
					$("#TB_load").remove();
					$("#TB_window").css({display:"block"});
				}
			}else{
				$("#TB_ajaxContent").load(url += "&random=" + (new Date().getTime()),function(){//to do a post change this load method
					tb_position();
					$("#TB_load").remove();
					tb_init("#TB_ajaxContent a.thickbox");
					$("#TB_window").css({display:"block"});
				});
			}

		}

		if(!params['modal']){
			document.onkeyup = function(e){ 	
				if (e == null) { // ie
					keycode = event.keyCode;
				} else { // mozilla
					keycode = e.which;
				}
				if(keycode == 27){ // close
					tb_remove();
				}	
			};
		}
	} catch(e) {
		//nothing here
	}
}

//helper functions below
function tb_showIframe(){
	$("#TB_load", context).remove();
	$("#TB_window", context).css({display:"block"});
}

function tb_remove() {
	$("#exif_panel").addClass("jsnodisplay");
 	$("#TB_imageOff").unbind("click");
	$("#TB_closeWindowButton").unbind("click");
	$("#TB_overlay,#TB_window", context).fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect,#TB_escapelink', context).trigger("unload").unbind().remove();remove_anchor();});
	$("#TB_load").remove();
	TB_die = true;
	$(".pagecontainer").show();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		$("body",context).css({height: "auto", width: "auto"});
		$("html",context).css("overflow","");
	}
	document.onkeydown = "";
	document.onkeyup = "";
	
	return false;
}

function tb_position() {
	$("#TB_window",context).css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px'});
	if ( !(jQuery.browser.msie && jQuery.browser.version < 7)) { // take away IE6
		$("#TB_window", context).css({marginTop: '-' + (parseInt((TB_HEIGHT / 2),10)) + 'px'});
	}
}

function tb_parseQuery ( query ) {
   var Params = {};
   if ( ! query ) {return Params;}// return empty object
   var Pairs = query.split(/[;&]/);
   for ( var i = 0; i < Pairs.length; i++ ) {
      var KeyVal = Pairs[i].split('=');
      if ( ! KeyVal || KeyVal.length != 2 ) {continue;}
      var key = unescape( KeyVal[0] );
      var val = unescape( KeyVal[1] );
      val = val.replace(/\+/g, ' ');
      Params[key] = val;
   }
   return Params;
}

function tb_getPageSize(){
	if(window.self == window.parent){
		var _context = window;
	}else{
		var _context = window.parent;
	}
	var de = _context.document.documentElement;
	var w = _context.innerWidth || self.innerWidth || (de&&de.clientWidth) || _context.document.body.clientWidth;
	var h = _context.innerHeight || self.innerHeight || (de&&de.clientHeight) || _context.document.body.clientHeight;
	arrayPageSize = [w,h];
	return arrayPageSize;
}

function tb_detectMacXFF() {
  var userAgent = navigator.userAgent.toLowerCase();
  if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox')!=-1) {
    return true;
  }
}

function remove_anchor(){
	if(TB_IsImage){
		// remove anchor from URL
		if(window.location.href.indexOf("#") != -1 ) window.location.href = window.location.href.substring(0,window.location.href.indexOf("#")+1);
	}
}

function load_image(){
	if(window.location.href.indexOf("#") != -1 && window.location.href.indexOf("#") <  window.location.href.length-1){
		$("a.thickbox").each(function(){
			if(this.href.substring(this.href.length-(window.location.href.length-window.location.href.indexOf("#")-1)) == window.location.href.substring(window.location.href.indexOf("#")+1)){
				tb_show(this.title,this.href,this.rel,this.id,false);
			}
		});		
	}
}

/************************************************************/
/********************END THICKBOX****************************/
/************************************************************/