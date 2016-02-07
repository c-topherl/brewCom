<?php

$function = 'unknown';
if (isset($_POST['function']))
{
    $function = $_POST['function'];
}
switch($function)
{
    case "add_product":
        include "add_product.php";
        if(addProduct())
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Successfully added product";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = "Failed to add product";
        }
        break;
    case "get_products":
        include "get_products.php";
        if($products = get_products())
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
    case "add_product_class":
        include "add_product_class.php";
        if($products = add_product_class())
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Class successfully added";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = "Failed to add class";
        }
        break;
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
