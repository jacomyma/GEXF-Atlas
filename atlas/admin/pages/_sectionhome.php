<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Home &mdash; eDiasporas Atlas : Administration";
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
				<h2 id="page-heading">Welcome to the section: <?php echo htmlentities($section->getTitle()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_6">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Set title</a>
					</h2>
					<div class="block">
						<form action="?page=sectionhome&section=<?php echo htmlentities($section->getId()); ?>" method="post">
							<fieldset>
								<legend>Title</legend>
								<p>
									<input type="text" name="title" value="<?php echo htmlentities($section->getTitle()); ?>" />
								</p>
								<input type="hidden" name="action" value="setTitle"/>
								<input type="submit" value="Apply Change" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Publish</a>
					</h2>
					<div class="block">
						<form action="?page=sectionhome&section=<?php echo htmlentities($section->getId()); ?>" method="post">
							<fieldset>
								<legend>Publication Status</legend>
								<input type="radio" name="publicationStatus" value="true" <?php echo ($section->isPublished())?('checked="true"'):(''); ?>/> Published<br/>
								<input type="radio" name="publicationStatus" value="false" <?php echo ($section->isPublished())?(''):('checked="true"'); ?>/> Unpublished<br/><br/>
								<input type="hidden" name="action" value="setPublicationStatus"/>
								<input type="submit" value="Apply Change" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Delete Section</a>
					</h2>
					<div class="block">
						<form action="?page=sections" method="post">
							<fieldset>
								<legend>Delete ?</legend>
								<p>
									<SELECT name="delete">
										<OPTION VALUE="n">Please confirm...</OPTION>
										<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
									</SELECT>
								</p>
								<input type="hidden" name="action" value="removeSection"/>
								<input type="hidden" name="section" value="<?php echo htmlentities($section->getId()); ?>"/>
								<input type="submit" value="Delete Section" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="clear"></div>

			<div class="grid_12">
				<h2 id="page-heading">Summary</h2>
			</div>
			<div class="clear"></div>

			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Documents</a>
					</h2>
					<div class="block">
						Work in progress
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Texts</a>
					</h2>
					<div class="block">
						Work in progress
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Structure</a>
					</h2>
					<div class="block">
						Work in progress
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">Browsable Graphs</a>
					</h2>
					<div class="block">
						Work in progress
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
			<div class="grid_12">
				<h2 id="page-heading">What do you want to do ?</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_12">
				<div class="box" id="kwick-box">
					<h2>Administration Steps</h2>
					<div id="kwick">
						<ul class="kwicks">
							<li>
								<a class="kwick one" href="?page=documents&section=<?php echo htmlentities($section->getId()); ?>">
									<span>1. Upload Documents</span>
									<p>
										Choose images, PDF, source files and other documents related to this section, and upload them.
									</p>
									<p>
										&gt; Go to Documents
									</p>
								</a>
							</li>
							<li>
								<a class="kwick two" href="?page=texts&section=<?php echo htmlentities($section->getId()); ?>">
									<span>2. Write Texts</span>
									<p>
										Write and edit online texts to explain different concerns about this section.
									</p>
									<p>
										&gt; Go to Texts
									</p>
								</a>
							</li>
							<li>
								<a class="kwick three" href="?page=setcontents&section=<?php echo htmlentities($section->getId()); ?>">
									<span>3. Design content/structure</span>
									<p>
										Set which documents and articles appear where and how. Organize information.
									</p>
									<p>
										&gt; Go to Set Contents
									</p>
								</a>
							</li>
							<li>

								<a class="kwick four" href="?page=managegraphs&section=<?php echo htmlentities($section->getId()); ?>">
									<span>4. Publish Graph Data</span>
									<p>
										Upload and explain the main graph of this section so that visitors can browse it easily.
									</p>
									<p>
										&gt; Go to Manage Browsable Graphs
									</p>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>