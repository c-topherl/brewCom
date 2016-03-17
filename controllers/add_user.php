<?php
/*
INPUTS:
username
email
password
*/
require_once("PDOConnection.php");
require_once("common.inc");
function add_user($userArray)
{
    $dbh = new PDOConnection();
    $username = $userArray['username'];
    $email = $userArray['email'];
//check exists
    $query = "SELECT username,email FROM users WHERE username = :username OR email = :email";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->execute();
    if($row = $sth->fetch(PDO::FETCH_ASSOC))
    {
        if($row['email'] === $email)
        {
            throw new Exception("Email already exists");
        }
        elseif($row['username'] === $username)
        {
            throw new Exception("Username already exists");
        }
    }
//salty.  in common.inc
    $password = hash_password($userArray['password'],$username);
    $query = "INSERT INTO users(username,email,password) VALUES(:username, :email, :password)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->bindParam(':password',$password);
    if($sth->execute())
    {
        require("mail.inc");
        verification_email($email);
        return true;
    }
    else
    {
        throw new Exception(json_encode($sth->errorInfo()));
    }
}
