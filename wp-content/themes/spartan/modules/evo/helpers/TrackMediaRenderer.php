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
          "<div class='tab-inner'>",
          "<h3>Previews</h3>",
          "<hr />"
    ];

		$html[] = "<div class='embed-wrapper'><h4>{$song->title}</h4><a href='#' data-video-id='{$searchResponse}' data-video-name='{$song->title}' class='youtube-link'><i class='fa fa-youtube'></i></a></div>";

    $html[] = "</div></div>";


    $html =  implode("", $html) . "</div>";
    return $html;
  }
}
