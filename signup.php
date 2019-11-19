<?php
// Include config file
require_once "dbtest.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"])) && !(preg_match('/[^a-zA-Z0-9_-]/', $_POST["username"]))){
        $username_err = "Please enter a valid username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
	}
	
	// Validate email
	if(empty(trim($_POST["email"]))) {
		$email_err = "Invalid email";
	}  else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            
            // Set parameters
            $param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Blockchat Signup</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="Signup_Page/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="Signup_Page/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="Signup_Page/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="Signup_Page/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="Signup_Page/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="Signup_Page/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="Signup_Page/css/util.css">
	<link rel="stylesheet" type="text/css" href="Signup_Page/css/main.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="Signup_Page/images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="" method="POST">
					<span class="login100-form-title">
						Sign Up
					</span>

					<div class="wrap-input100 validate-input <?php echo (!empty($username_err)) ? 'alert-validate' : ''; ?>" data-validate = "<?php echo (!empty($username_err)) ? $username_err : 'Valid username is required';?>">
							<input class="input100" type="text" name="username" placeholder="Username">
							<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input <?php echo (!empty($email_err)) ? 'alert-validate' : ''; ?>" data-validate = "<?php echo (!empty($email_err)) ? $email_err : 'Valid email is required: ex@abc.xyz';?>">
						<input class="input100" type="text" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input <?php echo (!empty($password_err)) ? 'alert-validate' : ''; ?>" data-validate = "<?php echo (!empty($password_err)) ? $password_err : 'Password is required';?>">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input <?php echo (!empty($confirm_password_err)) ? 'alert-validate' : ''; ?>" data-validate = "<?php echo (!empty($confirm_password_err)) ? $confirm_password_err : 'Password is required';?>">
							<input class="input100" type="password" name="confirm_password" placeholder="Confirm Password">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Sign Up
						</button>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="login.php">
							Already have an account? Log in.
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
	<script src="Signup_Page/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="Signup_Page/vendor/bootstrap/js/popper.js"></script>
	<script src="Signup_Page/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="Signup_Page/vendor/select2/select2.min.js"></script>
	<script src="Signup_Page/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<script src="Signup_Page/js/main.js"></script>

</body>
</html>