/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}


$tmp1=$_POST['like_email'];
$tmp2=$_POST['like_title'];
$tmp3=$_POST['like_datetime'];
$tmp4=$_POST['like_status'];
$Email=$_SESSION['email'];


print $tmp1.' '.$tmp2.' '.$tmp3.' '.$tmp4;

if($tmp4==0){
	$query = "INSERT INTO `Liked`
	          VALUES('{$Email}','{$tmp1}','{$tmp2}','{$tmp3}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Pushpin...<br>" . __FILE__ ." line:". __LINE__ );
	}	

print "<br>";
print $query;
$message = "Pushpin has been liked";
echo "<script type='text/javascript'>alert('$message');</script>";

}

else {
	$query = "DELETE FROM `Liked`
	          WHERE Email='{$Email}' AND Liked_Email='{$tmp1}' AND Title='{$tmp2}' AND Add_Datetime='{$tmp3}'";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Pushpin...<br>" . __FILE__ ." line:". __LINE__ );
	}	
	print "<br>";
print $query;

$message = "Pushpin has been unliked";
echo "<script type='text/javascript'>alert('$message');</script>";
}



?>


		
