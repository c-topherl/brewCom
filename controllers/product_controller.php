<?php

$function = 'unknown';
if (isset($_POST['function']))
{
    $function = $_POST['function'];
}
switch($function)
{
    case "get_products":
        include "get_products.php";
        if($products = getProducts())
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Products successfully read";
            $responseArray['products'] = $products;
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = "Something went wrong reading from products";
            $responseArray['products'] = '';
        }
        break;
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
