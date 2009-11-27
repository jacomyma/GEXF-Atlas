<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Article";
	include("_head.php");
	if(isset($_GET['article']) && $article_id = $_GET['article']){
		$split = preg_split('/_/', $article_id);
		$section_id = $split[0]."_".$split[1];
		$section = $data->getSectionById($section_id);
?>
	<body>
		<div class="container_12">
		
			<?php
				$thispage = "article";
				include("_menu.php");
			?>
			
<?php $article=$section->getArticleById($article_id); ?>
			<div class="grid_12">
				<h2 id="page-heading"><?php echo htmlentities($article->getTitle()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_8">
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Article</a>
					</h2>
					<div class="block">
						<div class="article full first">
							<!--<h4>Blabla</h4>
							<p class="meta">Patati Patata</p>-->
<?php
	echo $section->showArticleHtmlContent($article->getId(), true);
?>
						</div>
					</div>
				</div>
			</div>
						
			<div class="grid_4">
<?php
	$first = true;
	if($relatedDocuments = $article->getRelatedDocuments()){
?>
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Related Resources</a>
					</h2>
					<div class="block" id="articles">
<?php
		foreach($relatedDocuments as $relatedDocument){
			$document = $relatedDocument->getDocument();
			if($first){
				echo '<div class="first article">';
				$first = false;
			} else {
				echo '<div class="article">';
			}
?>
							<a href="<?php echo htmlentities($document->getFile()) ?>"><h3><?php echo htmlentities($document->getName()); ?></h3>
								<p align="center">
									<img src="res/file_icon.png"/>
									<br/>
									<?php echo htmlentities($document->getType()); ?>
								</p>
							</a>
						</div>
<?php
		}
?>
					</div>
				</div>
<?php
	}
?>
			</div>
			<div class="clear"></div>
<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
<?php } ?>
</html>