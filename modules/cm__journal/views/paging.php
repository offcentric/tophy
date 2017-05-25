<?php
if($GLOBALS['cm__journal_listing']->haspreviouspage || $GLOBALS['cm__journal_listing']->hasnextpage){
?>
					<div class="pagelinks clearfix">
	<?php if($GLOBALS['cm__journal_listing']->haspreviouspage){?>
						<a class="button previous" href="<?php echo $GLOBALS['scriptpath_without_page'] ?>page/<?php echo $GLOBALS['cm__journal_listing']->pagenumber-1 ?>">Newer Entries</a>
	<?php }?>
	<?php if($GLOBALS['cm__journal_listing']->hasnextpage){?>
						<a class="button next" href="<?php echo $GLOBALS['scriptpath_without_page'] ?>page/<?php echo $GLOBALS['cm__journal_listing']->pagenumber+1 ?>">Older Entries</a>
	<?php }?>
				</div>
<?php }?>