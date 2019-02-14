/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$tmp1 = $_GET['Email'];
$tmp2 = $_GET['Title'];
$tmp3 = $_GET['Add_Datetime'];
$tmp4 = $_GET['Type'];

print $tmp1.' '.$tmp2.' '.$tmp3;


?>


<html>

<head>		
<?php include("lib/header.php"); ?>	
<title>View PushPin</title>
</head>
	
	<body>
	<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

            <div class="title_name">	
			<?php print "CorkBoardIt<br>";print "Explore. Share. Inspire<br>"; ?>
			View PushPin <br><br>
            </div>  

            <div class="title_name">
			<?php
			                        $query = "SELECT PP.Email, PP.Title, PP.Add_Datetime, PP.Description, PP.URL, Name,SUBSTRING_INDEX(SUBSTRING_INDEX(URL, '/', 3), '/', -1) as Site FROM PushPin AS PP INNER JOIN User ON PP.Email=User.Email WHERE PP.Email='$tmp1' AND PP.Title='$tmp2' AND PP.Add_Datetime='$tmp3'";
                                    $result = mysqli_query($db, $query);
                                            
                                    include('lib/show_queries.php');
                                             
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                    array_push($error_msg,  "Query ERROR: Failed to get CorkBoard information...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
				                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
								    $opened_email=$row['Email'];
								    $opened_title=$row['Title'];
									$opened_datetime=$row['Add_Datetime'];
										
								
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
							alert("User was followed sucessfully !");
							location.reload();
						}
						</script>
										   
						<form   method="POST" action="follow.php" target= "hiddenFrame" id="follow"> 		
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

			 
			 
			 <p> <?php echo "Pinned " . date("F j, Y \a\\t g:i a",strtotime($row['Add_Datetime']));?> on 
			 <?php if ($tmp4=='PUBLIC') print "<a href=show_corkboard.php?Email=".$opened_email.'&Title='.$opened_title.'>'.$opened_title."</a>";
			       else 
			       print "<a href=password_corkboard.php?Email=".$opened_email.'&Title='.$opened_title.'>'.$opened_title."</a>" ;
			 
			 ?> 
			 </p> <br>
			 
			 
			 
			 <div>
			 <?php print "from ".$row['Site'] ?> <br>
			 <a href="<?php echo $row['URL'] ?>">
			 <img src="<?php echo $row['URL']?>" style="width:328px;height:328px"></a>
			 </div>

			 <p> <?php echo $row['Description']?> </p>

                Tags:
 							<?php
			                        $query = "SELECT lower(Tag) as Tag FROM PushPin AS PP INNER JOIN PushPin_Tag AS PT ON PP.Email=PT.Email AND PP.Title=PT.Title AND PP.Add_Datetime=PT.Add_Datetime WHERE PP.Email='$tmp1' AND PP.Title='$tmp2' AND PP.Add_Datetime='$tmp3' ORDER BY Tag";
                                    $result = mysqli_query($db, $query);
                                            
                                    include('lib/show_queries.php');
                                             
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                    array_push($error_msg,  "Query ERROR: Failed to get CorkBoard information...<br>" . __FILE__ ." line:". __LINE__ );
                                             }

									             while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
													 print '<tr> <td><a href="pushpin_search.php?keyword=' . $row['Tag'] . '">' . $row['Tag'] .','. "</a></td><tr>";
													 
												 
												 
							
											 															
			                ?>

			 </div>
			
			<hr>
            
			<div class="title_name">
			Like List: 
 							<?php
			                        $query = "SELECT Name FROM Liked AS L INNER JOIN User ON User.Email=L.Email WHERE Liked_Email='{$opened_email}' AND
									Title='{$opened_title}' AND Add_Datetime='{$opened_datetime}'";
                                    $result = mysqli_query($db, $query);
                                          
                                    include('lib/show_queries.php');
                                             
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                    array_push($error_msg,  "Query ERROR: Failed to get CorkBoard information...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
							            print $row['Name'].',';
													 
												 }
									
									
							        $query = "SELECT * FROM `Liked` WHERE Email='{$_SESSION['email']}' AND Liked_Email='$opened_email' AND
									Title='{$opened_title}' AND Add_Datetime='{$opened_datetime}'";
                                    $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                            if (mysqli_num_rows($result) == 0)  {
                                                  $like_status=0;
                                             }
											 else $like_status=1;				 
											 	 
			                ?>        
		

		    </div>
						
						<style>
                        .hide { position:absolute; top:-1px; left:-1px; width:1px; height:1px; }
                        </style>
						<iframe name="hiddenFrame" class="hide"></iframe>
						
						<script>
						function temp_fun(){
							location.reload()
						 
						}
						</script>
										   
						<form   method="POST" action="liked.php" id="like" onsubmit="temp_fun()" target="hiddenFrame"> 		
						<input  type='hidden' name='like_email' value="<?php echo $opened_email;?>">
						<input  type='hidden' name='like_title' value="<?php echo $opened_title;?>">
						<input  type='hidden' name='like_datetime' value="<?php echo $opened_datetime;?>">
						<input  type='hidden' name='like_status' value="<?php echo $like_status;?>">
						
						<?php
						if ($opened_email==$_SESSION['email']){
						print '<button type="submit" disabled form="like" > Like !</button>';
						}
						elseif ($like_status==1){
						print '<button type="submit"  form="like" > UnLike !</button>';
						}	
						else print '<button type="submit"  form="like" >Like !</button>';

						?>
		                </form>
			           
			
		<hr>
		            <form action="add_comment_insert.php" method="POST" target="hiddenFrame" onsubmit="temp_fun()">
					<textarea   name="comment" rows="5" cols="20">Enter Comment</textarea>
					    <input  type='hidden' name='com_email' value="<?php echo $opened_email;?>">
						<input  type='hidden' name='com_title' value="<?php echo $opened_title;?>">
						<input  type='hidden' name='com_datetime' value="<?php echo $opened_datetime;?>">

					<input type="submit" value="Post Comment"> 
			        </form>
		
		        <hr>
				
				<div class="title_name">
				
		 							<?php
			                        $query = "SELECT Name, Comment, Comment_Datetime FROM `Comment` AS C INNER JOIN User AS U ON C.Email=U.Email 
									WHERE Commented_Email='{$opened_email}' AND Title='{$opened_title}' AND Add_Datetime= '{$opened_datetime}'
									ORDER BY Comment_Datetime DESC";
                                    $result = mysqli_query($db, $query);
                                          
                                    include('lib/show_queries.php');
                                             
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                    array_push($error_msg,  "Query ERROR: Failed to get CorkBoard information...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
							            print $row['Name'].'------'.$row['Comment'].'------'.date("F j, Y \a\\t g:i a",strtotime($row['Comment_Datetime'])).'<br>';
													 
												 }
										 
											 	 
			                ?>  		
				</div>
				
				
		

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
	    </div>    

			 
	</div>
	</body>
</html>			
	


		
	
