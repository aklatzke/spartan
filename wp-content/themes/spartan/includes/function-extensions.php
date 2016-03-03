<?php

use AKL\Mason;

App::alias('Album', 'album');
App::alias('Song', 'song');


function loadCompiledTemplate($intended)
{
  $contents = file_get_contents($intended);
  $target = get_template_directory() . '/tmp/mason/';

  require_once( get_template_directory() . '/includes/mason/template-map.php' );

  Mason::build($intended, $target . basename(str_replace(".php", "", $intended)));

  return $target . basename($intended);
}

Actions::on("template_include", 'loadCompiledTemplate');
