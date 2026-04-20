<?php

$logData = date('Y-m-d H:i:s').'|'.$_SERVER['REMOTE_ADDR']."\n";
$fileName = 'logs/log-'.date('Y-m-d').'.log';
file_put_contents($fileName, $logData, FILE_APPEND);



include_once 'header.php';
?>
    <h1>Hi</h1>
    <?php 
    echo 1+1;
    ?>

<?php
include_once 'footer.php';
?>
