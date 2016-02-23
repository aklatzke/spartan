<?php

use AKL\Mason;

const HTML_ATTRIBUTE_PRESETS = [
  "class" => "",
  "href" => "#",
  "color" => "",
  "type" => "",
  "record" => ""
];

const DIRECTIVE_ARGS = [
  "argAssign" => ":",
  "argDelim" => ["{", "}"],

  "_defaults" => HTML_ATTRIBUTE_PRESETS
];

const SYMBOL_ARGS = [
  "argDelim" => ["|", "|"]
];

Mason::setRegexDelimiter("#");

Mason::symbolMap([
  "rand" => function($min, $max){
    return " " . rand($min, $max);
  }
], SYMBOL_ARGS);

Mason::directive(["!php", "!!"], function($a, $content){
  return Mason::php($content);
});

Mason::directive([":record", ":endrecord"], function($params, $content, $arguments){
  $postType = $params[0];
  $var = $arguments['var'];

  $post = App::module($postType)->getSingle($arguments['record']);

  $out = "[";

  foreach ($post as $key => $value) {
    $out .= "'{$key}' => '{$value}',";
  }

  $out = $out . "]";

  $content =  Mason::PHP("\${$var} = {$out}") . $content;

  foreach ($post as $key => $value)
  {
    $content = str_replace("{$var}.{$key}", Mason::PHP("echo \${$var}['{$key}']"), $content);
  }

  return $content;
}, DIRECTIVE_ARGS);

Mason::directive([":do", ":enddo"], function($a, $content, $arguments){
  return Mason::PHP("for( \$i = 0; \$i < {$a[0]}; ++\$i ) : ") . "\n" . Mason::buildString($content) . "\n" . Mason::PHP("endfor;");
});

Mason::directive(["::", "::/"], function($t, $c, $a){
  $res = Mason::buildString($c);

  $tag = $t[0];

  return "<{$tag} class='{$a['class']}'>{$res}</{$tag}>";
}, DIRECTIVE_ARGS);

Mason::directive(["load", "\n"], function($a, $c){
  return Mason::PHP("get_{$a[0]}()");
});

Mason::directive(["@", ";"], function($a, $b, $c){
  $a = str_replace(";", "", $a[0]);

  return Mason::PHP("echo \${$a};");
});


Mason::directive(["[row", "end]"], function($a, $content){
  $res = Mason::buildString($content);

  return "<div class='row'>$res</div>";
});

Mason::directive([":each", ":endeach"], function($a, $content, $arguments){
  return Mason::PHP("foreach( \${$a[0]} as \$i => \${$a[2]} ) : ") . "\n" . Mason::buildString($content) . "\n" . Mason::PHP("endforeach;");
});

Mason::directive([":col", ":endcol"], function($a, $content, $arguments){
  $w = $a[0];
  $res = Mason::buildString($content . " \n");

  return "<div class='col-md-{$w} {$arguments['class']}' style='background-color: rgba({$arguments['color']}, {$arguments['color']}, 255, 1)'>" . $res  . "</div>";
}, DIRECTIVE_ARGS);

Mason::directive(["img", "\n"], function($a, $b){
  $templatePath = get_template_directory_uri();
  return "<img src='{$templatePath}/img/{$a[0]}' />";
});

Mason::directive(["if","fi"], function( $a, $b ){
}, DIRECTIVE_ARGS);
