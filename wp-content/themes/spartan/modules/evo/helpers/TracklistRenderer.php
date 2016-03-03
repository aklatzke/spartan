<?php

final class TracklistRenderer
{
	public function __invoke(  ){
    $client = new Google_Client();
    $client->setDeveloperKey($_ENV["GOOGLE_API_CODE"]);
    $album = App::module('album')->getSingle();

    $youtube = new Google_Service_YouTube($client);

    $videos = [];
    // get the tracklist for the global post
    $tracklist = json_decode($album->albumTracklist);
    $favorites = $album->favorites;

		$reviewTracks = App::module('song')->get([
			'meta_query' => [
				[
				      'key' => 'itunesCollectionId',
				      'value' => $album->itunesCollectionId,
				]
			]
		]);


    $temp = [];
    foreach ($reviewTracks as $index => $value)
    {
      $temp[$value->title] = $value;
    }

    $reviewTracks = $temp;

    if( $favorites )
      $favorites = explode(",", $favorites);

    if( $favorites === "" )
      $favorites = [];

    $searchResponse =[];

    foreach ($favorites as $index => $favorite)
    {
      $res = $youtube->search->listSearch('id,snippet', array(
        'q' => str_replace(" ", "+", "{$album->artist}+{$favorite}"),
        'maxResults' => 1,
      ));

      if( $res )
      {
        foreach ($res['items'] as $searchResult)
        {
          $searchResponse[] = [ $searchResult['id']['videoId'], $favorite ];
        }
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

    foreach ($searchResponse as $index => $id)
    {
      $html[] = "<div class='embed-wrapper'><h4>{$id[1]}</h4><a href='#' data-video-id='{$id[0]}' data-video-name='{$id[1]}' class='youtube-link'><i class='fa fa-youtube'></i></a></div>";
    }

    $html[] = "</div></div><div class='tab'><div class='tab-label'><i class='fa fa-list-ol'></i></div><div class='tab-inner'><h3>Tracklist</h3><hr /><ul class='tracklist'>";

    foreach ($tracklist as $index => $el)
    {
      $strong = in_array($el->name, $favorites);

      $hasSeparatePost = isset($reviewTracks[$el->name]);

      $num = $index + 1;

      if( $hasSeparatePost )
      {
          $link = $reviewTracks[$el->name]->url;
          $html[] = "<li><a href='{$link}' style='color:white;'><strong data-favorite-track='{$el->name}'>{$num}: {$el->name}</strong></a></li>";
      }
      elseif( $strong )
          $html[] = "<li><strong data-favorite-track='{$el->name}'>{$num}: {$el->name}</strong></li>";
      else
          $html[] = "<li>{$num}: {$el->name}</li>";
    }

    $html =  implode("", $html) . "</ul></div></div></div>";
    return $html;
  }
}
