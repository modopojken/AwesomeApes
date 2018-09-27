<?php
// http://php.net/manual/en/language.variables.scope.php

// flytta till include för gitignore, vi har gått igenom detta
// namnge variabler så att deras syfte är tydligt

session_start();

$dbuser = "root";
$dbpass = "";
$dbh = new PDO('mysql:host=localhost;dbname=awesomeapes', $dbuser, $dbpass);

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
}

function add_money($username, $amount, PDO $dbh)	{
	$stmt = $dbh->prepare('UPDATE `users` SET "balance" += ' . $amount . ' WHERE username = "' . $username . '"');
	$stmt->execute();
}

function login($input_username, $password, $dbh) {
	try {
		$stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->bindParam(":username", $input_username);
		$stmt->execute();
		$row = $stmt->fetch();
		if(password_verify($password, $row['password'])) {
				echo "Logged in!";
		} else {
			echo "Wrong password";
		}
	} catch (PDOException $e) {
			echo "Wrong username";
	}

}




?>
