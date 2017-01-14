<?php
class Paypal extends AppModel {

    var $name = 'Paypal';
    var $useTable = false;

/*
    var $apiServer = PAYPAL_API_SERVER;
    var $expressCheckoutUrl = PAYPAL_EXPRESS_CHECKOUT_URL;
    var $username = PAYPAL_API_USERNAME;
    var $password = PAYPAL_API_PASSWORD;
    var $signature = PAYPAL_API_SIGNATURE;
    var $returnURL;
    var $cancelURL;
*/

    var $errorMsg = array();

	var $PROXY_HOST = '127.0.0.1';
	var $PROXY_PORT = '808';
//	var $SandboxFlag = true;
//	var $sBNCode = "PP-ECWizard";

/*
	// Paypal sandbox
	var $API_UserName="seller_1351587539_biz_api1.ciasol.com";
	var $API_Password="1351587554";
	var $API_Signature="AiPC9BjkCyDFQXbSkoZcgqH3hpacAqBJN9ouB28iaB1KjStM7HNkMLPG";
	var $API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
	var $PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
*/

	// Paypal 本番環境
	var $API_UserName="ryoshimura_api1.ciasol.com";
	var $API_Password="WM27RJVUDJJUJNYW";
	var $API_Signature="AsadXp.puXdi0rmCOTLCbLIMXsK5Ac3crRVd0mit0gisD3CHGu0Eh5oN";
	var $API_Endpoint = "https://api-3t.paypal.com/nvp";
	var $PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";



	var $USE_PROXY = false;
	var $version = '93';




    function __construct() {
//        vendor('Request');
        App::import('Vendor', 'Request');
        $this->returnURL = FULL_BASE_URL.'/mypages/confirm';
        $this->cancelURL = FULL_BASE_URL.'/mypages/cancel';


//       	if (session_id() == "")
//			session_start();
    }


    // Pear::HTTP_REQUESTが機能しているかテストするメソッド（適当なタイミングで削除してOK）
    function testPear () {

        $req = new HTTP_Request('http://nisodo.com/');
        $req->setMethod(HTTP_REQUEST_METHOD_GET);

        if (!PEAR::isError($req->sendRequest())) {
            $rawResult = $req->getResponseBody();
            parse_str($rawResult, $result);
			pr($result);
        }
    }



    // SetExpressCheckout
    function getToken ( $amount, $returnURL, $cancelURL, $itemName, $user_uid ) {

        App::import('Vendor', 'PaypalFunction', array('file' => 'paypal'.DS.'paypalfunctions.php'));

        $paymentAmount		= $amount;
        $currencyCodeType	= "JPY";
        $paymentType		= "Sale";
        $returnURL			= $returnURL;
        $cancelURL			= $cancelURL;

/*
		$shipToName = "<<ShiptoName>>";
		$shipToStreet = "<<ShipToStreet>>";
		$shipToStreet2 = "<<ShipToStreet2>>";
		$shipToCity = "<<ShipToCity>>";
		$shipToState = "<<ShipToState>>";
		$shipToCountryCode = "<<ShipToCountryCode>>";
		$shipToZip = "<<ShipToZip>>";
		$phoneNum = "<<PhoneNumber>>";
*/

		$resArray = $this->CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $itemName, $user_uid);
//		$resArray = $this->CallMarkExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState, $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum );

//pr($resArray);

