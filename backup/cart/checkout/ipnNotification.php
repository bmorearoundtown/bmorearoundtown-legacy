<?php

require_once ($_SERVER ['DOCUMENT_ROOT'] . '/_includes/config.php');

define ( "DEBUG", 0 );
// Set to 0 once you're ready to go live
define ( "USE_SANDBOX", 0 );
define ( "LOG_FILE", "./ipnlogging.log" );

$raw_post_data = file_get_contents ( 'php://input' );
$raw_post_array = explode ( '&', $raw_post_data );
$myPost = array ();
foreach ( $raw_post_array as $keyval ) {
	$keyval = explode ( '=', $keyval );
	if (count ( $keyval ) == 2)
		$myPost [$keyval [0]] = urldecode ( $keyval [1] );
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if (function_exists ( 'get_magic_quotes_gpc' )) {
	$get_magic_quotes_exists = true;
}
foreach ( $myPost as $key => $value ) {
	if ($get_magic_quotes_exists == true && get_magic_quotes_gpc () == 1) {
		$value = urlencode ( stripslashes ( $value ) );
	} else {
		$value = urlencode ( $value );
	}
	$req .= "&$key=$value";
}
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data
if (USE_SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}
$ch = curl_init ( $paypal_url );
if ($ch == FALSE) {
	return FALSE;
}
curl_setopt ( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $req );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
curl_setopt ( $ch, CURLOPT_FORBID_REUSE, 1 );
if (DEBUG == true) {
	curl_setopt ( $ch, CURLOPT_HEADER, 1 );
	curl_setopt ( $ch, CURLINFO_HEADER_OUT, 1 );
}
// CONFIG: Optional proxy configuration
// curl_setopt($ch, CURLOPT_PROXY, $proxy);
// curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
		'Connection: Close' 
) );
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
// $cert = __DIR__ . "./cacert.pem";
// curl_setopt($ch, CURLOPT_CAINFO, $cert);
	$res = curl_exec ( $ch );
	
	if (curl_errno ( $ch ) != 0){
		if (DEBUG == true) {
			error_log ( date ( '[Y-m-d H:i e] ' ) . "Can't connect to PayPal to validate IPN message: " . curl_error ( $ch ) . PHP_EOL, 3, LOG_FILE );
		}
		curl_close ( $ch );
		exit ();
	} else {
		// Log the entire HTTP response if debug is switched on.
		if (DEBUG == true) {
			error_log ( date ( '[Y-m-d H:i e] ' ) . "HTTP request of validation request:" . curl_getinfo ( $ch, CURLINFO_HEADER_OUT ) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE );
			error_log ( date ( '[Y-m-d H:i e] ' ) . "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE );
			// Split response headers and payload
			list ( $headers, $res ) = explode ( "\r\n\r\n", $res, 2 );
		}
		curl_close ( $ch );
	}
	
	$isVerified = (strpos($res, "VERIFIED") !== false);
	
	try {

		$registrationIds = explode ( ",", base64_decode ( $_POST ['custom'] ) );
		
		foreach ( $registrationIds as $registrationCode ) {
			
			if (DEBUG == true) {
			error_log ( date ( '[Y-m-d H:i e] ' ) . "REGISTRATION CODE:" . $registrationCode . PHP_EOL, 3, LOG_FILE );
			}
			
			$objRegistration = new Registration ();
			$objRegistration->loadByRegistrationCode ( $registrationCode );
			
			if( $isVerified ){
				
				if (DEBUG == true) {
				error_log ( date ( '[Y-m-d H:i e] ' ) . "REPLY WAS VERIFIED --------------------------------------------------" . PHP_EOL, 3, LOG_FILE );
				}
				
				if (!$objRegistration->isValid ())
					throw new UnexpectedValueException ( 'registrationCode' );
				
				$objRegistration->setConfirmationNumber ( $_POST ['txn_id'] );
				
				$payment_status = $_POST ['payment_status']; // read the payment details and the account holder
				
				if (DEBUG == true) {
				error_log ( date ( '[Y-m-d H:i e] ' ) . "PAYMENT STATUS:" . $payment_status . PHP_EOL, 3, LOG_FILE );
				}
				
				$objRegistration->setAmountPaid ( $_POST ['mc_gross'] );
				$objRegistration->setDatePaid ( time () );
				$objRegistration->setPaypalEmailAddress ( $_POST ['payer_email'] );
				$objRegistration->setIpnResponse ( $payment_status );
				$objRegistration->setIpnError ( 'None' );
				
				if ($_POST ['payment_status'] === 'Completed') {
					
					if (DEBUG == true) {
					error_log ( date ( '[Y-m-d H:i e] ' ) . "SENDING CONFIRMATION EMAIL ON COMPLETED PAYMENT STATUS:" . $_POST ['payment_status'] . PHP_EOL, 3, LOG_FILE );
					}
					
					$objRegistration->sendConfirmationEmail ();
				}
				
				if (! $objRegistration->updateDatabase ())
					throw new RecordUpdateException ();
				
				if (DEBUG == true) {
				error_log ( date ( '[Y-m-d H:i e] ' ) . "UPDATED DATABASE:" . $objRegistration->getId () . PHP_EOL, 3, LOG_FILE );
				}
				
			} elseif (strcmp ( $strReply, "INVALID" ) == 0)
				throw new UnexpectedValueException ( 'invalidIPN' );
		}
		
	} catch ( UnexpectedValueException $objException ) {
		
		error_log ( date ( '[Y-m-d H:i e] ' ) . "UnexpectedValueException EXCEPTION:" . $objException->getMessage () . PHP_EOL, 3, LOG_FILE );
		
		if ($objRegistration->isValid ()) {
			$objRegistration->setIpnError ( $objException->getMessage () );
			$objRegistration->setIpnResponse ( print_r ( $_POST, true ) );
			$objRegistration->updateDatabase ();
		}
	} catch ( RecordUpdateException $objException ) {
		
		error_log ( date ( '[Y-m-d H:i e] ' ) . "RECORD UPDATE EXCEPTION:" . $objException->getMessage () . PHP_EOL, 3, LOG_FILE );
		
	} catch ( Exception $objException ) {
		
		error_log ( date ( '[Y-m-d H:i e] ' ) . "ERROR:" . $objException->getMessage () . PHP_EOL, 3, LOG_FILE );
		
	}
?>