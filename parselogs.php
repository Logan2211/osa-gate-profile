#!/usr/bin/php
<?php
/* Parse all of the console logs saved to dump/*
 * This will try to regex match the first few lines which identify the nodepool
 * Then it will search through the console log for PLAY RECAP style output which contains execution times
 * for all of the ansible tasks run. Once it is done it will output a json object of metrics.
 * Example output at http://cdn.pasteraw.com/5efufwesy1nmxnl6qis9z1zr1hbce0
 */
$dir = new DirectoryIterator(dirname(__FILE__).'/dump/');
$profile = array();
$profiles=0;

pcntl_signal(SIGHUP,  function($signo) {
  global $profile;
  echo json_encode($profile)."\n";
});
pcntl_signal(SIGTERM,  function($signo) {
  print_exit();
});
pcntl_signal(SIGINT,  function($signo) {
  print_exit();
});

foreach ($dir as $f) {
  if (!$f->isFile()) continue;
  $c = file_get_contents('dump/'.$f);
  if (!preg_match('/Building remotely on <a .*>([^<]+)-[0-9]+<\/a>/', $c, $m)) {
    echo "Failed to find node type of $f\n";
    continue;
  }
  $nodetype = $m[1];
  foreach (explode("\n", $c) as $l) {
    if (preg_match('/(?:[^|]+)\|\s+(.*)\s+[-]+\s+([0-9\.s]+)s\s*$/', $l, $m)) {
      $role_task = $m[1];
      $seconds = (float)$m[2];
      $profile[$role_task][$nodetype][] = $seconds;
    }
  }
  $profiles++;
  pcntl_signal_dispatch();
}

print_exit();

function print_exit() {
  global $profile, $profiles;
  file_put_contents("php://stderr", "\nExiting with $profiles profiles completed.\n");
  exit(json_encode($profile));
}

?>
