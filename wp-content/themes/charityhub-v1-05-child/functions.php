<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/stylesheet/rs-style.css'/*
,
        array('parent-style')
*/
    );
   }
?>


<?php
	
/* This is disabled on DEV 
*  But it should be live on PROD
*
*
*/
add_action('wp_footer', 'add_googleanalytics');
function add_googleanalytics() { ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67794588-1', 'auto');
  ga('send', 'pageview');

</script>



<?php } ?>