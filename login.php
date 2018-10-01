<?php
// http://php.net/manual/en/language.variables.scope.php
// TODO:
// flytta till include för gitignore, vi har gått igenom detta
// namnge variabler så att deras syfte är tydligt

session_start();

$dbuser = "root";
$dbpass = "";
$dbh = new PDO('mysql:host=localhost;dbname=awesomeapes', $dbuser, $dbpass);
// filtrera inte innan ni kontrollerar om formuläret skickats
$filtered_username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);

// kör dessa med filter och skicka sedan med dem till era metoder

if(isset($_POST["signup"])){
	signup($filtered_username, $_POST["password"], $dbh); // bättrre skrivna funktioner med parameterar hanterar scope
	// förberedelse för klass tänk
} elseif(isset($_POST["login"])){
	login($filtered_username, $_POST["password"], $dbh);
} elseif(isset($_POST["add_money"])){
	add_money($filtered_username, 10, $dbh);
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

function add_money($username, $amount, PDO $dbh)	{
	$stmt = $dbh->prepare('UPDATE `users` SET "balance" += ' . $amount . ' WHERE username = "' . $username . '"');
	$stmt->execute();
}

// metodhuvuden och konsekvent namngivning av variabler
function login($input_username, $password, PDO $dbh) {
	// bra med try catch, testa att skriva ut $e på exception
	// går att använda för bättre felmeddelanden, duplicate username osv.
	try {
		$stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->bindParam(":username", $input_username);
		$stmt->execute();
		$row = $stmt->fetch();
		if(password_verify($password, $row['password'])) {
				echo "Logged in!";
				// sätt session variabler
				// forwarda user till sidan med header
				// kontrollera session där och visa inloggad sida
				// med utloggning
		} else {
			echo "Wrong password";
		}
	} catch (PDOException $e) {
			echo "Wrong username";
	}

}




?>
