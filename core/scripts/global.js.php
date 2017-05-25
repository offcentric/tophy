var General = {
	generate_random_string : function(string_length){
	  var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	  var str = "";
	  for(x=0; x < string_length; x++){
	    i = Math.floor(Math.random() * 62);
	    str += chars.charAt(i);
	  }
	  return str;
	},
	
	getCleanURL : function(url){
		if(url.indexOf("http://") != -1)
			url = url.substring(7);
		if(url.indexOf("#") != -1)
			url = url.substring(0, url.indexOf("#"));
		if(url.indexOf("?") != -1)
			url = url.substring(0, url.indexOf("?"));
		return url;
	}
};

var Window = {
	location : "no",
	menubar : "no",
	resizable : "no",
	scrollbars : "no",
	status : "no",
	toolbar : "no",
	
	popup : function(url, win_name, width, height){
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = "width="+width+",height="+height+",top="+top+",left="+left;
		params += ",directories=no,location=" + Window.location + ",menubar=" + Window.menubar + ",resizable=" + Window.resizable + ",scrollbars=" + Window.scrollbars + ",status=" + Window.status + ",toolbar=" + Window.toolbar;
	
		new_win = window.open(url, win_name, params);
		if (window.focus) {new_win.focus()}
		return new_win;
	},

	popover : function(params){
		var p = (params.parent == undefined)? "body" : params.parent;
		var c = (params.classname == undefined)? "popover" : params.classname;
		var w = (params.width == undefined)? "" : params.width + (((params.width + "").indexOf("px") == -1)? "px" : "");
		var h = (params.height == undefined)? "" : params.height + (((params.height + "").indexOf("px") == -1)? "px" : "");
		switch(params.effect){
			case "fade":
				e = "fadeIn";
				break;
			case "slide":
				e = "slideDown";
				break;
			case undefined:
				e = "";
				break;
			default:
				e = "fadeIn";			
		}
		
		if(params.parent == undefined){
			var l = (params.left == undefined)? ((Window.get_w()  - w.substring(0,w.indexOf("px")))/2 + "px") : params.left + (((params.left + "").indexOf("px") == -1)? "px" : "");
			var t = (params.top == undefined)? ((Window.get_h()  - h.substring(0,h.indexOf("px")))/2 + "px") : params.top + (((params.top + "").indexOf("px") == -1)? "px" : "");
			posStyle = "position:fixed;z-index:1000;top:" + t + ";left:" + l + ";";
		}else{
			posStyle = "";
		}
		var out = "<div id=\"" + params.id + "\" class=\"" + c + "\" style=\"display:none;width:" + w + ";height:" + h + ";" + posStyle + "\"></div>";
		var po = $(out);
		$(p).prepend(po);
		if(params.content != undefined){
			po.html(params.content);
			if(e != "") eval("po." + e + "(300)");
			else po.show();
		}else if(params.url != undefined){
			$.ajax({
				url:params.url + "?embed=true",
				success: function(result){
					po.html(result);
					if(e != "") eval("po." + e + "(300)");
					else po.show();
				}
			});
		}
		if(params.overlay){
			var overlay = "<div id=\"overlay-" + params.id + "\" class=\"overlay\" style=\"width:" + Window.get_w() + "px;height:" + Window.get_h() + "px;position:fixed;top:0;left:0;z-index:999;background-color:#666;filter:alpha(opacity=50);-moz-opacity:0.5;opacity: 0.5;\">&nbsp;</div>"
			$("body").append(overlay);
		}
		return po[0];
	},
	
	popover_kill : function(params){
		var po = $("#" + params.id); 
		switch(params.effect){
			case "fade":
				e = "fadeOut";
				break;
			case "slide":
				e = "slideUp";
				break;
			case undefined:
				e = "";
				break;
			default:
				e = "fadeOut";			
		}
		if(e != "") eval("po." + e + "(300, function(){po.remove();});");
		else $(po).remove();
		$("#overlay-" + params.id).remove();
	},
	
	toggle_popover : function(params){
		if($("#" + params.id).length == 0){
			this.popover(params);
		}else{
			this.popover_kill(params)
		}
	},
	
	get_w : function(){
		return window.innerWidth != null? window.innerWidth : document.documentElement && document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body != null ? document.body.clientWidth : null;
	},
	
	get_h: function(){
		return  window.innerHeight != null? window.innerHeight : document.documentElement && document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body != null? document.body.clientHeight : null;
	}

};

