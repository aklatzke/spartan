<?php

final class TrackMediaRenderer
{
	public function __invoke(  ){
    $client = new Google_Client();
    $client->setDeveloperKey($_ENV["GOOGLE_API_CODE"]);
    $song = App::module('song')->getSingle();

    $youtube = new Google_Service_YouTube($client);

    $res = $youtube->search->listSearch('id,snippet', array(
      'q' => str_replace(" ", "+", "{$song->artist}+{$song->title}"),
      'maxResults' => 1,
    ));

    $searchResponse = [];

    if( $res )
    {
      foreach ($res['items'] as $searchResult)
      {
        $searchResponse = $searchResult['id']['videoId'];
      }
    }

    $html = [
      "<div class='tab-group'>",
        "<div class='tab'>",
          "<div class='tab-label'><i class='fa fa-headphones'></i></div>",
          "<div class='tab-inner'>"
    ];

    $html[] = "<div class='embed-wrapper'><h4>{$song->title}</h4><iframe width='465' height='250' src='https://www.youtube.com/embed/{$searchResponse}' frameborder='0' allowfullscreen></iframe></div>";

    $html[] = "</div></div><div class='tab'><div class='tab-label'><i class='fa fa-list-ol'></i></div><div class='tab-inner'><ul class='tracklist'>";

    foreach ($tracklist as $index => $el)
    {
      $strong = in_array($el->name, $favorites);

      $num = $index + 1;

      if( $strong )
          $html[] = "<li><strong data-favorite-track='{$el->name}'>{$num}: {$el->name}</strong></li>";
      else
          $html[] = "<li>{$num}: {$el->name}</li>";
    }

    $html =  implode("", $html) . "</ul></div></div></div>";
    return $html;
  }
}
