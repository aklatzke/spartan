load header

partial navbar

<div class="container-fluid">
  :single song

    [row

      :col 12 { class: holding-cell }

      :endcol

      :col 12 { class: media-cell }
        <span class='inner' style="background-image: url('song.featuredImage')">
          render TrackMediaRenderer
        </span>
      :endcol

      <div class="col-md-12 info-cell" style="background-color:song.color;color:song.textColor;font-family:song.font;">
        <h4>song.title</h4>
        <h5>song.artist - song.album</h5>

        <div class="review">
          song.review
        </div>
      </div>

    end]
  :endsingle
</div>


load footer
