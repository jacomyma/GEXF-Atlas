<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Set Contents &mdash; eDiasporas Atlas : Administration";
	include("_head.php");
?>
	<body>
		<div class="container_12">
			<div class="grid_12">
				<h1 id="branding" class="fancy">
					<a href="?page=sections">Admin: <?php echo htmlentities($section->getTitle()); ?></a>
				</h1>
			</div>
			<div class="clear"></div>
			
<?php include("_menu.php"); ?>
			
			<div class="grid_12">
				<h2 id="page-heading">Set which document or text fits which part of the structure</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_4">
<?php
	$maps = $section->getMaps();
	foreach($maps as $map){
?>
				<div class="box">
					<h2>
						<a href="#" class="hidden">Map &mdash; <?php echo htmlentities($map->getTitle());?></a>
					</h2>
					<div class="block">
						<h1><?php echo htmlentities($map->getTitle());?></h1>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Settings</legend>
								<p>
									<caption>Title</caption>
									<input type="text" name="title" value="<?php echo htmlentities($map->getTitle()); ?>" />
								</p>
								<p>
									<caption>Source (GEXF)</caption>
									<select name="document">
										<option value="">None &mdash; Select Document... (required)</option>
<?php
		$documents = $section->getDocuments();
		foreach($documents as $document){
			$type = $document->getType();
			if($type=="GEXF"){
				echo '<option value="'.htmlentities($document->getId()).'" '.(($map->getDocument()==$document->getId())?('selected="true"'):('')).'>'.htmlentities($document->getName()).'</option>';
			}
		}
?>
									</select>
								</p>
								<p>
									<caption>Legend (GIF, JPG, PNG)</caption>
									<select name="legend">
										<option value="">None &mdash; Select Document...</option>
<?php
		$documents = $section->getDocuments();
		foreach($documents as $document){
			$type = $document->getType();
			if($type=="PNG" || $type=="GIF" || $type=="JPG"){
				echo '<option value="'.htmlentities($document->getId()).'" '.(($map->getLegend()==$document->getId())?('selected="true"'):('')).'>'.htmlentities($document->getName()).'</option>';
			}
		}
?>
									</select>
								</p>
								<p>
									<caption>Downloadable Document (PDF)</caption>
									<select name="downloadabledocument">
										<option value="">None &mdash; Select Document...</option>
<?php
		$documents = $section->getDocuments();
		foreach($documents as $document){
			$type = $document->getType();
			if($type=="PDF"){
				echo '<option value="'.htmlentities($document->getId()).'" '.(($map->getDownloadableDocument()==$document->getId())?('selected="true"'):('')).'>'.htmlentities($document->getName()).'</option>';
			}
		}
?>
									</select>
								</p>
								<p>
									<caption>Explanation Text</caption>
									<select name="text">
										<option value="">None &mdash; Select Text...</option>
<?php
		$texts = $section->getTexts();
		foreach($texts as $text){
			echo '<option value="'.htmlentities($text->getId()).'" '.(($map->getText()==$text->getId())?('selected="true"'):('')).'>'.htmlentities($text->getTitle()).'</option>';
		}
?>
									</select>
								</p>
								<input type="checkbox" name="front" value="true" <?php echo (($section->getFrontMap()==$map->getId())?('checked="true"'):(''));?> />Show as Front Map<br/><br/>
								<input type="hidden" name="action" value="changeSettings"/>
								<input type="hidden" name="map" value="<?php echo htmlentities($map->getId());?>"/>
								<input type="submit" value="Apply Changes" />
							</fieldset>
						</form>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Delete Map</legend>
								<p>
									<SELECT name="delete">
										<OPTION VALUE="n">Please confirm...</OPTION>
										<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
									</SELECT>
								</p>
								<input type="hidden" name="action" value="removeMap"/>
								<input type="hidden" name="map" value="<?php echo htmlentities($map->getId());?>"/>
								<input type="submit" value="Delete" />
							</fieldset>
						</form>
					</div>
				</div>
<?php
	}
?>
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">New Map</a>
					</h2>
					<div class="block">
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>New Map</legend>
								<p>
									<span>Title</span>
									<input type="text" name="title" value="" />
								</p>
								<input type="hidden" name="action" value="addMap"/>
								<input type="submit" value="Create Map" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_5">
