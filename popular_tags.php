/* written by xiao xiangjun*/
<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query="CREATE OR REPLACE VIEW All_Tag AS
(SELECT Tag, PP.Email, PP.Title, PP.Add_Datetime
FROM `Public_CorkBoard` AS PC RIGHT OUTER JOIN `CorkBoard` AS C ON PC.Email=C.Email AND PC.Title=C.Title INNER JOIN `PushPin` as PP ON PP.Email=PC.Email AND PP.Title=PC.Title INNER JOIN  `PushPin_Tag` AS PT ON PT.Email=PP.Email AND PT.Title=PP.Title AND PT.Add_Datetime=PP.Add_Datetime WHERE Tag IS NOT NULL)";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
if ($result==FALSE) {
    array_push($error_msg,  "Query ERROR: Failed to create view Watch_List...<br>" . __FILE__ ." line:". __LINE__ );
    }

?>


<html>

<head>		
<?php include("lib/header.php"); ?>	
<title>Add CorkBoard</title>
</head>
	
	<body>
    <div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

            <div class="title_name">	
			<?php print "Add CorkBoard" ?>
            </div>          
             
                    <table>
                         <tr>
						   <th> Tag</th>
						   <th> PushPins</th>
						   <th> Unique CorkBoards</th>
						 </tr>
						 
                         <?php
                                            $query = "SELECT TEMP1.Tag, number_pushpins, number_unique_cork FROM
                                            (SELECT lower(Tag) as Tag, COUNT(*) as number_pushpins
                                             FROM All_Tag
                                             GROUP BY Tag) AS TEMP1 INNER JOIN (SELECT lower(Tag) as Tag, COUNT(*) as number_unique_cork FROM (SELECT DISTINCT Email, Title, Tag FROM `All_Tag`) AS TEMP
                                             GROUP BY Tag) AS TEMP2 on TEMP1.Tag= TEMP2.Tag ORDER BY number_pushpins DESC LIMIT 5";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get My CorkBoards...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
											print '<tr> <td><a href="pushpin_search.php?keyword=' . $row['Tag'] . '">' . $row['Tag'] . "</a></td><td>" . $row['number_pushpins'] . "</td><td>" . $row['number_unique_cork'] . "</td>"; 
                                            }
						?>


                    </table>						
 				
		

            <?php include("lib/error.php"); ?>
                    
			<div class="clear"></div> 		
	</div>    

	 
    </div>
	</body>
</html>			
