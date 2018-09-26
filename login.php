<?php

$username = "root";
$password = "";

$dbh = new PDO('mysql:host=localhost;dbname=awesomeapes', $username, $password);

$input_username = $_POST["username"];
$input_password = md5($_POST["password"]);

if(isset($_POST["signup"])){
	signup();
} elseif(isset($_POST["login"])){
	login();
}

function signup(){
	global $dbh, $input_username, $input_password;
	$stmt = $dbh->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
	$stmt->bindParam(':username', $input_username);
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