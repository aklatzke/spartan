<?php
  $prev = get_next_post();
  $next = get_previous_post();
?>

<div class="pager pager-prev">
  <div class="pager-inner">
    <a href="http://<?php echo $_SERVER["HTTP_HOST"]  . "/" . $prev->post_name; ?>"><i class="fa fa-chevron-left"></i></a>
  </div>
</div>

<div class="pager pager-next">
  <div class="pager-inner">
    <a href="http://<?php echo $_SERVER["HTTP_HOST"]  . "/" . $next->post_name; ?>"><i class="fa fa-chevron-right"></i></a>
  </div>
</div>
