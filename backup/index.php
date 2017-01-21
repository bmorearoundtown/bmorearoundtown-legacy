<?php

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );
	
	/* set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'home' );
	
	require_once( "head.php" );
	
	require_once( "socialSDK.php" );
	
	require_once( "header.php" );
	
?>

<div id="content">
	
	<div id="bannerWrapper" class="wrapper">
	
		<div class="container">
			
			<div class="row">
			
				<div class="col-md-12">
					
					<div id="banner">
						
						<?php include("banner.php" ); ?>
						
					</div>
				
				</div>
						
			</div>
			
		</div>
	
	</div>
	
	<div id="featuredEventsWrapper" class="wrapper">
		
		<div class="container">
			
			<?php include("featuredEvents.php" ); ?>
		
		</div>
		
	</div>

	<div id="informationalDividerWrapper" class="wrapper">
		
		<div class="container" >
			
			<?php include("informationalDivider.php"); ?>
			
		</div>
		
	</div>

	<div id="newsletterWrapper" class="wrapper">
		
		<div class="container">
			
			<?php include("newsletter.php"); ?>
			
		</div>
		
	</div>
	
</div>

<?php require_once("footer.php"); ?>

</body>

</html>
