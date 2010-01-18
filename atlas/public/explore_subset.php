<?php
	include("../engine/engine.php");
	include("_inithtml.php");
	$title = "Atlas &mdash; Explore";
	include("_head.php");
	if(isset($_GET['db']) && isset($_GET['attribute']) && isset($_GET['value']) && $db_id = $_GET['db']){
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
	
	$g = new gRaph($dbh);
	if($attribute = $g->getAttributeById($_GET['attribute'])){
		if($avaSubset = $attribute->getValueAgregated($_GET['value'])){
			$attributeName = $attribute->name;
			$attributeRewrittenName = $attribute->getRewrittenName();
			$valueRewritten = $avaSubset->getRewrittenName();
			$color = $avaSubset->getColor();
			$nodes = $avaSubset->getNodes();
?>
			<div class="grid_12">
				<h2 id="page-heading">Nodes in <?php echo htmlentities($document->getName()); ?> <br/> where "<?php echo htmlentities($attributeRewrittenName); ?>" is "<?php echo htmlentities($valueRewritten); ?>"</h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_5">
				<div class="box">
					<h2>
						<a href="#" id="toggle-articles">Nodes count</a>
					</h2>
					<div class="block">
						<h3><?php echo htmlentities(sizeof($nodes)); ?> nodes</h3>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
			<div class="grid_12">
<?php
			// Connectivity (tower chart)
?>
				<div class="box">
					<h2>
						<a href="#">Connectivity with other categories in "<?php echo htmlentities($attributeRewrittenName); ?>"</a>
					</h2>
					<div class="block">
						<div id="towerChartDiv"></div>
						<br/>
						<script>
							var towerCharts = [{
								id:"towerChartDiv",
								category:{
									name:"<?php echo jsdefend($valueRewritten); ?>",
									color:"<?php echo jsdefend($color); ?>",
									nodesCount:<?php echo jsdefend(count($nodes)); ?>,
									internalLinks:<?php echo jsdefend($avaSubset->getInternalLinksCount()); ?>
								},
								categories:[
<?php
			$maxNodesCount = count($nodes);
			foreach($attribute->getValuesAgregated() as $secondAva){
				if($avaSubset->value != $secondAva->value){
					$maxNodesCount = max($maxNodesCount, $secondAva->getNodesCount());
?>
									{
										name:"<?php echo jsdefend($secondAva->getRewrittenName()); ?>",
										color:"<?php echo jsdefend($secondAva->getColor()); ?>",
										linksToMain:<?php echo jsdefend($avaSubset->getLinksCountFromValue($secondAva->value)); ?>,
										linksFromMain:<?php echo jsdefend($avaSubset->getLinksCountToValue($secondAva->value)); ?>,
										link:"<?php echo "explore_subset.php?db=".$db_id."&attribute=".$secondAva->attribute->id."&value=".urlencode($secondAva->value); ?>"
									},
<?php
				}
			}
?>
								],
								maxNodesCount:<?php echo jsdefend($maxNodesCount); ?>
							}];
						</script>
					</div>
				</div>
<?php
			// Pie charts
			foreach($g->getAttributes() as $secondAttribute){
				$valuesAgregated = $secondAttribute->getValuesAgregatedByPriorAttribute($attribute->id, $avaSubset->value);
				if($secondAttribute!=$attribute && $secondAttribute->viz == "partition"){
					$sndAttrRewrittenName = $secondAttribute->getRewrittenName();
?>				
				<div class="box">
					<h2>
						<a href="#"><?php echo htmlentities($sndAttrRewrittenName); ?> for "<?php echo htmlentities($valueRewritten); ?>"</a>
					</h2>
					<div class="block">
						<table class="piecharttable">
							<tr>
								<th>Color</th>
								<th><?php echo htmlentities($sndAttrRewrittenName); ?></th>
								<th>Nodes Count</th>
							</tr>
<?php
					foreach($valuesAgregated as $attrValue){
						$color = $attrValue->getColor();
						$value = $attrValue->getRewrittenName();
						$link = "explore_subset.php?db=".$db_id."&attribute=".$secondAttribute->id."&value=".$attrValue->value;
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
		}
	}
?>
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