/* THIS RULES, THIS ENABLES ROLLOVER IMAGES JUST BY SPECIFYING A CLASSNAME */
var Rollover = {
	init : function(){
		$("img.rollover").each(function(i,el){
			var on_image = el.src.substring(0, el.src.length-4) + "_on" + el.src.substring(el.src.length-4);
			if(menuItem == "" || unescape(el.parentNode.href).indexOf(menuItem)==-1 || el.src.indexOf("<?php echo $_SESSION['webpath'] ?>images/paging/back_" + galleryDisplay + ".gif")!=-1 || el.src.indexOf("<?php echo $_SESSION['webpath'] ?>images/paging/next_" + galleryDisplay + ".gif")!=-1){
		        $.ajax({async: true,url: on_image, success: function(){$(el).bind("mouseover", function(){Rollover.execute(el,'on')});$(el).bind("mouseout", function(){Rollover.execute(el,'off')});}});
			}else{
				//Rollover.execute(el,'on')
			}
		});
	},
	
	execute : function(img,state){
		if(state=="on"){
			img.src = img.src.substring(0,img.src.length-4)+'_on'+img.src.substring(img.src.length-4)
		}else{
			img.src = img.src.substring(0,img.src.length-7)+img.src.substring(img.src.length-4)		
		}
	}
};

var Page = {
	control_panel : 0,
	
	init : function(){
		this.remove_focus();
		this.init_control_panel();
		this.init_help();
	},
	
	// removes the dotted boxes around a hyperlink or certain form elements when clicked in Firefox
	remove_focus : function(){
		$("a").each(function(i,n){
			$(n).bind("mouseup", function(){if(this.blur)this.blur();});
		});
		$("input").each(function(i,n){
			if(n.type == "image" || n.type == "radio" || n.type == "submit")
				$(n).bind("mouseup", function(){if(this.blur)this.blur();});
		});
	},
	
	init_control_panel : function(){
		if($("#control_panel_container").length>0){
			var click_image = document.createElement("img");
			click_image.src = "<?php echo $_SESSION['webpath'] ?>images/control_panel/slideout.gif?t=<?php echo $_SESSION['theme'] ?>";
			click_image.setAttribute("title", "expand control panel")
			$("#control_panel_clickzone").append(click_image);		
			$("#control_panel").css("margin-left", -$("#control_panel")[0].offsetWidth+"px");
			$("#control_panel_clickzone").bind("click", Page.toggle_control_panel);
			$("#control_panel_container").removeClass("jshidden");
		}
	},
	
	toggle_control_panel : function(){
		if(!this.control_panel){
			Page.slide_control_panel('out');
			$("#control_panel_container").css("z-index", "3");
			this.control_panel = 1;
		}else{
			Page.slide_control_panel('in');
			this.control_panel = 0;
		}
	},
	
	slide_control_panel : function(state){
		if($("#control_panel")[0].offsetLeft < -($("#control_panel")[0].offsetWidth-20) || $("#control_panel")[0].offsetLeft > -50){
			timeout = 25;
		}else{
			timeout = 1;
		}
		if(state=="out"){
			if($("#control_panel")[0].offsetLeft<0){
				if($("#control_panel")[0].offsetLeft<-10){
					$("#control_panel").css("margin-left", ($("#control_panel")[0].offsetLeft+10)+"px");				
				}else{
					$("#control_panel").css("margin-left", "0px");					
				}
			}
			if($("#control_panel")[0].offsetLeft<0){
				cp_slide = setTimeout("Page.slide_control_panel('out')", timeout);
			}else{
				var click_image = document.createElement("img");
				click_image.src = "<?php echo $_SESSION['webpath'] ?>images/control_panel/slidein.gif";
				click_image.setAttribute("title", "collapse control panel")
				$("#control_panel_clickzone img").replaceWith(click_image);				
			}
		}else{
			if($("#control_panel")[0].offsetLeft>-$("#control_panel")[0].offsetWidth){
				if($("#control_panel")[0].offsetWidth + $("#control_panel")[0].offsetLeft > 10){
					$("#control_panel").css("margin-left", ($("#control_panel")[0].offsetLeft-10)+"px");				
				}else{
					$("#control_panel").css("margin-left", -($("#control_panel")[0].offsetWidth)+"px");					
				}
			}
			if($("#control_panel")[0].offsetLeft>-$("#control_panel")[0].offsetWidth){
				cp_slide = setTimeout("Page.slide_control_panel('in')", timeout);
			}else{
				var click_image = document.createElement("img");
				click_image.src = "<?php echo $_SESSION['webpath'] ?>images/control_panel/slideout.gif?t=<?php echo $_SESSION['theme'] ?>";
				click_image.setAttribute("title", "expand control panel")
				$("#control_panel_clickzone img").replaceWith(click_image);
				$("#control_panel_container").css("z-index", "0");
			}
		}
	},
	
	init_help : function(){
		$("a", $("div.helpicon")).unbind("click");
		$("div.helpicon").each(function(){
			$("a", this).removeAttr("target");
			$("a", this).bind("click", function(){Window.toggle_popover({id: this.id+"_content", url: $(this).attr("href"), classname: "help", parent: $(this).parents("div.formrow"), effect: "slide"});return false;});
		});
	}
}

