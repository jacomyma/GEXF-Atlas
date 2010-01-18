<?php

// EZ COMPONENTS : INIT - /!\ Edit Path in __config.php to make it work !!!
include("_initEZC.php");

include("graphdbviews.php");

// Fucking Magic Quotes...
if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

//
// Model : accessors and classes
//

// NB : template for the generation of the XML :
// We have a hierarchy of siteElements :
// root
//   `- section I
//         `- document I.A
//               `- subText I.A.1
//               `- subText I.A.2
//                    `- etc. etc...
//         `- document I.B
//               `- ...
//         `- article I.A
//         `- ...
//   `- section II
//         `- document II.A
//         `- ...
//
// We'll represent this as a generic structure in XML, our "template" for DOM construction.
//	...
//	<A>
//      <properties>
//			<P1>...</P1>
//			<P2>...</P2>
//			...
//      </properties>
//		<list type="AA">
//			<AA>...</AA>
//			<AA>...</AA>
//			...
//		</list>
//		<list type="BB">
//			<BB>...</BB>
//			<BB>...</BB>
//			...
//		</list>
//  </A>
//

$structureDomFile = '../data/data.xml';
$dom = null;
regenerate();

class DomAccessor{
	protected $domElement;
	
	protected function getProperty($property){
		$thisElement = $this->domElement;
		$properties = $thisElement->firstChild;
		if($properties != null){
			while($properties->tagName!="properties"){
				$properties = $properties->nextSibling;
				if($properties == null){
					return null;
				}
			}
			$goodProperty = getChildTagnamed($properties, $property);
			if($goodProperty != null){
				return getTextFromNode($goodProperty);
			}
		}
		return null;
	}
	
	protected function getSubElements($elementName){
		$thisElement = $this->domElement;
		$lists = getChildrenTagnamed($thisElement, 'list');
		$subElementsArray = array();
		foreach($lists as $subElementsList){
			if($subElementsList->getAttribute('type')==$elementName){
				$sectionDomNode = $subElementsList->firstChild;
				if($sectionDomNode != null){
					array_push($subElementsArray, $sectionDomNode);
					while($sectionDomNode->nextSibling != null) {
						array_push($subElementsArray, $sectionDomNode->nextSibling);
						$sectionDomNode = $sectionDomNode->nextSibling;
					}
				}
				return $subElementsArray;
			}
		}
		return $subElementsArray;
	}
	
	protected function getDom(){
		global $dom;
		return $dom;
	}
	
	protected function addSubElement($subElementTagname){
		$dom = $this->getDom();
		$thisElement = $this->domElement;
		// Seek the good list node
		$lists = getChildrenTagnamed($thisElement, 'list');
		$found = false;
		foreach($lists as $subElementsList){
			if($subElementsList->getAttribute('type')==$subElementTagname){
				$found = true;
				break;
			}
		}
		if(!$found){
			$subElementsList = $dom->createElement('list');
			$subElementsList->setAttribute('type', $subElementTagname);
			$thisElement->appendChild($subElementsList);
		}
		// add element and return it
		$subElement = $dom->createElement($subElementTagname);
		$subElementsList->appendChild($subElement);
		return $subElement;
	}
	
	protected function setProperty($property, $value){
		$dom = $this->getDom();
		$thisElement = $this->domElement;
		// Seek properties element
		$propertiesFound = false;
		$properties = $thisElement->firstChild;
		while($properties != null){
			if($properties->tagName=="properties"){
				$propertiesFound = true;
				break;
			} else {
				$properties = $properties->nextSibling;
			}
		}
		if(!$propertiesFound){
			$properties = $dom->createElement('properties');
			$thisElement->appendChild($properties);
		}
		// search property and update it
		$searchedProperty = getChildTagnamed($properties, $property);
		if($searchedProperty != null){
			deleteNode($searchedProperty);
		}
		$propertyTN = $dom->createTextNode($value);
		$propertyNode = $dom->createElement($property);
		$propertyNode->appendChild($propertyTN);
		$properties->appendChild($propertyNode);
	}
	
