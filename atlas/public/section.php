<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Home";
	include("_head.php");
	if(isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
?>
	<body>
		<div class="container_12">
		
			<?php
				$thispage = "home";
				include("_menu.php");
			?>
			
			<?php include("_sky.php"); ?>
			
			<div class="grid_12">
				<h2 id="page-heading">Welcome</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_4">
				<div class="box articles">
					<h2>
						Maps
					</h2>
					<div class="block" id="articles">
<?php
	$first = true;
	if($maps = $section->getMaps()){
		foreach($maps as $map){
			if($first){
				echo '<div class="first article">';
				$first = false;
			} else {
				echo '<div class="article">';
			}
?>
							<h3><a href="map.php?map=<?php echo htmlentities($map->getId()) ?>"><?php echo htmlentities($map->getTitle()); ?></a></h3>
							<?php GexfExplorer($map, 280, 120); ?>
							<br/>
							<?php echo $section->showMapHtmlContent($map->getId(), false); ?>
							<p/>
						</div>
<?php
		}
	}
?>
					</div>
				</div>
			</div>
			
			<div class="grid_5">
				<div class="box articles">
					<h2>
						<a href="#">Articles</a>
					</h2>
					<div class="block" id="articles">
<?php
	$first = true;
	if($articles = $section->getArticles()){
		foreach($articles as $article){
			if($first){
				echo '<div class="first article">';
				$first = false;
			} else {
				echo '<div class="article">';
			}
?>
							<h3><a href="article.php?article=<?php echo htmlentities($article->getId()) ?>"><?php echo htmlentities($article->getTitle()); ?></a></h3>
							<!--<h4>Blabla</h4>
							<p class="meta">Patati Patata</p>-->
<?php
			if($section->getFrontArticle() != $article->getId()){
				echo $section->showArticleHtmlContent($article->getId(), false);
			}
?>
							<p/>
						</div>
<?php
		}
	}
?>
					</div>
				</div>
			</div>
			
			
			<div class="grid_3">
				<!--
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Explore Data Set</a>
					</h2>
					<div class="block" id="articles">
						<div class="first article">
							<h3>
								<a href="#">Explore </a>
							</h3>
							<h4>Blabla</h4>
							<p class="meta">Patati Patata</p>
						</div>
					</div>
				</div>
				-->
				
<?php
	$databases = getPublishedDatabases($section->getId());
	if(sizeof($databases)>0){
?>
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Explore</a>
					</h2>
					<div class="block" id="articles">
<?php
		$first = true;
		foreach($databases as $db){
			$document = $section->getDocumentById($db);
			if($first){
				echo '<div class="first article">';
				$first = false;
			} else {
				echo '<div class="article">';
			}
?>
							<h3><a href="explore.php?db=<?php echo htmlentities($db) ?>"><?php echo htmlentities($document->getName()); ?></a></h3>
							<p/>
						</div>
<?php
		}
?>
					</div>
				</div>
<?php
	}
?>
				
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Resources</a>
					</h2>
					<div class="block" id="articles">
<?php
	$first = true;
	if($resources = $section->getResources()){
		foreach($resources as $resource){
			$document = $section->getDocumentById($resource->getDocument());
			if($first){
				echo '<div class="first article">';
				$first = false;
			} else {
				echo '<div class="article">';
			}
?>
							<h3><a href="resource.php?resource=<?php echo htmlentities($resource->getId()) ?>"><?php echo htmlentities($document->getName()); ?></a></h3>
							<!--<h4>Blabla</h4>
							<p class="meta">Patati Patata</p>-->
							<?php echo $section->showResourceHtmlContent($resource->getId(), false); ?>
							<p/>
						</div>
<?php
		}
	}
?>
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