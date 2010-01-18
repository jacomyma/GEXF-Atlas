<?php

function createDatabase($dbname){
	// NB : php.ini check that extension=php_pdo_sqlite.dll is uncommented
	$dbh = null;
	try{
		$dbh = new PDO("sqlite:../data/exploredbs/".$dbname);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
	return $dbh;
}

function deleteDatabase($dbname){
	return unlink("../data/exploredbs/".$dbname);
}

function resetDatabase($dbname){
	try{
		$dbh = new PDO("sqlite:../data/exploredbs/".$dbname);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
	$dbh->query("DROP TABLE IF EXISTS attributes");
	$dbh->query("DROP TABLE IF EXISTS nodes");
	$dbh->query("DROP TABLE IF EXISTS nodevalues");
	$dbh->query("DROP TABLE IF EXISTS edges");
	$dbh->query("DROP TABLE IF EXISTS rewriting");
	return $dbh;
}

function checkExistingDatabase($dbname){
	return file_exists("../data/exploredbs/".$dbname);
}

function getPublishedDatabases($sectionId){
	global $data;
	$result = array();
	$section = $data->getSectionById($sectionId);
	$documents = $section->getDocuments();
	foreach($documents as $document){
		if(checkExistingDatabase($document->getId())){
			array_push($result, $document->getId());
		}
	}
	return $result;
}

function parseGEXF($filename, $dbh){
	try {
		// Create Database
		/*** set the error reporting attribute ***/
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Attributes
		$dbh->query("CREATE TABLE attributes (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(200) NOT NULL, type VARCHAR(20) NOT NULL, viztype VARCHAR(50))");
		
		// Nodes
		$dbh->query("CREATE TABLE nodes (id INTEGER PRIMARY KEY AUTOINCREMENT, importid VARCHAR(50), label VARCHAR(500))");
		
		// Nodes Attributes
		$dbh->query("CREATE TABLE nodevalues (id INTEGER PRIMARY KEY AUTOINCREMENT, node INT, attribute INT, value VARCHAR(500))");
		
		// Edges
		$dbh->query("CREATE TABLE edges (id INTEGER PRIMARY KEY AUTOINCREMENT, nodefrom INT, nodeto INT)");	

		// Rewriting
		$dbh->query("CREATE TABLE rewriting (source VARCHAR(200) PRIMARY KEY, result VARCHAR(200), color VARCHAR(10))");	
		
	} catch(PDOException $e){
		echo $e->getMessage();
    }
	
	// Parse file
	$gexfDom = new DomDocument();
	$gexfDom->load($filename);
	//echo "<br/>".$gexfDom->documentURI;
	$xp = new DOMXPath($gexfDom);
	
	$dbh->beginTransaction();
	
	// Nodes attributes
	$xp->registerNamespace('gexf', 'http://www.gephi.org/gexf');
	$attributes = $xp->query("//gexf:graph[1]/gexf:attributes[@class='node']/gexf:attribute");
	foreach($attributes as $attribute){
		$query = "INSERT INTO 'attributes' ('name', 'type', 'viztype') VALUES ";
		$query .= "('".$attribute->getAttribute('title')."', '".$attribute->getAttribute('type')."', 'unpublished')";
		$dbh->exec($query);
	}
	
	// Nodes
	$nodes = $xp->query("//gexf:graph[1]/gexf:nodes/gexf:node");
	foreach($nodes as $node){
		$query = "INSERT INTO 'nodes' ('importid', 'label') VALUES ";
		$query .= "('".$node->getAttribute('id')."', '".str_replace(array("\\", "'"), array("\\\\", "\\'"), $node->getAttribute('label'))."')";
		$dbh->exec($query);
		$values = $xp->query("//gexf:graph[1]/gexf:nodes/gexf:node[@id='".$node->getAttribute('id')."']/gexf:attvalues/gexf:attvalue");
		foreach($values as $value){
			$query = "INSERT INTO 'nodevalues' ('node', 'attribute', 'value') ";
			$query .= "SELECT id, '".($value->getAttribute('id')+1)."', '".defend($value->getAttribute('value'))."' FROM nodes WHERE importId='".$node->getAttribute('id')."' ";
			$dbh->exec($query);
		}
	}
	
	// Edges
	$edges = $xp->query("//gexf:graph[1]/gexf:edges/gexf:edge");
	foreach($edges as $edge){
		$query = "INSERT INTO 'edges' ('nodefrom', 'nodeto') ";
		$query .= "SELECT n1.id, n2.id FROM nodes n1, nodes n2 WHERE n1.importid='".$edge->getAttribute('source')."' AND n2.importid='".$edge->getAttribute('target')."'";
		$dbh->exec($query);
	}
	$dbh->commit();
	
}

//////////////////////////////////////
// Classes for db object management
//////////////////////////////////////

class gElement{
	protected $dbh;
	public function __construct($databaseHandler){
		$this->dbh = $databaseHandler;
	}
}

class gRaph extends gElement{
	public function getAttributes(){
		$result = array();
		$stmt = $this->dbh->query("SELECT id, name, type, viztype FROM attributes");
		foreach($stmt as $row){
			$id = $row[0];
			$name = $row[1];
			$type = $row[2];
			$viz = $row[3];
			$attribute = new gAttribute($this->dbh, $id, $name, $type, $viz);
			array_push($result, $attribute);
		}
		return $result;
	}
	
	public function getAttributeById($id){
		$stmt = $this->dbh->query("SELECT id, name, type, viztype FROM attributes WHERE id='".$id."'");
		foreach($stmt as $row){
			//$id = $row[0];
			$name = $row[1];
			$type = $row[2];
			$viz = $row[3];
			$attribute = new gAttribute($this->dbh, $id, $name, $type, $viz);
			return $attribute;
		}
		return null;
	}
	
	public function getNodes(){
		$result = array();
		$stmt = $this->dbh->query("SELECT id, label FROM nodes");
		foreach($stmt as $row){
			$id = $row[0];
			$label = $row[1];
			$node = new gNode($this->dbh, $id, $label);
			array_push($result, $node);
		}
		return $result;
	}
	
	public function pushRewriting($source, $rewrite, $color){
		try{
			$this->dbh->query("INSERT OR REPLACE INTO rewriting (source, result, color) VALUES ('".defend($source)."', '".defend($rewrite)."', '".defend($color)."')");
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}

class gNode extends gElement{
	public $id;
	public $label;
	public function __construct($databaseHandler, $id, $label){
		$this->dbh = $databaseHandler;
		$this->id = $id;
		$this->label = $label;
	}
}

class gEdge extends gElement{

}

class gAttribute extends gElement{
	public $id;
	public $name;
	public $type;
	public $viz;
	public function __construct($databaseHandler, $id, $name, $type, $viz){
		$this->dbh = $databaseHandler;
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
		$this->viz = $viz;
	}

	public function getValuesAgregated(){
		$result = array();
		$stmt = $this->dbh->query("SELECT nv.value, COUNT(nv.node) FROM nodevalues nv WHERE nv.attribute='".defend($this->id)."' GROUP BY nv.value");
		foreach($stmt as $row){
			$av = new gAttributeValueAgregated($this->dbh, $this, $row[0], $row[1]);
			array_push($result, $av);
		}
		return $result;
	}
	
	public function getValuesAgregatedByPriorAttribute($priorAttributeId, $priorValue){
		$result = array();
		$stmt = $this->dbh->query("SELECT nv.value, COUNT(nv.node) FROM nodevalues nv, nodevalues nvprior WHERE nv.attribute='".defend($this->id)."' AND nv.node=nvprior.node AND nvprior.attribute='".defend($priorAttributeId)."' AND nvprior.value='".defend($priorValue)."' GROUP BY nv.value");
		foreach($stmt as $row){
			$av = new gAttributeValueAgregated($this->dbh, $this, $row[0], $row[1]);
			array_push($result, $av);
		}
		return $result;
	}
	
	public function getValues(){
		$result = array();
		$stmt = $this->dbh->query("SELECT nv.value, nv.node, n.label FROM nodevalues nv, nodes n WHERE nv.attribute='".defend($this->id)."' AND nv.node=n.id");
		foreach($stmt as $row){
			$av = new gAttributeValue($this->dbh, $this, $row[0], $row[1], $row[2]);
			array_push($result, $av);
		}
		return $result;
	}
	
	public function getValueAgregated($value){
		$stmt = $this->dbh->query("SELECT COUNT(nv.node) FROM nodevalues nv WHERE nv.attribute='".defend($this->id)."' AND nv.value='".defend($value)."' GROUP BY nv.value");
		foreach($stmt as $row){
			return new gAttributeValueAgregated($this->dbh, $this, $value, $row[0]);
		}
		return null;
	}
	
	public function setViz($viz){
		$this->dbh->query("UPDATE attributes SET viztype='".defend($viz)."' WHERE id='".defend($this->id)."'");
	}
	
	public function getRewrittenName(){
		try{
			$stmt = $this->dbh->query("SELECT result FROM rewriting WHERE source='".defend($this->name)."'");
			foreach($stmt as $row){
				return $row[0];
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		return $this->name;
	}
	
	public function setRewrittenName($name){
		try{
			$this->dbh->query("INSERT OR REPLACE INTO rewriting (source, result, color) VALUES ('".defend($this->name)."', '".defend($name)."', '666666')");
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}

class gAttributeValueAgregated extends gElement{
	public $attribute;
	public $value;
	public $nodesCount;
	
	public function __construct($databaseHandler, $attribute, $value, $nodesCount){
		$this->dbh = $databaseHandler;
		$this->attribute = $attribute;
		$this->value = $value;
		$this->nodesCount = $nodesCount;
	}
	
	public function getColor(){
		try{
			$stmt = $this->dbh->query("SELECT color FROM rewriting WHERE source='".defend($this->value)."'");
			foreach($stmt as $row){
				return $row[0];
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		return stringToColor($this->value);
	}
	
	public function getNodes(){
		$result = array();
		$stmt = $this->dbh->query("SELECT nv.node, n.label FROM nodevalues nv, nodes n WHERE nv.attribute='".defend($this->attribute->id)."' AND nv.value='".defend($this->value)."' AND nv.node=n.id");
		foreach($stmt as $row){
			$n = new gNode($this->dbh, $this, $row[0], $row[1]);
			array_push($result, $n);
		}
		return $result;
	}
	
	public function getNodesCount(){
		$stmt = $this->dbh->query("SELECT count(nv.node) FROM nodevalues nv, nodes n WHERE nv.attribute='".defend($this->attribute->id)."' AND nv.value='".defend($this->value)."' AND nv.node=n.id");
		foreach($stmt as $row){
			return($row[0]);
		}
		return -1;
	}
	
	public function getRewrittenName(){
		try{
			$stmt = $this->dbh->query("SELECT result FROM rewriting WHERE source='".defend($this->value)."'");
			foreach($stmt as $row){
				return $row[0];
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		return $this->value;
	}
	
	public function getInternalLinksCount(){
		$stmt = $this->dbh->query("SELECT COUNT(*) FROM nodevalues AS nv1 CROSS JOIN edges AS e CROSS JOIN nodevalues AS nv2 WHERE nv1.attribute='".defend($this->attribute->id)."' AND nv1.value='".defend($this->value)."' AND nv2.attribute='".defend($this->attribute->id)."' AND nv2.value='".defend($this->value)."' AND nv1.node=e.nodefrom AND e.nodeto=nv2.node");
		foreach($stmt as $row){
			return($row[0]);
		}
		return -1;		
	}
	
	public function getLinksCountToValue($value){
		$stmt = $this->dbh->query("SELECT COUNT(*) FROM nodevalues AS nv1 CROSS JOIN edges AS e CROSS JOIN nodevalues AS nv2 WHERE nv1.attribute='".defend($this->attribute->id)."' AND nv1.value='".defend($this->value)."' AND nv2.attribute='".defend($this->attribute->id)."' AND nv2.value='".defend($value)."' AND nv1.node=e.nodefrom AND e.nodeto=nv2.node");
		foreach($stmt as $row){
			return($row[0]);
		}
		return -1;		
	}

	public function getLinksCountFromValue($value){
		$stmt = $this->dbh->query("SELECT COUNT(*) FROM nodevalues AS nv1 CROSS JOIN edges AS e CROSS JOIN nodevalues AS nv2 WHERE nv1.attribute='".defend($this->attribute->id)."' AND nv1.value='".defend($value)."' AND nv2.attribute='".defend($this->attribute->id)."' AND nv2.value='".defend($this->value)."' AND nv1.node=e.nodefrom AND e.nodeto=nv2.node");
		foreach($stmt as $row){
			return($row[0]);
		}
		return -1;		
	}
	
}

class gAttributeValue extends gElement{
	public $attribute;
	public $value;
	public $nodeId;
	public $nodeLabel;
	
	public function __construct($databaseHandler, $attribute, $value, $nodeId, $nodeLabel){
		$this->dbh = $databaseHandler;
		$this->attribute = $attribute;
		$this->value = $value;
		$this->nodeId = $nodeId;
		$this->nodeLabel = $nodeLabel;
	}
	
	public function getColor(){
		return stringToColor($this->nodeLabel);
	}
}

function stringToColor($string){
	$md5 = md5($string);
	$array = str_split($md5, 6);
	return $array[0];
}

function defend($result){
	$result = utf8_decode($result);
	//$result = str_replace("\\", "\\\\", $result);
	$result = str_replace("'", "''", $result);
	return $result;
}

function jsdefend($result){
	$result = utf8_encode($result);
	$result = str_replace("\\", "\\\\", $result);
	$result = str_replace("\"", "\\\"", $result);
	return $result;
}
?>