	protected function setId($id){
		$dom = $this->getDom();
		$thisElement = $this->domElement;
		$attr = $dom->createAttribute("xml:id");
		$thisElement->appendChild($attr);
		$tNode = $dom->createTextNode($id);
		$attr->appendChild($tNode);
		$thisElement->setIdAttribute("xml:id", true);
	}
	
	public function getId(){
		$thisElement = $this->domElement;
		return $thisElement->getAttribute("xml:id");
	}
	
	public function saveDOM(){
		global $structureDomFile;
		$dom = $this->getDom();
		$dom->save($structureDomFile);
	}
}

class RootAccessor extends DomAccessor{
	
	public function __construct(){
		$DOM = $this->getDom();
		$this->domElement = $DOM->getElementById('root');
	}
	
	private function getNewSectionId(){
		$currentId = $this->getProperty("current_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_id", $currentId);
		return $currentId;
	}
	
	public function getSections(){
		$sectionElements = $this->getSubElements("section");
		$result = array();
		foreach($sectionElements as $sectionElement){
			array_push($result, new Section($sectionElement));
		}
		return $result;
	}
	
	public function addSection($title){
		$section = new Section($this->addSubElement("section"));
		$section->setId("section_".$this->getNewSectionId());
		$section->setTitle($title);
		$section->setPublished(false);
		$this->saveDOM();
		return $section;
	}
	
	public function removeSection($sectionId){
		$sectionElement = $this->getDom()->getElementById($sectionId);
		$sectionElement->parentNode->removeChild($sectionElement);
		$this->saveDOM();
	}
	
	public function getSectionById($sectionId){
		$sectionElement = $this->getDom()->getElementById($sectionId);
		if($sectionElement!=null)
			return new Section($sectionElement);
		else
			return null;
	}
}

