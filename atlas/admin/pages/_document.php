<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Edit Document &mdash; eDiasporas Atlas : Administration";
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
				<h2 id="page-heading">Edit this document: <?php echo htmlentities($document->getName()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_4">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Name</a>
					</h2>
					<div class="block">
						<form action="?page=document&section=<?php echo htmlentities($section->getId()); ?>&document=<?php echo htmlentities($document->getId()); ?>" method="post">
							<fieldset>
								<legend>Change usual name</legend>
								<p>
									<span>Usual Name</span>
									<input type="text" name="name" value="<?php echo htmlentities($document->getName()); ?>" />
								</p>
								<input type="hidden" name="action" value="setName"/>
								<input type="submit" value="Apply Change" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_2">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Preview</a>
					</h2>
					<div class="block">
						<a href="<?php echo htmlentities($document->getFile()); ?>" target="_blank">
							<p align="center">
								<img src="res/file_icon.png"/><br/>
								<?php echo htmlentities($document->getType()); ?>
							</p>
						</a>
					</div>
				</div>
			</div>
			<div class="grid_6">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Used As</a>
					</h2>
					<div class="block">
						<p>
							Work In Progress...
						</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
			<div class="grid_4">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Upload a new file</a>
					</h2>
					<div class="block">
						<form enctype="multipart/form-data" action="?page=document&section=<?php echo htmlentities($section->getId()); ?>&document=<?php echo htmlentities($document->getId()); ?>" method="post">
							<fieldset>
								<legend>Upload a new file</legend>
								<p>
									<input type="file" name="file" />
								</p>
								<input type="hidden" name="MAX_FILE_SIZE" value="12000000"> 
								<input type="hidden" name="action" value="setFile"/>
								<input type="submit" value="Upload" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_5">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Replace link</a>
					</h2>
					<div class="block">
						<form action="?page=document&section=<?php echo htmlentities($section->getId()); ?>&document=<?php echo htmlentities($document->getId()); ?>" method="post">
							<fieldset>
								<legend>Link a distant file</legend>
								<p>
									<input type="text" name="link" value="<?php echo htmlentities($document->getFile()); ?>" />
								</p>
								<input type="hidden" name="action" value="setLink"/>
								<input type="submit" value="Update link" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Delete Document</a>
					</h2>
					<div class="block">
						<form action="?page=documents&section=<?php echo htmlentities($section->getId()); ?>" method="post">
							<fieldset>
								<legend>Delete ?</legend>
								<p>
									<SELECT name="delete">
										<OPTION VALUE="n">Please confirm...</OPTION>
										<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
									</SELECT>
								</p>
								<input type="hidden" name="action" value="removeDocument"/>
								<input type="hidden" name="document" value="<?php echo htmlentities($document->getId()); ?>"/>
								<input type="submit" value="Delete Document" />
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