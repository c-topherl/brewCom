<?php
require_once(__DIR__ . '/../PDOConnection.php');
require_once(__DIR__ . '/../common.inc');
require_once(__DIR__ . '/../token.inc');
include_once(__DIR__ . '/../orders/get_cart.php');
include_once(__DIR__ . '/../orders/get_delivery_options.php');
include_once(__DIR__ . '/verify_user.php');

function verify_admin($userArray)
{
    $is_admin = verifyAdmin($userArray['user_id']);
    if(!$is_admin)
    {
        throw new Exception('User is not admin!');
    }
    $result = verify_user($userArray);
    return $result;
}
function verifyAdmin($user_id)
{
    $dbh = new PDOConnection();
    $query = 'SELECT user_id FROM admins WHERE user_id = :user_id';
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $result = $sth->fetchAll();
    $retval = !empty($result);
    return $retval;
}
