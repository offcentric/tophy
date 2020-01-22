			<div id="footer" class="colorbar">
				<div class="footercontent">
					<?php if(@in_array($_SESSION['cm__journal']['module_name'], $_SESSION['modules_enabled'])){ ?><a href="<?php echo $_SESSION['cm__journal']['webpath'] ?>xml/rss.xml" target="_blank">RSS</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?><?php echo str_replace("{gallery_webpath}", $_SESSION['webpath'], $_SESSION['footercontent']) ?>Site codebase, content and design &copy; <?php echo date("Y"); ?>&nbsp;Mark Mulder
				</div>
			</div>
		</div>
	</div>
</body>

</html>