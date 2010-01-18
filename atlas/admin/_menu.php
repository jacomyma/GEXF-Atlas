			<div class="grid_12">
				<ul class="nav main">
					<li>
						<?php showMenuItem("Section Home", "sectionhome"); ?>
					</li>
					<li>
						<?php showMenuItem("Documents", "documents"); ?>
					</li>
					<li>
						<?php showMenuItem("Texts", "texts"); ?>
					</li>
					<li>
						<?php showMenuItem("Set Contents", "setcontents"); ?>
					</li>
					<li>
						<?php showMenuItem("Manage Browsable Graphs", "managegraphs"); ?>
					</li>
					<li class="secondary">
						<a href="?page=logout"><?php echo $user; ?>: logout</a>
					</li>
					<li class="secondary">
						<a href="?page=sections">Other Sections</a>
					</li>
				</ul>

			</div>
			<div class="clear"></div>
			
<?php
function showMenuItem($menuItem, $page){
	global $section;
	if($_GET['page'] == $page){
		echo "<span>$menuItem</span>";
	} else {
		echo '<a href="?page='.$page.'&section='.$section->getId().'">'.$menuItem.'</a>';
	}
}
?>