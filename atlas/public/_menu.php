			<div class="grid_12">
				<ul class="nav main">
					<li>
						<?php showMenuItem("Section Home", "home"); ?>
					</li>
					<li>
						<?php showMenuItem("Maps", "map"); ?>
						<ul>
<?php
	if($maps = $section->getMaps()){
		foreach($maps as $map){
?>
							<li><a href="map.php?map=<?php echo  htmlentities($map->getId());?>"><?php echo htmlentities($map->getTitle()); ?></a></li>
<?php
		}
	}
?>
						</ul>
					</li>
					<li>
						<?php showMenuItem("Articles", "article"); ?>
						<ul>
<?php
	if($articles = $section->getArticles()){
		foreach($articles as $article){
?>
							<li><a href="article.php?article=<?php echo  htmlentities($article->getId());?>"><?php echo htmlentities($article->getTitle()); ?></a></li>
<?php
		}
	}
?>
						</ul>
					</li>
					<li>
						<?php showMenuItem("Resources", "resource"); ?>
						<ul>
<?php
	if($resources = $section->getResources()){
		foreach($resources as $resource){
			$document = $section->getDocumentById($resource->getDocument());
?>
							<li><a href="resource.php?resource=<?php echo  htmlentities($resource->getId());?>"><?php echo htmlentities($document->getName()); ?></a></li>
<?php
		}
	}
?>
						</ul>
					</li>
					<li>
						<?php showMenuItem("Explore", "explore"); ?>
						<ul>
<?php
	if($databases = getPublishedDatabases($section->getId())){
		foreach($databases as $db){
			$document = $section->getDocumentById($db);
?>
							<li><a href="explore.php?db=<?php echo  htmlentities($db);?>"><?php echo htmlentities($document->getName()); ?></a></li>
<?php
		}
	}
?>
						</ul>
					</li>
					<li class="secondary">
						<a href="index.php">Back to the Atlas</a>
					</li>
				</ul>

			</div>
			<div class="clear"></div>
			
<?php
function showMenuItem($menuItem, $page){
	global $thispage, $section;
	if($page == $thispage){
		echo "<span>$menuItem</span>";
	} else if($page=="home"){
		echo '<a href="section.php?section='.$section->getId().'">'.$menuItem.'</a>';
	} else {
		echo '<a href="#">'.$menuItem.'</a>';
	}
}
?>