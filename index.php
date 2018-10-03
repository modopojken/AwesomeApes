<?php
session_start();
//include "login.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<body>

<!--
<input type="text" name="username" value="" id="username" placeholder="Username">
<input type="password" name="password" value="" id="password" placeholder="Password">
<input type="submit">
-->

	<form action="login.php" method="POST">
		<fieldset>
			<legend>AwesomeApes fantastiska webbplats på internet!</legend>
			<p>
				<label for="username">Användarnamn: </label>
				<input type="text" name="username" id="username">
			</p>
			<p>
				<label for="password">Lösenord: </label>
				<input type="password" name="password" id="password">
			</p>
			<p>
				<input type="submit" name="login" id="login" value="Logga in">
				<input type="submit" name="sign_up" id="signup" value="Sign up">
			</p>
		</fieldset>
	</form>

<script type="text/javascript">
	//function login() {}

	function signup() {
		var http = new XMLHttpRequest();
		http.onreadystatechange = () => {
			if (this.readystate == 4 && this.status == 200) {
				console.log("Signup!")
			}
		}
		console.log("login.php?type=signup&username=" + document.getElementById("username").value + "&password=" + document.getElementById("password").value)
		http.open("GET","login.php?type=signup&username=" + document.getElementById("username").value + "&password=" + document.getElementById("password").value, true);
		http.send();
	}

</script>

</body>
</html>
<?php

?>
