<?php

$db = new mysqli('localhost', 'root', 'root', 'ticket');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

?>