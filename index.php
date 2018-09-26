<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<input type="text" name="" value="" id="username" placeholder="Username">
<input type="password" name="" value="" id="password" placeholder="Password">
<button onclick="login()">login</button>
<button onclick="signup()">signup</button>
<script type="text/javascript">
	//function login() {}
	
	function signup() {
		var http = new XMLHttpRequest();
		/*http.onreadystatechange = () => {
			if (this.readystate == 4 && this.status == 200) {

			}
		}*/
		http.open("GET","login.php?q=signup&username=" + document.getElementById("username").value + "&password=" + document.getElementById("password").value);
		http.send();
	}

</script>

</body>
</html>
<?php 

$username = "root";
$password = "";

$dbh = new PDO('mysql:host=localhost;dbname=awesomeapes', $username, $password);

 $statement = $dbh->query("SELECT * FROM users");
        $row = $statement->fetch(PDO::FETCH_ASSOC);


function login() {

}
function signup() {

}	




?>

