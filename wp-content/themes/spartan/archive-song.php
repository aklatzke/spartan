load header

partial navbar

:: div { class : container-fluid }
	[row
		:records song { limit: 30, var: song, order: 'desc' }
			:col 6 { class : tile-color, color: rand | 200 255 | }
					<a href="song.url">
						rawImg song.featuredImage
						::h4
							<span class='artist'>song.artist</span>
							<br />
							<span class='album'>song.title</span>
						::/
					</a>
			:endcol
		:endrecords
	end]

::/

load sidebar
load footer
