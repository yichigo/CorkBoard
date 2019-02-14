/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}
							
    $query  = "CREATE OR REPLACE VIEW Watch_List AS
    (SELECT U.Name, U.Email, W.Title, P.Add_Datetime, 'PUBLIC' as Type
    FROM `Watch` AS W INNER JOIN `Pushpin` AS P ON W.Watched_Email=P.Email AND W.Title=P.Title INNER JOIN `User` AS U ON U.Email=W.Watched_Email WHERE W.Email='{$_SESSION['email']}')";
	$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view Watch_List...<br>" . __FILE__ ." line:". __LINE__ );
    }
										
	$query  = "CREATE OR REPLACE VIEW Watch_List_recent AS
    (SELECT MAX(Name) AS Name, MAX(Email) AS Email, MAX(Title) AS Title, MAX(Add_Datetime) AS updated_datetime, MAX(Type) AS Type FROM Watch_List GROUP BY Name, Title)";
	$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view Watch_List_recent...<br>" . __FILE__ ." line:". __LINE__ );
    }
						
	$query  = "CREATE OR REPLACE VIEW Follow_List AS
    (SELECT Name, U.Email, C_ALL.Title, Add_Datetime, Type
    FROM `Follow` AS F INNER JOIN
    (SELECT C.Email, C.Title,
    CASE
    WHEN P.Password IS NOT NULL THEN 'PRIVATE'
    ELSE 'PUBLIC'
    END AS Type 
    FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_CorkBoard` AS P on C.Email=P.Email AND C.Title=P.Title) AS C_ALL ON C_ALL.Email=F.Followed_Email INNER JOIN `User` AS U ON U.Email=C_ALL.Email INNER JOIN `Pushpin` AS PUSH ON PUSH.Email=C_ALL.Email and PUSH.Title=C_ALL.Title WHERE F.Email='{$_SESSION['email']}')";
	$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view Follow_List...<br>" . __FILE__ ." line:". __LINE__ );
    }
						 
	$query  = "CREATE OR REPLACE VIEW Follow_List_recent AS
    (SELECT MAX(Name) AS Name, MAX(Email) AS Email, MAX(Title) AS Title, MAX(Add_Datetime) AS updated_datetime, MAX(Type) AS Type FROM Follow_List GROUP BY Name, Title)";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view Follow_List_recent...<br>" . __FILE__ ." line:". __LINE__ );
    }

    $query = "SELECT Name FROM User WHERE User.Email='{$_SESSION['email']}'";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
    array_push($error_msg,  "Query ERROR: Failed to get User Name...<br>" . __FILE__ ." line:". __LINE__ );
    }
			
?>


<html>
			
<?php include("lib/header.php"); ?>
<title>CorkBoardit Homepage</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="center_left">
            <div class="title_name">	
			<?php print "Home Page for" ?> <br>
            <?php print $row['Name']?>
            </div>

            <div class="features">   
            
                <div class="profile_section">
  
                    <table>
                         <tr>
                            <td class="item_label">My CorkBoards</td>
                            <td>
                                <ul>
                                    <?php
                                            $query = "SELECT MAX(Email) as Email, Title, COUNT(Add_Datetime) AS number_of_pushpins, MAX(Type) AS Type FROM
                                                     (SELECT C.Title, C.Email,Add_Datetime,
                                                     CASE
                                                     WHEN PB.Password IS NOT NULL THEN 'PRIVATE'
                                                     ELSE 'PUBLIC'
                                                     END AS Type
                                                    FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_CorkBoard` AS PB ON C.Email=PB.Email AND C.Title=PB.Title LEFT OUTER JOIN `Pushpin` AS P ON P.Email=C.Email AND P.Title=C.Title
                                                    WHERE C.Email='{$_SESSION['email']}') AS TEMP
                                                    GROUP BY Title
                                                    ORDER BY Title";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get My CorkBoards...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
												if($row['Type']=='PRIVATE'){
												
												print "<li>";
												print '<a href="password_corkboard.php?Email=' . $row['Email'] . "&" . "Title=" . $row['Title'] . '">' . $row['Title'] . "</a>";
												print "(" . $row['Type'] . ")" . " with " . $row['number_of_pushpins'] . " PushPins</li>";
												
												}
																								
												else {												
												print "<li>";
												print '<a href="show_corkboard.php?Email=' . $row['Email'] . "&" . "Title=" . $row['Title'] . '">' . $row['Title'] . "</a>";
												print " with " . $row['number_of_pushpins'] . " PushPins</li>";
												}
												
                                            }
											


										$query= "SELECT * FROM CorkBoard WHERE Email='{$_SESSION['email']}'";	
									    $result = mysqli_query($db, $query);
								        include('lib/show_queries.php');
													if (is_bool($result)) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get EMPTY CorkBoards...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
											 
										if (mysqli_num_rows($result) == 0)	print "<li>You have no CorkBoard</li>";

									?>
                                </ul>
                            </td>
                        </tr>
						<tr>
						<td> 
						<form   method="post" action="add_corkboard.php" id="add"> 		
						<input  type='hidden' name='email' value=<?php echo $_SESSION['email'] ?>">
						<button type='submit' form="add" value="Submit"> Add CorkBoard</button>
						</form>

						</td>
						</tr>
                    </table>						
                </div>
				
				<div class="profile_section">
				<table>
				<tr>
				    <td class="item_label">Recent CorkBoard Updates</td>
					<td>
					   <ul>
					    <?php
						
                        $query= "SELECT Name, Email, Title, updated_datetime, Type FROM
                        (SELECT * FROM Watch_List_recent
                        UNION 
                        SELECT * FROM Follow_List_recent) AS TEMP
                        ORDER BY updated_datetime DESC LIMIT 4";
					
	                    $result = mysqli_query($db, $query);                                        
                         include('lib/show_queries.php');
                                             
                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                        array_push($error_msg,  "Query ERROR: Failed to get Recent CorkBoard Updates...<br>" . __FILE__ ." line:". __LINE__ );
						
                                             }	

                  										 
						
						while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
												if($row['Type']=='PRIVATE'){
											    print "<li>";
												print '<a href="password_corkboard.php?Email=' . $row['Email'] . "&" . "Title=" . $row['Title'] . '">' . $row['Title'] . "</a>";
                                                print "(" . $row['Type'] . ")" . "<br>" . " updated by " . $row['Name'] . "<br>" . " on " . date("F j, Y \a\\t g:i a",strtotime($row['updated_datetime'])) . "</li>";
												}
												else{
											    print "<li>";
												print '<a href="show_corkboard.php?Email=' . $row['Email'] . "&" . "Title=" . $row['Title'] . '">' . $row['Title'] . "</a>";
                                                print "<br>" ." updated by " . $row['Name'] . "<br>" . " on " . date("F j, Y \a\\t g:i a",strtotime($row['updated_datetime'])) . "</li>";
												}
						}	
						
						if (mysqli_num_rows($result) == 0) print "<li> You DO NOT follow/watch any other user or corkboard, no update, no watch, no follow</li>";
						
				        ?>
				       </ul>
				    </td>
				</table>
			</div>
				
 		</div>
	</div>

	<?php include("lib/error.php"); ?>
                    
	<div class="clear"></div> 		
	</div>    

			 
</div>
</body>
</html>