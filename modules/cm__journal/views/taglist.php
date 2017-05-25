<?php
/********************************************************/
/* file: 		taglist.php 							*/
/* module:		CM__JOURNAL								*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		07/07/2011								*/
/* version:		0.1										*/
/* description:	Displays a sorted list of all tags		*/
/*				being used in Journal XML				*/
/********************************************************/
?>
				<div class="title clearfix"><h2>All tags</h2></div>
				<div class="sortby">
					Sort by:
				<?php if($GLOBALS['cm__journal']['tags']['sortby'] == "name"){?>
					<strong>name</strong>
				<?php }else{?>
					<a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>alltags/name">name</a>
				<?php }?>
				<?php if($GLOBALS['cm__journal']['tags']['sortby'] == "popular"){?>
					<strong>popular</strong>
				<?php }else{?>
					<a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>alltags/popular">popular</a>
				<?php }?>
				<?php if($GLOBALS['cm__journal']['tags']['sortby'] == "recent"){?>
					<strong>recently used</strong>
				<?php }else{?>
					<a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>alltags/recent">recently used</a>
				<?php }?>
				</div>
					<ul class="tags clearfix">
<?php for($x=0;$x<sizeof($GLOBALS['tags']->alltags);$x++){?>
						<li><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__journal']['webpath'] ?>tag/<?php echo $GLOBALS['tags']->alltags[$x]["tagname"] ?>"><?php echo $GLOBALS['tags']->alltags[$x]["tagname"] ?></a> (<?php echo $GLOBALS['tags']->alltags[$x]["count"] ?>)</li>
<?php }?>
					</ul>
