/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}
    $query = "CREATE OR REPLACE VIEW Corkboard_ALL  AS
(SELECT C.Email, C.Title,
        CASE
        WHEN P.Password IS NOT NULL THEN 'PRIVATE'
        ELSE 'PUBLIC'
        END AS Type 
FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_CorkBoard` AS P on C.Email=P.Email AND C.Title=P.Title)";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view All_Sites...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
    $query = "CREATE OR REPLACE VIEW All_Pushpin AS
(SELECT U.Email, PP.Title, PP.Add_Datetime, Name, Type
FROM Corkboard_ALL as CA INNER JOIN `PushPin` AS PP ON CA.Email=PP.Email AND CA.Title=PP.Title RIGHT OUTER JOIN `User` AS U ON U.Email=PP.Email)";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view All_Pushpin...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
	$query = "CREATE OR REPLACE VIEW public_cork AS
(SELECT MAX(Name) as Name, Email, COUNT(*) as public_cork
FROM (SELECT DISTINCT Email, Title, Name FROM All_Pushpin WHERE Type='PUBLIC') AS TEMP
GROUP BY Email)";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create VIEW public_cork...<br>" . __FILE__ ." line:". __LINE__ );
	}
		
	$query = "CREATE OR REPLACE VIEW private_cork AS
(SELECT MAX(Name) as Name, Email, COUNT(*) as private_cork
FROM (SELECT DISTINCT Email, Title, Name FROM All_Pushpin WHERE Type='PRIVATE') AS TEMP
GROUP BY Email)";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create VIEW private_cork...<br>" . __FILE__ ." line:". __LINE__ );
	}
	
	$query = "CREATE OR REPLACE VIEW public_push AS
(SELECT MAX(Name) as Name, Email, COUNT(*) as public_push
FROM (SELECT DISTINCT Email, Title, Add_Datetime, Name FROM All_Pushpin WHERE Type='PUBLIC') AS TEMP
GROUP BY Email)";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create VIEW public_push...<br>" . __FILE__ ." line:". __LINE__ );
	}
				
	$query = "CREATE OR REPLACE VIEW private_push AS
(SELECT MAX(Name) as Name, Email, COUNT(*) as private_push
FROM (SELECT DISTINCT Email, Title, Add_Datetime, Name FROM All_Pushpin WHERE Type='PRIVATE') AS TEMP
GROUP BY Email)";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create VIEW private_push...<br>" . __FILE__ ." line:". __LINE__ );
	}	
?>


<html>

<head>		
<?php include("lib/header.php"); ?>	
<title>Popular Sites</title>
</head>
	
	<body>
	<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

            <div class="title_name">	
			<?php print "CorkBoard Statistics" ?>
            </div>          
             
                    <table>
                         <tr>
						   <th> User</th>
						   <th> Public CorkBoards</th> 
 						   <th> Public PushPins</th> 
						   <th> Private CorkBoards</th> 
						   <th> Private Pushpins</th> 						   
   					     </tr>
						 
                         <?php
                                            $query = "SELECT User.Name, User.Email, COALESCE(public_cork,0) as public_cork, COALESCE(public_push,0) as public_push, COALESCE(private_cork,0) as private_cork, COALESCE(private_push,0) as private_push FROM
(SELECT P1.Name, P1.Email, public_cork, public_push
FROM public_cork AS P1 LEFT OUTER JOIN public_push AS P2 ON P1.Email = P2.Email) AS TEMP1 LEFT OUTER JOIN
(SELECT P1.Name, P1.Email, private_cork, private_push
FROM private_cork AS P1 LEFT OUTER JOIN private_push AS P2 ON P1.Email = P2.Email) AS TEMP2 ON TEMP1.Email = TEMP2.Email RIGHT OUTER JOIN User ON User.Email=TEMP1.Email
ORDER BY public_cork DESC, private_cork DESC";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get Popular Sites...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
												
												if($row['Email']==$_SESSION['email']){
												print '<tr><td><font color="red">' . $row['Name'] . "</font></td><td>" . $row['public_cork'] . "</td><td>" . $row['public_push'] . "</td><td>" . $row['private_cork'] . "</td><td>" . $row['private_push'] . "</td>"; }
												else {print '<tr> <td><font color="black">' . $row['Name'] . "</font></td><td>" . $row['public_cork'] . "</td><td>" . $row['public_push'] . "</td><td>" . $row['private_cork'] . "</td><td>" . $row['private_push'] . "</td>";}
												
												
												
                                            }
						?>


                    </table>						
 				
		

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
	    </div>    

			 
	</div>
	</body>
</html>			
