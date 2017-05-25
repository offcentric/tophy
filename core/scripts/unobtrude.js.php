/* this loads a special 'jsonly' CSS file which holds classes to hide certain elements on the page only when JS is enabled on the client, thereby keeping with Progressive Enhancement/Unobtrusive Javascript */
function insertCSSFile(filename){
	var css = document.createElement('link');
	css.type = 'text/css';
	css.rel = 'stylesheet';

	css.href = filename;
	css.media = 'screen';
	document.getElementsByTagName("head")[0].appendChild(css);
}

function insertJSFile(filename){
	var js = document.createElement('script');
	js.type = 'text/javascript';

	js.src = filename;
	document.getElementsByTagName("head")[0].appendChild(js);
}

/* fire off this function immediately, the <head> tag is all we need and it's already been created (we're inside the head after all :)) */
if(BrowserDetect.supported()) insertCSSFile('<?php echo $_SESSION['webpath'] ?>styles/jsonly.css');