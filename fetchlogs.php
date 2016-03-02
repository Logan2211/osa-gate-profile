#!/usr/bin/php
<?php
/* Argument required is json output from logstash.openstack.org's table of metrics. There is no an export option so hack this together by changing the paging size
 * and watch the network traffic in chrome, then save it to a file.
 * Once the file exists, feed it to this script as an argument and it will go through and download all of the log_url items to a directory called 'dump'
 * You should probably mkdir dump/ in the same directory as this script first.
 */
  $v = json_decode(file_get_contents($argv[1]), true);
  foreach($v['hits']['hits'] as $hit) {
    $outfile = $hit['_id'].'.html';
    $url = $hit['_source']['log_url'].'.gz';
    $wget = "wget -O dump/{$outfile} {$url}";
    echo "running $wget\n";
    shell_exec($wget);
  }

?>
