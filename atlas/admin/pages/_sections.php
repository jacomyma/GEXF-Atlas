<?php
	include("_inithtml.php");
	$title = "Choose a Section &mdash; eDiasporas Atlas : Administration";
	include("_head.php");
?>
	<body>
		<div class="container_12">
			<div class="grid_12">
				<h1 id="branding" class="fancy">
					<a href="?page=sections">Admin: Sections</a>
				</h1>
			</div>
			<div class="clear"></div>
			<div class="grid_12">
				<ul class="nav main">
					<li class="secondary">
						<a href="?page=logout"><?php echo htmlentities($user); ?>:logout</a>
					</li>
				</ul>
			</div>
			<div class="clear"></div>
			
			<div class="grid_12">
				<h2 id="page-heading">Choose a section</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_7">
				<div class="box">
					<h2>
						<a href="#">Sections</a>
					</h2>
					<div class="block">
						<table>
							<thead>
								<tr>
									<th>Section</th>
									<th>Published</th>
								</tr>
							</thead>
							<tbody>
<?php
	$sections = $data->getSections();
	$odd = true;
	foreach($sections as $section){
		echo '<tr';
		if($odd){
			echo ' class="odd"';
			$odd = false;
		} else {
			$odd = true;
		}
		echo '>';
		echo '<th><a href="?page=sectionhome&section='.htmlentities($section->getId()).'">'.htmlentities($section->getTitle()).'</a></th>';
		echo '<td>'.(($section->isPublished())?('Yes'):('No')).'</td>';
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
						<a href="#">Create new section</a>
					</h2>
					<div class="block">
						<form action="?page=sections" method='post'>
							<fieldset>
								<legend>New section</legend>
								<p>
									<label>Name: </label>
									<input type="text" name="title" />
									<input type="hidden" name="action" value="addSection"/>
								</p>
								<input type="submit" value="Create" />
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