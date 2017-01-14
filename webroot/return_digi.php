<?php
/**
*	PayPal Express Checkout for DG Sample Code.
*/

include('functions.php');	// PPHttpPost が定義されている.

//set GET var's to local vars:
$token   = $_GET['token'];
$payerid = $_GET['PayerID'];

  // *** 以下の★の設定を書きこんでください.
  $APIUSERNAME  = "seller_1351587539_biz_api1.ciasol.com";	// ★APIユーザー
  $APIPASSWORD  = "1351587554";			// ★APIパスワード
  $APISIGNATURE = "AiPC9BjkCyDFQXbSkoZcgqH3hpacAqBJN9ouB28iaB1KjStM7HNkMLPG";	// ★API認証コード
//  $ENDPOINT     = "https://api-3t.paypal.com/nvp";
  $ENDPOINT     = "https://api-3t.sandbox.paypal.com/nvp";
  $VERSION      = "65.1"; //must be >= 65.1

//Build the Credential String:
$cred_str =
	"USER=" . $APIUSERNAME .
	"&PWD=" . $APIPASSWORD .
	"&SIGNATURE=" . $APISIGNATURE .
	"&VERSION=" . $VERSION;

//Build NVP String for GetExpressCheckoutDetails
$nvp_str = "&METHOD=GetExpressCheckoutDetails&TOKEN=" . urldecode($token);

//combine the two strings and make the API Call
$req_str = $cred_str . $nvp_str;
$response = PPHttpPost($ENDPOINT, $req_str);

//get total
$total = urldecode($response['PAYMENTREQUEST_0_AMT']);

?>
<html>
<head>
	<title>Confirm your payment</title>
</head>
<body>
<table border='1'>
<tr>
	<td colspan='5'>Confirm Your Purchase</td>
</tr>
<tr>
	<td colspan='3'>Total:</td><td colspan='2'><?php echo $total; ?></td>
</tr>
</table>

<?php

if(isset($_POST['confirm']) && $_POST['confirm'] == "Confirm") // 確認O.K
{
// *** ここに商品情報、価格が埋め込まれています。
// *** 必要に応じて書き換えてください。
 $doec_str  = $cred_str . "&METHOD=DoExpressCheckoutPayment"
	. "&TOKEN=" . $token
	. "&PAYERID=" . $payerid
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

	//make the DoEC Call:
	$doresponse = PPHttpPost($ENDPOINT, $doec_str);

	//check Response
	if($doresponse['ACK'] == "Success" || $doresponse['ACK'] == "SuccessWithWarning")
	{
		echo "Your Payment Has Completed! <br/>\n";
		// ここにダウンロード先のURLを記述しておく.
		echo 'Download item is <a href="#">HERE!</a><br/>';
		// 戻るボタンに、決済フローを閉じるためのJavaScriptを埋め込んでいる.
		echo '<form action=""><input type="button" value="return to top" onclick="javasctipt:top.dg.closeFlow();"></form>';
		echo '<span style="cursor: pointer" onclick="javasctipt:top.dg.closeFlow();">検索</span>';
	}
	else if($doresponse['ACK'] == "Failure" || $doresponse['ACK'] == "FailureWithWarning")
	{
		echo "ERROR: The API Call Failed, after Confirm.<br/>\n";
		print_r($doresponse);
	}
}
else // ユーザー最終確認
{
	echo "<form action='' method='post'><input type='submit' name='confirm' value='Confirm' /></form>";
	echo "Please confirm your purchase. Are you O.K? <br/>\n";
}
?>
</body>
</html>

