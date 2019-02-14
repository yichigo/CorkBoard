/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

date_default_timezone_set('America/Chicago');


$tmp1=$_POST['Add_URL'];
$tmp2=$_POST['Add_Description'];
$tmp3=$_POST['Add_Tags'];
$tmp4=$_POST['Opened_Title'];
$Email=$_SESSION['email'];
$Add_Datetime=date('Y-m-d H:i:s', time());

print $tmp1.' '.$tmp2.' '.$tmp3.' '.$tmp4.' '.$Add_Datetime;

	

$picture=array('jpeg','jfif','jpg','png','gif','tiff','exif','bmp','bat','bpg','svg');
$s1=strtolower(end(explode(".",$tmp1)));

$description_len =  strlen($tmp2);

$tag_len_max=0;
foreach(explode(",",$tmp3) as $temp_len){
	if(strlen($temp_len) >$tag_len_max) $tag_len_max=strlen($temp_len);
}


if (!in_array($s1,$picture) ) {
$message = "please choose one from: jpeg, jiff, jpg, png, gif, tiff, exif, bmp, bat, bpg, svg";
echo "<script type='text/javascript'>alert('$message');</script>";
exit();
}

else if ($description_len>200){
$message = "Description length should be less than 200 characters";
echo "<script type='text/javascript'>alert('$message');</script>";	
exit();
}

else if ($tag_len_max>20){
$message = "A single tag should be less than 20 characters";
echo "<script type='text/javascript'>alert('$message');</script>";	
exit();
}

else if (filter_var($tmp1, FILTER_VALIDATE_URL)==FALSE){
$message = "NOT a valid URL";
echo "<script type='text/javascript'>alert('$message');</script>";	
exit();
}

else {
	$query = "INSERT INTO `Pushpin`
	          VALUES('{$Email}','{$tmp4}','{$Add_Datetime}','{$tmp1}','{$tmp2}')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Pushpin...<br>" . __FILE__ ." line:". __LINE__ );
	}	

print "<br>";
print $query;
$tags=array_unique(explode(",",strtolower($tmp3)));

if (!isset($tags)){
foreach	($tags as $tag){
	
		$query = "INSERT INTO `PushPin_Tag`
	          VALUES('{$Email}','{$tmp4}','{$Add_Datetime}','{$tag}')"; 
     $result = mysqli_query($db, $query);			  
     include('lib/show_queries.php');
    if ($result==FALSE ) {
    array_push($error_msg,  "Query ERROR: Failed to insert Pushpin Tag...<br>" . __FILE__ ." line:". __LINE__ );

      }
	print "<br>";
	print $query;
}
}
$message = "Successfully Add a New PushPin";
echo "<script type='text/javascript'>alert('$message');</script>";
}


?>


		
