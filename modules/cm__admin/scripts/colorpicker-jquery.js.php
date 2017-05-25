/* DHTML Color Picker : v1.0.4 : 2008/05/08 */
/* http://www.colorjack.com/software/dhtml+color+picker.html */

/* Re-engineered by Mark Mulder (http://offcentric.com) */
/* Now in JSON notated code and now allowing mulitple colorpickers on one page. Also now dependent on JQUery API */

/* COLOR PICKER */
var ColorPicker = {
	maxValue : {'H':360,'S':100,'V':100},
	HSV : {H:360, S:100, V:100},
	slideHSV : {H:360, S:100, V:100},
	zINDEX : 15,
	stop :1,
	ds : null,
	ab : null,
	area : "",
	oX : 0, oY : 0, eX : "", eY : "", ab : null, tX : 0, tY : 0, oo : 0,
	container : null,
	H : new Array(),
	index : 0,
	 
	init : function(index){
		if(index == undefined)
			$("div.custom_style").each(function(x, el){
				if($("div.colorpicker", el).length > 0)
					ColorPicker.init_cp(x, $("div.colorpicker", el)[0]);
			});
		else
			ColorPicker.init_cp(index, $("div.colorpicker", $("div.custom_style")[index])[0]);
	},
	
	init_cp : function(x, cp){
		var hexval = $("input.text_hex", $(cp).parent("div.custom_style"));
		$("div.SV", cp).bind("mousedown" , function(event){ColorPicker.HSVslide("SVslide",x,event);});
		$("form.H", cp).bind("mousedown" , function(event){ColorPicker.HSVslide("Hslide",x,event);});			
		ColorPicker.H[x] = 360;
		if(hexval.length >0){
			text_hexval = hexval.attr("value");
			if(text_hexval.match(/[0-9a-f]{6}/i)){
				inputHSV = Color.HEX_HSV(text_hexval);
				ColorPicker.H[x] = inputHSV.H;
				$(".SVslide", cp).css("left", Math.round(inputHSV.S * 1.67-5) + "px");
				$(".SVslide", cp).css("top", Math.round(166 - (inputHSV.V*1.66)) + "px");
				$("div.SV", cp).css("backgroundColor" , "#"  + Color.HSV_HEX({H:inputHSV.H, S:100, V:100}));
				if(inputHSV.H > 0)
					$(".Hslide", cp).css("top", Math.round(154 - ((inputHSV.H/360)*154)) + "px");
			}
		}
		var z = "";
		if($(".Hmodel div", cp).length == 0){
			for(var i=165; i>=0; i--){
				z+="<div style=\"background: #"+Color.HSV_HEX({H:Math.round((360/165)*i), S:100, V:100})+";\"><br /><\/div>";
			}
			$(".Hmodel", cp).html(z);
		}
		
	},
	
	HSVslide : function(area, index, event){
		this.index = index
		this.container = $("div.colorpicker", $("div.custom_style")[index]);
		this.area = area;
		if(this.stop){
			this.stop = "";
			this.ds = $("." + this.area, this.container)[0].style;

			this.ab = this.container.offset();
			this.oo = (this.area=='Hslide')? 2 : 0;
			
			this.ab.left += 9;
			this.ab.top += 12;
			if(this.area=='SVslide') this.slideHSV.H = this.H[this.index];
			$(document).bind("mousemove", ColorPicker.drag);
			$(document).bind("mouseup", function(){ColorPicker.stop=1; $(document).unbind("mousemove");$(document).unbind("mouseup");});
			ColorPicker.drag(event);
		}
	},
	
	drag : function(event){
		var oCP = ColorPicker;
		if(!oCP.stop){
			if(oCP.area != "drag")
				oCP.tXY(event);

			if(oCP.area == "SVslide"){
				oCP.ds.left = oCP.ckHSV(oCP.tX - oCP.oo, 162)+'px';
				oCP.ds.top = oCP.ckHSV(oCP.tY - oCP.oo, 162)+'px';
				oCP.slideHSV.S = oCP.mkHSV(100, 162, oCP.ds.left);
				oCP.slideHSV.V = 100 - oCP.mkHSV(100, 162, oCP.ds.top);
				oCP.HSVupdate();

			}else if(oCP.area == "Hslide"){
				var ck = oCP.ckHSV(oCP.tY - oCP.oo,163);
		
				oCP.ds.top = (ck-5)+'px';
				oCP.slideHSV.H = oCP.mkHSV(360,163,ck);

				r=['H','S','V'], z={};
				for(var i in r){
					i=r[i];
					z[i] = (i=='H')? (oCP.maxValue[i] - oCP.mkHSV(oCP.maxValue[i],163,ck)) : oCP.HSV[i];
				}
				
				oCP.HSVupdate(z);
				$("div.SV", oCP.container).css("backgroundColor" , "#"  + Color.HSV_HEX({H:oCP.HSV.H, S:100, V:100}));
				oCP.H[oCP.index] = oCP.HSV.H;
			}
		}
	},
	
	tXY : function(event){
		this.tY = this.XY(event).Y - this.ab.top;
		this.tX = this.XY(event).X - this.ab.left;
	},
	
	mkHSV : function(a,b,c){
		return(Math.min(a,Math.max(0,Math.ceil((parseInt(c)/b)*a))));
	},
	
	ckHSV : function(a,b){
		if(this.within(a,0,b))
			return(a);
		else if(a>b)
			return(b);
		else if(a < 0)
			return("-" + this.oo);
	},

	HSVupdate : function(v){
		v = Color.HSV_HEX(this.HSV = v? v : this.slideHSV);
		var cs_parent = $(this.container).parent("div.custom_style");
		$("input.text_hex", cs_parent).attr("value", v.toUpperCase());
		Customization.do_live_preview_color($("input.text_hex", cs_parent)[0], false)
		$("div.colorpicker_container div", cs_parent).css("background", "#"+v);
		return(v);
	},

	/* UTIL FUCNTIONS */
	agent : function(v){return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0));},
	within : function(v,a,z){return((v>=a && v<=z)?true:false);},
	XY : function(e,v){var o = this.agent('msie')? {'X':event.clientX+document.documentElement.scrollLeft,'Y':event.clientY+document.documentElement.scrollTop} : {'X':e.pageX,'Y':e.pageY};return(v?o[v]:o);}
};

