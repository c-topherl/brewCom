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
        if(add_product($_POST,$error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Successfully added product";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
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
    case "update_product":
        include "update_product.php";
        if($products = update_product($_POST, $error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Product successfully updated";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
        }
    case "add_product_class":
        include "add_product_class.php";
        if($products = add_product_class($_POST, $error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Class successfully added";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
        }
        break;
    case "update_product_class":
        include "update_product_class.php";
        if($products = update_product_class($_POST, $error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Product class successfully updated";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
        }
    case "add_unit":
        include "add_unit.php";
        if($products = add_unit($_POST, $error))
        {
            $responseArray['status'] = 'success';
            $responseArray['message'] = "Product/Unit successfully added";
        }
        else
        {
            $responseArray['status'] = 'failure';
            $responseArray['message'] = $error;
        }
        
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
