<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$checkSum = "";
$paramList = array();

$NAME=$_POST["FNAME"];
$ADDR=$_POST["ADDR"];
$MOBI=$_POST["MOBI"];
$MAIL=$_POST["MAIL"];

$ORDERID="ORDER".time();

$date=date("Y-m-d");
$amount='299';
if(!isset($NAME)||!isset($MOBI)||!isset($ADDR)||!isset($MAIL))
exit("Error Occured");

//save to database
include("connect.php");
$cxn=mysqli_connect($host,$user,$pass,$db);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$sql="INSERT INTO `info`( `orderid`, `date`, `name`, `addr`, `mobi`, `amount`, `email`, `pay_status`) VALUES ('$ORDERID','$date','$NAME','$ADDR','$MOBI','$amount','$MAIL','false')";
$result=mysqli_query($cxn,$sql);
if(!$result)
echo("Error description: " . mysqli_error($cxn));

$CUST_ID = "M".$MOBI;
$INDUSTRY_TYPE_ID = $_POST["INDUSTRY_TYPE_ID"];
$CHANNEL_ID = $_POST["CHANNEL_ID"];
$TXN_AMOUNT = $amount;

// Create an array having all required parameters for creating checksum.
$paramList["MID"] = PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $ORDERID;
$paramList["CUST_ID"] = $CUST_ID;
$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
$paramList["CHANNEL_ID"] = $CHANNEL_ID;
$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
$paramList["MOBILE_NO"] = $MOBI;
// $paramList["ADDR"] = "hello";
$paraList["CUST_NAME"] = $ADDR;
$paramList["EMAIL"] = $MAIL;
$paramList["userInfo"]["firstName"]=$NAME;

$paramList["CALLBACK_URL"] = PAYTM_CALLBACK_URL;
/*
$paramList["CALLBACK_URL"] = "http://localhost/PaytmKit/pgResponse.php";
$paramList["MSISDN"] = $MSISDN; //Mobile number of customer
$paramList["EMAIL"] = $EMAIL; //Email ID of customer
$paramList["VERIFIED_BY"] = "EMAIL"; //
$paramList["IS_USER_VERIFIED"] = "YES"; //

*/

//Here checksum string will return by getChecksumFromArray() function.
$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

?>
<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<center><h1>Please do not refresh this page...</h1></center>
		<form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
		<table border="1">
			<tbody>
			<?php
			foreach($paramList as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>