		return $resArray;
    }



	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	' Inputs:
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'--------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function CallShortcutExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $itemName, $user_uid )
	{
		//------------------------------------------------------------------------------------------------------------------------------------
		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

		$nvpstr="&PAYMENTREQUEST_0_AMT=". $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;

		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NAME0=" . $itemName;
		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_AMT0=" . $paymentAmount;
		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_QTY0=1";
		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=" . 'Digital';

		$nvpstr = $nvpstr . "&REQCONFIRMSHIPPING=0";
		$nvpstr = $nvpstr . "&NOSHIPPING=" . '1';

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_ITEMAMT=" . $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_INVNUM=" . $user_uid;

//		$_SESSION["currencyCodeType"] = $currencyCodeType;
//		$_SESSION["PaymentType"] = $paymentType;

		//'---------------------------------------------------------------------------------------------------------------
		//' Make the API call to PayPal
		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.
		//' If an error occured, show the resulting errors
		//'---------------------------------------------------------------------------------------------------------------
	    $resArray = $this->hash_call("SetExpressCheckout", $nvpstr);

		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
//			$token = urldecode($resArray["TOKEN"]);
//			$_SESSION['TOKEN']=$token;
		}

	    return $resArray;
	}

	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	' Inputs:
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'		shipToName:		the Ship to name entered on the merchant's site
	'		shipToStreet:		the Ship to Street entered on the merchant's site
	'		shipToCity:			the Ship to City entered on the merchant's site
	'		shipToState:		the Ship to State entered on the merchant's site
	'		shipToCountryCode:	the Code for Ship to Country entered on the merchant's site
	'		shipToZip:			the Ship to ZipCode entered on the merchant's site
	'		shipToStreet2:		the Ship to Street2 entered on the merchant's site
	'		phoneNum:			the phoneNum  entered on the merchant's site
	'--------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function CallMarkExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL,
									  $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState,
									  $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum
									)
	{
		//------------------------------------------------------------------------------------------------------------------------------------
		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

		$nvpstr="&PAYMENTREQUEST_0_AMT=". $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;
		$nvpstr = $nvpstr . "&ADDROVERRIDE=1";
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTONAME=" . $shipToName;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET=" . $shipToStreet;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET2=" . $shipToStreet2;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCITY=" . $shipToCity;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTATE=" . $shipToState;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . $shipToCountryCode;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOZIP=" . $shipToZip;
//		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOPHONENUM=" . $phoneNum;

//		$_SESSION["currencyCodeType"] = $currencyCodeType;
//		$_SESSION["PaymentType"] = $paymentType;

		//'---------------------------------------------------------------------------------------------------------------
		//' Make the API call to PayPal
		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.
		//' If an error occured, show the resulting errors
		//'---------------------------------------------------------------------------------------------------------------
	    $resArray=$this->hash_call("SetExpressCheckout", $nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
//			$token = urldecode($resArray["TOKEN"]);
//			$_SESSION['TOKEN']=$token;
		}

	    return $resArray;
	}

	/*
	'-------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:
	'		None
	' Returns:
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'-------------------------------------------------------------------------------------------
	*/
	function GetShippingDetails( $token )
	{
		//'--------------------------------------------------------------
		//' At this point, the buyer has completed authorizing the payment
		//' at PayPal.  The function will call PayPal to obtain the details
		//' of the authorization, incuding any shipping information of the
		//' buyer.  Remember, the authorization is not a completed transaction
		//' at this state - the buyer still needs an additional step to finalize
		//' the transaction
		//'--------------------------------------------------------------

	    //'---------------------------------------------------------------------------
		//' Build a second API request to PayPal, using the token as the
		//'  ID to get the details on the payment authorization
		//'---------------------------------------------------------------------------
	    $nvpstr="&TOKEN=" . $token;

		//'---------------------------------------------------------------------------
		//' Make the API call and store the results in an array.
		//'	If the call was a success, show the authorization details, and provide
		//' 	an action to complete the payment.
		//'	If failed, show the error
		//'---------------------------------------------------------------------------
	    $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);
	    $ack = strtoupper($resArray["ACK"]);
		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
//			$_SESSION['payer_id'] =	$resArray['PAYERID'];
		}
		return $resArray;
	}

	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns:
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------
	*/
