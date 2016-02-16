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
        if(add_user($_POST,$error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "User successfully added";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
        }
        break;
    case "verify_user"
        include "verify_user";
        if(verify_user($_POST,$error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "User verified";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = "Invalid username or password";
        }
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
