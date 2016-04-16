<?php
/*
There is no add/delete inventory.  we will just insert values and then on duplicate key update
*/
require_once "PDOConnection.php";
function update_inventory($inventoryInfo)
{
    if(!isset($inventoryInfo['inventory']))
    {
        throw new Exception('Must provide \'inventory\'');
    }

    $dbh = new PDOConnection();
    $query = "INSERT INTO inventory(
                product_id, unit_id, quantity
            )
            VALUES(
                :product_id, :unit_id, :quantity
            )
            ON DUPLICATE KEY UPDATE
                quantity = :quantity";

    $product_id = -1;
    $unit_id = -1;
    $qantity = -1;
    $response = '';

    $sth = $dbh->prepare($query);

    $sth->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $sth->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
    $sth->bindParam(':quantity', $quantity);

    foreach($inventoryInfo['inventory'] as $inventory)
    {
        $product_id = $inventory['product_id'];
        $unit_id = $inventory['unit_id'];
        $quantity = $inventory['quantity_id'];
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
    }
    return true;
}