//	function ConfirmPayment( $FinalPaymentAmt, $token, $payer_id )
	function ConfirmPayment( $FinalPaymentAmt, $token, $payer_id, $itemName, $paymentAmount )
	{
		/* Gather the information to make the final call to
		   finalize the PayPal payment.  The variable nvpstr
		   holds the name value pairs
		   */


		//Format the other parameters that were stored in the session from the previous calls
		$token 				= urlencode($token);
		$paymentType 		= urlencode("Sale");
		$currencyCodeType 	= urlencode("JPY");
		$payerID 			= urlencode($payer_id);

		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName;

		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NAME0=" . $itemName;
		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=" . 'Digital';
		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_AMT0=" . $paymentAmount;
		$nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_QTY0=1";
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_ITEMAMT=" . $FinalPaymentAmt;
//		$nvpstr = $nvpstr . "&REQCONFIRMSHIPPING=0";
//		$nvpstr = $nvpstr . "&NOSHIPPING=1";





		 /* Make the call to PayPal to finalize payment
		    If an error occured, show the resulting errors
		    */
		$resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr);

		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	}

	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	This function makes a DoDirectPayment API call
	'
	' Inputs:
	'		paymentType:		paymentType has to be one of the following values: Sale or Order or Authorization
	'		paymentAmount:  	total value of the shopping cart
	'		currencyCode:	 	currency code value the PayPal API
	'		firstName:			first name as it appears on credit card
	'		lastName:			last name as it appears on credit card
	'		street:				buyer's street address line as it appears on credit card
	'		city:				buyer's city
	'		state:				buyer's state
	'		countryCode:		buyer's country code
	'		zip:				buyer's zip
	'		creditCardType:		buyer's credit card type (i.e. Visa, MasterCard ... )
	'		creditCardNumber:	buyers credit card number without any spaces, dashes or any other characters
	'		expDate:			credit card expiration date
	'		cvv2:				Card Verification Value
	'
	'-------------------------------------------------------------------------------------------
	'
	' Returns:
	'		The NVP Collection object of the DoDirectPayment Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------
	*/


	function DirectPayment( $paymentType, $paymentAmount, $creditCardType, $creditCardNumber,
							$expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip,
							$countryCode, $currencyCode )
	{
		//Construct the parameter string that describes DoDirectPayment
		$nvpstr = "&AMT=" . $paymentAmount;
		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCode;
		$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;
		$nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;
		$nvpstr = $nvpstr . "&EXPDATE=" . $expDate;
		$nvpstr = $nvpstr . "&CVV2=" . $cvv2;
		$nvpstr = $nvpstr . "&FIRSTNAME=" . $firstName;
		$nvpstr = $nvpstr . "&LASTNAME=" . $lastName;
		$nvpstr = $nvpstr . "&STREET=" . $street;
		$nvpstr = $nvpstr . "&CITY=" . $city;
		$nvpstr = $nvpstr . "&STATE=" . $state;
		$nvpstr = $nvpstr . "&COUNTRYCODE=" . $countryCode;
		$nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];

		$resArray=$this->hash_call("DoDirectPayment", $nvpstr);

		return $resArray;
	}


	/**
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function hash_call($methodName,$nvpStr)
	{

		//declaring of global variables
//		global $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature;
//		global $USE_PROXY, $PROXY_HOST, $PROXY_PORT;
//		global $gv_ApiErrorURL;
//		global $sBNCode;

		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
//pr('API_ENDPOINT: '. $this->API_Endpoint);
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);

	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
		if($this->USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST. ":" . $this->PROXY_PORT);

		//NVPRequest for submitting to server
//		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->API_Password) . "&USER=" . urlencode($this->API_UserName) . "&SIGNATURE=" . urlencode($this->API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($this->sBNCode);
		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->API_Password) . "&USER=" . urlencode($this->API_UserName) . "&SIGNATURE=" . urlencode($this->API_Signature) . $nvpStr;
//pr($nvpreq);
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
//		$_SESSION['nvpReqArray']=$nvpReqArray;

		if (curl_errno($ch))
		{
			// moving to display page to display curl errors
//			  $_SESSION['curl_error_no']=curl_errno($ch) ;
//			  $_SESSION['curl_error_msg']=curl_error($ch);

			  //Execute the Error handling module to display errors.
		}
		else
		{
			 //closing the curl
		  	curl_close($ch);
		}

		return $nvpResArray;
	}





	/*'----------------------------------------------------------------------------------
	 Purpose: Redirects to PayPal.com site.
	 Inputs:  NVP string.
	 Returns:
	----------------------------------------------------------------------------------
	*/
	public function RedirectToPayPal ( $token )
	{
//		global $PAYPAL_URL;

		// Redirect to paypal.com here
		$payPalURL = $this->PAYPAL_URL . $token;
		header("Location: ".$payPalURL);
		exit;
	}





	/*'----------------------------------------------------------------------------------
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	   ----------------------------------------------------------------------------------
	  */
	function deformatNVP($nvpstr)
	{
		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}

}
?>