<?php if($GLOBALS['cm__journal_listing']->searchstring != "" || $_GET["tag"] != "" || $_GET["user"] != ""){?>
			<div id="resultscontainer">
				<div id="results" class="clearfix">
<?php
	if($GLOBALS['cm__journal_listing']->searchstring != "" && strlen($GLOBALS['cm__journal_listing']->searchstring) >= 3){
?>
					<div class="title"><h2>Your search for <span class="color"><?php echo sanitize_input($GLOBALS['cm__journal_listing']->searchstring) ?></span> returned <?php echo $GLOBALS['cm__journal_listing']->total_results ?> result<?php if($GLOBALS['cm__journal_listing']->total_results!=1) echo("s"); ?>.</h2></div>
<?php }else if($_GET["s"] != "" && strlen($GLOBALS['cm__journal_listing']->searchstring) < 3){ ?>
					Sorry, your search query must be at least 3 characters long.
<?php }else if($_GET["tag"] != ""){?>
					<div class="title clearfix"><h2>Posts tagged as <span class="color"><?php echo sanitize_input($_GET["tag"]) ?></span></h2></div>
<?php }else if($_GET["filter"] == "user_poster" && $_GET["user"] != ""){ ?>
					<div class="title"><h2>Posts created by <span class="color"><?php echo sanitize_input($_GET["user"]) ?></span></h2></div>
					<div class="subheader"><a href="<?php echo $_SESSION['cm__journal']['webpath']?>user/<?php echo sanitize_input($_GET["user"]) ?>/edited">(view posts edited by <?php echo sanitize_input($_GET["user"]) ?>)</a></div>
<?php }else if($_GET["filter"] == "user_editor" && $_GET["user"] != ""){ ?>
					<div class="title"><h2>Posts  edited by <span class="color"><?php echo sanitize_input($_GET["user"]) ?></span></h2></div>
					<div class="subheader"><a href="<?php echo $_SESSION['cm__journal']['webpath']?>user/<?php echo sanitize_input($_GET["user"]) ?>/created">(view posts created by <?php echo sanitize_input($_GET["user"]) ?>)</a></div>
<?php } ?>
<?php if($GLOBALS['cm__journal_listing']->total_results > $_SESSION['entries_per_page']){ ?>
				<span class="current_results">Displaying results <?php echo $GLOBALS['cm__journal_listing']->page_start ?> to <?php echo $GLOBALS['cm__journal_listing']->page_end ?></span>
<?php } ?>
				</div>
			</div>
<?php } ?>