class Section extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getTitle(){
		return utf8_decode($this->getProperty("title"));
    }
	
	public function setTitle($title){
		$this->setProperty("title", $title);
	}

	public function isPublished(){
		return (($this->getProperty("published"))=="true");
	}
	
	public function setPublished($published){
		$this->setProperty("published", (($published)?("true"):("false")));
	}
	
	private function getNewDocumentId(){
		$currentId = $this->getProperty("current_document_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_document_id", $currentId);
		return $currentId;
	}
	
	public function getDocuments(){
		$documentElements = $this->getSubElements("document");
		$result = array();
		foreach($documentElements as $documentElement){
			array_push($result, new Document($documentElement));
		}
		return $result;
	}
	
	public function addDocument($name, $file){
		$document = new Document($this->addSubElement("document"));
		$document->setId($this->getId()."_document_".$this->getNewDocumentId());
		$document->setName($name);
		$document->setFile($file);
		$this->saveDOM();
		return $document;
	}
	
	public function removeDocument($id){
		$documentElement = $this->getDom()->getElementById($id);
		$documentElement->parentNode->removeChild($documentElement);
		$this->saveDOM();
	}
	
	public function getDocumentById($id){
		$documentElement = $this->getDom()->getElementById($id);
		if($documentElement!=null)
			return new Document($documentElement);
		else
			return null;
	}
	
	private function getNewTextId(){
		$currentId = $this->getProperty("current_text_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_text_id", $currentId);
		return $currentId;
	}
	
	public function getTexts(){
		$textElements = $this->getSubElements("text");
		$result = array();
		foreach($textElements as $textElement){
			array_push($result, new Text($textElement));
		}
		return $result;
	}
	
	public function addText($title){
		$text = new Text($this->addSubElement("text"));
		$text->setId($this->getId()."_text_".$this->getNewTextId());
		$text->setTitle($title);
		$this->saveDOM();
		return $text;
	}
	
	public function removeText($id){
		$textElement = $this->getDom()->getElementById($id);
		$textElement->parentNode->removeChild($textElement);
		$this->saveDOM();
	}
	
	public function getTextById($id){
		$textElement = $this->getDom()->getElementById($id);
		if($textElement!=null)
			return new Text($textElement);
		else
			return null;
	}
	
	private function getNewMapId(){
		$currentId = $this->getProperty("current_map_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_map_id", $currentId);
		return $currentId;
	}
	
	public function getMaps(){
		$mapElements = $this->getSubElements("map");
		$result = array();
		foreach($mapElements as $mapElement){
			array_push($result, new Map($mapElement));
		}
		return $result;
	}
	
	public function addMap($title){
		$map = new Map($this->addSubElement("map"));
		$map->setId($this->getId()."_map_".$this->getNewMapId());
		$map->setTitle($title);
		$this->saveDOM();
		return $map;
	}
	
	public function removeMap($id){
		$mapElement = $this->getDom()->getElementById($id);
		$mapElement->parentNode->removeChild($mapElement);
		$this->saveDOM();
	}
	
	public function getMapById($id){
		$mapElement = $this->getDom()->getElementById($id);
		if($mapElement!=null)
			return new Map($mapElement);
		else
			return null;
	}
	
	public function setFrontMap($mapId){
		$this->setProperty('frontmap', $mapId);
	}
	
	public function getFrontMap(){
		return $this->getProperty('frontmap');
	}
	
	private function getNewArticleId(){
		$currentId = $this->getProperty("current_article_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_article_id", $currentId);
		return $currentId;
	}
	
	public function getArticles(){
		$articleElements = $this->getSubElements("article");
		$result = array();
		foreach($articleElements as $articleElement){
			array_push($result, new Article($articleElement));
		}
		return $result;
	}
	
	public function addArticle($title, $textId){
		$article = new Article($this->addSubElement("article"));
		$article->setId($this->getId()."_article_".$this->getNewArticleId());
		$article->setTitle($title);
		$article->setText($textId);
		$this->saveDOM();
		return $article;
	}
	
	public function removeArticle($id){
		$articleElement = $this->getDom()->getElementById($id);
		$articleElement->parentNode->removeChild($articleElement);
		$this->saveDOM();
	}
	
	public function getArticleById($id){
		$articleElement = $this->getDom()->getElementById($id);
		if($articleElement!=null)
			return new Article($articleElement);
		else
			return null;
	}
	
	public function setFrontArticle($articleId){
		$this->setProperty('frontarticle', $articleId);
	}
	
	public function getFrontArticle(){
		return $this->getProperty('frontarticle');
	}
	
	private function getNewResourceId(){
		$currentId = $this->getProperty("current_resource_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_resource_id", $currentId);
		return $currentId;
	}
	
	public function getResources(){
		$resourceElements = $this->getSubElements("resource");
		$result = array();
		foreach($resourceElements as $resourceElement){
			array_push($result, new Ressource($resourceElement));
		}
		return $result;
	}
	
	public function addResource($docId){
		$resource = new Ressource($this->addSubElement("resource"));
		$resource->setId($this->getId()."_resource_".$this->getNewResourceId());
		$resource->setDocument($docId);
		$this->saveDOM();
		return $resource;
	}
	
	public function removeResource($id){
		$resourceElement = $this->getDom()->getElementById($id);
		$resourceElement->parentNode->removeChild($resourceElement);
		$this->saveDOM();
	}
	
	public function getResourceById($id){
		$resourceElement = $this->getDom()->getElementById($id);
		if($resourceElement!=null)
			return new Ressource($resourceElement);
		else
			return null;
	}
	
	public function showArticleHtmlContent($articleId, $showAll){
		if($textObject = $this->getArticleById($articleId)->getText()){
			$text = $this->getHtmlContent($textObject, $showAll);
		} else {
			$text = "";
		}
		if(!$showAll){
			$text = $text.'<a href="article.php?article='.htmlentities($articleId).'">Read More...</a>';
		}
		return $text;
	}
	
	public function showResourceHtmlContent($resourceId, $showAll){
		if($textObject = $this->getResourceById($resourceId)->getText()){
			$text = $this->getHtmlContent($textObject, $showAll);
		} else {
			$text = "";
		}
		if(!$showAll){
			$text = $text.'<a href="resource.php?resource='.htmlentities($resourceId).'">More...</a>';
		}
		return $text;
	}

	public function showMapHtmlContent($mapId, $showAll){
		if($textObject = $this->getMapById($mapId)->getText()){
			$text = $this->getHtmlContent($textObject, $showAll);
		} else {
			$text = "";
		}
		if(!$showAll){
			$text = $text.'<a href="map.php?map='.htmlentities($mapId).'">More...</a>';
		}
		return $text;
	}
	
	public function getHtmlContent($textObject, $showAll){
		$text = htmlentities($this->getTextById($textObject)->getContent());
		$array = explode('&lt;readmore/&gt;', $text);
		if(count($array)>1){
			$text = $array[0];
			$temp = $array[1];
			if($showAll){
				$text = $text.$temp;
			}
		} else {
			$array = explode("\n", $text);
			if(!$showAll){
				$text = $array[0];
			}
		}
		// New lines --> <p></p>
		$text = '<p>'.$text.'</p>';
		$text = str_replace("\n\n", "\n</p><p>", $text);
		$text = str_replace("\n", "<br/>", $text);
		// easy markups
		$text = $this->parseMarkup("b", "b", $text);
		$text = $this->parseMarkup("i", "i", $text);
		$text = $this->parseMarkup("ul", "ul", $text);
		$text = $this->parseMarkup("li", "li", $text);
		// titles
		$text = $this->parseTitleMarkup("h1", "h3", $text);
		$text = $this->parseTitleMarkup("h2", "h4", $text);
		$text = $this->parseTitleMarkup("h3", "h5", $text);
		$text = $this->parseTitleMarkup("h4", "h6", $text);
		// Avoid BR markups interfering with UL LI
		$text = str_replace("<br/><ul>", "\n<ul>", $text);
		$text = str_replace("<br/><li>", "\n<li>", $text);
		$text = str_replace("<br/></ul>", "\n</ul>", $text);
		$text = str_replace("<br/></li>", "\n</li>", $text);
		// A and IMG
		$text = preg_replace('/&lt;A[\s]*HREF=&quot;([^&;\s]*)&quot;&gt;([^\/]*)&lt;\/A&gt;/i', '<a href="$1">$2</a>', $text);
		$text = preg_replace('/&lt;IMG[\s]*SRC=&quot;([^&;\s]*)&quot;\/&gt;/i', '<img src="$1"/>', $text);
		return $text;
	}
	
	public function parseMarkup($markup, $replacer, $text){
		$text = str_replace("&lt;".$markup."&gt;", '<'.$replacer.'>', $text);
		$text = str_replace("&lt;/".$markup."&gt;", '</'.$replacer.'>', $text);
		$text = str_replace("&lt;".strtoupper($markup)."&gt;", '<'.$replacer.'>', $text);
		$text = str_replace("&lt;/".strtoupper($markup)."&gt;", '</'.$replacer.'>', $text);
		return $text;
	}
	
	public function parseTitleMarkup($markup, $replacer, $text){
		$text = str_replace("&lt;".$markup."&gt;", '</p><'.$replacer.'>', $text);
		$text = str_replace("&lt;/".$markup."&gt;", '</'.$replacer.'><p>', $text);
		$text = str_replace("&lt;".strtoupper($markup)."&gt;", '</p><'.$replacer.'>', $text);
		$text = str_replace("&lt;/".strtoupper($markup)."&gt;", '</'.$replacer.'><p>', $text);
		
		return $text;
	}

}

