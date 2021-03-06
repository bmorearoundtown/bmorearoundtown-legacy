<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['add-blog'] = array();
	
		$objBlog = new Blog();

		$objBlog->setTitle($_POST['title']);
		$objBlog->setDate($_POST['date']);
		$objBlog->setIntroduction($_POST['introduction']);
		$objBlog->setContent($_POST['content']);
		$objBlog->setSignature($_POST['signature']);
		$objBlog->setAuthor($_POST['author']);
		$objBlog->setImageUrl($_POST['imageUrl']);
		$objBlog->setDateCreated(time());
		$objBlog->setDateModified(time());
		$objBlog->setIsActive(1); // set active by default can change later via editing
		$objBlog->generateBlogCode();
				
		if (!$objBlog->insertIntoDatabase())
			throw new RecordCreationInsertion();

		if ($_FILES['blogLogoFile']['size']) {
		
			$objUpload = new Upload($_FILES['blogLogoFile']);
			
			$strFilename = $objBlog->getBlogCode() . '.png';
			$strTempFilename = time() . '.upload';
			
			$objUpload->moveTo($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
			
			$objImage = new Image($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
			
			$objImage->convertTo('PNG', $strFilename);
			
			$objImage = new Image($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strFilename);
			
			$objImage->resizeToWidth(200);
			
			$objImage->export($_SERVER['DOCUMENT_ROOT'] . '/_assets/_images/logos/blogs/' . $strFilename, IMAGETYPE_PNG);
			
			$objImage->destroy();
			
			@unlink($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strFilename);
			@unlink($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
			
		}
		
		$_SESSION['forms']['blog']['success'] = '<strong>' . $objBlog->getTitle() . '</strong> was added successfully!';
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['add-blog'][$strKey] = $mxdValue;
		
		$_SESSION['forms']['add-blog']['error'] = true;
		
		header('Location: add.php');
		exit;
	
	}