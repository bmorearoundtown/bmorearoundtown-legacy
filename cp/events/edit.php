<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$GLOBALS['config']->addPageCSSFullPath( '/cp/_assets/_css/summernote.css' );
	$GLOBALS['config']->addPageJSFullPath( '/cp/_assets/_js/lib/summernote.min.js' );
	
	$objEvent = new EventX($_GET['event'] ? $_GET['event'] : $_SESSION['forms']['edit-event']['eventId']);
	
	if (!$_SESSION['forms']['edit-event']['eventId']) {
	
		$_SESSION['forms']['edit-event'] = $objEvent->getDataArray();
		
		if ($_SESSION['forms']['edit-event']['startDate']) {
			$_SESSION['forms']['edit-event']['startTime'] = date('g:i A', $_SESSION['forms']['edit-event']['startDate']);
			$_SESSION['forms']['edit-event']['startDate'] = date('n/j/Y', $_SESSION['forms']['edit-event']['startDate']);
		}
		
		if ($_SESSION['forms']['edit-event']['endDate']) {
			$_SESSION['forms']['edit-event']['endTime'] = date('g:i A', $_SESSION['forms']['edit-event']['endDate']);
			$_SESSION['forms']['edit-event']['endDate'] = date('n/j/Y', $_SESSION['forms']['edit-event']['endDate']);
		}
		
		if ($_SESSION['forms']['edit-event']['registrationDeadlineDate']) {
			$_SESSION['forms']['edit-event']['registrationDeadlineTime'] = date('g:i A', $_SESSION['forms']['edit-event']['registrationDeadlineDate']);
			$_SESSION['forms']['edit-event']['registrationDeadlineDate'] = date('n/j/Y', $_SESSION['forms']['edit-event']['registrationDeadlineDate']);
		}
		
		$objLocation = new EventLocation();
		$objLocation->loadByEventId($objEvent->getId());
		
		$objLocationValues = array();
		
		while( $objLocation->loadNext() ){
			array_push($objLocationValues , $objLocation->getLocationId() );
		}
		
		$_SESSION['forms']['edit-event']['locationId'] = $objLocationValues;
	
		
	}
	
	$_SESSION['forms']['imageChanged'] = false;
	
	require_once( 'header.php' );
