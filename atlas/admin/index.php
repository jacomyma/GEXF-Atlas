<?php
	include("../engine/engine.php");
	$session = new ezcAuthenticationSession();
	$session->start();
	$user = isset( $_POST['username'] ) ? $_POST['username'] : $session->load();
	$password = isset( $_POST['password'] ) ? $_POST['password'] : null;
	$credentials = new ezcAuthenticationPasswordCredentials( $user, $password );
	$authentication = new ezcAuthentication( $credentials );
	$authentication->session = $session;
	$authentication->addFilter( new ezcAuthenticationHtpasswdFilter( $enginePath.'/passwords' ) );
	if(isset($_GET['page']) && $_GET['page']=="logout"){
		$session->destroy();
		$user = null;
		$password = null;
	}
	// add other filters if needed
	if ( !$authentication->run() )
	{
		$caption = "";
		if($user!=""){
			// authentication did not succeed, so inform the user
			$status = $authentication->getStatus();
			$err = array(
				'ezcAuthenticationHtpasswdFilter' => array(
					ezcAuthenticationHtpasswdFilter::STATUS_USERNAME_INCORRECT => 'Incorrect username',
					ezcAuthenticationHtpasswdFilter::STATUS_PASSWORD_INCORRECT => 'Incorrect password'
				),
				'ezcAuthenticationSession' => array(
					ezcAuthenticationSession::STATUS_EMPTY => '',
					ezcAuthenticationSession::STATUS_EXPIRED => 'Session expired'
				)
			);
			foreach ( $status as $line )
			{
				list( $key, $value ) = each( $line );
				$caption .= $err[$key][$value] . "<br/>";
			}
		}
		
		include("_inithtml.php");
		$title = "eDiasporas Atlas &mdash; Admin : Identification";
		include("_head.php");
?>
	<body>
		<div class="container_12">
<?php include("pages/_authentication.php");?>
<?php include("_bottom.php");?>
		</div>
<?php include("_jsengines.php");?>
	</body>
</html>
<?php
	}
	else
	{
		// authentication succeeded, so allow the user to see his content
		$error = false;
		if(isset($_GET['page']) && $_GET['page']=="home"){
			
		} else if(isset($_GET['page']) && $_GET['page']=="sectionhome" && isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
			// SECTIONHOME
			if(isset($_POST['action'])){
				// setTitle
				if($_POST['action']=='setTitle' && isset($_POST['title'])){
					$section->setTitle($_POST['title']);
					$section->saveDOM();
				}
				// setPublicationStatus
				if($_POST['action']=='setPublicationStatus' && isset($_POST['publicationStatus'])){
					$section->setPublished(($_POST['publicationStatus']=="true"));
					$section->saveDOM();
				}
			}
			include("pages/_sectionhome.php");
		} else if(isset($_GET['page']) && $_GET['page']=="documents" && isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
			// DOCUMENTS
			if(isset($_POST['action'])){
				// add Distant Document
				if($_POST['action']=='addDistantDocument' && isset($_POST['link']) && isset($_POST['name'])){
					$section->addDocument($_POST['name'], $_POST['link']);
					regenerate();
					$section = $data->getSectionById($_GET['section']);
				}
				// add (Uploaded) Document
				if($_POST['action']=='addDocument' && isset($_FILES['file']) && isset($_POST['name'])){
					$path = redirectUploadedFile($_FILES['file']);
					$section->addDocument($_POST['name'], $path);
					regenerate();
					$section = $data->getSectionById($_GET['section']);
				}
				// Delete Document
				if($_POST['action']=='removeDocument' && isset($_POST['document']) && isset($_POST['delete']) && $_POST['delete']=="y"){
					$section->removeDocument($_POST['document']);
				}
			}
			include("pages/_documents.php");
		} else if(isset($_GET['page']) && $_GET['page']=="document" && isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
			if(isset($_GET['document']) && $document = $section->getDocumentById($_GET['document'])){
				// DOCUMENT
				if(isset($_POST['action'])){
					// setName
					if($_POST['action']=='setName' && isset($_POST['name'])){
						$document->setName($_POST['name']);
						$document->saveDOM();
					}
					// set (Uploaded) File
					if($_POST['action']=='setFile' && isset($_FILES['file'])){
						$path = redirectUploadedFile($_FILES['file']);
						$document->setFile($path);
						$document->saveDOM();
					}
					// set Link
					if($_POST['action']=='setLink' && isset($_POST['link'])){
						$document->setFile($_POST['link']);
						$document->saveDOM();
					}
				}
				include("pages/_document.php");
			}
		} else if(isset($_GET['page']) && $_GET['page']=="texts" && isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
			// TEXTS
			if(isset($_POST['action'])){
				// add Text
				if($_POST['action']=='addText' && isset($_POST['title'])){
					$section->addText($_POST['title']);
					regenerate();
					$section = $data->getSectionById($_GET['section']);
				}
				// Delete Text
				if($_POST['action']=='removeText' && isset($_POST['text']) && isset($_POST['delete']) && $_POST['delete']=="y"){
					$section->removeText($_POST['text']);
				}
			}
			include("pages/_texts.php");
		} else if(isset($_GET['page']) && $_GET['page']=="text" && isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
			if(isset($_GET['text']) && $text = $section->getTextById($_GET['text'])){
				// TEXT
				if(isset($_POST['action'])){
					// setTitle
					if($_POST['action']=='setTitle' && isset($_POST['title'])){
						$text->setTitle($_POST['title']);
						$text->saveDOM();
					}
					// setContent
					if($_POST['action']=='setContent' && isset($_POST['content'])){
						$text->setContent($_POST['content']);
						$text->saveDOM();
					}
				}
				include("pages/_text.php");
			}
		} else if(isset($_GET['page']) && $_GET['page']=="setcontents" && isset($_GET['section']) && $section = $data->getSectionById($_GET['section'])){
			// SET CONTENTS
			if(isset($_POST['action'])){
				// Add Map
				if($_POST['action']=='addMap' && isset($_POST['title'])){
					$section->addMap($_POST['title']);
					regenerate();
				}
				// Delete Map
				if($_POST['action']=='removeMap' && isset($_POST['map']) && isset($_POST['delete']) && $_POST['delete']=="y"){
					$section->removeMap($_POST['map']);
				}
				// Change Map Settings
				if($_POST['action']=='changeSettings'  && isset($_POST['map']) && isset($_POST['title']) && isset($_POST['document']) && isset($_POST['downloadabledocument'])  && isset($_POST['legend']) && isset($_POST['text'])){
					if($map=$section->getMapById($_POST['map'])){
						$map->setTitle($_POST['title']);
						$map->setDocument($_POST['document']);
						$map->setDownloadableDocument($_POST['downloadabledocument']);
						$map->setLegend($_POST['legend']);
						$map->setText($_POST['text']);
						if(isset($_POST['front']) && $_POST['front']=='true'){
							$section->setFrontMap($map->getId());
						} else {
							if($section->getFrontMap() == $map->getId()){
								$section->setFrontMap('');
							}
						}
						$map->saveDOM();
					}
				}
				// Add Article
				if($_POST['action']=='addArticle' && isset($_POST['title'])&& isset($_POST['text'])){
					if($text=$section->getTextById($_POST['text'])){
						$section->addArticle($_POST['title'], $_POST['text']);
						regenerate();
					}
				}
				// Delete Article
				if($_POST['action']=='removeArticle' && isset($_POST['article']) && isset($_POST['delete']) && $_POST['delete']=="y"){
					$section->removeArticle($_POST['article']);
				}
				// Change Article Settings
				if($_POST['action']=='changeSettings' && isset($_POST['article']) && isset($_POST['title']) && isset($_POST['text'])){
					if($article=$section->getArticleById($_POST['article'])){
						$article->setTitle($_POST['title']);
						$article->setText($_POST['text']);
						if(isset($_POST['front']) && $_POST['front']=='true'){
							$section->setFrontArticle($article->getId());
						} else {
							if($section->getFrontArticle() == $article->getId()){
								$section->setFrontArticle('');
							}
						}
						$article->saveDOM();
					}
				}
				// Manage Related Documents
				if($_POST['action']=='manageRelatedDocuments' && isset($_POST['article'])){
					if($article=$section->getArticleById($_POST['article'])){
						if(isset($_POST['relateddocument'])){
							if($_POST['relateddocument'] != ""){
								$article->addRelatedDocument($_POST['relateddocument']);
								regenerate();
							}
						}
						foreach($article->getRelatedDocuments() as $relatedDocument){
							if(isset($_POST['delete_'.$relatedDocument->getId()])){
								if($_POST['delete_'.$relatedDocument->getId()] == "true"){
									$article->removeRelatedDocument($relatedDocument->getId());
								}
							}
						}
					}
				}
				// Add Resource
				if($_POST['action']=='addResource' && isset($_POST['document'])){
					$section->addResource($_POST['document']);
					regenerate();
				}
				// Delete Resource
				if($_POST['action']=='removeResource' && isset($_POST['resource']) && isset($_POST['delete']) && $_POST['delete']=="y"){
					$section->removeResource($_POST['resource']);
				}
				// Change Resource Settings
				if($_POST['action']=='changeSettings' && isset($_POST['resource']) && isset($_POST['document']) && isset($_POST['text'])){
					if($resource=$section->getResourceById($_POST['resource'])){
						$resource->setDocument($_POST['document']);
						$resource->setText($_POST['text']);
						if(isset($_POST['front']) && $_POST['front']=='true'){
							$resource->setFront(true);
						} else {
							$resource->setFront(false);
						}
						$resource->saveDOM();
					}
				}
			}
			include("pages/_setcontents.php");
		} else {
			// SECTIONS
			if(isset($_POST['action'])){
				// Add Section
				if($_POST['action']=='addSection' && isset($_POST['title'])){
					$data->addSection($_POST['title']);
					regenerate();
				}
				// Delete Section
				if($_POST['action']=='removeSection' && isset($_POST['section']) && isset($_POST['delete']) && $_POST['delete']=="y"){
					$data->removeSection($_POST['section']);
				}
			}
			include("pages/_sections.php");
		}
	}
?>