/****** FORM VALIDATION ******/
var Formpage = {
	form_fields: new Array(),
	form: new Array(),
	re_url:	  /^(http(s?)\:\/\/|~\/|\/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((\/?\w+\/)+|\/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?$/i,
	re_filepath_win:	  /^(([a-zA-Z]:)|(\\{2}\w+)\$?)(\\(\w[\w ]*))+\.([a-zA-Z0-9]{3})$/i,
	re_filepath_nix:	  /^(\/\w[\w ]*)+(\.([a-zA-Z0-9]{1,}))?$/i,
	re_dirpath_win:	  /^(([a-zA-Z]:)|(\\{2}\w+)\$?)(\\(\w[\w \.\-]*))+\\$/i,
	re_dirpath_nix:	  /^(\/\w[\w \.\-]*)*\/$/i,
	re_username: /^[^ ]{4,}$/i,
	re_password: /^[^ ]{6,}$/i,
	re_email: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i,
	re_date: /^(?:(31)(\\D)(0?[13578]|1[02])\\2|(29|30)(\\D)(0?[13-9]|1[0-2])\\5|(0?[1-9]|1\\d|2[0-8])(\\D)(0?[1-9]|1[0-2])\\8)((?:1[6-9]|[2-9]\\d)?\\d{2})$|^(29)(\\D)(0?2)\\12((?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:16|[2468][048]|[3579][26])00)$/i,
	re_numeric: /^[0-9\,\.]*$/i,
	re_hex: /^[0-9a-fA-F]{6}$/i,
	re_postcode_nl: /^[0-9]{4}[ ]?[A-Za-z]{2}$/i,
	re_phonenumber: /^[0-9\(\)\+ ]{10,}$/i,
	errs:		0,
	err_messages: new Array(),
	first_error: null,
	plural_suffix : "s",
	splashpage_iframe : "",
	ajax_submit : true,
	
	init: function(){
		var forms = $("form.validate");
		if(forms.length > 0){
			forms.each(function(i,f){
				$("input.submit", f).each(function(i, oldSubmit){
					var submitDiv = oldSubmit.parentNode;
					$(oldSubmit).remove();
					var newSubmit = $(document.createElement("input"));
					newSubmit.attr("type", "button");
 					newSubmit.attr("value", $(oldSubmit).attr("value"));
					<?php if(@$_GET["pagename"] == "admin"){ ?>
 					newSubmit.attr("value", "Save settings");
					<?php }elseif(@$_GET["pagename"] == "admin_set_password"){ ?>
	 					newSubmit.attr("value", "Save password");
					<?php } ?>
					newSubmit.addClass("submit button")
					newSubmit.bind("click", function(){Formpage.send(f.getAttribute("id"), $(f).attr("action"), this);});
					submitDiv.appendChild(newSubmit[0]);					
				});
			
				Formpage.form[f.getAttribute("id")] = $("#" + f.getAttribute("id"))[0];
			});
			this.get_error_messages();			
		}
	},

	get_error_messages: function(){
		this.err_messages['too_short'] = "#field# must be at least #minlength# characters.";
		this.err_messages['too_long'] = "#field# can be no longer than #maxlength# characters in length.";
		this.err_messages['field_required'] = "You have not entered a #field#.";
		this.err_messages['username_invalid'] = "You have entered an invalid username. A valid username must be at least 4 characters in length, and cannot contain spaces.";
		this.err_messages['password_invalid'] = "You have entered an invalid password. A valid password must be at least 4 characters in length, and cannot contain spaces.";
		this.err_messages['email_invalid'] = "You have entered an invalid email address.";
		this.err_messages['url_invalid'] = "You have entered an invalid URL (web address).";
		this.err_messages['filepath_invalid'] = "You have entered an invalid filepath.";
		this.err_messages['dirpath_win_invalid'] = "You have entered an invalid directory path. Make sure you don't forget the trailing backslash.";
		this.err_messages['dirpath_nix_invalid'] = "You have entered an invalid directory path. Make sure you don't forget the trailing slash.";
		this.err_messages['email_missing'] = "You have not filled in an email address.";
		this.err_messages['not_selected'] = "You have not selected a #field#.";
		this.err_messages['too_few_selected'] = "You have not selected enough options. You must select at least #amount# option#plural#.";
		this.err_messages['too_many_selected'] = "You have selected too many options. You choose at most #amount# option#plural#.";
		this.err_messages['numeric_invalid'] = "#field# must be a number.";
		this.err_messages['hex_invalid'] = "The value for #field# is invalid. It must be a hexadecimal value of 6 characters long (e.g 53AE91).";
		this.err_messages['postcode_nl_invalid'] = "You have entered an invalid postal code.";
		this.err_messages['phonenumber_invalid'] = "You have entered an invalid telephone number.";
	},
	
	send: function(form_id, action, button){
		this.get_values_from_iframe(["resize_splash_images","splash_image_resize_w","splash_image_resize_h"]);

		if(this.validate(form_id)){
			var form = $("#"+form_id);
			form.submit();
			button.blur();
/*				form_data = this.get_form_data_as_json(form[0]);
				$.ajax({type:"POST", url:form.attr("action"), data:form_data, success: function(){
					Formpage.send_files(form,{"onComplete": function(){Window.popover_kill(po);}}, po);
				}});
*/
		}
	},
		
	validate: function(form_id){
		// example of the javascript array to be generated on the form page by the application
		// Formpage.form_fields["myFormId"][Formpage.form_fields.length] = {name: "firstname", label: 'First Name', type: 'text', data_type: 'text', min_occurs: 1, max_occurs: 1};
		this.errs = 0;
		// first clear all errordivs
		$(".errordiv", $("#"+form_id)).each(function(){
			$(this).html("");
			$(this).parent().addClass("redborder");
		});
		$(this.form_fields[form_id]).each(function(i,n){
			n["has_error"] = false;
			
			el = Formpage.form[form_id].elements[n.name];

			switch(n.type.toLowerCase()){
				case 'text':
				case 'textarea':
				case 'password':
					Formpage.toggle_error(n, el, (n.min_occurs > 0 && el.value == ""), Formpage.err_messages['field_required'].replace("#field#", n.label));
					if(el.value != ""){
						if(n.min_length != undefined) Formpage.toggle_error(n, el, (el.value.length < n.min_length), Formpage.err_messages['too_short'].replace("#field#", n.label).replace("#minlength#", n.min_length));
						if(n.max_length != undefined) Formpage.toggle_error(n, el, (el.value.length > n.max_length), Formpage.err_messages['too_long'].replace("#field#", n.label).replace("#maxlength#", n.max_length));
						switch(n.data_type.toLowerCase()){
							case 'username':
								Formpage.toggle_error(n, el, !Formpage.re_username.test(el.value), Formpage.err_messages['username_invalid'].replace("#field#", n.label));					
								break;
							case 'password':
								Formpage.toggle_error(n, el, !Formpage.re_password.test(el.value), Formpage.err_messages['password_invalid'].replace("#field#", n.label));					
								break;
							case 'email':
								Formpage.toggle_error(n, el, !Formpage.re_email.test(el.value), Formpage.err_messages['email_invalid'].replace("#field#", n.label));					
								break;
							case 'URL':
							case 'url':
								Formpage.toggle_error(n, el, !Formpage.re_url.test(el.value), Formpage.err_messages['url_invalid'].replace("#field#", n.label));					
								break;
							case 'filepath_win':
								Formpage.toggle_error(n, el, !Formpage.re_filepath_win.test(el.value), Formpage.err_messages['filepath_invalid'].replace("#field#", n.label));					
								break;
							case 'filepath_nix':
								Formpage.toggle_error(n, el, !Formpage.re_filepath_nix.test(el.value), Formpage.err_messages['filepath_invalid'].replace("#field#", n.label));					
								break;
							case 'dirpath_win':
								Formpage.toggle_error(n, el, !Formpage.re_dirpath.test(el.value), Formpage.err_messages['dirpath_win_invalid'].replace("#field#", n.label));					
								break;
							case 'dirpath_nix':
								Formpage.toggle_error(n, el, !Formpage.re_dirpath_nix.test(el.value), Formpage.err_messages['dirpath_nix_invalid'].replace("#field#", n.label));					
								break;
							case 'number':
								Formpage.toggle_error(n, el, !Formpage.re_numeric.test(el.value), Formpage.err_messages['numeric_invalid'].replace("#field#", n.label));					
								break;
							case 'hex':
								Formpage.toggle_error(n, el, !Formpage.re_hex.test(el.value), Formpage.err_messages['hex_invalid'].replace("#field#", n.label));					
								break;
							case 'postcode_nl':
								Formpage.toggle_error(n, el, !Formpage.re_postcode_nl.test(el.value), Formpage.err_messages['postcode_nl_invalid'].replace("#field#", n.label));					
								break;
							case 'phonenumber':
								Formpage.toggle_error(n, el, !Formpage.re_phonenumber.test(el.value), Formpage.err_messages['phonenumber_invalid'].replace("#field#", n.label));					
								break;
						}
					}
				break;
				case 'radio':
					checked = false;
					$(el).each(function(i,x){ if(x.checked){ checked = true; return false;}});
					Formpage.toggle_error(n, el[0], (n.min_occurs > 0 && !checked), Formpage.err_messages['not_selected'].replace("#field#", n.label));
				break;
				case 'checkbox':
					checked = 0;
					if(el[0]==undefined) el = new Array(el);
					$(el).each(function(i,x){;if(x.checked)checked++;});
					Formpage.toggle_error(n, el[0], (n.min_occurs > checked), Formpage.err_messages['too_few_selected'].replace("#field#", n.label).replace("#amount#", n.min_occurs).replace("#plural#", n.min_occurs==1?"":Formpage.plural_suffix));
					if(n.min_occurs <= checked)
					Formpage.toggle_error(n, el[0], (n.max_occurs < checked), Formpage.err_messages['too_many_selected'].replace("#field#", n.label).replace("#amount#", n.max_occurs).replace("#plural#", n.max_occurs==1?"":Formpage.plural_suffix));		
				break;
				case 'select':
					selected = 0;
					if($(el).attr("multiple") == "false" || !$(el).attr("multiple")){
						Formpage.toggle_error(n, el, (el.value == ""), Formpage.err_messages['not_selected'].replace("#field#", n.label));						
					}else{
						$(el.childNodes).each(function(i,x){ if(x.selected && x.value != '') selected++;});
						Formpage.toggle_error(n, el, (n.min_occurs > selected), Formpage.err_messages['too_few_selected'].replace("#field#", n.label).replace("#amount#", n.min_occurs).replace("#plural#", n.min_occurs==1?"":Formpage.plural_suffix));
						if(n.min_occurs <= selected)
						Formpage.toggle_error(n, el, (n.max_occurs < selected), Formpage.err_messages['too_many_selected'].replace("#field#", n.label).replace("#amount#", n.max_occurs).replace("#plural#", n.max_occurs==1?"":Formpage.plural_suffix))
					}
				break;
			}
		});
		if(this.errs > 0){
			var tabcontent = $(this.first_error).parents(".tabcontent");
			if(tabcontent.length > 0){
				if(tabcontent[0].className.indexOf("jsnodisplay") != -1 || tabcontent[0].className.indexOf("jshidden") != -1)
					OmniTab.show(OmniTab.getTabObjectById(tabcontent.attr("id")));
			}
			document.documentElement.scrollTop = $(Formpage.first_error).offset().top -75;
		}
		return (this.errs==0);
	},
	
	get_container_row: function(el){
		while((el = el.parentNode) != null){
			if(el.className.indexOf("formrow") != -1 || el.className.indexOf("row") != -1 || el.className.indexOf("item") != -1) break;
		}
		if(el.className.indexOf("formrow") != -1 || el.className.indexOf("row") != -1 || el.className.indexOf("item") != -1) return el; else return null;
	}, 
	
	toggle_error: function(item, el, has_error, msg){
		if(item["has_error"] != true){
			row = Formpage.get_container_row(el);
			if(row != null){
				$(row).attr("name", el.name + "_row");
				error_cell = $($(".errordiv", row)[0]);
//				error_cell.html("");
				if(has_error){
					if(error_cell.html().indexOf(msg) < 0){
						error_cell.append("<div>" + msg + "</div>");
					}
					if(this.errs==0)this.first_error = row;
					this.errs++;
					item["has_error"] = true;
				}
			}
		}
	},
	
	get_values_from_iframe : function(element_names){
		$("iframe").each(function(i,f){
			iframe_content = f.contentWindow.document.body;		
			jQuery.each(element_names, function(){
				hidden_element = $("input[name='"+ this +"']");
				if(hidden_element.length >0){
					source_element = $("input[name='"+ this +"'],select[name='"+ this +"']", iframe_content);
					if(source_element.length >0){
						if(source_element.attr("type") == "checkbox"){
							hidden_element[0].value=(source_element[0].checked?"on":"off");
						}else if(source_element.attr("type") == "radio"){
							hidden_element[0].value = (source_element[0].selected?"selected":"");
						}else{
							hidden_element[0].value = source_element[0].value;
						}
					}
				}
			});
		});
	},
	
	get_form_data_as_json : function(form, exceptions){
		var data = "";
		$("input[name], select[name], textarea[name]", form).each(function(){
			if($(this).attr("multiple") == true || $(this).attr("multiple") == "true" || $(this).attr("multiple") == "multiple"){
				data += "\"" + this.name + "[]\": [";
				multi_data = "";
				$.each(this.options, function(){
					if(this.selected){
						multi_data += "\"" +  this.value.replace(/"/g, "\\\"") + "\",";
					}
				});
				if(multi_data != ""){multi_data = multi_data.substring(0, multi_data.length-1)}
				data += multi_data + "],";
			}else{
				data += "\"" + this.name + "\": \"" + this.value.replace(/"/g, "\\\"") + "\", ";
			}
		});
		// strip trailing comma
		data = data.substring(0, data.length-2)
		return JSON.parse("{" + data + "}");
	},
	
	send_files : function(form, callback, po){
		var files = $("#"+form.id + " input:file");
		var out = "";
		if(files.length>0){
			if (callback && typeof(callback.onStart) == "function")
				callback.onStart();
			$("body").append("<iframe id=\"file_upload_iframe\" name=\"file_upload_iframe\" fromeborder=\"0\" height=\"0\" width=\"0\" style=\"display:none;\" src=\"about:blank\"></iframe>");
			var old_target = $(form).attr("target");
			$(form).attr("target", "file_upload_iframe");
			files.each(function(i, el){
				$("span", po).html("Saving " + el.name.replace(/_/g, " "));
				out = "<iframe name=\"" & el.name & "\" fromeborder=\"0\" height=\"0\" width=\"0\" style=\"display:none;width:0;height:0;position:absolute;left:0;top:0;\"name=\"edit_nav_image_" + index + "\" src=\"about:blank\"></iframe>";
			});
		}
	}
}

var Menu = {
	navlist : null,
	header : null,
	displaystate : null,
	
	toggle : function(forced){
		if(forced == 'close'){
			Menu.navlist.hide();
			Menu.displaystate = "closed";		
		}else{
			if(Menu.displaystate == "closed"){
				Menu.navlist.show();
				Menu.displaystate = "open";
			}else{
				Menu.navlist.hide();
				Menu.displaystate = "closed";			
			}
		}
	},
	
	init : function(){
		Menu.displaystate = "closed";
		Menu.navlist = $("ul#nav");
		Menu.header = $("div#nav_container h3.nav_header a");
		if(Menu.navlist.hasClass("dropdown")){
			Menu.navlist.hide();
		}
		$("body").bind("click", function(){Menu.toggle('close');});
		Menu.header.bind("click", function(){Menu.toggle();return false;});
	}
};


var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "an unknown version";
		this.OS = this.searchString(this.dataOS) || "an unknown OS";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;	
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	dataBrowser: [
		{string: navigator.userAgent,subString: "Chrome",identity: "Chrome"},
		{string: navigator.vendor,subString: "Apple",identity: "Safari"},
		{prop: window.opera,identity: "Opera"},
		{string: navigator.vendor,subString: "KDE",identity: "Konqueror"},
		{string: navigator.userAgent,subString: "Firefox",identity: "Firefox"},
		{string: navigator.vendor,subString: "Camino",identity: "Camino"},
		{/* for newer Netscapes (6+) */ string: navigator.userAgent,subString: "Netscape",identity: "Netscape"},
		{string: navigator.userAgent,subString: "MSIE",identity: "Explorer",versionSearch: "MSIE"},
		{string: navigator.userAgent,subString: "Gecko",identity: "Mozilla",versionSearch: "rv"},
		{ /* for older Netscapes (4-) */ string: navigator.userAgent,subString: "Mozilla",identity: "Netscape",versionSearch: "Mozilla"}
	],
	dataOS: [
		{string: navigator.platform,subString: "Win",identity: "Windows"},
		{string: navigator.platform,subString: "Mac",identity: "Mac"},
		{string: navigator.platform,subString: "Linux",identity: "Linux"}
	],
	supported: function(){
	/* 	this statement must be kept up to date as the browser requirements change. At the moment, supported browsers are:
		Internet Explorer 5.5 and newer (Windows), Firefox 1 and newer (Mac & Windows), Safari all versions (Mac & Windows)
	*/
		return 	(this.OS == "Windows" && this.browser == "Explorer" && parseFloat(this.version) >= 5.5) ||
				(this.browser == "Firefox" && parseInt(this.version) >= 1) ||
				(this.browser == "Safari") || (this.browser == "Chrome")
	}
};

function init(){
	Formpage.init();
	Page.init();
	Rollover.init();
	Menu.init();
}

function init_post(){
	
}

BrowserDetect.init();

if(BrowserDetect.supported()){
	/* fire init function when DOM is loaded */
	$(document).ready(init);
	/* only call init_post function if something must be initialized *after* images are loaded */
	window.onload = init_post;
}
