<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	//echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		//echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
        include("connect.php");
        $cxn=mysqli_connect($host,$user,$pass,$db);
        $orderid=$_POST["ORDERID"];
        $sql="UPDATE `info` SET `pay_status`='true' WHERE `orderid`='$orderid'";
        $result=mysqli_query($cxn,$sql);
        //
        $sql="SELECT * FROM `info` WHERE `orderid`='$orderid'";
        echo "<!--$sql-->";
        mysqli_query($cxn,"SET NAMES 'utf8'");
        $query=mysqli_query($cxn,$sql);
        while($info = mysqli_fetch_array($query))
        { 
        //take mobile no from cart
        $mobile= $info['mobi'];
        $phone="Mob +91-".$mobile;
        $name=$info['name'];
        $addr=$info['addr'];
        $mail=$info["email"];
        $date=$info["date"];
        }
        
        $from = 'From: pancholi.tanuj8@gmail.com'; 
        $to = $mail; 
        $subject = "New Order Received | $orderid";
        $body = "Details:\n<br/>OrderId:$orderid\n<br/>Name:$name\n<br/>Address:$addr\n<br/>Date:$date";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html\r\n";
        $headers .= 'From: pancholi.tanuj8@gmail.com' . "\r\n" .
        'Reply-To: pancholi.tanuj8@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
        mail($to, $subject, $body, $headers);
        //send mail to admin
        $from = 'From: pancholi.tanuj8@gmail.com'; 
        $to = 'pancholi.tanuj8@gmail.com'; 
        $subject = "New Order Received | $orderid";
        $body = "Details:\n<br/>OrderId:$orderid\n<br/>Name:$name\n<br/>Address:$addr\n<br/>Date:$date";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html\r\n";
        $headers .= 'From: pancholi.tanuj8@gmail.com' . "\r\n" .
        'Reply-To: pancholi.tanuj8@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
        mail($to, $subject, $body, $headers);
        if(!$result)
        exit("Failed :: Error Occured".$sql);
	}
	else {
		exit("<b>Transaction Failed</b>");
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 
?>
<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
        .btn {
        border: 2px solid #88B04B;
        border-radius: 0.6em;
        color: #88B04B;
        cursor: pointer;
        display: flex;
        align-self: center;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1;
        margin: 20px;
        padding: 1.2em 2.8em;
        text-decoration: none;
        text-align: center;
        text-transform: uppercase;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        width:10%;margin:auto;
        text-align:center;
        background:#F8FAF5;
        }
    </style>
    <body>
        <center><img src="logo.png" style="max-width: 250px;margin: 20px;"/></center>
      <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
      </div>
<h1>#<?php echo $orderid;?></h1>
    <p>We received your Payment;<br/> Our Team will be in touch shortly!</p>
      </div>
      <div style="clear:both;float:none"><br/></div>
<center></center><a href="https://order.slimkart.com" class="btn">Back to Home</a></center>
    </body>
</html>
  
<?php
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>