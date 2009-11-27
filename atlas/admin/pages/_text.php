<?php
	include("_inithtml.php");
	$title = $section->getTitle()." : Edit Text &mdash; eDiasporas Atlas : Administration";
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
				<h2 id="page-heading">Edit this text: <?php echo htmlentities($text->getTitle()); ?></h2>
			</div>
			<div class="clear"></div>
			
			<div class="grid_9">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Text</a>
					</h2>
					<div class="block">
						<form action="?page=text&section=<?php echo htmlentities($section->getId()); ?>&text=<?php echo htmlentities($text->getId()); ?>" method="post">
							<fieldset>
								<legend>Change title</legend>
								<p>
									<input type="text" name="title" value="<?php echo htmlentities($text->getTitle()); ?>" />
								</p>
								<input type="hidden" name="action" value="setTitle"/>
								<input type="submit" value="Apply Change" />
							</fieldset>
						</form>
						<form action="?page=text&section=<?php echo htmlentities($section->getId()); ?>&text=<?php echo htmlentities($text->getId()); ?>" method="post">
							<fieldset>
								<legend>Edit content</legend>
								<p>
									<textarea name="content" style="width:600px;height:400px;"><?php echo htmlentities($text->getContent()); ?></textarea>
								</p>
								<input type="hidden" name="action" value="setContent"/>
								<input type="submit" value="Apply Changes" />
							</fieldset>
						</form>
						<h4>Help: Text design markups</h4>
						<table>
							<thead>
								<tr>
									<th>Markup</th>
									<th>Result</th>
									<th>Syntax example</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<th>B</th>
									<td><b>Bold</b></td>
									<td>&lt;b&gt; Text in bold &lt;/b&gt;</td>
								</tr>
								<tr>
									<th>I</th>
									<td><i>Italic</i></td>
									<td>&lt;i&gt; Text in italic &lt;/i&gt;</td>
								</tr>
								<tr class="odd">
									<th>A</th>
									<td><a href="http://webatlas.fr">Hypertext Link</a></td>
									<td>&lt;a href="http://webatlas.fr"&gt; Clickable text &lt;/a&gt;</td>
								</tr>
								<tr>
									<th>IMG</th>
									<td>Puts an image (linked by the 'src' URL)</td>
									<td>&lt;img src="http://webatlas.fr/myImage.gif"/&gt;</td>
								</tr>
								<tr class="odd">
									<th>H1</th>
									<td><h3>Biggest title</h3></td>
									<td>&lt;h1&gt; My Title &lt;/h1&gt;</td>
								</tr>
								<tr>
									<th>H2</th>
									<td><h4>Big title</h4></td>
									<td>&lt;h2&gt; My Title &lt;/h2&gt;</td>
								</tr>
								<tr class="odd">
									<th>H3</th>
									<td><h5>Medium title</h5></td>
									<td>&lt;h3&gt; My Title &lt;/h3&gt;</td>
								</tr>
								<tr>
									<th>H4</th>
									<td><h6>Small title</h6></td>
									<td>&lt;h4&gt; My Title &lt;/h4&gt;</td>
								</tr>
								<tr class="odd">
									<th>UL + LI</th>
									<td>List :<ul><li>Item</li><li>Item</li></ul></td>
									<td>&lt;ul&gt; &lt;li&gt;Item&lt;/li&gt; &lt;li&gt;Item&lt;/li&gt; &lt;/ul&gt;</td>
								</tr>
								<tr>
									<th>READMORE</th>
									<td>Stops the preview of the text and shows:<br/>"<b>Read more...</b>"<br/>This links to the full article</td>
									<td>&lt;readmore/&gt;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="grid_3">
				<div class="box">
					<h2>
						<a href="#" id="toggle-accordion">Delete text</a>
					</h2>
					<p></p>
					<form action="?page=texts&section=<?php echo htmlentities($section->getId()); ?>" method="post">
						<fieldset>
							<legend>Delete ?</legend>
							<p>
								<SELECT name="delete">
									<OPTION VALUE="n">Please confirm...</OPTION>
									<OPTION VALUE="y">Delete (NO UNDO)</OPTION>
								</SELECT>
							</p>
							<input type="hidden" name="action" value="removeText"/>
							<input type="hidden" name="text" value="<?php echo htmlentities($text->getId()); ?>"/>
							<input type="submit" value="Delete Text" />
						</fieldset>
					</form>
				</div>
			</div>
			<div class="clear"></div>
			
			<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>