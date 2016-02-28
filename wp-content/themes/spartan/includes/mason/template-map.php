<?php

use AKL\Mason;

const HTML_ATTRIBUTE_PRESETS = [
  "class" => "",
  "href" => "#",
  "color" => "",
  "type" => "",
  "record" => ""
];

const RECORDS_PRESETS = [
  "limit" => "5",
  "order" => "desc",
  "var" => "local"
];

const DIRECTIVE_ARGS = [
  "argAssign" => ":",
  "argDelim" => ["{", "}"],

  "_defaults" => HTML_ATTRIBUTE_PRESETS
];

const RECORDS_ARGS = [
  "argAssign" => ":",
  "argDelim" => ["{", "}"],

  "_defaults" => RECORDS_PRESETS
];

const SYMBOL_ARGS = [
  "argDelim" => ["|", "|"]
];

Mason::setRegexDelimiter("#");

Mason::symbolMap([
  "rand" => function($min, $max){
    return " " . rand($min, $max);
  },
], SYMBOL_ARGS);

Mason::directive(["!php", "!!"], function($a, $content){
  return Mason::php($content);
});


Mason::directive(['partial', "\n"], function($a){
  return Mason::buildString(file_get_contents(get_template_directory() . "/includes/partials/{$a[0]}.php"));
});

Mason::directive([":records", ":endrecords"], function($params, $content, $arguments){

  $content = Mason::buildString($content);

  $postType = $params[0];
  $var = $arguments['var'];

  $posts = App::module($postType)->get([
    "order" => $arguments['order'],
    "posts_per_page" => $arguments['limit']
  ]);

  $out = "[";

  foreach ($posts as $index => $post) {
    $out .= "[";

    foreach ($post as $key => $value)
    {
      $value = str_replace("'", "\'", $value);

      $out .= "'{$key}' => '{$value}',";
    }

    $out .="],";
  }

  $out = $out . "]";
  $varDeclaration = Mason::PHP("\${$var} = {$out}");

  $finishedContent = [];
  foreach ($posts as $index => $post)
  {
    $contentCopy = $content;
    foreach ($post as $key => $value)
    {
      if( strpos($key, "post_") > -1 )
        $key = str_replace("post_", "", $key);

      $contentCopy = str_replace("{$var}.{$key}", Mason::PHP("echo \${$var}[{$index}]['{$key}']"), $contentCopy);
    }

    $finishedContent[] = $contentCopy;
  }

  $finishedContent = $varDeclaration . implode("", $finishedContent);

  return $finishedContent;
}, RECORDS_ARGS);

Mason::directive([":single", ":endsingle"], function( $a, $content ){
  $module = App::module($a[0])->getSingle();
  
  $out = "[";

  foreach ($module as $key => $value)
  {
    $value = str_replace("'", "\'", $value);
    $out .= "'{$key}' => '{$value}',";
  }

  $out = $out . "]";
  $varDeclaration = Mason::PHP("\${$a[0]} = {$out}");

  $contentCopy = $content;
  foreach ($module as $key => $value)
  {
    if( strpos($key, "post_") > -1 )
      $key = str_replace("post_", "", $key);

    $contentCopy = str_replace("{$a[0]}.{$key}", Mason::PHP("echo \${$a[0]}['{$key}'];"), $contentCopy);
  }

  $finishedContent = $varDeclaration . $contentCopy;

  return Mason::buildString($finishedContent);
});

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

  return "<div class='col-md-{$w} {$arguments['class']}' style='background-color: rgba({$arguments['color']}, 255, 255, 1)'>" . $res  . "</div>";
}, DIRECTIVE_ARGS);

Mason::directive(["img", "\n"], function($a, $b){
  $templatePath = get_template_directory_uri();
  return "<img src='{$templatePath}/img/{$a[0]}' />";
});

Mason::directive(["rawImg", "\n"], function($a, $b){
  $a = implode(" ", $a);

  return "<img src='{$a}' />";
});

Mason::directive(["if","fi"], function( $a, $b ){
}, DIRECTIVE_ARGS);

Mason::directive(['@@ ', '\n'], function($a){
  $class = App::module($a[0]);

  if( $class )
    return Mason::EOL($class());
});

Mason::directive(['render', '\n'], function($a){
  $class = App::helper($a[0]);

  if( $class )
    return Mason::EOL($class());
});
