Admin = {
	init : function(){
		Customization.init();
		Navigation.init();
		Aliases.init();
		Admin.init_ajax_submit();
		Formpage.init();
		Admin.display_clear_buttons();
		if(BrowserDetect.supported()) insertJSFile('<?php echo $_SESSION['webpath'] ?>scripts/colorpicker-jquery.js');
		if(BrowserDetect.supported()) insertJSFile('<?php echo $_SESSION['webpath'] ?>scripts/omnitab-jquery.js');
	},

	init_ajax_submit : function(){
		$("form.validate").submit(function(){
			if($("#json_response", this).length ==0) $(this).append("<input type=\"hidden\" class=\"hidden\" name=\"json_response\" id=\"json_response\" value=\"true\" />");
			po = Window.popover({id: "save_form_popover", content: "<div style=\"text-align:center\"><br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/thickbox/loading_animation.gif\" /><br /><br /><br /><span>Saving your settings...</span></div>", width: 200, height: 130, top: 200, overlay: true});
			AIM.submit(this, {'onComplete': function(response){
				try{
					if(Admin.show_ajax_errors(JSON.parse(response))){
						$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/exclamation.png\" /><br /><br /><br /><span>You have errors in your submission. Please try again.</span>");					
					}else{
						$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/checkmark.png\" /><br /><br /><br /><span>Your settings are saved!</span>");
						Customization.update_main_logo();
						Admin.clear_file_field($("input#gallery_logo"));
					}
				}catch(e){
					$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/exclamation.png\" /><br /><br /><br /><span>Internal server error. Please contact your administrator.</span>");
				}
				setTimeout("$(\"div#aim_container\").remove();", 1);
				setTimeout("$(po).fadeOut(\"slow\", function(){Window.popover_kill(po);});", 1000);
			}})
		});		
	},
	
	show_ajax_errors : function(Response){
		var Errs = Response.errs;
		var first_error = "";
		if(Errs.length == 0) $(".errordiv").html("");
		$.each(Errs, function(){
			id = this.substring(0, this.indexOf("="));
			if(first_error == "") first_error = id;
			message = this.substring(this.indexOf("=")+1);
			$(".errordiv", $("#" + id).parents("div.formrow")).html("<div><p>" + message + "</p></div>");
		});
		if(first_error != ""){
			tab_id = $("#" + first_error).parents("div.tabcontainer_level1").attr("id");
			OmniTab.show(OmniTab.getTabObjectById(tab_id))
		}
		return (Errs.length > 0);
	},

	check_dependency : function(el, val, dependee_id, message){
		$("p.note", $(el).parents(".formrow")).remove();
		var dependee= $(dependee_id)[0];
		if(el.tagName.toLowerCase()=="input"){
			if(el.type.toLowerCase()=="checkbox"){
				if(el.checked){
					if(!dependee.checked && dependee.checked != "checked"){
						$(el).parents(".formrow").prepend("<p class=\"note\">" + message + "</p>");
						if(dependee.type.toLowerCase() == "checkbox"){
							dependee.checked = "checked";
						}
					}
				}
			}
		}else if(el.tagName.toLowerCase()=="select"){
			$("p.note", $(el).parents(".formrow")).remove();
			if(el.value == val){
				if(!dependee.checked && dependee.checked != "checked"){
					$(el).parents(".formrow").prepend("<p class=\"note\">" + message + "</p>");
					if(dependee.type.toLowerCase() == "checkbox"){
						dependee.checked = "checked";
					}
				}
			}			
		}
	},
	
	check_reverse_dependency : function(el, val, depending_id, message){
		var dependant = $(depending_id)[0];
		if(el.tagName.toLowerCase()=="input"){
			if(el.type.toLowerCase()=="checkbox"){
				if(!el.checked){
					if(dependant.tagName.toLowerCase() == "input"){
						if(dependant.type.toLowerCase() == "checkbox"){
							if(dependant.checked || dependant.checked == "checked"){
								$(el).parents(".formrow").prepend("<p class=\"note\">" + message + "</p>");
								dependant.checked = false;
							}
						}
					}else if(dependant.tagName.toLowerCase() == "select"){
						if(dependant.value == val){
							$(el).parents(".formrow").prepend("<p class=\"note\">" + message + "</p>");
							$(dependant.options).each(function(i, option){
								if(dependant.selectedIndex != i){
									dependant.selectedIndex = i;
									return false;
								}
							});
						}
					}
				}else{
					$("p.note", $(el).parents(".formrow")).remove();
				}
			}else{
				$("p.note", $(el).parents(".formrow")).remove();
			}			
		}
	},
	
	display_clear_buttons : function(){
		$("input.file").each(function(i, el){
			if($(el).next("input.clear_button").length == 0)
				$("<input type=\"button\" class=\"clear_button\" value=\"Clear\" />").insertAfter(el).bind("click", function(){Admin.clear_file_field($(el))})
		});
	},
	
	clear_file_field :  function($file){
		
		$file.hide();
		$file.after("<input type=\"file\" class=\"file\" name=\"" + $file.attr("name") + "\" id=\"" + $file.attr("id") + "\" />");		
		$file.remove();
	}
	
};

