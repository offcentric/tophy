<?php
/********************************************************/
/* file: 		listing.php 							*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		06/07/2011								*/
/* version:		0.1										*/
/* description:											*/
/********************************************************/

include MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/views/results.php";
include MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/views/paging.php";
?>

					<ul class="journal">
<?php
		foreach ($GLOBALS['cm__journal_listing']->nodeset->aData as $p){
			$id = $p->getAttribute("id");
			$published = $p->getAttribute("published");
			$timestamp = getElementValue($p, "timestamp", 0, "");
			$posted_by = getElementValue($p, "posted_by", 0, "");
			$last_editor = getElementValue($p, "last_editor", 0, "");
			$title = getElementValue($p, "title", 0, "");
			$titlelink = createTitleLink($title);
			$mood = getElementValue($p, "mood", 0, "");
			$listening = getElementValue($p, "listening", 0, "");
			$reading = getElementValue($p, "reading", 0, "");
			$watching = getElementValue($p, "watching", 0, "");
			$content = getElementValue($p, "content", 0, "");
			if($_GET["s"] != "" && strlen($_GET["s"]) >= 3){
				$content = eregi_replace("(".$_GET["s"].")", "<span class=\"highlight\">\\1</span>", $content);
			}
			$tagcount = 1;
			$tagnodes =  $GLOBALS['cm__journal_xml']->xpath->query("tags/tag", $p);
			$taglinks = "";
			foreach($tagnodes as $tag){
				$taglinks .= "<a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] . "tag/" . trim($tag->nodeValue) . "\">" . trim($tag->nodeValue) . "</a>";
				if($tagcount < $tagnodes->length) $taglinks .= ", ";
				$tagcount++;
			}
			$content = utf8_decode($content);
			$end = "";
			if(strlen($content)>1000) $end = "...&nbsp;&nbsp;<a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath']."entry/".$id."/". $titlelink . "\">more &gt;&gt;</a>";
//			$content = trim(substr(stripslashes($content), 0, 1000)) . $end;
//			$content = transformContent($content);
?>
						<li class="<?php if(fmod($i, 2) != 0){echo "altrow ";}?>clearfix">
							<div class="entry clearfix">

								<div class="entrymain">
									<div class="title clearfix">
									<?php if(($_SESSION['cm__admin']['privileges'] > 2 || ($_SESSION['cm__admin']['privileges'] > 1 && $posted_by == $_SESSION['user'])) && $_GET["admin"]){?>
												<div class="editButtons">
													<?php if($_SESSION['cm__admin']['enable_publishing']){
														if(!$published){ ?>
														<a class="button publish" href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__admin']['webpath'] ?>entry/publish/<?php echo $id ?>">Publish</a>
													<?php }else{ ?>
														<a class="button unpublish" href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__admin']['webpath'] ?>entry/unpublish/<?php echo $id ?>">Unpublish</a>
													<?php }
													} ?>
													<a class="button" href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__admin']['webpath'] ?>entry/edit/<?php echo $id ?>">Edit</a>
													<a class="button" href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__admin']['webpath'] ?>entry/delete/<?php echo $id ?>">Delete</a>
												</div>
									<?php }?>
							<?php if($title!=""){?>
										<h3><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $id ?>/<?php echo $titlelink ?>"><?php echo stripslashes($title)?></a></h3>
							<?php }else{?>
										<h3><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $id ?>/<?php echo $titlelink ?>"><?php echo date('j F Y', $timestamp) ?></a></h3>
							<?php }?>
									</div>
								<?php if($title != "" || $mood != "" || $listening != "" || $reading != "" || $watching != "" || $taglinks != ""){?>
									<div class="entryheaders">
									<?php if($_SESSION['multiuser']){
										if($posted_by != "" || $title != ""){ ?>
										<strong>posted <?php if($posted_by != ""){ echo "by <a href=\"" . $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] . "user/" . $posted_by . "\">" . $posted_by . "</a></strong> "; } if($title != ""){?>on</strong> <?php echo date('j F Y',$timestamp) ?>, <?php echo date('H:i',$timestamp) ?><br /><?php } ?>
										<?php }
										if($last_editor != "" && $last_editor != $posted_by){?><strong>last edited by <a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] . "user/" . $last_editor . "\">" . $last_editor ?></a></strong><br /><?php } ?>
									<?php }elseif($title != ""){ ?>
										<strong>posted on</strong> <?php echo date('j F Y',$timestamp) ?>, <?php echo date('H:i',$timestamp) ?><br />
									<?php } ?>
									<?php if($_SESSION['showmood'] && $mood!=""){?>	<strong>mood:</strong> <?php echo stripslashes($mood) ?><br /><?php } ?>
									<?php if($_SESSION['showlistening'] && $listening!=""){?>	<strong>listening to:</strong> <?php echo stripslashes($listening) ?><br /><?php } ?>
									<?php if($_SESSION['showreading'] && $reading!=""){?>	<strong>reading:</strong> <?php echo stripslashes($reading) ?><br /><?php } ?>
									<?php if($_SESSION['showwatching'] && $watching!=""){?>	<strong>watching:</strong> <?php echo stripslashes($watching) ?><br /><?php } ?>
									<?php if($_SESSION['showtags'] && $taglinks!=""){?>	<strong>tags:</strong> <?php echo $taglinks ?><br /><?php } ?>
									</div>
								<?php }?>
									<div class="entrycontent clearfix"><?php  echo $content ?></div>
								</div>
<?php
			$comments = $p->getElementsByTagName("comment");
?>
								<div class="commentcount">
									<a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $id ?>/<?php echo $titlelink ?>#comments"><?php echo $comments->length ?> Comment<?php if($comments->length!=1) echo("s"); ?></a>
								</div>
							</div>
						</li>
<?php
			$i++;
		}
?>
					</ul>
<?php include MODULESBASEPATH . $_SESSION['cm__journal']['module_name'] . "/views/paging.php"; ?>

