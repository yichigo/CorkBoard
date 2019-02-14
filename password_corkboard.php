
<?php
include('lib/common.php');
// written by GTusername1
	

	$tmp1=$_SESSION['k1'];
    $tmp2=$_SESSION['k2'];
			
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$enteredPassword = mysqli_real_escape_string($db, $_POST['private_password']);


	if (empty($enteredPassword)) {
			array_push($error_msg,  "Please enter a password.");
	}
	
    if ( !empty($enteredPassword) )   { 
         

        $query = "SELECT Password as password FROM Private_CorkBoard WHERE Email='$tmp1' AND Title='$tmp2'";
        
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result); 
        
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password']; 
            
            $options = array('cost'=>8);
			
             //convert the plaintext passwords to their respective hashses
             // 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
            $storedHash  = password_hash($storedPassword,  PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($enteredPassword, PASSWORD_DEFAULT , $options); 
            
            if($showQueries){
                array_push($query_msg, "Plaintext entered password: ". $enteredPassword);
                //Note: because of salt, the entered and stored password hashes will appear different each time
                array_push($query_msg, "Entered Hash:". $enteredHash);
                array_push($query_msg, "Stored Hash:  ". $storedHash . NEWLINE);  //note: change to storedHash if tables store the plaintext password value
                //unsafe, but left as a learning tool uncomment if you want to log passwords with hash values
                //error_log('email: '. $enteredEmail  . ' password: '. $enteredPassword . ' hash:'. $enteredHash);
            }
            
            //depends on if you are storing the hash $storedHash or plaintext $storedPassword 
            if (password_verify($enteredPassword, $storedHash) ) {
                array_push($query_msg, "Password is Valid! ");
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=show_private_corkboard.php');		//to view the password hashes and login success/failure
				include ('show_private_corkboard.php');
                
            }   else {
                array_push($error_msg, "Wrong Password, please enter Correct Password again " . NEWLINE);
                
            }             
        } 
    }
}
?>

<html>


<?php include("lib/header.php"); ?>

<?php

if(!empty($_GET['Email'])) $_SESSION['k1']=$_GET['Email'] ;
if(!empty($_GET['Title'])) $_SESSION['k2']=$_GET['Title'];
print $_SESSION['k1'] . $_SESSION['k2'];
?>

<title>Private CockBoard</title>
</head>
<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

        <div class="center_content">
            <div class="text_box">

                <form action="password_corkboard.php" method="post" enctype="multipart/form-data">
                    <div class="title">The CorkBoard you are trying to view is private. Please enter the CorkBoard's password to continue</div>
                    <div class="login_form_row">
                        <label class="login_label">Password:</label>
                        <input type="password" name="private_password" value="" class="login_input"/>
                    </div>
	
					<input type="image" src="img/login.gif" class="login"/>
                </form>
                </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
            </div>
   

</div>
</body>
</html>
