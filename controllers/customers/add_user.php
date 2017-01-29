<?php
/*
INPUTS:
    username
    email
    password
*/
require_once("PDOConnection.inc"); 
require_once("common.inc"); //hash_password
require_once("mail.inc"); //verification_email
function add_user($userInfo)
{
    if(!(isset($userInfo['username']) && isset($userInfo['email'])  && isset($userInfo['password'])))
    {
        throw new Exception("Requires username, email, and password");
    }
    $dbh = new PDOConnection();
    CheckUsernameEmailExists($dbh, $userInfo);
    $user_id = AddToUsers($dbh, $userInfo);
    return array('user_id' => $user_id);
}

/**
    Add user information to users table
    
inputs:
    $dbh PDOConnection existing connect to the database
    $userInfo array Contains username, email, and password keys

*/
function AddToUsers($dbh, $userInfo)
{
    //salty.  in common.inc
    $username = $userInfo['username'];
    $email = $userInfo['email'];;
    $password = hash_password($userInfo['password'],$username);

    $query = "INSERT INTO users(username,email,password,created) VALUES(:username, :email, :password, NOW())";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->bindParam(':password',$password);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $user_id = $dbh->lastInsertId();
    verification_email($email);
    return $user_id;
}
/**
    Check if a username or email already exists in the system. Throws Exception if so.

inputs:
    $dbh PDOConnection existing connect to the database
    $userInfo array Contains username and email keys

*/
function CheckUsernameEmailExists($dbh, $userInfo)
{
//check exists
    $username = $userInfo['username'];
    $email = $userInfo['email'];

    $query = "SELECT username,email FROM users WHERE username = :username OR email = :email";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
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
}
