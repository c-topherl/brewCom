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
        add_cart_header($_POST);
        break;
    case "add_cart_detail":
        include "add_cart";
        add_cart_detail($_POST);
        break;
    case "add_order":
        include "add_order.php";
        $responseArray['response'] = add_order($_POST);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Added order";
        break;
    case "get_orders":
        include "get_orders.php";
        $responseArray['response'] = get_orders($_POST,$error);
        if($responseArray['response'] !== NULL)
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Orders successfully read";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
        }
    case "get_delivery_options.php"
        include "get_delivery_options.php";
        $responseArray['status'] = "success";
        $responseArray['message'] = "This feature is not implemented, but always will return \"pickup\" for now";
        $responseArray['response'] =  get_delivery_options($_POST);
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
