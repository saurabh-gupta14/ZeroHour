
<?php
 require('connect.php');
 if (isset($_POST['email']) && isset($_POST['password']) ){
    
   $email = htmlentities($_POST['email'] );
   $password = htmlentities($_POST['password'] );

   if (!empty($email) && !empty($password) ){
   $query = "SELECT * FROM `registration` WHERE email='$email' AND Password='$password'";

   $results = mysqli_query($connection, $query);
   $count = mysqli_num_rows($results);
   if($count == 1){
     echo "Login Successfull";
     $field=mysqli_fetch_assoc( $results );
     //GO to next Session after login.
    setcookie('hospitalid', $field['id'], time() + (86400 * 30), "/"); //To set cookie for each login. It contains value of hospital id for use in other pages
    header("Location: index.php");    // To redirect to next page
     
   }else{
     echo "Login Unsuccessful";
   }
 }else{
  echo "Please enter all details!";
 }
}

?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ZeroHour/Login</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">
		<link href="css/agency.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
	   <link href="css/agency.css" rel="stylesheet">
	   

 

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/11.png">

    </head>

    <body>

        <!-- Top content -->
        <div class="top-content" >
        	
            <div class="inner-bg" align="center">
                <div class="container">
                	<?php require_once 'navbar1.php'; ?>
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>ZeroHour</strong> Login </h1>
                            <div class="description">
                            	
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-5">
                        	
                        	<div class="form-box" align-content="center">
	                        	<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Login to our WebApp !</h3>
	                            		<p>Enter username and password to log on:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-lock"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
								
				                   
									
							            <form role="form" action="login.php" method="post" class="login-form" >
				                    	<div class="form-group">
				                    		<label class="sr-only" for="email">Username</label>
				                        	<input type="text" name="email" placeholder="Email-id..." class="form-username form-control" id="form-username">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="password">Password</label>
				                        	<input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
				                        </div>
				                        <button type="submit" class="btn">Sign in!</button>
				                    </form>
			                    </div>
		                    </div> </div>
		                   
		                	
                       
                         
                        <div class="col-sm-1 middle-border"></div>
						<div class="social-login"><br><br>
	                        	<h3>Or Login With</h3>
	                        	<div class="social-login-buttons">
		                        	<a class="btn btn-link-2" href="#">
		                        		<i class="fa fa-facebook"></i> Facebook
		                        	</a><br><br>
		                        	<a class="btn btn-link-2" href="#">
		                        		<i class="fa fa-twitter"></i> Twitter
		                        	</a><br><br>
		                        	<a class="btn btn-link-2" href="#">
		                        		<i class="fa fa-google-plus"></i> Google Plus
		                        	</a><br><br>
	                        	</div>
	                        </div>
                        <div class="col-sm-1"></div>
  <div class="row">
                     




        <!-- Footer -->
        <footer>
        	<div class="container">
        		<div class="row">
        			
        			<div class="col-sm-8 col-sm-offset-2"><br><br>
        				<div class="footer-border"></div>
        				<p>Made With Full Care And Security <strong>By Team Anton</strong></a> 
        					 <i class="fa fa-smile-o"></i></p>
        			</div>
        			
        		</div>
        	</div>
        </footer>

        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>