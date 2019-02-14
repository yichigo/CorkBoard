
<?php
include('lib/common.php');

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$enteredEmail = mysqli_real_escape_string($db, $_POST['email']);
	$enteredPassword = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($enteredEmail)) {
            array_push($error_msg,  "Please enter an email address.");
    }

	if (empty($enteredPassword)) {
			array_push($error_msg,  "Please enter a password.");
	}
	
    if ( !empty($enteredEmail) && !empty($enteredPassword) )   { 

        $query = "SELECT Pin as password FROM User WHERE email='$enteredEmail'";
        
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result); 
        
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password']; 
            
            $options = array('cost'=>8);
			

            $storedHash  = password_hash($storedPassword,  PASSWORD_DEFAULT , $options);   
            $enteredHash = password_hash($enteredPassword, PASSWORD_DEFAULT , $options); 
            
            if($showQueries){
                array_push($query_msg, "Plaintext entered password: ". $enteredPassword);

                array_push($query_msg, "Entered Hash:". $enteredHash);
                array_push($query_msg, "Stored Hash:  ". $storedHash . NEWLINE);  

            }
            
             if (password_verify($enteredPassword, $storedHash) ) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['email'] = $enteredEmail;
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=view_homepage.php');		
                
            } else {
                array_push($error_msg, "Login failed: " . $enteredEmail . NEWLINE);
                array_push($error_msg, "To demo enter: ". NEWLINE . "xxiao@hotmail.com". NEWLINE ."5811");
            }
            
        } else {
                array_push($error_msg, "The username entered does not exist: " . $enteredEmail);
            }
    }
}
?>

<html>
<?php include("lib/header.php"); ?>
<title>CockBoard Login</title>
</head>
<body>
    <div id="main_container">

        <div class="center_content">
            <div class="text_box">

                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">CocoBoard Login</div>
                    <div class="login_form_row">
                        <label class="login_label">Email:</label>
                        <input type="text" name="email" value="xxiao1@hotmail.com" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Password:</label>
                        <input type="password" name="password" value="5811" class="login_input"/>
                    </div>
                    <input type="image" src="img/login.gif" class="login"/>
                    <form/>
                </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
            </div>
   

        </div>

</body>
</html>