class Document extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getName(){
		return utf8_decode($this->getProperty("name"));
    }
	
	public function setName($name){
		$this->setProperty("name", $name);
	}
	
	public function getFile(){
		return utf8_decode($this->getProperty("file"));
	}

	public function setFile($path){
		$this->setProperty("file", $path);
	}


	public function getType(){
		$split = preg_split('/\./', $this->getFile());
		return strtoupper($split[count($split)-1]);
	}
	
	public function isUsedAs(){
		return "Not in use (WIP)";
	}
	
}

class Text extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getTitle(){
		return utf8_decode($this->getProperty("title"));
    }
	
	public function setTitle($title){
		$this->setProperty("title", $title);
	}
	
	public function getContent(){
		$file = '../data/texts/'.$this->getId();
		if(file_exists($file)){
			$fp = fopen($file, "r");
			$result = fread($fp, filesize($file));
			fclose($fp);
			return utf8_decode($result);
		} else {
			return "Write text here...";
		}
	}

	public function setContent($content){
		$file = '../data/texts/'.$this->getId();
		if (!$handle = fopen($file, 'w+')) {
			echo "Cannot open file ($file)";
			exit;
		}
		
		// Write $somecontent to our opened file.
		if (fwrite($handle, $content) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
		}
		fclose($handle);
	}
	
	public function isUsedAs(){
		return "Not in use (WIP)";
	}
	
}

