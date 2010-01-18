<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Explore";
	include("_head.php");
	if(isset($_GET['db']) && $db_id = $_GET['db']){
		$split = preg_split('/_/', $db_id);
		$section_id = $split[0]."_".$split[1];
		$section = $data->getSectionById($section_id);
?>
	<body>
		<div class="container_12">
		
			<?php
				$thispage = "db";
				include("_menu.php");
			?>
			
<?php
	$document = $section->getDocumentById($db_id);
	
	$dbh = null;
	try{
		$dbh = new PDO("sqlite:../data/exploredbs/".$db_id);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
?>
			<div class="grid_12">
				<h2 id="page-heading">Explore <?php echo htmlentities($document->getName()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_5">
				<div class="box">
					<h2>
						<a href="#">Nodes count</a>
					</h2>
					<div class="block">
<?php
	$stmt = $dbh->query("SELECT COUNT(*) FROM nodes");
	$result = $stmt->fetch(PDO::FETCH_NUM);
	$nodesCount = $result[0];
?>
						<h3><?php echo htmlentities($nodesCount); ?> nodes</h3>
					</div>
				</div>
			</div>


			<div class="grid_4">
				<div class="box">
					<h2>
						<a href="#">Edges count</a>
					</h2>
					<div class="block">
<?php
	$stmt = $dbh->query("SELECT COUNT(*) FROM edges");
	$result = $stmt->fetch(PDO::FETCH_NUM);
	$edgesCount = $result[0];
?>
						<h3><?php echo htmlentities($edgesCount); ?> edges</h3>
					</div>
				</div>
			</div>

			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#">Density</a>
					</h2>
					<div class="block">
<?php
	$density = Round(100 * $edgesCount / ($nodesCount * ($nodesCount - 1) * 2), 2);
?>
						<h3><?php echo htmlentities($density); ?> %</h3>
					</div>
				</div>
			</div>

			<div class="clear"></div>
			
			<div class="grid_12">
<?php
	$g = new gRaph($dbh);
	// Partitions, pie charts
	foreach($g->getAttributes() as $attribute){
		$valuesAgregated = $attribute->getValuesAgregated();
		if($attribute->viz == "partition"){
?>
				<div class="box">
					<h2>
						<a href="#"><?php echo htmlentities($attribute->getRewrittenName()); ?></a>
					</h2>
					<div class="block">
						<table class="piecharttable">
							<tr>
								<th>Color</th>
								<th><?php echo htmlentities($attribute->getRewrittenName()); ?></th>
								<th>Nodes Count</th>
							</tr>
<?php
			foreach($valuesAgregated as $attrValue){
				$color = $attrValue->getColor();
				$value = $attrValue->getRewrittenName();
				$link = "explore_subset.php?db=".$db_id."&attribute=".$attribute->id."&value=".$attrValue->value;
?>
							<tr>
								<td><span style="background-color: #<?php echo htmlentities($color); ?>;"><?php echo htmlentities($color); ?></span></td>
								<td><a href="<?php echo $link; ?>"><?php echo htmlentities($value); ?></a></td>
								<td><?php echo htmlentities($attrValue->nodesCount); ?></td>
							</tr>
<?php
			}
?>
						</table>
					</div>
				</div>
<?php
		}
	}
	// Binary partitions
	foreach($g->getAttributes() as $attribute){
		$valuesAgregated = $attribute->getValuesAgregated();
		if($attribute->viz == "bipartition"){
?>
				<div class="box">
					<h2>
						<a href="#"><?php echo htmlentities($attribute->getRewrittenName()); ?></a>
					</h2>
					<div class="block">
						<table class="halfpiecharttable">
							<tr>
								<th>Color</th>
								<th><?php echo htmlentities($attribute->getRewrittenName()); ?></th>
								<th>Nodes Count</th>
							</tr>
<?php
			foreach($valuesAgregated as $attrValue){
				$color = $attrValue->getColor();
				$value = $attrValue->getRewrittenName();
				$link = "explore_subset.php?db=".$db_id."&attribute=".$attribute->id."&value=".$attrValue->value;
?>
							<tr>
								<td><span style="background-color: #<?php echo htmlentities($color); ?>;"><?php echo htmlentities($color); ?></span></td>
								<td><a href="<?php echo $link; ?>"><?php echo htmlentities($value); ?></a></td>
								<td><?php echo htmlentities($attrValue->nodesCount); ?></td>
							</tr>
<?php
			}
?>
						</table>
					</div>
				</div>
<?php
		}	
	}
?>
<!-- Scores -->
<?php
	if(false){//////////////////////
	$g = new gRaph($dbh);
	foreach($g->getAttributes() as $attribute){
		if($attribute->type=="float"){
?>
				<div class="box">
					<h2>
						<a href="#"><?php echo htmlentities($attribute->name); ?></a>
					</h2>
					<div class="block">
						<table class="scoretable">
							<tr>
								<th>Color</th>
								<th><?php echo htmlentities($attribute->name); ?></th>
								<th>Node Id</th>
								<th>Node Label</th>
							</tr>
<?php
			foreach($attribute->getValues() as $attrValue){
?>
							<tr>
								<td><span style="background-color: #<?php echo htmlentities($attrValue->getColor()); ?>;"><?php echo htmlentities($attrValue->getColor()); ?></span></td>
								<td><a href="#"><?php echo htmlentities($attrValue->value); ?></a></td>
								<td><?php echo htmlentities($attrValue->nodeId); ?></td>
								<td><?php echo htmlentities($attrValue->nodeLabel); ?></td>
							</tr>
<?php
			}
?>
						</table>
					</div>
				</div>
<?php
		}
	}
	}//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
?>
<div class="box">
					<h2>
						<a href="#" class="hidden">Nodes list</a>
					</h2>
					<div class="block">
						<ul>
<?php
	$stmt = $dbh->query("SELECT id, importid, label FROM nodes");
	foreach($stmt as $row){
		$id = $row[0];
		$importid = $row[1];
		$label = $row[2];
?>
						<li><?php echo htmlentities($label); ?></li>
<?php
	}
?>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="clear"></div>
			
<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
		<script type="text/javascript" src="../js/statsviz.js"></script>
		<script>
window.onload=function(){
	initStatsViz();
}
		</script>
	</body>
<?php } ?>
</html>