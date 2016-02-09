<?php

// This file will have all the functions to interact with orders (creation, read, etc)
$function = 'unknown';
if (isset($_POST['function']))
{
    $function = $_POST['function'];
}
switch($function)
{
    case "add_user":
        include "add_user.php";
        add_user();
        $responseArray['status'] = 'success';
        $responseArray['message'] = "User successfully added";
        echo json_encode($responseArray);
        break;
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
        echo json_encode($responseArray);
}

exit();
?>
