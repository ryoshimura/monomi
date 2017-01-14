<?php
/**
*	PayPal Express for DG Checkout Sample Code.
*/

include('functions.php');	// PPHttpPost が定義されている.

  // *** 以下の★の設定を書きこんでください.
  $APIUSERNAME  = "seller_1351587539_biz_api1.ciasol.com";	// ★APIユーザー
  $APIPASSWORD  = "1351587554";			// ★APIパスワード
  $APISIGNATURE = "AiPC9BjkCyDFQXbSkoZcgqH3hpacAqBJN9ouB28iaB1KjStM7HNkMLPG";	// ★API認証コード
//  $ENDPOINT     = "https://api-3t.paypal.com/nvp";
  $ENDPOINT     = "https://api-3t.sandbox.paypal.com/nvp";
  $VERSION      = "65.1"; //must be >= 65.1
//  $REDIRECTURL  = "https://www.paypal.com/incontext?token=";
  $REDIRECTURL  = "https://www.sandbox.paypal.com/incontext?token=";

  $MySITE = "http://monomi.info/";	// ★あなたがこのPHPを置くサイトのURL
  $RETURN_CODE = "return_digi.php";
//  $CANCEL_RETURN_CODE = "cancel_return_digi.html";
//  $CANCEL_RETURN_CODE = "cancel_return_digi.php";
  $CANCEL_RETURN_CODE = "regist/bcancel/";

//Build the Credential String:
$cred_str =
	"USER=" . $APIUSERNAME .
	"&PWD=" . $APIPASSWORD .
	"&SIGNATURE=" . $APISIGNATURE .
	"&VERSION=" . $VERSION;

// *** ここに商品情報、価格が埋め込まれています。
// *** 必要に応じて書き換えてください。
$nvp_str  = "&METHOD=SetExpressCheckout"
	. "&RETURNURL=" . $MySITE . $RETURN_CODE
	. "&CANCELURL=". $MySITE . $CANCEL_RETURN_CODE
	. "&LOCALECODE=JP" // EN
	. "&PAYMENTREQUEST_0_CURRENCYCODE=JPY" // USD"
	. "&PAYMENTREQUEST_0_AMT=210"
	. "&PAYMENTREQUEST_0_ITEMAMT=200"
	. "&PAYMENTREQUEST_0_TAXAMT=10"
	. "&PAYMENTREQUEST_0_DESC=Movies"
	. "&PAYMENTREQUEST_0_PAYMENTACTION=Sale"
	. "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=Digital"
	. "&L_PAYMENTREQUEST_0_ITEMCATEGORY1=Digital"
	. "&L_PAYMENTREQUEST_0_NAME0=Kitty_Antics"
	. "&L_PAYMENTREQUEST_0_NAME1=All_About_Cats"
	. "&L_PAYMENTREQUEST_0_NUMBER0=101"
	. "&L_PAYMENTREQUEST_0_NUMBER1=102"
	. "&L_PAYMENTREQUEST_0_QTY0=1"
	. "&L_PAYMENTREQUEST_0_QTY1=1"
	. "&L_PAYMENTREQUEST_0_TAXAMT0=5"
	. "&L_PAYMENTREQUEST_0_TAXAMT1=5"
	. "&L_PAYMENTREQUEST_0_AMT0=100"
	. "&L_PAYMENTREQUEST_0_AMT1=100"
	. "&L_PAYMENTREQUEST_0_DESC0=Kitty_Item"
	. "&L_PAYMENTREQUEST_0_DESC1=Cats_World";

//combine the two strings and make the API Call
$req_str = $cred_str . $nvp_str;

$response = PPHttpPost($ENDPOINT, $req_str);

//check Response
if($response['ACK'] == "Success" || $response['ACK'] == "SuccessWithWarning")
{
	//setup redirect URL for Digital
	$redirect_url = $REDIRECTURL . urldecode($response['TOKEN']);
}
else if($response['ACK'] == "Failure" || $response['ACK'] == "FailureWithWarning")
{
	echo "ERROR: The API Call Failed";
	print_r($response);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>PayPal - Express Checkout for DG</title>
</head>
<body>

<a href="<?php echo $redirect_url ?>" id='submitBtn'><img src='https://www.paypal.com/ja_JP/i/btn/btn_dg_pay_w_paypal.gif' border='0' /></a>

<!-- *** PayPalの提供するJavaScriptを埋め込む *** -->
<script src ='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
<script>
// "submitBtn" を押すと、Digital Goodsの決済フローが始まる.
var dg = new PAYPAL.apps.DGFlow({
	// the HTML ID of the form submit button which calls setEC
	trigger: "submitBtn"
});
</script>

</body>
</html>
