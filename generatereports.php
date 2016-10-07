#!/usr/bin/php
<?php
$f = json_decode(file_get_contents($argv[1]), true);
$profiles = [];
$sorted = array();
foreach ($f as $taskname => $nodeproviders) {
  echo "$taskname:\n";
  foreach ($nodeproviders as $p => $n) {
    $avg = round(array_sum($n) / count($n), 2);
    $samples = count($n);
    echo "\t$p ($samples samples) - $avg\n";
    $sorted[$p][$taskname] = $avg;
  }
}

foreach ($sorted as $k => $v) {
  echo $k."\n";
  arsort($v);
  foreach ($v as $tasks => $avgs) {
    echo "\t$tasks = $avgs\n";
  }
}
?>
