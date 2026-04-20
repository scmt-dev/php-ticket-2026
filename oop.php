<?php 

class Logger {
    private $fileName;
    public function __construct($type) {
        // check folder exist
        $rootPath = __DIR__.'/logs/'.date('Y/m');
        if (!is_dir($rootPath)) {
            mkdir($rootPath, 0777, true);
        }
        $this->fileName = "logs/{$type}-".date('Y-m-d').'.log';
    }
    public function log($message) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $logData = date('Y-m-d H:i:s').'|'.$ip."|$message\n";
        file_put_contents($this->fileName, $logData, FILE_APPEND);
    }
    public function error($message) {
        $this->log("ERROR: $message");
    }
}
$logger = new Logger('log');
$error = new Logger('error');
$info = new Logger('info');

$logger->log('Hello');
$error->error('Hello');
$info->log('Hello');