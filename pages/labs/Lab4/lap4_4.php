<pre><?php
$a = array(1, 3, 5);
$b = array("x1"=>2, "x2"=>4);

$c = array($a, $b);
$d = array();
$d[] = $a;
$d[] = $b;
// print_r($c);
// print_r($d);
$v = $d[1]["x2"];//$v=4

?>