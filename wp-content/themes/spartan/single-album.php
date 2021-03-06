load header

partial navbar

<div class="container-fluid">
  :single album

    [row

      :col 12 { class: holding-cell }

      :endcol

      :col 12 { class: media-cell }
        <span class='inner background' style="background-image: url('album.featuredImage')">
          render TracklistRenderer
        </span>
      :endcol
      <?php if( isset($album['font']) && $album['font'] !== "" ): ?>
          <link href='https://fonts.googleapis.com/css?family=album.font:album.weight' rel='stylesheet' type='text/css'>
      <?php endif; ?>
      <div class="col-md-12 info-cell" style="background-color:album.color;color:album.textColor;font-family:album.font;">
        <h4>album.title</h4>
        <h5>album.artist - album.releaseDate</h5>

        <div class="review">
          album.review
        </div>

        partial pager
      </div>

    end]
  :endsingle
</div>


load footer
