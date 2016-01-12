<?php
require('settings.php');
session_start(); 
if (!isset($_SESSION['loggedin'])) {
	header("Location: $AdminUrl/");
	exit;
} else {
	
	if ($_SESSION['timeout'] + 20 * 60 < time()) {
		
		//session_destroy();
		header("Location: $AdminUrl/logout.php");
		exit;
		
	} else {
		
		$_SESSION['timeout'] = time();
		
		require('users.php');
		$userexists = false;
		
		foreach($result_array as $username => $password) {
			if (md5($username.$password.$salt) == $_SESSION['loggedin'])
				$userexists = true;
		}
		
		if ($userexists !== true) {
			exit('<p>Invalid session: please <a href="$AdminUrl/index.php">login</a>.</p>');
		}
  	}
}
?>