<?php
/**/
require_once "PDOConnection.php";
function update_product($user)
{
    $dbh = new PDOConnection();
    $query = "SELECT id,username,email,password,last_updated FROM users WHERE id = :id";
    $sth = $dbh->prepare($query);
    $id = $user['id'];
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    if(!($sth->execute()))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if(!($oldValues = $sth->fetch()))
    {
        throw new Exception("User id: '".$id."' not found!");
    }
    $username = isset($user['username'])? $user['username'] : $oldValues['username'];
    $email = isset($user['email'])? $user['email'] : $oldValues['email'];
    $password = isset($user['password'])? hash_password($user['password'],$username) : $oldValues['password'];
    $query = "UPDATE users SET username = :username, email = :mail, password = :password 
        WHERE id = :id";
    $sth = $dbh->prepare($query);

    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->bindParam(':code', $code);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return $sth->lastInsertId();
}
