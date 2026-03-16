<?php 
date_default_timezone_set('Asia/Bangkok');
echo date('Y-m-d H:i:s');
echo "<br>";
echo time();
function sayHi($name = 'A') {
    echo "Hi. ".$name;
}
sayHi();

function sum($a=0, $b=0) {
    return $a + $b;
}
echo "<br>";
$x = sum(5, 10);
echo $x;
$y = 2;
echo sum($x, $y);
$a = sum($x, $y);
echo sum($a, 3);



?>