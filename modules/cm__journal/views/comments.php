						<div class="comments"><a name="comments"></a>
								<h4>Comments</h4>
<?php		if($GLOBALS['entry']->comments->length>0){
			foreach($GLOBALS['entry']->comments as $commentnode){
				$comment = getElementValue($commentnode, "content", 0, "");
				$comment = cleanComment($comment);
?>
							<div class="comment clearfix">
								<?php if($_SESSION['cm__admin']['privileges'] > 2 && $_GET["admin"]){?><a class="button" href="<?php echo $_SESSION['admin_root'] ?>entry/<?php echo $_GET["id"] ?>/delete_comment/<?php echo $commentnode->getAttribute("id") ?>">delete</a><?php }?>
								<span class="postedby">
									posted by <strong><?php if($_SESSION['show_comment_emails'] && getElementValue($commentnode, "email", 0, "")!=""){?><a href="mailto:<?php echo getElementValue($commentnode, "email", 0, "") ?>"><?php }?><?php echo getElementValue($commentnode, "postedby", 0, "") ?><?php if($_SESSION['show_comment_emails'] && getElementValue($commentnode, "email", 0, "")!=""){?></a><?php }?></strong>
								 	on <?php echo date('j F Y', getElementValue($commentnode, "timestamp", 0, "")) ?>, <?php echo date('H:i', getElementValue($commentnode, "timestamp", 0, ""))?>
									<?php if($_SESSION['cm__admin']['privileges'] > 1 && $_GET["admin"]){?><br />IP Address: <?php echo getElementValue($commentnode, "IP", 0, "") ?><?php } ?>
								</span>
								<p><?php echo $comment ?></p>
							</div>
<?php
			}
		}else{
?>
							<div class="comment">No comments have been added yet.</div>
<?php
		}
?>
<?php if($GLOBALS['comment_messages'] != "") echo "<div class=\"messages\">" . $GLOBALS['comment_messages'] . "</div>"; ?>
							<div id="commentform" class="clearfix">
								<strong>Add a comment</strong><br />
								<form name="comment_form" id="comment_form" class="validate" action="<?php echo $_SESSION['cm__journal']['webpath'] ?>entry/<?php echo $_GET["id"] ?>/<?php echo createTitleLink($title) ?>#comment" method="post">
<?php if($_SESSION['user'] == ""){ ?>
								<div class="formrow clearfix">
									<div class="errordiv"><?php if($GLOBALS['errs']['comment_name'] != "") echo "<div><p>" . $GLOBALS['errs']['comment_name'] . "</p></div>"; ?></div>
									<label for="comment_name">name<span class="color">*</span></label>
									<div><input type="text" name="comment_name" id="comment_name" value="<?php if(sizeof($GLOBALS['errs']) > 0) echo $_POST["comment_name"] ?>" /></div>
								</div>
<?php 	if($_SESSION['show_comment_emails']){ ?>
								<div class="formrow clearfix">
									<div class="errordiv"><?php if($GLOBALS['errs']['comment_email'] != "") echo "<div><p>" . $GLOBALS['errs']['comment_email'] . "</p></div>"; ?></div>
									<label for="comment_email">email</label>
									<div><input type="text" name="comment_email" id="comment_email" value="<?php if(sizeof($GLOBALS['errs']) > 0) echo $_POST["comment_email"] ?>" /></div>
								</div>
<?php	} 
} ?>
								<div class="commentcontent formrow">
									<div class="errordiv"><?php if($GLOBALS['errs']['comment_text'] != "") echo "<div><p>" . $GLOBALS['errs']['comment_text'] . "</p></div>"; ?></div>
									<textarea name="comment_text" id="comment_text" /><?php if(sizeof($GLOBALS['errs']) > 0) echo stripslashes($_POST["comment_text"]) ?></textarea>
								</div>
								<input type="hidden" name="action" value="add_comment" />
								<input type="hidden" name="time" value="<?php echo time() ?>" />
								<div><input type="submit" class="submit" name="comment_submit" value="post comment" /></div>
								</form>
							</div>
						</div>				
