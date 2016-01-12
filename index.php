<?php
require('settings.php');
session_start(); 
if (!isset($_SESSION['loggedin'])) {
	
} else {
	header("Location: $AdminUrl/admin-home");
}

echo ($_SESSION['is_open']);

if ($_SESSION['is_open'] == "1") {
	header("Location: $AdminUrl/admin-home");
} else {
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if (!ereg("^[A-Za-z0-9]", $_POST['username'])) {
		$errorTest = "User";
	} else {
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		
		require('users.php');
		
		if (array_key_exists($username, $users)) {
			if ($password == $users[$username]) {
				session_start();
				$_SESSION['is_open'] = TRUE;
				$_SESSION['username'] = $username;
				$_SESSION['salt'] = $salt;
				$_SESSION['loggedin'] = md5($username.$password.$salt);
				$_SESSION['timeout'] = time();
				header("Location: $AdminUrl/admin-home");
				exit;
			} else {
				$errorTest = "Pass";
			}
		} else {
			$errorTest = "User";
		}
	}
}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<link rel="stylesheet" href="<?php echo ("$AdminUrl"); ?>/css/admin_login.css" type="text/css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript" language="javascript"></script>
	<script>
		$(document).ready(function(){
		  testInput();
		});
		function testInput() {
			if ($('input.user').val().length > 0) {
				$('input.user').addClass('userfocus');
			}
			if ($('input.pass').val().length > 0) {
				$('input.pass').addClass('passfocus');
			}
		}
	</script>
</head>
<body>
	
    <?php
		if ($errorTest == "User") {
			echo "<div class='form_error form_error_user'>please enter a valid username</div>";
		} else if ($errorTest == "Pass") {
			echo "<div class='form_error form_error_pass'>please enter a valid password</div>";
		}
	?>
    
    <form class="form_login" method="post" action="<?php echo ("$AdminUrl"); ?>/index.php">
		<input class="normal user" type="text" name="username" onFocus="this.className='normal user userfocus'" onBlur="if(this.value == '') { this.className='normal user'}" onchange="myFunction()" onMouseMove="testInput()" value="<?echo $username;?>" />
    	<input class="normal pass" type="password" name="password" onFocus="this.className='normal pass passfocus'" onBlur="if(this.value == '') { this.className='normal pass'}" onMouseMove="testInput()" />
		<input class="submit" type="submit" name="submit" value="Login" />
	</form>
</body>
</html>