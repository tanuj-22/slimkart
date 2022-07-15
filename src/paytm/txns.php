<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Txn List</title>
<meta name='HandheldFriendly' content='True'/>
<meta name='MobileOptimized' content='320'/>
<meta name="viewport" content="initial-scale=1, maximum-scale=1"/>
<meta content='en' name='language'/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
<style>
@import url(//fonts.googleapis.com/css?family=Droid+Sans);
*{font-family:'Droid Sans',sans-serif;}
/* Paper CSS */
@page { margin: 0 }
body { margin: 0 }
.sheet {
  margin: 0;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  page-break-after: always;
}

/** Paper sizes **/
body.A3           .sheet { width: 297mm; height: 419mm }
body.A3.landscape .sheet { width: 420mm; height: 297mm }
body.A4           .sheet { width: 212mm; height: 296mm }
body.A4.landscape .sheet { width: 297mm; height: 209mm }
body.A5           .sheet { width: 148mm; height: 209mm }
body.A5.landscape .sheet { width: 210mm; height: 147mm }

/** Padding area **/
.sheet.padding-5mm { padding: 5mm; padding-top:6mm;}
.sheet.padding-10mm { padding: 10mm }
.sheet.padding-15mm { padding: 15mm }
.sheet.padding-20mm { padding: 20mm }
.sheet.padding-25mm { padding: 25mm }

/** For screen preview **/
@media screen {
  body { background: #e0e0e0 }
  .sheet {
    background: white;
    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
    margin: 5mm;
  }
}

/* Custom CSS */
.row{width:100%;}
.card{width:93mm;max-width:94mm;min-height:43mm;max-height:43mm;border:solid 1mm #fff;padding-left:10px;border-radius:5px;overflow:hidden;text-align: left;margin:1.5mm 1mm;float:left;}
.card:nth-child(even){width:93mm;max-width:94mm;min-height:43mm;max-height:43mm;border:solid 1mm #fff;padding-left:13px;border-radius:5px;overflow:hidden;text-align: left;margin:1.5mm 1mm;float:right;}
/*
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block;page-break-after:always; }
}
*/
</style>
</head>
<body class="A4">
<center>
<section class="sheet padding-5mm">
<?php
include("connect.php");
$cxn=mysqli_connect($host,$user,$pass,$db);
$year=date("Y");
if($_GET["year"])
$year=$_GET['year'];
//$start=$end=0;
$start=$_GET["start"];
$end=$_GET["end"];
$sql="SELECT * FROM `info` WHERE `pay_status`='true'";
echo "<!--$sql-->";
mysqli_query($cxn,"SET NAMES 'utf8'");
$query=mysqli_query($cxn,$sql);
$str=0;
$count=0;
$page=0;
while($info = mysqli_fetch_array($query))
{ 
	//take mobile no from cart
    $mobile= $info['mobi'];
	$phone="Mob +91-".$mobile;
    $name=$info['name'];
    $addr=$info['addr'];
    $mail=$info["email"];
    $date=$info["date"];
    $orderid=$info["orderid"];
	$str++;
	
?>
	<div class="card">
	<?php echo "<center style='height:4px;color:lightgray;'><small>$date</small></center><small>$orderid</small><hr/>".$name."<br/>".$addr."<br/>".$phone;?>
	</div>
<?php
	if($str==12)
	{
	$str=0;
	$page++;
	echo "$page</section><section class=\"sheet padding-5mm\">";
	}
}	
mysqli_close($cxn);
?>
</section>
</center>
</body>
</html>