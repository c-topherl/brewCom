<?php
require_once(__DIR__ . '/../PDOConnection.inc');
require_once(__DIR__ . '/../common.inc');
require_once(__DIR__ . '/../token.inc');
include_once(__DIR__ . '/../orders/get_cart.php');
include_once(__DIR__ . '/../orders/get_delivery_options.php');
include_once(__DIR__ . '/verify_user.php');

function verify_admin($userArray)
{
    $is_admin = verifyUserIsAdmin($userArray['username']);
    if(!$is_admin)
    {
        throw new Exception('User is not admin!');
    }
    $result = verify_user($userArray);
    return $result;
}

function verifyUserIsAdmin($username)
{
    $dbh = new PDOConnection();
    $query = 'SELECT user_id FROM admins JOIN users ON users.id = user_id WHERE username = :username';
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username', $username, PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $result = $sth->fetchAll();
    $retval = !empty($result);
    return $retval;
}
