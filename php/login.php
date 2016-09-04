<?php

//allow sessions to be passed so we can see if the user is logged in

session_start();

ob_start();

//connect to the database so we can check, edit, or insert data to our users table

include('dbconfig.php');

//include out functions file giving us access to the protect() function made earlier

include "functions.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */
     .jumbotron {
      margin-bottom: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>
</head>

<body>

<?php

//If the user has submitted the form

if (isset($_POST['submit'])){

	//protect the posted value then store them to variables

	$username = protect($_POST['username']);

	$password = protect($_POST['password']);

	echo "<p>this is the username  </p>"; 
	echo $username;

	echo "<p>this is the password  </p>"; 
	echo $password;

	//Check if the username or password boxes were not filled in

	if(!$username || !$password){

	//if not display an error message

	echo "<center>You need to fill in a <b>Username</b> and a <b>Password</b>!</center>";

	}else{

		//if the were continue checking

		//select all rows from the table where the username matches the one entered by the user

		$res = mysqli_query($con,"SELECT * FROM `Texusers` WHERE `username` = '".$username."'");

		$num = mysqli_num_rows($res);

		//check if there was not a match

		if($num == 0){

		//if not display an error message

		echo "<center>The <b>Username</b> you supplied does not exist!</center>";

		}else{

			//if there was a match continue checking

			//select all rows where the username and password match the ones submitted by the user

			$res = mysqli_query($con,"SELECT * FROM `Texusers` WHERE `username` = '".$username."' AND `password` = '".md5($password)."'");

			$num = mysqli_num_rows($res);


			//check if there was not a match

			if($num == 0){

			//if not display error message

			echo "<center>The <b>Password</b> you supplied does not match the one for that username!</center>";

			}else{

				//if there was continue checking

				//split all fields fom the correct row into an associative array

				$row = mysqli_fetch_assoc($res);

				//check to see if the user has not activated their account yet

				if($row['active'] != 1){

				//if not display error message

				echo "<center>You have not yet <b>Activated</b> your account!</center>";

				}else{

					//if they have log them in

					//set the login session storing there id - we use this to see if they are logged in or not

					$_SESSION['uid'] = $row['id'];

					//show message

					echo "<center>You have successfully logged in!</center>";

					//update the online field to 50 seconds into the future

					$time = date('U')+50;

					mysqli_query($con,"UPDATE `users` SET `online` = '".$time."' WHERE `id` = '".$_SESSION['uid']."'");

					//redirect them to the usersonline page

					header('Location: usersOnline.php');

				}

			}

		}

	}

}


?>


<!--The form goes here-->

<form class="form-horizontal" action='login.php' method="POST"> 

			<fieldset> 
			<div id="legend"> <legend class="">Login</legend> </div> 

			<div class="control-group"> 
			 <!-- Username --> 
			  <label class="control-label" for="username">Username</label> 
			  <div class="controls"> <input type="text" id="username" name="username" placeholder="" class="input-xlarge"> 
			  </div> 
			</div> 

		

			<div class="control-group"> 
				<!-- Password--> 
				<label class="control-label" for="password">Password</label> 
				<div class="controls"> <input type="password" id="password" name="password" placeholder="" class="input-xlarge"> 
					
				</div>
			</div> 

			<div class="control-group"> 
				<!-- Button Register--> 
				<div class="controls"> 
					<button class="btn btn-success" type="submit" name="submit" value="Login">Login</button> 
				</div> 
				 
			</div> 
			<!-- Button Forgot Password--> 
				<div class="controls"> 
					
						<form action="forgot.php" method="get">
						 
						  <button class="btn btn-success"> Forgot Password</button> 
						  
						</form>
					
				</div>
 
				 
			</div> 
			</fieldset> 


</form>

<footer class="container-fluid text-center">
  <p>Online Store Copyright</p>
  <form class="form-inline">Get deals:
    <input type="email" class="form-control" size="50" placeholder="Email Address">
    <button type="button" class="btn btn-danger">Sign Up</button>
  </form>
</footer>

</body>

</html>



</body>

</html>

<?php

ob_end_flush();

?>
