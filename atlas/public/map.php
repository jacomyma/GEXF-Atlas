<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Map";
	include("_head.php");
	if(isset($_GET['map']) && $map_id = $_GET['map']){
		$split = preg_split('/_/', $map_id);
		$section_id = $split[0]."_".$split[1];
		$section = $data->getSectionById($section_id);
?>
	<body>
		<div class="container_12">
		
			<?php
				$thispage = "map";
				include("_menu.php");
			?>
			
<?php $map=$section->getMapById($map_id); ?>
			<div class="grid_12">
				<div style="height:50px;"></div>
				<h2 id="page-heading"><?php echo htmlentities($map->getTitle()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_12">
				<?php GexfExplorer($map, 940, 500); ?>
			</div>
			<div class="clear"></div>
			
<?php
	if($legend = $section->getDocumentById($map->getLegend())){
		$link = $legend->getFile();
?>
			<div class="grid_8">
				<div class="box">
					<h2>
						<a href="#" id="toggle-articles">Legend</a>
					</h2>
					<div class="block">
							<a href="<?php echo htmlentities($link); ?>"><img src="<?php echo htmlentities($link); ?>" width="600px"/></a>
					</div>
				</div>
			</div>
<?php
	}
	if($document = $section->getDocumentById($map->getDownloadableDocument())){
		$link = $document->getFile();
?>
			<div class="grid_2">
				<div class="box">
					<h2>
						<a href="#" id="toggle-articles">Download PDF</a>
					</h2>
					<div class="block">
						<a href="<?php echo htmlentities($link); ?>">
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
	}
	if($document = $section->getDocumentById($map->getDocument())){
		$link = $document->getFile();
?>
			<div class="grid_2">
				<div class="box">
					<h2>
						<a href="#" id="toggle-articles">Download Source</a>
					</h2>
					<div class="block">
						<a href="<?php echo htmlentities($link); ?>">
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
	}
?>
			
			<div class="clear"></div>
			
			<div class="grid_12">
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">About this map</a>
					</h2>
					<div class="block">
						<div class="article full first">
<?php
	echo $section->showMapHtmlContent($map->getId(), true);
?>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
<?php } ?>
</html>