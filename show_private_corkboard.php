/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}
    $query = "CREATE OR REPLACE VIEW Corkboard_ALL  AS
(SELECT C.Email, C.Title, CategoryID,
       CASE
        WHEN P.Password IS NOT NULL THEN 'PRIVATE'
        ELSE 'PUBLIC'
        END AS Type 
FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_Corkboard` AS P on C.Email=P.Email AND C.Title=P.Title)";
             
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create VIEW Corkboard_ALL...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
	$temp1=$_SESSION['k1'];
	$temp2=$_SESSION['k2'];
	$query = "CREATE OR REPLACE VIEW View_Cockboard AS
(SELECT Name, CA.Email, CA.Title, URL, Description, Add_Datetime, Category, Type
FROM `Corkboard_ALL` as CA LEFT OUTER JOIN Pushpin AS P ON CA.Email=P.Email AND CA.Title=P.Title INNER JOIN `Category` AS C ON C.CategoryID=CA.CategoryID INNER JOIN `User` AS U on U.Email=CA.Email
WHERE CA.Email='{$temp1}' AND CA.Title='{$temp2}')";
             
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create VIEW View_Cockboard...<br>" . __FILE__ ." line:". __LINE__ );
	}
?>



<html>

<head>		
<?php include("lib/header.php"); ?>	
<title>View CorkBoard</title>
</head>
	
	<body>
	<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

            <div class="title_name">	
			<?php print "CorkBoardIt<br>";print "Explore. Share. Inspire<br><br>"; ?>
            </div>  

            <div class="title_name">
			<?php
			                                $query = "SELECT MAX(Name) AS Name, MAX(Email) AS Email, MAX(Title) AS Title, MAX(Category) AS Category, MAX(Add_Datetime) AS Add_Datetime
                                             FROM View_Cockboard";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get CorkBoard information...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
											 $opened_email=$row['Email'];
											 $opened_title=$row['Title'];
											 
			                                $query = "SELECT * FROM Follow WHERE Email='{$_SESSION['email']}' AND Followed_Email='{$opened_email}'";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (mysqli_num_rows($result) == 0)  {
                                                    $follow_status=0;
                                             }
											 else   $follow_status=1;
								
			?>
			
			            <?php echo $row['Name'];?>
						
						<style>
                        .hide { position:absolute; top:-1px; left:-1px; width:1px; height:1px; }
                        </style>
						<iframe name="hiddenFrame" class="hide"></iframe>
						<script>
						function temp_fun(){
							location.reload();
						}
						</script>
										   
						<form   method="POST" action="follow.php" target= "hiddenFrame" id="follow" onsubmit="temp_fun()"> 		
						<input  type='hidden' name='Followed_Email' value="<?php echo $opened_email;?>">
						<input  type='hidden' name='follow_status' value="<?php echo $follow_status;?>">
						<?php
						if ($opened_email==$_SESSION['email']){
						print '<button type="submit" disabled form="follow" value="Follow"> Follow User !</button>';
						}
						elseif ($follow_status==1){
						print '<button type="submit"  form="follow" value="Unfollow"> Unfollow User!</button>';
						}	
						else print '<button type="submit"  form="follow" value="Follow">Follow User !</button>';
						

						?>
						</form>
			
			
            </div>
			
             <div class="title_name">
			 <table>
			 <tr>
			 <th> <?php echo $row['Title'];?> </th>
			 </tr>
			 
			 <tr>
			 <th> <?php echo "Category: ". $row['Category'];?> </th>
			 <th> <?php if(empty($row['Add_Datetime'])) echo "NO PushPin"; else  echo "Last Updated:  " . date("F j, Y \a\\t g:i a",strtotime($row['Add_Datetime']));?> </th>
			 <th> 

			 		    <form   method="post" action="add_pushpin.php" id="add_push"> 		
						<input  type='hidden' name='Email' value=<?php echo $_SESSION['email'] ?>">
						<input  type='hidden' name='Opened_Title' value="<?php echo $opened_title ?>">
						<?php
						if ($row['Email']== $_SESSION['email']){
						print '<button type="submit" form="add_push" value="Add PushPin"> Add PushPin</button>';
						}
						else print '<button type="submit" disabled form="add_push" value="Add PushPin"> Add PushPin</button>';
						?>
						</form>
			</th>
			</tr>	
			
 			<?php
			                                $query = "SELECT Name, Email, Title, URL, Add_Datetime, Description, Category, Type 
                                            FROM View_Cockboard";
                                            $result = mysqli_query($db, $query);
                                            
                                             include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get all pushpins from CorkBoard...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
												 
												 print "<tr>";
												 if(isset($row['URL'])) {
												 print '<td><a href="view_pushpin.php?Email=' . $row['Email'] . "&Title=" . $row['Title'] . "&Add_Datetime=" . $row['Add_Datetime']."&Type=".$row['Type']. '">';
												 print '<img src="' . $row['URL'] . '" style="width:328px;height:328px"></a></td></tr>';}
												 else  print '</tr>';
												 
												 
												 
												 
											 }
								
			?>

			 </table>
			 <?php
			                                $query = "SELECT COUNT(*) AS number_watchers
                                            FROM (Select DISTINCT Email, Title FROM `View_Cockboard` ) AS VC INNER JOIN `Watch` AS W ON VC.Email=W.Watched_Email AND VC.Title=W.Title;";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get CorkBoard information...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 $row = mysqli_fetch_array($result, MYSQLI_ASSOC);			 
			 ?>
			 
			 <div class="title_name">
			 <?php echo "This CorkBoard is private, cannot be watched"?>
			 			 <form   method="post" id="watch"> 		
						<button type="submit" disabled form="watch" value="Watch"> Watch </button>
						</form>
			 </div>
			


			
						
						
			 </div>
				
 				
		

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
	    </div>    

			 
	</div>
	</body>
</html>			
	
