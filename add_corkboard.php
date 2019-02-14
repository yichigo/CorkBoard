/* written by xiao xiangjun*/



<html>

<?php
include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

    $query = "SELECT * FROM Category ORDER BY Category";            
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
    array_push($error_msg,  "Query ERROR: Failed to get Popular Sites...<br>" . __FILE__ ." line:". __LINE__ );
	}
?>
	
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
             
					    <style>
                        .hide { position:absolute; top:-1px; left:-1px; width:1px; height:1px; }
                        </style>
						<iframe name="hiddenFrame" class="hide"></iframe>
						<script>
						function temp_fun(){
							alert("CorkBoard was added sucessfully !");
						}
						</script>
						
            <div>
            <form   method="POST" action="add_corkboard_insert.php" target="hiddenFrame" id="add")>
			Title: <input  type='text' name='Add_Title' required> <br>
			Category: 
			<select name="Category">
			<?php
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
				print '<option value=' . $row['CategoryID'] . '>' . $row['Category'] . '</option>';
			}
			?>
			</select> <br>
			Visibility: 
			<input type="radio" name="Visibility" value="PUBLIC" required> Public
			<input type="radio" name="Visibility" value="PRIVATE" > Private
			<input type='text'  name="Password"   placeholder="Please enter password"> <br>
			<button type="submit"  form="add" value="Add"> Add </button>
			
			</form>
			
			
			</div>
					

					
 				
		

                <?php include("lib/error.php"); ?>
                    
	
	    </div>    

			 
	</div>
	</body>
</html>			
