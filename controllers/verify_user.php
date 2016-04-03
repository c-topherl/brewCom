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
    $row = VerifyUser($dbh, $userArray);
    //user verified, return proper landing page content
    $user_id= $row['id'];
    return GetLandingPageContent($dbh, $user_id);
}
/*
*/
function GetLandingPageContent($dbh, $user_id)
{
    $query = "SELECT COUNT(*) count FROM cart_details where user_id = :user_id";
    $sth = $dbh->prepare($query);
    if(!$sth->execute(array(":user_id" => $user_id)))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $count = (int)$sth->fetch(PDO::FETCH_ASSOC)['count'];
    if($count > 0)
    {
        //user has details in cart
        $landing_page = get_cart(array('user_id' => $user_id));
    }
    else
    {
        //user does not have cart_details, so return delivery options page
        $landing_page = get_delivery_options();
    }
    //merge user_id to top level of array
    $landing_page = array_merge($landing_page, array('user_id' => $user_id));
    return $landing_page;
}
/**
    Verifies login attempt and returns row of user information
*/
function VerifyUser($dbh, $userArray)
{
    $username = isset($userArray['username']) ? $userArray['username'] : NULL;
    $email = isset($userArray['email']) ? $userArray['email'] : NULL;

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
    $password = hash_password($userArray['password'], $row['username']);
    if($row['password'] !== $password)
    {
        throw new Exception("Invalid password.");
    }
    return $row;
}