Customization = {
	init : function(){
		this.toggle_startpage();
		this.toggle_thumbnail_ratio();
		this.toggle_custom_theme(false);
		this.toggle_splashpage_interval();
		this.show_colorpicker_icons();
		
		$("#remove_main_logo").live("click",function(){Customization.remove_main_logo()});
		$("#remove_main_logo").removeAttr("href");

		$("#theme_select").bind("change", function(){Customization.toggle_custom_theme(true);})
		$(".custom_style input.text_hex").bind("blur", function(){Customization.check_hex_value(this);});
		$(".custom_style input.text_hex").bind("keyup", function(){if(this.value.length == 6)Customization.check_hex_value(this);});
		$("#startpage").bind("change", function(){Customization.toggle_startpage();});
		$("#startpage").bind("change", function(){Customization.toggle_splashpage_interval();});
		$("#gallery_display").bind("change", function(){Customization.toggle_thumbnail_ratio();})
		$("input#enable_all_books_list").bind("click", function(){Admin.check_reverse_dependency(this, "list", "#startpage", "If you disable full book listing, you cannot have 'List all books' as your startpage.");});
		$("input#enable_thickbox").bind("click", function(){Admin.check_reverse_dependency(this, "true", "#enable_slideshow", "If you disable Thickbox the slideshow feature cannot be enabled.");});
		$("input#enable_slideshow").bind("click", function(){Admin.check_dependency(this, "true", "#enable_thickbox", "The slideshow feature requires Thickbox to be enabled.");});
	},
	
	update_main_logo : function(){
		var logo_url = $("input#gallery_logo")[0].value;
		if(logo_url != ""){
			logo_image = $("#customization img.logo_image");
			logo_image[0].src = "<?php echo $_SESSION['webpath'] ?>images/logo."+ logo_url.substring(logo_url.length-3) + "?t=" + Math.floor(Math.random() * 99999);
				if($("#remove_main_logo").length==0) logo_image.after("<a class=\"remove_link\" id=\"remove_main_logo\"><img src=\"<?php $_SESSION['webpath'] ?>images/admin/delete.png\" alt=\"Remove logo image\"  title=\"Remove logo image\" /></a>");
				$("p.note", logo_image.parents("span.logo")).remove();
		}		
	},
	
	remove_main_logo : function(){
		if(confirm("Are you sure you want to delete the main logo? This cannot be undone.")){
			$.ajax({async: true,url: ".?action=remove_logo", success: function(){
				$("img.logo_image")[0].src="<?php echo $_SESSION['webpath'] ?>images/admin/no_image.gif";
				$("a#remove_main_logo").remove();
				Admin.clear_file_field($("input#gallery_logo"));
				$("span.logo p.note").remove();
				$("span.logo").prepend("<p class=\"note\">Main logo has been deleted.</p>")
			},error: function (XMLHttpRequest, textStatus, errorThrown){$("img.logo_image").after("Error in deleting main logo")}});
		}
	},

	toggle_custom_theme : function(reload_css){
		if($("#theme_select").length > 0){
			var theme = $("#theme_select")[0].value;
			if(theme == "") theme = "default";
			if(reload_css) this.replace_theme_css(theme);
			if($("#theme_select")[0].value == "custom"){
				$("#custom_styles").removeClass("jsnodisplay");
				if(reload_css) Customization.reload_custom_styles(false);
			}else{
				$("#custom_styles").addClass("jsnodisplay");
				if(reload_css) Customization.reload_custom_styles(true);
			}
		}
	},
	
	check_hex_value : function(el){
		var parent = $(el).parent("div.custom_style")[0];
		if(el.value.match(/[0-9a-f]{6}/i)){
			ColorPicker.init_cp($(".custom_style input.text_hex").index(el), $("div.colorpicker", parent)[0]);
			Customization.do_live_preview_color(el, false);
			$("div.colorpicker_container div", parent).css("background", "#"+ el.value);
			$(".errordiv", parent).html("");
			el.value = el.value.toUpperCase();
		}else{
			$(".errordiv", parent).html("<div>The value you have entered is invalid! The value must be a hexadecimal value of 6 characters long (e.g 53AE91)</div>");
			el.focus();
		}		
	},
	
	reload_custom_styles : function(clear){
		$("div#custom_colors input").each(function(){
			Customization.do_live_preview_color(this, clear);
		})
	},
	
	replace_theme_css : function(theme, context){
		if(context == undefined) context = document;
		var old_theme_css = $("link[href^='<?php echo $_SESSION['webpath'] ?>themes/<?php echo strtolower($_SESSION['theme']) ?>/styles/theme']", context)[0];
		var position = $("link", context).index(old_theme_css);
		var css = context.createElement("link");
		css.type = "text/css";
		css.rel = "stylesheet";
		css.href = "<?php echo $_SESSION['webpath'] ?>themes/<?php echo strtolower($_SESSION['theme']) ?>/styles/theme.css";
		css.media = "screen";
		stylesheets = $("link", $("head",context));
		stylesheets.each(function(i, stylesheet){
			if(stylesheet == old_theme_css){
				$("head",context)[0].insertBefore(css, stylesheet);
				$(stylesheet).remove();
				return false;
			}
		});
	},
	
	show_colorpicker_icons : function(){
		$("#custom_styles div.colorpicker_container").each(function(){
			$("div", this).css("cursor", "pointer");
			$(this).attr("title", "Open colorpicker");
			$("div", this).bind("click", function(){Customization.open_colorpicker(this)});
			
		});
	},
	
	open_colorpicker : function(img){
		var el = $(img).parent("div.colorpicker_container");
		var parent =  el.parent("div.custom_style");
		var cp = $("div.colorpicker", parent);
		if(cp.length > 0){
			$("div.colorpicker", parent).slideToggle("fast");
		}else{
			var cp_html = "<div class=\"colorpicker clearfix\">\n";
			cp_html += "	<div class=\"SV\" title=\"Saturation + Value\">\n";
			cp_html += "		<div class=\"SVslide\"><br /></div>\n";
			cp_html += "	</div>\n";
			cp_html += "	<form class=\"H\" title=\"Hue\">\n";
			cp_html += "		<div class=\"Hslide\"><br /></div>\n";
			cp_html += "		<div class=\"Hmodel\"></div>\n";
			cp_html += "	</form>\n";
			cp_html += "</div>\n";
			el.parent("div.custom_style").append(cp_html);
			$("div.colorpicker", parent).hide();
			$("div.colorpicker", parent).slideDown("fast");
			ColorPicker.init($("div.colorpicker_container").index(el));
		}
	},
	
	do_live_preview_color : function(input, clear, c){
//		if($("p.note", $(input).parents("div#custom_colors")).length == 0)
//			$(input).parents("div#custom_colors").prepend("<p class=\"note\">To save the changes you make to your custom color scheme, you must click the \"Save settings\" button.</p>");
		var v = "#" + input.value;
		if(clear) v = "";
		if(c == undefined) c = document
		switch($(input).attr("name")){
			case "custom_body_bgcolor":
				$("body", c).css("background-color", v);
				break;
		    case "custom_container_bgcolor":
				$("div.container", c).css("background-color", v);
				$("ul#nav", c).css("background-color", v);
				break;
			case "custom_body_textcolor":
				$("body", c).css("color", v);
				$("div.intro", c).css("color", v);
				break;
			case "custom_link_textcolor":
				$("a:not(ul#nav a)", c).css("color", v);
			 	break
			case "custom_link_hover_textcolor":
// jQuery selectors don't currently support pseudo selectors like :visited :hover :active etc, so this will be commented out for now
//				$("a:hover", c).css("color", v);
				break;
			case "custom_nav_textcolor":
				$("ul#nav li:not(.item_on) a", c).css("color", v);
				break;
			case "custom_nav_hover_textcolor":
// jQuery selectors don't currently support pseudo selectors like :visited :hover :active etc, so this will be commented out for now
//				$("ul#nav li a:hover", c).css("color", v);
				break;
			case "custom_nav_active_textcolor":
				$("ul#nav li.item_on", c).css("color", v);
				$("ul#nav li.item_on a", c).css("color", v);
				break;
			case "custom_nav_active_bgcolor":
				$("ul#nav", c).css("border-bottom-color", v);
				$("ul#nav li.item_on", c).css("background-color", v);			
				break;
			case "custom_bar_bgcolor":
				$("input.submit", c).css("background-color", v);
				$("div.navbar", c).css("background-color", v);
				$(".colorbar", c).css("background-color", v);
				break;
			case "custom_bar2_bgcolor":
				$("div.navbar", c).css("border-bottom-color", v);
				$("div.thickbar", c).css("background-color", v);
				$("div#footer", c).css("border-top-color", v);
				break;
			case "custom_bar_textcolor":
				$("h1", c).css("color", v);
				$("input.submit", c).css("color", v);
				$(".colorbar", c).css("color", v);
				break;
		}
	},

	
	toggle_startpage : function(){
		if($("#startpage").length > 0){
			Admin.check_dependency($("#startpage")[0], "list", "#enable_all_books_list", "To allow the full book listing as the startpage, 'enable full book listing' must be enabled.");
			$("div.startpage div#edit_splashpages_container").remove();
			if($("#startpage")[0].value == "book:"){
				$("#bookselect select:first").attr("selected", "selected")
				$("#bookselect").removeClass("jsnodisplay");
				$("#bookselect select option:last").remove();
			}else{
				$("#bookselect").addClass("jsnodisplay");
				if($("#bookselect select option:last")[0].value != "___null")
					$("#bookselect select").append("<option value=\"___null\"></option>");
				$("#bookselect select")[0].value = "___null";
				if($("#startpage")[0].value == "splash" && $("div.startpage div#edit_splashpages_container").length == 0){
					$("div.startpage").append("<div id=\"edit_splashpages_container\" class=\"jsheightauto\"><p>Loading...</p></div>");
					this.load_splashpages();
				}
			}
		}
	},
	
	load_splashpages : function(){
		$.ajax({
			url:"./?action=edit_splash_images&embed=true", 
			success:function(result){
				$("div#edit_splashpages_container").html($("div#splash_image_container", $(result)));
				$("div.startpage div#edit_splashpages_container *").unbind();
				Customization.init_splashpages();
			}
		});		
	},
	
	init_splashpages : function(){
		$("input#resize_splash_images").bind("click", function(){Customization.toggle_splash_image_resize();});
		$("div.splash_image span.move a").removeAttr("href");
		$("div.splash_image span.moveup a").bind("click", function(){Customization.move_splash_image(this, 'up');});			
		$("div.splash_image span.movedown a").bind("click", function(){Customization.move_splash_image(this, 'down');});			
		$("div.splash_image a.remove_link").removeAttr("href");
		$("div.splash_image a.remove_link").bind("click", function(){Customization.remove_splash_image(this)});
		$("div#add_splashimage_row input.submit").remove();
		$("<a class=\"submit submit2 button\"><span>Upload image</span></a>").appendTo("div#add_splashimage_row").bind("click", Customization.add_splash_image);
		$("<span style=\"top:5px;right:11px;width:150px;height:31px;\" class=\"disabled\"></span>").insertAfter("div#add_splashimage_row .submit");
		$("div#add_splashimage_row input.file").bind("change", function(){Customization.toggle_upload_button();});
		$("input.reset").bind("click", function(){$("div#add_splashimage_row span.disabled").show();});
		tb_init("a.thickbox");
		Admin.display_clear_buttons();
	},
	
	toggle_splashpage_interval : function(){
		if($("#startpage").length > 0){
			if($("#startpage")[0].value == "splash"){
				$("#splashpage_interval").removeClass("jsnodisplay");
			}else{
				$("#splashpage_interval").addClass("jsnodisplay");
				var field = $("#splashpage_interval input.text")[0];
				if(field.value == "") field.value ="5";
			}
		}
	},

	toggle_splash_image_resize : function(){
		if($("input#resize_splash_images").length > 0){
			if($("div#add_splashimage_row input.checkbox").attr("checked")){
				$("span#splash_image_resize_fields").removeClass("jsnodisplay");
			}else{
				$("span#splash_image_resize_fields").addClass("jsnodisplay");				
			}
		}
	},
	
	add_splash_image : function(){
		po = Window.popover({id: "save_form_popover", content: "<div style=\"text-align:center\"><br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/thickbox/loading_animation.gif\" /><br /><br /><br /><span>Uploading splash image...</span></div>", width: 200, height: 130, top: 100, overlay: true});
		var f = $("<form id=\"splashimage_upload\" action=\".\" encType=\"multipart/form-data\" method=\"post\"><input type=\"hidden\" class=\"hidden\" name=\"action\" value=\"edit_splash_images\" /><input type=\"hidden\" class=\"hidden\" name=\"json_response\" id=\"json_response\" value=\"true\" /></form>").appendTo("body");
		$("div#add_splashimage_row input").each(function(){f.append($(this).clone())});
		AIM.submit(f[0], {'onComplete': function(response){
			if(Admin.show_ajax_errors(JSON.parse(response))){
				$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/exclamation.png\" /><br /><br /><br /><span>You have errors in your submission. Please try again.</span>");
			}else{
				$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/checkmark.png\" /><br /><br /><br /><span>Splash image uploaded!</span>");
				Customization.load_splashpages();
			}
			setTimeout("$(po).fadeOut(\"slow\", function(){Window.popover_kill(po);});", 1000);
			setTimeout("$(\"div#aim_container\").remove();", 1);
			setTimeout("$(\"form#splashimage_upload\").remove();", 1);
		}});
		f.submit();
	},

	remove_splash_image : function(el){
		if(confirm("Are you sure you want to delete this splash image? This cannot be undone.")){
			var splash_image_entry = $(el).parents("div.splash_image");
			var splash_image = $("img.splash_thumb", splash_image_entry);
			var thumb_src = splash_image.attr("src");
			if(thumb_src.indexOf("?t=") != -1)
				thumb_src = thumb_src.substring(0,thumb_src.indexOf("?t="));
			var image_name = thumb_src.substring(thumb_src.indexOf("splashimage_"));

			$.ajax({async: true,url: ".?action=remove_splash_image&file=" + image_name, success: function(){splash_image_entry.remove();$("div#splash_images p.note").remove();},error: function (XMLHttpRequest, textStatus, errorThrown){$("img.logo_image").after("Error in deleting splash image '" + image_name + "'.")}});			
		}
	},

	move_splash_image : function(el, direction){
		var splash_image = $(el).parents("div.splash_image");
		$("img.splash_thumb", splash_image).fadeOut(200, function(){Customization.do_move_splash_image(splash_image,direction)});		
	},

	do_move_splash_image : function(splash_image, direction){
		var sibling = null;

		var thumb_src = $("img.splash_thumb",splash_image).attr("src");
		if(thumb_src.indexOf("?t=") != -1)
			thumb_src = thumb_src.substring(0, thumb_src.indexOf("?t="));
		var image_name = thumb_src.substring(thumb_src.indexOf("splashimage_"));

		var index = image_name.substring(image_name.indexOf("_")+1, image_name.indexOf("."));

		if(direction == "up"){
			sibling = splash_image.prev("div.splash_image");
			if(parseInt(index) == 1){	$("span.moveup", sibling).append($("span.moveup a", splash_image)); }
			if(parseInt(index) == $("div.splash_image").length-1){ $("span.movedown", splash_image).append($("span.movedown a", sibling)); }
		}else{
			sibling = splash_image.next("div.splash_image");
			if(parseInt(index) == 0){ $("span.moveup", splash_image).append($("span.moveup a", sibling)); }
			if(parseInt(index) == $("div.splash_image").length-2){ $("span.movedown", sibling).append($("span.movedown a", splash_image)); }
		}

		var sibling_thumb_src = $("img.splash_thumb",sibling).attr("src")
		if(sibling_thumb_src.indexOf("?t=") != -1)
			sibling_thumb_src = sibling_thumb_src.substring(0, sibling_thumb_src.indexOf("?t="));
		var sibling_name = sibling_thumb_src.substring(sibling_thumb_src.indexOf("splashimage_"));

		$.ajax({async: true,url: ".?action=move_splash_image&file=" + image_name + "&new_file=" + sibling_name, success: function(){Customization.reload_splash_images(splash_image,sibling,image_name,sibling_name)},error: function (XMLHttpRequest, textStatus, errorThrown){$("img.logo_image").after("Error in moving splash image '" + splash_image.attr("src") + "'.")}});			
	},

	reload_splash_images : function(splash_image,sibling,image_name,sibling_name){
		image_src = "<?php echo $_SESSION['webpath'] ?>_db/__splash/" + image_name;
		sibling_src = "<?php echo $_SESSION['webpath'] ?>_db/__splash/" + sibling_name;
		var img = $("img.splash_thumb",splash_image);
		var img_sibling = $("img.splash_thumb",sibling);
		img[0].src = "";
		img_sibling[0].src = "";
		img[0].src = image_src + "?t=" + General.generate_random_string(16);
		img_sibling[0].src = sibling_src + "?t=" + General.generate_random_string(16);
		img[0].onload = function(){img.show();};
		img_sibling[0].onload = function(){img_sibling.fadeIn(200);};
	},
	
	toggle_upload_button : function(){
		if($("div#add_splashimage_row input.file")[0].value == ""){
			$("div#add_splashimage_row span.disabled").show();
		}else{
			$("div#add_splashimage_row span.disabled").hide();
		}
	},

	toggle_thumbnail_ratio : function(){
		if($("#gallery_display").length > 0){
			if($("#gallery_display")[0].value == "thumbnails"){
				$("#thumbnail_ratio_select").removeClass("jsnodisplay");
			}else{
				$("#thumbnail_ratio_select").addClass("jsnodisplay");			
			}		
		}
	}
};

