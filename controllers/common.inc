<?php
define('ADDRESS_TYPE_SHIP',0);
define('ADDRESS_TYPE_BILL',1);

define('STATUS_OPEN',1);
define('STATUS_SHIPPED',2);
define('STATUS_CLOSED',3);
define('STATUS_VOID',4);

function output_error($errormessage){
    http_response_code(400);
    echo json_encode(array('error' => $errormessage));
    exit();
}

function return_success(){
    echo json_encode(array('result' => 'success'));
}

function debug_to_console($data) {
    if(is_array($data) || is_object($data)) {
        echo("<script>console.log('php: ".json_encode($data)."');</script>");
    } else {
        echo("<script>console.log('php: $data');</script>");
    }
}

function hash_password($p, $u)
{
    return hash('sha256', hash('sha256',$p).$u);
}
