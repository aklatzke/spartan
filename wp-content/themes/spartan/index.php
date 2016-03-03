load header

partial navbar

<div class="container-fluid scrollable">
	[row
		:records album { limit: 30, var: album, order: 'desc' }
			:col 6 { class : tile-color, color: rand | 200 255 | }
					<a href="album.url">
						rawImg album.featuredImage
						::h4
							<span class='artist'>album.artist</span>
							<br />
							<span class='album'>album.title</span>
						::/
					</a>
			:endcol
		:endrecords
	end]
</div>

load footer
