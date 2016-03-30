<?php
require_once("PDOConnection.php");
function get_cart($cartInfo)
{
    $dbh = new PDOConnection();
    $query = "SELECT u.id, u.username, u.email,
            ship_date, delivery_method, shipping_type, comments, shipping_comments, h.last_updated 
        FROM cart_header h 
        LEFT JOIN users u ON u.id = h.user_id 
        WHERE user_id = :user_id ";
    $user_id = $cartInfo['user_id'];
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->execute();
    $cartArray = $sth->fetch(PDO::FETCH_ASSOC);
    $cartArray['details'] = get_cart_details($dbh, $user_id);
    $cartArray['total_price'] = array_sum(array_map(function($row){
            return $row['price'];
        },$cartArray['details']));
    return array('cart' => $cartArray);
}
function get_cart_details($dbh, $user_id)
{
    $detailArray = array();
    $query = "SELECT product_id, p.code product_code, p.description product_description, 
            unit_id, u.code unit_code, u.description unit_description, 
            cd.price, quantity, cd.last_updated
        FROM cart_detail cd
        LEFT JOIN products p ON p.id = cd.product_id 
        LEFT JOIN units u ON u.id = cd.unit_id
        WHERE cd.user_id = :user_id ";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row)
    {
        $detailArray[] = $row;
    }
    return $detailArray;
}
