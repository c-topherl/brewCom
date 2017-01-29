<?php
/*
user_id required
*/
require_once("PDOConnection.inc");
function get_cart($cartInfo)
{
    $dbh = new PDOConnection();
    $query = "SELECT u.id user_id, u.username, u.email, h.address_id, delivery_date, delivery_method, shipping_type, comments, shipping_comments, h.last_updated 
        FROM cart_headers h 
        LEFT JOIN users u ON u.id = h.user_id 
        WHERE user_id = :user_id ";
    $user_id = $cartInfo['user_id'];
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    if(!$sth->execute())
    {
        throw new Exception('ERROR in get_cart(): '.$sth->errorInfo()[2]);
    }
    if($sth->rowCount() <= 0)
    {
        throw new Exception('No cart found for user_id: '.$user_id);
    }
    $cartArray = $sth->fetch(PDO::FETCH_ASSOC);

    $details = get_cart_details($dbh, $user_id);
    //calculate total price
    $cartArray['total_price'] = array_sum(array_map(function($row){
            return $row['line_price'];
        }, $details));
    //uncomment if you want details passed in the main get_cart function
    $cartArray['lines'] = $details;
    return $cartArray;
}

function get_cart_details($dbh, $user_id)
{
    $detailArray = array();
    $query = "SELECT product_id, p.code product_code, p.description product_description, 
            unit_id, u.code unit_code, u.description unit_description, 
            cd.price unit_price, (cd.price * cd.quantity) line_price, quantity, cd.last_updated, cd.line_id
        FROM cart_details cd
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
