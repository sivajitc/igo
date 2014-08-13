<?php
session_start();
ob_start();
require_once("config.php");
$mprice=$_POST['mprice'];
$mmin=$_POST['mdisc'];
$mdisc=$_POST['mmin'];
//$_SESSION['username']="sivaji";

$sql1="select *from minuteprice where min_type='Mobile'";
$res1=mysql_query($sql1);
if(mysql_num_rows($res1)==1)
{
$sql="UPDATE  `minuteprice` SET  `price` =  '".$mprice."' WHERE  `minuteprice`.`min_type` ='Mobile'";
mysql_query($sql);
}
else
{
$ins="INSERT INTO  `minuteprice` (`sno`,`min_type`,`price`,`created_date` ,
`created_by`)VALUES (NULL ,'Mobile',  '".$mprice."',  '".date('Y-m-d')."',  '".$_SESSION['username']."')";
mysql_query($ins);
}

$add=$mmin+1;
$sql="SELECT Packageid FROM masterpackages WHERE ".$mmin." BETWEEN StartRange AND StopRange and `Packagetype`='Mobile'";

$res=mysql_query($sql);

if(mysql_num_rows($res)==1)
{
$arr=mysql_fetch_array($res);
$sql1="select StartRange,StopRange from masterpackages where Packageid=".$arr['Packageid'];
$arr1=mysql_query($sql1);
$res1=mysql_fetch_array($arr1);
//var_dump($res1);
//echo "<br>";
$sql2="Select StopRange,Packageid from masterpackages WHERE Packagetype='Mobile' and StopRange>".$mmin." order by StopRange Asc";
//echo $sql2;
$arr2=mysql_query($sql2);
$res2=mysql_fetch_array($arr2);

$ins="insert into masterpackages values(null,'Mobile',".$mdisc.",".$res1['StartRange'].",".$mmin.",1,'1','".date('Y-m-d')."','".$_SESSION['username']."','".date('Y-m-d')."','".$_SESSION['username']."','')";

$ups="UPDATE  `masterpackages` SET  `StartRange` =".$add." WHERE  `masterpackages`.`Packageid` =".$res2['Packageid']." and Packagetype='Mobile'";

$output1=mysql_query($ins);
$output2=mysql_query($ups);
if(($output1==1)&&($output2==1))
header("Location:editprices.php");
}
else
{
$sql3="select *From masterpackages where StartRange<".$mmin." and Packagetype='Mobile' order by StopRange desc";
$res3=mysql_query($sql3);
$arr3=mysql_fetch_array($res3);
$startrange=$arr3['StopRange']+1;

$sql="INSERT INTO `masterpackages` (`Packageid`, `Packagetype`,`Discount`, `StartRange`, `StopRange`, `Hsnumber`, `status`, `Created_date`, `Created_by`, `Updated_date`, `Updated_by`) VALUES (NULL, 'Mobile', '".$mdisc."', '".$startrange."', '".$mmin."', '10', '1', '".date('Y-m-d')."', '".$_SESSION['username']."', '".date('Y-m-d')."', '".$_SESSION['username']."')";
$res=mysql_query($sql);

if($res==1)
header("Location:editprices.php");
}
?>