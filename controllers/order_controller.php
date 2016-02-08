<?php

// This file will have all the functions to interact with orders (creation, read, etc)
$function = 'unknown';
if (isset($_POST['function']))
{
    $function = $_POST['function'];
}
switch($function)
{
    case "create_order":
        include "create_order.php";
        create_order();
        break;
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
        echo json_encode($responseArray);
}

exit();
?>