<?php
	$articles = $section->getArticles();
	foreach($articles as $article){
?>
				<div class="box">
					<h2>
						<a class="hidden" href="#">Article &mdash; <?php echo htmlentities($article->getTitle());?></a>
					</h2>
					<div class="block">
						<h1><?php echo htmlentities($article->getTitle());?></h1>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Settings</legend>
								<p>
									<caption>Title</caption>
									<input type="text" name="title" value="<?php echo htmlentities($article->getTitle()); ?>" />
								</p>
								<p>
									<caption>Text</caption>
									<select name="text">
<?php
		$texts = $section->getTexts();
		foreach($texts as $text){
			echo '<option value="'.htmlentities($text->getId()).'" '.(($article->getText()==$text->getId())?('selected="true"'):('')).'>'.htmlentities($text->getTitle()).'</option>';
		}
?>
									</select>
								</p>
								<input type="checkbox" name="front" value="true" <?php echo (($section->getFrontArticle()==$article->getId())?('checked="true"'):(''));?> />Show as Front Article<br/><br/>
								<input type="hidden" name="action" value="changeSettings"/>
								<input type="hidden" name="article" value="<?php echo htmlentities($article->getId());?>"/>
								<input type="submit" value="Apply Changes" />
							</fieldset>
						</form>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Related Documents</legend>
								<table>
<?php
	$relatedDocuments = $article->getRelatedDocuments();
	foreach($relatedDocuments as $relatedDocument){
		$document = $relatedDocument->getDocument();
		echo '<tr><td>'.htmlentities($document->getName()).'</td><td><input type="checkbox" name="delete_'.htmlentities($relatedDocument->getId()).'" value="true" />Remove</td></tr>';
	}
?>
								</table>
								<p>
									<select name="relateddocument">
										<option value="">Select New Related Document...</option>
<?php
		$documents = $section->getDocuments();
		foreach($documents as $document){
			echo '<option value="'.htmlentities($document->getId()).'">'.htmlentities($document->getName()).' ('.htmlentities($document->getType()).')</option>';
		}
?>
									</select>
								</p>
								<input type="hidden" name="action" value="manageRelatedDocuments"/>
								<input type="hidden" name="article" value="<?php echo htmlentities($article->getId());?>"/>
								<input type="submit" value="Apply Changes" />
							</fieldset>
						</form>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Delete Article</legend>
								<p>
									<SELECT name="delete">
										<OPTION VALUE="n">Please confirm...</OPTION>
										<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
									</SELECT>
								</p>
								<input type="hidden" name="action" value="removeArticle"/>
								<input type="hidden" name="article" value="<?php echo htmlentities($article->getId());?>"/>
								<input type="submit" value="Delete" />
							</fieldset>
						</form>
					</div>
				</div>
<?php
	}
?>
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">New Article</a>
					</h2>
					<div class="block">
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>New Article</legend>
								<p>
									<span>Title</span>
									<input type="text" name="title" value="" />
								</p>
								<p>
									<caption>Text</caption>
									<select name="text">
										<option value="">None &mdash; Select Text... (required)</option>
<?php
		$texts = $section->getTexts();
		foreach($texts as $text){
			echo '<option value="'.htmlentities($text->getId()).'" >'.htmlentities($text->getTitle()).'</option>';
		}
?>
									</select>
								</p>
								<input type="hidden" name="action" value="addArticle"/>
								<input type="submit" value="Create Article" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_3">
<?php
	$resources = $section->getResources();
	foreach($resources as $resource){
		$document = $section->getDocumentById($resource->getDocument());
?>
				<div class="box">
					<h2>
						<a class="hidden" href="#">Resource &mdash; <?php echo htmlentities($document->getName());?></a>
					</h2>
					<div class="block">
						<h1><?php echo htmlentities($document->getName()." (".htmlentities($document->getType()).")");?></h1>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Settings</legend>
								<p>
									<caption>Document</caption>
									<select name="document">
										<option value="">None &mdash; Select Document... (required)</option>
<?php
		$documents = $section->getDocuments();
		foreach($documents as $document){
			echo '<option value="'.htmlentities($document->getId()).'" '.(($resource->getDocument()==$document->getId())?('selected="true"'):('')).'>'.htmlentities($document->getName())." (".htmlentities($document->getType()).')</option>';
		}
?>
									</select>
								</p>
								<p>
									<caption>Explanations Text</caption>
									<select name="text">
										<option value="">None &mdash; Select Text...</option>
<?php
		$texts = $section->getTexts();
		foreach($texts as $text){
			echo '<option value="'.htmlentities($text->getId()).'" '.(($resource->getText()==$text->getId())?('selected="true"'):('')).'>'.htmlentities($text->getTitle()).'</option>';
		}
?>
									</select>
								</p>
								<input type="checkbox" name="front" value="true" <?php echo (($resource->isFront())?('checked="true"'):(''));?> />Show as Front Resource<br/><br/>
								<input type="hidden" name="action" value="changeSettings"/>
								<input type="hidden" name="resource" value="<?php echo htmlentities($resource->getId());?>"/>
								<input type="submit" value="Apply Changes" />
							</fieldset>
						</form>
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>Delete Resource</legend>
								<p>
									<SELECT name="delete">
										<OPTION VALUE="n">Please confirm...</OPTION>
										<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
									</SELECT>
								</p>
								<input type="hidden" name="action" value="removeResource"/>
								<input type="hidden" name="resource" value="<?php echo htmlentities($resource->getId());?>"/>
								<input type="submit" value="Delete" />
							</fieldset>
						</form>
					</div>
				</div>
<?php
	}
?>
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">New Resource</a>
					</h2>
					<div class="block">
						<form action="?page=setcontents&section=<?php echo htmlentities($section->getId());?>" method="post">
							<fieldset>
								<legend>New Resource</legend>
								<p>
									<caption>Document</caption>
									<select name="document">
										<option value="">None &mdash; Select Document... (required)</option>
<?php
		$documents = $section->getDocuments();
		foreach($documents as $document){
			echo '<option value="'.htmlentities($document->getId()).'" >'.htmlentities($document->getName()." (".htmlentities($document->getType()).")").'</option>';
		}
?>
									</select>
								</p>
								<input type="hidden" name="action" value="addResource"/>
								<input type="submit" value="Create Resource" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
			<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>