class Map extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getTitle(){
		return utf8_decode($this->getProperty("title"));
    }
	
	public function setTitle($title){
		$this->setProperty("title", $title);
	}
	
	public function getDocument(){
		return $this->getProperty("document");
	}

	public function setDocument($docId){
		$this->setProperty("document", $docId);
	}
	
	public function getDownloadableDocument(){
		return $this->getProperty("downloadabledocument");
	}

	public function setDownloadableDocument($docId){
		$this->setProperty("downloadabledocument", $docId);
	}
	
	public function getLegend(){
		return $this->getProperty("legend");
	}

	public function setLegend($docId){
		$this->setProperty("legend", $docId);
	}
	
	public function getText(){
		return $this->getProperty("text");
	}

	public function setText($artId){
		$this->setProperty("text", $artId);
	}
}

class Article extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getTitle(){
		return utf8_decode($this->getProperty("title"));
    }
	
	public function setTitle($title){
		$this->setProperty("title", $title);
	}
		
	public function getText(){
		return $this->getProperty("text");
	}

	public function setText($artId){
		$this->setProperty("text", $artId);
	}
	
	private function getNewRelatedDocumentId(){
		$currentId = $this->getProperty("current_relateddocument_id");
		if($currentId == null)
			$currentId = -1;
		$currentId++;
		$this->setProperty("current_relateddocument_id", $currentId);
		return $currentId;
	}
	
	public function getRelatedDocuments(){
		$relatedDocumentElements = $this->getSubElements("relatedDocument");
		$result = array();
		foreach($relatedDocumentElements as $relatedDocumentElement){
			array_push($result, new ArticleRelatedDocument($relatedDocumentElement));
		}
		return $result;
	}
	
	public function addRelatedDocument($docId){
		$relatedDocument = new ArticleRelatedDocument($this->addSubElement("relatedDocument"));
		$relatedDocument->setId($this->getId()."_relatedDocument_".$this->getNewRelatedDocumentId());
		$relatedDocument->setDocumentId($docId);
		$this->saveDOM();
		return $relatedDocument;
	}
	
	public function removeRelatedDocument($id){
		$relatedDocumentElement = $this->getDom()->getElementById($id);
		$relatedDocumentElement->parentNode->removeChild($relatedDocumentElement);
		$this->saveDOM();
	}
	
	public function getRelatedDocumentById($id){
		$relatedDocumentElement = $this->getDom()->getElementById($id);
		if($relatedDocumentElement!=null)
			return new ArticleRelatedDocument($relatedDocumentElement);
		else
			return null;
	}
	
	public function setFrontArticle($articleId){
		$this->setProperty('frontarticle', $articleId);
	}
	
	public function getFrontArticle(){
		return $this->getProperty('frontarticle');
	}
}

class ArticleRelatedDocument extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getDocument(){
		return new Document($this->getDom()->getElementById($this->getProperty("docid")));
    }
	
	public function setDocumentId($id){
		$this->setProperty("docid", $id);
	}
}

class Ressource extends DomAccessor{
    public function __construct($domElement){
		$this->domElement = $domElement;
    }
	
	public function getDocument(){
		return $this->getProperty("document");
    }
	
	public function setDocument($docId){
		$this->setProperty("document", $docId);
	}
		
	public function getText(){
		return $this->getProperty("text");
	}

	public function setText($artId){
		$this->setProperty("text", $artId);
	}
	
	public function setFront($bool){
		$this->setProperty('front', (($bool)?('true'):('false')));
	}
	
	public function isFront(){
		return ($this->getProperty('front')=='true');
	}
}
	
//
// Utils
//

