<?php
require_once("PDOConnection.php");
function add_user($userArray, &$error = null)
{
    $dbh = new PDOConnection();
    $username = $userArray['username'];
    $email = $userArray['email'];
//check exists
    $query = "SELECT username,email FROM users WHERE username = '$username' OR email = '$email'";
    foreach($dbh->query($query) as $row)
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
    $query = "INSERT INTO users(username,email,password) VALUES('$username','$email','$password')";
    $dbh->query($query);
    return true;
}
