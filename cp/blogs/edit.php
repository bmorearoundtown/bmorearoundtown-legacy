<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$GLOBALS['config']->addPageCSSFullPath( '/cp/_assets/_css/summernote.css' );
	$GLOBALS['config']->addPageJSFullPath( '/cp/_assets/_js/lib/summernote.min.js' );
	
	$objBlog = new Blog($_GET['blog'] ? $_GET['blog'] : $_SESSION['forms']['edit-blog']['id']);

	if (!$_SESSION['forms']['edit-blog']['id'])
		$_SESSION['forms']['edit-blog'] = $objBlog->getDataArray();
	
	require_once( 'header.php' );
?>

	<div class="page-header">
	
		<h1 class="pull-left collapse-box">Edit Blog Entry <small> ( <?= $_SESSION['forms']['edit-blog']['title'] ?> ) </small></h1>
		
		<div class="pull-right">
			<a href="/cp/blogs/index.php"><i class="fa fa-arrow-left"></i> Back to blogs</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['edit-blog']['error']) {
	?>
	<div class="alert alert-danger">
		<p>There was an error saving the blog entry. Please try again.</p>
	</div>
	<?
		$_SESSION['forms']['edit-blog']['error'] = false;
	}
?>

	<div class="well well-lg">
		
		<form action="action.add.php" enctype="multipart/form-data" method="post" class="form form-horizontal validate">
			<input id="blogId" type="hidden" name="blogId" value="<?= $_SESSION['forms']['edit-blog']['id'] ?>" />
			
			<fieldset>
			
				<legend>Blog Details</legend>
				
				<div class="form-group">
					
					<label for="blogTitle" class="col-sm-2 control-label">Title:</label>
					
					<div class="col-sm-10">
						
						<input id="blogTitle" type="text" name="title" value="<?= $_SESSION['forms']['edit-blog']['title'] ?>" size="35" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="blogAuthor" class="col-sm-2 control-label">Author:</label>
					
					<div class="col-sm-10">
						
						<input id="blogAuthor" type="text" name="author" value="<?= $_SESSION['forms']['edit-blog']['author'] ?>" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="blogDate" class="col-sm-2 control-label">Date:</label>
						
					<div class="col-sm-10">
						
						<input id="blogDate" type="text" name="date" value="<?= $_SESSION['forms']['edit-blog']['blogLogoFile'] ?>" size="35" class="form-control input-sm date-picker" style="width: 150px;" />
							
					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="blogLogoFile" class="col-sm-2 control-label">Image:</label>
						
					<div class="col-sm-10">
						
						<input type="file" name="blogLogoFile" value="<?= $_SESSION['forms']['edit-blog']['blogLogoFile'] ?>" size="35" class="btn btn-default" />
							
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="blogActive" class="col-sm-2 control-label">Active:</label>
						
					<div class="col-sm-10">
						
						<select id="blogActive" name="isActive" class="form-control input-sm" style="width: 200px;">
							<option value="1"<?= $objBlog->getIsActive() ? ' selected="selected"' : '' ?>>Yes</option>
							<option value="0"<?= !$objBlog->getIsActive() ? ' selected="selected"' : '' ?>>No</option>
						</select>						
							
					</div>
					
				</div>
				
			</fieldset>

			<fieldset>
			
				<legend>Blog Entry</legend>
				
				<div class="form-group">
					
					<label for="blogIntroduction" class="col-sm-2 control-label">Introduction:</label>
					
					<div class="col-sm-10">
						
						<textarea id="blogIntroduction" name="introduction" row="8" class="form-control input-sm summernote">
							<?= $_SESSION['forms']['edit-blog']['introduction'] ?>
						</textarea>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="blogContent" class="col-sm-2 control-label">Content:</label>
					
					<div class="col-sm-10">
						
						<textarea id="blogContent" name="content" row="8" class="form-control input-sm summernote">
							<?= $_SESSION['forms']['edit-blog']['content'] ?>
						</textarea>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="blogSignature" class="col-sm-2 control-label">Signature:</label>
					
					<div class="col-sm-10">
						
						<textarea id="blogSignature" name="signature" row="8" class="form-control input-sm summernote">
							<?= $_SESSION['forms']['edit-blog']['signature'] ?>
						</textarea>
						
					</div>
					
				</div>
				
			</fieldset>
			
			<hr>
			
			<div class="form-group">
				
				<button type="button" class="btn btn-lg btn-info col-sm-2 col-md-offset-2 cushion-right"><i class="fa fa-eye"></i> Preview</button>
				
				<button type="submit" class="btn btn-lg btn-success col-sm-2"><i class="fa fa-plus"></i> Save Blog Entry</button>
			
			</div>
			
		</form>
	
	</div>

	<script>

		var BlogEdit = (function(){
			
			function createDateTimePopups() {

				$( '.date-picker' ).datepicker({});

				return;
			}
			
			function createWYSIWYG(){
				
				$( '.summernote' ).summernote();
				
				return;
				
			}
			
			function bindEvents(){
			
				return;
			}
			
			function init(){
				
				createDateTimePopups();
				
				createWYSIWYG();
				
				return;
			}
			
			return {
			
				"init": init
				
			}
			
		}());
		
		$( function(){
			
			BlogEdit.init();
			
		});
		
	</script>
	
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');