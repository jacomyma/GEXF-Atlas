<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Article";
	include("_head.php");
	if(isset($_GET['resource']) && $resource_id = $_GET['resource']){
		$split = preg_split('/_/', $resource_id);
		$section_id = $split[0]."_".$split[1];
		$section = $data->getSectionById($section_id);
?>
	<body>
		<div class="container_12">
<?php
		$thispage = "resource";
		include("_menu.php");
		$resource=$section->getResourceById($resource_id);
		$document = $section->getDocumentById($resource->getDocument());
?>
			<div class="grid_12">
				<h2 id="page-heading"><?php echo htmlentities($document->getName()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_4">
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Download File</a>
					</h2>
					<div class="article first">
						<a href="<?php echo htmlentities($document->getFile()); ?>"><h3><?php echo htmlentities($document->getName()); ?></h3>
							<p align="center">
								<img src="res/file_icon.png"/>
								<br/>
								<?php echo htmlentities($document->getType()); ?>
							</p>
						</a>
					</div>
				</div>
			</div>
			
<?php
		$text = $section->showResourceHtmlContent($resource->getId(), true);
		if($text != ""){
?>
			<div class="grid_8">
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">About this document...</a>
					</h2>
					<div class="block">
						<div class="article full first">
<?php
			echo $text;
?>
							<p></p>
						</div>
					</div>
				</div>
			</div>
<?php
		}
?>
			<div class="clear"></div>
			
<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
<?php } ?>
</html>