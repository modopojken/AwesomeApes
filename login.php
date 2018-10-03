<?php
// http://php.net/manual/en/language.variables.scope.php
// TODO:
// flytta till include för gitignore, vi har gått igenom detta
// namnge variabler så att deras syfte är tydligt

session_start();

// Direkt länk: http://172.20.10.2/AwesomeApes/

include "dbh.php";

$filtered_username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);

if(isset($_POST["sign_up"])){
	signup($filtered_username, $_POST["password"], $dbh); // bättrre skrivna funktioner med parameterar hanterar scope
	// förberedelse för klass tänk
} elseif(isset($_POST["login"])){
	login($filtered_username, $_POST["password"], $dbh);
} elseif(isset($_POST["add_money"])){

		if($_SESSION["logged_in"]){
		// If account is logged in via session, give them them money
		addMoney($_SESSION["username"], 10, $dbh);
		displayLoginPage($dbh);
		echo "<span style=color:green;>Added money!</span>";
		//echo loadHtmlFile("bilderna.html");
	}
}

function signup($username, $password, PDO $dbh){
	$hashed_password =	password_hash($password, PASSWORD_DEFAULT);
	$stmt = $dbh->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
	$stmt->bindParam(':username', $username); // se ändring
	$stmt->bindParam(':password', $hashed_password);
	$stmt->execute();
	echo "Created account for Mr." . $username;
	// här kan ni använda header för att skicka vidare användaren
	// http://php.net/manual/en/function.header.php
	// vill ni ha med ett meddelande, testa GET eller js
}

function addMoney($username, $amount, PDO $dbh)	{

	$stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
	$stmt->bindParam(":username", $username);
	$stmt->execute();
	$row = $stmt->fetch();

	$final_balance = $row["balance"] + $amount;

	//$stmt = $dbh->prepare('UPDATE `users` SET "balance" = ' . $amount . ' WHERE username = "' . $username);
	$stmt = $dbh->prepare("UPDATE users SET balance = :amount WHERE username = :username");
	$stmt->bindParam(":amount", $final_balance);
	$stmt->bindParam(":username", $username);
	$stmt->execute();
}

function displayLoginPage($dbh){

	$username = $_SESSION["username"];

	$stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
	$stmt->bindParam(":username", $username);
	$stmt->execute();
	$row = $stmt->fetch();

	$stmt = $dbh->prepare("SELECT * FROM users");
	$stmt->execute();
	$all_users = $stmt->fetch();

/*	$leaderboard = "";
	foreach ($all_users as $user) {
		$leaderboard += $user["username"] . " => " . $user["balance"];
	}

	echo 'Logged in as ' . $row["username"] . '! <br> Balance: ' . $row["balance"] . ' <br> <form action="" method="post"> <input type="submit" name="add_money" value="Add monay" /> </form>';
	echo $leaderboard;
*/
}

// metodhuvuden och konsekvent namngivning av variabler
function login($input_username, $password, PDO $dbh) {
	// går att använda för bättre felmeddelanden, duplicate username osv.
	try {
		$stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->bindParam(":username", $input_username);
		$stmt->execute();
		$row = $stmt->fetch();
		if(password_verify($password, $row['password'])) {
				$_SESSION["logged_in"] = true;
				$_SESSION["username"] = $input_username;
				displayLoginPage($dbh);

				// sätt session variabler
				// forwarda user till sidan med header
				// kontrollera session där och visa inloggad sida
				// med utloggning
		} else {
			echo "Wrong user credentials";
		}
	} catch (PDOException $e) {
			//var_dump($e);
			//echo $e; //vi vet att man kan printa ut erroret men tycker inte att det behövs i detta fall.
			echo "Wrong username";
	}
}
//laddar en helhtmlfil för direkt printing.
function loadHtmlFile($path) {
	$my_file = fopen($path, "r") or die("Unable to open file!");
	$html_data = fread($my_file,filesize($path));
	fclose($my_file);
	return $html_data;
}
?>
