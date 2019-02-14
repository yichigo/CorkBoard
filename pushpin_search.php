/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}


	$word=strtoupper($_GET['keyword']);
	

	
    $query = "CREATE OR REPLACE VIEW Search_Result AS
(SELECT Name, Tag, Description, Category, PP.Email, PP.Title, PP.Add_Datetime
FROM `Public_CorkBoard` AS PC INNER JOIN `CorkBoard` AS C ON PC.Email=C.Email AND PC.Title=C.Title INNER JOIN `Category` AS CAT ON CAT.CategoryID=C.CategoryID INNER JOIN `PushPin` as PP ON PP.Email=PC.Email AND PP.Title=PC.Title LEFT OUTER JOIN  `PushPin_Tag` AS PT ON PT.Email=PP.Email AND PT.Title=PP.Title AND PT.Add_Datetime=PP.Add_Datetime INNER JOIN `User` as U ON U.email=PC.Email
WHERE UPPER(Description) LIKE '%$word%' 
OR         UPPER(Tag) LIKE '%$word%'
OR         UPPER(Category) LIKE '%$word%')";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view All_Sites...<br>" . __FILE__ ." line:". __LINE__ );
	}
?>


<html>

<head>		
<?php include("lib/header.php"); ?>	
<title>PushPin Search Results</title>
</head>
	
	<body>
    <div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

            <div class="title_name">	
			<?php print "PushPin Search Results" ?>
            </div>          
             
                    <table>
                         <tr>
						   <th> PushPin Description</th>
						   <th> CorkBoard</th> 
 						   <th> Owner</th> 						   
   					     </tr>
						 
                         <?php
                                            $query = "SELECT MAX(Name) AS Name, Title, MAX(Description) AS Description, Email, Add_Datetime
                                            FROM `Search_Result`
                                             GROUP BY Email, Title, Add_Datetime ORDER BY Description";
                                            $result = mysqli_query($db, $query);
										
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get Popular Sites...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
											print '<tr> <td><a href="view_pushpin.php?Email=' . $row['Email'].'&Title='. $row['Title'] . '&Add_Datetime=' . $row['Add_Datetime'] . '&Type=PUBLIC' . '">' . $row['Description'] . "</a></td><td>" . $row['Title'] . "</td><td>" . $row['Name'] . "</td>"; 
                                            }
						?>


                    </table>						
 				
		

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
	    </div>    

			 
    </div>
	</body>
</html>			
