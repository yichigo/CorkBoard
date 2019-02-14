/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$tmp1=$_POST['Add_Title'];
$tmp2=$_POST['Category'];
$tmp3=$_POST['Visibility'];
$tmp4=$_POST['Password'];
$Email=$_SESSION['email'];

print $tmp1.$tmp2.$tmp3;

if($tmp3=="PRIVATE" and empty($tmp4)){
	$message = "ERROR: PRIVATE CorkBoard MUST have a password";
echo "<script type='text/javascript'>alert('$message');</script>";
	
}

else{
	
if ($tmp3=="PUBLIC"){
	

	$query = "INSERT INTO CorkBoard
	          VALUES('{$Email}','{$tmp1}','{$tmp2}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert CorkBoard...<br>" . __FILE__ ." line:". __LINE__ );
	}	
	
	$query = "INSERT INTO Public_CorkBoard
	          VALUES('{$Email}','{$tmp1}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Public_CorkBoard...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
}

else{
	

	
	$query = "INSERT INTO CorkBoard
	          VALUES('{$Email}','{$tmp1}','{$tmp2}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert CorkBoard...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
	
	    $query = "INSERT INTO Private_CorkBoard
	          VALUES('{$Email}','{$tmp1}','{$tmp4}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Private_CorkBoard...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
}

$message = "Successfully Add a CorkBoard";
echo "<script type='text/javascript'>alert('$message');</script>";
}
?>


		
