<?php
require_once(__DIR__ . '/../PDOConnection.inc');
require_once(__DIR__ . '/../common.inc');
require_once(__DIR__ . '/../token.inc');
include_once(__DIR__ . '/../orders/get_cart.php');
include_once(__DIR__ . '/../orders/get_delivery_options.php');

function verify_user($userArray)
{
    if(!(isset($userArray['user_id']) || 
        (isset($userArray['username']) || isset($userArray['email'])) 
        && (isset($userArray['password']) || isset($userArray['token']))
        ))
    {
        throw new Exception("Must provide (username or email) and password.");
    }
    //set variables
    $user_id = isset($userArray['user_id']) ? $userArray['user_id'] : NULL;
    $username = isset($userArray['username']) ? $userArray['username'] : NULL;
    $email = isset($userArray['email']) ? $userArray['email'] : NULL;
    $password = isset($userArray['password']) ? $userArray['password'] : NULL;
    $token = isset($userArray['token']) ? $userArray['token'] : NULL;

    if(isset($token))
    {
        $user_id = VerifyToken($token, $user_id, $username);
        if($user_id === FALSE)
        {
            throw new Exception("Your session has expired.  Please log in again.");
        }
        $token = $token;
    }

    $dbh = new PDOConnection();
    if(empty($user_id))
    {
        $row = GetUserInfo($dbh, $username, $email, $password);
        //user verified, return proper landing page content
        $user_id= $row['id'];
        $token = GenerateToken($username, $password);
        StoreToken($username, $token);
    }
    return array_merge(GetLandingPageContent($dbh, $user_id),array('token' => $token));
}
/*
 */
function GetLandingPageContent($dbh, $user_id)
{
    $query = "SELECT COUNT(*) count FROM cart_headers where user_id = :user_id";
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

/*
 * Verifies login attempt and returns row of user information
 */
function GetUserInfo($dbh, $username, $email, $password)
{
    $query = "SELECT id,username, email, password FROM users WHERE username = :username OR email = :email";
    //echo "$query -- $username -- $email -- $password -- " ;
    //exit;
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username',$username);
    $sth->bindParam(':email',$email);
    $sth->execute();
    $row = $sth->fetch();
    if(empty($row))
    {
        throw new Exception("Invalid username or email address.");
    }

    $password = hash_password($password, $username);
    if($row['password'] !== $password)
    {
        throw new Exception("Invalid password.");
    }
    return $row;
}
