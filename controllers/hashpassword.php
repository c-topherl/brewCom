<?php
//this functions just returns the output of hash_password from the command line
//usage:   php hashpassword <password> <user>
include "common.inc";
echo hash_password($argv[1], $argv[2]);
?>
