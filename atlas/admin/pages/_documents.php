<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Documents &mdash; eDiasporas Atlas : Administration";
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
				<h2 id="page-heading">Upload your documents</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_10">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Documents</a>
					</h2>
					<div class="block">
						<table>
							<thead>
								<tr>
									<th>Name</th>
									<th>Type</th>
									<th>Used as</th>
									<th>File</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
<?php
	$documents = $section->getDocuments();
	$odd = true;
	foreach($documents as $document){
		echo '<tr';
		if($odd){
			echo ' class="odd"';
			$odd = false;
		} else {
			$odd = true;
		}
		echo '>';
		echo '<th><a href="?page=document&section='.htmlentities($section->getId()).'&document='.htmlentities($document->getId()).'">'.htmlentities($document->getName()).'</a></th>';
		echo '<td>'.htmlentities($document->getType()).'</td>';
		echo '<td>'.htmlentities($document->isUsedAs()).'</td>';
		echo '<td><a href="'.htmlentities($document->getFile()).'" target="_blank">'.htmlentities($document->getFile()).'</a></td>';
		echo '<td><a href="?page=document&section='.htmlentities($section->getId()).'&document='.htmlentities($document->getId()).'">Edit</a> / <a href="'.htmlentities($document->getFile()).'" target="_blank">Preview</a></td>';
		echo '</tr>';
	}
?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="clear"></div>
				<div class="grid_5 alpha">
					<div class="box">
						<h2>
							<a href="#" id="toggle-accordion">Upload a document</a>
						</h2>
						<p></p>
						<form enctype="multipart/form-data" action="?page=documents&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
							<fieldset>
								<legend>New Document</legend>
								<p>
									Add a document that is <b>on your computer</b>.<br/><b>Restrictions</b>: see list on the right.
								</p>
								<p>
									<span>File</span>
									<input type="file" name="file" />
								</p>
								<p>
									<span>Usual Name</span>
									<input type="text" name="name" value="" />
								</p>
								<input type="hidden" name="MAX_FILE_SIZE" value="12000000"> 
								<input type="hidden" name="action" value="addDocument"/>
								<input type="submit" value="Upload" />
							</fieldset>
						</form>
					</div>
				</div>
				<div class="grid_5 omega">
					<div class="box">
						<h2>
							<a href="#" id="toggle-accordion">Link an uploaded document</a>
						</h2>
						<p></p>
						<form action="?page=documents&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
							<fieldset>
								<legend>New Distant Document</legend>
								<p>
									Add a document that is <b>already stored on a server</b>.<br/>No restriction.
								</p>
								<p>
									<span>Distant File (URL)</span>
									<input type="text" name="link" />
								</p>
								<p>
									<span>Usual Name</span>
									<input type="text" name="name" value="" />
								</p>
								<input type="hidden" name="action" value="addDistantDocument"/>
								<input type="submit" value="Add document" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_2">
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-accordion">Restrictions</a>
					</h2>
					<div class="block" id="articles">
						<div class="first article">
							<p>
								Only these types of files are allowed to upload:
							</p>
							<h5>Office documents</h5>
							<ul>
								<li>PDF</li>
								<li>DOC</li>
								<li>PPT</li>
								<li>ODS</li>
								<li>XLS</li>
								<li>CSV</li>
								<li>TXT</li>
							</ul>
							<h5>Images</h5>
							<ul>
								<li>GIF</li>
								<li>JPG</li>
								<li>PNG</li>
							</ul>
							<h5>Multimedia</h5>
							<ul>
								<li>SWF</li>
								<li>MP3</li>
							</ul>
							<h5>Graphs</h5>
							<ul>
								<li>GEXF</li>
								<li>GDF</li>
								<li>NET</li>
							</ul>
							<h5>Archives</h5>
							<ul>
								<li>ZIP</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
			<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>