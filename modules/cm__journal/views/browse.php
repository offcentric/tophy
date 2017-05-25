<?php
/********************************************************/
/* file: 		browse.php 								*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		07/07/2011								*/
/* version:		0.1										*/
/* description:	Generates HTML to show a taglist as  	*/
/*				well as older listings my month			*/
/********************************************************/

				$browsehtml = "";
				$tagshtml = "";
				
				if(($_SESSION['cm__admin']['privileges'] > 1 && $_GET["admin"]) || !$_SESSION['cm__admin']['enable_publishing']){
					$and_publish_filter = "";
				}else{
					$and_publish_filter = " and @published=1";
				}
				
				for($x=date("Y"); $x>=0; $x--){
					$oldestyear = $x;
					if($x!=date("Y")){
						$yearheader = ("<li><h4>$x</h4></li>");
						$startmonth = 12;
					}else{
						$startmonth = date("n");
					}
					$monthlinks = "";
					for($y=$startmonth; $y>=1; $y--){
						$nummonths++;
						if($y<12){$nextmonth = $y+1; $nextyear=$x;}else{ $nextmonth = 1; $nextyear = $x+1;}
						$xpresult = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[timestamp>" . strtotime("$y/1/$x") . " and timestamp<" . strtotime("$nextmonth/0/$nextyear") . $and_publish_filter . "]");
						if($x==$year && $y==$month){
							$oldestmonth = $y;
							$monthlinks .= "<li class=\"selected\">" . date("F",mktime(0,0,0,$y+1,0,$x)) . " " . $x . " (".$xpresult->length.")</li>\n";
						}else{
							if($xpresult->length > 0){
								$oldestmonth = $y;
								$monthlinks .= "<li><a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] . "date/$x/$y\">" . date("F",mktime(0,0,0,$y+1,0,$x)) . " " . $x . "</a>  (".$xpresult->length.")</li>\n";
							}
						}
						if($nummonths >= $_SESSION['maxmonths_browse']) break;	
					}
					if($monthlinks != "") $browsehtml .= $yearheader . $monthlinks;
					if($nummonths >= $_SESSION['maxmonths_browse']) break;	
				}
				if($oldestmonth != "" && $oldestyear != ""){
					$xpresult = $GLOBALS['cm__journal_xml']->xpath->query("/journal/entry[timestamp<" . strtotime("$oldestmonth/1/$oldestyear") . "]");
					if($xpresult->length > 0){
						$browsehtml .= "<br /><li><a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] . "archive\">Older Entries</a></li>\n";
					}
				}

				for($x=0;$x<sizeof($GLOBALS['tags']->alltags);$x++){
					if(strtolower($GLOBALS['tags']->alltags[$x]["tagname"]) == $_GET["tag"]){
						$tagshtml .= "<li class=\"selected\">" . $GLOBALS['tags']->alltags[$x]["tagname"] . "(" . $GLOBALS['tags']->alltags[$x]["count"] . ")</li>\n";
					}else{
						$tagshtml .= "<li class=\"tag" . ($x+1) . "\"><a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] . "tag/" . $GLOBALS['tags']->alltags[$x]["tagname"] . "\">" . $GLOBALS['tags']->alltags[$x]["tagname"] . "</a> (" .  $GLOBALS['tags']->alltags[$x]["count"] . ")</li>\n";
					} 
					if($x >= 15) break;
				}
				if($browsehtml != ""){
?>
					<h3>browse by date</h3>
					<ul class="entrylist">
						<?php echo $browsehtml ?>
					</ul>
<?php
				}
				if($tagshtml != ""){
?>
					<h3>browse by tag</h3>
					<ul class="taglist">
						<?php echo $tagshtml ?>
						<li class="alltags"><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>alltags">View all tags</a></li>
					</ul>
<?php } ?>