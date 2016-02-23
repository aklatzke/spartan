!php
	$albums = App::module('album')->get(['orderby' => 'desc']);

	$ids = array_map(function($album){
		return $album->ID;
	}, $albums);
!!

load header

:: div { class : container-fluid }
	[row
		:do count($albums)
			:col 3 { class : tile-color, color: rand | 1 255 | }
				:record album { type : album, record: $i, var: album }
					<div>
						<p>album.artist - album.title</p>
					</div>
				:endrecord
			:endcol
		:enddo
	end]

::/

load sidebar
load footer
