<?php
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
