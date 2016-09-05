<?php
class PDOConnection extends PDO
{
    public function __construct($dsn="mysql:host=localhost;dbname=joelmeis_brewCom",
            $user="joelmeis_brewery",$pass="brewcom9876")
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
?>
