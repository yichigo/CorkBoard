/* written by xiao xiangjun*/


<html>

<?php $Opened_Title=$_POST['Opened_Title'];?>

<head>		
<?php include("lib/header.php"); ?>	
<title>Add PushPin</title>
</head>

	<body>
	<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

            <div class="title_name">	
			<?php print "Add PushPin" ?>
            </div>          
             
					    <style>
                        .hide { position:absolute; top:-1px; left:-1px; width:1px; height:1px; }
                        </style>
						<iframe name="hiddenFrame" class="hide"></iframe>
						<script>
						function temp_fun(){
							alert("PushPin was added sucessfully !");
						}
						</script>
						
            <div>
            <form   method="POST" action="add_pushpin_insert.php" target="hiddenFrame" id="add")>
			URL: <input  type='text' name='Add_URL' required> <br>
			Description: <input  type='text' name='Add_Description' required> <br> 
			Tags: <input  type='text' name='Add_Tags' placeholder="word1,word2,word3,etc.."> <br>
			      <input  type='hidden' name='Opened_Title' value="<?php echo $Opened_Title?>">

			<button type="submit"  form="add" value="Add"> Add </button>
			
			</form>
			
			
			</div>
					

					
					
					
					
					
 				
		

                <?php include("lib/error.php"); ?>
                    
	
	    </div>    

			 
	</div>
	</body>
</html>			
