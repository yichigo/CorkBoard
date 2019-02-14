/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

date_default_timezone_set('America/Chicago');


$tmp1=$_POST['comment'];
$tmp2=$_POST['com_email'];
$tmp3=$_POST['com_title'];
$tmp4=$_POST['com_datetime'];
$Email=$_SESSION['email'];
$com_datetime=date('Y-m-d H:i:s', time());

print $tmp1.' '.$tmp2.' '.$tmp3.' '.$tmp4;


	$query = "INSERT INTO `Comment`
	          VALUES('{$Email}','{$tmp2}','{$tmp3}','{$tmp4}','{$com_datetime}','{$tmp1}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Comment...<br>" . __FILE__ ." line:". __LINE__ );
	}	

print "<br>";
print $query;



?>


		
