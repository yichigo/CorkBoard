<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$Watched_Email =$_POST['Watched_Email'];
$Watched_Title =$_POST['Watched_Title'];
$watch_status  =$_POST['watch_status'];
$Email=   $_SESSION['email'];
print $Email . ' ' .$Watched_Email . ' ' . $Watched_Title . ' '. $watch_status;


if ($watch_status==0){
$query = "INSERT INTO `Watch` 
          VALUES('{$Email}','{$Watched_Email}','{$Watched_Title}')";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                            if ($result==FALSE ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to Follow...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 
											 
                include("lib/error.php");
	$message = "CorkBoard has been watched";
echo "<script type='text/javascript'>alert('$message');</script>";			
}

else {
	
	$query = "DELETE FROM `Watch` 
          WHERE Email='$Email' AND Watched_Email='{$Watched_Email}' AND Title='{$Watched_Title}'";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                            if ($result==FALSE ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to Follow...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 
											 
                include("lib/error.php");
		$message = "CorkBoard has been unwatched";
echo "<script type='text/javascript'>alert('$message');</script>";
	
}

?>