<?php
function exception_handler($e)
{
    $responseArray['status'] = 'failure';
    $responseArray['message'] = $e->getMessage();
    echo json_encode($responseArray);
}
set_exception_handler('exception_handler');

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
        add_user($_POST);
        $responseArray['message'] = "User successfully added";
        break;
    case "verify_user":
        include "verify_user.php";
        $responseArray['response'] = verify_user($_POST);
        $responseArray['message'] = "User verified";
        break;
    default:
        throw new Exception("Unknown function: $function");
}

$responseArray['status'] = 'success';
echo json_encode($responseArray);
exit();
?>
