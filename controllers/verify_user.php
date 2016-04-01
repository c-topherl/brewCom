<?php
require_once("PDOConnection.php");
require_once("common.inc");
include("get_cart.php");
include("get_delivery_options.php");
function verify_user($userArray)
{
    
    if(!((isset($userArray['username']) || isset($userArray['email'])) && isset($userArray['password'])))
    {
        //fail gracefully-ish
        throw new Exception("Must provide (username or email) and password.");
    }
    $dbh = new PDOConnection();
    $username = isset($userArray['username']) ? $userArray['username'] : '';
    $email = isset($userArray['email']) ? $userArray['email'] : '';

    $query = "SELECT id,username, email, password FROM users WHERE username = :username OR email = :email";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->execute();
    if($sth->rowCount() <= 0)
    {
        throw new Exception("Invalid username or email address.");
    }
    $row = $sth->fetch();
    if($row['password'] !== hash_password($userArray['password'],$row['username']))
    {
        throw new Exception("Invalid password.");
    }

    //user verified, return proper landing page content
    $user_id= $row['id'];
    $query = "SELECT COUNT(*) FROM cart_details where user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->execute(array(":user_id" => $user_id));
    if($sth->rowCount() > 0)
    {
        return array('cart' => get_cart(array('user_id' => $user_id)));
    }
    return array('delivery_options' => get_delivery_options());
}