function GexfExplorer($map, $width, $height){
	global $section;
	echo '<object width="'.$width.'" height="'.$height.'" class="gexfexplorer">';
	echo '<param name="movie" value="res/GexfExplorer1.0.swf?path='.htmlentities($section->getDocumentById($map->getDocument())->getFile()).'&curvedEdges=true&clickableNodes=true&labelsColor=0x454545&font=Verdana" />';
	echo '<param name="allowFullScreen" value="true" />';
	echo '<param name="allowScriptAccess" value="always" />';
	echo '<param name="bgcolor" value="#FFFFFF" />';
	echo '<embed src="res/GexfExplorer1.0.swf?path='.htmlentities($section->getDocumentById($map->getDocument())->getFile()).'&curvedEdges=true&clickableNodes=true&labelsColor=0x454545&font=Verdana" allowfullscreen="true" allowScriptAccess="always" width="'.$width.'" height="'.$height.'" bgcolor="#FFFFFF">';
	echo '</embed></object>';
}

function regenerate(){
	global $dom;
	global $structureDomFile;
	global $data;
	if (file_exists($structureDomFile)) {
		$dom = new DomDocument();
		$dom->load($structureDomFile);
	} else {
		$dom = new DomDocument();
		$root = $dom->createElement('root');
		
		// put id "root" to the root node : tricky... because of some weird php bug(?)
		$attr = $dom->createAttribute("xml:id");
		$root->appendChild($attr);
		$tNode = $dom->createTextNode("root");
		$attr->appendChild($tNode);
		$root->setIdAttribute("xml:id", true);
		
		$dom->appendChild($root);
		$dom->save($structureDomFile);
	}
	
	$data = new rootAccessor();
	
}

function allowedToUpload($filePath){
	$split = preg_split('/\./', $filePath);
	$type = strtoupper($split[count($split)-1]);
	return (
		// Office documents
		$type=="PDF"
		|| $type == "DOC"
		|| $type == "PPT"
		|| $type == "ODS"
		|| $type == "ODT"
		|| $type == "XLS"
		|| $type == "CSV"
		|| $type == "TXT"
		// Images
		|| $type == "GIF"
		|| $type == "JPG"
		|| $type == "PNG"
		// Multimedia
		|| $type == "SWF"
		|| $type == "MP3"
		// Graphs
		|| $type == "GEXF"
		|| $type == "GDF"
		|| $type == "NET"
		// Archives
		|| $type == "ZIP"
	);
}

function redirectUploadedFile($postFile){
	$folder = '../data/files/';
	$file = basename($postFile['name']);
	if(allowedToUpload($file)){
		if(move_uploaded_file($postFile['tmp_name'], $folder . $file)){
			return $folder.$file;
		} else {
			return null;
		}
	}
}

// DOM

function getTextFromNode($Node, $Text = "") {
	if($Node->nodeName == "#text")
		return $Text.$Node->textContent;
	else if ($Node->tagName == null)
        return $Text.$Node->textContent;

    $Node = $Node->firstChild;
    if ($Node != null){
        $Text = getTextFromNode($Node, $Text);

		while($Node->nextSibling != null) {
			$Text = getTextFromNode($Node->nextSibling, $Text);
			$Node = $Node->nextSibling;
		}
	}
	
    return $Text;
}

function getChildrenTagnamed($Node, $Tagname){
	$result = array();
	$Node = $Node->firstChild;
	if($Node != null){
		if($Node->tagName == $Tagname){
			array_push($result, $Node);
		}
		while($Node->nextSibling != null) {
			if($Node->nextSibling->tagName == $Tagname){
				array_push($result, $Node->nextSibling);
			}
			$Node = $Node->nextSibling;
		}
	}
    return $result;
}

function getChildTagnamed($Node, $Tagname){
	$Node = $Node->firstChild;
	if($Node != null){
		if($Node->tagName == $Tagname){
			return $Node;
		}
		while($Node->nextSibling != null) {
			if($Node->nextSibling->tagName == $Tagname){
				return $Node->nextSibling;
			}
			$Node = $Node->nextSibling;
		}
    }
    return null;
}

function deleteNode($node) {
    deleteChildren($node);
    $parent = $node->parentNode;
    $oldnode = $parent->removeChild($node);
}

function deleteChildren($node) {
    while (isset($node->firstChild)) {
        deleteChildren($node->firstChild);
        $node->removeChild($node->firstChild);
    }
}

?>