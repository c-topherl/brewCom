<?php
require_once("PDOConnection.php");
require_once("common.inc");
function verify_user($userArray, &$error=NULL)
{
    $dbh = new PDOConnection();
    $username = $userArray['username'];
    $email = $userArray['username'];
    $password = hash_password($userArray['username']);

    $query = "SELECT username, email, password FROM users WHERE (username = :username OR email = :email) AND password = :password";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->bindParam(':password',$password);
    $sth->execute();
    return ($sth->rowCount() > 0);
}
