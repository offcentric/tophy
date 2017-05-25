/* OMNI TABS */
var OmniTab = {
	tabTree : new Array(), maxLevels : 2, currentPage : General.getCleanURL(window.location.href),
	
	// initalize event listeners
	init : function(){
		var tabFamily = new Array();
		$(".tabcontainer_level0", $("body")[0]).each(function(i, tf){
			OmniTab.tabTree[i] = OmniTab.initTabs(tf, 0);
		});
//		OmniTab.getTabObjectById("books")
	},
	
	initTabs : function(tf, _currentLevel){
		var tabFamily = new Array()
		tabFamily['id'] = tf.id;
		tabFamily['selectedTab'] = "";
		tabFamily['tabs'] = new Array();
		
		var tabs = $("ul.tabs_level" + _currentLevel +" > li", tf);
		if(tabs.length == 0) tabs = $(".tabs_level" + _currentLevel + " > td", tf);
		if(tabs.length == 0) tabs = $(".tabs_level" + _currentLevel + " > a", tf);
		if(tabs.length > 0){
			if(tf.className.indexOf("noneselected") == -1){
				if(tf.className.indexOf("stickyselected") != -1)
					tabFamily['selectedTab'] = OmniTab.getSelectedTabFromCookie(tf, tabs[0]);
				else
					tabFamily['selectedTab'] = OmniTab.getTabId(tabs[0]);
			}

			tabs.each(function(n,t){
				var tab = new Array();
				tab['domelement'] = t;
				tab['id'] = OmniTab.getTabId(t);
				tab['content'] = $("#" + tab['id'], tf);
				tab['parent'] = tf;
				tab['siblings'] = tabs;
				if(tab['content'].length > 0){
					$("a[href]", tab['content']).each(function(){
						h = $(this).attr("href")
						if(h.indexOf("#")==0){
							$(this).bind('click', function(){OmniTab.show(OmniTab.getTabObjectById(h.substring(1)));return false;});
							$(this).attr("href", "#");
						}
					});
					var $t = $(t)
					$t.bind('mouseover', function(){OmniTab.mouseOver(tab);}); 
					$t.bind('mouseout', function(){OmniTab.mouseOut(tab);}); 
					$t.bind('click', function(){OmniTab.show(tab);return false;});
					if(_currentLevel <= OmniTab.maxLevels){			
						tab['tabFamily'] = OmniTab.initTabs(tab['content'][0], _currentLevel+1);
					}
				}
				if(tab['id'] == tabFamily['selectedTab']){
					OmniTab.show(tab);
				}else{
					OmniTab.hide(tab);
				}
				tabFamily['tabs'][n] = tab;
			});
		}else{
			tabFamily = null;
		}
		if(_currentLevel==0)
			$(tf).removeClass("jshidden");
		
		return tabFamily;
	},

	mouseOver : function(tab){
		if(tab['parent']['selectedTab'] != tab['id'])
			$(tab['domelement']).addClass("item_over");
	},
	
	mouseOut : function(tab){
		if(tab['parent']['selectedTab'] != tab['id'])
			$(tab['domelement']).removeClass("item_over");
	},
	
	show : function(tab){
		if(tab['parent']['selectedTab'] != tab['id']){
			$(tab['siblings']).each(function(i, t){
				if(OmniTab.getTabId(t) == tab['id']){
					$("#" + tab['id'], tab['parent']).removeClass("jsnodisplay");
					$(t).addClass("item_on");
					Cookie.create("tab_page:" + OmniTab.currentPage + "&tab_family:" + tab['parent'].id, tab['id']);
				}else{
					$("#" + OmniTab.getTabId(t), tab['parent']).addClass("jsnodisplay");				
					$(t).removeClass("item_on");
				}
				$(t).css("color", "");
				$(t).css("background-color", "");
				$(t).removeClass("item_over");
			});
			tab['parent']['selectedTab'] = tab['id'];
		}
	},
	
	hide : function(tab){
		$("#" + tab['id'], tab['parent']).addClass("jsnodisplay");				
		$(tab).removeClass("selected");
	},

	getTabObjectById : function(id){
		var tab = null;
		$.each(OmniTab.tabTree, function(){
			tab = OmniTab.recurseTabObjects(id, this)
		});
		return tab;
	},
	
	recurseTabObjects : function(id, tabFamily){
		var tab = null;
		$.each(tabFamily['tabs'], function(i, t){
			if(t['id'] == id){
				tab = t;
			}
		});
		// if not found at the current level, drill deeper
		$.each(tabFamily['tabs'], function(i, t){
			tf = t['tabFamily'];
			if(tf != null)
				tab = OmniTab.recurseTabObjects(id, t['tabFamily']);
		});
		return tab
	},
	
	getTabId : function(tab){
		var $link = $(this.getTabLink(tab));
		return $link.attr("href").substring($link.attr("href").indexOf("#")+1)
	},
	
	getTabLink : function(tab){
		return ($("a", tab).length > 0)? $("a", tab)[0] : tab;
	},

	getSelectedTabFromCookie : function(tf, firstTab){
		var c = Cookie.read("tab_page:" + this.currentPage + "&tab_family:" + tf.id);
		if(c != null){
			return c;
		}else{
			return this.getTabId(firstTab)
		}
	}
};


/* COOKIE HANDLING CLASS */
var Cookie = {
	
	create : function(name, value){
		if(arguments.length > 2){
			var days = arguments[2];
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	},

	read : function(name){
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++){
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},

	erase : function(name){
		this.create(name,"",-1);
	}
};

OmniTab.init();