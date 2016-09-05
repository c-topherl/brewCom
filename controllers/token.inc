<?php
/*
This file contains functions related to user tokens
*/
require_once(__DIR__ . '/PDOConnection.inc');
require_once(__DIR__ . '/common.inc');

//Verify that a token is correct and has not expired
function VerifyToken($token, $user_id = NULL, $username = NULL)
{
    //token and user_id/username required
    if(!isset($token) || !(isset($user_id) || isset($username)))
    {
        throw new Exception("Token and user_id or username required");
    }
    $user_id = !empty($user_id) ? $user_id : '';
    $username = !empty($username) ? $username : '';

    $dbh = new PDOConnection();
    $sth = $dbh->prepare('SELECT id,last_login FROM users WHERE token = :token AND (id = :id OR username = :username)');
    $sth->bindParam(':token', $token);
    $sth->bindParam(':id', $user_id, PDO::PARAM_INT);
    $sth->bindParam(':username', $username);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if(VerifyLastLogin($row['last_login']) === FALSE)
    {
        return FALSE;
    }

    return $row['id'];
}

//Ensure last login is not too old
function VerifyLastLogin($last_login)
{
    $timeout = strtotime('-1 day');
    if(strtotime($last_login) < $timeout)
    {
        return FALSE;
    }
    return TRUE;
}

//This function generates a user token
function GenerateToken($username, $password)
{
    $current_time = date("Y-m-d H:m:s",time());
    $token = MD5($username . $password . $current_time);
    return $token;
}

//Store a users token in the users table and updates last_login
function StoreToken($username, $token)
{
    $dbh = new PDOConnection();
    $sth = $dbh->prepare('UPDATE users SET token = :token, last_login = NOW() WHERE username = :username');
    $sth->bindParam(':token',$token);
    $sth->bindParam(':username',$username);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
}
