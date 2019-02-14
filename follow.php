<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$Followed=$_POST['Followed_Email'];
$follow_status = $_POST['follow_status'];
$Email=   $_SESSION['email'];

print $Followed. ' '.$Email.' '.$follow_status;



if($follow_status==0){
$query = "INSERT INTO `Follow` 
          VALUES('{$Email}','{$Followed}')";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                            if ($result==FALSE ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to Follow...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 
											 
				$message = "User has been followed";
echo "<script type='text/javascript'>alert('$message');</script>";
}

else {
	
	$query = "DELETE FROM `Follow` 
          WHERE Email='{$Email}' AND Followed_Email='{$Followed}'";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                            if ($result==FALSE ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to Follow...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 
											 
                include("lib/error.php");
				$message = "User has been unfollowed";
echo "<script type='text/javascript'>alert('$message');</script>";
	
}
?>