Aliases = {
	init : function(){
		$("#add_alias").unbind();
		$("div#aliases_container *").unbind();
		$("#add_alias").removeAttr("href");
		$("#add_alias").bind("click", function(){Aliases.add();});
		
		if($("div#aliases_container a.remove_link").length > 1){
			$("div#aliases_container a.remove_link").removeAttr("href");
			$("div#aliases_container a.remove_link").bind("click", function(){Aliases.remove(this);});		
		}else{
			$("div#aliases_container a.remove_link").remove();			
		}
	},
	
	add : function(){
		if($("div.alias_entry").length == 1){
			$("div.alias_entry span.remove").html("<a class=\"remove_link\"><img src=\"<?php $_SESSION['webpath'] ?>images/admin/delete.png\" alt=\"Remove this alias\"  title=\"Remove this alias\" /></a>");
		}
		var new_entry = $("#alias0").clone(true)
		var new_index = $("div.alias_entry").length;
		new_entry.css("display" ,"none");
		$("#aliases_container").append(new_entry);
		$("input", new_entry).each(function(){this.value = "";});
		new_entry.fadeIn(500);
		new_entry[0].id = "alias" + new_index;
		$("input", new_entry).attr("value", "");
		$(".alias_counter", new_entry)[0].innerHTML = new_index+1;
		$("input", new_entry).each(function(){
			this.name = this.name.replace(/(alias\[)[0-9]+(.*)/,"$1" + new_index + "$2");
		});
		$("label", new_entry).each(function(){
			$(this).attr("for", $(this).attr("for").replace(/(alias\[)[0-9]+(.*)/,"$1" + new_index + "$2"));
		});
		$(".errordiv", new_entry).html("");
		read_form_data();
		Aliases.init();
	},
	
	remove : function(el){
		var entry = $(el).parents("div.alias_entry");
		entry.fadeOut(500, function(){Aliases.post_remove(entry, entry[0].id.substring(5))});
	},
	
	post_remove : function(entry, deleted_index){
		name = $(".text_aliases", entry)[0].value
		entry.remove();
		$("div.alias_entry").each(function(i, el){
			index = $(".alias_counter", el)[0].innerHTML-1;
			if(index > deleted_index){
				$(".alias_counter", el)[0].innerHTML--;
				el.id = "alias" + (index-1);
				$("input.text_aliases", el).each(function(x,txt){
					txt.name = txt.name.replace(/(alias\[)[0-9]+(.*)/,"$1" + (index-1) + "$2");
				})
			}
		});
		read_form_data();
		message = "The alias for " + name + " has been removed. You must click the 'Save settings' button to make this change permanent.";
		alert(message);
		Aliases.init();
	}
};

Books = {
	
};

Navigation = {
	nav_image_format : "<?php echo $_SESSION['cm__gallery']['navigation_image_format']; ?>",
	
	init : function(){
		this.toggle_image_format();
		
		$(".edit_nav_image a").die();
		$("div.navrow span.moveup a").unbind();
		$("div.navrow span.movedown a").unbind();
		$("div.navrow span.remove a.remove_link").unbind();
		$("a.delete_link").unbind();
		$("#add_nav_item").unbind();
		$("input.text_navigation").unbind();
		
		$("#gallery_navigation_style").bind("change", function(){Navigation.toggle_image_format();});		

		$("input.text_navigation").bind("blur", function(){Formpage.validate("config_form");});
		$("select.folder_books").bind("change", function(){Navigation.show_edit_nav_images($(this).parents("div.navrow"), "update");});

		$("div.navrow span.move a").removeAttr("href");
		$("div.navrow span.moveup a").bind("click", function(){Navigation.move(this, 'up');});			
		$("div.navrow span.movedown a").bind("click", function(){Navigation.move(this, 'down');});			
		$("div.navrow:first span.moveup").html("&nbsp;");
		$("div.navrow:last span.movedown").html("&nbsp;");
		$("div.navrow span.remove a.remove_link").removeAttr("href");
		$("div.navrow span.remove a.remove_link").bind("click", function(){Navigation.remove(this);});

		$("div.navrow").each(function(i, row){
			Navigation.check_edit_nav_image(row, $("select", row)[0]);
		});
		$(".edit_nav_image a").live("click", function(){Navigation.show_edit_nav_images($(this).parents("div.navrow"), "toggle")});		

		$("#add_nav_item").bind("click", function(){Navigation.add();});
		$("#add_nav_item").removeAttr("href");			
	},
	
	
	toggle_image_format : function(){
		if($("#gallery_navigation_style").length > 0){
			if($("#gallery_navigation_style")[0].value == "images"){
				$("#nav_image_format_select").removeClass("jsnodisplay");
				$("#navigation_container div.subsection_header span.image").removeClass("jsnodisplay");
				$("#navigation_container div.navrow span.icons span.edit_nav_image").removeClass("jsnodisplay");
				$("#navigation_container div.navrow div.edit_nav_image_row").removeClass("jsnodisplay");
				
			}else{
				$("#nav_image_format_select").addClass("jsnodisplay");			
				$("#navigation_container div.subsection_header span.image").addClass("jsnodisplay");
				$("#navigation_container div.navrow span.icons span.edit_nav_image").addClass("jsnodisplay");
				$("#navigation_container div.navrow div.edit_nav_image_row").addClass("jsnodisplay");
			}
		}
	},
	
	add : function(){
		if($("div.navrow").length == 1){
			$("div.navrow span.remove").html("<a class=\"remove_link\"><img src=\"<?php $_SESSION['webpath'] ?>images/admin/delete.png\" alt=\"Remove this book from navigation menu\" title=\"Remove this book from navigation menu\" /></a>");
		}
		$("div.navrow:last span.movedown").html("<a><img src=\"<?php $_SESSION['webpath'] ?>images/admin/down.png\" alt=\"Move this book down\" title=\"Move this book down\"></a>");
		var new_row = $("#navrow_0").clone(true)
		$("span.moveup", new_row).html("<a><img src=\"<?php $_SESSION['webpath'] ?>images/admin/up.png\" alt=\"Move this book up\" title=\"Move this book up\"></a>");
		new_row.css("display" ,"none");
		$("#navigation_container").append(new_row);
		$("div.edit_nav_image_row", new_row).remove();
		$("input", new_row)[0].value = "";
		$("select", new_row)[0].selectedIndex = 0
		$(".edit_nav_image", new_row).html("<a><img src=\"<?php $_SESSION['webpath'] ?>images/admin/add_image.png\" alt=\"Add Navigation Image\" title=\"Add  Navigation Image\" /></a>");
		new_row.fadeIn(500);
		new_row[0].id = "navrow_" + ($(".navrow").length-1);
		var nav_item = $("input", new_row)[0];
		var folder = $("select", new_row)[0];
		nav_item.name = nav_item.name.replace(/(nav_item\[)[0-9]+(.*)/,"$1" + ($(".navrow").length-1) + "$2");
		folder.name = folder.name.replace(/(nav_item\[)[0-9]+(.*)/,"$1" + ($(".navrow").length-1) + "$2");
		$(".errordiv", new_row).html("");
		read_form_data();
		Navigation.init();
	},
	
	move : function(el, direction){
		var row = $(el).parents("div.navrow");
		row.fadeOut(200, function(){Navigation.do_move(row,direction)});
	},
	
	do_move : function(row, direction){
		var sibling = null;
		var row_id = row.attr("id");
		var row_index = parseInt(row_id.substring(row_id.indexOf("_")+1));
		if(direction == "up"){
			sibling = row.prev("div.navrow");
			row.insertBefore(sibling)
			new_index = row_index-1;
			if(row_index == 1){	$("span.moveup", sibling).append($("span.moveup a", row)); }
			if(row_index == $("div.navrow").length-1){ $("span.movedown", row).append($("span.movedown a", sibling)); }
		}else{
			sibling = row.next("div.navrow");
			row.insertAfter(sibling)
			new_index = row_index+1;
			if(row_index == 0){ $("span.moveup", row).append($("span.moveup a", sibling)); }
			if(row_index == $("div.navrow").length-2){ $("span.movedown", sibling).append($("span.movedown a", row)); }

		}
		row.attr("id", "navrow_" + new_index);
		sibling.attr("id", "navrow_" + row_index)
		$("input.text_navigation", row).attr("name", "nav_item[" + new_index + "][title]")
		$("input.text_navigation", sibling).attr("name", "nav_item[" + row_index + "][title]")
		$("select", row).attr("name", "nav_item[" + new_index + "][folder]")
		$("select", sibling).attr("name", "nav_item[" + row_index + "][folder]")
		row.fadeIn(200);
	},
	
	remove : function(el){
		if($("div.navrow").length > 1){
			var row = $(el).parents("div.navrow");
			row.fadeOut(500, function(){Navigation.post_remove(row, row[0].id.substring(row[0].id.indexOf("_")+1))});
		}
	},
	
	post_remove : function(row, deleted_index){
		name = $(".text_navigation", row)[0].value;
		row.remove();
		$("div.navrow").each(function(i, el){
			index = el.id.substring(el.id.indexOf("_")+1);
			if(index > deleted_index){
				el.id = "navrow_" + (index-1);
				nav_item = $(".text_navigation", el)[0];
				folder = $("select", el)[0];
				nav_item.name = nav_item.name.replace(/(nav_item\[)[0-9]+(.*)/,"$1" + (index-1) + "$2");
				folder.name = folder.name.replace(/(nav_item\[)[0-9]+(.*)/,"$1" + (index-1) + "$2");
			}
		});
		if($("div.navrow").length == 1){
			$("div.navrow span.remove a").remove();
		}
		Navigation.init();
		message = "The book " + name + " has been removed from the navigation. You must click the 'Save settings' button to make this change permanent.";
		read_form_data();
		alert(message);
	},
	
	show_edit_nav_images : function(row, mode){
		index = $("div.navrow").index(row);
		var folder_title = $(".folder_books", row)[0].value;
		if(folder_title!= ""){
			if($("div.edit_nav_image_row", row).length == 0 || mode == "update"){
				if(mode=="toggle")
					$(row).append("<div class=\"edit_nav_image_row\" id=\"edit_nav_image_row_" + index + "\"><p>Loading...</p></div>");
				$.ajax({
					url:"./?action=edit_nav_images&book=" + folder_title.replace(/ /g, "+") + "&embed=true", 
					success:function(result){
						$("div.edit_nav_image_row", row).html($("div.edit_nav_image_container", $(result)));
						$("div.container *", row).unbind();
						Navigation.init_navimages(row);
					}
				});
			}else{
				$("div.edit_nav_image_row", row).remove();
			}
		}else{
			if($("div.edit_nav_image_row", row).length > 0)
				$("div.edit_nav_image_row", row).remove();
			else
				alert("You must first select a folder for this Book before you can add or edit the navigation image.")			
		}
	},
	
	init_navimages : function(row){
		Admin.display_clear_buttons();
		$("a.delete_link", row).removeAttr("href");
		$("a.delete_link", row).bind("click", function(){Navigation.delete_nav_image($(this).parents("span.img"))});
		$("div.image input.file", row).bind("change", function(){Navigation.toggle_upload_button(row);});
		$("input.clear_button", row).bind("click", function(){Navigation.toggle_upload_button(row);});
		$("input.submit", row).remove();
		if($("a.button", row).length == 0){
			$("div.submitrow", row).append("<a class=\"submit submit2 button\"><span>Upload image(s)</span></a>").bind("click", function(){Navigation.upload_nav_images($(this).parents("div.edit_nav_image_row"))});
			$("div.submitrow .submit", row).after("<span style=\"top:3px;right:6px;width:160px;height:25px;\" class=\"disabled\"></span>");
		}
	},
	
	upload_nav_images : function(row){
		po = Window.popover({id: "save_form_popover", content: "<div style=\"text-align:center\"><br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/thickbox/loading_animation.gif\" /><br /><br /><br /><span>Uploading navigtation image(s)...</span></div>", width: 200, height: 130, top: 100, overlay: true});
		var f = $("<form id=\"navigationimage_upload\" action=\".\" encType=\"multipart/form-data\" method=\"post\"><input type=\"hidden\" class=\"hidden\" name=\"action\" value=\"upload_nav_images\" /><input type=\"hidden\" class=\"hidden\" name=\"json_response\" id=\"json_response\" value=\"true\" /></form>").appendTo("body");
		$("div.image input.file", row).each(function(){f.append($(this).clone())});
		AIM.submit(f[0], {'onComplete': function(response){
			if(Admin.show_ajax_errors(JSON.parse(response))){
				$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/exclamation.png\" /><br /><br /><br /><span>You have errors in your submission. Please try again.</span>");
			}else{
				$("div", po).html("<br /><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/checkmark.png\" /><br /><br /><br /><span>Navigation image(s) uploaded!</span>");
				Customization.load_splashpages();
			}
			setTimeout("$(po).fadeOut(\"slow\", function(){Window.popover_kill(po);});", 1000);
			setTimeout("$(\"div#aim_container\").remove();", 1);
			setTimeout("$(\"form#navigationimage_upload\").remove();", 1);
		}});
		f.submit();		
	},
	
	delete_nav_image : function(el){
		var folder = $("select.folder_books", el.parents(".navrow")).val();
		$.ajax({
			url:"./?action=delete_nav_image&book=" + folder.replace(/ /g, "+") + "&embed=true", 
			dataType:"json",
			success:function(response){
				jsonResponse = JSON.parse(response);
				if(jsonResponse.status == "saved"){
					$("img.nav_image", el).remove();
					$("a.delete_link", el).remove();
				}
			}
		});
	},
	
	check_edit_nav_image : function(navrow, el){
		if(el.value != ""){
			var nav_image = "/images/nav/" + el.value.toLowerCase();
			$.ajax({async: true,url: nav_image + "." + Navigation.nav_image_format, success: function(){$(".edit_nav_image", navrow).html("<a><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/edit_image.png\" alt=\"Edit Navigation Image\" title=\"Edit Navigation Image\" /></a>");},error: function (XMLHttpRequest, textStatus, errorThrown){$(".edit_nav_image", navrow).html("<a><img src=\"<?php echo $_SESSION['webpath'] ?>images/admin/add_image.png\" alt=\"Add Navigation Image\" title=\"Add Navigation Image\" /></a>");}});		
		}
	},
	
	toggle_upload_button : function(row){
		var inputs = $("input.file", row);
		inputs.each(function(){
			if(this.value != ""){
				$("div.submitrow span.disabled", row).hide();
				return false;
			}else{
				$("div.submitrow span.disabled", row).show();
			}
		});		
	}
};

/**
*
*  AJAX IFRAME METHOD (AIM)
*  http://www.webtoolkit.info/
*
**/
 
AIM = {
 
	frame : function(c){
		var n = 'f' + Math.floor(Math.random() * 99999);
		var d = document.createElement('div');
		d.id = "aim_container";
		d.innerHTML = '<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="AIM.loaded(\''+n+'\')"></iframe>';
		document.body.appendChild(d);
 
		var i = document.getElementById(n);
		if (c && typeof(c.onComplete) == 'function') {
			i.onComplete = c.onComplete;
		}
 
		return n;
	},
 
	form : function(f, name) {
		f.setAttribute('target', name);
	},
 
	submit : function(f, c) {
		AIM.form(f, AIM.frame(c));
		if (c && typeof(c.onStart) == 'function') {
			return c.onStart();
		} else {
			return true;
		}
	},
	 
	loaded : function(id) {
		var i = document.getElementById(id);
		if (i.contentDocument) {
			var d = i.contentDocument;
		} else if (i.contentWindow) {
			var d = i.contentWindow.document;
		} else {
			var d = window.frames[id].document;
		}
		if (d.location.href == "about:blank") {
			return;
		}
 
		if (typeof(i.onComplete) == 'function') {
			i.onComplete(d.body.innerHTML);
		}
	}
 
}