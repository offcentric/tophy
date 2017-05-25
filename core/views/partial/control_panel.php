<div id="control_panel_container" class="jshidden jspositionabsolute">
	<div id="control_panel" class="jsfloatleft">
		<div>
<?php if($_SESSION['pagetype']=="page" || $_SESSION['pagetype']=="book"){ ?>
		<div class="line">select display mode:</div>
			<div class="links">
<?php
			if($_SESSION['cm__gallery']['thumb_ratio'] =="fixed"){
?>
				<div class="item_on">fixed ratio</div>
<?php
			}else{
?>
				<div><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] ?>/<?php if(@$page != "") echo $page . "/"; ?>thumbnails/fixed">fixed ratio</a></div>
<?php 
			}
			if($_SESSION['cm__gallery']['thumb_ratio'] =="constrain_h"){
?>
				<div class="item_on">constrain height</div>
<?php
			}else{
?>
				<div><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] ?>/<?php if(@$page != "") echo $page . "/"; ?>thumbnails/constrain_h">constrain height</a></div>
<?php
}
			if($_SESSION['cm__gallery']['thumb_ratio'] =="constrain_w"){
?>
				<div class="item_on">constrain width</div>
<?php
			}else{
?>
				<div><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] ?>/<?php if(@$page != "") echo $page . "/"; ?>thumbnails/constrain_w">constrain width</a></div>
<?php
			}
			if($_SESSION['cm__gallery']['thumb_ratio'] =="constrain_both"){
?>
				<div class="item_on">constrain both</div>
<?php
			}else{
?>
				<div><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] ?>/<?php if(@$page != "") echo $page . "/"; ?>thumbnails/constrain_both">constrain both</a></div>
<?php
			}
			if($_SESSION['cm__gallery']['display']=="full"){
?>
				<div class="item_on">full</div>
<?php
			}else{
?>						
				<div><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] ?>/<?php if(@$page != "") echo $page . "/"; ?>full">full</a></div>
<?php 
			}
			if($_SESSION['cm__gallery']['display']=="strip"){
?>
				<div class="item_on">strip</div>
<?php
			}else{
?>						
				<div><a href="<?php echo $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] ?>/<?php if(@$page != "") echo $page . "/"; ?>strip">strip</a></div>
<?php 
			}
?>
			</div>
<?php } ?>
<?php if(array_key_exists('themes', $_SESSION) && count($_SESSION['themes']) > 1){ ?>
			<div class="line">select theme:</div>
				<div class="links">
<?php
		foreach($_SESSION['themes'] as $theme){
			if($theme == $_SESSION['theme']){
?>			
					<div class="item_on"><?php echo $theme; ?></div>
<?php 		}else{ ?>
					<div><a href="<?php echo $GLOBALS['current_webpath'] ?>?theme=<?php echo $theme ?>"><?php echo $theme; ?></a></div>
<?php
			}
		}
?>
			</div>
<?php
	}
?>
		</div>
	</div>
	<div id="control_panel_clickzone"></div>
</div>	
