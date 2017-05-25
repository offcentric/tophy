<?php
/********************************************************/
/* file: 		entry.php 								*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		07/07/2011								*/
/* version:		0.1										*/
/* description:	Generates HTML to show a single 		*/
/*				journal entry							*/
/********************************************************/

	if($GLOBALS['entry']->currentnode){
?>
<div class="pagelinks clearfix">
<?php if($GLOBALS['entry']->previous_title != ""){?>
	<a class="previous" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $GLOBALS['entry']->previous_id ?>/<?php echo createTitleLink($GLOBALS['entry']->previous_title) ?>">&lt;&lt; <?php echo $GLOBALS['entry']->previous_title ?></a>
<?php }?>
<?php if($GLOBALS['entry']->next_title != ""){?>
	<a class="next" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $GLOBALS['entry']->next_id ?>/<?php echo createTitleLink($GLOBALS['entry']->next_title) ?>"><?php echo $GLOBALS['entry']->next_title ?> &gt;&gt;</a>
<?php }?>
</div>
					<div class="title clearfix">
<?php if(($_SESSION['cm__admin']['privileges'] > 2 || ($_SESSION['cm__admin']['privileges'] > 1 && $GLOBALS['entry']->posted_by == $_SESSION['user'])) && $_GET["admin"]){?>
						<div class="editButtons">
							<?php if($_SESSION['cm__admin']['enable_publishing']){
								if(!$GLOBALS['entry']->published){ ?>
								<a class="button publish" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/publish/<?php echo $_REQUEST["id"] ?>">Publish</a>
							<?php }else{ ?>
								<a class="button unpublish" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/unpublish/<?php echo $_REQUEST["id"] ?>">Unpublish</a>
							<?php }
							} ?>
							<a class="button" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/edit/<?php echo $_REQUEST["id"] ?>">Edit</a>
							<a class="button" href="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/delete/<?php echo $_REQUEST["id"] ?>">Delete</a>
						</div>
<?php	}?>
				<?php if($GLOBALS['entry']->title!=""){?>
						<h2><a href="<?php echo $_SESSION['cm__journal']['webpath'] . "entry/" . $_REQUEST["id"] . "/" . createTitleLink($GLOBALS['entry']->title) ?>"><?php echo $GLOBALS['entry']->title ?></a></h2>
				<?php }else{?>
						<h2><a href="<?php echo $_SESSION['cm__journal']['webpath'] . "entry/" . $_REQUEST["id"] ?>"><?php echo date('j F Y',$GLOBALS['entry']->timestamp) ?>, <?php echo date('H:i',$GLOBALS['entry']->timestamp) ?></a></h2>
				<?php }?>
					</div>
					<div class="entrydetail">
					<?php if($GLOBALS['entry']->title != "" || $GLOBALS['entry']->mood != "" || $GLOBALS['entry']->listening != "" || $GLOBALS['entry']->reading != "" || $GLOBALS['entry']->watching != "" || $GLOBALS['entry']->taglinks != ""){?>
						<div class="entryheaders">
						<?php if($_SESSION['multiuser']){
							if($GLOBALS['entry']->posted_by != "" || $GLOBALS['entry']->title != ""){ ?>
							<strong>posted <?php if($GLOBALS['entry']->posted_by != ""){ echo "by <a href=\"" . $_SESSION['cm__journal']['webpath'] . "user/" . $GLOBALS['entry']->posted_by . "\">" . $GLOBALS['entry']->posted_by . "</a></strong> ";}  if($GLOBALS['entry']->title != ""){?>on</strong> <?php echo date('j F Y',$GLOBALS['entry']->timestamp) ?>, <?php echo date('H:i',$GLOBALS['entry']->timestamp) ?><br /><?php } ?>
							<?php }
							if($GLOBALS['entry']->last_editor != "" && $GLOBALS['entry']->last_editor != $GLOBALS['entry']->posted_by){?><strong>last edited by <a href="<?php echo $_SESSION['cm__journal']['webpath'] . "user/" . $GLOBALS['entry']->last_editor . "\">" . $GLOBALS['entry']->last_editor ?></a></strong><br /><?php } ?>
						<?php }elseif($GLOBALS['entry']->title != ""){ ?>
							<strong>posted on</strong> <?php echo date('j F Y',$GLOBALS['entry']->timestamp) ?>, <?php echo date('H:i',$GLOBALS['entry']->timestamp) ?><br />
						<?php } ?>
							<?php if($_SESSION['showmood'] && $GLOBALS['entry']->mood!=""){?>	<strong>mood:</strong> <?php echo $GLOBALS['entry']->mood ?><br /><?php } ?>
							<?php if($_SESSION['showlistening'] && $GLOBALS['entry']->listening!=""){?>	<strong>listening to:</strong> <?php echo $GLOBALS['entry']->listening ?><br /><?php } ?>
							<?php if($_SESSION['showreading'] && $GLOBALS['entry']->reading!=""){?>	<strong>reading:</strong> <?php echo $GLOBALS['entry']->reading ?><br /><?php } ?>
							<?php if($_SESSION['showwatching'] && $GLOBALS['entry']->watching!=""){?>	<strong>watching:</strong> <?php echo $GLOBALS['entry']->watching ?><br /><?php } ?>
							<?php if($_SESSION['showtags'] && $GLOBALS['entry']->taglinks!=""){?>	<strong>tags:</strong> <?php echo $GLOBALS['entry']->taglinks ?><br /><?php } ?>
						</div>
					<?php }?>
					<div class="entrycontent clearfix"><?php echo $GLOBALS['entry']->content ?></div>
					<?php include JOURNALBASEPATH . '/views/partial/comments.php'; ?>
					</div>
<?php
	}else{
?>
			<br /><br /><div align="center">ERROR! invalid entry ID!</div>
<?php }
?>
