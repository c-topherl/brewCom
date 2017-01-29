<?php

namespace Util;

use \PDO;

class PDOConnection extends PDO
{
    public function __construct($dsn="mysql:host=localhost;dbname=brewCom",
                                $user="root",$pass="")
    {
        parent::__construct($dsn,$user,$pass);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

}
