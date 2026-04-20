<?php

function errorHandle($message) {
    $fileName = 'logs/error-'.date('Y-m-d').'.log';
    $ip = $_SERVER['REMOTE_ADDR'];
    $logData = date('Y-m-d H:i:s').'|'.$ip."|$message\n";
    file_put_contents($fileName, $logData, FILE_APPEND);
}

function logging($message) {
    $fileName = 'logs/log-'.date('Y-m-d').'.log';
    $ip = $_SERVER['REMOTE_ADDR'];
    $logData = date('Y-m-d H:i:s').'|'.$ip."|$message\n";
    file_put_contents($fileName, $logData, FILE_APPEND);
}