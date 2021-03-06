<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BMORE Around Town | Dedicated to Bringing you the Best social Event Activities in Baltimore | Happy Hours</title>
<?php include("../_includes/functions.php"); ?>


<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
			google.load("jquery", "1.3");
		</script>

<script src="../_assets/_js/validate.js" type="text/javascript" charset="utf-8"></script>


<script type="text/javascript">
		$(document).ready(function() {
			$("#happy_hour").validate({
				submitHandler:function(form) {
					SubmittingForm();
				},
				rules: {
					firstname: "required",		// simple rule, converted to {required:true}
					lastname: "required",
					phone: "required",
					email: {				// compound rule
						required: true,
						email: true
					},
					message: {
						required: false
					}
				},
				messages: {
					comment: "Please enter your message."
				}
			});
		});

		jQuery.validator.addMethod(
			"selectNone",
			function(value, element) {
				if (element.value == "none")
				{
					return false;
				}
				else return true;
			},
			"Please select an option."
		);

		$(document).ready(function() {
			$("#fvujq-form2").validate({
				submitHandler:function(form) {
					SubmittingForm();
				},
				rules: {
					sport: {
						selectNone: true
					}
				}
			});
		});
	</script>

</head>

<body>

<div class="min-width">
	
    <div id="container">
    	
        <?php include("../_includes/navigation.php"); ?>
        
        <div id="contentcontainer">
        	
            <?php include("../_includes/sidebar.php"); ?>
            
            <div id="content">
                
                	<h1>Happy Hours</h1>
    
         	 <p>Happy Hours are a great way to get out and meet new people in the community. It is also a chance for you to mingle with others who are part of the BMORE Around Town Social Club! We have a Happy Hour Event scheduled each month in a various location. We can also help you plan your own for you and your friends by simply filling out the form below!</p>
             
             <form method="post" name="happy_hour" id="happy_hour" action="http://www.ipower.com/scripts/formemail.bml">
                           <input type="hidden" name="my_email" value="register@b-morearoundtown.com">

                           <input type="hidden" name="thankyou_url" value="http://www.bmorearoundtown.com/thankyou"> 
                           <input type="hidden" name="eventtype" value="happy hour"> 
                           
                            <input name="firstname" type="text" id="firstname" value="First Name *" />
                        <br />
                            <input name="lastname" type="text" id="lastname" value="Last Name *" />
                        <br />
                        <input name="email" type="text" id="email" value="Email *" />
                        <br />
                        <input name="address" type="text" id="address" value="Address" />

                      <br />
                      <input name="city" type="text" id="city" value="City" />
                      <br />
                      <input name="state" type="text" id="state" value="State" />
                      <br />
                      <input name="zip" type="text" id="zip" value="Zip" />
                      <br />
                            
                            <input name="phone" type="text" id="phone" value="Phone *" />
                      <br />

                      <textarea></textarea>
                      <br />
                            <button type="submit">Submit</button>
                        </form>

                
            </div>
            
            <div class="clear"></div>
            
        </div>
        
        <?php include("../_includes/footer.php"); ?>
        
    </div>

</div>


</body>
</html>