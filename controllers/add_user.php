<?php
require_once("PDOConnection.php");
function add_user($userArray, &$error = null)
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
            $error = "Email already exists";
            return false;
        }
        elseif($row['username'] === $username)
        {
            $error = "Username already exists";
            return false;
        }
    }
//add some salt
    $password = hash('sha256',hash('sha256',$userArray['password']).$username);
    $query = "INSERT INTO users(username,email,password) VALUES(:username, :email, :password)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->bindParam(':password',$password);
    return $sth->execute();
}
