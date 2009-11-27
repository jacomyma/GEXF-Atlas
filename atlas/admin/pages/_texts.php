<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Texts &mdash; eDiasporas Atlas : Administration";
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
				<h2 id="page-heading">Create and Edit texts</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_7">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Texts</a>
					</h2>
					<div class="block">
						<table>
							<thead>
								<tr>
									<th>Title</th>
									<th>Used as</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
<?php
	$texts = $section->getTexts();
	$odd = true;
	foreach($texts as $text){
		echo '<tr';
		if($odd){
			echo ' class="odd"';
			$odd = false;
		} else {
			$odd = true;
		}
		echo '>';
		echo '<th><a href="?page=text&section='.htmlentities($section->getId()).'&text='.htmlentities($text->getId()).'">'.htmlentities($text->getTitle()).'</a></th>';
		echo '<td>'.htmlentities($text->isUsedAs()).'</td>';
		echo '<th><a href="?page=text&section='.htmlentities($section->getId()).'&text='.htmlentities($text->getId()).'">Edit</a></th>';
		echo '</tr>';
	}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="grid_5">
				<div class="box">
					<h2>
						<a href="#" id="toggle-paragraphs">New text</a>
					</h2>
					<div class="block">
						<form action="?page=texts&section=<?php echo htmlentities($section->getId()); ?>" method="post">
							<fieldset>
								<legend>New Text</legend>
								<p>
									<span>Title</span>
									<input type="text" name="title" value="" />
								</p>
								<input type="hidden" name="action" value="addText"/>
								<input type="submit" value="Create text" />
							</fieldset>
						</form>
					</div>
				</div>
			</div><div class="clear"></div>
			
			<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>