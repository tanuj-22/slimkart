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

// echo "ORDERID:".mktime();
$ORDERID="ORDER".time();

$date=date("d-m-Y");
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


$paramList["MID"] = PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $ORDERID;
$paramList["CUST_ID"] = "M".$MOBI;
// $paramList["MOBILE_NO"] = $MOBI;
// $paramList["CUST_NAME"] = $ADDR;

// $paramList["EMAIL"] = $MAIL;
$paramList["INDUSTRY_TYPE_ID"] = "Retail";
$paramList["CHANNEL_ID"] = "WEB";
$paramList["TXN_AMOUNT"] = $amount;
$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
/* Custom Field Added*/
$paramList["userInfo"]["FIRSTNAME"]=$NAME;
$paramList["userInfo"]["Address"]=$ADDR;
$paramList["userInfo"]["Mobile"]=$MOBI;
$paramList["userInfo"]["Email"]=$MAIL;
$paramList["userInfo"]["CUST_ID"]="M".$MOBI;

$paramList["CALLBACK_URL"] = PAYTM_CALLBACK_URL;

// $paramList = array(
// 	"MID" => PAYTM_MERCHANT_MID,
// 	"ORDER_ID" => $ORDERID,
// 	"CUST_ID" => "M".$MOBI,
// 	"MOBILE_NO" => $MOBI,
// 	"CUST_NAME" => $ADDR,
// 	"EMAIL" => $MAIL,
// 	"INDUSTRY_TYPE_ID" => "Retail",
// 	"CHANNEL_ID" => "WEB",
// 	"TXN_AMOUNT" => $amount,
// 	"WEBSITE" => PAYTM_MERCHANT_WEBSITE,
// 	"CALLBACK_URL" => PAYTM_CALLBACK_URL,
// 	"userInfo" => array(
// 		"firstName" => $NAME,
// 	)
// 	);

$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

?>
<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
    
	<center>
        <h1>Please do not refresh this page...</h1></center>
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