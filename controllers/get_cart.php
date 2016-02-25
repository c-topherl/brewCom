<?php
require_once("PDOConnection.php");
function get_cart($cartInfo = NULL, &$error = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT email, ship_date,type,shipping_type,comments,shipping_comments,h.last_updated header_last_updated ";
    $query .= "FROM cart_header LEFT JOIN users u ON u.id = h.user_id WHERE user_id = :user_id ";
    $optionalParams = '';
    if($optionalParams !== '')
    {
        $query .= "WHERE ".$optionalParams;
    }
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->execute();
    $cartArray = $sth->fetch();
    $cartArray['details'] = get_cart_details($dbh, $user_id);
    return $cartArray;
}
function get_cart_details($dbh, $user_id)
{
    $detailArray = array();
    $query = "SELECT product_id,price,quantity,unit_id,last_updated FROM cart_detail WHERE user_id = :user_id ";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->execute();
    $result = $sth->fetchAll();
    foreach($result as $row)
    {
        $detailArray[] = $row;
    }
    return $detailArray;
}
