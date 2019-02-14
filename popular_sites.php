/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}
    $query = "CREATE OR REPLACE VIEW All_Sites AS
(SELECT PP.Email, PP.Title, PP.Add_Datetime, SUBSTRING_INDEX(SUBSTRING_INDEX(URL, '/', 3), '/', -1) as Site
FROM `CorkBoard` AS C INNER JOIN `PushPin` as PP ON PP.Email=C.Email AND PP.Title=C.Title)";
             
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
	if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view All_Sites...<br>" . __FILE__ ." line:". __LINE__ );
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
			<?php print "Popular Sites" ?>
            </div>          
             
                    <table>
                         <tr>
						   <th> Site</th>
						   <th> PushPins</th>   
   					     </tr>
						 
                         <?php
                                            $query = "SELECT Site, COUNT(*) AS number_pushpins
                                            FROM `All_Sites`
                                            GROUP BY Site
                                            ORDER BY COUNT(*) DESC";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get Popular Sites...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
											print '<tr> <td><a href="http://' . $row['Site'] . '">' . $row['Site'] . "</td><td>" . $row['number_pushpins'] . "</td>"; 

                                            }
						?>


                    </table>						
 				
		

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
	    </div>    

			 
    </div>
	</body>
</html>			
