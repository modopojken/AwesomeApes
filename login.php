<?php
// http://php.net/manual/en/language.variables.scope.php

// flytta till include för gitignore, vi har gått igenom detta
// namnge variabler så att deras syfte är tydligt
$dbuser = "root";
$dbpass = "";
$dbh = new PDO('mysql:host=localhost;dbname=awesomeapes', $dbuser, $dbpass);

// kör dessa med filter och skicka sedan med dem till era metoder
$filtered_username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
$input_username = $_POST["username"];
$input_password = md5($_POST["password"]);

if(isset($_POST["signup"])){
	signup($filtered_username, $dbh); // bättrre skrivna funktioner med parameterar hanterar scope
	// förberedelse för klass tänk
} elseif(isset($_POST["login"])){
	login();
}

function signup($username, $dbh){
	$stmt = $dbh->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
	$stmt->bindParam(':username', $username); // se ändring
	$stmt->bindParam(':password', $input_password);
	$stmt->execute();
}

function add_money($amount, $name){
	global $dbh;
	$stmt = $dbh->prepare('UPDATE `users` SET "balance" += ' . $amount . ' WHERE username = "' . $name . '"');
	$stmt->execute();	
}

function login(){
	global $input_username, $input_password, $dbh;
	$stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
	$stmt->bindParam(":username", $input_username);
	$user = $stmt->fetch();	
	var_dump($stmt);
	
	
	var_dump($user);
}

?>
