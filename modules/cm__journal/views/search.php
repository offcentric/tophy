			<div id="search" class="clearfix">
				<div class="searchby_text">
					<form action="<?php echo $_SESSION['cm__journal']['webpath'] ?>search" method="post" id="searchform" name="searchform">
						<input type="text" name="searchstring" id="search" value="<?php echo $_GET["searchstring"] ?>" />
						<input value="search" type="submit" class="submit" />
					</form>
				</div>
			</div>