/* COLOR LIBRARY */

var Color={

	cords : function(W){
		var W2=W/2, rad=(hsv.H/360)*(Math.PI*2), hyp=(hsv.S+(100-hsv.V))/100*(W2/2);

		$("#mCur").css("left", Math.round(Math.abs(Math.round(Math.sin(rad)*hyp)+W2+3))+"px");
		$("#mCur").css("top" , Math.round(Math.abs(Math.round(Math.cos(rad)*hyp)-W2-21))+"px");
	},

	HEX : function(o){
		o=Math.round(Math.min(Math.max(0,o),255));
		return("0123456789ABCDEF".charAt((o-o%16)/16)+"0123456789ABCDEF".charAt(o%16));
	},

	RGB_HEX : function(o) {
		var fu=this.HEX; return(fu(o.R)+fu(o.G)+fu(o.B));
	},

	HSV_RGB : function(o){
    	var R, G, A, B, C, S=o.S/100, V=o.V/100, H=o.H/360;

		if(S>0){
			if(H>=1) H=0;
			H=6*H; F=H-Math.floor(H);
			A=Math.round(255*V*(1-S));
			B=Math.round(255*V*(1-(S*F)));
			C=Math.round(255*V*(1-(S*(1-F))));
			V=Math.round(255*V); 

			switch(Math.floor(H)) {
				case 0: R=V; G=C; B=A; break;
				case 1: R=B; G=V; B=A; break;
				case 2: R=A; G=V; B=C; break;
				case 3: R=A; G=B; B=V; break;
				case 4: R=C; G=A; B=V; break;
				case 5: R=V; G=A; B=B; break;
			}
        	return({'R':R?R:0, 'G':G?G:0, 'B':B?B:0, 'A':1});
		}else{
			return({'R':(V=Math.round(V*255)), 'G':V, 'B':V, 'A':1});

		}
	},

	HSV_HEX : function(o){
		return(this.RGB_HEX(this.HSV_RGB(o)));
	},
	
	HEX_RGB : function(hex){
		var RGB = {R:255, G:255, B:255};
		RGB.R = this.decimalize(hex.substring(0,2));
		RGB.G = this.decimalize(hex.substring(2,4));
		RGB.B = this.decimalize(hex.substring(4,6));
		return RGB;
	},
	
	RGB_HSV : function(o) {
		var HSV = {H:360, S:100, V:100};
		R = o.R / 255; G = o.G / 255; B = o.B / 255; // Scale to unity.
/*		var v, x, f, i;
		var minVal = Math.min(R, G, B);
		var maxVal = Math.max(R, G, B);
		if(minVal == maxVal){
		    HSV.H = 0;
			HSV.S = 0;			
		}else{
		    f = (R == minVal) ? G - B : ((G == minVal) ? B - R : R - G);
		    i = (R == minVal) ? 3 : ((G == minVal) ? 5 : 1);
		    HSV.H = ((i - f /(maxVal - minVal))/6);
			HSV.S = (maxVal - minVal)/maxVal;
		}
		HSV.V ; maxVal;
*/
		var minVal = Math.min(R, G, B);
		var maxVal = Math.max(R, G, B);
		var delta = maxVal - minVal;

		HSV.V = maxVal;

		if(delta == 0){
			HSV.H = 0;
			HSV.S = 0;
		}else{
			HSV.S = delta / maxVal;
			var del_R = (((maxVal - R) / 6) + (delta / 2)) / delta;
			var del_G = (((maxVal - G) / 6) + (delta / 2)) / delta;
			var del_B = (((maxVal - B) / 6) + (delta / 2)) / delta;

			if(R == maxVal) {HSV.H = del_B - del_G;}
			else if (G == maxVal) {HSV.H = (1 / 3) + del_R - del_B;}
			else if (B == maxVal) {HSV.H = (2 / 3) + del_G - del_R;}

			if (HSV.H < 0) {HSV.H += 1;}
			if (HSV.H > 1) {HSV.H -= 1;}
		}
		HSV.H = Math.round(HSV.H * 360);
		HSV.S = Math.round(HSV.S * 100);
		HSV.V = Math.round(HSV.V * 100);
		return HSV;

	},
	
	HEX_HSV : function(hex){
		return this.RGB_HSV(this.HEX_RGB(hex));
	},
	
	decimalize : function(hex){
		var digits = '0123456789ABCDEF';
		return ((digits.indexOf(hex.charAt(0).toUpperCase()) * 16) + digits.indexOf(hex.charAt(1).toUpperCase()));
	}
};

ColorPicker.init();