?>

	<div class="page-header">
		
		<h1 class="pull-left collapse-box">Edit Event <small> ( <?= $_SESSION['forms']['edit-event']['name'] ?> ) </small></h1>
		
		<div class="pull-right">
			<a href="/cp/events/view.php?event=<?= $objEvent->getId() ?>""><i class="fa fa-arrow-left"></i> Back to view</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
	<div class="well well-lg">
	
		<form id="eventEditForm" action="action.edit.php" enctype="multipart/form-data" method="post" class="form form-horizontal validate">
			<input type="hidden" name="eventId" 
				value="<?= $_SESSION['forms']['edit-event']['eventId'] ? $_SESSION['forms']['edit-event']['eventId'] : $objEvent->getId() ?>" />
			<input id="imageChanged" type="hidden" name="imageChanged" value="0" />
				
			<fieldset>
			
				<legend>Event Details</legend>
				
				<div class="form-group">
					
					<label for="eventName" class="col-sm-2 control-label">Event Name:</label>
					
					<div class="col-sm-10">
						
						<input id="eventName" type="text" name="name"
								value="<?= $_SESSION['forms']['edit-event']['name'] ?>" size="35" 
								class="form-control required" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="eventCategory" class="col-sm-2 control-label">Category:</label>
					
					<div class="col-sm-10">
						
						<select id="eventCategory" style="width: auto;" name="categoryId" class="form-control" required>
							<option value=""> - Select a category -</option>
							<?
								$objCategory = new Category();
								$objCategory->loadForForm();
								
								while ($objCategory->loadNext()) {
								?>
									<option value="<?= $objCategory->getId() ?>"<?= $_SESSION['forms']['edit-event']['categoryId'] == $objCategory->getId() ? ' selected="selected"' : '' ?>><?= $objCategory->getName() ?></option>
								<?
								}
							?>
						</select>

					</div>
					
				</div>

				<div class="form-group">
					
					<label for="eventDescription" class="col-sm-2 control-label">Description:</label>
					
					<div class="col-sm-10">

						<textarea id="eventDescription" name="description" rows="8" cols="50" class="form-control summernote">
							<?= $_SESSION['forms']['edit-event']['description'] ?>
						</textarea>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="startDate" class="col-sm-2 control-label">Start Date:</label>
					
					<div class="col-sm-10"> 

						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input type="text" id="startDate" name="startDate" value="<?= $_SESSION['forms']['edit-event']['startDate'] ?>" size="15" class="form-control date-picker" required />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">						
							<input type="text" name="startTime" value="<?= $_SESSION['forms']['edit-event']['startTime'] ?>" size="12" class="form-control time-picker" required />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>							
						</div>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="endDate" class="col-sm-2 control-label">End Date:</label>
					
					<div class="col-sm-10">

						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input id="endDate" name="endDate" type="text" class="form-control date-picker" size="15" value="<?= $_SESSION['forms']['edit-event']['endDate'] ?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">	
							<input type="text" name="endTime" value="<?= $_SESSION['forms']['edit-event']['endTime'] ?>" size="12" class="form-control time-picker" />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>							
						</div>
						
					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="eventImage" class="col-sm-2 control-label">Event Image:</label>

					<div class="col-sm-10">
						
						<button id="changeEventLogoButton" type="button" class="btn btn-warning"><i class="fa fa-exchange cushion-right"></i> Change Event Image</button>
						
						<div class="hide change-event-image-container">
							
							<input type="file" name="eventLogoFile" value="<?= $_SESSION['forms']['edit-event']['eventLogoFile'] ?>" class="btn btn-default pull-left" />
							
							<button id="cancelEventLogoChange" type="button" class="btn btn-danger cushion-left"><i class="fa fa-times cushion-right"></i> Cancel Change</button>
							
							<p class="help-block">If you do not cancel or add a picture the logo will be replaced with no logo.</p>
							
						</div>
						
					</div>
					
				</div>
				
			</fieldset>
		
			<fieldset>
				
				<legend>Location and Hotels</legend>

				<div class="form-group">
					
					<label for="eventLocations" class="col-sm-2 control-label">Locations:</label>

					<div class="col-sm-10">
						
						<?
							$objLocation = new Location();
							$objLocation->loadForForm();
						?>				
						
						<select id="eventLocations" style="width: auto;" size="10" name="locationId[]" class="form-control"<?= count($objLocation) ? '' : ' disabled="disabled"' ?> multiple>
							<?	
								if (count($objLocation)) {
								?>
									<option value=""> - Select a location - </option>
								<?
								} else {
								?>
									<option value=""> - There are no locations configured - </option>
								<?
								}
								
								while ($objLocation->loadNext()) {
								?>
									<option value="<?= $objLocation->getId() ?>"<?= in_array( $objLocation->getId(), $_SESSION['forms']['edit-event']['locationId'] ) ? ' selected="selected"' : '' ?>><?= $objLocation->getName() ?></option>
								<?
								}
							?>
						</select>
						
						<p id="eventLocationInformation" class="help-block"></p>
						
						<p class="help-block">Hit Ctrl + click or shift + click to highlight multiple locations.</p>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="eventHotels" class="col-sm-2 control-label">Hotels:</label>

					<div class="col-sm-10">

						<?
							$objHotel = new Hotel();
							$objHotel->loadForForm();
						?>		
						
						<select name="hotelIds[]" class="uiSelect form-control" multiple size="5"<?= count($objHotel) ? '' : ' disabled="disabled"' ?>>
						
							<?	
								if (count($objHotel)) {
								?>
									<option value=""> - Select a hotel - </option>
								<?
								} else {
								?>
									<option value="">- There are no hotels configured -</option>
								<?
								}
								
								while ($objHotel->loadNext()) {
								?>
									<option value="<?= $objHotel->getId() ?>"<?= in_array($objHotel->getId(), $_SESSION['forms']['edit-event']['hotelIds']) ? ' selected="selected"' : '' ?>><?= $objHotel->getName() ?></option>
								<?
								}
							?>
							
						</select>
						
					</div>
				
				</div>
				
			</fieldset>

			<fieldset>
				
				<legend>Registration Options</legend>

				<div class="form-group">
					
					<label for="eventDeadline" class="col-sm-2 control-label">Registration Deadline:</label>

					<div class="col-sm-10">
						
						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input id="eventDeadline" type="text" name="registrationDeadlineDate" value="<?= $_SESSION['forms']['edit-event']['registrationDeadlineDate'] ?>" size="15" class="form-control date-picker required" />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">	
							<input type="text" name="registrationDeadlineTime" value="<?= $_SESSION['forms']['edit-event']['registrationDeadlineTime'] ?>" size="12" class="form-control time-picker" required />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
						</div>
						
					</div>
				
				</div>

				<div class="form-group">
					
					<label for="eventInventory" class="col-sm-2 control-label">Total Ticket Inventory:</label>

					<div class="col-sm-10">

						<input id="eventInventory" type="text" name="maxParticipants" style="width: 100px;" value="<?= $_SESSION['forms']['edit-event']['maxParticipants'] ?>" size="5" class="form-control required" required />
						
						<div class="error" id="advice-maxParticipants" style="display: none;">Enter the total number of tickets for this event</div>
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label for="eventMaxTickets" class="col-sm-2 control-label">Max tickets per Registration:</label>

					<div class="col-sm-10">

						<input id="eventMaxTickets" type="text" style="width: 100px;" name="maxTicketsPerRegistration" value="<?= $_SESSION['forms']['edit-event']['maxTicketsPerRegistration'] ?>" size="5" class="form-control" required />
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label class="col-sm-2 control-label">Is Active:</label>

					<div class="col-sm-10">

						<label for="eventIsActiveYes">
						
							<input id="eventIsActiveYes" type="radio" class="checkbox-inline" name="isActive" value="1"
							
							<? if( $_SESSION['forms']['edit-event']['isActive'] ) { ?>
								checked="checked" 
							<? } ?>
							
							/> Yes
							
						</label>
						
						<label for="eventIsActiveNo">
						
							<input id="eventIsActiveNo" type="radio" class="checkbox-inline" name="isActive" value="0"
								
								<? if( !$_SESSION['forms']['edit-event']['isActive'] ) { ?>
									checked="checked" 
								<? } ?>
							
							/> No
							
						</label>	
						
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label class="col-sm-2 control-label">Is Published:</label>

					<div class="col-sm-10">

						<label for="eventIsPublishedYes">
						
							<input id="eventIsPublishedYes" type="radio" class="checkbox-inline" name="isPublished" value="1"
							
							<? if( $_SESSION['forms']['edit-event']['isPublished'] ) { ?>
								checked="checked" 
							<? } ?>
							
							/> Yes
							
						</label>
						
						<label for="eventIsPublishedNo">
						
							<input id="eventIsPublishedNo" type="radio" class="checkbox-inline" name="isPublished" value="0"
								
								<? if( !$_SESSION['forms']['edit-event']['isPublished'] ) { ?>
									checked="checked" 
								<? } ?>
							
							/> No
							
						</label>	
						
						
					</div>
			
				</div>
				
			</fieldset>

			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2" style="font-size: 1.25em;"><i class="fa fa-check"></i> Save Event</button>
			
			</div>
			
		</form>
	
	</div>

	<script>
		
		var EventEdit = (function(){
			
			function createDateTimePopups() {

				$( '.date-picker' ).datepicker({});

				$( '.time-picker' ).timepicker({});
				
				return;
			}
			
			function createWYSIWYG(){
				
				$( '.summernote' ).summernote();
				
				return;
				
			}
			
			function toggleLogoChange( e ){
				
				e.preventDefault();
				
				var $buttonEl = $( "button#changeEventLogoButton" );
				var $changeImageFormContainer = $( '.change-event-image-container' );
				var $imageChangedInput = $( "input#imageChanged" );
				
				$changeImageFormContainer.toggleClass( " hide" );
				
				if( $changeImageFormContainer.is( ":visible" ) ){
					
					$buttonEl.hide();
					
					$imageChangedInput.val( 1 );
					
				} else {
					
					$buttonEl.show();
					
					$imageChangedInput.val( 0 );
					
				}
				
				return;
			}
			
			function bindEvents(){
				
				// watch for changing of an image
				$( "button#changeEventLogoButton, button#cancelEventLogoChange" ).on( "click", toggleLogoChange );
				
				return;
			}
			
			function init(){
				
				createDateTimePopups();
				
				createWYSIWYG();
				
				bindEvents();
				
				return;
			}
			
			return {
			
				"init": init
				
			}
			
		}());
		
		$( function(){
			
			EventEdit.init();
			
		});
		
	</script>
	