<?php

// This file will have all the functions to interact with orders (creation, read, etc)
$function = 'unknown';
if (isset($_POST['function']))
{
    $function = $_POST['function'];
}
switch($function)
{
    case "add_cart_header":
        include "add_cart";
        add_cart_header();
        break;
    case "add_cart_detail":
        include "add_cart";
        add_cart_detail();
        break;
    case "add_order":
        include "create_order.php";
        create_order();
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Added order";
        break;
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
