<?php
require_once("PDOConnection.php");
require_once("common.inc");
function verify_user($userArray)
{
    $dbh = new PDOConnection();
    $username = isset($userArray['username']) ? $userArray['username'] : '';
    $email = isset($userArray['email']) ? $userArray['email'] : '';

    $query = "SELECT username, email, password FROM users WHERE username = :username OR email = :email";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->execute();
    if($sth->rowCount() <= 0)
    {
        throw new Exception("Invalid username");
    }
    $row = $sth->fetch();
    if($row['password'] !== hash_password($userArray['password'],$row['username']))
    {
        throw new Exception("Invalid password");
    }
    return true;
}
