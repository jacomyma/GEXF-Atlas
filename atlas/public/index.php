<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Home";
	include("_head.php");
?>
	<body>
		<div class="container_12">
			<div class="grid_12">
				<h1>Atlas</h1>
				<h3>Choose a section to browse</h3>
			</div>
			<div class="cleaner"></div>
		
<?php
	foreach($data->getSections() as $section){
		if($section->isPublished()){
?>
			
			<div class="grid_12 sectionBlock">
				<h1 class="fancy sectionBlockTitle">
					<a href="section.php?section=<?php echo htmlentities($section->getId()); ?>"><?php echo htmlentities($section->getTitle()); ?></a>
				</h1>
				<div class="grid_4 alpha">
					<?php GexfExplorer($section->getMapById($section->getFrontMap()), 300, 250); ?>
					<p align="center"><a href="map.php?map=<?php echo htmlentities($section->getMapById($section->getFrontMap())->getId()) ?>">More about this map...</a></p>
				</div>
				<div class="grid_5">
					<div class="block">
<?php
			if($article = $section->getArticleById($section->getFrontArticle())){
				$textId = $article->getText()
?>
						<h3><a href="article.php?article=<?php echo htmlentities($article->getId()); ?>"><?php echo htmlentities($article->getTitle()); ?></a></h3>
<?php echo $section->showArticleHtmlContent($article->getId(), false); } ?>
					</div>
				</div>
				<div class="grid_3 omega">
					<div class="block">
<?php
			if($resources = $section->getResources()){
?>
						<h3>Resources</h3>
<?php
			foreach($resources as $resource){
				$document = $section->getDocumentById($resource->getDocument());
				if($resource->isFront()){
?>
						<p>
							<h5><a href="resource.php?resource=<?php echo htmlentities($resource->getId()); ?>"><?php echo htmlentities($document->getName()); ?></a></h5>
							<?php $section->showResourceHtmlContent($resource->getId(), false); ?>
						</p>
<?php
					}
				}
			}
?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
<?php
		}
	}
?>
			
<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>