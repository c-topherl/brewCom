<?php

function check_cart_exists($dbh, $user_id)
{
    $query = "SELECT user_id FROM cart_headers WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id,PDO::PARAM_INT);
    $sth->execute();
    return ($sth->rowCount() > 0);
}
