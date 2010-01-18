<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Manage browsable graphs &mdash; eDiasporas Atlas : Administration";
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
				<h2 id="page-heading">Manage browsable graphs</h2>
			</div>
			<div class="clear"></div>
			
<?php
	$documents = $section->getDocuments();
	$odd = true;
	
	foreach($documents as $document){
		if($document->getType() == 'GEXF'){
			if(checkExistingDatabase($document->getId())){
				$dbh = null;
				try{
					$dbh = new PDO("sqlite:../data/exploredbs/".$document->getId());
					$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch(PDOException $e) {
					echo $e->getMessage();
				}
?>
			<div class="grid_4">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion"><?php echo htmlentities($document->getName()); ?></a>
					</h2>
					<div class="block">
						<form action="?page=managegraphs&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
							<fieldset>
								<legend>Reset exploration database</legend>
								<p>
									<span><?php echo htmlentities($document->getFile()); ?></span>
								</p>
								<input type="hidden" name="action" value="updateDatabase"/>
								<input type="hidden" name="document" value="<?php echo htmlentities($document->getId()); ?>"/>
								<input type="submit" value="Reset Database" />
							</fieldset>
						</form>
						<form action="?page=managegraphs&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
							<fieldset>
								<legend>Delete exploration database ?</legend>
								<p>
									<span><?php echo htmlentities($document->getFile()); ?></span>
								</p>
								<p>
									<SELECT name="delete">
										<OPTION VALUE="n">Please confirm...</OPTION>
										<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
									</SELECT>
								</p>
								<input type="hidden" name="action" value="deleteDatabase"/>
								<input type="hidden" name="document" value="<?php echo htmlentities($document->getId()); ?>"/>
								<input type="submit" value="Delete Database" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_8">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Settings (<?php echo htmlentities($document->getName()); ?>)</a>
					</h2>
					<div class="block">
<?php
				$g = new gRaph($dbh);
				foreach($g->getAttributes() as $attribute){
					$avas = $attribute->getValuesAgregated();
					$rwname = $attribute->getRewrittenName();
?>
						<div style="margin-top:40px;">
<?php
				if($attribute->viz == "unpublished"){
?>
							<h3 style="color:#666;"><?php echo htmlentities($rwname); ?></h3>
<?php
				} else {
?>
							<h3 style="color:#000;"><?php echo htmlentities($rwname); ?></h3>
<?php
				}
?>
							<form action="?page=managegraphs&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
								<fieldset>
									<legend>Visualization Settings</legend>
									<input type="hidden" name="action" value="changeViz"/>
									<input type="hidden" name="document" value="<?php echo htmlentities($document->getId()); ?>"/>
									<input type="hidden" name="attribute" value="<?php echo htmlentities($attribute->id); ?>"/>
									<table>
										<tr>
											<td>
												<b>Raw Name</b>
											</td>
											<td>
												<?php echo htmlentities($attribute->name); ?>
											</td>
										</tr><tr>
											<td>
												<b>Type</b>
											</td>
											<td>
												<?php echo htmlentities($attribute->type); ?>
											</td>
										</tr><tr>
											<td>
												<b>Values</b>
											</td>
											<td>
												<?php echo htmlentities(sizeof($avas)); ?>
											</td>
										</tr><tr>
											<td>
												<b>Visualization</b>
											</td>
											<td>
												<select name="viz">
<?php
					if($attribute->viz == "unpublished"){$select='selected="true"';}else{$select='';}
?>
													<option value="unpublished" <?php echo htmlentities($select); ?>>None (Unpublished)</option>
<?php
					if($attribute->viz == "bipartition"){$select='selected="true"';}else{$select='';}
					if(sizeof($avas)==2){
?>
													<option value="bipartition" <?php echo htmlentities($select); ?>>Bi-partition (ex: Yes/No)</option>
<?php
					}
					if($attribute->viz == "partition"){$select='selected="true"';}else{$select='';}
					if(sizeof($avas)>2){
?>
													<option value="partition" <?php echo htmlentities($select); ?>>Partition / Pie Chart (ex: France/Italy/Germany/Other)</option>
<?php
					}
					if($attribute->viz == "rank"){$select='selected="true"';}else{$select='';}
					if($attribute->type!="string"){
?>
													<option value="rank" <?php echo htmlentities($select); ?>>Ranking / Curve (ex: from 1 to 100)</option>
<?php
					}
?>
												</select>
											</td>
										<tr></tr>
											<td>
												<b>Name Rewriting</b>
											</td><td>
												<input type="text" name="rewritedattributename" value="<?php echo htmlentities($attribute->getRewrittenName()); ?>"  style="width:400px"/>
											</td>
										</tr>
									</table>
									<input type="submit" value="Apply Changes" />
								</fieldset>
							</form>
<?php
					if($attribute->viz == "partition" || $attribute->viz == "bipartition"){
?>
							<form action="?page=managegraphs&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
								<fieldset>
									<legend>Rewrite Values / Set Colors</legend>
									<table>
										<tr>
											<th>Value Raw Name</th>
											<th>Name Rewriting</th>
											<th>Color</th>
										</tr>
<?php
						foreach($avas as $ava){
?>
										<tr>
											<td>
												<?php echo htmlentities($ava->value); ?>
											</td>
											<td>
												<input type="text" name="rewrittenavaname_<?php echo htmlentities(md5($ava->getRewrittenName())); ?>" value="<?php echo htmlentities($ava->getRewrittenName()); ?>"  style="width:200px"/>
											</td>
											<td class="colortd">
												<input type="text" name="rewrittenavacolor_<?php echo htmlentities(md5($ava->getRewrittenName())); ?>" value="<?php echo htmlentities($ava->getColor()); ?>"  style="width:50px"/>
												<span style="background-color:#<?php echo htmlentities($ava->getColor()); ?>;">__</span>
												<span style="background-color:#FFF; color:#<?php echo htmlentities($ava->getColor()); ?>;"><?php echo htmlentities($ava->getColor()); ?></span>
												<input type="hidden" name="value_<?php echo htmlentities(md5($ava->getRewrittenName())); ?>" value="<?php echo htmlentities($ava->value); ?>"/>
											</td>
										</tr>
<?php
						}
?>
									</table>
									<input type="hidden" name="action" value="rewriteavas"/>
									<input type="hidden" name="document" value="<?php echo htmlentities($document->getId()); ?>"/>
									<input type="submit" value="Apply Changes" />
								</fieldset>
							</form>
<?php
					}
?>
						</div>
<?php
				}
?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
<?php		} else { ?>
			<div class="grid_4">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion"><?php echo htmlentities($document->getName()); ?></a>
					</h2>
					<div class="block">
						<form action="?page=managegraphs&section=<?php echo htmlentities($section->getId()); ?>" method='post'>
							<fieldset>
								<legend>Create exploration database</legend>
								<p>
									<span><?php echo htmlentities($document->getFile()); ?></span>
								</p>
								<input type="hidden" name="action" value="createDatabase"/>
								<input type="hidden" name="document" value="<?php echo htmlentities($document->getId()); ?>"/>
								<input type="submit" value="Create Database" />
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="clear"></div>
<?php		} 
		}
	}
?>

			
			<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
		<script>
			var colorIndex = 0;
			var colorPalette = [
				'11766D',
				'410936',
				'A40B54',
				'E46F0A',
				'F0B300',
				'B8A47E',
				'8F9C7A',
				'3E8C73',
				'5C5347',
				'96423B',
				'605063',
				'248F8D',
				'61ADA0',
				'B2D5BA',
				'EFF3CD',
				'E9D18A',
				'C2C29D',
				'C7A68B',
				'C9937F',
				'DE745F',
				'A2ABA5',
				'5D8A99',
				'746A8A',
				'5C5F6E',
				'50525C',
				'5BC793',
				'E6D5A7',
				'EBCD72',
				'FCAD4B',
				'FF8147',
				'49423D',
				'40866A',
				'879D6F',
				'BCB77B',
				'F1D186',
				'5B1D99',
				'0074B4',
				'00B34C',
				'FFD41F',
				'FC6E3D'
			];
			var colorNext = function(){
				var color = colorPalette[colorIndex];
				colorIndex++;
				if(colorIndex >= colorPalette.length){
					colorIndex = 0;
				}
				this.parentNode.childNodes[1].value = color;
				this.parentNode.childNodes[3].setStyle('background-color', '#'+color);
				this.parentNode.childNodes[5].setStyle('color', '#'+color);
				this.parentNode.childNodes[5].firstChild.textContent = color;
			}
			$$('.colortd').each(function(item){
				item.childNodes[3].onmouseover = function () {
					this.setStyle('cursor','pointer');
				}
				item.childNodes[5].onmouseover = function () {
					this.setStyle('cursor','pointer');
				}
				item.childNodes[3].onclick = colorNext;
				item.childNodes[5].onclick = colorNext;
			});
		</script>
	</body>
</html>