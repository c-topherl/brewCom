<?php
/**/
include_once "common.inc";
require_once "PDOConnection.inc";
include_once "token.inc";
function update_user($user)
{
    if(!(isset($user['email']) || isset($user['username']) || isset($user['password'])))
    {
        throw new Exception("Nothing changed!");
    }
    $dbh = new PDOConnection();
    $query = "SELECT id,username,email,password,token,last_updated FROM users WHERE id = :id";
    $sth = $dbh->prepare($query);
    $id = $user['user_id'];
    $sth->bindParam(':id', $id, PDO::PARAM_INT);

    if(!($sth->execute()))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if(!($oldValues = $sth->fetch()))
    {
        throw new Exception("User id: '".$id."' not found!");
    }

    // if you change username you must provide password
    if(isset($user['username']) && !isset($user['password']))
    {
        throw new Exception("Must provide password to change username.");
    }
    $email = isset($user['email'])? $user['email'] : $oldValues['email'];
    $username = isset($user['username'])? $user['username'] : $oldValues['username'];
    $password = isset($user['password'])? hash_password($user['password'],$username) : $oldValues['password'];
    $token = $oldValues['token'];

    $query = "UPDATE users 
        SET username = :username, email = :email, password = :password 
        WHERE id = :id";
    $sth = $dbh->prepare($query);

    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->bindParam(':username', $username);
    $sth->bindParam(':email', $email);
    $sth->bindParam(':password', $password);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if(isset($user['password']))
    {
        $token = GenerateToken($username, $user['password']);
        StoreToken($username, $token);
    }
    return array('id' => $id, 'email' => $email, 'username' => $username, 'token' => $token);
}
