<?php
$_SESSION['pagetitle'] = "Help";
$_SESSION['pagetype'] = "help";
$_SESSION['pagename'] = "help";
include JOURNALBASEPATH . '/views/partial/start.php';

if($_GET["display"] == "entry_form"){
?>
		<table class="<?php echo $_GET["display"]?>">
			<tr>
				<th>You type</th>
				<th>You get</th>
			</tr>
			<tr>
				<td>[a href="http://offcentric.com"]coolness[/a]</td>
				<td><a href="http://offcentric.com">coolness</a></td>
			</tr>
			<tr>
				<td>[img src="http://media.offcentric.com/rick.jpg" /]</td>
				<td><img src="http://media.offcentric.com/rick.jpg" /></td>
			</tr>
			<tr>
				<td>[imageblock float="right" src="http://media.offcentric.com/rick.jpg" caption="rickrolled again!" /]The imageblock tag allows you to place an image and accompanying caption floating inline with some text, on the left side or the right side, using the "float" attribute. This example, as you can see, has a float value of "right". You can omit this float attribute entirely which will display the image block above your text.</td>
				<td><div class="imageblock right"><div class="inside"><img src="http://media.offcentric.com/rick.jpg" /><div>rickrolled again!</div></div></div>The imageblock tag allows you to place an image and accompanying caption floating inline with some text, on the left side or the right side, using the "float" attribute. This example, as you can see, has a float value of "right". You can omit this float attribute entirely which will display the image block above your text.</td>
			</tr>
			<tr>
				<td>[flash id="some_unique_name" swf="http://www.mysite.com/some-flash-movie.swf" width="125" height="96" flashvars="flashvar1:'value1', flashvar2:'value2'" /]</td>
				<td><span id="widget" style="display:block;width:125px;height:96px;margin:auto"><span id="widget_embed"></span></span><script type="text/javascript">var flashvars = {env:"embed", widget:    "bca5e1fa4e5cb67336c12942a1dacdb4", playlist: "a72ca3fe4eb33c41620d517bebea0d0a", vuid: "096aaa8937eea3636b1de15e5d1851b6"}; var params = {allowScriptAccess: "always",wmode: "transparent"}; var attributes = {id: "widget",name: "widget"}; swfobject.embedSWF("http://www.mixwit.com/flash/widgets/shell.swf", "widget_embed", "125", "96", "9.0.0", false, flashvars, params, attributes);</script></td>
			</tr>
			<tr>
				<td>[quote]quoth the raven nevermore[/quote]</td>
				<td><quote>quoth the raven nevermore</quote></td>
			</tr>
			<tr>
				<td>Web 2.0 is so [strike]trendy[/strike] lame.</td>
				<td>Web 2.0 is so <strike>trendy</strike> lame.</td>
			</tr>
			<tr>
				<td>[pre]some example code<br/>this and that[/pre]</td>
				<td><pre>some example code<br/>this and that</pre></td>
			</tr>
			<tr>
				<td>those are some [strong]good[/strong] tacos!</td>
				<td>those are some <strong>good</strong> tacos!</td>
			</tr>
			<tr>
				<td>[small]read the fine print[/small]</td>
				<td><small>read the fine print</small></td>
			</tr>
			<tr>
				<td>I told you a [em]million[/em] times, don't exaggerate!</td>
				<td>I told you a <em>million</em> times, don't exaggerate!</td>
			</tr>
		</table>
<?php
}

include JOURNALBASEPATH . '/views/partial/end.php';
?>
